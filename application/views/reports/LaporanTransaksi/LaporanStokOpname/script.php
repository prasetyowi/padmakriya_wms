<script type="text/javascript">
	var index_pallet = 0;

	$(document).ready(
		function() {
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

			$(".select2").select2();
			$("#page2").hide();
			$('#table_detail').DataTable({
				responsive: true
			});
			// GetLaporanStockRekapData();
		}
	);


	// function message(msg, msgtext, msgtype) {
	// 	Swal.fire(msg, msgtext, msgtype);
	// }

	// function message_topright(type, msg) {
	// 	const Toast = Swal.mixin({
	// 		toast: true,
	// 		position: "top-end",
	// 		showConfirmButton: false,
	// 		timer: 3000,
	// 		didOpen: (toast) => {
	// 			toast.addEventListener("mouseenter", Swal.stopTimer);
	// 			toast.addEventListener("mouseleave", Swal.resumeTimer);
	// 		},
	// 	});

	// 	Toast.fire({
	// 		icon: type,
	// 		title: msg,
	// 	});
	// }

	$("#btncariAll").click(
		function() {
			GetLaporanStockRekapData()
			let timerInterval
			Swal.fire({
				title: 'Loading...',
				html: 'Please Wait..',
				timer: 3000,
				timerProgressBar: true,
				allowOutsideClick: false,
				showCancelButton: false, // Disable the "Cancel" button
				showConfirmButton: false,
				didOpen: () => {
					Swal.showLoading()
					const b = Swal.getHtmlContainer().querySelector('b')
					timerInterval = setInterval(() => {
						b.textContent = Swal.getTimerLeft()
					}, 2000)
				},
				willClose: () => {
					clearInterval(timerInterval)
				}
			}).then((result) => {
				if (result.dismiss === Swal.DismissReason.timer) {
					console.log('I was closed by the timer')

				}
			})
		})

	function GetLaporanStockRekapData() {

		if ($.fn.DataTable.isDataTable('#table_detail')) {
			$('#table_detail').DataTable().destroy();
		}
		$('#table_detail').DataTable({
			'serverSide': true,
			searching: false,
			'ajax': {
				'url': "<?= base_url('WMS/LaporanTransaksi/LaporanStokOpname/GetDetail') ?>",
				'type': 'POST',
				'data': {
					principle: $('#filter_stock_principle option:selected').val(),
					depo_detail_id: $('#filter_stock_gudang option:selected').val(),
					sku_kode: $('#filter_stock_sku_kode').val(),
					sku_nama: $('#filter_stock_sku_nama').val(),
					client_wms: $('#filter_stock_pt option:selected').val(),
					filter_stock_tanggal: $('#filter_stock_tanggal').val()
				},
				'dataSrc': function(response) {
					return response.data;
				}
			},
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, 'All']
			],
			dom: "Blfrtip",
			// buttons: [{
			// 	extend: 'excel',
			// 	className: "btn-sm",
			// 	messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
			// }],
			autoWidth: 'true',
			// dom: 'Bfrtip',

			'ordering': 'false',
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
			'columns': [{
					data: 'depo_nama',
					sortable: false
				},
				{
					data: 'tahun',
					sortable: false
				},
				{
					data: 'bulan',
					sortable: false
				},
				{
					data: 'tanggal',
					sortable: false
				},
				{
					data: 'client_wms_nama',
					sortable: false
				},
				{
					data: 'principle_nama',
					sortable: false
				},
				{
					data: 'tr_opname_plan_status',
					sortable: false
				},
				{
					data: 'tipe_stok',
					sortable: false
				},
				{
					data: 'tipe_opname_nama',
					sortable: false
				},
				{
					data: 'karyawan_nama',
					sortable: false
				},
				{
					data: 'tr_opname_plan_kode',
					sortable: false
				},
				{
					data: 'depo_detail_nama',
					sortable: false
				},
				{
					data: 'nama_detail_rak',
					sortable: false
				},
				{
					data: 'pallet_kode',
					sortable: false
				},
				{
					data: 'sku_kode',
					sortable: false
				},
				{
					data: 'sku_nama_produk',
					sortable: false
				},
				{
					data: 'sku_kode_sku_principle',
					sortable: false
				},
				{
					data: 'sku_kemasan',
					sortable: false
				},
				{
					data: 'sku_satuan',
					sortable: false
				},
				{
					data: 'sku_batch_no',
					sortable: false
				},
				{
					data: 'sku_expired_date',
					sortable: false
				},
				{
					data: 'sku_actual_qty_opname',
					sortable: false
				},
				{
					data: 'tr_opname_plan_detail2_status',
					sortable: false
				},
			]
		});
		// $('#table_detail > tbody').empty();
		// $.ajax({
		// 	type: 'POST',
		// 	url: "<?= base_url('WMS/LaporanTransaksi/LaporanStokOpname/GetDetail') ?>",
		// 	data: {
		// 		principle: $('#filter_stock_principle option:selected').val(),
		// 		depo_detail_id: $('#filter_stock_gudang option:selected').val(),
		// 		sku_kode: $('#filter_stock_sku_kode option:selected').val(),
		// 		sku_nama: $('#filter_stock_sku_nama option:selected').val(),
		// 		client_wms: $('#filter_stock_pt option:selected').val(),
		// 		filter_stock_tanggal: $('#filter_stock_tanggal').val(),
		// 	},
		// 	dataType: "JSON",
		// 	async: false,
		// 	success: function(response) {
		// 		if (response.length > 0) {
		// 			response.forEach(function(e, i) {
		// 				$("#table_detail > tbody").append(`
		// 						<tr>
		// 							<td>${e.depo_nama}</td>
		// 							<td>${e.tahun}</td>
		// 							<td>${e.bulan}</td>
		// 							<td>${e.tanggal}</td>
		// 							<td>${e.client_wms_nama}</td>
		// 							<td>${e.principle_nama}</td>
		// 							<td>${e.tr_opname_plan_status}</td>
		// 							<td>${e.tipe_stok}</td>
		// 							<td>${e.tipe_opname_nama}</td>
		// 							<td>${e.karyawan_nama}</td>
		// 							<td>${e.tr_opname_plan_kode}</td>
		// 							<td>${e.depo_detail_nama}</td>
		// 							<td>${e.nama_detail_rak}</td>
		// 							<td>${e.pallet_kode}</td>
		// 							<td>${e.sku_kode}</td>
		// 							<td>${e.sku_nama_produk}</td>
		// 							<td>${e.sku_kode_sku_principle}</td>
		// 							<td>${e.sku_kemasan}</td>
		// 							<td>${e.sku_satuan}</td>
		// 							<td>${e.sku_batch_no}</td>
		// 							<td>${e.sku_expired_date}</td>
		// 							<td>${e.sku_actual_qty_opname}</td>
		// 							<td>${e.tr_opname_plan_detail2_status}</td>
		// 						</tr>
		// 						`);
		// 			})

		// 		} else {
		// 			message_topright("error", "Data Kosong")
		// 		}
		// 		$('#table_detail').DataTable({
		// 			lengthMenu: [
		// 				[10, 25, 50, 100, -1],
		// 				[10, 25, 50, 100, 'All']
		// 			],
		// 			dom: "Blfrtip",
		// 			// buttons: [{
		// 			// 	extend: 'excel',
		// 			// 	className: "btn-sm",
		// 			// 	messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
		// 			// }],
		// 			autoWidth: 'true',
		// 			// dom: 'Bfrtip',
		// 			buttons: [{
		// 					extend: "print",
		// 					text: '<i class="fa fa-print"></i> Print',
		// 					className: 'btn btn-warning',
		// 					customize: function(win) {

		// 						var last = null;
		// 						var current = null;
		// 						var bod = [];

		// 						var css = '@page { size: landscape; }',
		// 							head = win.document.head || win.document.getElementsByTagName('head')[0],
		// 							style = win.document.createElement('style');

		// 						style.type = 'text/css';
		// 						style.media = 'print';

		// 						if (style.styleSheet) {
		// 							style.styleSheet.cssText = css;
		// 						} else {
		// 							style.appendChild(win.document.createTextNode(css));
		// 						}

		// 						head.appendChild(style);
		// 					}
		// 				},
		// 				{
		// 					extend: 'excelHtml5',
		// 					text: '<i class="fa fa-file-excel-o"></i> Excel',
		// 					className: 'btn btn-success',
		// 				},
		// 				{
		// 					extend: 'csvHtml5',
		// 					text: '<i class="fa fa-file"></i> CSV',
		// 					className: 'btn btn-info',
		// 				},
		// 				{
		// 					extend: 'pdfHtml5',
		// 					orientation: 'landscape',
		// 					text: '<i class="fa fa-file-pdf-o"></i> PDF',
		// 					className: 'btn btn-danger',
		// 					pageSize: 'A4'
		// 				}
		// 			]
		// 		});

		// 	}
		// });

	}
</script>