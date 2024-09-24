<script>
	loadingBeforeReadyPage()
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

		$('#table_detail').DataTable({
			responsive: true
		});

		$(".select2").select2({
			width: "100%"
		});

	});

	/** --------------------------------------- Untuk Global ------------------------------------------- */

	// const message = (msg, msgtext, msgtype) => {
	// 	Swal.fire(msg, msgtext, msgtype);
	// }

	// const message_topright = (type, msg) => {
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

	async function postDataRequest(url = '', data = {}, type) {
		// Default options are marked with *
		const response = await fetch(url, {
			method: type,
			headers: {
				'Content-Type': 'application/json'
			},
			body: JSON.stringify(data)
		});
		return response.json();
	}

	$(document).on("input", ".numeric", function(event) {
		this.value = this.value.replace(/[^\d.]+/g, '');
	});


	const handlerFilterData = (event) => {

		if ($.fn.DataTable.isDataTable('#table_detail')) {
			$('#table_detail').DataTable().destroy();
		}

		$('#table_detail').DataTable({
			'bInfo': true,
			'pageLength': 10,
			'serverSide': true,
			'serverMethod': 'post',
			'ajax': {
				url: "<?= base_url('WMS/RekalkulasiStock/filteredDataRekalkulasiStock') ?>",
				data: {
					filterTanggal: $('#filter_stock_tanggal').val()
				}
			},
			// 'fixedHeader': false,
			'scrollY': 500,
			'scrollX': true,
			"pagingType": "full_numbers",
			"lengthMenu": [
				[5, 10, 25, 50, 100, 500, 1000],
				[5, 10, 25, 50, 100, 500, 1000]
			],
			'columns': [{
					data: 'client_wms_nama'
				},
				{
					data: 'principle_kode'
				},
				{
					data: 'depo_detail_nama'
				},
				{
					data: 'sku_kode'
				},
				{
					data: 'sku_nama_produk'
				},
				{
					data: 'ed_ss'
				},
				{
					data: 'sku_stock_awal'
				},
				{
					data: 'sku_stock_masuk'
				},
				{
					data: 'sku_stock_alokasi_masuk'
				},
				{
					data: 'sku_stock_alokasi_keluar'
				},
				{
					data: 'sku_stock_keluar'
				},
				{
					data: 'sku_stock_akhir'
				},
			],
		});

		// postDataRequest('<?= base_url('WMS/RekalkulasiStock/filteredDataRekalkulasiStock') ?>', {
		// 		filterTanggal: $('#filter_stock_tanggal').val(),
		// 	}, 'POST')
		// 	.then((response) => {
		// 		console.log(response);
		// 		return false;
		// 	})

	}
</script>