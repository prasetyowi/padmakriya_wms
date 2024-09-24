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
				responsive: true,
				searching: false,
				ordering: false,
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
					console.log('succes');
				}
			})

		})

	function GetLaporanStockRekapData() {

		if ($.fn.DataTable.isDataTable('#table_detail')) {
			$('#table_detail').DataTable().destroy();
		}
		// $('#table_detail > tbody').empty();
		$('#table_detail').DataTable({
			'serverSide': true,
			'ajax': {
				'url': "<?= base_url('WMS/LaporanTransaksi/LaporanPenjualan/GetDetail') ?>",
				'type': 'POST',
				'data': {
					principle: $('#filter_stock_principle option:selected').val(),
					depo_detail_id: $('#filter_stock_gudang option:selected').val(),
					sku_kode: $('#filter_stock_sku_kode').val(),
					sku_nama: $('#filter_stock_sku_nama').val(),
					client_wms: $('#filter_stock_pt option:selected').val(),
					filter_stock_tanggal: $('#filter_stock_tanggal').val(),
					driver: $('#filter_driver').val()
				},
				'dataSrc': function(response) {
					return response.data;
				}
			},
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, 'All']
			],
			searching: false,
			// dom: "Blfrtip",
			// buttons: [{
			// 	extend: 'excel',
			// 	className: "btn-sm",
			// 	messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
			// }],
			autoWidth: 'true',
			// dom: 'Bfrtip',
			// buttons: [{
			// 		extend: "print",
			// 		text: '<i class="fa fa-print"></i> Print',
			// 		className: 'btn btn-warning',
			// 		customize: function(win) {

			// 			var last = null;
			// 			var current = null;
			// 			var bod = [];

			// 			var css = '@page { size: landscape; }',
			// 				head = win.document.head || win.document.getElementsByTagName('head')[0],
			// 				style = win.document.createElement('style');

			// 			style.type = 'text/css';
			// 			style.media = 'print';

			// 			if (style.styleSheet) {
			// 				style.styleSheet.cssText = css;
			// 			} else {
			// 				style.appendChild(win.document.createTextNode(css));
			// 			}

			// 			head.appendChild(style);
			// 		}
			// 	},
			// 	{
			// 		extend: 'excelHtml5',
			// 		text: '<i class="fa fa-file-excel-o"></i> Excel',
			// 		className: 'btn btn-success',
			// 	},
			// 	{
			// 		extend: 'csvHtml5',
			// 		text: '<i class="fa fa-file"></i> CSV',
			// 		className: 'btn btn-info',
			// 	},
			// 	{
			// 		extend: 'pdfHtml5',
			// 		orientation: 'landscape',
			// 		text: '<i class="fa fa-file-pdf-o"></i> PDF',
			// 		className: 'btn btn-danger',
			// 		pageSize: 'A4'
			// 	}
			// ],
			'columns': [
				// {'data': 'id'},
				{
					'data': 'depo_nama'
				},
				{
					'data': 'tgl_do'
				},
				{
					'data': 'tgl_terkirim'
				},
				{
					'data': 'delivery_order_kode'
				},
				{
					'data': 'sales_order_no'
				},
				{
					'data': 'nama_outlet'
				},
				{
					'data': 'sku_kode'
				},
				{
					'data': 'sku_nama_produk'
				},
				{
					'data': 'sku_qty'
				},
				{
					'data': 'sku_qty_kirim'
				}, {
					'data': 'harga'
				}, {
					'data': 'delivery_order_batch_kode'
				},
				{
					'data': 'karyawan_nama'
				},
				{
					'data': 'kendaraan_nopol'
				},
				{
					'data': 'delivery_order_batch_tanggal_kirim'
				},
				{
					'data': 'delivery_order_status'
				},
				{
					'data': 'reason'
				},
			]
		});

		/*
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/LaporanTransaksi/LaporanPenjualan/GetDetail') ?>",
			data: {
				principle: $('#filter_stock_principle option:selected').val(),
				depo_detail_id: $('#filter_stock_gudang option:selected').val(),
				sku_kode: $('#filter_stock_sku_kode option:selected').val(),
				sku_nama: $('#filter_stock_sku_nama option:selected').val(),
				client_wms: $('#filter_stock_pt option:selected').val(),
				filter_stock_tanggal: $('#filter_stock_tanggal').val(),
			},
			dataType: "JSON",
			async: false,
			success: function(response) {

				if (response.length > 0) {
					response.forEach(function(e, i) {
						$("#table_detail > tbody").append(`
								<tr>
									<td>${e.depo_nama}</td>
									<td>${e.tgl_do}</td>
									<td>${e.tgl_terkirim}</td>
									<td>${e.delivery_order_kode}</td>
									<td>${e.sales_order_no}</td>
									<td>${e.nama_outlet}</td>
									<td>${e.sku_kode}</td>
									<td>${e.sku_nama_produk}</td>
									<td>${e.sku_qty}</td>
									<td>${e.sku_qty_kirim}</td>
									<td>${Math.round(e.harga)}</td>
									<td>${e.delivery_order_batch_kode}</td>
									<td>${e.karyawan_nama}</td>
									<td>${e.kendaraan_nopol}</td>
									<td>${e.delivery_order_batch_tanggal_kirim}</td>
									<td>${e.delivery_order_status}</td>
									<td>${e.reason}</td>
								</tr>
								`);
					})

				} else {
					message_topright("error", "Data Kosong")
				}
				$('#table_detail').DataTable({
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
					]
				});

			}
		});

		*/
	}

	function exportExcel() {
		var principle = $('#filter_stock_principle option:selected').val()
		var depo_detail_id = $('#filter_stock_gudang option:selected').val()
		var sku_kode = $('#filter_stock_sku_kode').val()
		var sku_nama = $('#filter_stock_sku_nama').val()
		var client_wms = $('#filter_stock_pt option:selected').val()
		var filter_stock_tanggal = $('#filter_stock_tanggal').val()
		var driver = $('#filter_driver').val()

		location.href = "<?= base_url() ?>WMS/LaporanTransaksi/LaporanPenjualan/exportExcel/?principle=" + principle + "&filter_stock_tanggal=" + filter_stock_tanggal + "&sku_kode=" + sku_kode + "&sku_nama=" + sku_nama + "&client_wms=" + client_wms + "&driver=" + driver + "";

	}
</script>