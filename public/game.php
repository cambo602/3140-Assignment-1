<?php
require_once('_config.php');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <title>Page Title</title>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../game.css" />
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
      let discInAir = 0;
      let moveCount = 0;
      let discCount = 5;

      // on document load call reset
      document.addEventListener("DOMContentLoaded", function () {
        reset();
      });

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
                document.querySelector("#score").textContent =
                  scoreRequest.responseText;
              }
            }
          };

          scoreRequest.open("GET", "api.php?action=resetScore", true);
          scoreRequest.send();

          document.querySelector("#score").textContent = "Moves: " + moveCount;

        // Remove all discs from pillars
        let pillarInt = 1;
        let pillar = document.querySelector(`#pillar${pillarInt}`);
        while (pillar.children.length > 1) {
          pillar.removeChild(pillar.lastChild);
        }
        pillarInt = 2;
        pillar = document.querySelector(`#pillar${pillarInt}`);
        while (pillar.children.length > 1) {
          pillar.removeChild(pillar.lastChild);
        }
        pillarInt = 3;
        pillar = document.querySelector(`#pillar${pillarInt}`);
        while (pillar.children.length > 1) {
          pillar.removeChild(pillar.lastChild);
        }

        const floats = document.getElementsByClassName("float");
        for (let float of floats) {
          float.innerHTML = "";
        }

        // Add discs to pillar 1
        pillarInt = 1;
        const pillar1 = document.querySelector(`#pillar${pillarInt}`);
        for (let i = 1; i <= discCount; i++) {
          const disc = document.createElement("div");
          disc.classList.add("disc", `d${i}`);
          pillar1.appendChild(disc);
        }
      }

      // this function does all the game logic, if there is no disc in the air
      // it moves the top most disc from the clicked pillar to the floating area.
      // if there is a disc in the air it moves it to the clicked pillar if it is a valid move.
      function pillarClick(pillarInt) {
        // if a disc is not in the air move it there
        if (discInAir == 0) {
          // get the top most disc from pillar
          const disc = document.querySelector(
            `#pillar${pillarInt} > div:last-of-type`
          );
          // the !disc.classList.contains("disc") is here to make sure we don't select the stand
          if (!disc || !disc.classList.contains("disc")) {
            return;
          }
          // remove the disc from the pillar and add it to the floating area
          const discHTML = disc.outerHTML;
          disc.remove();
          document.querySelector(`#float${pillarInt}`).innerHTML += discHTML;

          // now a disc is in the air above this pillar
          discInAir = pillarInt;
        } else {
          // get the floating disc
          const disc = document.querySelector(`#float${discInAir} > div`);
          if (!disc) {
            return;
          }

          // get the size of the disc and the pillar element we want to move it to
          const discSize = parseInt(disc.classList.item(1).substring(1));
          const pillar = document.querySelector(`#pillar${pillarInt}`);

          // if the pillar has discs
          if (pillar.children.length > 1) {
            const topDisc = pillar.querySelector("div:last-of-type");

            // if the disc we want to move is bigger than the top disc of the pillar
            if (parseInt(topDisc.classList.item(1).substring(1)) > discSize) {
              return;
            }
          }

          // remove the disc from the floating area and add it to the pillar
          const discHTML = disc.outerHTML;
          disc.remove();
          pillar.innerHTML += discHTML;

          // now no disc is in the air
          discInAir = 0;
          moveCount++;

          const scoreRequest = new XMLHttpRequest();

          scoreRequest.onreadystatechange = function () {
            if (scoreRequest.readyState == XMLHttpRequest.DONE) {
              if (scoreRequest.status == 200) {
                document.querySelector("#score").textContent =
                  scoreRequest.responseText;
              }
            }
          };

          scoreRequest.open("GET", "api.php?action=increaseScore", true);
          scoreRequest.send();

          // check if the game is won
          checkWin();
        }
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
              }
            }
          };

          scoreRequest.open("GET", "api.php?action=checkLeaderScore", true);
          scoreRequest.send();
          // disable all pillars click box
          document.getElementById("click1").disabled = true;
          document.getElementById("click2").disabled = true;
          document.getElementById("click3").disabled = true;
          document.querySelector("#score").textContent =
            "Moves: " + moveCount + " - You Win!";
        }
        pillarInt = 3;
        pillar = document.querySelector(`#pillar${pillarInt}`);
        if (pillar.children.length == discCount + 1) {
          document.querySelector("#score").textContent =
            "Moves: " + moveCount + " - You Win!";
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
        <div class="float" id="float1"></div>
        <div class="pillar" id="pillar1">
          <div class="stand"></div>
          <div class="disc d1" id="disc1"></div>
          <div class="disc d2" id="disc2"></div>
          <div class="disc d3" id="disc3"></div>
          <div class="disc d4" id="disc4"></div>
          <div class="disc d5" id="disc5"></div>
        </div>
      </button>
      <button class="clickbox" id="click2" onclick="pillarClick(2)">
        <div class="float" id="float2"></div>
        <div class="pillar" id="pillar2">
          <div class="stand"></div>
        </div>
      </button>
      <button class="clickbox" id="click3" onclick="pillarClick(3)">
        <div class="float" id="float3"></div>
        <div class="pillar" id="pillar3">
          <div class="stand"></div>
        </div>
      </button>
    </div>
  </body>
</html>
