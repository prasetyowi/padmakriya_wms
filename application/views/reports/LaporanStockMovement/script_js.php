<script type="text/javascript">
	var index_pallet = 0;

	$(document).ready(
		function() {
			$(".select2").select2();
		}
	);

	$("#btn_stock_pencarian").click(
		function() {
			var tipe = $("#filter_stock_opsi").val();

			if (tipe == "rekap") {
				$('#table_stock_rekap').DataTable().destroy();
				GetLaporanStockRekapData();
			} else if (tipe == "detail") {
				$('#table_stock_detail').DataTable().destroy();
				GetLaporanStockDetailData();
			} else {
				var msg = 'Pilih Opsi!';

				Swal.fire({
					icon: 'error',
					title: 'Error',
					text: msg
				});
			}
		}
	);

	function GetLaporanStockRekapData() {
		$(".panel-stock-rekap").show();
		$(".panel-stock-detail").hide();

		$('#table_stock_rekap').DataTable({
			paging: false,
			ordering: false,
			info: false,
			searching: false,
			// dom: "Blfrtip",
			// buttons: [{
			// 	extend: 'excel',
			// 	className: "btn-sm",
			// 	messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
			// }],
			// autoWidth: 'true',
			// dom: 'Bfrtip',
			// dom: "Blfrtip",
			dom: 'Bfrtip',
			buttons: [{
					extend: "print",
					text: '<i class="fa fa-print"></i> Print',
					className: 'btn btn-warning',
					customize: function(win) {

						var last = null;
						var current = null;
						var bod = [];

						var css = '@page { size: landscape; }',
							head = win.document.head || win.document.getElementsByTagName('head')[0],
							style = win.document.createElement('style');

						style.type = 'text/css';
						style.media = 'print';

						if (style.styleSheet) {
							style.styleSheet.cssText = css;
						} else {
							style.appendChild(win.document.createTextNode(css));
						}

						head.appendChild(style);
					}
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel-o"></i> Excel',
					className: 'btn btn-success',
				},
				{
					extend: 'csvHtml5',
					text: '<i class="fa fa-file"></i> CSV',
					className: 'btn btn-info',
				},
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					text: '<i class="fa fa-file-pdf-o"></i> PDF',
					className: 'btn btn-danger',
					pageSize: 'A4'
				}
			],
			'processing': true,
			'serverSide': true,
			// "scrollX": true,
			'serverMethod': 'post',
			'ajax': {
				url: "<?= base_url('WMS/Laporan/LaporanStockMovement/GetLaporanStockMovementRekapData') ?>",
				data: {
					filter_stock_tanggal: $('#filter_stock_tanggal').val(),
					tipe_transaksi: $('#filter_stock_tipe_transaksi').val(),
					client_wms: $('#filter_stock_pt').val(),
					depo_detail_id: $('#filter_stock_gudang').val(),
					principle_id: $('#filter_stock_principle').val(),
					sku_kode: $('#filter_stock_sku_kode').val(),
					sku_nama: $('#filter_stock_sku_nama').val()
				}
			},
			'columns': [{
					data: 'depo_nama'
				},
				{
					data: 'depo_detail_nama'
				},
				{
					data: 'principle_kode'
				},
				{
					data: 'tipe_mutasi_nama'
				},
				{
					data: 'stock_in'
				},
				{
					data: 'stock_out'
				},
				// {
				// 	data: 'stock_total'
				// },
			]
		});
	}

	function GetLaporanStockDetailData() {
		$(".panel-stock-rekap").hide();
		$(".panel-stock-detail").show();

		$('#table_stock_detail').DataTable({
			paging: false,
			ordering: false,
			info: false,
			searching: false,
			dom: "Blfrtip",
			buttons: [{
					extend: "print",
					text: '<i class="fa fa-print"></i> Print',
					className: 'btn btn-warning',
					customize: function(win) {

						var last = null;
						var current = null;
						var bod = [];

						var css = '@page { size: landscape; }',
							head = win.document.head || win.document.getElementsByTagName('head')[0],
							style = win.document.createElement('style');

						style.type = 'text/css';
						style.media = 'print';

						if (style.styleSheet) {
							style.styleSheet.cssText = css;
						} else {
							style.appendChild(win.document.createTextNode(css));
						}

						head.appendChild(style);
					}
				},
				{
					extend: 'excelHtml5',
					text: '<i class="fa fa-file-excel-o"></i> Excel',
					className: 'btn btn-success',
				},
				{
					extend: 'csvHtml5',
					text: '<i class="fa fa-file"></i> CSV',
					className: 'btn btn-info',
				},
				{
					extend: 'pdfHtml5',
					orientation: 'landscape',
					text: '<i class="fa fa-file-pdf-o"></i> PDF',
					className: 'btn btn-danger',
					pageSize: 'A4'
				}
			],
			"scrollX": true,
			'processing': true,
			'serverSide': true,
			'serverMethod': 'post',
			'ajax': {
				url: "<?= base_url('WMS/Laporan/LaporanStockMovement/GetLaporanStockMovementDetailData') ?>",
				data: {
					filter_stock_tanggal: $('#filter_stock_tanggal').val(),
					tipe_transaksi: $('#filter_stock_tipe_transaksi').val(),
					client_wms: $('#filter_stock_pt').val(),
					depo_detail_id: $('#filter_stock_gudang').val(),
					principle_id: $('#filter_stock_principle').val(),
					sku_kode: $('#filter_stock_sku_kode').val(),
					sku_nama: $('#filter_stock_sku_nama').val()
				}
			},
			'columns': [{
					data: 'sku_stock_card_tanggal'
				},
				{
					data: 'depo_nama'
				},
				{
					data: 'depo_detail_nama'
				},
				{
					data: 'principle_kode'
				},
				{
					data: 'sku_kode'
				},
				{
					data: 'sku_nama_produk'
				},
				{
					data: 'sku_stock_card_dokumen_no'
				},
				{
					data: 'driver_nama'
				},
				{
					data: 'sku_stock_card_keterangan'
				},
				{
					data: 'stock_in'
				},
				{
					data: 'stock_out'
				},
				// {
				// 	data: 'stock_total'
				// },
			]
		});
	}

	$(document).ready(function() {
		if ($('#filter_stock_tanggal').length > 0) {
			$('#filter_stock_tanggal').daterangepicker({
				'applyClass': 'btn-sm btn-success',
				'cancelClass': 'btn-sm btn-default',
				locale: {
					"format": "DD/MM/YYYY",
					applyLabel: 'Apply',
					cancelLabel: 'Cancel',
				},
				'startDate': '<?= date("01-m-Y") ?>',
				'endDate': '<?= date("t-m-Y") ?>'
			});
		}

	});
</script>