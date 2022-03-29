<?php

require('../include/mellivora.inc.php');

validate_id($_GET['id']);

head(lang_get('challenge_details'));

if (cache_start(CONST_CACHE_NAME_CHALLENGE . $_GET['id'], Config::get('MELLIVORA_CONFIG_CACHE_TIME_CHALLENGE'))) {

    $challenge = db_query_fetch_one('
        SELECT
           ch.title,
           ch.description,
           ch.available_from AS challenge_available_from,
           ca.title AS category_title,
           ca.available_from AS category_available_from
        FROM challenges AS ch
        LEFT JOIN categories AS ca ON ca.id = ch.category
        WHERE
           ch.id = :id AND
           ch.exposed = 1 AND
           ca.exposed = 1',
        array('id'=>$_GET['id'])
    );

    if (empty($challenge)) {
        message_generic(
            lang_get('sorry'),
            lang_get('no_challenge_for_id'),
            false
        );
    }

    $now = time();
    if ($challenge['challenge_available_from'] > $now || $challenge['category_available_from'] > $now) {
        message_generic(
            lang_get('sorry'),
            lang_get('challenge_not_available'),
            false
        );
    }

    section_head($challenge['title']);

    $user_types = db_select_all(
        'user_types',
        array(
            'id',
            'title',
            'scoreboard'
        )
    );

    // no user types
    if (empty($user_types)) {
        $submissions = db_query_fetch_all(
            'SELECT
                u.id AS user_id,
                u.team_name,
                s.added,
                c.available_from
              FROM users AS u
              LEFT JOIN submissions AS s ON s.user_id = u.id
              LEFT JOIN challenges AS c ON c.id = s.challenge
              WHERE
                 u.competing = 1 AND
                 s.challenge = :id AND
                 s.correct = 1
              ORDER BY s.added ASC',
            array('id' => $_GET['id'])
        );
    
        $num_correct_solves = count($submissions);
    
        if (!$num_correct_solves) {
            echo lang_get('challenge_not_solved');
        }
    
        else {
            $user_count = get_num_participating_users();
            echo lang_get(
                'challenge_solved_by_percentage',
                array(
                    'solve_percentage' => number_format((($num_correct_solves / $user_count) * 100), 1)
                )
            );
    
            print_challenge_scoreboard($submissions);
        }    
    }
    // at least one user type
    else {
        foreach ($user_types as $user_type) {
            if ($user_type['scoreboard']) {
                section_subhead($user_type['title']);
                $submissions = db_query_fetch_all(
                    'SELECT
                        u.id AS user_id,
                        u.team_name,
                        s.added,
                        c.available_from
                      FROM users AS u
                      LEFT JOIN submissions AS s ON s.user_id = u.id
                      LEFT JOIN challenges AS c ON c.id = s.challenge
                      WHERE
                         u.user_type = :user_type AND
                         s.challenge = :id AND
                         s.correct = 1
                      ORDER BY s.added ASC',
                    array(
                        'id' => $_GET['id'],
                        'user_type'=>$user_type['id']
                    )
                );
            
                $num_correct_solves = count($submissions);
            
                if (!$num_correct_solves) {
                    echo lang_get('challenge_not_solved');
                }
            
                else {
                    print_challenge_scoreboard($submissions);
                }  
            }
        }

    }


    cache_end(CONST_CACHE_NAME_CHALLENGE . $_GET['id']);
}

foot();