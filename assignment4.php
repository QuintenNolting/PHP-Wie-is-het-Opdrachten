<?php
    require_once __DIR__ . '/../includes/load_data.php';
    $characterDataset = load_data();

    // Opdracht 4: Toon alle personages die een man zijn, kaal zijn en een bril hebben.
    // Tip: Combineer meerdere voorwaarden in je if-statement.

foreach ($characterDataset as $name => $data) {
    if ($name == "_feature_order"){
        continue;
    }
    $f = $data['features'];
    if ($data['features']['man'] === 1 && $f['bald'] === 1 && $f['glasses'] === 1) {
        echo $name . "<br>";
    }
}