function getRandomAnswer() {
    const aAnswer = document.querySelector("#aAnswer");
    const trueAnswer = document.querySelector("#trueAnswer");

    if (aAnswer) {
        const answers = ["aAnswer", "bAnswer", "cAnswer"];
        const randomAnswer = answers[Math.floor(Math.random() * answers.length)];
        return randomAnswer;
    } else if (trueAnswer) {
        const answers = ["trueAnswer", "falseAnswer"];
        const randomAnswer = answers[Math.floor(Math.random() * answers.length)];
        return randomAnswer;
    }

    return null;
}

function selectAndSubmitAnswer() {
    const randomAnswerId = getRandomAnswer();

    if (randomAnswerId) {
        const answerElement = document.querySelector(`#${randomAnswerId}`);
        answerElement.checked = true;
        document.querySelector("#answerForm").submit();
    }
}

document.addEventListener("DOMContentLoaded", selectAndSubmitAnswer);