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

		let count_sku = $("#list_data_tambah_koreksi_stok_view > tbody tr").length;
		$("#list_data_tambah_koreksi_stok_view > tfoot tr #total_detail_sku_view").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
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

	function getDataKoreksiDraftById() {
		$.ajax({
			url: "<?= base_url('WMS/KoreksiStokBarangDraft/getDataKoreksiDraftById'); ?>",
			type: "POST",
			data: {
				id: global_id
			},
			dataType: "JSON",
			success: function(response) {
				const header = response.header;
				const detail = response.detail;
				const tipeDokumen = response.tipeDokumen;
				$("#no_koreksi_draft_view").val(header.kode);
				$("#koreksi_draft_tgl_view").val(header.tgl);
				$("#gudang_koreksi_draft_view").val(header.gudang_id).trigger('change');
				$("#koreksi_draft_principle_view").val(header.principle_id).trigger('change');
				$("#koreksi_draft_checker_view").val(header.checker_id).trigger('change');
				$("#koreksi_draft_tipe_transaksi_view").val(header.tipe_id).trigger('change');
				// get_checker(header.checker_id);
				$("#koreksi_draft_status_view").val(header.status);
				$("#koreksi_draft_keterangan_view").val(header.keterangan);
				header.status == "Draft" ? $("#koreksi_draft_approval_view").prop('checked', false) : $("#koreksi_draft_approval_view").prop('checked', true);

				var tipe_dokumen = tipeDokumen == null ? '' : tipeDokumen.table_name + '-' + tipeDokumen.table_name_kode + '-' + tipeDokumen.tipe_dokumen_nama;
				$("#koreksi_draft_tipe_dokumen").val(tipe_dokumen).trigger('change');
				$("#koreksi_draft_no_referensi_dokumen").val(header.no_referensi_dokumen);

				if (header.attachment != null) {
					$('#hide-file').append(`
					<a href="<?= base_url('assets/images/uploads/KoreksiStokBarang/') ?>${header.attachment}" target="_blank">${header.attachment}</a>
					`)
				} else {
					$('#hideFileNull').empty();
				}

				initDataDetail(detail);
			}
		})
	}

	function initDataDetail(data) {
		if (data.length != 0) {
			$.each(data, function(i, v) {
				$("#list_data_tambah_koreksi_stok_view > tbody").append(`
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
			$("#list_data_tambah_koreksi_stok_view > tbody").html(`<tr><td colspan="8" class="text-center text-danger">Data Kosong</td></tr>`);
		}

		trigger();
	}

	function trigger() {
		check_count_sku();

		let count_sku = $("#list_data_tambah_koreksi_stok_view > tbody tr").length;
		$("#list_data_tambah_koreksi_stok_view > tfoot tr #total_detail_sku_view").html("<strong><h4>Total " + count_sku + " SKU</h4></strong>");
	}

	function check_count_sku() {
		let total_sku = $("#list_data_tambah_koreksi_stok_view > tbody tr").length;
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
			url: "<?= base_url('WMS/KoreksiStokBarangDraft/get_checker_by_principleId') ?>",
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
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/KoreksiStokBarangDraft/KoreksiStokBarangDraftMenu') ?>";
		}, 500);
	})

	function getReferensiDokumen(value) {
		$('#konten-table').empty();

		if (value != '') {
			$('#koreksi_draft_no_referensi_dokumen').val('');
			$('#koreksi_draft_no_referensi_dokumen').attr('data-tipe-dokumen', value);
			$('#koreksi_draft_no_referensi_dokumen').prop('disabled', false);
		} else {
			$('#koreksi_draft_no_referensi_dokumen').val('');
			$('#koreksi_draft_no_referensi_dokumen').attr('data-tipe-dokumen', value);
			$('#koreksi_draft_no_referensi_dokumen').prop('disabled', true);
		}
	}
</script>