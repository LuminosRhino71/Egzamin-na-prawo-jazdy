export class questionTimer {
    timeGot;
    timeLeft;
    isQuestionAdvanced;
    secondTimerCanStart;
    moment;

    end() {
        answerForm.submit();
    }

    showMedia() {
        mediaElement.style.visibility = "visible";
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
        timeLeftLabel.classList.add("displayNone");
        timeLeftBar.classList.add("displayNone");
    }

    secondTimerStartSetup() {
        this.secondTimerCanStart = true;
        this.moment = performance.now();
        timeLeftLabel.classList.remove("displayNone");
        timeLeftBar.classList.remove("displayNone");
        this.timeGot = basicQuestionTime2;
        this.timeLeft = basicQuestionTime2;
        submitButton.classList.remove("displayNone");
        this.panelSetup();
    }

    prepareAndShowMedia() {
        showMediaButton.classList.add("displayNone");
        if ("questionVideo" == mediaElement.id) {
            this.showMedia();
            this.firstTimerEndSetup();
            mediaElement.play();
            mediaElement.addEventListener("ended", () => this.secondTimerStartSetup());
        } else if ("questionImage" == mediaElement.id) {
            this.showMedia();
            this.secondTimerStartSetup();
        } else {
            this.secondTimerStartSetup();
        }
    }

    check() {
        if (true === this.isQuestionAdvanced && this.timeGot <= performance.now() - this.moment) {
            this.end();
        } else if (false === this.isQuestionAdvanced && this.timeGot <= performance.now() - this.moment) {
            if (false === this.secondTimerCanStart) {
                this.prepareAndShowMedia();
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
            try {
                if ("questionVideo" == mediaElement.id) {
                    this.showMedia();
                } else if ("questionImage" == mediaElement.id) {
                    this.showMedia();
                } else {
                    //Tu ma nastąpić pokazanie informacji o braku obrazu lub filmu.
                }
            } catch (error) {}
        } else if (document.querySelector("#trueAnswer")) {
            this.timeGot = basicQuestionTime1;
            this.timeLeft = this.timeGot;
            this.isQuestionAdvanced = false;
            this.secondTimerCanStart = false;
            submitButton.classList.add("displayNone");
            showMediaButton.classList.remove("displayNone");
            showMediaButton.addEventListener("click", () => this.prepareAndShowMedia())
        }
        this.moment = performance.now();
        this.panelSetup();
        this.update();
    }
}