<?php

namespace App\Library\VK;

use GuzzleHttp\Client;

class VK_API
{
    static $client = null;

    private static function getGuzzleClient()
    {
        if (self::$client === null) {
            self::$client = new Client();
        }
        return self::$client;
    }

    public static function request($params, $method)
    {
        $params['v'] = env('VK_API_VERSION');
        $params['access_token'] = env('VK_API_TOKEN');
        $promise = self::getGuzzleClient()->requestAsync(
            'POST',
            'https://api.vk.com/method/' . $method,
            ["form_params" => $params]
        );
        return $promise->wait();
    }

    public static function repost($post_id)
    {
        $method = 'messages.send';
        $request_params = array(
            'random_id' => rand(),
            'peer_id' => env('VK_CHAT_ID'),
            'attachment' => 'wall' . env('VK_DPK_ID') . '_' . $post_id,
        );
        self::request($request_params, $method);
    }

    public static function sendMessage($message)
    {
        $method = 'messages.send';
        $request_params = array(
            'random_id' => rand(),
            'peer_id' => env('VK_TEST_CHAT_ID'),
            'message' => $message,
        );
        self::request($request_params, $method);
    }

    public static function sendMessageWithKeyboard($message)
    {
        $method = 'messages.send';
        $request_params = array(
            'random_id' => rand(),
            'peer_id' => env('VK_TEST_CHAT_ID'),
            'message' => $message,
            'keyboard' => self::getKeyboard()
        );
        self::request($request_params, $method);
    }

    public static function getKeyboard()
    {
        return '{
  "one_time": false,
  "buttons": [
    [
      {
        "action": {
          "type": "text",
          "payload": "1",
          "label": "Вчера"
        },
        "color": "secondary"
      },
      {
        "action": {
          "type": "text",
          "payload": "2",
          "label": "Завтра"
        },
        "color": "secondary"
      },
      {
        "action": {
          "type": "text",
          "payload": "3",
          "label": "Сегодня"
        },
        "color": "secondary"
      }
    ],
    [
      {
        "action": {
          "type": "text",
          "payload": "4",
          "label": "Понедельник"
        },
        "color": "secondary"
      },
      {
        "action": {
          "type": "text",
          "payload": "5",
          "label": "Вторник"
        },
        "color": "secondary"
      }
    ],
    [
      {
        "action": {
          "type": "text",
          "payload": "6",
          "label": "Среда"
        },
        "color": "secondary"
      },
      {
        "action": {
          "type": "text",
          "payload": "7",
          "label": "Четверг"
        },
        "color": "secondary"
      }
    ],
    [
      {
        "action": {
          "type": "text",
          "payload": "8",
          "label": "Пятница"
        },
        "color": "secondary"
      },
      {
        "action": {
          "type": "text",
          "payload": "9",
          "label": "Суббота"
        },
        "color": "secondary"
      }
    ],
    [
      {
        "action": {
          "type": "text",
          "payload": "10",
          "label": "Следующая пара"
        },
        "color": "secondary"
      }
    ]
  ]
}';
    }
}
