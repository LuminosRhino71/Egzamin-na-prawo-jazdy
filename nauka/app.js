const answerForm = document.querySelector("#answerForm");
const answerButtons = document.querySelectorAll("#answerForm > input");
const answerButtonsLabels = document.querySelectorAll("#answerForm > label");
const submitButton = document.querySelector("#submitAnswerButton");

async function summariseQuestion(userAnswer) {
    const correctAnswer = await (await fetch("correct_answer.php")).text();
    answerButtons.forEach(button => {
        button.checked = false;
    });

    submitButton.style.display = "none";
    const nextQuestionButton = document.createElement("button");
    nextQuestionButton.textContent = "Następne pytanie";
    nextQuestionButton.classList.add("defaultButton");
    nextQuestionButton.type = "button";
    document.querySelector("main").append(nextQuestionButton);
    nextQuestionButton.addEventListener("click", () => {
        location.reload();
    });

    function getAnswerButtonIndex(answer) {
        let index;
        switch (answer) {
            case "T":
                index = 0;
                break;
            case "N":
                index = 1;
                break;
            case "A":
                index = 0;
                break;
            case "B":
                index = 1;
                break;
            case "C":
                index = 2;
                break;
            default:
                break;
        }
        return index;
    }

    if (userAnswer != correctAnswer && "NOT ANSWERED" != userAnswer) {
        answerButtonsLabels[getAnswerButtonIndex(userAnswer)].classList.add("incorrectlyChosen");
    }
    answerButtonsLabels[getAnswerButtonIndex(correctAnswer)].classList.add("correct");
}

answerForm.addEventListener("submit", event => {
    answerButtonsLabels.forEach(buttonLabel => buttonLabel.classList.add("deactivateLabel"));
    event.preventDefault();
    summariseQuestion(event.target.querySelector('input[name="multipleChoiceAnswer"]:checked').value);
});