const answerForm = document.querySelector("#answerForm");
const timeLeftBar = document.querySelector("#timeLeftProgressBar");
const secondsLeftContainer = document.querySelector("#secondsLeft");

let timeLeft = 35000;

timeLeftBar.max = timeLeft;

timeLeftBar.value = timeLeft;
secondsLeftContainer.innerHTML = timeLeft / 1000;

function updateTimer() {
    if (35000 <= performance.now()) {
        answerForm.submit();
    }
    timeLeftBar.value = timeLeft;
    secondsLeftContainer.innerHTML = timeLeft / 1000;
    timeLeft -= 1000;
    setTimeout("updateTimer()", 1000);
}

updateTimer();