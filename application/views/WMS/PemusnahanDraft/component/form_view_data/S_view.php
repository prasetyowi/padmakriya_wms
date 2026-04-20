<script>
	let global_id = $("#global_id_view").val();
	let global_sku_id = [];
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
		getDataKoreksiDraftById();

		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		let count_sku = $("#list_data_tambah_pemusnahan_stok_view > tbody tr").length;
		$("#list_data_tambah_pemusnahan_stok_view > tfoot tr #total_detail_sku_view").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
	});

	// function message(msg, msgtext, msgtype) {
	// 	Swal.fire(msg, msgtext, msgtype);
	// }

	// function message_topright(type, msg) {
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


	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	function getDataKoreksiDraftById() {
		$.ajax({
			url: "<?= base_url('WMS/PemusnahanDraft/getDataPemusnahanDraftById'); ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			success: function(response) {
				const header = response.header;
				const detail = response.detail;
				$("#no_pemusnahan_draft_view").val(header.kode);
				$("#pemusnahan_draft_tgl_view").val(header.tgl);
				$("#gudang_pemusnahan_draft_view").val(header.gudang_id).trigger('change');
				$("#pemusnahan_draft_principle_view").val(header.principle_id).trigger('change');
				$("#pemusnahan_draft_checker_view").val(header.checker_id).trigger('change');
				$("#pemusnahan_draft_tipe_transaksi_view").val(header.tipe_id).trigger('change');
				if ($("#pemusnahan_draft_tipe_transaksi_view").children("option").filter(":selected").text() == 'Pemusnahan') {
					$('#divhideshow').fadeIn()
					$('#divpemusnahan_draft_ekspedisi').hide()
					$('#divpemusnahan_draft_driver').fadeIn()
					$('#divpemusnahan_draft_kendaraan').fadeIn()
					// $('#divpemusnahan_draft_nopol').fadeIn()

					$('#divpemusnahan_draft_driver2').hide()
					$('#divdivpemusnahan_draft_kendaraan2').hide()
					$('#divpemusnahan_draft_nopol2').hide()

					$("#pemusnahan_draft_driver_view").val(header.tr_koreksi_stok_draft_pengemudi).trigger('change');
					$("#pemusnahan_draft_kendaraan_view").val(header.tr_koreksi_stok_draft_kendaraan).trigger('change');
					$("#tempNopol").val(header.tr_koreksi_stok_draft_nopol);
					let kendaraan_model = header.tr_koreksi_stok_draft_kendaraan;

					$.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/PemusnahanDraft/get_nopol_by_kendaraan_model') ?>",
						data: {
							kendaraan_model: kendaraan_model
						},
						async: false,
						success: function(response) {
							let data = JSON.parse(response);
							$("#pemusnahan_draft_nopol_view").html('');
							if (data.length > 0) {
								$("#pemusnahan_draft_nopol_view").append("<option value=''>--Pilih No Polisi--</option>")
								$.each(data, function(i, v) {
									if (header.tr_koreksi_stok_draft_nopol == v.kendaraan_nopol) {
										$("#pemusnahan_draft_nopol_view").append(`<option value="${v.kendaraan_id}" selected>${v.kendaraan_nopol}</option>`);
									} else {
										$("#pemusnahan_draft_nopol_view").append(`<option value="${v.kendaraan_id}">${v.kendaraan_nopol}</option>`);
									}
								});
							} else {
								$("#pemusnahan_draft_nopol_view").empty();
							}
						}
					});
				}
				if ($("#pemusnahan_draft_tipe_transaksi_view").children("option").filter(":selected").text() == 'Retur Supplier') {
					$('#divhideshow').fadeIn()
					$('#divpemusnahan_draft_ekspedisi').fadeIn()
					$('#divpemusnahan_draft_driver').hide()
					$('#divpemusnahan_draft_kendaraan').hide()
					$('#divpemusnahan_draft_nopol').hide()

					$('#divpemusnahan_draft_driver2').fadeIn()
					$('#divdivpemusnahan_draft_kendaraan2').hide()
					$('#divpemusnahan_draft_nopol2').fadeIn()
					$("#pemusnahan_draft_ekspedisi_view").val(header.ekspedisi_id).trigger('change');
					$('#pemusnahan_draft_driver2_view').val(header.tr_koreksi_stok_draft_pengemudi)
					$('#pemusnahan_draft_nopol2_view').val(header.tr_koreksi_stok_draft_nopol)
				}
				// get_checker(header.checker_id);
				$("#pemusnahan_draft_status_view").val(header.status);
				$("#pemusnahan_draft_keterangan_view").val(header.keterangan);
				header.status == "Draft" ? $("#pemusnahan_draft_approval_view").prop('checked', false) : $("#pemusnahan_draft_approval_view").prop('checked', true);

				let kendaraan_model = header.tr_koreksi_stok_draft_kendaraan;


				initDataDetail(detail);
			}
		})
	}

	function initDataDetail(data) {
		if (data.length != 0) {
			$.each(data, function(i, v) {
				$("#list_data_tambah_pemusnahan_stok_view > tbody").append(`
            <tr>
                <td>${v.sku_kode} <input type="hidden" name="sku_stock_id" id="sku_stock_id" value="${v.id}"/></td>
                <td>${v.sku_nama} <input type="hidden" name="sku_id" id="sku_id" value="${v.sku_id}"/></td>
                <td class="text-center">${v.brand}</td>
                <td class="text-center">${v.sku_satuan}</td>
                <td class="text-center">${v.sku_kemasan}</td>
                <td class="text-center">${v.ed}</td>
                <td class="text-center">${v.qty_available}</td>
                <td class="text-center">
                  <input type="text" class="form-control numeric" name="qty_plan" id="qty_plan" value="${v.qty_plan}" readonly/>
                </td>
            </tr>
        `);
			});
		} else {
			$("#list_data_tambah_pemusnahan_stok_view > tbody").html(`<tr><td colspan="8" class="text-center text-danger" name="CAPTION-DATAKOSONG">Data Kosong</td></tr>`);
		}

		trigger();
	}

	function trigger() {
		check_count_sku();

		let count_sku = $("#list_data_tambah_pemusnahan_stok_view > tbody tr").length;
		$("#list_data_tambah_pemusnahan_stok_view > tfoot tr #total_detail_sku_view").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
	}

	function check_count_sku() {
		let total_sku = $("#list_data_tambah_pemusnahan_stok_view > tbody tr").length;
		if (total_sku == 0) {
			//hapus readonly perusahaan dan principle
			$("#gudang_koreksi_draft_view").removeAttr("disabled");
			$("#koreksi_draft_principle_view").removeAttr("disabled");
		} else {
			$("#gudang_koreksi_draft_view").attr("disabled", "disabled");
			$("#koreksi_draft_principle_view").attr("disabled", "disabled");
		}
	}

	$("#gudang_koreksi_draft_view").on("change", function() {
		$("#pilih_sku_koreksi_draft_view").attr("data-gudang-id", $(this).val());
		$("#pilih_sku_koreksi_draft_view").attr("data-gudang-txt", $(this).children("option").filter(":selected").text());
	});

	$("#koreksi_draft_principle_view").on("change", function() {
		let id = $(this).val();
		let txt = $(this).children("option").filter(":selected").text();
		$("#pilih_sku_koreksi_draft_view").attr("data-principle-id", id);
		$("#pilih_sku_koreksi_draft_view").attr("data-principle-txt", txt);
		$.ajax({
			type: 'POST',
			url: "<?= base_url('KoreksiStokBarangDraft/get_checker_by_principleId') ?>",
			data: {
				id: id
			},
			dataType: "JSON",
			async: false,
			success: function(response) {
				$("#koreksi_draft_checker_view").empty();
				if (response.length > 0) {
					$("#koreksi_draft_checker_view").append("<option value=''>--Pilih Checker--</option>")
					$.each(response, function(i, v) {
						$("#koreksi_draft_checker_view").append(`<option value="${v.id}">${v.nama}</option>`);
					});
				} else {
					$("#koreksi_draft_checker_view").empty();
				}
			}
		});
	});

	$("#koreksi_draft_approval_view").on("change", function(e) {
		e.target.checked ? $("#koreksi_draft_status_view").val($(this).val()) : $("#koreksi_draft_status_view").val("Draft");
	});

	$(document).on("click", "#kembali_koreksi_draft_view", function() {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('KoreksiStokBarangDraft/KoreksiStokBarangDraftMenu') ?>";
		}, 500);
	})
</script>