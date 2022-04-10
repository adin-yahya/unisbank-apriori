<div class="container">
    <div class="page-title-container">
        <div class="row">
            <div class="col-12 col-md-7">
                <h1 class="mb-0 pb-0 display-4" id="title">Data Penjualan Piala Liemo’s Trophy</h1>
            </div>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <form action="" method="post">
                <div class="row mb-3">
                    <div class="col-auto form-group me-3">
                        <label class="form-label">Tanggal Transaksi</label>
                        <input name="tanggal" type="date" class="form-control" value="<?php echo date("Y-m-d"); ?>" style="width: fit-content;">
                    </div>
                    <div class="col-lg col-sm-12 form-group mt-3 mt-sm-0">
                        <label class="form-label">Item Transaksi</label>
                        <select name="item[]" class="form-control" id="multiple-item" multiple="multiple" style="width: 100%">
                        </select>
                    </div>
                </div>
                <input type="submit" id="save" name="save" class="btn btn-primary" value="Simpan"></input>
            </form>
            <?php
            if (isset($_POST['save']) && isset($_POST['item'])) {
                $form['tanggal'] = $_POST['tanggal'];
                $form['item'] = implode(", ", array_map('cleansingArrayString', $_POST['item']));
                $input = create('transaksi', $form);
                if($input) echo "<script> document.location.href='index.php?page=penjualan';</script>";
            }
            if (isset($_GET['delete'])) {
                $delete = delete('transaksi', $_GET['delete']);
                if($delete) echo "<script> document.location.href='index.php?page=penjualan';</script>";
            }
            ?>
        </div>
    </div>
    <div class="card mb-2">
        <div class="card-body">
            <table class="table table-bordered table-striped dataTable py-3">
                <thead>
                    <tr>
                        <th>Aksi</th>
                        <th style="width: 1%; height: 100%; white-space: nowrap; text-align: left;">Tanggal Transaksi</th>
                        <th>Item Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $transaksi = dataset("transaksi");
                    foreach ($transaksi as $d) {
                    ?>
                        <tr>
                            <td style="width: 1%; height: 100%; white-space: nowrap; text-align: right;">
                                <a id="delete" href="<?php echo 'index.php?page=penjualan&delete=' . $d['id'] ?>" class="btn btn-danger btn-sm">
                                    <i class="now-ui-icons files_box"></i> Hapus
                                </a>
                            </td>
                            <td style="width: 1%; height: 100%; white-space: nowrap; text-align: left;"><?= strftime("%A, %d %B %Y", strtotime($d['tanggal'])) ?></td>
                            <td><?= $d['item'] ?></td>
                        </tr>
                    <?php }; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>