<script type="text/javascript">
	var jumlah_sku = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_header = [];
	let arr_detail = [];
	var cek_qty = 0;
	let cek_tipe_stock = 0;
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2();
			initDataCustomer();
		}
	);

	function message_custom(titleType, iconType, htmlType) {
		Swal.fire({
			title: titleType,
			icon: iconType,
			html: htmlType,
		})
	}

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

	function getCustomer() {
		$("#cek_customer").val(0);

		$(".factory-name").html('');
		$(".factory-address").html('');
		$(".factory-area").html('');

		$("#DeliveryOrder-delivery_order_ambil_nama").val('');
		$("#DeliveryOrder-delivery_order_ambil_alamat").val('');
		$("#DeliveryOrder-delivery_order_ambil_provinsi").val('');
		$("#DeliveryOrder-delivery_order_ambil_kota").val('');
		$("#DeliveryOrder-delivery_order_ambil_kecamatan").val('');
		$("#DeliveryOrder-delivery_order_ambil_kelurahan").val('');
		$("#DeliveryOrder-delivery_order_ambil_kodepos").val('');
		$("#DeliveryOrder-delivery_order_ambil_telepon").val('');
		$("#DeliveryOrder-delivery_order_ambil_area").val('');

		$(".customer-name").html('');
		$(".customer-address").html('');
		$(".customer-area").html('');

		$("#DeliveryOrder-delivery_order_kirim_nama").val('');
		$("#DeliveryOrder-delivery_order_kirim_alamat").val('');
		$("#DeliveryOrder-delivery_order_kirim_provinsi").val('');
		$("#DeliveryOrder-delivery_order_kirim_kota").val('');
		$("#DeliveryOrder-delivery_order_kirim_kecamatan").val('');
		$("#DeliveryOrder-delivery_order_kirim_kelurahan").val('');
		$("#DeliveryOrder-delivery_order_kirim_kodepos").val('');
		$("#DeliveryOrder-delivery_order_kirim_telepon").val('');
		$("#DeliveryOrder-delivery_order_kirim_area").val('');

		$("#modal-factory").modal('hide');
		$("#modal-customer").modal('hide');

		initDataCustomer();
	}

	// $("#btn-choose-prod-delivery").on("click", function() {
	// 	
	// });

	$("#btn-search-customer").on("click", function() {
		getCustomer();
	});

	function getSelectedCustomer(customer, tipe_layanan) {

		var perusahaan = $("#DeliveryOrder-client_wms_id").val();

		$(".factory-name").html('');
		$(".factory-address").html('');
		$(".factory-area").html('');

		$("#DeliveryOrder-delivery_order_ambil_nama").val('');
		$("#DeliveryOrder-delivery_order_ambil_alamat").val('');
		$("#DeliveryOrder-delivery_order_ambil_provinsi").val('');
		$("#DeliveryOrder-delivery_order_ambil_kota").val('');
		$("#DeliveryOrder-delivery_order_ambil_kecamatan").val('');
		$("#DeliveryOrder-delivery_order_ambil_kelurahan").val('');
		$("#DeliveryOrder-delivery_order_ambil_kodepos").val('');
		$("#DeliveryOrder-delivery_order_ambil_telepon").val('');
		$("#DeliveryOrder-delivery_order_ambil_area").val('');

		$(".customer-name").html('');
		$(".customer-address").html('');
		$(".customer-area").html('');

		$("#DeliveryOrder-delivery_order_kirim_nama").val('');
		$("#DeliveryOrder-delivery_order_kirim_alamat").val('');
		$("#DeliveryOrder-delivery_order_kirim_provinsi").val('');
		$("#DeliveryOrder-delivery_order_kirim_kota").val('');
		$("#DeliveryOrder-delivery_order_kirim_kecamatan").val('');
		$("#DeliveryOrder-delivery_order_kirim_kelurahan").val('');
		$("#DeliveryOrder-delivery_order_kirim_kodepos").val('');
		$("#DeliveryOrder-delivery_order_kirim_telepon").val('');
		$("#DeliveryOrder-delivery_order_kirim_area").val('');

		$("#modal-factory").modal('hide');
		$("#modal-customer").modal('hide');

		$("#cek_customer").val(0);

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetSelectedCustomer') ?>",
			data: {
				customer: customer,
				perusahaan: perusahaan
			},
			dataType: "JSON",
			success: function(response) {
				$('#panel-customer').fadeOut("slow", function() {
					$(this).hide();
				}).fadeIn("slow", function() {
					$.each(response, function(i, v) {
						$(".customer-name").append(v.client_pt_nama);
						$(".customer-address").append(v.client_pt_alamat);
						$(".customer-area").append(v.area_nama);

						$("#DeliveryOrder-client_pt_id").val(v.client_pt_id);
						$("#DeliveryOrder-delivery_order_kirim_nama").val(v.client_pt_nama);
						$("#DeliveryOrder-delivery_order_kirim_nama").val(v.client_pt_nama);
						$("#DeliveryOrder-delivery_order_kirim_alamat").val(v.client_pt_alamat);
						$("#DeliveryOrder-delivery_order_kirim_provinsi").val(v.client_pt_propinsi);
						$("#DeliveryOrder-delivery_order_kirim_kota").val(v.client_pt_kota);
						$("#DeliveryOrder-delivery_order_kirim_kecamatan").val(v.client_pt_kecamatan);
						$("#DeliveryOrder-delivery_order_kirim_kelurahan").val(v.client_pt_kelurahan);
						$("#DeliveryOrder-delivery_order_kirim_kodepos").val(v.client_pt_kodepos);
						$("#DeliveryOrder-delivery_order_kirim_telepon").val(v.client_pt_telepon);
						$("#DeliveryOrder-delivery_order_kirim_area").val(v.area_nama);

					});

					if ($("#DeliveryOrder-client_pt_id").val() != "") {
						$("#cek_customer").val(1);
					}

				});
			}
		});
	}

	function initDataCustomer() {
		var perusahaan = $("#DeliveryOrder-client_wms_id").val();
		var tipe_layanan = document.querySelector('input[id="DeliveryOrder-delivery_order_tipe_layanan"]:checked').value;
		var tipe_pembayaran = document.querySelector('input[id="DeliveryOrder-delivery_order_tipe_pembayaran"]:checked').value;

		$("#panel-customer").show();
		$("#panel-factory").hide();

		var nama = $("#filter-client-name").val();
		var alamat = $("#filter-client-address").val();
		var telp = $("#filter-client-phone").val();
		var area = $("#filter-area").val();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetCustomerByTypePelayanan') ?>",
			data: {
				perusahaan: perusahaan,
				tipe_pembayaran: tipe_pembayaran,
				tipe_layanan: tipe_layanan,
				nama: nama,
				alamat: alamat,
				telp: telp,
				area: area
			},
			dataType: "JSON",
			success: function(response) {

				$("#table-customer > tbody").empty();

				if (response != 0) {
					$.each(response, function(i, v) {

						$("#table-customer > tbody").append(`
								<tr id="row-${i}">
									<td style="display: none">
										<input type="hidden" id="item-${i}-DeliveryOrder-client_pt_id" value="${v.client_pt_id}" />
										<input type="hidden" id="item-${i}-DeliveryOrder-client_pt_propinsi" value="${v.client_pt_propinsi}" />
										<input type="hidden" id="item-${i}-DeliveryOrder-client_pt_kota" value="${v.client_pt_kota}" />
										<input type="hidden" id="item-${i}-DeliveryOrder-client_pt_kecamatan" value="${v.client_pt_kecamatan}" />
										<input type="hidden" id="item-${i}-DeliveryOrder-client_pt_kelurahan" value="${v.client_pt_kelurahan}" />
										<input type="hidden" id="item-${i}-DeliveryOrder-client_pt_kodepos" value="${v.client_pt_kodepos}" />
										<input type="hidden" id="item-${i}-DeliveryOrder-area_id" value="${v.area_id}" />
									</td>
									<td class="text-center">
										<span class="client-pt-nama-label">${v.client_pt_nama}</span>
										<input type="hidden" id="item-${i}-DeliveryOrder-client_pt_nama" class="form-control client-pt-nama" value="${v.client_pt_nama}" />
									</td>
									<td class="text-center">
										<span class="client-pt-alamat-label">${v.client_pt_alamat}</span>
										<input type="hidden" id="item-${i}-DeliveryOrder-client_pt_alamat" class="form-control client-pt-alamat" value="${v.client_pt_alamat}" />
									</td>
									<td class="text-center">
										<span class="client-pt-telepon-label">${v.client_pt_telepon}</span>
										<input type="hidden" id="item-${i}-DeliveryOrder-client_pt_telepon" class="form-control client-pt-telepon" value="${v.client_pt_telepon}" />
									</td>
									<td class="text-center">
										<span class="area-nama-label">${v.area_nama}</span>
										<input type="hidden" id="item-${i}-DeliveryOrder-area_nama" class="form-control area-nama" value="${v.area_nama}" />
									</td>
									<td class="text-center">
										<button class="btn btn-primary btn-small btn-select-customer" onclick="getSelectedCustomer('${v.client_pt_id}','${tipe_layanan}')"><i class="fa fa-angle-down"></i></button>
									</td>
								</tr>
							`);
					});
				} else {
					$("#table-customer > tbody").append(`
								<tr>
									<td colspan="5" class="text-danger text-center">
										Data Kosong
									</td>
								</tr>
						`);
				}
			}
		});
	}

	$("#DeliveryOrder-tipe_delivery_order_id").on("change", function() {
		if ($("#DeliveryOrder-tipe_delivery_order_id").val() == "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {
			$("#DeliveryOrder-delivery_order_status").val('retur');
		} else {
			$("#DeliveryOrder-delivery_order_status").val('delivered');
		}
	});

	$("#btnsavedo").on("click", function() {
		var cek_customer = $("#cek_customer").val();
		var tipe_do = $("#DeliveryOrder-tipe_delivery_order_id").val();

		// console.log(arr_sku);

		if (tipe_do != "") {

			if (cek_customer > 0) {
				$("#table-sku-delivery-only > tbody tr").each(function() {
					var is_Qty = $(this).find("td:eq(11) input[type='number']");
					var is_tipe_stock = $(this).find("td:eq(6) select");
					console.log(is_tipe_stock.val());
					if (is_Qty.val() == 0) {
						cek_qty++;
					}

					if (is_tipe_stock.val() == 0) {
						cek_tipe_stock++;
					}
				});

				if (cek_tipe_stock == 0) {
					if (cek_qty == 0) {
						arr_header = [];
						arr_detail = [];

						for (var index = 0; index < arr_sku.length; index++) {
							if (arr_sku[index] != "") {
								arr_detail.push({
									'sku_id': $("#item-" + index + "-DeliveryOrderDetail-sku_id").val(),
									'depo_id': $("#item-" + index + "-DeliveryOrderDetail-depo_id").val(),
									'depo_detail_id': $("#item-" + index + "-DeliveryOrderDetail-depo_detail_id").val(),
									'sku_kode': $("#item-" + index + "-DeliveryOrderDetail-sku_kode").val(),
									'sku_nama_produk': $("#item-" + index + "-DeliveryOrderDetail-sku_nama_produk").val(),
									'sku_harga_satuan': $("#item-" + index + "-DeliveryOrderDetail-sku_harga_satuan").val(),
									'sku_disc_percent': $("#item-" + index + "-DeliveryOrderDetail-sku_disc_percent").val(),
									'sku_disc_rp': $("#item-" + index + "-DeliveryOrderDetail-sku_disc_rp").val(),
									'sku_harga_nett': $("#item-" + index + "-DeliveryOrderDetail-sku_harga_nett").val(),
									'sku_request_expdate': $("#item-" + index + "-DeliveryOrderDetail-sku_request_expdate").val(),
									'sku_filter_expdate': $("#item-" + index + "-DeliveryOrderDetail-sku_filter_expdate").val(),
									'sku_filter_expdatebulan': $("#item-" + index + "-DeliveryOrderDetail-sku_filter_expdatebulan").val(),
									'sku_filter_expdatetahun': "",
									'sku_weight': $("#item-" + index + "-DeliveryOrderDetail-sku_weight").val(),
									'sku_weight_unit': $("#item-" + index + "-DeliveryOrderDetail-sku_weight_unit").val(),
									'sku_length': $("#item-" + index + "-DeliveryOrderDetail-sku_length").val(),
									'sku_length_unit': $("#item-" + index + "-DeliveryOrderDetail-sku_length_unit").val(),
									'sku_width': $("#item-" + index + "-DeliveryOrderDetail-sku_width").val(),
									'sku_width_unit': $("#item-" + index + "-DeliveryOrderDetail-sku_width_unit").val(),
									'sku_height': $("#item-" + index + "-DeliveryOrderDetail-sku_height").val(),
									'sku_height_unit': $("#item-" + index + "-DeliveryOrderDetail-sku_height_unit").val(),
									'sku_volume': $("#item-" + index + "-DeliveryOrderDetail-sku_volume").val(),
									'sku_volume_unit': $("#item-" + index + "-DeliveryOrderDetail-sku_volume_unit").val(),
									'sku_qty': $("#item-" + index + "-DeliveryOrderDetail-sku_qty").val(),
									'sku_keterangan': $("#item-" + index + "-DeliveryOrderDetail-sku_keterangan").val(),
									'sku_qty_kirim': $("#item-" + index + "-DeliveryOrderDetail-sku_qty").val(),
									'reason_id': $("#item-" + index + "-DeliveryOrderDetail-reason_id").val(),
									'tipe_stock_nama': $("#item-" + index + "-DeliveryOrderDetail-sku_tipe_stock").val()
								});
							}
						}

						Swal.fire({
							title: "Apakah anda yakin?",
							text: "Pastikan data yang sudah anda input benar!",
							icon: "warning",
							showCancelButton: true,
							confirmButtonColor: "#3085d6",
							cancelButtonColor: "#d33",
							confirmButtonText: "Ya, Simpan",
							cancelButtonText: "Tidak, Tutup"
						}).then((result) => {
							if (result.value == true) {
								console.log(arr_detail);
								//ajax save data
								$.ajax({
									async: false,
									url: "<?= base_url('WMS/SettlementPengiriman/insert_delivery_order'); ?>",
									type: "POST",
									data: {
										delivery_order_id: "",
										delivery_order_batch_id: $('#DeliveryOrder-delivery_order_batch_id').val(),
										sales_order_id: "",
										delivery_order_kode: "",
										delivery_order_yourref: "",
										client_wms_id: $('#DeliveryOrder-client_wms_id').val(),
										delivery_order_tgl_buat_do: $('#DeliveryOrder-delivery_order_tgl_buat_do').val(),
										delivery_order_tgl_expired_do: $('#DeliveryOrder-delivery_order_tgl_expired_do').val(),
										delivery_order_tgl_surat_jalan: $('#DeliveryOrder-delivery_order_tgl_surat_jalan').val(),
										delivery_order_tgl_rencana_kirim: $('#DeliveryOrder-delivery_order_tgl_rencana_kirim').val(),
										delivery_order_tgl_aktual_kirim: $('#DeliveryOrder-delivery_order_tgl_aktual_kirim').val(),
										delivery_order_keterangan: $('#DeliveryOrder-delivery_order_keterangan').val(),
										delivery_order_status: $('#DeliveryOrder-delivery_order_status').val(),
										delivery_order_is_prioritas: "",
										delivery_order_is_need_packing: "",
										delivery_order_tipe_layanan: document.querySelector('input[id="DeliveryOrder-delivery_order_tipe_layanan"]:checked').value,
										delivery_order_tipe_pembayaran: document.querySelector('input[id="DeliveryOrder-delivery_order_tipe_pembayaran"]:checked').value,
										delivery_order_sesi_pengiriman: "",
										delivery_order_request_tgl_kirim: "",
										delivery_order_request_jam_kirim: "",
										tipe_pengiriman_id: "",
										nama_tipe: "",
										confirm_rate: "",
										delivery_order_reff_id: "",
										delivery_order_reff_no: "",
										delivery_order_total: $('#DeliveryOrder-delivery_order_total').val(),
										unit_mandiri_id: "<?= $this->session->userdata('unit_mandiri_id') ?>",
										depo_id: "<?= $this->session->userdata('depo_id') ?>",
										client_pt_id: $('#DeliveryOrder-client_pt_id').val(),
										delivery_order_kirim_nama: $('#DeliveryOrder-delivery_order_kirim_nama').val(),
										delivery_order_kirim_alamat: $('#DeliveryOrder-delivery_order_kirim_alamat').val(),
										delivery_order_kirim_telp: $('#DeliveryOrder-delivery_order_kirim_telp').val(),
										delivery_order_kirim_provinsi: $('#DeliveryOrder-delivery_order_kirim_provinsi').val(),
										delivery_order_kirim_kota: $('#DeliveryOrder-delivery_order_kirim_kota').val(),
										delivery_order_kirim_kecamatan: $('#DeliveryOrder-delivery_order_kirim_kecamatan').val(),
										delivery_order_kirim_kelurahan: $('#DeliveryOrder-delivery_order_kirim_kelurahan').val(),
										delivery_order_kirim_latitude: $('#DeliveryOrder-delivery_order_kirim_latitude').val(),
										delivery_order_kirim_longitude: $('#DeliveryOrder-delivery_order_kirim_longitude').val(),
										delivery_order_kirim_kodepos: $('#DeliveryOrder-delivery_order_kirim_kodepos').val(),
										delivery_order_kirim_area: $('#DeliveryOrder-delivery_order_kirim_area').val(),
										delivery_order_kirim_invoice_pdf: "",
										delivery_order_kirim_invoice_dir: "",
										principle_id: "",
										delivery_order_ambil_nama: "",
										delivery_order_ambil_alamat: "",
										delivery_order_ambil_telp: "",
										delivery_order_ambil_provinsi: "",
										delivery_order_ambil_kota: "",
										delivery_order_ambil_kecamatan: "",
										delivery_order_ambil_kelurahan: "",
										delivery_order_ambil_latitude: "",
										delivery_order_ambil_longitude: "",
										delivery_order_ambil_kodepos: "",
										delivery_order_ambil_area: "",
										delivery_order_update_who: "",
										delivery_order_update_tgl: "",
										delivery_order_approve_who: "",
										delivery_order_approve_tgl: "",
										delivery_order_reject_who: "",
										delivery_order_reject_tgl: "",
										delivery_order_reject_reason: "",
										delivery_order_no_urut_rute: "",
										delivery_order_prioritas_stock: "",
										tipe_delivery_order_id: $('#DeliveryOrder-tipe_delivery_order_id').val(),
										delivery_order_draft_id: "",
										delivery_order_draft_kode: "",
										is_ada_titipan: "",
										detail: arr_detail
									},
									dataType: "JSON",
									success: function(data) {
										if (data == 1) {
											message_topright("success", "Data berhasil disimpan");
											setTimeout(() => {
												location.href = "<?= base_url(); ?>WMS/SettlementPengiriman/SettlementMenu/?delivery_order_batch_id=" + $('#DeliveryOrder-delivery_order_batch_id').val();
												// location.reload();
											}, 500);
										} else {
											message_topright("error", "Data gagal disimpan");
										}
									}
								});
							}
						});

						// console.log(arr_header);
						// console.log(arr_detail);
					} else {
						cek_qty = 0;
						message("Error!", "Qty tidak boleh 0!", "error");
					}
				} else {
					cek_tipe_stock = 0;
					message("Error!", "Tipe Stock tidak boleh kosong!", "error");

				}
			} else {
				message("Pilih Customer!", "Customer belum dipilih", "error");

			}

		} else {
			message("Pilih Tipe DO!", "Tipe delivery order draft belum dipilih", "error");
		}
	});

	function DeleteSKU(row, index) {
		var row = row.parentNode.parentNode;
		row.parentNode.removeChild(row);

		arr_sku[index] = "";
	}

	function reset_table_sku() {
		$("#table-sku-delivery-only > tbody").empty();

	}

	function reqFilter(val, i) {
		if (val == 1) {
			// $("#item-" + i + "-DeliveryOrder-sku_filter_expdate").prop('disabled', false);
			$("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdatebulan").prop('disabled', false);

			$("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdate").append("<option value='>=' selected>=></option>").change();
		} else {
			$("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdate").val(0).change();
			$("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdatebulan").val(0).change();

			// $("#item-" + i + "-DeliveryOrder-sku_filter_expdate").prop('disabled', true);
			$("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdatebulan").prop('disabled', true);
		}

	}

	$(document).on("click", ".btn-choose-sku-multi", function() {
		var jumlah = $('input[name="CheckboxSKU"]').length;
		var numberOfChecked = $('input[name="CheckboxSKU"]:checked').length;
		var no = 1;
		jumlah_sku = numberOfChecked;

		arr_sku = [];

		if (numberOfChecked > 0) {
			for (var i = 0; i < jumlah; i++) {
				var checked = $('[id="check-sku-' + i + '"]:checked').length;
				var sku_id = "'" + $("#check-sku-" + i).val() + "'";

				if (checked > 0) {
					arr_sku.push(sku_id);
				}
			}

			$("#table-sku-delivery-only > tbody").empty();

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetSelectedSKU') ?>",
				data: {
					sku_id: arr_sku
				},
				dataType: "JSON",
				success: function(response) {
					$.each(response, function(i, v) {
						$("#table-sku-delivery-only > tbody").append(`
							<tr id="row-${i}">
								<td style="display: none">
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_id" value="${v.sku_id}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-depo_id" value="<?= $this->session->userdata('depo_id'); ?>" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-depo_detail_id" value="" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_kode" value="${v.sku_kode}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_nama_produk" value="${v.sku_nama_produk}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_harga_satuan" value="${v.sku_harga_jual}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_disc_percent" value="0" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_disc_rp" value="0" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_harga_nett" value="${v.sku_harga_jual}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_kemasan" value="${v.sku_kemasan}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_satuan" value="${v.sku_satuan}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_weight" value="${v.sku_weight}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_weight_unit" value="${v.sku_weight_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_length" value="${v.sku_length}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_length_unit" value="${v.sku_length_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_width" value="${v.sku_width}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_width_unit" value="${v.sku_width_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_height" value="${v.sku_height}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_height_unit" value="${v.sku_height_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_volume" value="${v.sku_volume}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_volume_unit" value="${v.sku_volume_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-reason_id" value="" />
								</td>
								<td class="text-center">
									<span class="sku-kode-label">${v.sku_kode}</span>
								</td>
								<td class="text-center" style="display: none"></td>
								<td class="text-center">
									<span class="sku-nama-produk-label">${v.sku_nama_produk}</span>
								</td>
								<td class="text-center">
									<span class="sku-kemasan-label">${v.sku_kemasan}</span>
								</td>
								<td class="text-center">
									<span class="sku-satuan-label">${v.sku_satuan}</span>
								</td>
								<td class="text-center" style="width:10%">
									<select id="item-${i}-DeliveryOrderDetail-sku_tipe_stock" class="form-control sku-tipe_stock">
									<option value="">**Pilih**</option>
									<?php if (isset($Lokasi)) { ?>
										<?php for ($x = 0; $x < count($Lokasi); $x++) { ?>
											<option value="<?= $Lokasi[$x]->nama ?>"><?= $Lokasi[$x]->nama ?></option>
										<?php } ?>
									<?php } ?>
									</select>
								</td>
								<td class="text-center" style="width:10%">
									<select id="item-${i}-DeliveryOrderDetail-sku_request_expdate" class="form-control sku-request-expdate" onchange="reqFilter(this.value,'${i}')">
										<option value="0">Tidak</option>
										<option value="1">Ya</option>
									</select>
								</td>
								<td class="text-center" style="width:10%">
									<select id="item-${i}-DeliveryOrderDetail-sku_filter_expdate" class="form-control sku-filter-expdate" disabled>
										<option value="">**Pilih**</option>
									</select>
								</td>
								<td class="text-center" style="width:10%">
									<select id="item-${i}-DeliveryOrderDetail-sku_filter_expdatebulan" class="form-control sku-filter-expdatebulan" disabled>
									<option value="">**Pilih**</option>
									<?php if (isset($Bulan)) { ?>
									<?php for ($x = 0; $x < count($Bulan); $x++) { ?>
										<option value="<?= $Bulan[$x] ?>"><?= $Bulan[$x] ?></option>
									<?php } ?>
									<?php } ?>
									</select>
								</td>
								<td class="text-center"><input type="text" id="item-${i}-DeliveryOrderDetail-sku_keterangan" class="form-control sku-keterangan" value="" /></td>
								<td class="text-center"><input type="number" id="item-${i}-DeliveryOrderDetail-sku_qty" class="form-control sku-qty" value="0" /></td>
								<td class="text-center">
									<button class="btn btn-danger btn-small btn-delete-sku" onclick="DeleteSKU(this,${i})"><i class="fa fa-trash"></i></button>
								</td>
							</tr>
						`);
					});
				}
			});

		} else {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'Pilih SKU!'
			});
		}
	});

	function reqFilter(val, i) {
		if (val == 1) {
			// $("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdate").prop('disabled', false);
			$("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdatebulan").prop('disabled', false);

			$("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdate").append("<option value='>=' selected>=></option>").change();
		} else {
			$("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdate").val(0).change();
			$("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdatebulan").val(0).change();

			// $("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdate").prop('disabled', true);
			$("#item-" + i + "-DeliveryOrderDetail-sku_filter_expdatebulan").prop('disabled', true);
		}

	}
</script>