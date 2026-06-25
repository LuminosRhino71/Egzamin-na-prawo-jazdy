<!DOCTYPE html>
<?php
    session_start();
    require_once("../dbconnect.php");

    $category = "B";

    $getQuestionQuery = $connection->prepare(
        'SELECT pyt.Numer_pytania, pyt.Pytanie, pyt.Odp_A, pyt.Odp_B, pyt.Odp_C, pyt.Poprawna_odp, pyt.Media, pyt.Zakres_struktury, pyt.Liczba_punktow
        FROM pytania_egzaminacyjne as pyt WHERE Kategorie LIKE :category /*AND Zakres_struktury LIKE :questionLevel AND Liczba_punktow = :pointQuantity*/
        ORDER BY RAND() LIMIT :questionQuantity'
    );

    $categoryValue = "%{$category},%";
    $getQuestionQuery->bindValue(':category', $categoryValue, PDO::PARAM_STR);
    //$getQuestionQuery->bindValue(':questionLevel', $questionLevel, PDO::PARAM_STR);
    //$getQuestionQuery->bindValue(':pointQuantity', $pointQuantity, PDO::PARAM_INT);
    $getQuestionQuery->bindValue(':questionQuantity', 1, PDO::PARAM_INT);
    $getQuestionQuery->execute();
    $question = $getQuestionQuery->fetch();
?>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tryb Nauki</title>
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="../variable_styles.css">
    </head>
    <body>
        <header>
            <h1>Ucz się</h1>
        </header>
        <main>
            <a href="../" class="defaultButton">Porzuć naukę</a>
            <?php
                if (!empty($question)) {
                    $mediaContainerDisplayCSSProperty = $question["Media"] ? "block" : "none";

                    echo <<<HTML
                        <div id="questionInfo-MediaContainer" class="horizontalContainer">
                            <div class="mediaContainer" style="display: {$mediaContainerDisplayCSSProperty}">
                    HTML;

                    if (str_ends_with($question["Media"], ".mp4")) {
                        echo <<<HTML
                            <video id="questionVideo" class="questionMedia" autoplay muted>
                                <source src='../test/media/{$question["Media"]}' type='video/mp4'/>
                            </video>
                        HTML;
                    } else if (str_ends_with($question["Media"], ".jpg")) {
                        echo <<<HTML
                            <img id="questionImage" src='../test/media/{$question["Media"]}' class="questionMedia" alt='Obraz załączony do pytania'/>
                        HTML;
                    }

                    echo <<<HTML
                            </div>
                        </div>
                        <p>{$question["Pytanie"]}</p>
                    HTML;

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

                    $summaryMode = isset($_POST["summaryMode"]) && $_POST["summaryMode"] === true;
                    if ($summaryMode) {
                        $radioInputState = " disabled";
                        $labelSummaryModeClass = " deactivateLabel";
                        $labelAnswerIndicatorsClasses[$test->questions[$currentQuestionNumber]["Poprawna_odp"]] = " correct";
                        if (!$test->answersCorrectness[$currentQuestionNumber]) {
                            $labelAnswerIndicatorsClasses[$test->answersChosen[$currentQuestionNumber]] = " incorrectlyChosen";
                        }
                    }

                    if ($question["Poprawna_odp"] === "A" || $question["Poprawna_odp"] === "B" || $question["Poprawna_odp"] === "C") {
                        echo <<<HTML
                            <input type="radio" id="aAnswer" name="multipleChoiceAnswer" value="A" class="invisibleRadio"{$radioInputState}>
                            <label for="aAnswer" class='answerRadioLabel{$labelAnswerIndicatorsClasses["A"]}{$labelSummaryModeClass}'>A. {$question["Odp_A"]}</label>
                            <input type="radio" id="bAnswer" name="multipleChoiceAnswer" value="B" class="invisibleRadio"{$radioInputState}>
                            <label for="bAnswer" class='answerRadioLabel{$labelAnswerIndicatorsClasses["B"]}{$labelSummaryModeClass}'>B. {$question["Odp_B"]}</label>
                            <input type="radio" id="cAnswer" name="multipleChoiceAnswer" value="C" class="invisibleRadio"{$radioInputState}>
                            <label for="cAnswer" class='answerRadioLabel{$labelAnswerIndicatorsClasses["C"]}{$labelSummaryModeClass}'>C. {$question["Odp_C"]}</label>
                            <input type="radio" id="noAnswer" name="multipleChoiceAnswer" value="NOT ANSWERED" class="invisibleRadio" checked>
                        HTML;
                    } else if ($question["Poprawna_odp"] === "T" || $question["Poprawna_odp"] === "N") {
                        echo <<<HTML
                            <input type="radio" id="trueAnswer" name="multipleChoiceAnswer" value="T" class="invisibleRadio"{$radioInputState}>
                            <label for="trueAnswer" class='answerRadioLabel{$labelAnswerIndicatorsClasses["T"]}{$labelSummaryModeClass}'>Tak</label>
                            <input type="radio" id="falseAnswer" name="multipleChoiceAnswer" value="N" class="invisibleRadio"{$radioInputState}>
                            <label for="falseAnswer" class='answerRadioLabel{$labelAnswerIndicatorsClasses["N"]}{$labelSummaryModeClass}'>Nie</label>
                            <input type="radio" id="noAnswer" name="multipleChoiceAnswer" value="NOT ANSWERED" class="invisibleRadio" checked>
                        HTML;
                    }

                    echo /*html*/'</form>';
                    echo /*html*/'<button type="submit" id="submitAnswerButton" class="defaultButton" form="answerForm">Następne pytanie</button>';
                } else {
                    echo /*html*/'<p class="errorMessage">Wystąpił błąd podczas pobierania pytania.</p>';
                }
            ?>
        </main>
        <script src="app.js"></script>
    </body>
</html>