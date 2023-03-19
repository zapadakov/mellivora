<?php

require('../../include/mellivora.inc.php');

enforce_authentication(CONST_USER_CLASS_MODERATOR);

head('User types');
menu_management();
section_head('Users types');

echo '
    <table id="files" class="table table-striped table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Score required<br></th>
          <th>Members</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
    ';

$types = db_query_fetch_all('
  SELECT
    ut.id,
    ut.title,
    ut.description,
    ut.score_required,
    COUNT(u.id) AS members
  FROM
    user_types AS ut
  LEFT JOIN
    users AS u ON ut.id = u.user_type
  GROUP BY
    ut.id
  ORDER BY
    ut.id
  ASC
');

foreach($types as $type) {
    echo '
    <tr>
        <td>',htmlspecialchars($type['id']),'</td>
        <td>',htmlspecialchars($type['title']),'</td>
        <td>',short_description($type['description'], 50),'</td>
        <td>',htmlspecialchars($type['score_required']),'</td>
        <td>',htmlspecialchars($type['members']),'</td>
        <td><a href="edit_user_type.php?id=',htmlspecialchars($type['id']), '" class="btn btn-xs btn-primary">Edit</a></td>
    </tr>
    ';
}

echo '
      </tbody>
    </table>
     ';

$non_competitors_user_type = CONST_NON_COMPETITORS_USER_TYPE;
section_subhead('Reassign user types by required score');
form_start(Config::get('MELLIVORA_CONFIG_SITE_ADMIN_RELPATH') . 'actions/reassign_user_types');
message_inline_blue("Only users with at least one correct submission are reassigned. Non-competitors user type ID: $non_competitors_user_type. User type with negative required score is not targeted.");
message_inline_red('Warning! This may change the user type for many users!');
form_input_checkbox('Update Discord', 1);
form_hidden('action', 'reassign');
form_button_submit('Reassign user types', 'warning');
form_end();

foot();