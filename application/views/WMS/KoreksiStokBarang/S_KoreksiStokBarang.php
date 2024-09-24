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

	$("#filter_koreksi_principle").on("change", function() {
		let id = $(this).val();
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/KoreksiStokBarang/get_checker_by_principleId') ?>",
			data: {
				id: id
			},
			success: function(response) {
				let data = JSON.parse(response);
				$("#filter_koreksi_checker").empty();
				if (data.length > 0) {
					$("#filter_koreksi_checker").append("<option value=''>--All--</option>")
					$.each(data, function(i, v) {
						$("#filter_koreksi_checker").append(`<option value="${v.id}">${v.nama}</option>`);
					});
				} else {
					$("#filter_koreksi_checker").empty();
				}
			}
		});
	});

	$(document).on("click", "#search_filter_data", function() {
		let filter_no_koreksi = $("#filter_no_koreksi").val();
		let filter_koreksi_tgl = $("#filter_koreksi_tgl").val();
		let filter_gudang_asal_koreksi = $("#filter_gudang_asal_koreksi").val();
		let filter_koreksi_tipe_transaksi = $("#filter_koreksi_tipe_transaksi").val();
		let filter_koreksi_principle = $("#filter_koreksi_principle").val();
		let filter_koreksi_checker = $("#filter_koreksi_checker").val();
		let filter_koreksi_status = $("#filter_koreksi_status").val();

		$("#loadingsearch").show();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/KoreksiStokBarang/get_data_koreksi_stok_by_filter') ?>",
			data: {
				filter_no_koreksi: filter_no_koreksi,
				filter_koreksi_tgl: filter_koreksi_tgl,
				filter_gudang_asal_koreksi: filter_gudang_asal_koreksi,
				filter_koreksi_tipe_transaksi: filter_koreksi_tipe_transaksi,
				filter_koreksi_principle: filter_koreksi_principle,
				filter_koreksi_checker: filter_koreksi_checker,
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
				let str = "";
				if (v.status == "In Progress") {
					str += `<button type="button" data-href='<?= base_url('WMS/KoreksiStokBarang/edit/') ?>${ v.id }' class="btn btn-warning edit_data"><i class="fas fa-edit"></i> Edit</button>`;
				}
				if (v.status == "Completed") {
					str += `<button type="button" data-href='<?= base_url('WMS/KoreksiStokBarang/view/') ?>${ v.id }' class="btn btn-primary detail_data"><i class="fas fa-eye"></i> Detail</button>`;
				}
				$("#list_data_koreksi_stok > tbody").append(`
            <tr class="text-center">
                <td>${i + 1}</td>
                <td>${v.kode}</td>
                <td>${v.tgl}</td>
                <td>${v.tipe}</td>
                <td >${v.principle}</td>
                <td>${v.checker}</td>
                <td >${v.gudang}</td>
                <td>${v.status}</td>
                <td>${str}</td>
            </tr>
        `);
			});

			$('#list_data_koreksi_stok').DataTable();
		} else {
			$("#list_data_koreksi_stok > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
		}
	}

	$(document).on("click", ".edit_data", function() {
		let href = $(this).attr("data-href");
		location.href = href;
	});

	$(document).on("click", ".detail_data", function() {
		let href = $(this).attr("data-href");
		location.href = href;
	});
</script>