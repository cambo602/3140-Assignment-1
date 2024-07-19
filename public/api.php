<?php
require_once('_config.php');
session_start();
if(!isset($_SESSION['score'])){
  $_SESSION['score']=0;
}
if(!isset($_SESSION['discs'])){
  // in reverse order for push and pop
  $_SESSION['discs'] = [
    [5, 4, 3, 2, 1],
    [],
    []
  ];
}

switch ($_GET["action"] ?? "version") {
  case "increaseScore":
    $_SESSION['score']++;
    $data = "Moves " . $_SESSION['score'];
    break;
  case "moveTopDisc":
    $data = new stdClass;

    $from = $_GET["from"] ?? 0;
    $to = $_GET["to"] ?? 0;

    if (empty($_SESSION['discs'][$from])) {
      $data -> valid=false;
      $data -> diskState=$_SESSION['discs'];
      break;
    }

    if (!empty($_SESSION['discs'][$to]) && 
        $_SESSION['discs'][$from][
          count($_SESSION['discs'][$from])-1
        ] 
        > 
        $_SESSION['discs'][$to][
          count($_SESSION['discs'][$to])-1
        ]
      ){
      $data -> valid="Invalid move";
      $data -> diskState=$_SESSION['discs'];
      break;
    }

    $disc = array_pop($_SESSION['discs'][$from]);
    array_push($_SESSION['discs'][$to], $disc);

    $data -> valid=true;
    $data -> diskState=$_SESSION['discs'];
    break;
  case "resetScore":
    $_SESSION['score']=0;
    $data = "Moves" . $_SESSION['score'];
    break;
  case "checkLeaderScore":
      // get the 10  scores from the "database" leaderBoardDB.json
      $leaderBoard = json_decode(file_get_contents('leaderBoardDB.json'), true);
      // if the score is less than the first score in the leaderBoard than replace it, if not check the next one etc
      $score = $leaderBoard['scores'];
      foreach ($score as $score) {
        echo $score['score'];
        if ($_SESSION['score'] < $score) {
          $score = $_SESSION['score'];
          break;
        }
      }
      // save the leaderBoard back to the "database"
      file_put_contents('leaderBoardDB.json', json_encode($leaderBoard));
      break;
  default:
    $data = "Hanoi Game API v1.0";
    break;
}

header("Content-Type: application/json");
echo json_encode($data);
?>