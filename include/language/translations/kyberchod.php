<?php

$lang['sorry'] = 'Sorry';
$lang['after_release'] = 'after release';
$lang['position'] = 'Position';
$lang['team'] = 'Přezdívka';
$lang['points'] = 'Body';
$lang['points_short'] = 'b';
$lang['country'] = 'Škola';
$lang['solved'] = 'Solved';
$lang['home'] = 'Úvod';
$lang['profile'] = 'Profil';
$lang['scores'] = 'Výsledky';
$lang['log_in'] = 'Přihlásit';
$lang['log_out'] = 'Odhlásit';
$lang['close'] = 'Zavřít';
$lang['error'] = 'Chyba';
$lang['profile_settings'] = 'Nastavení profilu';
$lang['view_public_profile'] = 'Zobrazit veřejný profil';
$lang['hint'] = 'Hint';
$lang['hints'] = 'Hints';
$lang['no_hints_available'] = 'No hints have been made available yet.';
$lang['challenge'] = 'Challenge';
$lang['added'] = 'Added';
$lang['challenges'] = 'Útoky';
$lang['category'] = 'Category';
$lang['ctf_empty'] = 'Your CTF is looking a bit empty! Start by adding a category using the management console.';
$lang['available_in'] = 'Available in';
$lang['cat_unavailable'] = 'Category unavailable';

$lang['two_factor_auth'] = 'Two-factor authentication';
$lang['two_factor_auth_required'] = 'Two-factor authentication required';
$lang['enable_two_factor_auth'] = 'Enable two-factor authentication';
$lang['disable_two_factor_auth'] = 'Disable two-factor authentication';
$lang['generate_codes'] = 'Generate codes';
$lang['using_totp'] = 'using TOTP';
$lang['scan_with_totp_app'] = 'Scan with your TOTP app';
$lang['authenticate'] = 'Authenticate';

$lang['save_changes'] = 'Uložit změny';
$lang['reset_password'] = 'Reset hesla';
$lang['choose_password'] = 'Choose password';
$lang['password'] = 'Password';
$lang['email_password_on_signup'] = 'Heslo bude vygenerováno a zasláno na zadanou emailovou adresu.';

$lang['register'] = 'Registrace';
$lang['register_your_team'] = 'Registrace';
$lang['account_signup_information'] = 'Soutěžit mohou jen ti, kdo mají v profilu vybránu svou školu. Chybí-li ve výběru, registruj se pod Czech Republic (tyto účty nesoutěží). Ozvi se pak na Discordu nebo emailem a školu doplníme. {password_information}';
$lang['team_name'] = 'Přezdívka';
$lang['full_name'] = 'Celé jméno';
$lang['select_team_type'] = 'Vyber prosím soutěžní kategorii';
$lang['registration_closed'] = 'Registration is currently closed, but you can still <a href="interest">register your interest for upcoming events</a>.';
$lang['please_fill_details_correctly'] = 'Please fill in all the details correctly.';
$lang['invalid_team_type'] = 'That does not look like a valid team type.';
$lang['team_name_too_long_or_short'] = 'Moc krátká, nebo příliš dlouhá přezdívka.';
$lang['email_not_whitelisted'] = 'Email not on whitelist. Please choose a whitelisted email or contact organizers.';
$lang['user_already_exists'] = 'Účet s touto přezdívkou nebo emailovou adresou už existuje.';
$lang['signup_successful'] = 'Signup successful';
$lang['signup_successful_text'] = 'Thank you for registering! Your chosen email is: {email}. Make sure to check your spam folder as emails from us may be placed into it. Please stay tuned for updates!';
$lang['your_password_is'] = 'Tvé heslo je';
$lang['your_password_was_set'] = 'Your password was chosen by you on signup.';

$lang['signup_email_subject'] = '{site_name} účet';
$lang['signup_email_success'] =
    '{site_name} - registrace účtu {team_name} byla úspěšná.' .
    "\r\n" .
    "\r\n" .
    '{signup_email_availability}' .
    "\r\n" .
    "\r\n" .
    '{signup_email_password}' .
    "\r\n" .
    "\r\n" .
    'Po přihlášení si v profilu můžeš vyplnit celé jméno. Bude se hodit, pokud bude potřeba ověřit tvůj status žáka ve škole (např. když něco vyhraješ).' .
    "\r\n" .
    "\r\n" .
    'Máš-li účet na Discordu, rádi tě přivítáme na našem serveru: aHR0cHM6Ly9kaXNjb3JkLmdnL0pLWTNXUlkyeGg=' .
    "\r\n" .
    "\r\n" .
    'Těšíme se,' .
    "\r\n" .
    '{site_name}'
;
$lang['signup_email_account_availability_message_login_now'] = 'Teď se můžeš přihlásit pomocí emailové adresy a hesla.';
$lang['signup_email_account_availability_message_login_later'] = 'Once the competition starts, please use this email address to log in.';

$lang['register_interest'] = 'Register interest';
$lang['register_interest_text'] = 'We are likely to run more CTFs in the future. Input your email below if you are interested in hearing from us about future competitions. We will not spam you. Your email address will not be shared with third parties.';

$lang['expression_of_interest'] = 'Expression of interest';
$lang['recruitment_text'] = 'Like the look of our sponsors? They are all hiring. Please fill out the form below if you wish to be contacted with recruitment information. Each team member can fill out the form individually. We will not share your details with anyone but our sponsors. We will not spam you. Only addresses entered into this form will be shared.';
$lang['name_optional'] = 'Name (optional)';
$lang['city_optional'] = 'City (optional)';

$lang['email_address'] = 'Emailová adresa';
$lang['password'] = 'Heslo';
$lang['name_nick'] = 'Name / team name / nick';
$lang['remember_me'] = 'Zapamatovat';
$lang['forgotten_password'] = 'Zapomenuté heslo';

$lang['please_request_view'] = 'Please request a view';
$lang['please_request_page'] = 'Please request a page to show';
$lang['please_supply_country_code'] = 'Please supply a valid country code';
$lang['not_a_valid_link'] = 'That is not a valid link.';
$lang['not_a_valid_email'] = 'That doesn\'t look like an email. Please go back and double check the form.';
$lang['please_select_country'] = 'Vyber prosím školu';

$lang['no_file_found'] = 'No file found with this ID.';
$lang['invalid_team_key'] = 'Invalid team key.';
$lang['user_not_enabled'] = 'This user is not enabled, and can as such not download files.';
$lang['file_not_available'] = 'This file is not available yet.';

$lang['challenge_details'] = 'Challenge details';
$lang['no_challenge_for_id'] = 'No challenge found with this ID, or challenge not public';
$lang['no_category_for_id'] = 'No category found with that ID, or category not public';
$lang['challenge_not_available'] = 'This challenge is not yet available';
$lang['challenge_not_solved'] = 'This challenge has not yet been solved by any teams.';
$lang['challenge_solved_by_percentage'] = 'This challenge has been solved by {solve_percentage}% of actively participating users.';

$lang['challenge_solved_first'] = 'First to solve this challenge!';
$lang['challenge_solved_second'] = 'Second to solve this challenge!';
$lang['challenge_solved_third'] = 'Third to solve this challenge!';

$lang['correct_flag'] = 'Correct flag, you are awesome!';
$lang['incorrect_flag'] = 'Incorrect flag, try again.';
$lang['submission_awaiting_mark'] = 'Your submission is awaiting manual marking.';
$lang['please_enter_flag'] = 'Please enter flag for challenge:';
$lang['submit_flag'] = 'Submit flag';
$lang['no_remaining_submissions'] = 'You have no remaining submission attempts. If you have made an erroneous submission, please contact the organizers.';

$lang['no_category_with_id'] = 'No category found with that ID';

$lang['cat_unavailable_explanation'] = 'This category is not available. It is open from {available_from} ({available_from_time_remaining} from now) until {available_until} ({available_until_time_remaining} from now)';

$lang['hidden_challenge_worth'] = 'Hidden challenge worth {pts}b';

$lang['available_in'] = 'Available in {available_in} (from {from} until {to})';
$lang['minimum_time_between_submissions'] = 'Minimum of {time} between submissions.';
$lang['num_submissions_remaining'] = '{num_remaining} submissions remaining.';
$lang['time_remaining'] = '{time} remaining';

$lang['challenge_relies_on'] = 'The details for this challenge will be displayed only after {relies_on_link} in the {relies_on_category_link} category has been solved (by any team).';

$lang['no_reset_data'] = 'No reset data found.';

$lang['scoreboard'] = 'Výsledky';
$lang['first_solvers'] = 'Nejrychlejší';
$lang['percentage_solvers'] = 'Vyřešilo';
$lang['unsolved'] = 'Unsolved';

$lang['user_details'] = 'User details';
$lang['no_user_found'] = 'No user found with that ID';
$lang['non_competing_user'] = 'This user is listed as a non-competitor.';
$lang['no_information'] = 'No information';
$lang['no_solves'] = 'This user has not solved any challenges yet!';
$lang['no_exceptions'] = 'No exceptions for this user';
$lang['solved_challenges'] = 'Vyřešené úlohy';
$lang['total_solves'] = 'Total:';
$lang['no_challenges_solved'] = 'Zatím nic!';

$lang['action_success'] = 'Success!';
$lang['action_failure'] = 'Failure!';
$lang['action_something_went_wrong'] = 'Something went wrong! Most likely the action you attempted has failed.';
$lang['generic_error'] = 'Something went wrong.';

$lang['year'] = 'year';
$lang['month'] = 'month';
$lang['day'] = 'day';
$lang['hour'] = 'hour';
$lang['minute'] = 'minute';
$lang['second'] = 'second';
$lang['append_to_time_to_make_plural'] = 's';

$lang['user_class_user'] = 'User';
$lang['user_class_moderator'] = 'Moderator';
$lang['user_class_unknown'] = 'Unknown user class';

$lang['manage'] = 'Manage';
$lang['add_news_item'] = 'Add news item';
$lang['list_news_item'] = 'List news items';
$lang['news'] = 'News';

$lang['categories'] = 'Categories';
$lang['add_category'] = 'Add category';
$lang['list_categories'] = 'List categories';

$lang['add_challenge'] = 'Add challenge';
$lang['list_challenges'] = 'List challenges';

$lang['submissions'] = 'Submissions';
$lang['list_submissions_in_need_of_marking'] = 'List submissions in need of marking';
$lang['list_all_submissions'] = 'List all submissions';

$lang['users'] = 'Users';
$lang['list_users'] = 'List users';
$lang['user_types'] = 'User types';
$lang['add_user_type'] = 'Add user type';
$lang['list_user_types'] = 'List user types';

$lang['signup_rules'] = 'Signup rules';
$lang['list_rules'] = 'List rules';
$lang['new_rule'] = 'New rule';
$lang['test_rule'] = 'Test rule';

$lang['single_email'] = 'Single email';
$lang['email_all_users'] = 'Email all users';

$lang['new_hint'] = 'New hint';
$lang['list_hints'] = 'List hints';

$lang['dynamic_content'] = 'Dynamic content';
$lang['new_menu_item'] = 'New menu item';
$lang['list_menu_items'] = 'List menu items';
$lang['menu'] = 'Menu';
$lang['new_page'] = 'New page';
$lang['list_pages'] = 'List pages';
$lang['pages'] = 'Pages';

$lang['exceptions'] = 'Exceptions';
$lang['list_exceptions'] = 'List exceptions';
$lang['clear_exceptions'] = 'Clear exceptions';

$lang['search'] = 'Search';
