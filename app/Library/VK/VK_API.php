<?php

namespace App\Library\VK;

class VK_API
{

    public static function request($params, $method)
    {
        $params['v'] = config('api.VK_API_VERSION');
        $params['access_token'] = config('api.VK_API_TOKEN');
        $params = http_build_query($params);
        file_get_contents('https://api.vk.com/method/' . $method . '?' . $params);
    }

    public static function repost($post_id)
    {
        $method = 'messages.send';
        $request_params = array(
            'random_id' => rand(),
            'peer_id' => config('api.VK_NEW_CHAT_ID'),
            'attachment' => 'wall' . config('api.VK_DPK_ID') . '_' . $post_id,
        );
        self::request($request_params, $method);
    }

    public static function sendMessage($message, $chat_id)
    {
        $method = 'messages.send';
        $request_params = array(
            'random_id' => rand(),
            'peer_id' => $chat_id,
            'message' => $message,
        );
        self::request($request_params, $method);
    }

    public static function updateKeyboard($chat_id, $message = 'Клавиатура обновлена!')
    {
        $method = 'messages.send';
        $request_params = array(
            'random_id' => rand(),
            'peer_id' => $chat_id,
            'message' => $message,
            'keyboard' => self::getKeyboard()
        );
        self::request($request_params, $method);
    }

    public static function getKeyboard()
    {
        return file_get_contents(app_path('Library/VK/keyboard.json'));
    }
}
