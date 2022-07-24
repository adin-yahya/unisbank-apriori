<?php

use Phpml\Association\Apriori;
?>
<div class="container">
    <div class="page-title-container">
        <div class="row">
            <div class="col-12 col-md-7">
                <h1 class="mb-0 pb-0 display-4" id="title">Analisa Apriori Penjualan Piala Liemo’s Trophy</h1>
            </div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <form action="" method="post">
                <div class="row mb-3">
                    <div class="col-auto form-group me-3">
                        <label class="form-label">Mulai Tanggal</label>
                        <input name="startDate" type="date" class="form-control" value="<?= isset($_POST['startDate'])? $_POST['startDate']: date("Y-m-d"); ?>" style="width: fit-content;">
                    </div>
                    <div class="col-auto form-group me-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input name="endDate" type="date" class="form-control" value="<?= isset($_POST['endDate'])? $_POST['endDate']: date("Y-m-d"); ?>" style="width: fit-content;">
                    </div>
                    <div class="col-auto form-group me-3">
                        <label class="form-label">Min Support</label>
                        <input name="support" type="number" class="form-control" step="0.1" value="<?= isset($_POST['support'])? $_POST['support']: '0.09'; ?>" style="width: fit-content;">
                    </div>
                    <div class="col-auto form-group me-3">
                        <label class="form-label">Min Confidence</label>
                        <input name="confidence" type="number" class="form-control" step="0.1" value="<?= isset($_POST['confidence'])? $_POST['confidence']: '0.2'; ?>" style="width: fit-content;">
                    </div>
                </div>
                <input type="submit" id="save" name="save" class="btn btn-primary" value="Hitung"></input>
            </form>
            <?php
            if (isset($_POST['save']) && isset($_POST['startDate']) && isset($_POST['endDate'])) {
                $dataDB = dataset("transaksi", "where tanggal between '$_POST[startDate]' and '$_POST[endDate]' ");
                $samples = array_map(function ($e) {
                    return explode(", ", $e['item']);
                }, $dataDB);
                $labels  = [];
                $associator = new Apriori($support = $_POST['support'], $confidence = $_POST['confidence']);
                // $associator = new Apriori($_POST['support'], $_POST['confidence']);
                // $associator = new Apriori(0, 0);
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
                                $iteration[$i][$j]['itemset'] = join(", ", $tempVar);
                                $iteration[$i][$j]['support'] = $associator->support($tempVar);
                                $iteration[$i][$j]['frequency'] = $associator->frequency($tempVar);
                            }
                        }
                    }
                }
                // =================================================
                // Pembentukan Aturan Assosiatif
                // =================================================
                for ($i = 0; $i < count($assoc); $i++) {
                    $aturan[$i]['aturan_asosiatif'] = join(", ", $assoc[$i]['antecedent']);
                    $aturan[$i]['result'] = join(", ", $assoc[$i]['consequent']);
                    $aturan[$i]['support_AUB'] = $assoc[$i]['support'];
                    $aturan[$i]['confidence'] = $assoc[$i]['confidence'];
                }
            }
            ?>
        </div>
    </div>
    <?php if (isset($iteration) && isset($aturan)) { ?>
        <div class="row">
            <div class="col-6">
                <div class="card mb-2">
                    <div class="card-body">
                        <h5>Dataset Transaksi</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Item</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataDB as $d) { ?>
                                    <tr>
                                        <td style="width: 1%; height: 100%; white-space: nowrap; text-align: left;"><?= strftime("%A, %d %B %Y", strtotime($d['tanggal'])) ?></td>
                                        <td><?= $d['item'] ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php foreach ($iteration as $key => $value) { ?>
                    <div class="card mb-2">
                        <div class="card-body">
                            <h5>Iterasi Ke - <?= $key ?></h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Itemset</th>
                                        <th>Support</th>
                                        <th>Frequency</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($value as $v) { ?>
                                        <tr>
                                            <td><?= $v['itemset'] ?></td>
                                            <td><?= $v['support'] ?></td>
                                            <td><?= $v['frequency'] ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="col-6">
                <div class="card mb-2">
                    <div class="card-body">
                        <h5>Hasil Algoritma Apriori</h5>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Aturan Asosiatif</th>
                                    <th>Support AUB</th>
                                    <th>Confidence</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($aturan as $a) { ?>
                                    <tr>
                                        <td>{ <?= $a['aturan_asosiatif'] ?> } -> { <?= $a['result'] ?> }</td>
                                        <td><?= $a['support_AUB'] ?></td>
                                        <td><?= round($a['confidence'], 1) ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>