<?php
    session_start();
    require_once __DIR__ . '/../includes/load_data.php';
    $characterDataset = load_data();

// Opdracht 7: Bouw het "Wie is het?" spel.
    // 1. Kies een willekeurig personage en sla dit op in de sessie.
    // 2. Maak een formulier waarmee de speler een feature kan kiezen om te vragen.
    // 3. Vergelijk de gekozen feature met die van het geheime personage.
    // 4. Geef antwoord ("Ja" of "Nee").
    // 5. Filter de lijst van overgebleven kandidaten op basis van het antwoord.
    // 6. Toon de overgebleven kandidaten.
    // 7. Voeg een reset-knop toe om een nieuw spel te starten.
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Guess Who – Board Game</title>
    <link rel="stylesheet" href="wie-is-het.css">
</head>
<body>
<div class="Titlewindow">
    <div class="Title">Wie Is Het?</div>
    <?php
    if (isset($_POST['reset'])) {
    session_unset();
    session_destroy();
    header("Location: " . $_SERVER['PHP_SELF'] . "?r=1");
    exit;
    }

    if (isset($_POST['guess'])) {
        $guessed = $_POST['guess_character'];

        if ($guessed === $_SESSION['secret_character']) {
            $_SESSION['character'] = $guessed;
        } else {
            $_SESSION['character'] = "Wrong guess!";
            unset($_SESSION['remaining'][$guessed]);
        }
    }
    ?>

    <form method="post">
        <select name="guess_character" class="Guessdropdown">
            <?php
            foreach ($_SESSION['remaining'] as $name => $data) {
                if ($name == "_feature_order") continue;
                echo "<option value='$name'>$name</option>";
            }
            ?>
        </select>
        <button type="submit" class="Guessbutton" name="guess" value="1">Guess</button>
        </form>

        <form method="post">
        <button type="submit" class="Resetbutton" name="reset" value="1">Reset Game</button>
    </form>
</div>
<div class="Mainwindow">
    <div class="Answerwindow">
        <?php
        if (!isset($_SESSION['secret_character'])) {
            $_SESSION['secret_character'] = array_rand($characterDataset);
            while ($_SESSION['secret_character'] == "_feature_order") { $_SESSION['secret_character'] = array_rand($characterDataset);}
        }
        if (!isset($_SESSION['remaining'])) {
            $_SESSION['remaining'] = $characterDataset;
        }

        $variable = null;
        if (isset($_POST['a'])) {
            $radioanswer = $_POST['a'];
            foreach ($characterDataset[$_SESSION['secret_character']]['features'] as $key => $value) {
                if ($radioanswer === $key and $value === 1) {
                    $variable = 'Yes';
                } else if ($radioanswer === $key and $value === 0) {
                    $variable = 'No';
                }
            }
            if ($variable != null) {
                $_SESSION['lastanswer'] = $variable;
            }
        }
        ?>
        <div class="Answer">
            <?php
            echo $_SESSION['lastanswer'] ?? '';
            ?>
        </div>
        <?php

        $remaining = [];

        if (isset($_POST['a'])) {
            $radioanswer = $_POST['a'];

            foreach ($_SESSION['remaining'] as $name => $data) {
                if ($name == "_feature_order") continue;

                $featureValue = $data['features'][$radioanswer];

                if ($variable === 'Yes' && $featureValue == 1) {
                    $remaining[$name] = $data;
                }

                if ($variable === 'No' && $featureValue == 0) {
                    $remaining[$name] = $data;
                }
            }

            $_SESSION['remaining'] = $remaining;
            if (count($_SESSION['remaining']) === 1) {
                $keys = array_keys($_SESSION['remaining']);
                $_SESSION['character'] = $keys[0];
            } else {
                unset($_SESSION['character']);
            }

        }
        ?>
    </div>
    <div class="Questionwindow">
        <form method="post">
            <input type="radio" class="Questionbuttons" id="man" name="a" value="man">
            <label for="man" class="Questionlabels"></label>Man?<br>

            <input type="radio" class="Questionbuttons" id="woman" name="a" value="woman">
            <label for="woman" class="Questionlabels"></label>Woman?<br>

            <input type="radio" class="Questionbuttons" id="hair_blond" name="a" value="hair_blond">
            <label for="hair_blond" class="Questionlabels"></label>Blond hair?<br>

            <input type="radio" class="Questionbuttons" id="hair_brown" name="a" value="hair_brown">
            <label for="hair_brown" class="Questionlabels"></label>Brown hair?<br>

            <input type="radio" class="Questionbuttons" id="hair_black" name="a" value="hair_black">
            <label for="hair_black" class="Questionlabels"></label>Black hair?<br>

            <input type="radio" class="Questionbuttons" id="hair_red" name="a" value="hair_red">
            <label for="hair_red" class="Questionlabels"></label>Red hair?<br>

            <input type="radio" class="Questionbuttons" id="hair_white" name="a" value="hair_white">
            <label for="hair_white" class="Questionlabels"></label>White hair?<br>

            <input type="radio" class="Questionbuttons" id="bald" name="a" value="bald">
            <label for="bald" class="Questionlabels"></label>Bald?<br>

            <input type="radio" class="Questionbuttons" id="mustache" name="a" value="mustache">
            <label for="mustache" class="Questionlabels"></label>Mustache?<br>

            <input type="radio" class="Questionbuttons" id="beard" name="a" value="beard">
            <label for="beard" class="Questionlabels"></label>Beard?<br>

            <input type="radio" class="Questionbuttons" id="glasses" name="a" value="glasses">
            <label for="glasses" class="Questionlabels"></label>Glasses?<br>

            <input type="radio" class="Questionbuttons" id="hat" name="a" value="hat">
            <label for="hat" class="Questionlabels"></label>Hat?<br>

            <input type="radio" class="Questionbuttons" id="earrings" name="a" value="earrings">
            <label for="earrings" class="Questionlabels"></label>Earrings?<br>

            <button type="submit" class="Questionbutton">Answer</button>
        </form>

    </div>
    <div class="Peoplewindow">
        <?php
        $i = 0;
        $charactersToShow = $_SESSION['remaining'] ?? $characterDataset;

        foreach ($characterDataset as $name => $data) {
            if ($name == "_feature_order"){continue;}
            $row = floor($i / 6);
            $hidden = !isset($charactersToShow[$name]) ? "hide" : "";
            echo "<div class='People $hidden row-$row'><img src='../images/{$name}.png'></div>";
            $i++;}
            ?>
        <div class="Row1cover">
            <?php if (isset($_SESSION['character'])): ?>
                <?php if ($_SESSION['character'] === $_SESSION['secret_character']): ?>
                    <center>You Won!<br> It Was <?= htmlspecialchars($_SESSION['character']) ?>!</center>
                <?php else: ?>
                    <center>Wrong guess!</center>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
if (isset($_POST['a'])):
    ?>
    <script>
        setTimeout(() => {
            window.location.href = window.location.pathname + "?refresh=1";
        }, 50);
    </script>
<?php
endif;
?>

</body>
</html>
<script src="Wie-is-het.js"></script>