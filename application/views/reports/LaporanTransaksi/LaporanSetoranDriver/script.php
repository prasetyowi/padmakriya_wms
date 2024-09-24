<script type="text/javascript">
	var index_pallet = 0;

	$(document).ready(
		function() {
			if ($('#filter_tanggal_kirim').length > 0) {
				$('#filter_tanggal_kirim').daterangepicker({
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
			// $('#table_detail').DataTable({
			// 	responsive: true,
			// 	searching: false,
			// 	ordering: false,
			// });
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

		// // $('#table_detail > tbody').empty();
		// $('#table_detail').DataTable({
		// 	'serverSide': true,
		// 	'ajax': {
		// 		'url': "<?= base_url('WMS/LaporanTransaksi/LaporanSetoranDriver/GetDetail') ?>",
		// 		'type': 'POST',
		// 		'data': {
		// 			principle: $('#filter_principle option:selected').val(),
		// 			client_wms: $('#filter_perusahaan option:selected').val(),
		// 			filter_tanggal_kirim: $('#filter_tanggal_kirim').val(),
		// 			driver: $('#filter_driver').val()
		// 		},
		// 		'dataSrc': function(response) {
		// 			return response.data;
		// 		}
		// 	},
		// 	lengthMenu: [
		// 		[10, 25, 50, 100, -1],
		// 		[10, 25, 50, 100, 'All']
		// 	],
		// 	searching: false,
		// 	dom: "Blfrtip",
		// 	// buttons: [{
		// 	// 	extend: 'excel',
		// 	// 	className: "btn-sm",
		// 	// 	messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
		// 	// }],
		// 	autoWidth: 'true',
		// 	// dom: 'Bfrtip',
		// 	buttons: [{
		// 			extend: "print",
		// 			text: '<i class="fa fa-print"></i> Print',
		// 			className: 'btn btn-warning',
		// 			customize: function(win) {

		// 				var last = null;
		// 				var current = null;
		// 				var bod = [];

		// 				var css = '@page { size: landscape; }',
		// 					head = win.document.head || win.document.getElementsByTagName('head')[0],
		// 					style = win.document.createElement('style');

		// 				style.type = 'text/css';
		// 				style.media = 'print';

		// 				if (style.styleSheet) {
		// 					style.styleSheet.cssText = css;
		// 				} else {
		// 					style.appendChild(win.document.createTextNode(css));
		// 				}

		// 				head.appendChild(style);
		// 			}
		// 		},
		// 		{
		// 			extend: 'excelHtml5',
		// 			text: '<i class="fa fa-file-excel-o"></i> Excel',
		// 			className: 'btn btn-success',
		// 		},
		// 		{
		// 			extend: 'csvHtml5',
		// 			text: '<i class="fa fa-file"></i> CSV',
		// 			className: 'btn btn-info',
		// 		},
		// 		{
		// 			extend: 'pdfHtml5',
		// 			orientation: 'landscape',
		// 			text: '<i class="fa fa-file-pdf-o"></i> PDF',
		// 			className: 'btn btn-danger',
		// 			pageSize: 'A4'
		// 		}
		// 	],
		// 	'columns': [
		// 		// {'data': 'id'},
		// 		{
		// 			'data': 'karyawan_nama'
		// 		},
		// 		{
		// 			'data': 'principle_kode'
		// 		},
		// 		{
		// 			'data': 'delivery_order_nominal_tunai'
		// 		},
		// 		{
		// 			'data': 'delivery_order_jumlah_bayar'
		// 		},
		// 		{
		// 			'data': 'sales_order_no'
		// 		},
		// 		{
		// 			'data': 'nama_outlet'
		// 		},
		// 		{
		// 			'data': 'sku_kode'
		// 		},
		// 		{
		// 			'data': 'sku_nama_produk'
		// 		},
		// 		{
		// 			'data': 'sku_qty'
		// 		},
		// 		{
		// 			'data': 'sku_qty_kirim'
		// 		}, {
		// 			'data': 'harga'
		// 		}, {
		// 			'data': 'delivery_order_batch_kode'
		// 		},
		// 		{
		// 			'data': 'karyawan_nama'
		// 		},
		// 		{
		// 			'data': 'kendaraan_nopol'
		// 		},
		// 		{
		// 			'data': 'delivery_order_batch_tanggal_kirim'
		// 		},
		// 		{
		// 			'data': 'delivery_order_status'
		// 		},
		// 		{
		// 			'data': 'reason'
		// 		},
		// 	]
		// });


		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/LaporanTransaksi/LaporanSetoranDriver/GetDetail') ?>",
			data: {
				principle: $('#filter_principle option:selected').val(),
				client_wms: $('#filter_perusahaan option:selected').val(),
				tanggal_kirim: $('#filter_tanggal_kirim').val(),
				driver: $('#filter_driver').val()
			},
			dataType: "JSON",
			async: false,
			success: function(response) {

				$('#table_detail > tbody').html();
				$('#table_detail > tbody').empty();

				// if ($.fn.DataTable.isDataTable('#table_detail')) {
				// 	$('#table_detail').DataTable().clear();
				// 	$('#table_detail').DataTable().destroy();
				// }

				if (response.length > 0) {
					$.each(response, function(i, v) {
						if (v.principle_kode === null && v.karyawan_nama === null) {
							$("#table_detail > tbody").append(`
								<tr style="background:#430A5D;color:white;font-weight: bold;">
									<td class="text-center">Grand Total</td>
									<td class="text-center"></td>
									<td class="text-right">${formatRupiahCurr(parseInt(v.delivery_order_nominal_tunai))}</td>
									<td class="text-right">${formatRupiahCurr(parseInt(v.delivery_order_jumlah_bayar))}</td>
									<td class="text-right">${formatRupiahCurr(parseInt(v.delivery_order_nominal_tunai) - parseInt(v.delivery_order_jumlah_bayar))}</td>
								</tr>
							`);
						} else if (v.principle_kode === null) {
							$("#table_detail > tbody").append(`
								<tr style="background:#5755FE;color:white;font-weight: bold;">
									<td class="text-center"></td>
									<td class="text-center">Total ${v.karyawan_nama}</td>
									<td class="text-right">${formatRupiahCurr(parseInt(v.delivery_order_nominal_tunai))}</td>
									<td class="text-right">${formatRupiahCurr(parseInt(v.delivery_order_jumlah_bayar))}</td>
									<td class="text-right">${formatRupiahCurr(parseInt(v.delivery_order_nominal_tunai) - parseInt(v.delivery_order_jumlah_bayar))}</td>
								</tr>
							`);

						} else {
							$("#table_detail > tbody").append(`
								<tr>
									<td class="text-center">${v.karyawan_nama}</td>
									<td class="text-center">${v.principle_kode}</td>
									<td class="text-right">${formatRupiahCurr(parseInt(v.delivery_order_nominal_tunai))}</td>
									<td class="text-right">${formatRupiahCurr(parseInt(v.delivery_order_jumlah_bayar))}</td>
									<td class="text-right">${formatRupiahCurr(parseInt(v.delivery_order_nominal_tunai) - parseInt(v.delivery_order_jumlah_bayar))}</td>
								</tr>
							`);
						}
					});

					// $('#table_detail').DataTable({
					// 	lengthMenu: [
					// 		[-1],
					// 		['All']
					// 	],
					// 	dom: "Blfrtip",
					// 	// buttons: [{
					// 	// 	extend: 'excel',
					// 	// 	className: "btn-sm",
					// 	// 	messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.'
					// 	// }],
					// 	autoWidth: 'true',
					// 	// dom: 'Bfrtip',
					// 	searching: false,
					// 	ordering: false,
					// 	bInfo: false,
					// 	bPaginate: false,
					// 	// buttons: [{
					// 	// 		extend: "print",
					// 	// 		text: '<i class="fa fa-print"></i> Print',
					// 	// 		className: 'btn btn-warning',
					// 	// 		customize: function(win) {

					// 	// 			var last = null;
					// 	// 			var current = null;
					// 	// 			var bod = [];

					// 	// 			var css = '@page { size: landscape; }',
					// 	// 				head = win.document.head || win.document.getElementsByTagName('head')[0],
					// 	// 				style = win.document.createElement('style');

					// 	// 			style.type = 'text/css';
					// 	// 			style.media = 'print';

					// 	// 			if (style.styleSheet) {
					// 	// 				style.styleSheet.cssText = css;
					// 	// 			} else {
					// 	// 				style.appendChild(win.document.createTextNode(css));
					// 	// 			}

					// 	// 			head.appendChild(style);
					// 	// 		}
					// 	// 	},
					// 	// 	{
					// 	// 		extend: 'excelHtml5',
					// 	// 		text: '<i class="fa fa-file-excel-o"></i> Excel',
					// 	// 		className: 'btn btn-success',
					// 	// 	},
					// 	// 	{
					// 	// 		extend: 'csvHtml5',
					// 	// 		text: '<i class="fa fa-file"></i> CSV',
					// 	// 		className: 'btn btn-info',
					// 	// 	},
					// 	// 	{
					// 	// 		extend: 'pdfHtml5',
					// 	// 		orientation: 'landscape',
					// 	// 		text: '<i class="fa fa-file-pdf-o"></i> PDF',
					// 	// 		className: 'btn btn-danger',
					// 	// 		pageSize: 'A4'
					// 	// 	}
					// 	// ]
					// });

				} else {
					message_topright("error", "Data Kosong")
				}

			}
		});
	}
</script>