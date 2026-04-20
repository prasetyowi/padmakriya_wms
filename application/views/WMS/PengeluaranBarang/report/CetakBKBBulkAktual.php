<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>report_pengeluaran_barang_<?= date('d-M-Y H:i:s') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <style type="text/css">
        * {
            margin: 0;
            padding: 0;
            /* box-sizing: border-box; */
        }

        /* KELAS UNTUK PAGE BREAK */
        .break {
            page-break-after: always;
            /* page-break-before: always; */
            /* page-break-inside: avoid; */
        }


        table {
            margin: 0 auto;
        }

        table,
        th,
        td,
        tr {
            border: 1px solid black;
            border-collapse: collapse;
            /* margin: 5px; */
        }

        .table12 {
            border-width: thin;
            border-spacing: 2px;
            border-style: none;
            border-color: black;
            border-collapse: collapse;
        }

        .table12 td {
            border: 0px solid black;
        }

        .table12 th {
            border: 0px solid black;
        }

        .table12 tr {
            border: 0px solid black;
        }

        body {
            position: relative;
            /* KERTAS A4  */
            /* width: 21cm;
         height: 29.7cm; */
            /* KERTAS CONTINUES FORM  */
            /* width: 21cm;
         height: 14cm; */
            height: auto;
            width: 29.7cm;
            margin: 0 auto;
            /* to centre page on screen*/
            /* margin-left: auto;
         margin-right: auto; */
            width: 100%;
        }

        .landScape {
            width: 100%;
            height: 100%;
            margin: 0% 0% 0% 0%;
            /* filter: progid: DXImageTransform.Microsoft.BasicImage(Rotation=3); */
        }

        .table-borderless>tbody>tr>td,
        .table-borderless>tbody>tr>th,
        .table-borderless>tfoot>tr>td,
        .table-borderless>tfoot>tr>th,
        .table-borderless>thead>tr>td,
        .table-borderless>thead>tr>th {
            border: none;
        }

        .table-content.gridtable th {
            border-width: 1px;
            font-size: 15px;
            padding-right: 3px;
            padding-left: 3px;
            /* padding: 8px; */
            border-style: solid;
            border-color: #666666;
            background-color: #dedede;
        }

        .table-content.gridtable td {
            border-width: 1px;
            font-size: 15px;
            padding-right: 3px;
            padding-left: 3px;
            border-style: solid;
            border-color: #666666;
            background-color: #ffffff;
        }

        .text-center {
            text-align: center;
        }

        /* .a{
         margin-left: 100px ;
         font-family: 'Courier New', Courier, monospace;
         } */
    </style>
    <style type="text/css" media="print">
        @page {
            size: A4 landscape;
        }
    </style>
</head>

<body>
    <?php
    if ($cetak == 'perprinciple') {
        foreach ($detail as $key => $val) {
    ?>
            <div class="lanscape <?= $key + 1 == count($detail) ? '' : 'break' ?>">
                <h3 class="text-center">Data BKB Bulk</h3>
                <div class="clearfix"></div>

                <table class="table12" style="width:100%;padding-bottom: 10px;">
                    <tr>
                        <th style="text-align: left; border: 0;">No BKB</th>
                        <td style="text-align: left; border: 0;">:</td>
                        <td style="text-align: left; border: 0;"><?= $header[0]->picking_order_aktual_kode; ?></td>
                        <td style="width: 15rem; border: 0;"></td>
                        <th style="text-align: left; border: 0;">Tanggal BKB</th>
                        <td style="text-align: left; border: 0;">:</td>
                        <td style="text-align: left; border: 0;"><?= $header[0]->picking_order_aktual_tgl; ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left; border: 0;">No PBB</th>
                        <td style="text-align: left; border: 0;">:</td>
                        <td style="text-align: left; border: 0;"><?= $header[0]->picking_order_kode; ?></td>
                        </td>
                        <td style="width: 15rem; border: 0;"></td>
                        <th style="text-align: left; border: 0;">Checker</th>
                        <td style="text-align: left; border: 0;">:</td>
                        <td style="text-align: left; border: 0;"><?= $header[0]->checker_nama; ?></td>
                    </tr>
                    <tr>
                        <th style="text-align: left; border: 0;">No FDJR</th>
                        <td style="text-align: left; border: 0;">:</td>
                        <td style="text-align: left; border: 0;"><?= $header[0]->delivery_order_batch_kode; ?></td>
                        <td style="width: 15rem; border: 0;"></td>
                        <th style="text-align: left; border: 0;">Tanggal dicetak</th>
                        <td style="text-align: left; border: 0;">:</td>
                        <td style="text-align: left; border: 0;">
                            <?php
                            $hari = date('l');
                            $dayList = array(
                                'Monday'    => 'Senin',
                                'Tuesday'   => 'Selasa',
                                'Wednesday' => 'Rabu',
                                'Thursday'  => 'Kamis',
                                'Friday'    => 'Jumat',
                                'Saturday'  => 'Sabtu',
                                'Sunday'    => 'Minggu'
                            );

                            $monthList = array(
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '06' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember'
                            );

                            $tgl = date('d-m-Y');
                            $cek = explode("-", $tgl);

                            echo $dayList[$hari] . ', ' . $cek[0] . ' ' . $monthList[$cek[1]] . ' ' . $cek[2];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th style="text-align: left; border: 0;">Principle</th>
                        <td style="text-align: left; border: 0;">:</td>
                        <td style="text-align: left; border: 0;"><?= $val[0]->principal; ?></td>
                        <td style="width: 15rem; border: 0;"></td>
                        </td>
                    </tr>
                    <tr>

                    </tr>
                </table>
                <div class="clearfix"></div><br><br>

                <table class="table-content" width="100%" id="initDataCetakdetail-<?= $key ?>">
                    <thead>
                        <tr class="text-center" style="background-color: #EEE0C9;">
                            <th name="CAPTION-PRINCIPLE">Principle</th>
                            <th name="CAPTION-BRAND">Brand</th>
                            <th name="CAPTION-SKUKODE">SKU Kode</th>
                            <th name="CAPTION-NAMABARANG">Nama Barang</th>
                            <th name="CAPTION-KEMASAN">Kemasan</th>
                            <th name="CAPTION-SATUAN">Satuan</th>
                            <th name="CAPTION-RENCANAQTYAMBIL">Rencana Qty Ambil</th>
                            <th name="CAPTION-AKTUALQTYAMBIL">Aktual Qty Ambil</th>
                            <th name="CAPTION-TGLEXP">Tanggal Kadaluarsa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($detail)) { ?>

                        <?php } else { ?>
                            <?php
                            foreach ($val as $value) { ?>
                                <tr class="text-center">
                                    <td class="td_l1" style="font-size: 12px"><?= $value->principal ?></td>
                                    <td class="td_l1" style="font-size: 12px"><?= $value->brand ?></td>
                                    <td class="td_l1" style="font-size: 12px"><?= $value->sku_kode ?></td>
                                    <td class="td_l1" style="font-size: 12px"><?= $value->sku_nama_produk ?></td>
                                    <td class="td_l1" style="font-size: 12px"><?= $value->sku_kemasan ?></td>
                                    <td class="td_l1" style="font-size: 12px"><?= $value->sku_satuan ?></td>
                                    <td class="td_l1" style="font-size: 12px"><?= $value->sku_stock_qty_ambil_plan ?></td>
                                    <td class="td_l1" style="font-size: 12px"><?= $value->sku_stock_qty_ambil ?></td>
                                    <td class="td_l1" style="font-size: 12px"><?= $value->sku_stock_expired_date ?></td>
                                </tr>
                            <?php
                            } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php }
    } else { ?>
        <div class="lanscape">
            <h3 class="text-center">Data BKB Bulk</h3>
            <div class="clearfix"></div>

            <table class="table12" style="width:100%;padding-bottom: 10px;">
                <tr>
                    <th style="text-align: left; border: 0;">No BKB</th>
                    <td style="text-align: left; border: 0;">:</td>
                    <td style="text-align: left; border: 0;"><?= $header[0]->picking_order_aktual_kode; ?></td>
                    <td style="width: 15rem; border: 0;"></td>
                    <th style="text-align: left; border: 0;">Tanggal BKB</th>
                    <td style="text-align: left; border: 0;">:</td>
                    <td style="text-align: left; border: 0;"><?= $header[0]->picking_order_aktual_tgl; ?></td>
                </tr>
                <tr>
                    <th style="text-align: left; border: 0;">No PBB</th>
                    <td style="text-align: left; border: 0;">:</td>
                    <td style="text-align: left; border: 0;"><?= $header[0]->picking_order_kode; ?></td>
                    </td>
                    <td style="width: 15rem; border: 0;"></td>
                    <th style="text-align: left; border: 0;">Checker</th>
                    <td style="text-align: left; border: 0;">:</td>
                    <td style="text-align: left; border: 0;"><?= $header[0]->checker_nama; ?></td>
                </tr>
                <tr>
                    <th style="text-align: left; border: 0;">No FDJR</th>
                    <td style="text-align: left; border: 0;">:</td>
                    <td style="text-align: left; border: 0;"><?= $header[0]->delivery_order_batch_kode; ?></td>
                    <td style="width: 15rem; border: 0;"></td>
                    <th style="text-align: left; border: 0;">Tanggal dicetak</th>
                    <td style="text-align: left; border: 0;">:</td>
                    <td style="text-align: left; border: 0;">
                        <?php
                        $hari = date('l');
                        $dayList = array(
                            'Monday'    => 'Senin',
                            'Tuesday'   => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday'  => 'Kamis',
                            'Friday'    => 'Jumat',
                            'Saturday'  => 'Sabtu',
                            'Sunday'    => 'Minggu'
                        );

                        $monthList = array(
                            '01' => 'Januari',
                            '02' => 'Februari',
                            '03' => 'Maret',
                            '04' => 'April',
                            '05' => 'Mei',
                            '06' => 'Juni',
                            '07' => 'Juli',
                            '08' => 'Agustus',
                            '06' => 'September',
                            '10' => 'Oktober',
                            '11' => 'November',
                            '12' => 'Desember'
                        );

                        $tgl = date('d-m-Y');
                        $cek = explode("-", $tgl);

                        echo $dayList[$hari] . ', ' . $cek[0] . ' ' . $monthList[$cek[1]] . ' ' . $cek[2];
                        ?>
                    </td>
                </tr>
                <tr>

                </tr>
            </table>
            <div class="clearfix"></div><br><br>

            <table class="table-content" width="100%" id="initDataCetakdetail">
                <thead>
                    <tr class="text-center" style="background-color: #EEE0C9;">
                        <th name="CAPTION-PRINCIPLE">Principle</th>
                        <th name="CAPTION-BRAND">Brand</th>
                        <th name="CAPTION-SKUKODE">SKU Kode</th>
                        <th name="CAPTION-NAMABARANG">Nama Barang</th>
                        <th name="CAPTION-KEMASAN">Kemasan</th>
                        <th name="CAPTION-SATUAN">Satuan</th>
                        <th name="CAPTION-RENCANAQTYAMBIL">Rencana Qty Ambil</th>
                        <th name="CAPTION-AKTUALQTYAMBIL">Aktual Qty Ambil</th>
                        <th name="CAPTION-TGLEXP">Tanggal Kadaluarsa</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($detail)) { ?>

                    <?php } else { ?>
                        <?php
                        foreach ($detail as $value) { ?>
                            <tr class="text-center">
                                <td class="td_l1" style="font-size: 12px"><?= $value->principal ?></td>
                                <td class="td_l1" style="font-size: 12px"><?= $value->brand ?></td>
                                <td class="td_l1" style="font-size: 12px"><?= $value->sku_kode ?></td>
                                <td class="td_l1" style="font-size: 12px"><?= $value->sku_nama_produk ?></td>
                                <td class="td_l1" style="font-size: 12px"><?= $value->sku_kemasan ?></td>
                                <td class="td_l1" style="font-size: 12px"><?= $value->sku_satuan ?></td>
                                <td class="td_l1" style="font-size: 12px"><?= $value->sku_stock_qty_ambil_plan ?></td>
                                <td class="td_l1" style="font-size: 12px"><?= $value->sku_stock_qty_ambil ?></td>
                                <td class="td_l1" style="font-size: 12px"><?= $value->sku_stock_expired_date ?></td>
                            </tr>
                        <?php
                        } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

    <script>
        const urlSearchParams = new URLSearchParams(window.location.search);
        const pickingOrderId = Object.fromEntries(urlSearchParams.entries()).picking_order_id;
        // window.print();

        $(document).ready(function() {
            <?php if ($cetak == 'perprinciple') : ?>
                var total = <?= COUNT($detail); ?>;

                for (let i = 0; i < total; i++) {
                    MergeCommonRows($('#initDataCetakdetail-' + i + ''));

                }
            <?php else : ?>
                MergeCommonRows($('#initDataCetakdetail'));
            <?php endif; ?>
        });


        // let arrData = [];

        function MergeCommonRows(table) {
            var firstColumnBrakes = [];
            // iterate through the columns instead of passing each column as function parameter:
            for (var i = 1; i <= table.find('th').length; i++) {
                var previous = null,
                    cellToExtend = null,
                    rowspan = 1;
                table.find(".td_l1:nth-child(" + i + ")").each(function(index, e) {
                    var jthis = $(this),
                        content = jthis.text();
                    console.log(content);

                    // check if current row "break" exist in the array. If not, then extend rowspan:
                    if (previous == content && $.inArray(index, firstColumnBrakes) === -1) {
                        // hide the row instead of remove(), so the DOM index won't "move" inside loop.
                        jthis.addClass('hidden');
                        cellToExtend.attr("rowspan", (rowspan = rowspan + 1));
                    } else {
                        // store row breaks only for the first column:
                        if (i === 1) firstColumnBrakes.push(index);
                        rowspan = 1;
                        previous = content;
                        cellToExtend = jthis;
                    }
                });
            }
            // now remove hidden td's (or leave them hidden if you wish):
            $('td.hidden').remove();
        }

        // console.log(MergeCommonRows($('#initDataCetakdetail')));
    </script>

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