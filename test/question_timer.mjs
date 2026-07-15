import * as DOMELements from "./DOMElements.mjs";
import * as questionsTimes from "./questionsTimes.mjs";

export default class questionTimer {
    timeGot;
    timeLeft;
    isQuestionAdvanced;
    secondTimerCanStart;
    moment;

    end() {
        DOMELements.answerForm.submit();
    }

    showMedia() {
        DOMELements.mediaElement.style.visibility = "visible";
    }

    panelSetup() {
        if (DOMELements.timeLeftBar && DOMELements.timeLeftTextContainer && DOMELements.secondsLeftContainer) {
            DOMELements.timeLeftBar.max = this.timeGot;
            DOMELements.timeLeftBar.value = this.timeGot;
            DOMELements.timeLeftTextContainer.innerHTML = false === this.secondTimerCanStart ? "Czas na zapoznanie się z pytaniem" : "Pozostały czas na odpowiedź";
            DOMELements.secondsLeftContainer.innerHTML = this.timeGot / 1000;
        }
    }

    firstTimerEndSetup() {
        DOMELements.timeLeftLabel.classList.add("displayNone");
        DOMELements.timeLeftBar.classList.add("displayNone");
    }

    secondTimerStartSetup() {
        this.secondTimerCanStart = true;
        this.moment = performance.now();
        DOMELements.timeLeftLabel.classList.remove("displayNone");
        DOMELements.timeLeftBar.classList.remove("displayNone");
        this.timeGot = questionsTimes.basic2;
        this.timeLeft = questionsTimes.basic2;
        DOMELements.submitButton.classList.remove("displayNone");
        this.panelSetup();
    }

    prepareAndShowMedia() {
        DOMELements.showMediaButton.classList.add("displayNone");
        if ("questionVideo" == DOMELements.mediaElement.id) {
            this.showMedia();
            this.firstTimerEndSetup();
            DOMELements.mediaElement.play();
            DOMELements.mediaElement.addEventListener("ended", () => this.secondTimerStartSetup());
        } else if ("questionImage" == DOMELements.mediaElement.id) {
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
        if (DOMELements.timeLeftBar && DOMELements.secondsLeftContainer) {
            DOMELements.timeLeftBar.value = this.timeLeft;
            DOMELements.secondsLeftContainer.innerHTML = this.timeLeft / 1000;
        }
        this.timeLeft -= 1000;
        this.check();
    }

    constructor() {
        if (document.querySelector("#aAnswer")) {
            this.timeGot = questionsTimes.advanced;
            this.timeLeft = this.timeGot;
            this.isQuestionAdvanced = true;
            try {
                if ("questionVideo" == DOMELements.mediaElement.id) {
                    this.showMedia();
                } else if ("questionImage" == DOMELements.mediaElement.id) {
                    this.showMedia();
                } else {
                    //Tu ma nastąpić pokazanie informacji o braku obrazu lub filmu.
                }
            } catch (error) {}
        } else if (document.querySelector("#trueAnswer")) {
            this.timeGot = questionsTimes.basic1;
            this.timeLeft = this.timeGot;
            this.isQuestionAdvanced = false;
            this.secondTimerCanStart = false;
            DOMELements.submitButton.classList.add("displayNone");
            DOMELements.showMediaButton.classList.remove("displayNone");
            DOMELements.showMediaButton.addEventListener("click", () => this.prepareAndShowMedia())
        }
        this.moment = performance.now();
        this.panelSetup();
        this.update();
    }
}