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

function unlock_discord_channel($discord_channel_id, $discord_user_id) {
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') && Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID')) {
        try {
            $client = new DiscordClient(['token' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN')]);

            $client->channel->editChannelPermissions(array(
                'channel.id' => $discord_channel_id,
                'overwrite.id' => $discord_user_id,
                'type' => 1, //user
                'allow'=> 1024 //VIEW_CHANNEL
            ));

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

function link_discord_account($discord_user_id, $nick, $competing) {
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') && Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID') && Config::get('MELLIVORA_CONFIG_DISCORD_NON_COMPETITOR_ROLE_ID') && Config::get('MELLIVORA_CONFIG_DISCORD_COMPETITOR_ROLE_ID')) {
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
                    //check competitor role
                    $competitor = array(
                        'guild.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                        'user.id' => intval($discord_user_id),
                        'role.id' => Config::get('MELLIVORA_CONFIG_DISCORD_COMPETITOR_ROLE_ID')
                    );
                    $non_competitor = array(
                        'guild.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                        'user.id' => intval($discord_user_id),
                        'role.id' => Config::get('MELLIVORA_CONFIG_DISCORD_NON_COMPETITOR_ROLE_ID')
                    );
                    if ($competing == 1) {
                        if (!in_array(Config::get('MELLIVORA_CONFIG_DISCORD_COMPETITOR_ROLE_ID'), $member->roles)) {
                            $client->guild->addGuildMemberRole($competitor);
                        }
                        if (in_array(Config::get('MELLIVORA_CONFIG_DISCORD_NON_COMPETITOR_ROLE_ID'), $member->roles)) {
                            $client->guild->removeGuildMemberRole($non_competitor);
                        }
                    }
                    if ($competing == 0) {
                        if (!in_array(Config::get('MELLIVORA_CONFIG_DISCORD_NON_COMPETITOR_ROLE_ID'), $member->roles)) {
                            $client->guild->addGuildMemberRole($non_competitor);
                        }
                        if (in_array(Config::get('MELLIVORA_CONFIG_DISCORD_COMPETITOR_ROLE_ID'), $member->roles)) {
                            $client->guild->removeGuildMemberRole($competitor);
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
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') && Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_ID') && Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_TOKEN')) {
        try {
            $client = new DiscordClient(['token' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN')]);

            if ($type == 'new_solver') {
                $client->webhook->executeWebhook(array(
                    'webhook.id' => Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_ID'),
                    'webhook.token' => Config::get('MELLIVORA_CONFIG_DISCORD_WEBHOOK_TOKEN'),
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

        } catch (Exception $e) {
            message_error('Caught exception connecting to Discord: ' . $e->getMessage());
        }
    }
    else {
        message_error('Set Discord parameters in config!');
    }
}