<?php

namespace App\Library\VK;

class VK_API
{
    /**
     * Все запросы к API
     *
     * @param $params
     * @param $method
     */
    public static function request($params, $method)
    {
        $params['v'] = config('api.VK_API_VERSION');
        $params['access_token'] = config('api.VK_API_TOKEN');
        $params = http_build_query($params);
        file_get_contents('https://api.vk.com/method/' . $method . '?' . $params);
    }

    /**
     * Репост записи со стены группы в чат
     *
     * @param $post_id
     */
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

    /**
     * Отправление сообщения в чат
     *
     * @param $message
     * @param $chat_id
     * @param string $keyboard
     */
    public static function sendMessage($message, $chat_id, $keyboard = '')
    {
        $method = 'messages.send';
        $request_params = array(
            'random_id' => rand(),
            'peer_id' => $chat_id,
            'message' => $message,
        );
        if (!$keyboard) {
            $request_params['keyboard'] = $keyboard;
        }
        self::request($request_params, $method);
    }
}
