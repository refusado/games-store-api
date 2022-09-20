<?php
header("Content-Type: Application/json");

define('DB_HOST'        , "localhost");
define('DB_USER'        , "root");
define('DB_PASSWORD'    , "");
define('DB_NAME'        , "game-store");

require_once "Connection.php";

try {

    $connection = Connection::getConnection();
    $query      = $connection->query("SELECT game_name, price, brand FROM games");
    $allGames   = $query->fetchAll(PDO::FETCH_ASSOC);

    die(json_encode($allGames));

 } catch(Exception $e){

    $result['error'] = $e->getMessage();
    die(json_encode($result));

 }