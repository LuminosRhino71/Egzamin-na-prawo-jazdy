<!DOCTYPE html>
<?php
    session_start();
    session_unset();
?>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Strona Główna</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="variable_styles.css">
    </head>
    <body>
        <header>
            <h1>Testuj.</h1>
        </header>
        <main>
            <p>Można tu rozwiązywać testy.</p>
            <div class="verticalContainer">
                <a href="test" class="defaultButton">Rozpocznij test</a>
                <a href="test?automatic=true" class="defaultButton">Uruchom test z losowo zaznaczonymi odpowiedziami</a>
            </div>
        </main>
    </body>
</html>