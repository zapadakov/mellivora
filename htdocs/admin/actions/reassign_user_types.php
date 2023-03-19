<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'reassign') {

        $users = db_query_fetch_all('
            SELECT
                u.id AS user_id,
                u.team_name,
                u.user_type,
                u.discord_id,
                u.competing,
                SUM(c.points) AS score
            FROM
                users AS u
            LEFT JOIN
                submissions AS s ON u.id = s.user_id AND s.correct = 1
            LEFT JOIN
                challenges AS c ON c.id = s.challenge
            WHERE
                c.exposed = 1
            GROUP BY
                u.id'
        );

        foreach($users as $user) {

            $user_type = check_user_type($user);

            if($user_type['before'] != $user_type['after']) {

                update_user_type($user['user_id'], $user_type['after']);
                
                if (($_POST['update_discord']) and $user['discord_id'] > 0) {

                    link_discord_account($user['discord_id'], $user['team_name'], $user_type['before'], $user_type['after']);
                    usleep(25000); //(20000+reserve) All bots can make up to 50 requests per second to Discord API.
                }
            }
        }

        redirect(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'list_user_types.php?generic_success=1');

    }
}