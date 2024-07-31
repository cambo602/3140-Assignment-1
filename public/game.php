<?php
$servername = "localhost";
$username = "root";
$password = "admin";
$dbname = "csi3140";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Page Title</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="game.css" />
    <style>
      body {
        font-family: Arial, Helvetica, sans-serif;
      }
    </style>
    <script
      src="https://kit.fontawesome.com/f43e4323c8.js"
      crossorigin="anonymous"
    ></script>
    <script src="game.js"></script>
  </head>
  <body>
    <div class="navbar">
      <a href="CameronResume.html">Cameron's Resume</a>
      <a href="CameronProjects.html">Cameron's Projects</a>
      <a href="/public/game.php"> Game</a>
      <a href="ReidProjects.html" class="right"> Reid's Proejcts</a>
      <a href="ReidResume.html" class="right"> Reid's Resume</a>
    </div>
    <!--Towers of hanoi game, 3 pillars sitting next to each other and a reset button -->
    <!--Text to display score -->
    <div class="gameInfo">
      <h1 class="score" id="score">Moves: 0</h1>
      <!-- Reset button on new line centered-->
      <button class="reset" onClick="reset()" title="Reset">
        <i class="fa-sharp fa-solid fa-arrow-rotate-right"
          ><span class="fa-icon-innter-text">&nbsp;Reset</span></i
        >
      </button>
    </div>
    <div class="stage">
      <!-- The pillars stack from the bottom up, so the "stand" is at the top of this list -->
      <button class="clickbox" id="click1" onclick="pillarClick(1)">
        <div class="float" id="float0"></div>
        <div class="pillar" id="pillar1">
          <div class="disc d1" id="disc1"></div>
          <div class="disc d2" id="disc2"></div>
          <div class="disc d3" id="disc3"></div>
          <div class="disc d4" id="disc4"></div>
          <div class="disc d5" id="disc5"></div>
          <div class="stand"></div>
        </div>
      </button>
      <button class="clickbox" id="click2" onclick="pillarClick(2)">
        <div class="float" id="float1"></div>
        <div class="pillar" id="pillar2">
          <div class="stand"></div>
        </div>
      </button>
      <button class="clickbox" id="click3" onclick="pillarClick(3)">
        <div class="float" id="float2"></div>
        <div class="pillar" id="pillar3">
          <div class="stand"></div>
        </div>
      </button>
    </div>
    <div class="leaderBoard">
    <h2>Leader Board</h2>
      <?php
        $sql = "SELECT * FROM scores";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
          // output data of each row
          while($row = mysqli_fetch_assoc($result)) {
            echo "Name: " . $row["username"] . " | Score: " . $row["score"] . "<br>";
          }
        } else {
          echo "0 results";
        }
      ?>
    </div>
  </body>
</html>
