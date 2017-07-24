<?php

/**
  * Получение содержимого папки datafiles в соответствие с регулярным выражением
  */
$dir = './datafiles'; 
$files = scandir($dir);
$res = array();
foreach ($files as $file) {
  if (preg_match('/^[A-Za-z0-9]+.txt$/', $file)) {
    $res[] = $file;
  }
}

/**
  * Сортировка по имени
  */
$res = natcasesort($res)

/**
  * Вывод на экран содержимого папки datafiles
  */
foreach ($res as $r) {
  echo $r[0]."\n";
}