<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>laporan_pembongkaran/pengemasan <?= date('d-m-Y H:i:s') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <style>
        @media print {
            @page {
                size: A4 landscape;
            }

            .table2 {
                /* page-break-inside: avoid; */
                font-family: Arial, Helvetica, sans-serif;
                width: 100%;
            }

            hr {
                border: 3px dashed #ddd;
            }

            .table2 th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: center;
                border-top: 3px dashed #ddd;
                border-bottom: 3px dashed #ddd;
            }


            td,
            th {
                /* padding: 8px; */
                text-align: center;
                font-family: sans-serif;
                font-style: normal;
                font-size: 12px;
                border-bottom: 3px dashed #D3D3D3;
            }
        }
    </style>
</head>

<body>
    <div class="mx-5">
        <center>
            <h2>Laporan Hasil Pembongkaran / Pengemasan</h3>
                <hr>
        </center>

        <div class="mt-2">
            <table style="padding-bottom: 10px">
                <tr style="page-break-before:avoid">
                    <th style="text-align: left; border: 0;">Kode</th>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">:</td>
                    <td style="text-align: left; border: 0; padding-left: 1rem;"><?= $header['tr_konversi_sku_kode'] ?>
                    </td>
                </tr>
                <tr style="page-break-before:avoid">
                    <th style="text-align: left; border: 0;">Tanggal</th>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">:</td>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">
                        <?= $header['tr_konversi_sku_tanggal'] ?></td>
                </tr>
                <tr style="page-break-before:avoid">
                    <th style="text-align: left; border: 0;">Status</th>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">:</td>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">
                        <?= $header['tr_konversi_sku_status'] ?></td>
                </tr>
                <tr style="page-break-before:avoid">
                    <th style="text-align: left; border: 0;">Perusahaan</th>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">:</td>
                    <td style="text-align: left; border: 0; padding-left: 1rem;"><?= $header['client_wms_nama'] ?></td>
                </tr>
                <tr style="page-break-before:avoid">
                    <th style="text-align: left; border: 0;">Tipe</th>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">:</td>
                    <td style="text-align: left; border: 0; padding-left: 1rem;"><?= $header['tipe_konversi_nama'] ?>
                    </td>
                </tr>
                <tr style="page-break-before:avoid">
                    <th style="text-align: left; border: 0;">Area</th>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">:</td>
                    <td style="text-align: left; border: 0; padding-left: 1rem;"><?= $header['depo_detail_nama'] ?></td>
                </tr>
                <tr style="page-break-before:avoid">
                    <th style="text-align: left; border: 0;">Keterangan</th>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">:</td>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">
                        <?= $header['tr_konversi_sku_keterangan'] ?></td>
                </tr>
            </table>
        </div>

        <div style="margin-top:15px;">
            <table class="table table-bordered table-striped table2" width="100%" id="table-detail">
                <thead style="page-break-before:avoid">
                    <tr style="background-color: #F1F6F9;" class="text-center">
                        <th rowspan="2" style="vertical-align: middle">Kode SKU</th>
                        <th rowspan="2" style="vertical-align: middle">SKU</th>
                        <!-- <th rowspan="2" style="vertical-align: middle">Brand</th>
                        <th rowspan="2" style="vertical-align: middle">Kemasan</th> -->
                        <th rowspan="2" style="vertical-align: middle">Satuan</th>
                        <th rowspan="2" style="vertical-align: middle">Exp Date</th>
                        <th rowspan="2" style="vertical-align: middle">Qty Plan</th>
                        <th colspan="5">Area Asal</th>
                        <th colspan="5">Area Tujuan</th>
                    </tr>
                    <tr style="background-color: #F1F6F9;" class="text-center">
                        <th style="vertical-align: middle">Area</th>
                        <th style="vertical-align: middle">Lokasi</th>
                        <th style="vertical-align: middle">No. Pallet</th>
                        <th style="vertical-align: middle">Qty Aktual</th>
                        <th style="vertical-align: middle">Satuan</th>
                        <th style="vertical-align: middle">Area</th>
                        <th style="vertical-align: middle">Lokasi</th>
                        <th style="vertical-align: middle">No. Pallet</th>
                        <th style="vertical-align: middle">Qty Konversi</th>
                        <th style="vertical-align: middle">Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detail as $data) { ?>
                        <tr class="text-center">
                            <td style="vertical-align: middle"><?= $data['sku_kode'] ?></td>
                            <td style="vertical-align: middle"><?= $data['sku_nama_produk'] ?></td>
                            <!-- <td style="vertical-align: middle"><?= $data['brand'] ?></td>
                        <td style="vertical-align: middle"><?= $data['sku_kemasan'] ?></td> -->
                            <td style="vertical-align: middle"><?= $data['sku_satuan'] ?></td>
                            <td style="vertical-align: middle"><?= $data['expired_date'] ?></td>
                            <td style="vertical-align: middle"><?= $data['qty_plan'] ?></td>
                            <td style="vertical-align: middle"><?= $data['area'] ?></td>
                            <td style="vertical-align: middle"><?= $data['lokasi_asal'] ?></td>
                            <td style="vertical-align: middle"><?= $data['kode_asal'] ?></td>
                            <td style="vertical-align: middle"><?= $data['qty_aktual'] ?></td>
                            <td style="vertical-align: middle"><?= $data['satuan_asal'] ?></td>
                            <td style="vertical-align: middle"><?= $data['area'] ?></td>
                            <td style="vertical-align: middle"><?= $data['lokasi_tujuan'] ?></td>
                            <td style="vertical-align: middle"><?= $data['kode_tujuan'] ?></td>
                            <td style="vertical-align: middle"><?= $data['qty_konversi'] ?></td>
                            <td style="vertical-align: middle"><?= $data['satuan_tujuan'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <!-- <tbody>
                    <?php foreach ($detail as $key => $value) { ?>
                        <?php foreach ($value['data'] as $key2 => $val) { ?>
                            <tr>
                                <?php if ($key2 == 0) { ?>
                                    <td style="vertical-align: middle" rowspan="<?= count($value['data']) ?>">
                                        <?= $value['kode_rak'] ?></td>
                                    <td style="vertical-align: middle" rowspan="<?= count($value['data']) ?>"><?= $value['area'] ?>
                                    </td>
                                <?php } ?>
                                <td style="vertical-align: middle"><?= $val['kode_sku'] ?></td>
                                <td style="vertical-align: middle"><?= $val['nama_sku'] ?></td>
                                <td style="vertical-align: middle"><?= $val['exp_date'] ?></td>
                                <td style="vertical-align: middle"><?= $val['kemasan'] ?></td>
                                <td style="vertical-align: middle"><?= $val['satuan'] ?></td>
                                <td style="vertical-align: middle"><?= $val['qty_sistem'] ?></td>
                                <td style="vertical-align: middle"><?= $val['qty_aktual'] ?></td>
                                <td style="vertical-align: middle"><?= $val['deviasi'] ?></td>
                                <td style="vertical-align: middle"><?= $val['sku_batch_no'] ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody> -->
            </table>
        </div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            window.print();
        });
    </script>
</body>

</html>