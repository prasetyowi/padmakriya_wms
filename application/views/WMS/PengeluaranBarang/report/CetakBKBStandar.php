<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8" />
   <title>Print Cetak BKB Standar</title>
   <title><label name="CAPTION-FORMPERMINTAANPENGELUARANBARANGBULK"></label></title>
   <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
   <!-- bootstrap & fontawesome -->
   <style type="text/css">
      * {
         margin: 0;
         padding: 0;
         /* box-sizing: border-box; */
      }

      /* KELAS UNTUK PAGE BREAK */
      .break {
         page-break-before: always;
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
   <style type="text/css" media="print">
      @page {
         size: landscape;
      }
   </style>
   <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
</head>

<body style="font-family:'Courier New', Courier, monospace">


   <script>
      const urlSearchParams = new URLSearchParams(window.location.search);
      const pickingOrderId = Object.fromEntries(urlSearchParams.entries()).picking_order_id;
      // window.print();

      $(document).ready(function() {

         MergeCommonRows($('#initDataCetakBKBStandar'));
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
               // check if current row "break" exist in the array. If not, then extend rowspan:
               if (previous == content && content !== "" && $.inArray(index, firstColumnBrakes) === -1) {
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

      // console.log(MergeCommonRows($('#initDataCetakBKBStandar')));
   </script>

   <div class="lanscape">
      <header class="clearfix" style="margin-top: 10px;">
         <h1 class="text-center">Data BKB Standar</h1>
         <!-- <h3 class="text-center">Principle : <?= $bkbbulk[0]->principal ?></h3> -->
         <br>
      </header>

      <div>
         <table class="table table-striped" width="100%" id="initDataCetakBKBStandar">
            <thead>
               <tr class="text-center">
                  <th>No. DO</th>
                  <th>SKU Kode</th>
                  <th>SKU Produk</th>
                  <th>Jumlah Barang</th>
                  <th>Sisa Ambil</th>
                  <th>Lokasi</th>
                  <th>Rak</th>
                  <th>Pallet</th>
                  <th>SKU</th>
                  <th>Sisa Stock Qty</th>
                  <th>Qty Aktual Ambil</th>
               </tr>
            </thead>
            <tbody>
               <?php foreach ($bkbstandar as $key => $value) { ?>
                  <tr class="text-center">
                     <td class="td_l1"><?= $value->delivery_order_kode ?></td>
                     <td class="td_l1"><?= $value->sku_kode ?></td>
                     <td class="td_l1"><?= $value->sku_nama_produk ?></td>
                     <td class="td_l1"><?= $value->sku_stock_qty_ambil ?></td>
                     <td class="td_l1"><?= $value->sku_stock_qty_ambil - $value->total_ambil ?></td>
                     <td class="td_l1"><?= $value->depo_detail_nama ?></td>
                     <td class="td_l1"><?= $value->rak_lajur_detail_nama ?></td>
                     <td><?= $value->pallet_kode ?></td>
                     <td><?= $value->detail_sku ?></td>
                     <td><?= $value->sku_stock_qty ?></td>
                     <td></td>
                  </tr>
               <?php } ?>
            </tbody>
         </table>
      </div>

   </div>

   <script>
      window.print();
   </script>

</body>

</html>