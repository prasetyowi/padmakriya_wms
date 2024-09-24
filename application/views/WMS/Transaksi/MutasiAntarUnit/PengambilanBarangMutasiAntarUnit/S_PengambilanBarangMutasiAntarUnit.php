<script>
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
		$('#filter_koreksi_tgl').daterangepicker({
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
	});


	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	$(document).on("click", "#search_filter_data", function() {
		let filter_no_koreksi = $("#filter_no_koreksi").val();
		let filter_koreksi_tgl = $("#filter_koreksi_tgl").val();
		let filterEkspedisiHomePage = $("#filterEkspedisiHomePage").val();
		let filterPengemudiHomePage = $("#filterPengemudiHomePage").val();
		let filterKendaraanHomePage = $("#filterKendaraanHomePage").val();
		let filter_koreksi_status = $("#filter_koreksi_status").val();

		$("#loadingsearch").show();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/get_data_koreksi_stok_by_filter') ?>",
			data: {
				filter_no_koreksi: filter_no_koreksi,
				filter_koreksi_tgl: filter_koreksi_tgl,
				filterEkspedisiHomePage: filterEkspedisiHomePage,
				filterPengemudiHomePage: filterPengemudiHomePage,
				filterKendaraanHomePage: filterKendaraanHomePage,
				filter_koreksi_status: filter_koreksi_status,
			},
			dataType: "JSON",
			success: function(response) {
				$("#loadingsearch").hide();
				$("#show-filter-list").show();
				initDataSearch(response);
			}
		});
	});

	function initDataSearch(response) {
		if (response.length > 0) {
			if ($.fn.DataTable.isDataTable('#list_data_koreksi_stok')) {
				$('#list_data_koreksi_stok').DataTable().destroy();
			}
			$("#list_data_koreksi_stok > tbody").empty();


			$.each(response, function(i, v) {

				let strAction = "";
				if (v.countSimpan > 0) {
					if (v.tr_mutasi_depo_status === 'In Progress Picking') {
						strAction += `<a href='<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/edit/') ?>${ v.tr_mutasi_depo_id }' target="_BLANK" class="btn btn-warning edit_data"><i class="fas fa-edit"></i> Edit</a>`;
					}
					if (v.tr_mutasi_depo_status == "Completed") {
						strAction += `<a href='<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/view/') ?>${ v.tr_mutasi_depo_id }' target="_BLANK" class="btn btn-primary detail_data"><i class="fas fa-eye"></i> Detail</a>`;
					}
				}


				$('#list_data_koreksi_stok > tbody').append(`
							<tr class="text-center">
								<td>${i + 1}</td>
								<td>${v.tr_mutasi_depo_kode}</td>
								<td>${v.ekspedisi_nama}</td>
								<td>${v.karyawan_nama}</td>
								<td>${v.kendaraan_model} - ${v.kendaraan_nopol}</td>
								<td>${v.tr_mutasi_depo_status}</td>
								<td>${strAction}</td>
							</tr>
					`);
			});

			$('#list_data_koreksi_stok').DataTable();
		} else {
			$("#list_data_koreksi_stok > tbody").html(`<tr><td colspan="7" class="text-center text-danger">Data Kosong</td></tr>`);
		}
	}
</script>