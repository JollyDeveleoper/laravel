# VK Schedule Bot

Данный Laravel проект представляет собой VK Bot'a, который показывает расписание в чате на определенный день недели  
Так же бот может делать репост новой записи из группы в определенный чат

## Функционал
* Получение пар с понедельника по субботу включительно
* Получение следующей пары текущего дня
* Получение пар на сегодня
* Получение пар на завтра
* Просмотр всех пар на сайте (https://yourdomain.ru/schedule), где **yourdomain** - ваш домен  
_P.S. Путь зависит от настроек веб-сервера_
* Редактирование пар
* Удаление пар
* Добавление пар

## Инструкция
Установка проекта не отличается от обычной установки Laravel, кроме нескольких моментов  
* В сконфигурированном **.env** файле необходимо указать ID группы, от которой будет работать бот, и токен группы для запросов к API VK
```no-highlight 
VK_TOKEN = 
VK_GROUP = 
```
* Необходимо создать базу данных **reposter_bot.sqlite** и выполнить:
```no-highlight 
php artisan migrate
```

* Редактирование, удаление и добавление пар работает только через сайт, и только после авторизации, поэтому после установки проекта необходимо выполнить
```
php artisan make:auth
```
### Примечание
Отслеживания изменения пар нет, все редактируется вручную. Данный проект не позиционируется как граббер с какого-либо сайта 
