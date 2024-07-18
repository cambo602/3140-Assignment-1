<?php
require_once('_config.php');
session_start();
if(!isset($_SESSION['score'])){
  $_SESSION['score']=0;
}

switch ($_GET["action"] ?? "version") {
  case "increaseScore":
    $_SESSION['score']++;
    $data = "Moves" . $_SESSION['score'];
    break;
}

header("Content-Type: application/json");
echo json_encode($data);