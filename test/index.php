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

        $test->answersChosen[] = $userAnswer;

        if ($userAnswer === $correctAnswer) {
            $test->answersCorrectness[] = true;
            $test->pointQuantity += $test->questions[$_SESSION["currentQuestionNumber"]]["Liczba_punktow"];
        } else {
            $test->answersCorrectness[] = false;
        }
        $_SESSION["currentQuestionNumber"] += 1;
    }

    if (isset($_GET["automatic"])) {
        $test->automaticMode = true;
    }
?>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Test</title>
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="../variable_styles.css">
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

                    $mediaVisibilityCSSProperty = $summaryMode ? "visible" : "hidden";

                    if (str_ends_with($test->questions[$currentQuestionNumber]["Media"], ".mp4")) {
                        echo <<<HTML
                            <video id="questionVideo" class="questionMedia" autoplay muted style="visibility: {$mediaVisibilityCSSProperty}">
                                <source src='media/{$test->questions[$currentQuestionNumber]["Media"]}' type='video/mp4'/>
                            </video>
                        HTML;
                    } else if (str_ends_with($test->questions[$currentQuestionNumber]["Media"], ".jpg")) {
                        echo <<<HTML
                            <img id="questionImage" src='media/{$test->questions[$currentQuestionNumber]["Media"]}' class="questionMedia" alt='Obraz załączony do pytania' style="visibility: {$mediaVisibilityCSSProperty}"/>
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
                            <label for="timeLeftProgressBar" id="timeLeftLabel"><span id="timeLeftText"></span>: <span id="secondsLeft"></span> s</label>
                            <progress id="timeLeftProgressBar" value="" max=""></progress>
                            <button type="submit" id="submitAnswerButton" class="defaultButton" form="answerForm">Zatwierdź odpowiedź</button>
                            <button type="button" id="showMediaButton" class="defaultButton">Obejrzyj załącznik</button>
                        HTML;
                    }

                    echo <<<HTML
                            </div>
                        </div>
                        <p>{$test->questions[$currentQuestionNumber]["Pytanie"]}</p>
                    HTML;

                    $_SESSION["correctAnswer"] = $test->questions[$currentQuestionNumber]["Poprawna_odp"];

                    echo /*html*/'<form id="answerForm" action="./" method="post">';

                    $radioInputState = "";
                    $labelSummaryModeClass = "";
                    $labelAnswerIndicatorsClasses = [
                        "A" => "",
                        "B" => "",
                        "C" => "",
                        "T" => "",
                        "N" => ""
                    ];

                    if ($summaryMode) {
                        $radioInputState = " disabled";
                        $labelSummaryModeClass = " deactivateLabel";
                        $labelAnswerIndicatorsClasses[$test->questions[$currentQuestionNumber]["Poprawna_odp"]] = " correct";
                        if (!$test->answersCorrectness[$currentQuestionNumber]) {
                            $labelAnswerIndicatorsClasses[$test->answersChosen[$currentQuestionNumber]] = " incorrectlyChosen";
                        }
                    }

                    if ($test->questions[$currentQuestionNumber]["Poprawna_odp"] === "A" || $test->questions[$currentQuestionNumber]["Poprawna_odp"] === "B" || $test->questions[$currentQuestionNumber]["Poprawna_odp"] === "C") {
                        echo <<<HTML
                            <input type="radio" id="aAnswer" name="multipleChoiceAnswer" value="A" class="invisibleRadio"{$radioInputState}>
                            <label for="aAnswer" class='answerRadioLabel{$labelAnswerIndicatorsClasses["A"]}{$labelSummaryModeClass}'>A. {$test->questions[$currentQuestionNumber]["Odp_A"]}</label>
                            <input type="radio" id="bAnswer" name="multipleChoiceAnswer" value="B" class="invisibleRadio"{$radioInputState}>
                            <label for="bAnswer" class='answerRadioLabel{$labelAnswerIndicatorsClasses["B"]}{$labelSummaryModeClass}'>B. {$test->questions[$currentQuestionNumber]["Odp_B"]}</label>
                            <input type="radio" id="cAnswer" name="multipleChoiceAnswer" value="C" class="invisibleRadio"{$radioInputState}>
                            <label for="cAnswer" class='answerRadioLabel{$labelAnswerIndicatorsClasses["C"]}{$labelSummaryModeClass}'>C. {$test->questions[$currentQuestionNumber]["Odp_C"]}</label>
                            <input type="radio" id="noAnswer" name="multipleChoiceAnswer" value="NOT ANSWERED" class="invisibleRadio" checked>
                        HTML;
                    } else if ($test->questions[$currentQuestionNumber]["Poprawna_odp"] === "T" || $test->questions[$currentQuestionNumber]["Poprawna_odp"] === "N") {
                        echo <<<HTML
                            <input type="radio" id="trueAnswer" name="multipleChoiceAnswer" value="T" class="invisibleRadio"{$radioInputState}>
                            <label for="trueAnswer" class='answerRadioLabel{$labelAnswerIndicatorsClasses["T"]}{$labelSummaryModeClass}'>Tak</label>
                            <input type="radio" id="falseAnswer" name="multipleChoiceAnswer" value="N" class="invisibleRadio"{$radioInputState}>
                            <label for="falseAnswer" class='answerRadioLabel{$labelAnswerIndicatorsClasses["N"]}{$labelSummaryModeClass}'>Nie</label>
                            <input type="radio" id="noAnswer" name="multipleChoiceAnswer" value="NOT ANSWERED" class="invisibleRadio" checked>
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
        <?php
            if ($test->automaticMode && !$summaryMode) {
                echo '<script src="randomizeAnswers.js"></script>';
            }
        ?>
    </body>
</html>