<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_id($_POST['id']);
    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'edit') {

        db_update(
          'user_types',
          array(
             'title'=>$_POST['title'],
             'description'=>$_POST['description'],
             'scoreboard'=>$_POST['scoreboard'],
             'score_required'=>$_POST['score_required'],
             'discord_id'=>$_POST['discord_id'],
             'badge'=>$_POST['badge']
          ),
          array(
             'id'=>$_POST['id']
          )
        );

        redirect(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'list_user_types.php?generic_success=1');
    }

    else if ($_POST['action'] == 'delete') {

        if (!$_POST['delete_confirmation']) {
            message_error('Please confirm delete');
        }

        db_delete(
            'user_types',
            array(
                'id'=>$_POST['id']
            )
        );

        redirect(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'list_user_types.php?generic_success=1');
    }
}