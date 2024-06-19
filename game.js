let discInAir = 0;
let moveCount = 0;
let discCount = 5;

function reset() {
  document.getElementById("click1").disabled = false;
  document.getElementById("click2").disabled = false;
  document.getElementById("click3").disabled = false;

  // Reset move count
  moveCount = 0;
  discInAir = 0;
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
    document.querySelector("#score").textContent = "Moves: " + moveCount;

    // check if the game is won
    checkWin();
  }
}

function checkWin() {
  // Check if pillar 2, 3 have all discs
  let pillarInt = 2;
  let pillar = document.querySelector(`#pillar${pillarInt}`);
  if (pillar.children.length == discCount + 1) {
    document.querySelector("#score").textContent =
      "Moves: " + moveCount + " - You Win!";
    // disable all pillars click box
    document.getElementById("click1").disabled = true;
    document.getElementById("click2").disabled = true;
    document.getElementById("click3").disabled = true;
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
