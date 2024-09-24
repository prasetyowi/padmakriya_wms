<script type="text/javascript">
	let global_id = $("#global_id_view").val();
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
	});

	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	// function message(msg, msgtext, msgtype) {
	//   Swal.fire(msg, msgtext, msgtype);
	// }

	$.ajax({
		url: "<?= base_url('WMS/PenerimaanBarang/get_surat_jalan_detail_view') ?>",
		type: "POST",
		data: {
			id: global_id
		},
		dataType: "JSON",
		beforeSend: function() {
			$("#tablelistdatadetailview > tbody").html(`<tr><td colspan="9" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> Loading...</span></td></tr>`);
		},
		success: function(data) {
			$("#showfilterdata").show();
			let no = 1;
			if (data != null) {
				if ($.fn.DataTable.isDataTable('#tablelistdatadetailview')) {
					$('#tablelistdatadetailview').DataTable().destroy();
				}
				$('#tablelistdatadetailview > tbody').empty();
				$.each(data, function(i, v) {
					$("#tablelistdatadetailview > tbody").append(`
              <tr>
                  <td class="text-center">${no++}</td>
                  <td class="text-center">${v.sku_kode}</td>
                  <td>${v.sku_nama}</td>
                  <td class="text-center">${v.sku_kemasan}</td>
                  <td class="text-center">${v.sku_satuan}</td>
                  <td class="text-center">${v.batch_no == null ? '' : v.batch_no}</td>
                  <td class="text-center">${v.sku_exp_date}</td>
                  <td class="text-center">${v.jml_barang}</td>
                  <td class="text-center">${v.jml_terima}</td>
              </tr>
          `);
				});
			} else {
				$("#tablelistdatadetailview > tbody").html(`<tr><td colspan="9" class="text-center text-danger"><label name="CAPTION-DATAKOSONG">Data Kosong</label></td></tr>`);
			}

			$('#tablelistdatadetailview').DataTable({
				lengthMenu: [
					[10, 25, 50, 100, -1],
					[10, 25, 50, 100, 'All']
				],
			});
		}
	});


	//get data pallet

	$.ajax({
		url: "<?= base_url('WMS/PenerimaanBarang/getDataPalletPb') ?>",
		type: "POST",
		data: {
			id: global_id,
		},
		dataType: "JSON",
		success: function(response) {
			$("#data_pallet_view > tbody").empty();
			if (response.length > 0) {
				$.each(response, function(i, v) {
					$("#data_pallet_view > tbody").append(`
                  <tr>
                      <td>
                          <input type="text" class="form-control" value="${v.kode}" readonly/>
                          <input type="hidden" class="form-control" name="id_pallet[]" id="id_pallet" value="` + v.id + `"/>
                      </td>
                      <td>
                          <select class="form-control select2 jenis_palet" id="jenis_palet" name="jenis_palet" disabled>
                            <option value="">${v.jenis}</option>
                          </select>
                      </td>
                      <td>
                          <select class="form-control select2 gudang" id="gudang" name="gudang" disabled>
                            <option value="">${v.gudang}</option>
                          </select>
                      </td>
                      <td class="text-center">
                          <button type="button" data-toggle="tooltip" data-placement="top" title="detail pallet" data-id="` + v.id + `" data-kode="${v.kode}" class="detail_pallet_view" style="border:none;background:transparent"><i class="fas fa-eye text-primary" style="cursor: pointer"></i></button>

                          <button type='button' data-id='${v.id}' class='btn btn-success btnprint' data-href='<?= base_url("WMS/PenerimaanBarang/print") ?>' style="border:none;background:transparent"><i class='fas fa-print text-success'></i></button>
                      </td>
                  </tr>
              `);
				});
			}

			let tot_pallet = $("#data_pallet_view > tbody tr").length;
			$("#data_pallet_view > tfoot tr #total_pallet_view").html("<strong><h4>Total " + tot_pallet + " Pallet</h4></strong>");

			setTimeout(() => {
				$(".detail_pallet_view").first().click();
			}, 1000);
		}
	})

	$(document).on("click", ".detail_pallet_view", function() {
		let id = $(this).attr('data-id');
		let kode = $(this).attr('data-kode');
		$('#show_isi_sku_pallet_view').fadeOut("slow", function() {
			$(this).hide();
			$("#list_detail_pallet_view > tbody").html(`<tr><td colspan="7" class="text-center"><span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span></td></tr>`);
		}).fadeIn("slow", function() {
			$(this).show();
			append_data(id, kode);
			count_tot_detail_pallet();
		});
	});

	function append_data(pallet_id, kode) {
		$.ajax({
			url: "<?= base_url('WMS/PenerimaanBarang/get_surat_jalan_detail_by_arrId_view'); ?>",
			type: "POST",
			data: {
				id: pallet_id
			},
			dataType: "JSON",
			success: function(data) {
				$(".kode_pallet_view").empty();
				$(".kode_pallet_view").append(kode)
				$("#list_detail_pallet_view > tbody").empty();
				if (data.length != 0) {
					$.each(data, function(i, v) {
						$("#list_detail_pallet_view > tbody").append(`
              <tr>
                  <td class="text-center">${v.principle}</td>
                  <td class="text-center">${v.sku_kode}</td>
                  <td>${v.sku_nama_produk}</td>
                  <td class="text-center">${v.sku_kemasan}</td>
                  <td class="text-center">${v.sku_satuan}</td>
                  <td>
                      <input type="date" class="form-control" id="exp_date" name="exp_date" value="${v.expired_date}" readonly/>
                  </td>
                  <td>
                      <input type="text" class="form-control qty_detail_pallet numeric" id="qty_detail_pallet" name="qty_detail_pallet" value="${v.qty}" readonly/>
                  </td>
              </tr>
          `);
					});
				} else {
					$("#list_detail_pallet_view > tbody").html(`<tr><td colspan="7" class="text-center text-danger"><label name="CAPTION-DATAKOSONG">Data Kosong</label></td></tr>`);
				}
				select2();
				count_tot_detail_pallet();
			}
		});
	}

	function count_tot_detail_pallet() {
		let no = 0;
		$("#list_detail_pallet_view > tbody tr").each(function(i, v) {
			let first_element = $(this).find("td:eq(0)");
			if (first_element.text() != "Data Kosong") {
				no++;
			} else {
				no = 0;
			}
		});
		$("#list_detail_pallet_view > tfoot tr #total_detail_pallet_view").html("<strong><h4>Total " + no + " SKU</h4></strong>");
	}

	$(document).on("click", "#kembali_view", function() {
		setTimeout(() => {
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-LOADING">Loading</label>...</span>',
				showConfirmButton: false,
				timer: 500
			});
			location.href = "<?= base_url('WMS/PenerimaanBarang/PenerimaanBarangMenu') ?>";
		}, 500);
	});


	$(document).on("click", ".btnprint", function() {
		let href = $(this).attr("data-href");
		let id = $(this).attr("data-id");
		window.open(href + "?id=" + id + "&tipe=single", '_blank');
	});

	$(document).on("click", ".btn_close_pilih_checker", function() {
		$("#list-data-checker").modal('hide');
	});
</script>