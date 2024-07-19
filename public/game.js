let discCount = 5;

const stand = '<div class="stand"></div>';

function makeDisc(discNumber) {
  return `<div class="disc d${discNumber}" id="disc${discNumber}"></div>`;
}

function display(response) {
  document.querySelector("#score").textContent = "Moves: " + response.score;

  for (let i = 0; i < 3; i++) {
    const pillar = document.getElementById(`pillar${i + 1}`);

    pillar.innerHTML = stand;

    if (!response.diskState[i]) {
      continue;
    }

    response.diskState[i].forEach((disc) => {
      pillar.innerHTML = makeDisc(disc) + pillar.innerHTML;
    });
  }

  if (response.diskInAir[0] != null) {
    const float = document.getElementById(`float${response.diskInAir[0]}`);
    float.innerHTML = makeDisc(response.diskInAir[1]);
  } else {
    const floats = document.getElementsByClassName("float");
    for (let float of floats) {
      float.innerHTML = "";
    }
  }
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
          scoreElement.textContent = i + 1 + ". " + score + " moves";
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

  const resetRequest = new XMLHttpRequest();

  resetRequest.onreadystatechange = function () {
    if (resetRequest.readyState == XMLHttpRequest.DONE) {
      if (resetRequest.status == 200) {
        const response = JSON.parse(resetRequest.responseText);

        display(response);
      }
    }
  };

  resetRequest.open("GET", "api.php?action=reset", true);
  resetRequest.send();
}

function pillarClick(pillarInt) {
  const moveRequest = new XMLHttpRequest();

  moveRequest.onreadystatechange = function () {
    if (moveRequest.readyState == XMLHttpRequest.DONE) {
      if (moveRequest.status == 200) {
        const response = JSON.parse(moveRequest.responseText);

        display(response);
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
