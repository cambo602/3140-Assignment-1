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
      // get the 10 scores from the "database" leaderBoardDB.json
      $leaderBoard = json_decode(file_get_contents('leaderBoardDB.json'), true);
  
      // Add the new score to the leaderboard
      $leaderBoard['scores'][] = $_SESSION['score'];
  
      // Sort the leaderboard in descending order
      sort($leaderBoard['scores']);
  
      // Keep only the top 10 scores
      $leaderBoard['scores'] = array_slice($leaderBoard['scores'], 0, 10);
  
      // Save the updated leaderboard back to the "database"
      file_put_contents('leaderBoardDB.json', json_encode($leaderBoard));
      break;
  default:
    $data = "Hanoi Game API v1.0";
    break;
}

header("Content-Type: application/json");
echo json_encode($data);
?>