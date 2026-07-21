import * as DOMELements from "./DOMElements.mjs";
import * as questionsTimes from "./questionsTimes.mjs";
import questionTimer from "./question_timer.mjs";

try{
    DOMELements.showMediaButton.classList.add("displayNone");
} catch (error) {}

const timerFlag = DOMELements.timeLeftTextContainer ? true : false;
const timer = timerFlag ? new questionTimer : undefined;

function colorPoints() {
    const pointsGained = parseInt(DOMELements.pointsGainedContainer.innerHTML);
    const pointsNeeded = parseInt(DOMELements.pointsNeededContainer.innerHTML);
    if (pointsGained >= pointsNeeded) {
        DOMELements.pointsGainedContainer.style.color = "green";
    } else {
        DOMELements.pointsGainedContainer.style.color = "red";
    }
}

if (DOMELements.pointsGainedContainer && DOMELements.pointsNeededContainer) colorPoints();
if (timerFlag) setInterval(() => timer.update(), 1000);

if (null === DOMELements.mediaElement) {
    DOMELements.questionInfoAndMediaContainer.style.display = "flex";
}