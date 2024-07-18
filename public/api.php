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
  case "resetScore":
    $_SESSION['score']=0;
    $data = "Moves" . $_SESSION['score'];
    break;
  default:
    $data = "Hanoi Game API v1.0";
    break;
}

header("Content-Type: application/json");
echo json_encode($data);
?>