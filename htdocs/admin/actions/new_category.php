<?php

require('../../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    validate_xsrf_token($_POST[CONST_XSRF_TOKEN_KEY]);

    if ($_POST['action'] == 'new') {

        require_fields(array('title'), $_POST);

        $discord_id = 0;
        if ($_POST['discord'] == true) {
            $discord_id = create_discord_category($_POST['title']);
        }

       $id = db_insert(
          'categories',
          array(
             'added'=>time(),
             'added_by'=>$_SESSION['id'],
             'title'=>$_POST['title'],
             'description'=>$_POST['description'],
             'exposed'=>$_POST['exposed'],
             'discord_id'=>$discord_id,
             'available_from'=>strtotime($_POST['available_from']),
             'available_until'=>strtotime($_POST['available_until'])
          )
       );

        if ($id) {
            redirect(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'edit_category.php?id='.$id);
            if ($_POST['discord'] == true) {
                create_discord_category($_POST['title']);
            }
        } else {
            message_error('Could not insert new category.');
        }
    }
}