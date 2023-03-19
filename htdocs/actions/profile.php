<?php

require('../../include/mellivora.inc.php');

enforce_authentication(
    CONST_USER_CLASS_USER,
    true
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'edit') {

        //set the user type and competing status for the user with at least one correct submission
        if($user = get_user_with_score($_SESSION['id'])) {

            $user['competing'] = ($_POST['non_competitor'] == 1) ? 0 : 1;

            db_update(
                'users',
                array(
                   'competing'=>$user['competing']
                ),
                array(
                   'id'=>$_SESSION['id']
                )
              );
            
            $user_id = $user['user_id'];
            $user_type = check_user_type($user);
        }
        //set the user type = 0 otherwise
        else {

            $user = db_select_one(
                'users',
                array(
                    'id',
                    'team_name',
                    'user_type'
                ),
                array('id'=>$_SESSION['id'])
            );

            $user_id = $user['id'];
            $user_type = array('before' => $user['user_type'], 'after' => 0);
        }

        if($user_type['before'] != $user_type['after']) {

            update_user_type($user_id, $user_type['after']);
        }

        //link Discord account and set Discord ID if successful
        if (strlen($_POST['discord_id']) == 18) {

            $discord_user = link_discord_account(
                $_POST['discord_id'],
                $user['team_name'],
                $user_type['before'],
                $user_type['after']
            );

            if ($discord_user['id'] != 0) {
                $solved_challenges = db_query_fetch_all('
                    SELECT
                        c.discord_id
                    FROM challenges AS c
                    INNER JOIN submissions AS s ON c.id = s.challenge
                    WHERE
                        s.user_id = :user_id AND
                        NOT c.discord_id = 0 AND
                        s.correct = 1',
                    array(
                        'user_id'=>$_SESSION['id']
                    )
                );
                unlock_discord_channels($solved_challenges, $discord_user['id']);
            }
        }
        else {

            $discord_user['id'] = 0;
        }

        db_update(
          'users',
          array(
             'full_name'=>$_POST['full_name'],
             'country_id'=>$_POST['country'],
             'discord_id'=>$discord_user['id']
          ),
          array(
             'id'=>$_SESSION['id']
          )
        );

         redirect('profile?generic_success=1');
    }

    else if ($_POST['action'] == '2fa_generate') {

        db_insert(
            'two_factor_auth',
            array(
                'user_id'=>$_SESSION['id'],
                'secret'=>generate_two_factor_auth_secret(32)
            )
        );

        db_update(
            'users',
            array(
                '2fa_status'=>'generated'
            ),
            array(
                'id'=>$_SESSION['id']
            )
        );

        redirect('profile?generic_success=1');
    }

    else if ($_POST['action'] == '2fa_enable') {

        if (!validate_two_factor_auth_code($_POST['code'])) {
            message_error('Incorrect code');
        }

        db_update(
            'users',
            array(
                '2fa_status'=>'enabled'
            ),
            array(
                'id'=>$_SESSION['id']
            )
        );

        redirect('profile?generic_success=1');
    }

    else if ($_POST['action'] == '2fa_disable') {

        db_update(
            'users',
            array(
                '2fa_status'=>'disabled'
            ),
            array(
                'id'=>$_SESSION['id']
            )
        );

        db_delete(
            'two_factor_auth',
            array(
                'user_id'=>$_SESSION['id']
            )
        );

        redirect('profile?generic_success=1');
    }

    else if ($_POST['action'] == 'reset_password') {

        $user = db_select_one(
            'users',
            array('passhash'),
            array('id'=>$_SESSION['id'])
        );

        if (!check_passhash($_POST['current_password'], $user['passhash'])) {
            message_error('Current password was incorrect.');
        }

        if (!strlen($_POST['new_password'])) {
            message_error('Password cannot be empty.');
        }

        if ($_POST['new_password'] != $_POST['new_password_again']) {
            message_error('Passwords did not match.');
        }

        $new_passhash = make_passhash($_POST['new_password']);

        $password_set = db_update(
            'users',
            array(
                'passhash'=>$new_passhash
            ),
            array(
                'id'=>$_SESSION['id']
            )
        );

        if (!$password_set) {
            message_error('Password not set.');
        }

        redirect('profile?generic_success=1');
    }
}