<?php
if ($_GET) {
    $dbHostname = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "change_url";
    $dbTableName = "urls";
    $dbLongUrl = "LongUrl";
    $dbShortUrl = "ShortUrl";
    $longUserLink = trim($_REQUEST['link'], '/');

    if (stripos($longUserLink, '://') !== false) {
        $userLink = explode('://', $longUserLink);
        $longUserLink = $userLink[1];
    }
    if (stripos($longUserLink, 'www.') !== false) {
        $userLink = explode('www.', $longUserLink);
        $longUserLink = $userLink[1];
    }

    /* generate shor url */
    function generate_string($input = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', $strength = 6) {
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return 'http://'.$random_string;
    }
    /* generate short url */
    
    function createDb($dbHostname, $dbUsername, $dbPassword, $dbName) {
        $mysqlConnect = mysqli_connect($dbHostname, $dbUsername, $dbPassword);
        $queryCreateDb = "CREATE DATABASE ".$dbName."";
        $queryCreateDbRes = mysqli_query($mysqlConnect, $queryCreateDb);
        if ($queryCreateDbRes) {
            return true;
        } else {
            throw new Exception('База не создалась или была создана ранее');
        }
    }

    function createTable($dbHostname, $dbUsername, $dbPassword, $dbName, $dbTableName, $dbLongUrl, $dbShortUrl) {
        $mysqlConnect = mysqli_connect($dbHostname, $dbUsername, $dbPassword, $dbName);
        $queryCreateTable = "CREATE TABLE IF NOT EXISTS ".$dbTableName." (
            id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
            ".$dbLongUrl." VARCHAR(230) NOT NULL,
            ".$dbShortUrl." VARCHAR(30) NOT NULL
        );";
        $queryCreateTableRes = mysqli_query($mysqlConnect, $queryCreateTable);
        if ($queryCreateTableRes) {
            return true;
        } else {
            throw new Exception('Таблица не создалась или была создана ранее');
        }
    }

    function addNewUrl($mysqlConnect, $dbTableName, $dbLongUrl, $dbShortUrl, $longUserLink) {
        $shortLink = generate_string();
        $queryAddNewUrl = "INSERT INTO ".$dbTableName." (".$dbLongUrl.", ".$dbShortUrl.") VALUES ('".$longUserLink."', '".$shortLink."');";
        $insertNewUrl = mysqli_query($mysqlConnect, $queryAddNewUrl);
        if ($insertNewUrl) {
            $result = ['data' => 'success', 'result' => $shortLink];
            return $result;
        }
    }

    function createDbRow($dbHostname, $dbUsername, $dbPassword, $dbName, $dbTableName, $dbLongUrl, $dbShortUrl, $longUserLink) {
        $mysqlConnect = mysqli_connect($dbHostname, $dbUsername, $dbPassword, $dbName);
        $queryCheckEntry = "SELECT ".$dbLongUrl.", ".$dbShortUrl." FROM ".$dbTableName.";";
        $queryCheckEntryRes = mysqli_query($mysqlConnect, $queryCheckEntry);
        while ($checkEntryRes = mysqli_fetch_assoc($queryCheckEntryRes)) {
            $checkEntry[$checkEntryRes[$dbLongUrl]] = $checkEntryRes[$dbShortUrl];
        }
        if ($checkEntry) {
            if (array_key_exists($longUserLink, $checkEntry)) {
                $result = ['data' => 'success', 'result' => $checkEntry[$longUserLink]];
            } else {
                $result = addNewUrl($mysqlConnect, $dbTableName, $dbLongUrl, $dbShortUrl, $longUserLink);
            }
        } else {
            $result = addNewUrl($mysqlConnect, $dbTableName, $dbLongUrl, $dbShortUrl, $longUserLink);
        }
        echo json_encode($result);
    }

    try {
        createDb($dbHostname, $dbUsername, $dbPassword, $dbName);
    } catch (Exception $e) {
        $e->getMessage();
    }
    
    try {
        createTable($dbHostname, $dbUsername, $dbPassword, $dbName, $dbTableName, $dbLongUrl, $dbShortUrl);
    } catch (Exception $e) {
        $e->getMessage();
    }

    try {
        createDbRow($dbHostname, $dbUsername, $dbPassword, $dbName, $dbTableName, $dbLongUrl, $dbShortUrl, $longUserLink);
    } finally {
        mysqli_close(mysqli_connect($dbHostname, $dbUsername, $dbPassword, $dbName));
    }
} else {
    $result = ['data' => 'error'];
    echo json_encode($result);
}