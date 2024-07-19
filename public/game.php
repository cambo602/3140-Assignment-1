<?php
require_once('_config.php');
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
    <script>
      let discCount = 5;

      const stand = '<div class="stand"></div>'

      function makeDisc(discNumber) {
        return `<div class="disc d${discNumber}" id="disc${discNumber}"></div>`;
      }

      // on document load call reset
      document.addEventListener("DOMContentLoaded", function () {
        reset();
        updateLeaderBoard();
      });

      function updateLeaderBoard() {
        const leaderBoardRequest = new XMLHttpRequest();

        leaderBoardRequest.onreadystatechange = function () {
          if (leaderBoardRequest.readyState == XMLHttpRequest.DONE) {
            if (leaderBoardRequest.status == 200) {
              const leaderBoard = JSON.parse(leaderBoardRequest.responseText);
              const leaderBoardElement = document.querySelector("#leaderBoardChild");
              leaderBoardElement.innerHTML = "";
              for (let i = 0; i < leaderBoard.length; i++) {
                const score = leaderBoard[i];
                const scoreElement = document.createElement("div");
                scoreElement.textContent = i+1 + ". " + score  + " moves";
                leaderBoardElement.appendChild(scoreElement);
              }
            }
          }
        };

        leaderBoardRequest.open("GET", "api.php?action=getLeaderBoard", true);
        leaderBoardRequest.send();
      }
      function reset() {
        document.getElementById("click1").disabled = false;
        document.getElementById("click2").disabled = false;
        document.getElementById("click3").disabled = false;

        // Reset move count
        moveCount = 0;
        discInAir = 0;
        
        const scoreRequest = new XMLHttpRequest();

          scoreRequest.onreadystatechange = function () {
            if (scoreRequest.readyState == XMLHttpRequest.DONE) {
              if (scoreRequest.status == 200) {
                document.querySelector("#score").textContent = "Moves: " +
                  scoreRequest.responseText;
              }
            }
          };

          scoreRequest.open("GET", "api.php?action=resetScore", true);
          scoreRequest.send();

        // Remove all discs from pillars
        // let pillarInt = 1;
        // let pillar = document.querySelector(`#pillar${pillarInt}`);
        // while (pillar.children.length > 1) {
        //   pillar.removeChild(pillar.lastChild);
        // }
        // pillarInt = 2;
        // pillar = document.querySelector(`#pillar${pillarInt}`);
        // while (pillar.children.length > 1) {
        //   pillar.removeChild(pillar.lastChild);
        // }
        // pillarInt = 3;
        // pillar = document.querySelector(`#pillar${pillarInt}`);
        // while (pillar.children.length > 1) {
        //   pillar.removeChild(pillar.lastChild);
        // }

        // const floats = document.getElementsByClassName("float");
        // for (let float of floats) {
        //   float.innerHTML = "";
        // }

        // // Add discs to pillar 1
        // pillarInt = 1;
        // const pillar1 = document.querySelector(`#pillar${pillarInt}`);
        // for (let i = 1; i <= discCount; i++) {
        //   const disc = document.createElement("div");
        //   disc.classList.add("disc", `d${i}`);
        //   pillar1.appendChild(disc);
        // }
      }

      function pillarClick(pillarInt) {
        const moveRequest = new XMLHttpRequest();

        moveRequest.onreadystatechange = function () {
          if (moveRequest.readyState == XMLHttpRequest.DONE) {
            if (moveRequest.status == 200) {
              const response = JSON.parse(moveRequest.responseText);
              console.log(pillarInt, response);

              document.querySelector("#score").textContent = "Moves: " +
                response.score;

              for (let i = 0; i < 3; i++) {
                const pillar = document.getElementById(`pillar${i+1}`);

                pillar.innerHTML = stand;

                if (!response.diskState[i]) {
                  continue;
                }

                response.diskState[i].forEach((disc) => {
                  pillar.innerHTML = makeDisc(disc) + pillar.innerHTML;
                });
              }

              if (response.diskInAir[0] != null) {
                console.log(`float${response.diskInAir[0]}`);
                const float = document.getElementById(`float${response.diskInAir[0]}`);
                float.innerHTML = makeDisc(response.diskInAir[1]);
              } else {
                const floats = document.getElementsByClassName("float");
                for (let float of floats) {
                  float.innerHTML = "";
                }
              }
            }
          }
        };

        moveRequest.open(
          "GET",
          "api.php?action=moveDisc&pillar=" + (pillarInt - 1),
          true
        );
        moveRequest.send();

        // check if the game is won
        checkWin();
      }

      function checkWin() {
        // Check if pillar 2, 3 have all discs
        let pillarInt = 2;
        let pillar = document.querySelector(`#pillar${pillarInt}`);
        if (pillar.children.length == discCount + 1) {
            const scoreRequest = new XMLHttpRequest();

          scoreRequest.onreadystatechange = function () {
            if (scoreRequest.readyState == XMLHttpRequest.DONE) {
              if (scoreRequest.status == 200) {
                document.querySelector("#score").textContent =
              "Moves: " + moveCount + " - You Win!";
              updateLeaderBoard();
              }
            }
          };

          scoreRequest.open("GET", "api.php?action=checkLeaderScore", true);
          scoreRequest.send();
          // disable all pillars click box
          document.getElementById("click1").disabled = true;
          document.getElementById("click2").disabled = true;
          document.getElementById("click3").disabled = true;
        }
        pillarInt = 3;
        pillar = document.querySelector(`#pillar${pillarInt}`);
        if (pillar.children.length == discCount + 1) {
          const scoreRequest = new XMLHttpRequest();

          scoreRequest.onreadystatechange = function () {
            if (scoreRequest.readyState == XMLHttpRequest.DONE) {
              if (scoreRequest.status == 200) {
                document.querySelector("#score").textContent =
              "Moves: " + moveCount + " - You Win!";
              updateLeaderBoard();
              }
            }
          };

          scoreRequest.open("GET", "api.php?action=checkLeaderScore", true);
          scoreRequest.send();
          
          // disable all pillars
          document.getElementById("click1").disabled = true;
          document.getElementById("click2").disabled = true;
          document.getElementById("click3").disabled = true;
        }
      }
    </script>
  </head>
  <body>
    <div class="navbar">
      <a href="CameronResume.html">Cameron's Resume</a>
      <a href="CameronProjects.html">Cameron's Projects</a>
      <a href="game.html"> Game</a>
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
      <div id="leaderBoardChild">
      </div>
    </div>
  </body>
</html>
