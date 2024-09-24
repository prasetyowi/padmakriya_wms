<html lang="en">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Berita Acara</title>

<style>
.text-center {
text-align: center;
}

.text-left {
text-align: left;
}

.text-right {
text-align: right;
}

.padding-left {
padding-left: 10px;
}

.padding-top {
    padding-top: 100px;
    }

.padding {
padding: 10px;
}

.table {
border-collapse: collapse;
border: 1px solid;
width: 100%;
text-align: left;
}

.table-do {
    border-collapse: collapse;
    border: 1px solid;
    width: 100%;
    text-align: left;
    padding-top: 30px;
    }

.table-ttd {
border: none;
width: 100%;
}
</style>
</head>

<body>
<h2 class="text-center">BERITA ACARA</h2>
<h4 class="text-center">Perihal : Berita Acara Penerimaan Barang <?= $header['principle_nama'] ?></h4>
<hr style="border-top: 1px solid">
<p><strong>NO: <?= $header['no_ba'] ?></strong></p>
<p> Pada hari ini, <?= $header['tgl1'] ?></p>
<p>Dibuat berita acara mengenai Penerimaan Produk-produk <?= $header['principle_nama'] ?>, dengan rincian produk sebagai berikut :</p>
<table class="table" border="1px solid" width="100%">
<tr>
<th height="30px" class="text-left padding-left">Nama Depo</th>
<th height="30px" class="text-left padding-left"><?= $header['depo_nama'] ?></th>
</tr>
<tr>
<th height="30px" class="text-left padding-left">Nomor Shipment</th>
<th height="30px" class="text-left padding-left"><?= $header['penerimaan_surat_jalan_no_sj'] ?></th>
</tr>
<tr>
<th height="30px" class="text-left padding-left">Transporter</th>
<th height="30px" class="text-left padding-left"><?= $header['ekspedisi_nama'] ?></th>
</tr>
<tr>
<th height="30px" class="text-left padding-left">Pengirim</th>
<th height="30px" class="text-left padding-left"><?= $header['principle_nama'] ?></th>
</tr>
<tr>
<th height="30px" class="text-left padding-left">Penerima</th>
<th height="30px" class="text-left padding-left"><?= $header['client_wms_nama'] ?></th>
</tr>
<tr>
<th height="30px" class="text-left padding-left">Plat Nomor Transporter</th>
<th height="30px" class="text-left padding-left"><?= $header['penerimaan_pembelian_nopol'] ?></th>
</tr>
<tr>
<th height="30px" class="text-left padding-left">Nama Pengemudi</th>
<th height="30px" class="text-left padding-left"><?= $header['penerimaan_pembelian_pengemudi'] ?></th>
</tr>
</table>
*Copy Nota Pengiriman harap di lampirkan
<table class="table-do" border="1px solid" width="100%">
<thead>
<tr class="text-center">
<th colspan="8" height="35px">Daftar Perincian Produk <?= $header['principle_nama'] ?></th>
</tr>
<tr class="text-center">
<th class="padding">Nomer DO</th>
<th class="padding">ID</th>
<th class="padding">Nama</th>
<th class="padding">QTY SJ</th>
<th class="padding">QTY Terima</th>
<th class="padding">Z001</th>
<th class="padding">Z003</th>
<th class="padding">Z010</th>
</tr>
</thead>
<tbody>
<?php
$sku_jumlah_barang = 0;
$terima = 0;
$rusak = 0;
$barang_tertinggal = 0;
$kardus_rusak = 0;
foreach($contens as $value) :
    $sku_jumlah_barang = $sku_jumlah_barang + $value['sku_jumlah_barang'];
    $terima = $terima + $value['terima'];
    $rusak = $rusak + $value['rusak'];
    $barang_tertinggal = $barang_tertinggal + $value['barang_tertinggal'];
    $kardus_rusak = $kardus_rusak + $value['kardus_rusak'];
?>
<tr>
<td class="padding"><?= $value['penerimaan_surat_jalan_no_sj'] ?></td>
<td class="padding text-center"><?= $value['sku_kode'] ?></td>
<td class="padding"><?= $value['sku_nama_produk'] ?></td>
<td class="padding text-center"><?= $value['sku_jumlah_barang'] ?></td>
<td class="padding text-center"><?= $value['terima'] ?></td>
<td class="padding text-center"><?= $value['rusak'] ?></td>
<td class="padding text-center"><?= $value['barang_tertinggal'] ?></td>
<td class="padding text-center"><?= $value['kardus_rusak'] ?></td>
</tr>
<?php
endforeach ;
?>
<tr>
<th colspan="3" class="text-center" height="30px">Jumlah</th>
<th><?= $sku_jumlah_barang ?></th>
<th><?= $terima ?></th>
<th><?= $rusak ?></th>
<th><?= $barang_tertinggal ?></th>
<th><?= $kardus_rusak ?></th>
</tr>
</tbody>
</table>
<br>
<p></p>
<p>Demikian berita acara ini dibuat dengan sebenar-benarnya untuk dapat di gunakan sebagai mana mestinya.</p>
<br>
<p class="text-right">Di buat pada tanggal <?= $header['tgl2'] ?></p>
<table class="table-ttd">
<tr>
<td  class="text-center">Menyetujui</td>
<td  class="text-center">Dibuat</td>
<td  class="text-center">Mengetahui</td>
<td  class="text-center">Mengetahui</td>
<td  class="text-center">Mengetahui</td>
</tr>
<tr>
<td class="text-center padding-top">Driver Transporter</td>
<td class="text-center padding-top">Admin Gudang</td>
<td class="text-center padding-top">ADH</td>
<td class="text-center padding-top">Kepala Gudang</td>
<td class="text-center padding-top">Kepala depo</td>
</tr>
</table>
</body>
</html>