<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

validate_id($_GET['id']);

head('Site management');
menu_management();
section_subhead('Edit user type');

$user_type = db_select_one(
    'user_types',
    array('*'),
    array('id' => $_GET['id'])
);

form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_user_type');
form_input_text('Title', $user_type['title']);
form_textarea('Description', $user_type['description']);
form_input_checkbox('Scoreboard', $user_type['scoreboard']);
form_input_text('Score Required', $user_type['score_required']);
form_input_text('Discord ID', $user_type['discord_id']);
form_input_text('Badge', $user_type['badge']);
form_hidden('action', 'edit');
form_hidden('id', $_GET['id']);
form_button_submit('Save changes');
form_end();

section_subhead('Delete user type');
form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_user_type');
form_input_checkbox('Delete confirmation');
form_hidden('action', 'delete');
form_hidden('id', $_GET['id']);
message_inline_red('Warning! Any users of this type will be without a type.
You must manually give them a type in the DB. If no types will exist after this action, you must set their type to 0.');
form_button_submit('Delete user type', 'danger');
form_end();

foot();