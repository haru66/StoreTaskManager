StoreTaskManager
====

StoreTaskManager is very simply task management tool for stores.

***

## Description

This is include these features:<br>
    **Task management* **(Of course!)**<br>
    **Store management*<br>
        - Multiple store management<br>
    **Staff management*<br>
        - Store staff or Manager<br>
        - Department management

## Requirement

PHP 7 later
MySQL or SQlite3

## Install

1. Copy all files to your web root directory.

2. Edit configuration file.

    You need to edit database setting.
    file: config/app.php

    Supported database is MySQL and SQLite.

4. Initialize database

    MySQL : Excute db/mysql_db_init.sql
    SQLite: Copy stm_org.sqlite and rename to stm.sqlite

5. Setting your store.

    Access to root directory.
    Choose '店舗管理' and enter password to login. (Default password: password)

    Change store name, add manager and store.

## Licence

MIT License

## Author

[stack@haru66] (https://github.com/haru66)
[mail] (haru66g@gmail.com)