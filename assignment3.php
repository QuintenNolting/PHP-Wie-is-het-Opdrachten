<?php
    require_once __DIR__ . '/../includes/load_data.php';
    $characterDataset = load_data();

    // Opdracht 3: Toon alle personages die een vrouw zijn.
    // Tip: Loop door alle personages en controleer de 'woman' feature.

foreach ($characterDataset as $name => $data) {
    if ($name == "_feature_order"){
        continue;
    }
    if ($data['features']['woman']=== 1) {
    echo $name . "<br>";
    }
}