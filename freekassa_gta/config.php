<?php

class Config
{
    // Настроек от проекта в личном кабинете free-kassa.ru
    const MERCHANT_ID = '233339';
    const SECRET_KEY_1 = 'eprpbtwt';
    const SECRET_KEY_2 = 'pvsv0osd';
    
    // Стоимость товара в руб.
    const ITEM_PRICE = 1;

    // Таблица начисления товара, например `accounts`
    const TABLE_ACCOUNT = 'accounts';
    // Название поля из таблицы начисления товара по которому производится поиск аккаунта/счета, например `email`
    const TABLE_ACCOUNT_NAME = 'login';
    // Название поля из таблицы начисления товара которое будет увеличено на колличево оплаченого товара, например `sum`, `donate`
    const TABLE_ACCOUNT_DONATE= 'redbucks';

    // Параметры соединения с бд
    // Хост
    const DB_HOST = '31.31.198.55';
    // Имя пользователя
    const DB_USER = 'u1083682_elpato';
    // Пароль
    const DB_PASS = 'rhjyjcnfh1337';
    // Назывние базы
    const DB_NAME = 'u1083682_gta5rp';
}