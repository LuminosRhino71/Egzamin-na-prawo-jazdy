<!DOCTYPE html>
<?php
    require_once("../dbconnect.php");
    require_once("test.php");
    session_start();

    if (isset($_SESSION["testObject"])) {
        $test = $_SESSION["testObject"];
    } else {
        $test = new Test("B");
        $test->assignQuestions($connection);
    }

    if (isset($_POST["multipleChoiceAnswer"])) {
        $userAnswer = $_POST["multipleChoiceAnswer"];
        $correctAnswer = $_SESSION["correctAnswer"];

        if ($userAnswer === $correctAnswer) {
            $test->answersCorrectness[] = true;
            $test->pointQuantity += $test->questions[$_SESSION["currentQuestionNumber"]]["Liczba_punktow"];
        } else {
            $test->answersCorrectness[] = false;
        }
        $_SESSION["currentQuestionNumber"] += 1;
    }
?>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Test</title>
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <header>
            <h1>Odpowiadaj na pytania.</h1>
        </header>
        <main>
            <a href="../" class="defaultButton">Porzuć test</a>
            <?php
                if ($test->isAvailable()) {
                    if (isset($_SESSION["currentQuestionNumber"])) {
                        $currentQuestionNumber = $_SESSION["currentQuestionNumber"];
                    } else {
                        $_SESSION["currentQuestionNumber"] = 0;
                        $currentQuestionNumber = 0;
                    }

                    if (isset($_POST["summaryQuestion"])) {
                        $currentQuestionNumber = intval($_POST["summaryQuestion"]) - 1;
                    }

                    if (empty($test->questions[$currentQuestionNumber]) || !empty($_SESSION["testCompleted"])) {
                        if (empty($test->questions[$currentQuestionNumber])) {
                            $currentQuestionNumber = 0;
                        }
                        $summaryMode = true;
                    } else {
                        $summaryMode = false;
                    }

                    echo <<<HTML
                        <div id="infoContainer" class="horizontalContainer">
                            <div class="mediaContainer">
                    HTML;

                    if (str_ends_with($test->questions[$currentQuestionNumber]["Media"], ".mp4")) {
                        echo <<<HTML
                            <video id="questionVideo" autoplay muted>
                                <source src='media/{$test->questions[$currentQuestionNumber]["Media"]}' type='video/mp4'/>
                            </video>
                        HTML;
                    } else if (str_ends_with($test->questions[$currentQuestionNumber]["Media"], ".jpg")) {
                        echo <<<HTML
                            <img id="questionImage" src='media/{$test->questions[$currentQuestionNumber]["Media"]}' alt='Obraz załączony do pytania'/>
                        HTML;
                    }

                    echo <<<HTML
                            </div>
                            <div id="testInfoContainer">
                    HTML;

                    if ($summaryMode) {
                        $_SESSION["testCompleted"] = true;
                        $testPassed = $test->pointQuantity >= $test->pointsNeededToPass;
                        $testPassedUserMessage = $testPassed ? "Zdałeś" : "Nie zdałeś";

                        echo <<<HTML
                            <h2>{$testPassedUserMessage}</h2>
                            <p>Zdobyte punkty: <span id="pointsGained">{$test->pointQuantity}</span></p>
                            <p>Wymagana ilość punktów: <span id="pointsNeeded">{$test->pointsNeededToPass}</span></p>
                            <form id="summaryForm" action="./" method="post">
                        HTML;

                        foreach ($test->answersCorrectness as $index => $correctness) {
                            $questionNumber = $index + 1;
                            $class = $correctness ? "green" : "red";
                            echo <<<HTML
                                <input type="radio" id="summaryQuestion{$questionNumber}" name="summaryQuestion" value="{$questionNumber}" class="invisibleRadio" onchange="this.form.submit()" >
                                <label for="summaryQuestion{$questionNumber}" class="{$class} summaryRadioLabel">{$questionNumber}</label>
                            HTML;
                        }

                        echo /*html*/'</form>';
                    } else {
                        echo <<<HTML
                            <p>Numer pytania: {$test->questions[$currentQuestionNumber]["Numer_pytania"]}</p>
                            <p>Liczba punktów: {$test->questions[$currentQuestionNumber]["Liczba_punktow"]}</p>
                            <p>Zakres struktury: {$test->questions[$currentQuestionNumber]["Zakres_struktury"]}</p>
                            <label for="timeLeftProgressBar">Pozostały czas na odpowiedź: <span id="secondsLeft"></span> s</label>
                            <progress id="timeLeftProgressBar" value="" max=""></progress>
                            <button type="submit" id="submitAnswerButton" class="defaultButton" form="answerForm">Zatwierdź odpowiedź</button>
                        HTML;
                    }

                    echo <<<HTML
                            </div>
                        </div>
                        <p>{$test->questions[$currentQuestionNumber]["Pytanie"]}</p>
                    HTML;

                    $_SESSION["correctAnswer"] = $test->questions[$currentQuestionNumber]["Poprawna_odp"];

                    echo /*html*/'<form id="answerForm" action="./" method="post">';

                    if ($test->questions[$currentQuestionNumber]["Poprawna_odp"] === "A" || $test->questions[$currentQuestionNumber]["Poprawna_odp"] === "B" || $test->questions[$currentQuestionNumber]["Poprawna_odp"] === "C") {
                        echo <<<HTML
                            <input type="radio" id="aAnswer" name="multipleChoiceAnswer" value="A" class="invisibleRadio">
                            <label for="aAnswer" class="answerRadioLabel">A. {$test->questions[$currentQuestionNumber]["Odp_A"]}</label>
                            <input type="radio" id="bAnswer" name="multipleChoiceAnswer" value="B" class="invisibleRadio">
                            <label for="bAnswer" class="answerRadioLabel">B. {$test->questions[$currentQuestionNumber]["Odp_B"]}</label>
                            <input type="radio" id="cAnswer" name="multipleChoiceAnswer" value="C" class="invisibleRadio">
                            <label for="cAnswer" class="answerRadioLabel">C. {$test->questions[$currentQuestionNumber]["Odp_C"]}</label>
                        HTML;
                    } else if ($test->questions[$currentQuestionNumber]["Poprawna_odp"] === "T" || $test->questions[$currentQuestionNumber]["Poprawna_odp"] === "N") {
                        echo <<<HTML
                            <input type="radio" id="trueAnswer" name="multipleChoiceAnswer" value="T" class="invisibleRadio">
                            <label for="trueAnswer" class="answerRadioLabel">Tak</label>
                            <input type="radio" id="falseAnswer" name="multipleChoiceAnswer" value="N" class="invisibleRadio">
                            <label for="falseAnswer" class="answerRadioLabel">Nie</label>
                        HTML;
                    }

                    echo /*html*/'</form>';
                } else if (!empty($getQuestionQuery) && 0 == $getQuestionQuery->rowCount()) {
                    echo /*html*/'<p>Brak pytań.</p>';
                } else {
                    echo /*html*/'<p class="errorMessage">Wystąpił błąd podczas pobierania pytań.</p>';
                }

                $_SESSION["testObject"] = $test;
                $_SESSION["currentQuestionNumber"] = $currentQuestionNumber;
            ?>
        </main>
        <script src="app.js"></script>
    </body>
</html>