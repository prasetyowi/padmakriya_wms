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

		}
	);

	$("#btncariAll").click(
		function() {
			getData();

		})

	function getData() {
		var filter_tanggal_kirim = $(`#filter_tanggal_kirim`).val();
		var filter_driver = $(`#filter_driver`).val();

		$("#table_detail > tbody").empty();

		if ($.fn.DataTable.isDataTable('#table_detail')) {
			$('#table_detail').DataTable().clear();
			$('#table_detail').DataTable().destroy();
		}

		table_detail = $('#table_detail').DataTable({
			// "scrollX": true,
			'paging': true,
			'searching': true,
			'ordering': true,
			'order': [
				[0, 'asc']
			],
			'lengthMenu': [
				[50, 100, 150, -1], // -1 untuk menunjukkan opsi "All"
				[50, 100, 150, "All"]
			],
			'processing': true,
			'serverSide': true,
			// 'deferLoading': 0,
			'ajax': {
				url: "<?= base_url('WMS/LaporanTransaksi/LaporanBiayaLainPengiriman/GetData') ?>",
				type: "POST",
				dataType: "json",
				data: function(data) {
					data.filter_tanggal_kirim = $(`#filter_tanggal_kirim`).val(),
						data.filter_driver = $(`#filter_driver`).val()
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
			// "drawCallback": function(response) {
			// 	var resp = response.json;
			// 	console.log(resp);
			// 	arrayDetail = []
			// 	for (var i = 0; i < resp.data.length; i++) {

			// 		arrayDetail.push(resp.data[i]);
			// 	}

			// 	console.log(arrayDetail);
			// },
			'columns': [{
					data: 'delivery_order_batch_kode'
				},
				{
					data: 'delivery_order_batch_tanggal_kirim'
				},
				{
					data: 'karyawan_nama'
				},
				{
					data: 'delivery_order_kirim_nama'
				},
				{
					data: 'delivery_order_kirim_alamat'
				},
				{
					render: function(data, type, row, meta) {
						var str = `${formatNumber(Math.round(row.total_nominal_tunai))}`;

						return str;
					},
					data: null
				},
				{
					render: function(data, type, row, meta) {
						var str = `<button class="btn btn-primary" onclick="btnDetailBiaya('${row.delivery_order_batch_kode}', '${row.delivery_order_id}', '${row.delivery_order_batch_tanggal_kirim}')">${row.total_biaya}</button>`;
						return str;
					},
					data: null
				},
			],
			"columnDefs": [{
				targets: 5,
				searchable: false
			}, ],
			initComplete: function() {
				parent_dt = $('#table_detail').closest('.dataTables_wrapper')
				parent_dt.find('.dataTables_filter').css('width', 'auto')
				var input = parent_dt.find('.dataTables_filter input').unbind(),
					self = this.api(),
					$searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
					.html('<i class="fa fa-fw fa-search">')
					.click(function() {
						self.search(input.val()).draw();
					}),
					$clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
					.html('<i class="fa fa-fw fa-recycle">')
					.click(function() {
						input.val('');
						$searchButton.click();

					})
				parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);
				parent_dt.find('.dataTables_filter input').keypress(function(e) {
					var key = e.which;
					if (key == 13) {
						$searchButton.click();
						return false;
					}
				});
			},
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