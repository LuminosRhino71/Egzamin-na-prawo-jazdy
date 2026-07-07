import "./question_timer.mjs";

const questionInfoAndMediaContainer = document.querySelector("#questionInfo-MediaContainer");2
const timeLeftLabel = document.querySelector("#timeLeftLabel");
const timeLeftBar = document.querySelector("#timeLeftProgressBar");
const timeLeftTextContainer = document.querySelector("#timeLeftText");
const secondsLeftContainer = document.querySelector("#secondsLeft");
const pointsGainedContainer = document.querySelector("#pointsGained");
const pointsNeededContainer = document.querySelector("#pointsNeeded");
const submitButton = document.querySelector("#submitAnswerButton");
const showMediaButton = document.querySelector("#showMediaButton");
const answerForm = document.querySelector("#answerForm");

const advancedQuestionTime = 50000;
const basicQuestionTime1 = 20000;
const basicQuestionTime2 = 15000;

const mediaElement = document.querySelector(".questionMedia");

try{
    showMediaButton.classList.add("displayNone");
} catch (error) {}

const timerFlag = timeLeftTextContainer ? true : false;
const timer = timerFlag ? new questionTimer : undefined;

function colorPoints() {
    const pointsGained = parseInt(pointsGainedContainer.innerHTML);
    const pointsNeeded = parseInt(pointsNeededContainer.innerHTML);
    if (pointsGained >= pointsNeeded) {
        pointsGainedContainer.style.color = "green";
    } else {
        pointsGainedContainer.style.color = "red";
    }
}

if (pointsGainedContainer && pointsNeededContainer) colorPoints();
if (timerFlag) setInterval(() => timer.update(), 1000);