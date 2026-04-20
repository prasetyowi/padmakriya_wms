<script type="text/javascript">
	var jumlah_sku = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_header = [];
	let arr_detail = [];
	var cek_qty = 0;
	let cek_tipe_stock = 0;
	var delivery_order_batch_id = $('#deliveryorder-delivery_order_batch_id').val();
	loadingBeforeReadyPage()
	$(document).ready(
		function() {
			$('.select2').select2();
			if ($('#filter-do-date').length > 0) {
				$('#filter-do-date').daterangepicker({
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
			}

			if ($("#deliveryorder-client_wms_id").val() != "") {
				initDataCustomer();
				initDataSKU();
			}
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

	$('#select-sku').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxSKU"]:checkbox').each(function() {
				this.checked = false;
			});
		}
	});

	function getCustomer() {
		$("#cek_customer").val(0);

		$(".factory-name").html('');
		$(".factory-address").html('');
		$(".factory-area").html('');

		$("#deliveryorder-delivery_order_ambil_nama").val('');
		$("#deliveryorder-delivery_order_ambil_alamat").val('');
		$("#deliveryorder-delivery_order_ambil_provinsi").val('');
		$("#deliveryorder-delivery_order_ambil_kota").val('');
		$("#deliveryorder-delivery_order_ambil_kecamatan").val('');
		$("#deliveryorder-delivery_order_ambil_kelurahan").val('');
		$("#deliveryorder-delivery_order_ambil_kodepos").val('');
		$("#deliveryorder-delivery_order_ambil_telepon").val('');
		$("#deliveryorder-delivery_order_ambil_area").val('');

		$(".customer-name").html('');
		$(".customer-address").html('');
		$(".customer-area").html('');

		$("#deliveryorder-delivery_order_kirim_nama").val('');
		$("#deliveryorder-delivery_order_kirim_alamat").val('');
		$("#deliveryorder-delivery_order_kirim_provinsi").val('');
		$("#deliveryorder-delivery_order_kirim_kota").val('');
		$("#deliveryorder-delivery_order_kirim_kecamatan").val('');
		$("#deliveryorder-delivery_order_kirim_kelurahan").val('');
		$("#deliveryorder-delivery_order_kirim_kodepos").val('');
		$("#deliveryorder-delivery_order_kirim_telepon").val('');
		$("#deliveryorder-delivery_order_kirim_area").val('');

		$("#modal-factory").modal('hide');
		$("#modal-customer").modal('hide');

		initDataCustomer();
		reset_table_sku();
		initDataSKU();
	}

	// $("#btn-choose-prod-delivery").on("click", function() {
	// 	initDataSKU();
	// });

	$("#btn-search-sku").on("click", function() {
		initDataSKU();
	});

	$("#btn-search-customer").on("click", function() {
		getCustomer();
	});

	$("#btn-search-factory").on("click", function() {
		getCustomer();
	});

	$("#deliveryorder-client_wms_id").on("change", function() {
		var perusahaan = $("#deliveryorder-client_wms_id").val();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/SuratTugasPengiriman/GetPerusahaanById') ?>",
			data: {
				id: perusahaan
			},
			dataType: "JSON",
			success: function(response) {
				$("#deliveryorder-client_wms_alamat").val('');

				if (response != 0) {
					$.each(response, function(i, v) {
						$("#deliveryorder-client_wms_alamat").val(v.client_wms_alamat);
					});
				} else {
					$("#deliveryorder-client_wms_alamat").val('');
				}
			}
		});

	});

	function getSelectedCustomer(customer, tipe_layanan) {

		var perusahaan = $("#deliveryorder-client_wms_id").val();

		$(".factory-name").html('');
		$(".factory-address").html('');
		$(".factory-area").html('');

		$("#deliveryorder-delivery_order_ambil_nama").val('');
		$("#deliveryorder-delivery_order_ambil_alamat").val('');
		$("#deliveryorder-delivery_order_ambil_provinsi").val('');
		$("#deliveryorder-delivery_order_ambil_kota").val('');
		$("#deliveryorder-delivery_order_ambil_kecamatan").val('');
		$("#deliveryorder-delivery_order_ambil_kelurahan").val('');
		$("#deliveryorder-delivery_order_ambil_kodepos").val('');
		$("#deliveryorder-delivery_order_ambil_telepon").val('');
		$("#deliveryorder-delivery_order_ambil_area").val('');

		$(".customer-name").html('');
		$(".customer-address").html('');
		$(".customer-area").html('');

		$("#deliveryorder-delivery_order_kirim_nama").val('');
		$("#deliveryorder-delivery_order_kirim_alamat").val('');
		$("#deliveryorder-delivery_order_kirim_provinsi").val('');
		$("#deliveryorder-delivery_order_kirim_kota").val('');
		$("#deliveryorder-delivery_order_kirim_kecamatan").val('');
		$("#deliveryorder-delivery_order_kirim_kelurahan").val('');
		$("#deliveryorder-delivery_order_kirim_kodepos").val('');
		$("#deliveryorder-delivery_order_kirim_telepon").val('');
		$("#deliveryorder-delivery_order_kirim_area").val('');

		$("#modal-factory").modal('hide');
		$("#modal-customer").modal('hide');

		$("#cek_customer").val(0);

		$("#table-sku-delivery-only > tbody").empty();

		if (tipe_layanan == "Pickup Only") {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/SuratTugasPengiriman/GetSelectedPrinciple') ?>",
				data: {
					customer: customer,
					perusahaan: perusahaan
				},
				dataType: "JSON",
				success: function(response) {

					$('#panel-factory').fadeOut("slow", function() {
						$(this).hide();
					}).fadeIn("slow", function() {
						$.each(response, function(i, v) {
							$(this).show();
							$(".factory-name").append(v.principle_nama);
							$(".factory-address").append(v.principle_alamat);
							$(".factory-area").append(v.area_nama);

							$("#deliveryorder-principle_id").val(v.principle_id);
							$("#deliveryorder-delivery_order_ambil_nama").val(v.principle_nama);
							$("#deliveryorder-delivery_order_ambil_alamat").val(v.principle_alamat);
							$("#deliveryorder-delivery_order_ambil_provinsi").val(v.principle_propinsi);
							$("#deliveryorder-delivery_order_ambil_kota").val(v.principle_kota);
							$("#deliveryorder-delivery_order_ambil_kecamatan").val(v.principle_kecamatan);
							$("#deliveryorder-delivery_order_ambil_kelurahan").val(v.principle_kelurahan);
							$("#deliveryorder-delivery_order_ambil_kodepos").val(v.principle_kodepos);
							$("#deliveryorder-delivery_order_ambil_telepon").val(v.principle_telepon);
							$("#deliveryorder-delivery_order_ambil_area").val(v.area_nama);
						});
						arr_sku = [];
						$("#table-sku-delivery-only > tbody").empty();
						initDataSKU();

						if ($("#deliveryorder-principle_id").val() != "") {
							$("#cek_customer").val(1);
						}
					});
				}
			});
		} else {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/SuratTugasPengiriman/GetSelectedCustomer') ?>",
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

							$("#deliveryorder-client_pt_id").val(v.client_pt_id);
							$("#deliveryorder-delivery_order_kirim_nama").val(v.client_pt_nama);
							$("#deliveryorder-delivery_order_kirim_nama").val(v.client_pt_nama);
							$("#deliveryorder-delivery_order_kirim_alamat").val(v.client_pt_alamat);
							$("#deliveryorder-delivery_order_kirim_provinsi").val(v.client_pt_propinsi);
							$("#deliveryorder-delivery_order_kirim_kota").val(v.client_pt_kota);
							$("#deliveryorder-delivery_order_kirim_kecamatan").val(v.client_pt_kecamatan);
							$("#deliveryorder-delivery_order_kirim_kelurahan").val(v.client_pt_kelurahan);
							$("#deliveryorder-delivery_order_kirim_kodepos").val(v.client_pt_kodepos);
							$("#deliveryorder-delivery_order_kirim_telepon").val(v.client_pt_telepon);
							$("#deliveryorder-delivery_order_kirim_area").val(v.area_nama);

						});

						arr_sku = [];
						$("#table-sku-delivery-only > tbody").empty();
						initDataSKU();

						if ($("#deliveryorder-client_pt_id").val() != "") {
							$("#cek_customer").val(1);
						}

					});
				}
			});

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
				url: "<?= base_url('WMS/SuratTugasPengiriman/GetSelectedSKU') ?>",
				data: {
					sku_id: arr_sku
				},
				dataType: "JSON",
				success: function(response) {
					$.each(response, function(i, v) {
						$("#table-sku-delivery-only > tbody").append(`
							<tr id="row-${i}">
								<td style="display: none">
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_id" value="${v.sku_id}" class="sku-id" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-depo_id" class="depo-id" value="${v.depo_id}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_harga_satuan" class="sku-harga-satuan" value="${v.sku_harga_jual}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_disc_percent" class="sku-disc-percent" value="0" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_disc_rp" class="sku-disc-rp" value="0" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_harga_nett" class="sku-harga-nett" value="${v.sku_harga_jual}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_weight" class="sku-weight" value="${v.sku_weight}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_weight_unit" class="sku-weight-unit" value="${v.sku_weight_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_length" class="sku-length" value="${v.sku_length}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_length_unit" class="sku-length-unit" value="${v.sku_length_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_width" class="sku-width" value="${v.sku_width}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_width_unit" class="sku-width-unit" value="${v.sku_width_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_height" class="sku-height" value="${v.sku_height}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_height_unit" class="sku-height-unit" value="${v.sku_height_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_volume" class="sku-volume" value="${v.sku_volume}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_volume_unit" class="sku-volume-unit" value="${v.sku_volume_unit}" />
								</td>
								<td class="text-center">
									<span class="sku-kode-label">${v.sku_kode}</span>
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_kode" class="form-control sku-kode" value="${v.sku_kode}" />
								</td>
								<td class="text-center" style="display: none"></td>
								<td class="text-center">
									<span class="sku-nama-produk-label">${v.sku_nama_produk}</span>
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_nama_produk" class="form-control sku-nama-produk" value="${v.sku_nama_produk}" />
								</td>
								<td class="text-center">
									<span class="sku-kemasan-label">${v.sku_kemasan}</span>
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_kemasan" class="form-control sku-kemasan" value="${v.sku_kemasan}" />
								</td>
								<td class="text-center">
									<span class="sku-satuan-label">${v.sku_satuan}</span>
									<input type="hidden" id="item-${i}-DeliveryOrderDetail-sku_satuan" class="form-control sku-satuan" value="${v.sku_satuan}" />
								</td>
								<td class="text-center" style="width:10%">
									<select id="item-${i}-DeliveryOrderDetail-tipe_stock_nama" class="form-control sku-tipe_stock">
									<option value="">**Pilih**</option>
									<?php foreach ($Lokasi as $row) : ?>
                                        <option value="<?= $row['nama'] ?>"><?= $row['nama'] ?></option>
                                    <?php endforeach; ?>
									</select>
								</td>
								<td class="text-center" style="width:10%">
									<select id="item-${i}-DeliveryOrderDetail-depo_detail_id" class="form-control select2">
									<option value="">**Pilih**</option>
									<?php foreach ($Gudang as $row) : ?>
                                        <option value="<?= $row['depo_detail_id'] ?>"><?= $row['depo_detail_nama'] ?></option>
                                    <?php endforeach; ?>
									</select>
								</td>
								<td class="text-center" style="width:10%">
									<input type="date" id="item-${i}-DeliveryOrderDetail-sku_expdate" class="form-control" autocomplete="off" value="<?= date('Y-m-d') ?>">
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

	function initDataCustomer() {
		var perusahaan = $("#deliveryorder-client_wms_id").val();
		var tipe_layanan = document.querySelector('input[id="deliveryorder-delivery_order_tipe_layanan"]:checked').value;
		var tipe_pembayaran = document.querySelector('input[id="deliveryorder-delivery_order_tipe_pembayaran"]:checked').value;

		if (tipe_layanan == "Pickup Only") {
			$("#panel-customer").hide();
			$("#panel-factory").show();

			var nama = $("#filter-principle-name").val();
			var alamat = $("#filter-principle-address").val();
			var telp = $("#filter-principle-phone").val();
			var area = $("#filter-area-principle").val();

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/SuratTugasPengiriman/GetFactoryByTypePelayanan') ?>",
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
					$("#table-factory > tbody").empty();
					if (response != 0) {
						$.each(response, function(i, v) {
							$("#table-factory > tbody").append(`
								<tr id="row-${i}">
									<td style="display: none">
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-principle_id" value="${v.principle_id}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-principle_propinsi" value="${v.principle_propinsi}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-principle_kota" value="${v.principle_kota}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-principle_kecamatan" value="${v.principle_kecamatan}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-principle_kelurahan" value="${v.principle_kelurahan}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-principle_kodepos" value="${v.principle_kodepos}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-area_id" value="${v.area_id}" />
									</td>
									<td class="text-center">
										<span class="client-pt-nama-label">${v.principle_nama}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-principle_nama" class="form-control client-pt-nama" value="${v.principle_nama}" />
									</td>
									<td class="text-center">
										<span class="client-pt-alamat-label">${v.principle_alamat}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-principle_alamat" class="form-control client-pt-alamat" value="${v.principle_alamat}" />
									</td>
									<td class="text-center">
										<span class="client-pt-telepon-label">${v.principle_telepon}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-principle_telepon" class="form-control client-pt-telepon" value="${v.principle_telepon}" />
									</td>
									<td class="text-center">
										<span class="area-nama-label">${v.area_nama}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-area_nama" class="form-control area-nama" value="${v.area_nama}" />
									</td>
									<td class="text-center">
										<button class="btn btn-primary btn-small btn-select-customer" onclick="getSelectedCustomer('${v.principle_id}','${tipe_layanan}')"><i class="fa fa-angle-down"></i></button>
									</td>
								</tr>
							`);
						});
					} else {
						$("#table-factory > tbody").append(`
								<tr>
									<td colspan="5" class="text-danger text-center">
										Data Kosong
									</td>
								</tr>
						`);
					}
				}
			});

		} else {
			$("#panel-customer").show();
			$("#panel-factory").hide();

			var nama = $("#filter-client-name").val();
			var alamat = $("#filter-client-address").val();
			var telp = $("#filter-client-phone").val();
			var area = $("#filter-area").val();

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/SuratTugasPengiriman/GetCustomerByTypePelayanan') ?>",
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
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-client_pt_id" value="${v.client_pt_id}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-client_pt_propinsi" value="${v.client_pt_propinsi}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-client_pt_kota" value="${v.client_pt_kota}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-client_pt_kecamatan" value="${v.client_pt_kecamatan}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-client_pt_kelurahan" value="${v.client_pt_kelurahan}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-client_pt_kodepos" value="${v.client_pt_kodepos}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-area_id" value="${v.area_id}" />
									</td>
									<td class="text-center">
										<span class="client-pt-nama-label">${v.client_pt_nama}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-client_pt_nama" class="form-control client-pt-nama" value="${v.client_pt_nama}" />
									</td>
									<td class="text-center">
										<span class="client-pt-alamat-label">${v.client_pt_alamat}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-client_pt_alamat" class="form-control client-pt-alamat" value="${v.client_pt_alamat}" />
									</td>
									<td class="text-center">
										<span class="client-pt-telepon-label">${v.client_pt_telepon}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-client_pt_telepon" class="form-control client-pt-telepon" value="${v.client_pt_telepon}" />
									</td>
									<td class="text-center">
										<span class="area-nama-label">${v.area_nama}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetail-area_nama" class="form-control area-nama" value="${v.area_nama}" />
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
	}

	function initDataSKU() {
		var client_pt = "";
		var perusahaan = $("#deliveryorder-client_wms_id").val();
		var tipe_layanan = document.querySelector('input[id="deliveryorder-delivery_order_tipe_layanan"]:checked').value;
		var tipe_pembayaran = document.querySelector('input[id="deliveryorder-delivery_order_tipe_pembayaran"]:checked').value;
		var sku_induk = $("#filter-sku-induk").val();
		var sku_nama_produk = $("#filter-sku-nama-produk").val();
		var sku_kemasan = $("#filter-sku-kemasan").val();
		var sku_satuan = $("#filter-sku-satuan").val();
		var principle = $("#filter-principle").val();
		var brand = $("#filter-brand").val();

		if (tipe_layanan == "Pickup Only") {
			client_pt = $("#deliveryorder-principle_id").val();

			if (client_pt != "") {

				$("#loadingsku").show();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/SuratTugasPengiriman/search_filter_chosen_sku_by_pabrik') ?>",
					data: {
						client_pt: client_pt,
						perusahaan: perusahaan,
						tipe_pembayaran: tipe_pembayaran,
						brand: brand,
						principle: principle,
						sku_induk: sku_induk,
						sku_nama_produk: sku_nama_produk,
						sku_kemasan: sku_kemasan,
						sku_satuan: sku_satuan
					},
					dataType: "JSON",
					async: false,
					success: function(response) {
						$("#loadingsku").hide();

						if (response.length > 0) {
							if ($.fn.DataTable.isDataTable('#table-sku')) {
								$('#table-sku').DataTable().destroy();
							}
							$("#table-sku > tbody").empty();

							$.each(response, function(i, v) {
								$("#table-sku > tbody").append(`
							<tr>
								<td width="5%" class="text-center">
									<input type="checkbox" name="CheckboxSKU" id="check-sku-${i}" value="${v.sku_id}">
								</td>
								<td width="15%" class="text-center">${v.sku_induk}</td>
								<td width="25%" class="text-center">${v.sku_nama_produk}</td>
								<td width="8%" class="text-center">${v.sku_kemasan}</td>
								<td width="8%" class="text-center">${v.sku_satuan}</td>
								<td width="10%" class="text-center">${v.principle}</td>
								<td width="10%" class="text-center">${v.brand}</td>
							</tr>
						`);
							});

							$('#table-sku').DataTable({
								"searching": false,
								columnDefs: [{
									sortable: false,
									targets: [0, 1, 2, 3, 4, 5, 6]
								}],
								lengthMenu: [
									[-1],
									['All']
								],
							});
						} else {
							$("#table-sku > tbody").html(`<tr><td colspan="7" class="text-center text-danger">Data Kosong</td></tr>`);
						}
					}
				});
			}
		} else {
			client_pt = $("#deliveryorder-client_pt_id").val();

			if (client_pt != "") {
				$("#loadingsku").show();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/SuratTugasPengiriman/search_filter_chosen_sku') ?>",
					data: {
						client_pt: client_pt,
						perusahaan: perusahaan,
						tipe_pembayaran: tipe_pembayaran,
						brand: brand,
						principle: principle,
						sku_induk: sku_induk,
						sku_nama_produk: sku_nama_produk,
						sku_kemasan: sku_kemasan,
						sku_satuan: sku_satuan
					},
					dataType: "JSON",
					async: false,
					success: function(response) {
						$("#loadingsku").hide();

						if (response.length > 0) {
							if ($.fn.DataTable.isDataTable('#table-sku')) {
								$('#table-sku').DataTable().destroy();
							}
							$("#table-sku > tbody").empty();

							$.each(response, function(i, v) {
								$("#table-sku > tbody").append(`
							<tr>
								<td width="5%" class="text-center">
									<input type="checkbox" name="CheckboxSKU" id="check-sku-${i}" value="${v.sku_id}">
								</td>
								<td width="15%" class="text-center">${v.sku_induk}</td>
								<td width="25%" class="text-center">${v.sku_nama_produk}</td>
								<td width="8%" class="text-center">${v.sku_kemasan}</td>
								<td width="8%" class="text-center">${v.sku_satuan}</td>
								<td width="10%" class="text-center">${v.principle}</td>
								<td width="10%" class="text-center">${v.brand}</td>
							</tr>
						`);
							});

							$('#table-sku').DataTable({
								"searching": false,
								columnDefs: [{
									sortable: false,
									targets: [0, 1, 2, 3, 4, 5, 6]
								}],
								lengthMenu: [
									[-1],
									['All']
								],
							});
						} else {
							$("#table-sku > tbody").html(`<tr><td colspan="7" class="text-center text-danger">Data Kosong</td></tr>`);
						}
					}
				});
			}
		}


	}

	$("#btnsavedo").on("click", function() {
		var cek_customer = $("#cek_customer").val();
		var tipe_do = $("#deliveryorder-tipe_delivery_order_id").val();

		console.log(arr_sku);

		if (tipe_do != "") {

			if (cek_customer > 0) {

				if (arr_sku.length > 0) {
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
										'depo_id': "<?= $this->session->userdata('depo_id') ?>",
										'depo_detail_id': "",
										'sku_kode': $("#item-" + index + "-DeliveryOrderDetail-sku_kode").val(),
										'sku_nama_produk': $("#item-" + index + "-DeliveryOrderDetail-sku_nama_produk").val(),
										'sku_harga_satuan': $("#item-" + index + "-DeliveryOrderDetail-sku_harga_satuan").val(),
										'sku_disc_percent': $("#item-" + index + "-DeliveryOrderDetail-sku_disc_percent").val(),
										'sku_disc_rp': $("#item-" + index + "-DeliveryOrderDetail-sku_disc_rp").val(),
										'sku_harga_nett': $("#item-" + index + "-DeliveryOrderDetail-sku_harga_nett").val(),
										'sku_request_expdate': "0",
										'sku_filter_expdate': "",
										'sku_filter_expdatebulan': "",
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
										'sku_qty_kirim': 0,
										'reason_id': "",
										'tipe_stock_nama': $("#item-" + index + "-DeliveryOrderDetail-tipe_stock_nama").val(),
										'depo_detail_id': $("#item-" + index + "-DeliveryOrderDetail-depo_detail_id").val(),
										'sku_expdate': $("#item-" + index + "-DeliveryOrderDetail-sku_expdate").val()
									});
								}
							}

							// console.log(arr_detail);

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
									//ajax save data
									$.ajax({
										async: false,
										url: "<?= base_url('WMS/SuratTugasPengiriman/insert_delivery_order'); ?>",
										type: "POST",
										data: {
											delivery_order_id: "",
											delivery_order_batch_id: delivery_order_batch_id,
											sales_order_id: "",
											delivery_order_kode: "",
											delivery_order_yourref: "",
											client_wms_id: $('#deliveryorder-client_wms_id').val(),
											delivery_order_tgl_buat_do: $('#deliveryorder-delivery_order_tgl_buat_do').val(),
											delivery_order_tgl_expired_do: $('#deliveryorder-delivery_order_tgl_expired_do').val(),
											delivery_order_tgl_surat_jalan: $('#deliveryorder-delivery_order_tgl_surat_jalan').val(),
											delivery_order_tgl_rencana_kirim: $('#deliveryorder-delivery_order_tgl_rencana_kirim').val(),
											delivery_order_tgl_aktual_kirim: $('#deliveryorder-delivery_order_tgl_aktual_kirim').val(),
											delivery_order_keterangan: $('#deliveryorder-delivery_order_keterangan').val(),
											delivery_order_status: $('#deliveryorder-delivery_order_status').val(),
											delivery_order_is_prioritas: 0,
											delivery_order_is_need_packing: 0,
											delivery_order_tipe_layanan: $('#deliveryorder-delivery_order_tipe_layanan').val(),
											delivery_order_tipe_pembayaran: $('#deliveryorder-delivery_order_tipe_pembayaran').val(),
											delivery_order_sesi_pengiriman: $('#deliveryorder-delivery_order_sesi_pengiriman').val(),
											delivery_order_request_tgl_kirim: "",
											delivery_order_request_jam_kirim: "",
											tipe_pengiriman_id: "",
											nama_tipe: "",
											confirm_rate: "",
											delivery_order_reff_id: $('#deliveryorder-delivery_order_reff_id').val(),
											delivery_order_reff_no: $('#deliveryorder-delivery_order_reff_no').val(),
											delivery_order_total: "",
											unit_mandiri_id: "",
											depo_id: "<?= $this->session->userdata('depo_id') ?>",
											client_pt_id: $('#deliveryorder-client_pt_id').val(),
											delivery_order_kirim_nama: $('#deliveryorder-delivery_order_kirim_nama').val(),
											delivery_order_kirim_alamat: $('#deliveryorder-delivery_order_kirim_alamat').val(),
											delivery_order_kirim_telp: $('#deliveryorder-delivery_order_kirim_telp').val(),
											delivery_order_kirim_provinsi: $('#deliveryorder-delivery_order_kirim_provinsi').val(),
											delivery_order_kirim_kota: $('#deliveryorder-delivery_order_kirim_kota').val(),
											delivery_order_kirim_kecamatan: $('#deliveryorder-delivery_order_kirim_kecamatan').val(),
											delivery_order_kirim_kelurahan: $('#deliveryorder-delivery_order_kirim_kelurahan').val(),
											delivery_order_kirim_latitude: "",
											delivery_order_kirim_longitude: "",
											delivery_order_kirim_kodepos: $('#deliveryorder-delivery_order_kirim_kodepos').val(),
											delivery_order_kirim_area: $('#deliveryorder-delivery_order_kirim_area').val(),
											delivery_order_kirim_invoice_pdf: "",
											delivery_order_kirim_invoice_dir: "",
											principle_id: $('#deliveryorder-principle_id').val(),
											delivery_order_ambil_nama: $('#deliveryorder-delivery_order_ambil_nama').val(),
											delivery_order_ambil_alamat: $('#deliveryorder-delivery_order_ambil_alamat').val(),
											delivery_order_ambil_telp: $('#deliveryorder-delivery_order_ambil_telp').val(),
											delivery_order_ambil_provinsi: $('#deliveryorder-delivery_order_ambil_provinsi').val(),
											delivery_order_ambil_kota: $('#deliveryorder-delivery_order_ambil_kota').val(),
											delivery_order_ambil_kecamatan: $('#deliveryorder-delivery_order_ambil_kecamatan').val(),
											delivery_order_ambil_kelurahan: $('#deliveryorder-delivery_order_ambil_kelurahan').val(),
											delivery_order_ambil_latitude: "",
											delivery_order_ambil_longitude: "",
											delivery_order_ambil_kodepos: $('#deliveryorder-delivery_order_ambil_kodepos').val(),
											delivery_order_ambil_area: $('#deliveryorder-delivery_order_ambil_area').val(),
											delivery_order_update_who: "<?= $this->session->userdata('pengguna_username') ?>",
											delivery_order_update_tgl: "<?= date('Y-m-d') ?>",
											delivery_order_approve_who: "",
											delivery_order_approve_tgl: "",
											delivery_order_reject_who: "",
											delivery_order_reject_tgl: "",
											delivery_order_reject_reason: "",
											delivery_order_no_urut_rute: $('#deliveryorder-delivery_order_no_urut_rute').val(),
											delivery_order_prioritas_stock: "",
											tipe_delivery_order_id: $('#deliveryorder-tipe_delivery_order_id').val(),
											delivery_order_draft_id: "",
											delivery_order_draft_kode: "",
											detail: arr_detail
										},
										dataType: "JSON",
										success: function(data) {
											if (data == 1) {
												message_topright("success", "Data berhasil disimpan");
												setTimeout(() => {
													location.href = "<?= base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=" + delivery_order_batch_id;
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
					message("Pilih SKU!", "SKU belum dipilih", "error");
				}
			} else {
				message("Pilih Customer!", "Customer belum dipilih", "error");

			}

		} else {
			message("Pilih Tipe DO!", "Tipe delivery order belum dipilih", "error");
		}
	});

	function DeleteSKU(row, index) {
		var row = row.parentNode.parentNode;
		row.parentNode.removeChild(row);

		arr_sku[index] = "";
	}

	function reset_table_sku() {
		$("#table-sku-delivery-only > tbody").empty();
		initDataSKU();
	}

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