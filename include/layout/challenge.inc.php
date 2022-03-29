<?php

function print_challenge_scoreboard($submissions) {
    echo '
    <table class="challenge-table table table-striped table-hover">
    <thead>
    <tr>
      <th>',lang_get('position'),'</th>
      <th>',lang_get('team'),'</th>
      <th>',lang_get('solved'),'</th>
    </tr>
    </thead>
    <tbody>
    ';
     $i = 1;
     foreach ($submissions as $submission) {
         echo '
           <tr>
             <td>', number_format($i), ' ', get_position_medal($i), '</td>
             <td class="team-name"><a href="user.php?id=', htmlspecialchars($submission['user_id']), '">', htmlspecialchars($submission['team_name']), '</a></td>
             <td>', time_elapsed($submission['added'], $submission['available_from']), ' ', lang_get('after_release'), ' (', date_time($submission['added']), ')</td>
           </tr>
           ';
         $i++;
     }

     echo '
    </tbody>
    </table>
      ';
}