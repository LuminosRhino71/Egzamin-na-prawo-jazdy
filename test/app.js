const answerForm = document.querySelector("#answerForm");
const timeLeftBar = document.querySelector("#timeLeftProgressBar");
const timeLeftTextContainer = document.querySelector("#timeLeftText");
const secondsLeftContainer = document.querySelector("#secondsLeft");
const pointsGainedContainer = document.querySelector("#pointsGained");
const pointsNeededContainer = document.querySelector("#pointsNeeded");

const advancedQuestionTime = 50000;
const basicQuestionTime1 = 20000;
const basicQuestionTime2 = 15000;

class questionTimer {
    timeGot;
    timeLeft;
    isQuestionAdvanced;
    secondTimerCanStart;
    mediaElement;
    moment;

    end() {
        answerForm.submit();
    }

    showMedia() {
        this.mediaElement.style.visibility = "visible";
    }

    panelSetup() {
        if (timeLeftBar && timeLeftTextContainer && secondsLeftContainer) {
            timeLeftBar.max = this.timeGot;
            timeLeftBar.value = this.timeGot;
            timeLeftTextContainer.innerHTML = false === this.secondTimerCanStart ? "Czas na zapoznanie się z pytaniem" : "Pozostały czas na odpowiedź";
            secondsLeftContainer.innerHTML = this.timeGot / 1000;
        }
    }

    firstTimerEndSetup() {
        this.secondTimerCanStart = true;
        this.moment = performance.now();
        this.timeGot = basicQuestionTime2;
        this.timeLeft = basicQuestionTime2;
        this.panelSetup();
    }

    check() {
        if (true === this.isQuestionAdvanced && this.timeGot <= performance.now() - this.moment) {
            this.end();
        } else if (false === this.isQuestionAdvanced && this.timeGot <= performance.now() - this.moment) {
            if (false === this.secondTimerCanStart) {
                if (this.mediaElement = document.querySelector("#questionVideo")) {
                    this.showMedia();
                    this.mediaElement.play();
                    this.mediaElement.addEventListener("ended", () => this.firstTimerEndSetup());
                } else if (this.mediaElement = document.querySelector("#questionImage")) {
                    this.showMedia();
                    this.firstTimerEndSetup();
                } else {
                    this.firstTimerEndSetup();
                }
            } else if (true === this.secondTimerCanStart) {
                this.end();
            }
        }
    }

    update() {
        if (timeLeftBar && secondsLeftContainer) {
            timeLeftBar.value = this.timeLeft;
            secondsLeftContainer.innerHTML = this.timeLeft / 1000;
        }
        this.timeLeft -= 1000;
        this.check();
    }

    constructor() {
        if (document.querySelector("#aAnswer")) {
            this.timeGot = advancedQuestionTime;
            this.timeLeft = this.timeGot;
            this.isQuestionAdvanced = true;
        } else if (document.querySelector("#trueAnswer")) {
            this.timeGot = basicQuestionTime1;
            this.timeLeft = this.timeGot;
            this.isQuestionAdvanced = false;
            this.secondTimerCanStart = false;
        }
        this.moment = performance.now();
        this.panelSetup();
        this.update();
    }
}
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