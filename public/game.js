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
});

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
        checkWin(response.win);
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
}

function checkWin(win) {
  if (win) {
    const scoreRequest = new XMLHttpRequest();

    scoreRequest.onreadystatechange = function () {
      if (scoreRequest.readyState == XMLHttpRequest.DONE) {
        if (scoreRequest.status == 200) {
          location.reload();
        }
      }
    };

    let name = prompt("Enter username");

    scoreRequest.open("GET", `api.php?action=addScore&username=${name}`, true);
    scoreRequest.send();
    // disable all pillars click box

    document.querySelector("#score").textContent += " - You Win!";
    document.getElementById("click1").disabled = true;
    document.getElementById("click2").disabled = true;
    document.getElementById("click3").disabled = true;
  }
}
