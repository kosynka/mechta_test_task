<?php

namespace VBulletin\Database;

use PDO;
use PDOException;

/**
 * Синглтон для работы с базой данных, чтобы не создавать новое соединение каждый раз
 * чтобы избежать ошибок при неправильной настройке уровня транзакций
 */
final class Database
{
    protected static ?PDO $instance;

    protected function __construct()
    {
    }

    public static function getInstance(): PDO
    {
        if (empty(self::$instance)) {
            $config = array(
                "dbhost" => getenv('DB_HOST') === false ? "127.0.0.1" : getenv('DB_HOST'),
                "dbport" => getenv('DB_PORT') === false ? "3306" : getenv('DB_PORT'),
                "dbuser" => getenv('DB_USER') === false ? "forum" : getenv('DB_USER'),
                "dbpass" => getenv('DB_PASS') === false ? "123456" : getenv('DB_PASS'),
                "dbname" => getenv('DB_NAME') === false ? "vbforum" : getenv('DB_NAME'),
            );

            try {
                self::$instance = new PDO(
                    "mysql:host=" . $config['dbhost'] . ';dbname=' . $config['dbname'],
                    $config['dbuser'],
                    $config['dbpass'],
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                self::$instance->query('SET NAMES utf8');
                self::$instance->query('SET CHARACTER SET utf8');
            } catch (PDOException $error) {
                exit('Connection failed: ' . $error->getMessage());
            }
        }

        return self::$instance;
    }
}
