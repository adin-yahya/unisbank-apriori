<?php
use Phpml\Association\Apriori;
// https://medium.com/@infharis/data-mining-definisi-dan-cara-kerja-algoritma-apriori-untuk-pencarian-association-rule-a44a8f864a61

echo '<pre>';
$samples = [
    ['pena', 'roti', 'mentega'],
    ['roti', 'mentega'],
    ['roti', 'mentega', 'telur', 'susu'],
    ['roti', 'mentega', 'kecap', 'telur', 'susu'],
    ['buncis', 'telur', 'susu'],
];
$labels  = [];

$associator = new Apriori($support = 0.2, $confidence = 0.5);
$associator->train($samples, $labels);
$assoc = $associator->getRules();
$frequent = $associator->apriori();


// =================================================
// Iterasi Apriori
// =================================================
for ($i = 0; $i <= count($frequent); $i++) {
    if (!empty($frequent[$i])) {
        for ($j = 0; $j <= count($frequent[$i]); $j++) {
            if (!empty($frequent[$i][$j])) {
                $tempVar = $frequent[$i][$j];
                $iteration[$i][$j]['itemset'] = join(", ",$tempVar);
                $iteration[$i][$j]['support'] = $associator->support($tempVar);
                $iteration[$i][$j]['frequency'] = $associator->frequency($tempVar);
                echo '<br>';
            }
        }
    }
}
print_r($iteration);


// =================================================
// Pembentukan Aturan Assosiatif
// =================================================
for ($i = 0; $i < count($assoc); $i++) {
    $aturan[$i]['aturan_asosiatif'] = join(", ",$assoc[$i]['antecedent']);
    $aturan[$i]['result'] = join(", ",$assoc[$i]['consequent']);
    $aturan[$i]['support_AUB'] = $assoc[$i]['support'];
    $aturan[$i]['confidence'] = $assoc[$i]['confidence'];
}
print_r($aturan);

echo '</pre>';
