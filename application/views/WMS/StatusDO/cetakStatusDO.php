<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>laporan_pembongkaran/pengemasan <?= date('d-m-Y H:i:s') ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css"
        integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
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
            <h2>Laporan Monitoring Status DO</h3>
                <hr>
        </center>

        <div class="mt-2">
            <table style="padding-bottom: 10px">
                <tr style="page-break-before:avoid">
                    <th style="text-align: left; border: 0;"><?= $mode == 0 ? 'Tanggal Order' : 'Tanggal Kirim' ?></th>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">:</td>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">
                        <?= $mode == 0 ? date('d/m/Y', strtotime($tgl)) . ' - ' . date('d/m/Y', strtotime($tgl2)) : date('d/m/Y', strtotime($tgl)) . ' - ' . date('d/m/Y', strtotime($tgl2)) ?>
                    </td>
                </tr>
                <tr style="page-break-before:avoid">
                    <th style="text-align: left; border: 0;"><?= $mode == 0 ? 'Sales' : 'Pengemudi' ?></th>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">:</td>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">
                        <?= $mode == 0 ? $sales['karyawan_nama'] : $driver['karyawan_nama'] ?>
                    </td>
                </tr>
                <tr style="page-break-before:avoid">
                    <th style="text-align: left; border: 0;"><?= $mode == 0 ? 'No SO' : 'No FDJR' ?></th>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">:</td>
                    <td style="text-align: left; border: 0; padding-left: 1rem;">
                        <?= $mode == 0 ? $noso['sales_order_no_po'] : $nofdjr['delivery_order_batch_kode'] ?>
                    </td>
                </tr>
            </table>
        </div>

        <div style="margin-top:15px;">
            <table class="table table-bordered table-striped table2" width="100%" id="table-detail">
                <thead style="page-break-before:avoid">
                    <tr style="background-color: #F1F6F9;">
                        <th class="text-center" name="CAPTION-NO">No</th>
                        <th class="text-center" name="CAPTION-TGLKIRIM">Tgl Kirim</th>
                        <th class="text-center" name="CAPTION-DO">DO</th>
                        <th class="text-center" name="CAPTION-NOSO">NO SO</th>
                        <th class="text-center" name="CAPTION-NOSOEKSTERNAL">NO SO
                            Eksternal</th>
                        <th class="text-center" name="CAPTION-NAMA">Nama</th>
                        <th class="text-center" name="CAPTION-ALAMAT">Alamat</th>
                        <th class="text-center" name="CAPTION-STATUSTERAKHIR">Status
                            Terakhir</th>
                        <th class="text-center" name="CAPTION-FLAG">Flag</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $key => $data) { ?>
                    <tr class="text-center">
                        <td style="vertical-align: middle"><?= $key + 1 ?></td>
                        <td style="vertical-align: middle"><?= $data['delivery_order_tgl_rencana_kirim'] ?></td>
                        <td style="vertical-align: middle"><?= $data['delivery_order_kode'] ?></td>
                        <td style="vertical-align: middle"><?= $data['sales_order_kode'] ?></td>
                        <td style="vertical-align: middle"><?= $data['sales_order_no_po'] ?></td>
                        <td style="vertical-align: middle"><?= $data['delivery_order_kirim_nama'] ?></td>
                        <td style="vertical-align: middle"><?= $data['delivery_order_kirim_alamat'] ?></td>
                        <td style="vertical-align: middle"><?= $data['delivery_order_status'] ?></td>
                        <td style="vertical-align: middle"><?= $data['flag'] ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js"
        integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous">
    </script>
    <script type="text/javascript">
    $(document).ready(function() {
        window.print();
    });
    </script>
</body>

</html>