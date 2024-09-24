<script>
	loadingBeforeReadyPage()

	$(document).ready(function() {
		select2();
		$('#filterKoreksiPalletTanggal').daterangepicker({
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

	const postDataRequest = async (url = '', data = {}, type) => {
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

	const handleSearchFilterData = () => {
		let filterNoKoreksiPallet = $("#filterNoKoreksiPallet").val();
		let filterKoreksiPalletTanggal = $("#filterKoreksiPalletTanggal").val();

		$("#loadingsearch").show();

		postDataRequest('<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/getDataKoreksiPalletByFilter') ?>', {
				filterNoKoreksiPallet,
				filterKoreksiPalletTanggal
			}, 'POST')
			.then((response) => {
				$("#loadingsearch").hide();
				$("#show-filter-list").show();
				initDataSearch(response);
			});
	}


	function initDataSearch(response) {
		if (response.length > 0) {
			if ($.fn.DataTable.isDataTable('#list_data_filter')) {
				$('#list_data_filter').DataTable().destroy();
			}
			$("#list_data_filter > tbody").empty();

			$.each(response, function(i, v) {
				let str = "";
				str += `<button type="button" data-href='<?= base_url('WMS/Distribusi/AssignmentPengembalianBarang/view/') ?>${ v.id }' class="btn btn-primary detail_data"><i class="fas fa-eye"></i> Detail</button>`;
				$("#list_data_filter > tbody").append(`
            <tr class="text-center">
                <td>${i + 1}</td>
                <td>${v.tgl}</td>
                <td>${v.kode}</td>
                <td>${v.tipe}</td>
                <td >${v.client}</td>
                <td >${v.principle}</td>
                <td>${v.depo_detail_nama}</td>
                <td>${v.keterangan}</td>
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