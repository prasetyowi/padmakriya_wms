<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8" />
   <title><label name="CAPTION-FORMPERMINTAANPENGELUARANBARANGBULK"></label></title>
   <!-- bootstrap & fontawesome -->
   <style type="text/css">
      /* KELAS UNTUK PAGE BREAK */
      .break {
         page-break-before: always;
         /* page-break-inside: avoid; */
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
         height: 21cm;
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

      table.gridtable th {
         border-width: 1px;
         font-size: 15px;
         padding-right: 3px;
         padding-left: 3px;
         /* padding: 8px; */
         border-style: solid;
         border-color: #666666;
         background-color: #dedede;
      }

      table.gridtable td {
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
   <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
</head>

<body style="font-family:'Courier New', Courier, monospace">
   <div class="lanscape">
      <header class="clearfix">
         <center>
            <h3 name="CAPTION-PERMINTAANPENGELUARANBARANGBULK"></h3>
         </center>
         <br>
         <!-- status manifes do -->
         <table class="table12" id="list_emp" width="100%" style="font-size: 15px;">
            <tbody>
               <tr>
                  <td width="20%" name="CAPTION-NAMACHECKER"></td>
                  <td width="50%"><?= $bkbbulk[0]['karyawan_nama'] ?></td>
                  <td width="33%" class="text-center" style="vertical-align: middle;" rowspan="3">
                     <?php
                     require 'vendor/autoload.php';

                     $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                     echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($bkbbulk[0]['picking_list_kode'], $generator::TYPE_CODE_128)) . '" style="width:200px;height:50px;">';
                     ?>
                     <!-- <img class="barcode" src="<?= base_url('assets/images/barcode.png'); ?>" style="width: 200px"/> -->
                     <p class="text-center"><?= $bkbbulk[0]['picking_list_kode'] ?></p>
                  </td>
               </tr>
               <tr>
                  <td width="20%" name="CAPTION-TGLPPB"></td>
                  <td width="50%"><?= $bkbbulk[0]['picking_order_tanggal'] ?></td>
               </tr>
               <tr>
                  <td width="20%" name="CAPTION-TGLPB"></td>
                  <td width="50%"><?= $bkbbulk[0]['picking_list_tgl_kirim'] ?></td>
               </tr>
               <tr>
                  <td width="20%" name="CAPTION-PRINCIPLE"></td>
                  <td width="50%"><?= $bkbbulk[0]['principal'] ?></td>
                  <td width="33%" class="text-center" style="vertical-align: middle;" rowspan="3">
                     <?php
                     require 'vendor/autoload.php';

                     $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
                     echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($bkbbulk[0]['picking_order_kode'], $generator::TYPE_CODE_128)) . '" style="width:200px;height:50px;">';
                     ?>
                     <!-- <img class="barcode" src="<?= base_url('assets/images/barcode.png'); ?>" style="width: 200px"/> -->
                     <p class="text-center"><?= $bkbbulk[0]['picking_order_kode'] ?></p>
                  </td>
               </tr>
               <tr>
                  <td width="20%" name="CAPTION-DICETAKOLEH"></td>
                  <td width="50%"><?= $this->session->userdata('pengguna_username') ?></td>
               </tr>
               <tr>
                  <td width="20%" name="CAPTION-TGLPPB"></td>
                  <td width="50%"><?= date('d-m-Y') ?></td>
               </tr>
            </tbody>
         </table>
         <br><br>
         <center>
            <div>
               <table class="gridtable" id="list_emp" width="100%" style="font-size: 15px;">
                  <thead>
                     <tr>
                        <th class="text-center" name="CAPTION-NO"></th>
                        <th class="text-center" name="CAPTION-SKUKODE"></th>
                        <th class="text-center" name="CAPTION-NAMABARANG"></th>
                        <th class="text-center" name="CAPTION-KEMASAN"></th>
                        <th class="text-center" name="CAPTION-SATUAN"></th>
                        <th class="text-center" name="CAPTION-QTYBARANG"></th>
                        <th class="text-center" name="CAPTION-PERMINTAANEDBARANG"></th>
                        <th class="text-center" name="CAPTION-LOKASIGUDANGBARANG"></th>
                        <th class="text-center" name="CAPTION-QTYDIAMBIL"></th>
                        <th class="text-center" name="CAPTION-TGLBARANGDIAMBIL"></th>
                        <th class="text-center" name="CAPTION-DAFTARPERIKSA"></th>
                     </tr>
                  </thead>
                  <tbody>
                     <?php
                     $no = 1;

                     foreach ($bkbbulk as $row) {

                     ?>
                        <tr>
                           <td class="text-center"><?= $no ?></td>
                           <td class="text-center"><?= $row['sku_kode'] ?></td>
                           <td class="text-center"><?= $row['sku_nama_produk'] ?></td>
                           <td class="text-center"><?= $row['sku_kemasan'] ?></td>
                           <td class="text-center"><?= $row['sku_satuan'] ?></td>
                           <td class="text-center"><?= round($row['sku_stock_qty_ambil']); ?></td>
                           <td class="text-center"><?= $row['sku_stock_expired_date'] ?></td>
                           <td class="text-center"><?= $row['rak_nama'] ?></td>
                           <td class="text-center"><?= round($row['sku_stock_qty_ambil_aktual']); ?></td>
                           <td class="text-center"><?= $row['sku_stock_expired_date_aktual'] ?></td>
                           <td class="text-center"><input class="text-center" type="checkbox"></td>
                        </tr>
                     <?php
                        $no++;
                     }
                     ?>
                  </tbody>
               </table>
            </div>
         </center>
         <p>
   </div>
   <footer>
      <center>
         <table class="table12" width="80%" style="font-size: 15px;">
            <tbody>
               <tr>
                  <td class="text-center" colspan="3" name="CAPTION-MENGETAHUI"></td>
               </tr>
               <tr>
                  <td class="text-center" width="33%">TTD</td>
                  <td class="text-center" width="33%"></td>
                  <td class="text-center" width="33%">TTD</td>
               </tr>
               <tr>
                  <td class="text-center" width="33%"><br><br><br><br><br><br></td>
                  <td class="text-center" width="33%"><br><br><br><br><br><br></td>
                  <td class="text-center" width="33%"><br><br><br><br><br><br></td>
               </tr>
               <tr>
                  <td class="text-center" width="33%">..............</td>
                  <td class="text-center" width="33%"></td>
                  <td class="text-center" width="33%" name="CAPTION-CHECKER"></td>
               </tr>
            </tbody>
         </table>
      </center>
   </footer>
</body>

</html>
<script>
   window.print();
</script>