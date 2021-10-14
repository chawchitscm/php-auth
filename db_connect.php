<?php

function openCon () {
    require_once __DIR__.'./config.php';

    $conf = new Config;
    $connection = mysqli_connect($conf->host, $conf->user, $conf->password, $conf->db);
    return $connection;
}

function closeCon ($connection) {
    $connection->close();
}