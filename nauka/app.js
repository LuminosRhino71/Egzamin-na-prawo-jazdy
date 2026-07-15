const answerForm = document.querySelector("#answerForm");
const answerButtons = document.querySelectorAll("#answerForm > input");
const answerButtonsLabels = document.querySelectorAll("#answerForm > label");
const submitButton = document.querySelector("#submitAnswerButton");

function summariseQuestion(userAnswer) {
    const correctAnswer = getCorrectAnswer();
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

function getCorrectAnswer() {
    const xhr = new XMLHttpRequest();
    let correctAnswer;
    xhr.open("GET", "correct_answer.php", false);
    xhr.onload = () => {
        switch (xhr.status) {
            case 200:
                correctAnswer = xhr.responseText;
                break;
            default:
                console.log("Unknown response from correct_answer.php.");
        }
    }
    xhr.send();
    return correctAnswer;
}

answerForm.addEventListener("submit", event => {
    answerButtonsLabels.forEach(buttonLabel => buttonLabel.classList.add("deactivateLabel"));
    event.preventDefault();
    summariseQuestion(event.target.querySelector('input[name="multipleChoiceAnswer"]:checked').value);
});