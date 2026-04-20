<script type="text/javascript">
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
	});

	function message(msg, msgtext, msgtype) {
		Swal.fire(msg, msgtext, msgtype);
	}

	function message_topright(type, msg) {
		const Toast = Swal.mixin({
			toast: true,
			position: "top-end",
			showConfirmButton: false,
			timer: 3000,
			didOpen: (toast) => {
				toast.addEventListener("mouseenter", Swal.stopTimer);
				toast.addEventListener("mouseleave", Swal.resumeTimer);
			},
		});

		Toast.fire({
			icon: type,
			title: msg,
		});
	}

	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	$(document).on("click", "#search_filter_data", function() {
		let no_mutasi = $("#no_draft_mutasi").val();
		let tgl = $("#tgl").val();
		if (no_mutasi == "") {
			message("Error!", "No. Draft Mutasi tidak boleh kosong", "error");
			return false;
		} else {
			$("#loadingsearch").show();
			$.ajax({
				url: "<?= base_url('KonfirmasiDistribusiPenerimaan/get_data_mutasi_pallet_draft_by_kode') ?>",
				type: "POST",
				data: {
					no_mutasi: no_mutasi,
					tgl: tgl,
				},
				dataType: "JSON",
				success: function(data) {
					$("#loadingsearch").hide();
					$("#list-data-form-search").show();
					let no = 1;
					if (data != null) {
						if ($.fn.DataTable.isDataTable('#listdata')) {
							$('#listdata').DataTable().destroy();
						}
						$('#listdata > tbody').empty();
						let str = "";
						if (data.status == "Open") {
							str += "<button data-href='<?= base_url('KonfirmasiDistribusiPenerimaan/konfirmasi/') ?>" + data.id + "' class='btn btn-warning form-control btnkonfirmasi' title='Konfirmasi distribusi penerimaan barang'><i class='fa fa-check'></i></button>";
						} else {
							str += "<button type='button' class='btn btn-primary btnviewdetail' data-href='<?= base_url('KonfirmasiDistribusiPenerimaan/view/') ?>" + data.id + "' data-id='" + data.id + "' title='detail data'><i class='fas fa-eye'> </i>";
						}
						$("#listdata > tbody").append(`
                <tr class="text-center">
                    <td>${no++}</td>
                    <td>${data.kode}</td>
                    <td>${data.tgl}</td>
                    <td>${data.pb_kode}</td>
                    <td>${data.no_sj}</td>
                    <td>${data.no_sj_eks}</td>
                    <td>${data.depo_asal}</td>
                    <td>${data.depo_tujuan}</td>
                    <td>${data.status}</td>
                    <td>${str}</td>
                </tr>
            `);
					} else {
						$("#listdata > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
					}

					$('#listdata').DataTable({
						columnDefs: [{
							sortable: false,
							targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
						}],
					});

				}
			});
		}

		$(document).on("click", ".btnkonfirmasi", function() {
			let href = $(this).attr("data-href");
			location.href = href;
		});


		// $(document).on("click", ".btnviewdetail", function() {
		//   let href = $(this).attr("data-href");
		//   location.href = href;
		// });

		// $(document).on("click", ".btnprint", function() {
		//   let href = $(this).attr("data-href");
		//   let sj_id = $(this).attr("data-id");
		//   window.open(href + "?id=" + sj_id + "&tipe=multiple", '_blank');
		// });
	});
</script>