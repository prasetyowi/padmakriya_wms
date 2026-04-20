<script type="text/javascript">
	var index_pallet = 0;

	$(document).ready(
		function() {

			// let arrData = [];

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
			MergeCommonRows($('#table_detail'));
			// GetLaporanStockRekapData();
		}
	);

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
		$('#table_detail > tbody').empty();

		var table = $('#table_detail');
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/LaporanTransaksi/LaporanRetur/GetDetail') ?>",
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
				console.log(response);
				response.forEach(function(e, i) {
					console.log("awwwww", e);
				})
				return false;
				if (response.length > 0) {
					response.forEach(function(e, i) {
						console.log(e);
						$("#table_detail > tbody").append(`
								<tr>
									<td class="td_l1">${e.penerimaan_pembelian_id}</td>
									<td class="td_l1">${e.karyawan_nama}</td>
									<td class="td_l1">${e.penerimaan_pembelian_kode}</td>
									<td class="td_l1">${e.sku_nama}</td>
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

	}
</script>