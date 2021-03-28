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
    if (Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN') && Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID')) {
        try {
            $client = new DiscordClient(['token' => Config::get('MELLIVORA_CONFIG_DISCORD_BOT_TOKEN')]);

            $result = $client->guild->createGuildChannel(array(
                'guild.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                'name' => $name,
                'type' => 0,
                'parent_id' => $discord_category_id
            ));
            $client->channel->editChannelPermissions(array(
                'channel.id' => $result->id,
                'overwrite.id' => Config::get('MELLIVORA_CONFIG_DISCORD_GUILD_ID'),
                'type' => 'role',
                'deny'=> 1024
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