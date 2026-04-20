<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>report_distribusi_pengiriman_<?= date('d-M-Y H:i:s') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <style>
        .table2 {
            /* page-break-inside: avoid; */
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        .table2 tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .table2 tr:hover {
            background-color: #ddd;
        }

        hr {
            border: 1px solid black;
        }

        .table2 th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: center;
            background-color: #04AA6D;
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
            color: white;
        }

        td,
        th {
            padding: 8px;
            text-align: center;
            font-family: sans-serif;
            font-style: normal;
            font-size: 14px;
            border-bottom: 1px solid #D3D3D3;
        }

        /* tr {
        page-break-inside: avoid;
        page-break-after: auto;
    } */

        @media print {
            @page {
                size: landscape
            }

            body {
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <center>
        <h3>Cetak Laporan Pengiriman Barang</h3>
        <hr>
    </center>

    <table style="padding-bottom: 10px">
        <tr>
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
            <td style="width: 17rem; border: 0;"></td>
            <th style="text-align: left; border: 0;">Driver</th>
            <td style="text-align: left; border: 0;">:</td>
            <td style="text-align: left; border: 0;"><?= $row[0]->driver; ?></td>
        </tr>
        <tr>
            <th style="text-align: left; border: 0;">No FDJR</th>
            <td style="text-align: left; border: 0;">:</td>
            <td style="text-align: left; border: 0;"><?= $row[0]->kode; ?></td>
            <td style="width: 15rem; border: 0;"></td>
            <th style="text-align: left; border: 0;">Armada</th>
            <td style="text-align: left; border: 0;">:</td>
            <td style="text-align: left; border: 0;"><?= $row[0]->armada; ?></td>
        </tr>
        <tr>
            <th style="text-align: left; border: 0;">No Pengiriman barang</th>
            <td style="text-align: left; border: 0;">:</td>
            <td style="text-align: left; border: 0;"><?= $row[0]->kode_kirim; ?></td>
            <td style="width: 15rem; border: 0;"></td>
            <th style="text-align: left; border: 0;">Keterangan</th>
            <td style="text-align: left; border: 0;">:</td>
            <td style="text-align: left; border: 0;"><?= $row[0]->keterangan == null ? '-' : $row[0]->keterangan; ?>
            </td>
        </tr>
        <tr>
            <th style="text-align: left; border: 0;">No PBB</th>
            <td style="text-align: left; border: 0;">:</td>
            <td style="text-align: left; border: 0;"><?= $row[0]->kode_order; ?></td>
        </tr>
        <tr>

        </tr>
    </table>

    <div id="table2" style="overflow-x:auto;">
        <table width="100%" id="table2" class="table2">
            <thead>
                <tr>
                    <th>No</th>
                    <th>No. DO</th>
                    <th>Tanggal terima</th>
                    <th>Prioritas</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Tipe pembayaran</th>
                    <th>Produk</th>
                    <th>Satuan</th>
                    <th>Qty</th>
                    <th>Expired</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $no_temp = 1;
                $arr_temp = [];
                foreach ($row as $data) :
                    if (count($arr_temp) == 0) {
                        $arr_temp = array($data->no_do, $no_temp++);
                    } else {
                        if ($arr_temp[0] != $data->no_do) {
                            $arr_temp = array($data->no_do, $no_temp++);
                        }
                    }
                    if ($arr_temp[1] % 2 == 0) {
                ?>
                        <tr style="background-color: #FFFFFF;">
                            <td><?= $no ?></td>
                            <td><?= $data->no_do ?></td>
                            <td><?= date('d-m-Y', strtotime($data->tgl_terima)) ?></td>
                            <td><?= $data->rute ?></td>
                            <td><?= $data->nama ?></td>
                            <td><?= $data->alamat ?></td>
                            <td><?= $data->tipe_pembayaran == 0 ? 'Tunai' : 'Non Tunai' ?></td>
                            <td><?= $data->produk ?></td>
                            <td><?= $data->satuan ?></td>
                            <td><?= $data->qty ?></td>
                            <td><?= date('d-m-Y', strtotime($data->exp_date)) ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr style="background-color: #E8E8E8;">
                            <td><?= $no ?></td>
                            <td><?= $data->no_do ?></td>
                            <td><?= date('d-m-Y', strtotime($data->tgl_terima)) ?></td>
                            <td><?= $data->rute ?></td>
                            <td><?= $data->nama ?></td>
                            <td><?= $data->alamat ?></td>
                            <td><?= $data->tipe_pembayaran == 0 ? 'Tunai' : 'Non Tunai' ?></td>
                            <td><?= $data->produk ?></td>
                            <td><?= $data->satuan ?></td>
                            <td><?= $data->qty ?></td>
                            <td><?= date('d-m-Y', strtotime($data->exp_date)) ?></td>
                        </tr>
                    <?php } ?>
            </tbody>
        <?php
                    $no++;
                endforeach;
        ?>
        </table>
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