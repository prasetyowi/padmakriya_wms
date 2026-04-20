<script>
	loadingBeforeReadyPage()
	let global_id = $("#global_id_view").val();

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
			url: "<?= base_url('WMS/DistribusiPenerimaan/get_data_header_konfirmasi_view') ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				console.log(response);
				const header = response.header;
				const detail = response.detail;

				$("#no_doc_view").val(header.kode);
				$("#tgl_view").val(header.tgl);
				$("#no_penerimaan_view").html("<option value=''>" + header.kode_btb + "</option>");
				$("#surat_jalan_view").val(header.no_sj);
				$("#surat_jalan_eksternal_view").val(header.sj_no);
				$("#tipe_penerimaan_view").val(header.tipe);
				$("#tgl_btb_view").val(header.tgl_btb);
				$("#expedisi_view").val(header.eks_kode + " - " + header.eks_nama);
				$("#no_kendaraan_view").val(header.nopol);
				$("#nama_pengemudi_view").val(header.pengemudi);
				$("#status_view").val(header.status);
				$("#keterangan_view").val(header.keterangan == null ? "-" : header.keterangan);
				$("#gudang_asal_view").html("<option value=''>" + header.gudang + "</option>");
				$("#checker_view").html("<option value=''>" + header.checker + "</option>");

				append_detail_konfirmasi(detail);
			}
		});
	}

	function append_detail_konfirmasi(response) {
		$("#list_data_view > tbody").empty();
		let no = 1;
		$.each(response, function(i, v) {
			let status = "";

			if (v.valid == null) {
				status += "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Belum Divalidasi</span>";
			} else if (v.valid == 0) {
				status += "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Tidak Valid</span>";
			} else {
				status += "<span class='btn btn-success' style='cursor: context-menu;padding:0px'>Valid </span>";
			}
			$("#list_data_view > tbody").append(`
                <tr>
                    <td class="text-center">${no++}</td>
                    <td class="text-center">${v.kode}</td>
                    <td class="text-center">${v.pallet_jenis}</td>
                    <td class="text-center"><select class="form-control" disabled><option value="">${v.gudang_tujuan}</option></select></td>
                    <td class="text-center">${status}</td>
                    <td class="text-center">
                        <button type="button" data-toggle="tooltip" data-placement="top" data-id="` + v.id + `" title="detail pallet" class="btn btn-primary detail_pallet"><i class="fas fa-eye"></i> Detail</button>
                    </td>
                </tr>
            `);
		});

		$('#list_data_view').DataTable();
	}

	$(document).on("click", ".detail_pallet", function() {
		let id = $(this).attr('data-id');
		$("#modal_view_detail_view").modal('show');
		//ajax request detail
		$.ajax({
			url: "<?= base_url('WMS/DistribusiPenerimaan/get_data_detail_pallet_view'); ?>",
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
					$("#table_list_detail_view > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
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

	$(document).on("click", "#kembali_distribusi", () => {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/DistribusiPenerimaan/DistribusiPenerimaanMenu') ?>";
		}, 500);
	});
</script>