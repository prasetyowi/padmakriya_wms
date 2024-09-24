<script>
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
			if ($('#filter_stock_tanggal1').length > 0) {
				$('#filter_stock_tanggal1').daterangepicker({
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
			$('#table_laporan_lead_time_do_detail_so').DataTable({
				responsive: true
			});
			// GetLaporanStockRekapData();
		}
	);

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

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/OperationalExcellence/AnalisisLeadTimeDokumen/Get_laporan_lead_time_do') ?>",
			dataType: "JSON",
			data: {
				filter_stock_tanggal: $('#filter_stock_tanggal').val(),
				principle_id: $('#filter_stock_principle option:selected').val()
			},
			beforeSend: function() {

				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});

				$("#btncariAll").prop("disabled", true);
				$("#loadingviewdodraft").show();
			},
			success: function(response) {

				if (response.length > 0) {
					$('#table_laporan_lead_time_do').fadeOut("slow", function() {
						$(this).hide();

						$("#table_laporan_lead_time_do > tbody").html('');
						$("#table_laporan_lead_time_do > tbody").empty('');

					}).fadeIn("slow", function() {
						$(this).show();

						// console.log(response.CanvasInDO);
						$.each(response, function(i, v) {
							$("#table_laporan_lead_time_do > tbody").append(`
								<tr>
									<td class="text-left">${v.tipe}</td>
									<td class="text-left">${v.keterangan}</td>
									<td class="text-right">
										<button class="btn btn-link" onclick="GetDetailLaporan('${v.tipe}','jml_0_sd_3')">${v.jml_0_sd_3}</button>
									</td>
									<td class="text-right">
										<button class="btn btn-link" onclick="GetDetailLaporan('${v.tipe}','jml_4_sd_7')">${v.jml_4_sd_7}</button>
									</td>
									<td class="text-right">
										<button class="btn btn-link" onclick="GetDetailLaporan('${v.tipe}','jml_8_sd_14')">${v.jml_8_sd_14}</button>
									</td>
									<td class="text-right">
										<button class="btn btn-link" onclick="GetDetailLaporan('${v.tipe}','jml_15_sd_28')">${v.jml_15_sd_28}</button>
									</td>
									<td class="text-right">
										<button class="btn btn-link" onclick="GetDetailLaporan('${v.tipe}','jml_29')">${v.jml_29}</button>
									</td>
								</tr>
							`).fadeIn("slow");
						});
					});

				}

				$("#btncariAll").prop("disabled", false);
				$("#loadingviewdodraft").hide();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");
				$("#btncariAll").prop("disabled", false);
				$("#loadingviewdodraft").hide();
			},
			complete: function() {
				Swal.close();

				$("#btnapprovdodraft").prop("disabled", false);
				$("#btncariAll").prop("disabled", false);
				$("#loadingviewdodraft").hide();
			}
		});

	}

	function GetDetailLaporan(tipe, range) {
		if (tipe == "SO Approved") {
			$("#modal_detail_so").modal('show');
			$("#modal_detail_do").modal('hide');

			if ($.fn.DataTable.isDataTable('#table_laporan_lead_time_do_detail_so')) {
				$('#table_laporan_lead_time_do_detail_so').DataTable().destroy();
			}

			$('#table_laporan_lead_time_do_detail_so').DataTable({
				scrollX: true,
				'bInfo': true,
				'pageLength': 10,
				'serverSide': true,
				'searching': false,
				'serverMethod': 'post',
				'processing': true,
				'ajax': {
					url: "<?= base_url('WMS/OperationalExcellence/AnalisisLeadTimeDokumen/Get_laporan_lead_time_do_detail_so_approved') ?>",
					data: {
						filter_stock_tanggal: $('#filter_stock_tanggal').val(),
						principle_id: $('#filter_stock_principle option:selected').val(),
						tipe: tipe,
						range: range
					}

				},
				// dom: "Blfrtip",
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
				// 'fixedHeader': false,
				// 'scrollY': 500,
				// 'scrollX': true,
				// "pagingType": "full_numbers",
				"lengthMenu": [
					[5, 10, 25, 50, 100],
					[5, 10, 25, 50, 100]
				],
				'columns': [{
						data: null,
						render: function(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						},
						orderable: false
					},
					{
						data: 'sales_order_tgl'
					},
					{
						data: 'sales_order_tgl_kirim'
					},
					{
						data: 'sales_order_kode'
					},
					{
						data: 'sales_order_no_po'
					},
					{
						data: 'kode_sales'
					},
					{
						data: 'karyawan_nama'
					},
					{
						data: 'kode_customer_eksternal'
					},
					{
						data: 'principle_kode'
					},
					{
						data: 'client_wms_nama'
					},
					{
						data: 'client_pt_nama'
					},
					{
						data: 'client_pt_alamat'
					},
					{
						data: 'tipe_sales_order_nama'
					},
					{
						data: 'sales_order_status'
					},
					{
						render: function(data, type, row) {
							return Math.round(row.sku_harga_nett);
						},
						data: null,
						targets: 14
					},
					{
						data: 'sales_order_keterangan'
					},
					{
						data: 'is_priority'
					}
				],
			});

		} else if (tipe == "SO Approved Belum Punya DO Draft") {
			$("#modal_detail_so").modal('show');
			$("#modal_detail_do").modal('hide');

			if ($.fn.DataTable.isDataTable('#table_laporan_lead_time_do_detail_so')) {
				$('#table_laporan_lead_time_do_detail_so').DataTable().destroy();
			}


			$('#table_laporan_lead_time_do_detail_so').DataTable({
				scrollX: true,
				'bInfo': true,
				'pageLength': 10,
				'serverSide': true,
				'searching': false,
				'serverMethod': 'post',
				'processing': true,
				'ajax': {
					url: "<?= base_url('WMS/OperationalExcellence/AnalisisLeadTimeDokumen/Get_laporan_lead_time_do_detail_so_approved_belum_punya_do_draft') ?>",
					data: {
						filter_stock_tanggal: $('#filter_stock_tanggal').val(),
						principle_id: $('#filter_stock_principle option:selected').val(),
						tipe: tipe,
						range: range
					}

				},
				// dom: "Blfrtip",
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
				// 'fixedHeader': false,
				// 'scrollY': 500,
				// 'scrollX': true,
				// "pagingType": "full_numbers",
				"lengthMenu": [
					[5, 10, 25, 50, 100],
					[5, 10, 25, 50, 100]
				],
				'columns': [{
						data: null,
						render: function(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						},
						orderable: false
					},
					{
						data: 'sales_order_tgl'
					},
					{
						data: 'sales_order_tgl_kirim'
					},
					{
						data: 'sales_order_kode'
					},
					{
						data: 'sales_order_no_po'
					},
					{
						data: 'kode_sales'
					},
					{
						data: 'karyawan_nama'
					},
					{
						data: 'kode_customer_eksternal'
					},
					{
						data: 'principle_kode'
					},
					{
						data: 'client_wms_nama'
					},
					{
						data: 'client_pt_nama'
					},
					{
						data: 'client_pt_alamat'
					},
					{
						data: 'tipe_sales_order_nama'
					},
					{
						data: 'sales_order_status'
					},
					{
						render: function(data, type, row) {
							return Math.round(row.sku_harga_nett);
						},
						data: null,
						targets: 14
					},
					{
						data: 'sales_order_keterangan'
					},
					{
						data: 'is_priority'
					}
				],
			});

		} else if (tipe == "SO Approved jadi DO Draft") {
			$("#modal_detail_so").modal('hide');
			$("#modal_detail_do").modal('show');

			if ($.fn.DataTable.isDataTable('#table_laporan_lead_time_do_detail_do')) {
				$('#table_laporan_lead_time_do_detail_do').DataTable().destroy();
			}

			$('#table_laporan_lead_time_do_detail_do').DataTable({
				scrollX: true,
				'bInfo': true,
				'pageLength': 10,
				'serverSide': true,
				searching: false,
				'serverMethod': 'post',
				'ajax': {
					url: "<?= base_url('WMS/OperationalExcellence/AnalisisLeadTimeDokumen/Get_laporan_lead_time_do_detail_so_approved_jadi_do_draft') ?>",
					data: {
						filter_stock_tanggal: $('#filter_stock_tanggal').val(),
						principle_id: $('#filter_stock_principle option:selected').val(),
						tipe: tipe,
						range: range
					}

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
					[5, 10, 25, 50, 100],
					[5, 10, 25, 50, 100]
				],
				'columns': [{
						data: null,
						render: function(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						},
						orderable: false
					},
					{
						data: 'delivery_order_draft_tgl_rencana_kirim'
					},
					{
						data: 'delivery_order_draft_kode'
					},
					{
						data: 'sales_order_kode'
					},
					{
						data: 'sales_order_no_po'
					},
					{
						data: 'principle_kode'
					},
					{
						data: 'karyawan_nama'
					},
					{
						data: 'delivery_order_draft_kirim_nama'
					},
					{
						data: 'delivery_order_draft_kirim_alamat'
					},
					{
						data: 'delivery_order_draft_kirim_area'
					},
					{
						data: 'tipe_delivery_order_alias'
					},
					{
						data: 'umur_so'
					},
					{
						render: function(v) {
							return Math.round(v.delivery_order_draft_nominal_tunai);
						},
						data: null,
						targets: 9
					},
					{
						data: 'delivery_order_draft_status'
					},
					{
						data: 'client_pt_segmen_nama1'
					},
					{
						data: 'client_pt_segmen_nama2'
					},
					{
						data: 'client_pt_segmen_nama3'
					},
					{
						data: 'delivery_order_draft_is_prioritas'
					},
				]
			});

		} else if (tipe == "DO Draft diapproved") {
			$("#modal_detail_so").modal('hide');
			$("#modal_detail_do").modal('show');

			if ($.fn.DataTable.isDataTable('#table_laporan_lead_time_do_detail_do')) {
				$('#table_laporan_lead_time_do_detail_do').DataTable().destroy();
			}

			$('#table_laporan_lead_time_do_detail_do').DataTable({
				scrollX: true,
				'bInfo': true,
				'pageLength': 10,
				'serverSide': true,
				searching: false,
				'serverMethod': 'post',
				'ajax': {
					url: "<?= base_url('WMS/OperationalExcellence/AnalisisLeadTimeDokumen/Get_laporan_lead_time_do_detail_do_draft_approved') ?>",
					data: {
						filter_stock_tanggal: $('#filter_stock_tanggal').val(),
						principle_id: $('#filter_stock_principle option:selected').val(),
						tipe: tipe,
						range: range
					}

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
					[5, 10, 25, 50, 100],
					[5, 10, 25, 50, 100]
				],
				'columns': [{
						data: null,
						render: function(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						},
						orderable: false
					},
					{
						data: 'delivery_order_draft_tgl_rencana_kirim'
					},
					{
						data: 'delivery_order_draft_kode'
					},
					{
						data: 'sales_order_kode'
					},
					{
						data: 'sales_order_no_po'
					},
					{
						data: 'principle_kode'
					},
					{
						data: 'karyawan_nama'
					},
					{
						data: 'delivery_order_draft_kirim_nama'
					},
					{
						data: 'delivery_order_draft_kirim_alamat'
					},
					{
						data: 'delivery_order_draft_kirim_area'
					},
					{
						data: 'tipe_delivery_order_alias'
					},
					{
						data: 'umur_so'
					},
					{
						render: function(v) {
							return Math.round(v.delivery_order_draft_nominal_tunai);
						},
						data: null,
						targets: 9
					},
					{
						data: 'delivery_order_draft_status'
					},
					{
						data: 'client_pt_segmen_nama1'
					},
					{
						data: 'client_pt_segmen_nama2'
					},
					{
						data: 'client_pt_segmen_nama3'
					},
					{
						data: 'delivery_order_draft_is_prioritas'
					},
				]
			});

		} else if (tipe == "DO Dilaksanakan") {
			$("#modal_detail_so").modal('hide');
			$("#modal_detail_do").modal('show');

			if ($.fn.DataTable.isDataTable('#table_laporan_lead_time_do_detail_do')) {
				$('#table_laporan_lead_time_do_detail_do').DataTable().destroy();
			}

			$('#table_laporan_lead_time_do_detail_do').DataTable({
				scrollX: true,
				'bInfo': true,
				'pageLength': 10,
				'serverSide': true,
				searching: false,
				'serverMethod': 'post',
				'ajax': {
					url: "<?= base_url('WMS/OperationalExcellence/AnalisisLeadTimeDokumen/Get_laporan_lead_time_do_detail_do_dilaksanakan') ?>",
					data: {
						filter_stock_tanggal: $('#filter_stock_tanggal').val(),
						principle_id: $('#filter_stock_principle option:selected').val(),
						tipe: tipe,
						range: range
					}

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
					[5, 10, 25, 50, 100],
					[5, 10, 25, 50, 100]
				],
				'columns': [{
						data: null,
						render: function(data, type, row, meta) {
							return meta.row + meta.settings._iDisplayStart + 1;
						},
						orderable: false
					},
					{
						data: 'delivery_order_draft_tgl_rencana_kirim'
					},
					{
						data: 'delivery_order_draft_kode'
					},
					{
						data: 'sales_order_kode'
					},
					{
						data: 'sales_order_no_po'
					},
					{
						data: 'principle_kode'
					},
					{
						data: 'karyawan_nama'
					},
					{
						data: 'delivery_order_draft_kirim_nama'
					},
					{
						data: 'delivery_order_draft_kirim_alamat'
					},
					{
						data: 'delivery_order_draft_kirim_area'
					},
					{
						data: 'tipe_delivery_order_alias'
					},
					{
						data: 'umur_so'
					},
					{
						render: function(v) {
							return Math.round(v.delivery_order_draft_nominal_tunai);
						},
						data: null,
						targets: 9
					},
					{
						data: 'delivery_order_draft_status'
					},
					{
						data: 'client_pt_segmen_nama1'
					},
					{
						data: 'client_pt_segmen_nama2'
					},
					{
						data: 'client_pt_segmen_nama3'
					},
					{
						data: 'delivery_order_draft_is_prioritas'
					},
				]
			});

		}

	}

	const handlerFilterData2 = (event) => {


		if ($.fn.DataTable.isDataTable('#table_laporan_lead_time_do_detail_so')) {
			$('#table_laporan_lead_time_do_detail_so').DataTable().destroy();
		}

		$('#table_laporan_lead_time_do_detail_so').DataTable({
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
				}

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
				[5, 10, 25, 50, 100],
				[5, 10, 25, 50, 100]
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
				$('#table_laporan_lead_time_do_detail_so_wrapper .btn-group').addClass('pull-right').css('margin-bottom', '10px');
			},
		});

	}

	function upModalDetail(sku_id, sku_nama_produk, sku_kode, tipe, depo_detail_id, tipe_report, sku_stock_expired_date, stock_awal, stock_akhir) {
		$(`.inputTitle`).html(`<b>${sku_kode}</b> - <b>${sku_nama_produk}</b>`);

		$('#table_stock_detail').DataTable().destroy();
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
					stockAwal: stockAwal
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