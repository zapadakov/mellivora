<?php

require('../../include/mellivora.inc.php');

enforce_authentication(
    CONST_USER_CLASS_USER,
    true
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'edit') {

        $competing = (($_POST['country'] > 1) ? 1 : 0);
        $user_type = (($_POST['type'] > 0) ? $_POST['type'] : 0);

        if (strlen($_POST['discord_id']) == 18) {

            $user = db_select_one(
                'users',
                array(
                    'team_name',
                    'user_type'
                ),
                array('id'=>$_SESSION['id'])
            );
            $user_types = db_select_all(
                'user_types',
                array(
                    'id',
                    'discord_id'
                )
            );
            $discord_user = link_discord_account(
                $_POST['discord_id'],
                $user['team_name'],
                $user['user_type'],
                $user_type,
                $user_types
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

        } else {
            $discord_user['id'] = 0;
        }

        db_update(
          'users',
          array(
             'full_name'=>$_POST['full_name'],
             'country_id'=>$_POST['country'],
             'discord_id'=>$discord_user['id'],
             'competing'=>$competing,
             'user_type'=>$user_type
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