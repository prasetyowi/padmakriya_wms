<script type="text/javascript">
	var index_pallet = 0;

	$(document).ready(
		function() {
			$(".select2").select2();
			$("#page2").hide();
			$('#tablelaporanstockskupage2').DataTable({
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

	document.getElementById('filter_sku').addEventListener('keyup', function() {

		if (this.value != "") {
			fetch('<?= base_url('WMS/Laporan/LaporanStockSKU/getDataAutoComplete?params='); ?>' + this.value)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						document.getElementById('table-fixed').style.display = 'none';
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							let txt = e.sku_nama_produk;
							let txtreplace = txt.replace('"', '')
							data += `
								<tr onclick="GetDetailBySKU('${e.sku_id}','${txtreplace}')" style="cursor:pointer">
										<td class="col-xs-1">${i + 1}.</td>
										<td class="col-xs-5">${e.sku_kode}</td>
										<td class="col-xs-10">${e.sku_nama_produk}</td>
								</tr>
								`;
						})

						document.getElementById('konten-table').innerHTML = data;
						// console.log(data);
						document.getElementById('table-fixed').style.display = 'block';
					}
				});
		} else {
			document.getElementById('table-fixed').style.display = 'none';
		}
	});

	function GetDetailBySKU(id, nama) {
		$("#filter_sku").val(nama);
		document.getElementById('table-fixed').style.display = 'none'
		AppendToTable(id, nama)

	}

	function AppendToTable(id, nama) {
		$('#tablelaporanstocksku > tbody').empty();
		fetch('<?= base_url('WMS/Laporan/LaporanStockSKU/getDataDetail?id='); ?>' + id)
			.then(response => response.json())
			.then((results) => {
				if (!results[0]) {
					message_topright("error", "Data Kosong")
				} else {

					let data = "";
					results.forEach(function(e, i) {
						data += `
								<tr>
										<td class="text-center">${i + 1}.</td>
										<td>${e.rak_lajur_detail_nama}</td>
										<td>${e.pallet_kode}</td>
										<td>${e.sku_stock_expired_date}</td>
										<td>${e.batch_no==''?'-':e.batch_no}</td>
										<td>${e.qtyHasil}</td>
								</tr>
								`;
					})

					document.getElementById('konten-tablelaporanstocksku').innerHTML = data;
				}
			})
	}

	// add new

	const chkradio = (event) => {

		if (event.currentTarget.value == "0") {
			$("#page1").show('slow')
			$("#page2").hide('slow')

		} else {
			$("#page1").hide('slow');
			$("#page2").show('slow');

		}
	}
	$("#btncariAll").click(
		function() {
			GetLaporanStockRekapData()
		})

	function GetLaporanStockRekapData() {

		if ($.fn.DataTable.isDataTable('#tablelaporanstockskupage2')) {
			$('#tablelaporanstockskupage2').DataTable().destroy();
		}
		$('#tablelaporanstockskupage2 > tbody').empty();
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Laporan/LaporanStockSKU/GetDetailInformasiAll') ?>",
			data: {
				principle: $('#filter_stock_principle option:selected').val(),
				kode_pallet: $('#filter_pallet option:selected').val(),
				rak: $('#filter_rak option:selected').val()
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				if (response.length > 0) {
					response.forEach(function(e, i) {
						$("#tablelaporanstockskupage2 > tbody").append(`
								<tr>
										<td class="text-center">${i + 1}.</td>
										<td>${e.principle_nama}</td>
										<td>${e.pallet_kode}</td>
										<td>${e.rak_lajur_detail_nama}</td>
										<td>${e.sku_kode}</td>
										<td>${e.sku_nama_produk}</td>
										<td>${e.sku_stock_expired_date}</td>
										<td>${e.batch_no==''?'-':e.batch_no}</td>
										<td>${e.qtyHasil}</td>
								</tr>
								`);
					})

				} else {
					message_topright("error", "Data Kosong")
				}
				$('#tablelaporanstockskupage2').DataTable({
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