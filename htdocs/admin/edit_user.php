<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

validate_id($_GET['id']);

$user = db_select_one(
    'users',
    array(
        'team_name',
        'full_name',
        'email',
        'enabled',
        'competing',
        'country_id',
        'discord_id',
        'user_type'
    ),
    array('id' => $_GET['id'])
);

head('Site management');
menu_management();

section_subhead('Edit user: ' . $user['team_name']);

form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_user');
form_input_text('Email', $user['email']);
form_input_text('Team name', $user['team_name']);
form_input_text('Full name', $user['full_name']);

$opts = db_query_fetch_all('SELECT * FROM countries ORDER BY country_name ASC');
form_select($opts, 'Country', 'id', $user['country_id'], 'country_name');

$opts = db_query_fetch_all('SELECT * FROM user_types');
form_select($opts, 'Type', 'id', $user['user_type'], 'title', '', lang_get('select_team_type'));

form_input_text('Discord ID', $user['discord_id']);

form_input_checkbox('Enabled', $user['enabled']);
form_input_checkbox('Competing', $user['competing']);
form_hidden('action', 'edit');
form_hidden('id', $_GET['id']);
form_button_submit('Save changes');
form_end();

section_subhead('Reset password');
form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_user');
form_input_checkbox('Reset confirmation');
form_hidden('action', 'reset_password');
form_hidden('id', $_GET['id']);
form_button_submit('Reset password', 'warning');
form_end();

section_subhead('Delete user');
form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/edit_user');
form_input_checkbox('Delete confirmation');
form_hidden('action', 'delete');
form_hidden('id', $_GET['id']);
message_inline_red('Warning! This will delete all submissions made by this user!');
form_button_submit('Delete user', 'danger');

foot();