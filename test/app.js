const answerForm = document.querySelector("#answerForm");
const timeLeftBar = document.querySelector("#timeLeftProgressBar");
const secondsLeftContainer = document.querySelector("#secondsLeft");
const pointsGainedContainer = document.querySelector("#pointsGained");
const pointsNeededContainer = document.querySelector("#pointsNeeded");

let timeLeft = 35000;

if (timeLeftBar && secondsLeftContainer) {
    timeLeftBar.max = timeLeft;
    timeLeftBar.value = timeLeft;
    secondsLeftContainer.innerHTML = timeLeft / 1000;
}

function colorPoints() {
    const pointsGained = parseInt(pointsGainedContainer.innerHTML);
    const pointsNeeded = parseInt(pointsNeededContainer.innerHTML);
    if (pointsGained >= pointsNeeded) {
        pointsGainedContainer.style.color = "green";
    } else {
        pointsGainedContainer.style.color = "red";
    }
}

function updateTimer() {
    if (35000 <= performance.now()) {
        answerForm.submit();
    }
    if (timeLeftBar && secondsLeftContainer) {
        timeLeftBar.value = timeLeft;
        secondsLeftContainer.innerHTML = timeLeft / 1000;
    }
    timeLeft -= 1000;
    setTimeout("updateTimer()", 1000);
}

colorPoints();
//updateTimer();