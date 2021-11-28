<?php

use RestCord\DiscordClient;

function create_discord_category($name) {
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') && Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID')) {
        try {
            $client = new DiscordClient(['token' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN')]);

            $result = $client->guild->createGuildChannel(array(
                'guild.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                'name' => $name,
                'type' => 4
            ));
            return $result->id;

        } catch (Exception $e) {
            message_error('Caught exception connecting to Discord: ' . $e->getMessage());
        }
    }
    else {
        message_error('Set Discord parameters in config!');
    }
}

function create_discord_channel($name, $discord_category_id) {
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') && Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID') && Config::get('MELLIVORA_CONFIG_DISCORD_BOT_ID')) {
        try {
            $client = new DiscordClient(['token' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN')]);

            $result = $client->guild->createGuildChannel(array(
                'guild.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                'name' => $name,
                'type' => 0,
                'parent_id' => $discord_category_id
            ));
            //add bot permissions in a channel in order to manage permissions later
            $client->channel->editChannelPermissions(array(
                'channel.id' => $result->id,
                'overwrite.id' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_ID'),
                'type' => 1, //user
                'allow'=> 1024 //VIEW_CHANNEL
            ));
            //lock channel
            $client->channel->editChannelPermissions(array(
                'channel.id' => $result->id,
                'overwrite.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'), //everyone role id = guild id
                'type' => 0, //role
                'deny'=> 1024 //VIEW_CHANNEL
            ));
            return $result->id;

        } catch (Exception $e) {
            message_error('Caught exception connecting to Discord: ' . $e->getMessage());
        }
    }
    else {
        message_error('Set Discord parameters in config!');
    }
}

function unlock_discord_channels($discord_channels, $discord_user_id) {
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') && Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID')) {
        try {
            $client = new DiscordClient(['token' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN')]);

            foreach($discord_channels as $discord_channel) {
                $client->channel->editChannelPermissions(array(
                    'channel.id' => $discord_channel['discord_id'],
                    'overwrite.id' => $discord_user_id,
                    'type' => 1, //user
                    'allow'=> 1024 //VIEW_CHANNEL
                ));
            }

        } catch (Exception $e) {
            message_error('Caught exception connecting to Discord: ' . $e->getMessage());
        }
    }
    else {
        message_error('Set Discord parameters in config!');
    }
}

function get_discord_user($discord_user_id) {
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') && Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID')) {
        try {
            $client = new DiscordClient(['token' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN')]);
            
            $result = $client->user->getUser(array(
                'user.id' => intval($discord_user_id)
            ));
            return array(
                'id' => $result->id
            );

        } catch (Exception $e) {
            message_error(lang_get("discord_user_not_found"));
        }
    }
    else {
        message_error('Set Discord parameters in config!');
    }
}

function get_discord_member($discord_user_id) {
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') && Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID')) {
        try {
            $client = new DiscordClient(['token' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN')]);
            
            $client->guild->getGuildMember(array(
                'guild.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                'user.id' => intval($discord_user_id)
            ));
            return array(
                'id' => $result->id,
                'nick' => $result->nick
            );
            
        } catch (Exception $e) {
            message_error(lang_get("discord_user_not_found"));
        }
    }
    else {
        message_error('Set Discord parameters in config!');
    }
}

function link_discord_account($discord_user_id, $nick, $user_type_old, $user_type_new, $user_types) {
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') && Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID')) {
        try {
            $client = new DiscordClient(['token' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN')]);

            $user = $client->user->getUser(array(
                'user.id' => intval($discord_user_id)
            ));
            //valid user?
            if ($user->id > 0) {
                $member = $client->guild->getGuildMember(array(
                    'guild.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                    'user.id' => intval($discord_user_id)
                ));
                //guild member?
                if ($member) {
                    $client->guild->modifyGuildMember(array(
                        'guild.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                        'user.id' => intval($discord_user_id),
                        'nick' => $nick
                    ));
                    //link user_type to discord role
                    foreach($user_types as $user_type) {
                        if(($user_type_new == $user_type['id']) && ($user_type['discord_id'] > 0)) {
                            $client->guild->addGuildMemberRole(array(
                                'guild.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                                'user.id' => intval($discord_user_id),
                                'role.id' => intval($user_type['discord_id'])
                            ));
                        }
                        else if (($user_type_old == $user_type['id']) && (in_array($user_type['discord_id'], $member->roles))) {
                            $client->guild->removeGuildMemberRole(array(
                                'guild.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                                'user.id' => intval($discord_user_id),
                                'role.id' => intval($user_type['discord_id'])
                            ));
                        }
                    }
                    return array(
                        'id' => $user->id
                    );
                }
            }
   
        } catch (Exception $e) {
            message_error(lang_get("discord_user_not_linked"));
        }
    }
    else {
        message_error('Set Discord parameters in config!');
    }
}

function send_discord_message($type, $content) {
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') &&
        Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_GENERAL_ID') &&
        Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_GENERAL_TOKEN') &&
        Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_SUBMISSIONS_ID') &&
        Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_SUBMISSIONS_TOKEN')) {
        try {
            $client = new DiscordClient(['token' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN')]);

            if ($type == 'new_solver') {
                $client->webhook->executeWebhook(array(
                    'webhook.id' => Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_GENERAL_ID'),
                    'webhook.token' => Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_GENERAL_TOKEN'),
                    'content' => lang_get(
                        'new_solver',
                        array(
                            'role' => $content['role'],
                            'user' => $content['user'],
                            'challenge_id' => $content['challenge_id'],
                            'challenge_title' => $content['challenge_title']
                        )
                    )
                ));
            }
            if ($type == 'new_submission') {
                $client->webhook->executeWebhook(array(
                    'webhook.id' => Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_SUBMISSIONS_ID'),
                    'webhook.token' => Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_SUBMISSIONS_TOKEN'),
                    'content' => lang_get(
                        'new_submission',
                        array(
                            'role' => $content['role'],
                            'user' => $content['user'],
                            'num_attempts' => $content['num_attempts'],
                            'challenge_title' => $content['challenge_title'],
                            'result' => $content['result'],
                            'flag' => $content['flag']
                        )
                    )
                ));
            }
            if ($type == 'new_registration') {
                $client->webhook->executeWebhook(array(
                    'webhook.id' => Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_REGISTRATION_ID'),
                    'webhook.token' => Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_REGISTRATION_TOKEN'),
                    'content' => lang_get(
                        'new_registration',
                        array(
                            'role' => $content['role'],
                            'user' => $content['user'],
                            'email' => $content['email'],
                            'ip' => $content['ip']
                        )
                    )
                ));
            }
            if ($type == 'activity') {
                $client->webhook->executeWebhook(array(
                    'webhook.id' => Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_ACTIVITY_ID'),
                    'webhook.token' => Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_ACTIVITY_TOKEN'),
                    'content' => lang_get(
                        'activity',
                        array(
                            'role' => $content['role'],
                            'user' => $content['user'],
                            'email' => $content['email'],
                            'full_name' => $content['full_name'],
                            'ip' => $content['ip']
                        )
                    )
                ));
            }

        } catch (Exception $e) {
            log_exception($e);
            //message_error('Caught exception connecting to Discord: ' . $e->getMessage());
        }
    }
    else {
        //message_error('Set Discord parameters in config!');
    }
}