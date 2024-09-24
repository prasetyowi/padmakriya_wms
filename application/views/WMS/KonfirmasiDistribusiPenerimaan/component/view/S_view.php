<script>
	let global_id = $("#global_id_view").val();
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
		get_data();
	});

	// function message(msg, msgtext, msgtype) {
	//     Swal.fire(msg, msgtext, msgtype);
	// }

	// function message_topright(type, msg) {
	//     const Toast = Swal.mixin({
	//         toast: true,
	//         position: "top-end",
	//         showConfirmButton: false,
	//         timer: 3000,
	//         didOpen: (toast) => {
	//             toast.addEventListener("mouseenter", Swal.stopTimer);
	//             toast.addEventListener("mouseleave", Swal.resumeTimer);
	//         },
	//     });

	//     Toast.fire({
	//         icon: type,
	//         title: msg,
	//     });
	// }

	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	function get_data() {
		//ajax get data header
		$.ajax({
			url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/get_data_header_konfirmasi_view') ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				const header = response.header;
				const detail = response.detail;

				let tgl = header.tgl.split(" ");
				$("#no_mutasi_view").val(header.kode);
				$("#no_draft_mutasi_view").val(header.kode_draft);
				$("#tgl_view").val(tgl[0]);
				$("#no_penerimaan_view").val(header.pb_kode);
				// $("#no_sj_view").val(header.no_sj);
				$("#gudang_asal_view").val(header.depo_asal);
				$("#gudang_tujuan_view").val(header.depo_tujuan);
				$("#checker_view").val(header.checker);
				$("#tipe_transaksi_view").val(header.tipe);
				$("#keterangan_view").val(header.keterangan);
				$("#status_view").val(header.status);

				append_detail_konfirmasi(detail);
			}
		});
	}

	function append_detail_konfirmasi(response) {
		$("#list_data_konfirmasi_view > tbody").empty();
		let no = 1;
		$.each(response, function(i, v) {
			let status = "";

			if (v.status == null) {
				status += "<span class='btn btn-danger' style='cursor: context-menu;padding:0px' name='CAPTION-BELUMDIVALIDASI'>Belum Divalidasi</span>";
			} else if (v.status == 0) {
				status += "<span class='btn btn-danger' style='cursor: context-menu;padding:0px' name='CAPTION-TIDAKVALID'>Tidak Valid</span>";
			} else {
				status += "<span class='btn btn-success' style='cursor: context-menu;padding:0px' name='CAPTION-VALID'>Valid </span>";
			}
			$("#list_data_konfirmasi_view > tbody").append(`
                <tr>
                    <td class="text-center">${no++} <input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="` + v.id + `"/></td>
                    <td class="text-center">${v.kode}</td>
                    <td class="text-center">${v.jenis}</td>
                    <td class="text-center">${status}</td>
                    <td class="text-center">${v.rak_nama} </td>
                    <td class="text-center">
                        <button type="button" data-toggle="tooltip" data-placement="top" data-id="` + v.id + `" title="detail pallet" class="btn btn-primary detail_pallet"><i class="fas fa-eye"></i> Detail</button>
                    </td>
                </tr>
            `);
		});

		$('#list_data_konfirmasi_view').DataTable();
	}

	$(document).on("click", ".detail_pallet", function() {
		let id = $(this).attr('data-id');
		$("#modal_view_detail_view").modal('show');
		//ajax request detail
		$.ajax({
			url: "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/get_data_detail_pallet_view'); ?>",
			type: "POST",
			data: {
				id: id
			},
			dataType: "JSON",
			async: false,
			success: function(data) {
				if (data != null) {
					if ($.fn.DataTable.isDataTable('#table_list_detail_view')) {
						$('#table_list_detail_view').DataTable().destroy();
					}
					$('#table_list_detail_view > tbody').empty();
					let no = 1;
					$.each(data, function(i, v) {
						$("#table_list_detail_view > tbody").append(`
                            <tr>
                                <td class="text-center">${no++}</td>
                                <td class="text-center">${v.principle}</td>
                                <td class="text-center">${v.sku_kode}</td>
                                <td>${v.sku_nama}</td>
                                <td class="text-center">${v.sku_kemasan}</td>
                                <td class="text-center">${v.sku_satuan}</td>
                                <td class="text-center">${v.ed}</td>
                                <td class="text-center">${v.tipe}</td>
                                <td class="text-center">${v.qty}</td>
                            </tr>
                        `);
					});
				} else {
					$("#table_list_detail_view > tbody").html(`<tr><td colspan="9" class="text-center text-danger" name="CAPTION-DATAKOSONG">Data Kosong</td></tr>`);
				}

				$('#table_list_detail_view').DataTable({
					lengthMenu: [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, 'All']
					],
				});
			},
		});
	});

	$(document).on("click", ".btn_close_detail_view", function() {
		$("#modal_view_detail_view").modal('hide');
	});

	$(document).on("click", "#kembali_distribusi_view", () => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/KonfirmasiDistribusiPenerimaan/KonfirmasiDistribusiPenerimaanMenu') ?>";
		}, 500);
	});
</script>