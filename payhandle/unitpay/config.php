<?php

class Config
{
    // Ваш секретный ключ (из настроек проекта в личном кабинете UnitPay )
    const SECRET_KEY = 'ваш_ключ';
    // Стоимость товара в руб.
    const ITEM_PRICE = 0.5;

    // Таблица начисления товара, например `users`
    const TABLE_ACCOUNT = 'accounts';
    // Название поля из таблицы начисления товара по которому производится поиск аккаунта/счета, например `email`
    const TABLE_ACCOUNT_NAME = 'login';
    // Название поля из таблицы начисления товара которое будет увеличено на колличево оплаченого товара, например `sum`, `donate`
    const TABLE_ACCOUNT_DONATE= 'redbucks';

    // Параметры соединения с бд сервера
    // Хост
    const DB_HOST = 'хост';
    // Имя пользователя
    const DB_USER = 'пользователь';
    // Пароль
    const DB_PASS = 'пароль';
    // Назывние базы
    const DB_NAME = 'ragempserver';
    // номер порта(необязательно)
    const DB_PORT = '';

}