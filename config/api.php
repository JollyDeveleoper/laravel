<?php
return [
    /*
    |--------------------------------------------------------------------------
    | VK API Constant
    |--------------------------------------------------------------------------
    */

    // Ивенты от вк
    'VK_EVENT_CONFIRMATION' => 'confirmation',
    'VK_EVENT_WALL_POST_NEW' => 'wall_post_new',
    'VK_EVENT_MESSAGE_NEW' => 'message_new',

    'VK_API_VERSION' => 5.101, // версия API

    'VK_API_TOKEN' => env('VK_TOKEN'), // токен группы
    'VK_DPK_ID' => env('VK_GROUP'), // группа бота из которой будем тянуть записи

    'VK_CHAT_ID' => 2000000003, // чат, в который нужно репостить записи
];
