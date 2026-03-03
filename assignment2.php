<?php
    require_once __DIR__ . '/../includes/load_data.php';
    $characterDataset = load_data();

    // Opdracht 2: Kies één personage en toon al zijn/haar kenmerken (features).
    // Tip: Haal eerst de features op en loop er doorheen.
    // Toon per feature of het 'JA' (true) of 'NEE' (false) is.
    echo "Claire<br>";

    foreach ($characterDataset as $name => $data) {
        if ($name != "Claire"){ continue; }
        foreach ($data as $datas => $data2) {
            if ($datas != "features"){ continue; }
            foreach ($data2 as $key => $value) {
            echo $key . " ";
                if ($value == false) {
                    echo "False<br>";
                    }
                else if ($value == true) {
                    echo "True<br>";

                    }
                }
            }
        }{
    }