<?php

if (!defined('root_define')) die('Access denied'); // Проверяем источник скрипта

class db {
    private $dbHost; // Хост базы данных
    private $dbPort; // Порт базы данных
    private $dbUser; // Имя пользователя базы данных
    private $dbPass; // Пароль пользователя базы данных
    private $dbName; // Имя базы данных
    
    private $conn; // Соединение с базой данных
    
    public function __construct($dbHost, $dbUser, $dbPass, $dbName) {
        $ip = explode(":", $dbHost); // Разбиваем хост на порт и домен
        $this->dbHost = $ip[0]; // Хост базы данных
        if (isset($ip[1])) $this->dbPort = $ip[1]; else $this->dbPort = 3306; // Порт базы данных
        $this->dbUser = $dbUser; // Имя пользователя базы данных
        $this->dbPass = $dbPass; // Пароль пользователя базы данных
        $this->dbName = $dbName; // Имя базы данных
    }
    
    public function connect() {
        $this->conn = new mysqli($this->dbHost, $this->dbUser, $this->dbPass, $this->dbName, $this->dbPort); // Соединяемся с базой данных
        if ($this->conn->connect_error) { // Проверяем на ошибки подключения
            header('HTTP/1.1 500 Internal Server Error'); // Возвращаем ошибку 500
            die("Connection failed: " . $this->conn->connect_error); // Выводим ошибку подключения
        }
    }

    public function query($sql) {
        $result = $this->conn->query($sql); // Выполняем запрос к базе данных
        if (!$result) { // Проверяем на ошибки выполнения запроса
            header('HTTP/1.1 500 Internal Server Error'); // Возвращаем ошибку 500
            die("Error: " . $this->conn->error); // Выводим ошибку выполнения запроса
        }
        return $result; // Возвращаем результат выполнения запроса
    }

    public function close() {
        $this->conn->close(); // Закрываем соединение с базой данных
    }
}
