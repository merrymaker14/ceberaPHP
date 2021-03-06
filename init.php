﻿<?php

final class Init
{
    protected $db;
    private $name = 'database';
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    
    public function __construct()
    {
        $this->db = $this->connect();
        $this->create();
        for ($i=0; $i < 5; $i++){
            $this->fill();
        }
    }

    /**
     * Соединение с базой данных, используя PDO
     */

    private function connect()
    {
        try {
            $dsn = 'mysql:dbname='.$this->name.';host='.$this->host;
            $db = new PDO($dsn, $this->user, $this->pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e){
            echo $e->getMessage();
            return false;
        }
        return $db;
    }

    /**
     * Создание таблицы
     */
    private function create()
    {
        $table = "CREATE TABLE IF NOT EXISTS `test` ( 
                `id` INT NOT NULL AUTO_INCREMENT, 
                `script_name` VARCHAR(25) NOT NULL , 
                `start_time` INT NOT NULL , 
                `end_time` INT NOT NULL , 
                `result` ENUM('normal','illegal','failed','success') NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci;";
        $tb = $this->db->exec($table);
        if(!$tb) throw new Exception("The table has not been created");
        return true;
    }

    /**
     * Заполнение случайными данными
     */
    private function fill()
    {
        $sql = "INSERT INTO `test` (script_name, start_time, end_time, result) 
                VALUES (:script_name, :start_time, :end_time, :result)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':script_name', $this->randomString(), PDO::PARAM_STR);
        $stmt->bindParam(':start_time', time(), PDO::PARAM_INT);
        $stmt->bindParam(':end_time', time(), PDO::PARAM_INT);
        $stmt->bindParam(':result', $this->randEnum(['normal', 'illegal', 'failed', 'success']), PDO::PARAM_STR);
        $execute = $stmt->execute();
        if(!$execute) throw new Exception("SQL query in not execute!");
    	return true;
    }

    /**
     * Получение данных из таблицы
     */

    public function get()
    {
            $stmt = $this->db->prepare("SELECT id, script_name, start_time, end_time, result FROM `test` WHERE result IN (?, ?)");
            $stmt->execute(['normal', 'success']);
            while ($row = $stmt->fetch(PDO::FETCH_LAZY))
            {
                echo $row['id'] . " | " .$row['script_name'] ." | ".$row['start_time'] ." | " .$row['end_time'] . " | " .$row['result'] ."<br />";
            }
    }
}

$cl = new Init();
$cl->get();