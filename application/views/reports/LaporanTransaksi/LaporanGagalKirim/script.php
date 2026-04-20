<script type="text/javascript">
	var index_pallet = 0;

	$(document).ready(
		function() {
			// if ($('#filter_tanggal_kirim').length > 0) {
			// 	$('#filter_tanggal_kirim').daterangepicker({
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

		}
	);

	$("#btncariAll").click(
		function() {
			if ($("#filter_driver").val() == '') {
				message("Error!", "Pilih Pengemudi Terlebih Dahulu!", "error");
				return false;
			}

			getData();

		})

	function getData() {
		if ($.fn.DataTable.isDataTable('#table_detail')) {
			$('#table_detail').DataTable().destroy();
		}

		$('#table_detail > tbody').empty();

		$('#table_detail').DataTable({
			'serverSide': true,
			'ajax': {
				'url': "<?= base_url('WMS/LaporanTransaksi/LaporanGagalKirim/getDetail') ?>",
				'type': 'POST',
				'data': {
					filter_tanggal_fdjr: $('#filter_tanggal_fdjr').val(),
					driver: $('#filter_driver').val()
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
					table_dt.columns.adjust();
				},
			},
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, 'All']
			],
			searching: false,
			ordering: false,
			// info: false,
			autoWidth: 'true',
			'columns': [{
					data: null,
					render: function(data, type, full, meta) {
						var index = meta.row + meta.settings._iDisplayStart + 1;
						return index;
					},
				},
				{
					'data': 'karyawan_nama'
				},
				{
					'data': 'tgl_fdjr'
				},
				{
					'data': 'delivery_order_batch_kode'
				},
				{
					'data': 'sales_order_kode'
				},
				{
					'data': 'sales_order_no_po'
				},
				{
					'data': 'delivery_order_kode'
				},
				{
					'data': 'delivery_order_kirim_nama'
				},
				{
					'data': 'delivery_order_kirim_alamat'
				},
				{
					'data': 'sku_kode'
				}, {
					'data': 'sku_nama'
				}, {
					'data': 'qtyorder'
				},
				{
					'data': 'qtyterkirim'
				},
				{
					'data': 'qtygagal'
				},
				{
					'data': 'reason'
				},
			]
		});
	}

	function formatNumber(num) {
		return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
	}

	function btnDetailBiaya(do_batch_kode, do_id, tgl_kirim) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/LaporanTransaksi/LaporanBiayaLainPengiriman/getDetailBiaya') ?>",
			data: {
				do_batch_kode: do_batch_kode,
				do_id: do_id,
				tgl_kirim: tgl_kirim
			},
			dataType: "JSON",
			success: function(response) {
				$("#table-detail-biaya > tbody").empty();

				if ($.fn.DataTable.isDataTable('#table-detail-biaya')) {
					$('#table-detail-biaya').DataTable().clear();
					$('#table-detail-biaya').DataTable().destroy();
				}

				$.each(response, function(i, v) {
					$("#table-detail-biaya tbody").append(`
						<tr>
							<td class="text-center">${v.tipe_biaya_nama}</td>
							<td class="text-center">${formatNumber(parseInt(v.delivery_order_detail3_nilai))}</td>
							<td class="text-center">${v.delivery_order_detail3_keterangan}</td>
						</tr>
					`)
				})

				$("#modalViewDetailBiaya").modal('show');
			}
		});
	}
</script>