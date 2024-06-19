const pillars = document.querySelectorAll('.pillar');

let pillarContent = [[], [], []];
let pillarContentIndex = [0, 0, 0];

let numDiscs = 5;

const DISC_COLOURS = ['#FFC0CB', '#800080', '#0000FF', '#FFFF00', '#ff0000']; // Pink, Purple, Blue, Yellow, Red

const startingWidth = 90;

const buildPillar = (pillars) => {
    pillars.forEach(pillar => {
        const stem = document.createElement('div');
        stem.className = 'stem';
        const plate = document.createElement('div');
        plate.className = 'plate';
        pillar.innerHTML = '';
        pillar.appendChild(stem);
        pillar.appendChild(plate);
    });
}

function reset(){
    for (let i = 0; i < 3; i++){
        pillarContent[i] = [];
        pillarContentIndex[i] = 0;
    }
  
    buildPillar(pillars);
    // create the discs and add them to the first pillar
    for (let i = 0; i < numDiscs; i++){
        let pillar = document.createElement('div');
        pillar.classList.add('disc');
        pillar.draggable = true;
        pillar.style.backgroundColor = DISC_COLOURS[i];
        pillar.style.width = startingWidth - i * 10 + 'px';
        towerContent[0].push(pillar);
        pillarContent[0].push(i);
    }

    towerContent[0].forEach(d => {
        tower[0].innerHTML = d.outerHTML + tower[0].innerHTML;
    });

}
