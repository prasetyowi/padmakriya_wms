<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Label</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html {
            position: relative;
            min-width: 113mm;
            min-height: 89mm;
        }

        body {
            border: 1px solid black;
            font-size: 10px;
        }

        .data-list,
        .data-list td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        table .middle {
            vertical-align: middle;
            text-align: left;
            height: 100px;
        }

        @page {
            size: 100mm 100mm;
        }

        .logo {
            width: 100%;
            height: auto;
            margin-top: -30px;
            margin-bottom: -20px;
            margin-left: -20px;
        }

        @media print {

            body {
                width: 113mm;
                height: 89mm;
                font-size: 10px;
            }

            html {
                position: relative;
                min-width: 113mm;
                min-height: 89mm;
            }

        }

        .barcode {
            width: 80%;
            height: 10%;
        }
    </style>

</head>

<body>

    <?php foreach ($header as $key => $data) { ?>
        <div style="padding: 7px;page-break-inside: avoid;">
            <table width="100%">
                <tr>
                    <td width="35%" style="vertical-align: middle;">
                        INI LOGO
                    </td>
                    <!-- <td width="25%" class="text-left" style="vertical-align: middle;">
                        <h6>Label Resi</h6>
                    </td> -->
                    <td width="65%" class="text-center" style="vertical-align: middle;">
                    <?php

                    $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                    echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($data['do_kode'], $generator::TYPE_CODE_128, 1, 30)) . '">';

                    ?>
                    <p class="text-center"><?= $data['do_kode'] ?></p>

                    </td>
                </tr>
            </table>

            <table cellspacing="0" cellpadding="0" class="mt-2 data-list">
                <tr class="text-center">
                    <td colspan="1"><?= $data['tipe_pengiriman'] ? $data['tipe_pengiriman'] : '-' ?></td>
                    <td colspan="2"><?= $data['tipe_layanan'] ? $data['tipe_layanan'] : '-' ?></td>
                    <td colspan="3"><?= $data['tipe_pembayaran'] ? $data['tipe_pembayaran'] : '-' ?></td>
                </tr>
                <tr>
                    <td colspan="3">Pengirim : <?= $data['nama_pengirim'] ?> <?= $data['alamat_pengirim'] ?> <?= $data['telp_pengirim'] ?></td>
                    <td colspan="3">Estimasi Sampai : <?= $data['tgl_do'] ?></td>
                </tr>
                <tr>
                    <td colspan="3">Penerima : <?= $data['nama_customer'] ?> <?= $data['alamat_customer'] ?> <?= $data['telp_customer'] ?></td>
                    <td colspan="3">
                        <span>Tanggal : <?= date('Y-m-d') ?></span><br>
                        <span>Berat (Gr) : <?= floor($data['berat']) ?> Gr</span><br>
                        <span>Jumlah Paket : <?= $data['koli'] ?> /
                            <?php foreach ($jml_koli as $val) { ?>
                                <?php if ($data['do_id'] == $val['do_id']) { ?>
                                    <?= $val['jml_koli'] ?>
                                <?php } ?>
                            <?php } ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td width="135" valign="top" colspan="3">Item : <br>
                        <?php $no = 1;
                        foreach ($detail as $value) { ?>
                            <?php if ($value['ph_id'] == $data['ph_id']) { ?>
                                <span><?= $no++ ?>. <?= $value['nama_sku'] ?> - <?= $value['qty'] ?> Qty</span><br>
                            <?php } ?>
                        <?php } ?>
                    </td>
                    <td width="165" valign="top" colspan="3">Instruksi Khusus : <?= $data['intruksi'] ? $data['intruksi'] : '-' ?></td>
                </tr>
            </table>
        </div>
    <?php } ?>


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>

</body>


</html>