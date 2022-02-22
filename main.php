<?php
echo '<pre>';

$stockPrices = [];
$stockMinMaxPrices = [];
$rowFlag = 1;

if (($handle = fopen("Sample-Stock-Price-List.csv", "r")) !== false) {
    while (($data = fgetcsv($handle, 0, ",")) !== false) {
        if ($rowFlag) {
            $rowFlag = 0;
            continue;
        }
        $stockPrices[$data[2]][] = [
            'date' => $data[1],
            'price' => $data[3],
        ];

        $stockMinMaxPrices[$data[2]][] = $data[3];
    }
    fclose($handle);
}

foreach ($stockPrices as $stock => $datePrice) {
    $min = $max = 0;
    for ($i = 0; $i < count($datePrice); $i++) {
        for ($j = $i + 1; $j < count($datePrice); $j++) {
            $difference = $datePrice[$j]['price'] - $datePrice[$i]['price'];
            $max = max($max, $difference);
            $min = min($min, $difference);
        }
    }

    echo $stock . '<br/>' . $max . '<br/>' . $min . '<br/>';
}
