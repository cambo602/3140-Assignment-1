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

if(!isset($_SESSION['discInAir'])){
  // the tower and disc number
  $_SESSION['discInAir'] = [null, null];
}

switch ($_GET["action"] ?? "version") {
  case "moveDisc":
    $data = new stdClass;
    $pillar = $_GET["pillar"] ?? 0;

    if ($_SESSION['discInAir'][0] == null) {
      $_SESSION['discInAir'][0] = $pillar;
      $_SESSION['discInAir'][1] = array_pop($_SESSION['discs'][$pillar]);
    }
    else{
      if ( !empty($_SESSION['discs'][$pillar]) &&
        $_SESSION['discInAir'][1] 
        > 
        $_SESSION['discs'][$pillar][
          count($_SESSION['discs'][$pillar])-1
        ]
      ){
        $data -> diskInAir = $_SESSION['discInAir'];
        $data -> diskState = $_SESSION['discs'];
        $data -> score = $_SESSION['score'];
        break;
      }

      array_push($_SESSION['discs'][$pillar], $_SESSION['discInAir'][1]);
      $_SESSION['discInAir'] = [null, null];
      $_SESSION['score']++;
    }

    if (count($_SESSION['discs'][2]) == 5 or count($_SESSION['discs'][1]) == 5){
      $data -> win = true;
    }
    else{
      $data -> win = false;
    }

    $data -> diskInAir = $_SESSION['discInAir'];
    $data -> diskState = $_SESSION['discs'];
    $data -> score = $_SESSION['score'];
    break;
  case 'reset':
    $data = new stdClass;
    $_SESSION['score'] = 0;
    $_SESSION['discs'] = [
      [5, 4, 3, 2, 1],
      [],
      []
    ];
    $_SESSION['discInAir'] = [null, null];
    $data -> diskInAir = $_SESSION['discInAir'];
    $data -> diskState = $_SESSION['discs'];
    $data -> score = $_SESSION['score'];
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
  case "getLeaderBoard":
    $leaderBoard = json_decode(file_get_contents('leaderBoardDB.json'), true);
    $data = $leaderBoard['scores'];
    break;
  default:
    $data = "Hanoi Game API v1.0";
    break;
}

header("Content-Type: application/json");
echo json_encode($data);
?>