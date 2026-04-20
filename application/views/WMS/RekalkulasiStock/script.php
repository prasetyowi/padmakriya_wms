<script type="text/javascript">
	var jumlah_sku = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_header = [];
	let arr_detail = [];
	let arr_checkbox_sku = [];
	var cek_qty = 0;
	let cek_tipe_stock = 0;
	var arr_do_draft = [];
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2({
				width: '100%'
			});

			if ($('#filter_tgl_rekalkulasi').length > 0) {
				$('#filter_tgl_rekalkulasi').daterangepicker({
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
		}
	);

	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		})
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

	$("#btn_search_rekalkulasi").on("click", function() {
		GetRekalkulasiByFilter();
	});

	$("#btn_proses_rekalkulasi").on("click", function() {
		GetProsesRekalkulasiByFilter();
	});

	function GetRekalkulasiByFilter() {
		var idx = 0;

		const load_start = () => {
			Swal.fire({
				title: 'Loading ...',
				html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
				timerProgressBar: false,
				showConfirmButton: false
			});
		};

		const proses = () => {

			$('#table_list_data_rekalkulasi').DataTable().clear();
			$('#table_list_data_rekalkulasi').DataTable().destroy();

			$('#table_list_data_rekalkulasi').DataTable({
				"scrollX": true,
				'paging': true,
				'searching': false,
				'ordering': false,
				'processing': true,
				'serverSide': true,
				"language": {
					"processing": "<span class='fa-stack'>\n\
                            <i class='fa fa-spinner fa-spin fa-stack-2x text-dark'></i>\n\
                       </span>&emsp; Loading ...",
				},
				'ajax': {
					url: "<?= base_url('WMS/RekalkulasiStock/GetRekalkulasiByFilter') ?>",
					type: "POST",
					dataType: "json",
					data: {
						tgl: $("#filter_tgl_rekalkulasi").val(),
						perusahaan: $("#filter_perusahaan").val(),
						gudang: $("#filter_gudang").val(),
						principle: $("#filter_principle").val(),
						sku_kode: $("#filter_sku_kode").val(),
						sku_nama_produk: $("#filter_sku_nama_produk").val(),
						status: $("#filter_status").val()
					}
				},
				"columnDefs": [{
						"targets": 0,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 1,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 2,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 3,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 4,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 5,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 6,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 7,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 8,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 9,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 10,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 11,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 12,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 13,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 14,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 15,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 16,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 17,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 18,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					}
				],
				'columns': [{
						data: 'idx'
					},
					{
						data: 'sku_stock_tgl'
					},
					{
						data: 'client_wms_nama'
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
						data: 'sku_satuan'
					},
					{
						data: 'sku_stock_expired_date'
					},
					{
						data: 'sku_stock_batch_no'
					},
					{
						data: 'sku_stock_awal'
					},
					{
						data: 'sku_stock_masuk'
					},
					{
						data: 'sku_stock_keluar'
					},
					{
						data: 'sku_stock_akhir'
					},
					{
						data: 'sku_stock_awal2'
					},
					{
						data: 'sku_stock_masuk2'
					},
					{
						data: 'sku_stock_keluar2'
					},
					{
						data: 'sku_stock_akhir2'
					},
					{
						data: 'statusrecal'
					}
				]
			});

			var jml_data_rekalkulasi = $('#table_list_data_rekalkulasi').DataTable().page.info().recordsTotal;
			$("#jml_rekalkulasi").val(jml_data_rekalkulasi);

		};

		const load_end = () => {
			Swal.close();
		};

		// load_start();
		// setInterval(proses(), 1000);
		// load_end();

		proses();

	}

	function GetProsesRekalkulasiByFilter() {
		var idx = 0;

		const load_start = () => {
			Swal.fire({
				title: 'Loading ...',
				html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
				timerProgressBar: false,
				showConfirmButton: false
			});
		};

		const proses = () => {

			$('#table_list_data_rekalkulasi').DataTable().clear();
			$('#table_list_data_rekalkulasi').DataTable().destroy();

			$('#table_list_data_rekalkulasi').DataTable({
				"scrollX": true,
				'paging': true,
				'searching': false,
				'ordering': false,
				'processing': true,
				'serverSide': true,
				"language": {
					"processing": "<span class='fa-stack'>\n\
                            <i class='fa fa-spinner fa-spin fa-stack-2x text-dark'></i>\n\
                       </span>&emsp; Loading ...",
				},
				'ajax': {
					url: "<?= base_url('WMS/RekalkulasiStock/GetProsesRekalkulasiByFilter') ?>",
					type: "POST",
					dataType: "json",
					data: {
						tgl: $("#filter_tgl_rekalkulasi").val(),
						perusahaan: $("#filter_perusahaan").val(),
						gudang: $("#filter_gudang").val(),
						principle: $("#filter_principle").val(),
						sku_kode: $("#filter_sku_kode").val(),
						sku_nama_produk: $("#filter_sku_nama_produk").val(),
						status: $("#filter_status").val()
					}
				},
				"columnDefs": [{
						"targets": 0,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 1,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 2,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 3,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 4,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 5,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 6,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 7,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 8,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 9,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 10,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 11,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 12,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 13,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 14,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 15,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 16,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 17,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 18,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					}
				],
				'columns': [{
						data: 'idx'
					},
					{
						data: 'sku_stock_tgl'
					},
					{
						data: 'client_wms_nama'
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
						data: 'sku_satuan'
					},
					{
						data: 'sku_stock_expired_date'
					},
					{
						data: 'sku_stock_batch_no'
					},
					{
						data: 'sku_stock_awal'
					},
					{
						data: 'sku_stock_masuk'
					},
					{
						data: 'sku_stock_keluar'
					},
					{
						data: 'sku_stock_akhir'
					},
					{
						data: 'sku_stock_awal2'
					},
					{
						data: 'sku_stock_masuk2'
					},
					{
						data: 'sku_stock_keluar2'
					},
					{
						data: 'sku_stock_akhir2'
					},
					{
						data: 'statusrecal'
					}
				]
			});

			var jml_data_rekalkulasi = $('#table_list_data_rekalkulasi').DataTable().page.info().recordsTotal;
			$("#jml_rekalkulasi").val(jml_data_rekalkulasi);

		};

		const load_end = () => {
			Swal.close();
		};

		// load_start();
		// setInterval(proses(), 1000);
		// load_end();

		proses()

	}

	function SimpanRekalkulasi() {
		var idx = 0;

		const load_start = () => {
			Swal.fire({
				title: 'Loading ...',
				html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
				timerProgressBar: false,
				showConfirmButton: false
			});
		};

		const proses = () => {

			$('#table_list_data_rekalkulasi').DataTable().clear();
			$('#table_list_data_rekalkulasi').DataTable().destroy();

			$('#table_list_data_rekalkulasi').DataTable({
				"scrollX": true,
				'paging': true,
				'searching': false,
				'ordering': false,
				'processing': true,
				'serverSide': true,
				"language": {
					"processing": "<span class='fa-stack'>\n\
                            <i class='fa fa-spinner fa-spin fa-stack-2x text-dark'></i>\n\
                       </span>&emsp; Loading ...",
				},
				'ajax': {
					url: "<?= base_url('WMS/RekalkulasiStock/SimpanRekalkulasi') ?>",
					type: "POST",
					dataType: "json",
					data: {
						tgl: $("#filter_tgl_rekalkulasi").val(),
						perusahaan: $("#filter_perusahaan").val(),
						gudang: $("#filter_gudang").val(),
						principle: $("#filter_principle").val(),
						sku_kode: $("#filter_sku_kode").val(),
						sku_nama_produk: $("#filter_sku_nama_produk").val(),
						status: $("#filter_status").val()
					}
				},
				"columnDefs": [{
						"targets": 0,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 1,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 2,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 3,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 4,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 5,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 6,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 7,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 8,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 9,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 10,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 11,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 12,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 13,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 14,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 15,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 16,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 17,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					},
					{
						"targets": 18,
						"createdCell": function(td, cellData, rowData, row, col) {
							if (rowData['statusrecal'] == 'beda') {
								$(td).css('background-color', 'red')
								$(td).css('color', 'white')
							}
						}
					}
				],
				'columns': [{
						data: 'idx'
					},
					{
						data: 'sku_stock_tgl'
					},
					{
						data: 'client_wms_nama'
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
						data: 'sku_satuan'
					},
					{
						data: 'sku_stock_expired_date'
					},
					{
						data: 'sku_stock_batch_no'
					},
					{
						data: 'sku_stock_awal'
					},
					{
						data: 'sku_stock_masuk'
					},
					{
						data: 'sku_stock_keluar'
					},
					{
						data: 'sku_stock_akhir'
					},
					{
						data: 'sku_stock_awal2'
					},
					{
						data: 'sku_stock_masuk2'
					},
					{
						data: 'sku_stock_keluar2'
					},
					{
						data: 'sku_stock_akhir2'
					},
					{
						data: 'statusrecal'
					}
				]
			});

			var jml_data_rekalkulasi = $('#table_list_data_rekalkulasi').DataTable().page.info().recordsTotal;
			$("#jml_rekalkulasi").val(jml_data_rekalkulasi);

		};

		const load_end = () => {
			var msg = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
			message_custom("Success", "success", msg);
		};

		// load_start();
		// setInterval(proses(), 1000);
		// load_end();
		proses();

	}

	$("#btn_simpan_rekalkulasi").on("click", function() {

		Swal.fire({
			title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
			cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
		}).then((result) => {
			if (result.value == true) {

				SimpanRekalkulasi();
			}
		});
	});
</script>