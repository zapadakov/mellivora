<?php

require('../../include/mellivora.inc.php');

enforce_authentication(
    CONST_USER_CLASS_USER,
    true
);

$time = time();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if (Config::get('MELLIVORA_CONFIG_RECAPTCHA_ENABLE_PRIVATE')) {
        validate_captcha();
    }

    if ($_POST['action'] == 'submit_flag') {

        validate_id($_POST['challenge']);

        if (empty($_POST['flag'])) {
            message_error('Did you really mean to submit an empty flag?');
        }

        $submissions = db_query_fetch_all(
            'SELECT
              s.added,
              s.marked,
              s.correct,
              c.automark
            FROM
              submissions AS s JOIN challenges AS c ON c.id = s.challenge
            WHERE
              s.challenge = :challenge AND
              s.user_id = :user_id',
            array(
                'user_id' => $_SESSION['id'],
                'challenge' => $_POST['challenge']
            )
        );

        $latest_submission_attempt = 0;
        $num_attempts = 0;
        foreach ($submissions as $submission) {
            $latest_submission_attempt = max($submission['added'], $latest_submission_attempt);

            // make sure user isn't "accidentally" submitting a correct flag twice
            if ($submission['correct']) {
                message_error('You may only submit a correct flag once.');
            }

            // don't allow multiple unmarked submissions to manually marked challenges
            if (!$submission['automark'] && !$submission['marked']) {
                message_error('You already have an unmarked submission for this challenge.');
            }

            $num_attempts++;
        }

        // get challenge information
        $challenge = db_select_one(
            'challenges',
            array(
                'flag',
                'category',
                'case_insensitive',
                'automark',
                'available_from',
                'available_until',
                'num_attempts_allowed',
                'min_seconds_between_submissions',
                'discord_id',
                'title'
            ),
            array(
                'id' => $_POST['challenge']
            )
        );

        $seconds_since_submission = $time-$latest_submission_attempt;
        if ($seconds_since_submission < $challenge['min_seconds_between_submissions']) {
            message_generic('Sorry','You may not submit another solution for this challenge for another ' . seconds_to_pretty_time($challenge['min_seconds_between_submissions']-$seconds_since_submission));
        }

        if ($challenge['num_attempts_allowed'] && $num_attempts >= $challenge['num_attempts_allowed']) {
            message_generic('Sorry','You\'ve already tried '.$challenge['num_attempts_allowed'].' times. Sorry!');
        }

        if ($challenge['available_from'] && $time < $challenge['available_from']) {
            message_generic('Sorry','This challenge hasn\'t started yet.');
        }

        if ($challenge['available_until'] && $time > $challenge['available_until']) {
            message_generic('Sorry','This challenge has expired.');
        }

        $correct = false;

        // automark the submission
        if ($challenge['automark']) {

            // lots of people submit with trailing whitespace..
            // we probably never want automarked keys with whitespace
            // at beginning or end, so trimming is probably fine.
            $_POST['flag'] = trim($_POST['flag']);
            $challenge['flag'] = trim($challenge['flag']);

            if ($challenge['case_insensitive']) {
                if (strcasecmp($_POST['flag'], $challenge['flag']) == 0) {
                    $correct = true;
                }
            } else {
                if (strcmp($_POST['flag'], $challenge['flag']) == 0) {
                    $correct = true;
                }
            }
        }

        db_insert(
            'submissions',
            array(
                'added'=>$time,
                'challenge'=>$_POST['challenge'],
                'user_id'=>$_SESSION['id'],
                'flag'=>$_POST['flag'],
                'correct'=>($correct ? '1' : '0'),
                'marked'=>($challenge['automark'] ? '1' : '0')
            )
        );

        if ($correct) {
            
            $user = get_user_with_score($_SESSION['id']);

            //update user type if needed
            $user_type = check_user_type($user);

            if($user_type['before'] != $user_type['after']) {

                update_user_type($user['user_id'], $user_type['after']);

                if (($user['discord_id'] != 0) and ($user_type['after'] > 0)) {
                    $discord_user = link_discord_account($user['discord_id'], $user['team_name'], $user_type['before'], $user_type['after']);
                }
            }
            
            //unlock related Discord channel in case of correct submission (if the channel exists)
            if (($user['discord_id'] != 0) and ($challenge['discord_id'] != 0)) {
                unlock_discord_channels(array(array('discord_id'=>$challenge['discord_id'])), $user['discord_id']);
            }
            
            //send congrat to Discord ctf channel
            //if ($user['competing'] == 1) {
                send_discord_message(
                    'new_solver',
                    array(
                        'user' => ($user['discord_id'] != 0 ? '<@!'.$user['discord_id'].'>' : $user['team_name']),
                        'challenge_id' => $_POST['challenge'],
                        'challenge_title' => $challenge['title']
                    )
                );
            //}
        }
        
        else {
            $user = db_select_one(
                'users',
                array(
                    'discord_id',
                    'team_name'
                ),
                array(
                    'id' => $_SESSION['id']
                )
            );
        }

        //send submission details to Discord admin channel
        send_discord_message(
            'new_submission',
            array(
                'user' => ($user['discord_id'] != 0 ? '<@!'.$user['discord_id'].'>' : $user['team_name']),
                'num_attempts' => $num_attempts+1,
                'challenge_title' => $challenge['title'],
                'result' => ($correct ? lang_get("correct_flag") : lang_get("incorrect_flag")),
                'flag' => $_POST['flag']
            )
        );

        if (!$challenge['automark']) {
            redirect('challenges?status=manual');
        }

        redirect('challenges?category='.$challenge['category'].'&status=' . ($correct ? 'correct' : 'incorrect'));
    }
}