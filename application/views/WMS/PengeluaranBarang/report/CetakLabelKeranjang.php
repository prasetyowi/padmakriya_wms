<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Label <?php echo  $bkbstandar[0]['picking_order_kode']; ?></title>
    <!-- <title>adwa</title> -->

    <!-- <link href="<?php echo  base_url('assets/admin/css/bootstrap4-3.min.css') ?>" rel="stylesheet"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html {
            position: relative;
            /* min-width: 290px;
            min-height: 491px; */
            min-width: 113mm;
            min-height: 89mm;
            /* padding-bottom: -1000px; */
            /* padding-bottom: -17mm; */
            /* height: 190px; */
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
            /* size: portrait; */
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
                font-size: 13px;
            }

            html {
                position: relative;
                /* min-width: 290px;
                min-height: 491px; */
                min-width: 113mm;
                min-height: 89mm;
                /* height: 100%; */
            }

        }
    </style>

</head>

<body>
    <?php foreach ($label as $row) { ?>
        <div style="padding: 7px;page-break-inside: avoid;border: 1px solid black;font-size: 11px;">
            <table width="100%">
                <tr>
                    <td width="30%" class="text-center" style="vertical-align: middle;">
                        <h5>INI LOGO</h5>
                    </td>
                    <td style="vertical-align: middle;">
                        <h5 name="CAPTION-LABELKERANJANG"></h5>
                    </td>
                </tr>
            </table>

            <table width="100%" cellspacing="0" cellpadding="0" class="mt-2 data-list">
                <tr class="text-center">
                    <td><?php echo $row['delivery_order_kode']; ?></td>
                    <td><?php echo $row['picking_order_aktual_kode']; ?></td>
                    <td><?php echo $row['picking_list_kode']; ?></td>
                </tr>
                <tr class="text-center">
                    <td colspan="2"><?php echo $row['karyawan_nama']; ?></td>
                    <td rowspan="2"><?php echo $row['picking_order_aktual_tgl']; ?></td>
                </tr>
                <tr class="text-center">
                    <td colspan="2"><?php echo $row['driver_nama']; ?></td>
                </tr>
                <tr>
                    <td colspan="3">
                        Item: <br>
                        <?php $no = 1;
                        foreach ($row['data'] as $value) { ?>
                            <span><?php echo  $no++ ?>. [<?php echo  $value['sku_nama_produk'] ?>] [<?php echo  $value['sku_stock_qty_ambil_aktual'] ?>]</span><br>
                        <?php } ?>

                    </td>
                    <!-- <td>Instruksi Khusus : <br><?php echo $row['picking_order_aktual_tgl']; ?></td> -->
                </tr>
            </table>
        </div>
    <?php } ?>
    <!-- <script src="<?php echo base_url('assets/admin/js/popper.js') ?>"></script> -->
    <!-- <script src="<?php echo base_url('assets/admin/js/bootstrap4-3.min.js') ?>"></script> -->

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>

</body>

</html>