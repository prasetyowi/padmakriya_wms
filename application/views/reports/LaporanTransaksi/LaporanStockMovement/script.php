<script>
	$(document).ready(
		function() {
			// if ($('#filter_stock_tanggal').length > 0) {
			// 	$('#filter_stock_tanggal').daterangepicker({
			// 		'applyClass': 'btn-sm btn-success',
			// 		'cancelClass': 'btn-sm btn-default',
			// 		locale: {
			// 			"format": "DD/MM/YYYY",
			// 			applyLabel: 'Apply',
			// 			cancelLabel: 'Cancel',
			// 		},
			// 		'startDate': '<?= date("01-m-Y") ?>',
			// 		'endDate': '<?= date("t-m-Y") ?>'
			// 	});
			// }
			// if ($('#filter_stock_tanggal1').length > 0) {
			// 	$('#filter_stock_tanggal1').daterangepicker({
			// 		'applyClass': 'btn-sm btn-success',
			// 		'cancelClass': 'btn-sm btn-default',
			// 		locale: {
			// 			"format": "DD/MM/YYYY",
			// 			applyLabel: 'Apply',
			// 			cancelLabel: 'Cancel',
			// 		},
			// 		'startDate': '<?= date("01-m-Y") ?>',
			// 		'endDate': '<?= date("t-m-Y") ?>'
			// 	});
			// }

			$(".select2").select2();
			$("#page2").hide();
			$('#table_detail').DataTable({
				responsive: true
			});
			$('#table_detail2').DataTable({
				responsive: true
			});
			// GetLaporanStockRekapData();
		}
	);

	function chgDate(checked) {
		if (checked == 'histori') {
			$("#filter_stock_tanggal").prop('disabled', false);
			$("#filter_stock_tanggal").val('<?= $lastTbgDepo ?>');
			$("#filter_stock_tanggal").attr('max', '<?= $lastTbgDepo ?>');
		} else {
			$("#filter_stock_tanggal").prop('disabled', true);
			$("#filter_stock_tanggal").val('<?= getLastTbgDepo() ?>');
		}
	}

	function chgDate1(checked) {
		if (checked == 'histori') {
			$("#filter_stock_tanggal1").prop('disabled', false);
			$("#filter_stock_tanggal1").val('<?= $lastTbgDepo ?>');
			$("#filter_stock_tanggal1").attr('max', '<?= $lastTbgDepo ?>');
		} else {
			$("#filter_stock_tanggal1").prop('disabled', true);
			$("#filter_stock_tanggal1").val('<?= getLastTbgDepo() ?>');
		}
	}

	const chkradio = (event) => {

		if (event.currentTarget.value == "0") {
			$("#page1").show('slow')
			$("#page2").hide('slow')
			$("#pp1").show('slow')
			$("#pp2").hide('slow')
		} else {
			$("#page1").hide('slow');
			$("#page2").show('slow');
			$("#pp1").hide('slow')
			$("#pp2").show('slow')
		}
	}

	const handlerFilterData = (event) => {


		if ($.fn.DataTable.isDataTable('#table_detail')) {
			$('#table_detail').DataTable().destroy();
		}

		$('#table_detail').DataTable({
			scrollX: true,
			'bInfo': true,
			'pageLength': 10,
			'serverSide': true,
			searching: false,
			'serverMethod': 'post',
			'ajax': {
				url: "<?= base_url('WMS/LaporanTransaksi/LaporanStockMovement/getDetail') ?>",
				data: {
					filter_stock_tanggal: $('#filter_stock_tanggal').val(),
					principle_id: $('#filter_stock_principle option:selected').val(),
					depo_detail_id: $('#filter_stock_gudang option:selected').val(),
					sku_kode: $('#filter_stock_sku_kode').val(),
					sku_nama: $('#filter_stock_sku_nama').val(),
					client_wms: $('#filter_stock_pt option:selected').val(),
					mode: $('#mode').val(),
				},
				beforeSend: function() {
					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false
					});
				},
				complete: function() {
					Swal.close();
				},

			},
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
			// 'fixedHeader': false,
			// 'scrollY': 500,
			// 'scrollX': true,
			// "pagingType": "full_numbers",
			"lengthMenu": [
				[5, 10, 25, 50, 100, 500, 1000],
				[5, 10, 25, 50, 100, 500, 1000]
			],
			'columns': [{
					data: 'depo_nama'
				},
				{
					data: 'principle_nama'
				},
				{
					data: 'depo_detail_nama'
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', '', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.sku_kode}</a>`;
						return button;
					},
					data: null
				},
				{
					data: 'sku_nama_produk'
				},
				{
					data: 'sku_stock_batch_no'
				},
				{
					data: 'sku_stock_expired_date'
				},
				{
					data: 'sku_kemasan'
				},
				{
					data: 'stock_awal'
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'opname_in', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.opname_in}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'penerimaan_supplier', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.penerimaan_supplier}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'penerimaan_retur_outlet', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.penerimaan_retur_outlet}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'mutasi_in_antar_gudang', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.mutasi_in_antar_gudang}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'mutasi_in_extern_gudang', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.mutasi_in_extern_gudang}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'koreksi_adjustmen_in', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.koreksi_adjustmen_in}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'pembongkaran_in', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.pembongkaran_in}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'penjualan', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.penjualan}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'opname_out', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.opname_out}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'retur_supplier', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.retur_supplier}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'pemusnahan', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.pemusnahan}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'mutasi_out_antar_gudang', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.mutasi_out_antar_gudang}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'mutasi_out_antar_cabang', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.mutasi_out_antar_cabang}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'koreksi_adjustmen_out', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.koreksi_adjustmen_out}</a>`;
						return button;
					},
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'ed_batch_barang', '${row.depo_detail_id}', 'pembongkaran_out', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.pembongkaran_out}</a>`;
						return button;
					},
					data: null
				},
				{
					data: 'stock_akhir'
				}

			],
			fixedColumns: {
				leftColumns: 8,
			},

			initComplete: function() {
				$('#table_detail_wrapper .btn-group').addClass('pull-right').css('margin-bottom', '10px');
			},
		});

	}

	const handlerFilterData2 = (event) => {


		if ($.fn.DataTable.isDataTable('#table_detail2')) {
			$('#table_detail2').DataTable().destroy();
		}

		$('#table_detail2').DataTable({
			scrollX: true,
			'bInfo': true,
			'pageLength': 10,
			'serverSide': true,
			searching: false,
			'serverMethod': 'post',
			'ajax': {
				url: "<?= base_url('WMS/LaporanTransaksi/LaporanStockMovement/getDetail') ?>",
				data: {
					filter_stock_tanggal: $('#filter_stock_tanggal1').val(),
					principle_id: $('#filter_stock_principle1 option:selected').val(),
					depo_detail_id: $('#filter_stock_gudang1 option:selected').val(),
					sku_kode: $('#filter_stock_sku_kode1').val(),
					sku_nama: $('#filter_stock_sku_nama1').val(),
					client_wms: $('#filter_stock_pt1 option:selected').val(),
					mode: $('#mode1').val(),
				},
				beforeSend: function() {
					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false
					});
				},
				complete: function() {
					Swal.close();
				},

			},
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
			// 'fixedHeader': false,
			// 'scrollY': 500,
			// 'scrollX': true,
			// "pagingType": "full_numbers",
			"lengthMenu": [
				[5, 10, 25, 50, 100, 500, 1000],
				[5, 10, 25, 50, 100, 500, 1000]
			],
			'columns': [{
					data: 'depo_nama'
				},
				{
					data: 'principle_nama'
				},
				{
					data: 'depo_detail_nama'
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}' , '', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.sku_kode}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					data: 'sku_nama_produk'
				},
				{
					data: 'sku_kemasan'
				},
				{
					data: 'stock_awal'
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','opname_in', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.opname_in}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','penerimaan_supplier', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.penerimaan_supplier}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','penerimaan_retur_outlet', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.penerimaan_retur_outlet}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','mutasi_in_antar_gudang', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.mutasi_in_antar_gudang}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','mutasi_in_extern_gudang', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.mutasi_in_extern_gudang}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','koreksi_adjustmen_in', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.koreksi_adjustmen_in}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','pembongkaran_in', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.pembongkaran_in}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','penjualan', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.penjualan}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','opname_out', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.opname_out}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','retur_supplier', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.retur_supplier}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','pemusnahan', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.pemusnahan}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','mutasi_out_antar_gudang', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.mutasi_out_antar_gudang}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','mutasi_out_antar_cabang', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.mutasi_out_antar_cabang}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','koreksi_adjustmen_out', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.koreksi_adjustmen_out}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					render: function(data, type, row) {
						var button = '';
						button += `<a onclick="upModalDetail('${row.sku_id}','${row.sku_nama_produk}', '${row.sku_kode}', 'barang', '${row.depo_detail_id}','pembongkaran_out', '${row.sku_stock_expired_date}', '${row.stock_awal}', '${row.stock_akhir}')" style="color: blue; cursor: pointer">${row.pembongkaran_out}</a>`;
						return button;
					},
					orderable: false,
					data: null
				},
				{
					data: 'stock_akhir'
				}

			],
			'fixedColumns': {
				leftColumns: 6,
			},
			initComplete: function() {
				$('#table_detail2_wrapper .btn-group').addClass('pull-right').css('margin-bottom', '10px');
			},
		});

	}

	function upModalDetail(sku_id, sku_nama_produk, sku_kode, tipe, depo_detail_id, tipe_report, sku_stock_expired_date, stock_awal, stock_akhir) {
		$(`.inputTitle`).html(`<b>${sku_kode}</b> - <b>${sku_nama_produk}</b>`);

		if ($.fn.DataTable.isDataTable('#table_stock_detail')) {
			$('#table_stock_detail').DataTable().destroy();
		}

		// $('#table_stock_detail').DataTable().destroy();
		$(`#modal-detailSKUKode`).modal('show');
		GetLaporanStockDetailData(sku_id, sku_kode, tipe, depo_detail_id, tipe_report, sku_stock_expired_date, stock_awal, stock_akhir);

	}

	function GetLaporanStockDetailData(sku_id, sku_kode, tipe, depo_detail_id, tipe_report, sku_stock_expired_date, stock_awal, stock_akhir) {
		var stockAwal = parseInt(stock_awal)

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
			// "scrollX": true,
			'processing': true,
			'serverSide': true,
			'serverMethod': 'post',
			'ajax': {
				url: "<?= base_url('WMS/LaporanTransaksi/LaporanStockMovement/GetLaporanStockMovementDetailData') ?>",
				data: {
					filter_stock_tanggal: tipe == 'barang' ? $('#filter_stock_tanggal1').val() : $('#filter_stock_tanggal').val(),
					tipe_transaksi: "",
					client_wms: "",
					depo_detail_id: depo_detail_id,
					principle_id: "",
					sku_id: sku_id,
					sku_kode: sku_kode,
					sku_nama: "",
					tipe_report: tipe_report,
					sku_stock_expired_date: sku_stock_expired_date,
					stockAwal: stockAwal,
					mode: tipe == 'barang' ? $('#mode1').val() : $('#mode').val(),
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
					// render: function(data, type, row) {
					// 	if (row.sku_stock_card_keterangan == 'Stock Awal' || row.sku_stock_card_keterangan == 'Stock Akhir') {
					// 		return ''
					// 	} else {
					// 		return row.sku_stock_card_keterangan
					// 	}
					// },
					// data: null
					data: 'sku_stock_card_keterangan'
				},
				{
					render: function(data, type, row) {
						if (row.sku_stock_card_keterangan == 'Stock Awal') {
							return 'Stock Awal'
						} else if (row.sku_stock_card_keterangan == 'Stock Akhir') {
							return 'Stock Akhir'
						} else if (parseInt(row.stock_in) > 0) {
							return 'Stock In'
						} else {
							return 'Stock Out'
						}
					},
					data: null
				},
				{
					render: function(data, type, row) {
						if (row.sku_stock_card_keterangan == 'Stock Awal') {
							return stock_awal
						} else if (row.sku_stock_card_keterangan == 'Stock Akhir') {
							return stock_akhir
						} else if (parseInt(row.stock_in) > 0) {
							return row.stock_in
						} else {
							return row.stock_out
						}
					},
					data: null
				},
				{
					// render: function(data, type, row) {
					// 	if (row.sku_stock_card_keterangan == 'Stock Awal') {
					// 		return stock_awal
					// 	} else if (row.sku_stock_card_keterangan == 'Stock Akhir') {
					// 		return stock_akhir
					// 	} else {
					// 		return row.stock_total
					// 	}
					// },
					// data: null
					data: 'stock_total'
				},
				// {
				// 	data: 'stock_total'
				// },
			],
		});
	}
</script>