<script>
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
		$('#filter_koreksi_draft_tgl_draft').daterangepicker({
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

	// function message(msg, msgtext, msgtype) {
	//   Swal.fire(msg, msgtext, msgtype);
	// }

	// function message_topright(type, msg) {
	//   const Toast = Swal.mixin({
	//     toast: true,
	//     position: "top-end",
	//     showConfirmButton: false,
	//     timer: 3000,
	//     didOpen: (toast) => {
	//       toast.addEventListener("mouseenter", Swal.stopTimer);
	//       toast.addEventListener("mouseleave", Swal.resumeTimer);
	//     },
	//   });

	//   Toast.fire({
	//     icon: type,
	//     title: msg,
	//   });
	// }


	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	$("#filter_koreksi_draft_principle").on("change", function() {
		let id = $(this).val();
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/KoreksiStokBarangDraft/get_checker_by_principleId') ?>",
			data: {
				id: id
			},
			success: function(response) {
				let data = JSON.parse(response);
				$("#filter_koreksi_draft_checker").empty();
				if (data.length > 0) {
					$("#filter_koreksi_draft_checker").append("<option value=''>--Pilih Checker--</option>")
					$.each(data, function(i, v) {
						$("#filter_koreksi_draft_checker").append(`<option value="${v.id}">${v.nama}</option>`);
					});
				} else {
					$("#filter_koreksi_draft_checker").empty();
				}
			}
		});
	});

	$(document).on("click", "#search_filter_data", function() {
		let filter_no_koreksi_draft = $("#filter_no_koreksi_draft").val();
		let filter_koreksi_draft_tgl_draft = $("#filter_koreksi_draft_tgl_draft").val();
		let filter_gudang_asal_koreksi_draft = $("#filter_gudang_asal_koreksi_draft").val();
		let filter_koreksi_draft_tipe_transaksi = $("#filter_koreksi_draft_tipe_transaksi").val();
		let filter_koreksi_draft_principle = $("#filter_koreksi_draft_principle").val();
		let filter_koreksi_draft_checker = $("#filter_koreksi_draft_checker").val();
		let filter_koreksi_draft_status = $("#filter_koreksi_draft_status").val();

		$("#loadingsearch").show();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/KoreksiStokBarangDraft/get_data_koreksi_stok_draft_by_filter') ?>",
			data: {
				filter_no_koreksi_draft: filter_no_koreksi_draft,
				filter_koreksi_draft_tgl_draft: filter_koreksi_draft_tgl_draft,
				filter_gudang_asal_koreksi_draft: filter_gudang_asal_koreksi_draft,
				filter_koreksi_draft_tipe_transaksi: filter_koreksi_draft_tipe_transaksi,
				filter_koreksi_draft_principle: filter_koreksi_draft_principle,
				filter_koreksi_draft_checker: filter_koreksi_draft_checker,
				filter_koreksi_draft_status: filter_koreksi_draft_status,
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
			if ($.fn.DataTable.isDataTable('#list_data_filter')) {
				$('#list_data_filter').DataTable().destroy();
			}
			$("#list_data_filter > tbody").empty();

			$.each(response, function(i, v) {
				let str = "";
				if (v.status == "Draft") {
					str += `<button type="button" data-href='<?= base_url('WMS/KoreksiStokBarangDraft/edit/') ?>${ v.id }' class="btn btn-warning edit_data"><i class="fas fa-edit"></i> Edit</button>`;
				} else {
					str += `<button type="button" data-href='<?= base_url('WMS/KoreksiStokBarangDraft/view/') ?>${ v.id }' class="btn btn-primary detail_data"><i class="fas fa-eye"></i> Detail</button>`;
				}
				$("#list_data_filter > tbody").append(`
            <tr>
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

			$('#list_data_filter').DataTable();
		} else {
			$("#list_data_filter > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
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