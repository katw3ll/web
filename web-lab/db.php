<?php

class Db
{
    public static function getConnection()
    {
        $paramsPath = 'config/db_params.php';
        $params = include($paramsPath);

        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $opt = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $db = new PDO($dsn, $params['user'], $params['password'], $opt);
        $db->exec("set names utf8");

        return $db;
    }

}

function show_table($sql){
    $db = Db::getConnection();
    $result = $db->query($sql);

    $data = $result->fetchAll();
    $keys = array_keys($data[0]);
    
    echo '<tr>';
    foreach ($keys as $key) {
        echo "<th>$key</th>";
    }
    echo '</tr>';

    foreach ($data as $row) {
        echo '<tr>';
        foreach ($keys as $key) {
            echo "<td>$row[$key]</td>";
        }
        echo '</tr>';
    } 
}

?>