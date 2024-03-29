<?php

$_URL[0] = null;
$data   = [];

if (@$_GET['url']) {
    require "src/Request.php";
    $request = new Request;

    $_URL = explode('/', $_GET['url']);
}

if ($_URL[0] === "games") {
    if (!empty($_URL[1])) {
        $request->setId($_URL[1]);
    }

    try {
        $data['games'] = $request->getGames();
        $data['total'] = count($data['games']);
    } catch (Exception $e) {
        $data['message'] = $e->getMessage();
    }

    die(json_encode($data));
}

if ($_URL[0] === "create") {
    if (
        !empty($_URL[1]) &&
        !empty($_URL[2]) &&
        !empty($_URL[3]) &&
        !empty($_URL[4])
    ) {
        $request->setName($_URL[1]);
        $request->setPrice($_URL[2]);
        $request->setCategory($_URL[3]);
        $request->setCompany($_URL[4]);

        
        $att = 0;
        do {
            try {
                $data['games'] = $request->insertGame();
            } catch (Exception $e) {
                $att++;
                continue;
            }
            break;
        } while($att < 30);

        if ($att >= 30) {
            $data['message'] = "There was an error when registering the game, try again.";
        }
    } else {
        header("HTTP/1.1 400 Bad Request: missing data");
        $data['message'] = "Please set all data";
    }

    die(json_encode($data));
}

if ($_URL[0] === "delete") {
    if (!empty($_URL[1])) {
        $request->setId($_URL[1]);

        try {
            $data['deleted'] = $request->deleteGame();
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }
    } else {
        header("HTTP/1.1 400 Bad Request: missing data");
        $data['message'] = "Please set all data";
    }

    die(json_encode($data));
}

if ($_URL[0] === "update") {
    if (
        !empty($_URL[1]) &&
        !empty($_URL[2]) &&
        !empty($_URL[3]) &&
        !empty($_URL[4]) &&
        !empty($_URL[5])
    ) {
        $request->setId($_URL[1]);
        $request->setName($_URL[2]);
        $request->setPrice($_URL[3]);
        $request->setCategory($_URL[4]);
        $request->setCompany($_URL[5]);

        try {
            $data['games'] = $request->updateGame();
        } catch (Exception $e) {
            $data['message'] = $e->getMessage();
        }
    } else {
        $data['message'] = "Please set all data";
    }

    die(json_encode($data));
}

if ($_SERVER['REQUEST_URI'] != "/") {
    $rootPath = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    // header("location: $rootPath/"); // -> PARA DEPLOY EM PRODUÇÃO
}

require "./home/index.php";