<script type="text/javascript">
	loadingBeforeReadyPage()
	$(document).ready(function() {
		$(".select2").select2({
			width: "100%"
		});
		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});
		get_data_list_karyawan();
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

	function get_data_list_karyawan() {
		$.ajax({
			url: "<?= base_url('WMS/Distribusi/PengemasanBarang/GetDataListkaryawan') ?>",
			type: "GET",
			dataType: "JSON",
			success: function(data) {
				$('#nama_packer').empty();
				$('#nama_packer').append('<option value="">--Pilih Packer--</option>');
				$.each(data, function(k, v) {
					$('#nama_packer').append('<option value="' + v.karyawan_id + '">' + v.karyawan_nama + '</option>');
				});
			}
		});
	}

	//button clear storage
	$('#clear-storage').click(function() {
		localStorage.clear();
	});

	//button filter
	$("#search_filter_data").click(function() {
		let date_create = $("#tgl_create").val();
		let no_picking_list = $("#no_picking_list").val();
		let no_batch_do = $("#no_batch_do").val();
		let tipe_layanan = $("#tipe_layanan").val();
		let tipe_pengiriman = $("#tipe_pengiriman").val();
		let tipe_picking_list = $("#tipe_picking_list").val();
		let status = $("#status").val();
		let area = $("#area").val();
		$("#loadingsearch").show();
		$("#showfilterdata").show();
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengemasanBarang/GetPackingMenu') ?>",
			data: {
				date_create: date_create,
				no_picking_list: no_picking_list,
				no_batch_do: no_batch_do,
				tipe_layanan: tipe_layanan,
				tipe_pengiriman: tipe_pengiriman,
				tipe_picking_list: tipe_picking_list,
				status: status,
				area: area
			},
			async: "true",
			success: function(response) {
				$("#loadingsearch").hide();
				let Packing = JSON.parse(response);
				if ($.fn.DataTable.isDataTable('#tablepackingmenu')) {
					$('#tablepackingmenu').DataTable().destroy();
				}
				$('#tablepackingmenu tbody').empty();
				if (Packing.PackingMenu != 0) {
					for (i = 0; i < Packing.PackingMenu.length; i++) {
						var Packing_ID = Packing.PackingMenu[i].picking_list_id;
						var Packing_Date_Create = Packing.PackingMenu[i].picking_list_create_tgl;
						var Packing_Date_Send = Packing.PackingMenu[i].picking_list_tgl_kirim;
						var Packing_Kode = Packing.PackingMenu[i].picking_list_kode;
						var Packing_DO_Batch = Packing.PackingMenu[i].delivery_order_batch_kode;
						var Packing_Type_PickList = Packing.PackingMenu[i].picking_list_tipe;
						var Packing_Type_Layanan = Packing.PackingMenu[i].delivery_order_batch_tipe_layanan_nama;
						var Packing_Type_Pengiriman = Packing.PackingMenu[i].tipe_pengiriman;
						var Packing_Area = Packing.PackingMenu[i].area_nama;
						var Packing_Status = Packing.PackingMenu[i].picking_list_status;
						var ispacking = Packing.PackingMenu[i].delivery_order_batch_is_need_packing;

						var str = '<button class="btn btn-info form-control btndetailpacking" data-id="' + Packing_ID + '" data-toggle="tooltip" data-placement="top" title="Lihat detail data"><i class="fas fa-eye"></i></button>';

						var str2 = "";
						if (Packing_Status == "packing item confirmed") {
							str2 += '<button class="btn btn-success form-control btncetakpacking" data-id="' + Packing_ID + '" data-toggle="tooltip" data-placement="top" title="cetak data"><i class="fas fa-print"></i></button>';
						}

						var strmenu = '';

						strmenu += '<tr>';
						strmenu += '<td>' + Packing_Date_Create + '</td>';
						strmenu += '<td>' + Packing_Kode + '</td>';
						strmenu += '<td>' + Packing_DO_Batch + '</td>';
						strmenu += '<td>' + Packing_Date_Send + '</td>';
						strmenu += '<td>' + Packing_Type_PickList + '</td>';
						strmenu += '<td>' + Packing_Type_Layanan + '</td>';
						strmenu += '<td>' + Packing_Type_Pengiriman + '</td>';
						strmenu += '<td>' + Packing_Area + '</td>';
						strmenu += '<td>' + Packing_Status + '</td>';
						strmenu += '<td>' + str + ' ' + str2 + '</td>';
						strmenu += '</tr>';
						$("#tablepackingmenu > tbody").append(strmenu);
					}
				} else {
					message_topright("info", "Data yang anda cari kosong");
				}

				$('#tablepackingmenu').DataTable();
			}
		});
	});


	//modal untuk menampilkan detail data packing
	$("#tablepackingmenu tbody").on("click", ".btndetailpacking", function() {
		let id_pl = $(this).attr("data-id");
		$(".btnsavebuatpackingdo").attr("data-id-pl", id_pl);
		$(".btnkonfirmasimodalpackingdo").attr("data-id-pl", id_pl);

		$("#modaldetailpickinglist").modal("show");
		ajaxdetaildatabeforebtnpacking(id_pl);
	});

	//function untuk load data detail sebelum klik button packing
	function ajaxdetaildatabeforebtnpacking(id_pl) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengemasanBarang/GetDataDetailPickingList') ?>",
			data: {
				id: id_pl,
			},
			dataType: "json",
			asyc: "true",
			success: function(response) {
				if (response != null) {

					if ($.fn.DataTable.isDataTable('#datadetailpickinglist')) {
						$('#datadetailpickinglist').DataTable().destroy();
					}

					$('#datadetailpickinglist tbody').empty();
					for (i = 0; i < response.length; i++) {
						let id_pld = response[i].id_pld;
						let id = response[i].id;
						let DoBatchBatcId = response[i].DoBatchBatcId;
						let tgl_buat = response[i].tgl_buat;
						let kode = response[i].kode;
						let nama_customer = response[i].nama_customer;
						let alamat_customer = response[i].alamat_customer;
						let telp_customer = response[i].telp_customer;
						let tipe_pembayaran = response[i].tipe_pembayaran;
						let tipe_layanan = response[i].tipe_layanan;
						let prioritas = response[i].prioritas;
						let tipe = response[i].tipe;
						let status = response[i].status;
						var str = "";

						let temp_arr = ['packing item confirmed', 'in transit'];
						// console.log(id_pld);

						if (status == "pick up item confirmed") {
							str += '<button  class="btn btn-info form-control btnpackingdo" data-DoBatchId="' + DoBatchBatcId + '" data-id-pl="' + id_pl + '" data-idpld="' + id_pld + '" data-id="' + id + '" data-dono="' + kode + '" data-customer="' + nama_customer + '" data-prioritas="' + prioritas + '" data-alamat="' + alamat_customer + '" data-toggle="tooltip" data-placement="top" title="Packing data"><i class="fas fa-boxes-packing"></i> Packing</button>';
						} else if ((status == "packing item confirmed") || (status == "in transit") || (status == "delivered") || (status == "delivery failed") || (status == "partially delivered")) {
							str += str += '<button  class="btn btn-warning form-control btndetailpackingdo" data-DoBatchId="' + DoBatchBatcId + '" data-id-pl="' + id_pl + '" data-idpld="' + id_pld + '" data-id="' + id + '" data-dono="' + kode + '" data-customer="' + nama_customer + '" data-prioritas="' + prioritas + '" data-alamat="' + alamat_customer + '" data-toggle="tooltip" data-placement="top" title="Detail data packing"><i class="fas fa-eye"></i> Detail</button>';
						}

						var strmenu = '';

						strmenu += '<tr>';
						strmenu += '<td>' + str + '</td>';
						strmenu += '<td>' + tgl_buat + '</td>';
						strmenu += '<td>' + kode + '</td>';
						strmenu += '<td>' + nama_customer + '</td>';
						strmenu += '<td>' + alamat_customer + '</td>';
						strmenu += '<td>' + telp_customer + '</td>';
						strmenu += '<td>' + tipe_pembayaran + '</td>';
						strmenu += '<td>' + tipe_layanan + '</td>';
						strmenu += '<td>' + tipe + '</td>';
						strmenu += '<td>' + prioritas + '</td>';
						strmenu += '<td>' + status + '</td>';
						strmenu += '</tr>';
						$("#datadetailpickinglist > tbody").append(strmenu);
					}
				} else {
					$("#datadetailpickinglist > tbody").html(`<tr><td colspan="11" class="text-center text-danger">Data Kosong</td></tr>`);
				}

				$('#datadetailpickinglist').DataTable();
			}
		});
	}

	//modal untuk close detail data packing
	$(".btnclosemodaldetaildatado").on("click", () => {
		$("#modaldetailpickinglist").modal("hide");
	});

	//modal untuk menampilkan cetak data packing
	$("#tablepackingmenu tbody").on("click", ".btncetakpacking", function() {
		let id_pl = $(this).attr("data-id");
		// $(".btnsavebuatpackingdo").attr("data-id-pl", id_pl);
		// $(".btnkonfirmasimodalpackingdo").attr("data-id-pl", id_pl);

		$("#modalcetakdo").modal("show");
		ajaxdatacetakpacking(id_pl);
	});

	function ajaxdatacetakpacking(id_pl) {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengemasanBarang/GetDataCetakPacking') ?>",
			data: {
				id: id_pl,
			},
			dataType: "json",
			success: function(response) {
				if (response != null) {

					if ($.fn.DataTable.isDataTable('#listdatapackaging')) {
						$('#listdatapackaging').DataTable().destroy();
					}

					$('#listdatapackaging tbody').empty();
					for (i = 0; i < response.length; i++) {
						let do_id = response[i].do_id;
						let tgl_do = response[i].tgl_do;
						let kode_do = response[i].kode_do;
						let customer = response[i].customer;
						let alamat_customer = response[i].alamat_customer;
						let telp_customer = response[i].telp_customer;
						let tipe_pembayaran = response[i].tipe_pembayaran;
						let tipe_layanan = response[i].tipe_layanan;
						let prioritas = response[i].prioritas;
						let area = response[i].area;
						let koli = response[i].koli;
						let nama_pencetak = response[i].nama_pencetak;
						let tgl_cetak = response[i].tgl_cetak;
						let jml_cetak = response[i].jml_cetak;
						var str = "";
						var strAction = "";
						if (response[i].approve == 1) {
							str += "<input type='checkbox' class='form-control check-item' name='chk-data[]' id='chk-data[]' value='" + do_id + "' disabled data-toggle='tooltip' data-placement='top' title='Tidak dapat dicentang, silahkan ajukan permohonanan cetak supaya bisa dicetak kembali'/>";
							strAction += "<button type='button' class='btn btn-primary' data-id='" + do_id + "' data-toggle='tooltip' data-placement='top' title='Ajukan permohonan cetak kembali'><i class='fas fa-arrow-up'></i> Approval</button>";
						} else {
							str += "<input type='checkbox' class='form-control check-item' name='chk-data[]' id='chk-data[]' value='" + do_id + "'/>";
							strAction += "<button type='button' class='btn btn-primary' data-id='" + do_id + "' disabled data-toggle='tooltip' data-placement='top' title='Tidak dapat mengajukan permohonanan cetak, silahkan lakukan cetak terlebih dahulu pada DO ini'><i class='fas fa-arrow-up'></i> Approval</button>";
						}


						var strmenu = '';

						strmenu += '<tr>';
						strmenu += '<td class="text-center">' + str + '</td>';
						strmenu += '<td>' + tgl_do + '</td>';
						strmenu += '<td>' + kode_do + '</td>';
						strmenu += '<td>' + customer + '</td>';
						strmenu += '<td>' + alamat_customer + '</td>';
						strmenu += '<td>' + telp_customer + '</td>';
						strmenu += '<td class="text-center">' + tipe_pembayaran + '</td>';
						strmenu += '<td>' + tipe_layanan + '</td>';
						strmenu += '<td class="text-center">' + prioritas + '</td>';
						strmenu += '<td>' + area + '</td>';
						strmenu += '<td class="text-center">' + koli + '</td>';
						strmenu += '<td class="text-center">' + nama_pencetak + '</td>';
						strmenu += '<td class="text-center">' + tgl_cetak + '</td>';
						strmenu += '<td class="text-center">' + jml_cetak + '</td>';
						strmenu += '<td class="text-center">' + strAction + '</td>';
						strmenu += '</tr>';
						$("#listdatapackaging > tbody").append(strmenu);
					}
				} else {
					$("#listdatapackaging > tbody").html(`<tr><td colspan="11" class="text-center text-danger">Data Kosong</td></tr>`);
				}

				$('#listdatapackaging').DataTable({
					columnDefs: [{
						sortable: false,
						targets: [0]
					}],
				});
			}
		});
	}

	//untuk check all checkbox cetak
	function checkAll(e) {
		var checkboxes = $("input[name='chk-data[]']");
		if (e.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
					checkboxes[i].checked = true;
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = false;
				}
			}
		}
	}

	$(document).on("click", ".cetaklabel", function() {
		let arr_chk = [];
		$("input[name='chk-data[]']").each(function(i, v) {
			if ($(this).is(":checked")) {
				arr_chk.push($(this).val());
			}
		});

		if (arr_chk.length == 0) {
			message("Info!", "Pilih data yang akan dicetak", "info");
		} else {
			window.open("<?php echo base_url(); ?>WMS/Distribusi/PengemasanBarang/CetakLabel/?id=" + arr_chk, "_blank");
		}


	});

	//modal untuk close detail data packing
	$(".btnclosemodalcetakpackingdo").on("click", () => {
		$("#modalcetakdo").modal("hide");
	});


	//modal untuk menampilkan informasi packing delivery order
	$(document).on("click", ".btnpackingdo", function() {
		let id_pll = $(this).attr("data-id-pl");
		let id_pldd = $(this).attr("data-idpld");
		let id = $(this).attr("data-id");
		let DoBatchId = $(this).attr("data-DoBatchId");
		let dono = $(this).attr("data-dono");
		let customer = $(this).attr("data-customer");
		let prioritas = $(this).attr("data-prioritas");
		let alamat = $(this).attr("data-alamat");
		$("#modaldetailpickinglist").modal("hide");
		$("#modalpackingdo").modal("show");
		$(".btnsavebuatpackingdo").attr("data-id-pld", id_pldd);
		$(".btnsavebuatpackingdo").attr("data-id", id);
		$(".btnkonfirmasimodalpackingdo").attr("data-do-id", id);
		$(".btnkonfirmasimodalpackingdo").attr("data-id-pld", id_pldd);
		$(".btnkonfirmasimodalpackingdo").attr("data-DoBatchId", DoBatchId);
		let items = new getDataLocalStorage();


		$("#dono").val(dono);
		$("#customer").val(customer);
		$("#prioritas").val(prioritas);
		$(".alamat_customer").val(alamat);

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengemasanBarang/GetDataDetailPackingDO') ?>",
			data: {
				id: id_pldd,
			},
			dataType: "json",
			success: function(response) {

				let response_ = JSON.stringify(response)

				GetDataDetailPackingDO(response, items, id_pll, id_pldd);

				$(".btnsavebuatpackingdo").attr("data-id-pld", id_pldd);
				$(".btnsavebuatpackingdo").attr("data-response", response_);
				// temp_respon(response);
				// buttonsavepackingdo(response);

				AppendToInformationPacking(response, items, id_pll, id_pldd);
				btnkonfirmasipacking(response);
				btnsinpampacking(response, id_pll, id_pldd);
			}
		});

	});

	//modal close informasi packing delivery order
	$(document).on("click", ".btnclosemodalpackingdo", function() {
		$("#modalpackingdo").modal("hide");
		$("#modaldetailpickinglist").modal("show");
	});

	//modal untuk menampilkan informasi packing delivery order yang status packing has confirmed
	$(document).on("click", ".btndetailpackingdo", function() {

		let id_pll = $(this).attr("data-id-pl");
		let id_pldd = $(this).attr("data-idpld");
		let id = $(this).attr("data-id");
		let dono = $(this).attr("data-dono");
		let customer = $(this).attr("data-customer");
		let prioritas = $(this).attr("data-prioritas");
		let alamat = $(this).attr("data-alamat");
		$("#modaldetailpickinglist").modal("hide");
		$("#modalpackingdodetail").modal("show");

		$("#dono_detail").val(dono);
		$("#customer_detail").val(customer);
		$("#prioritas_detail").val(prioritas);
		$(".alamat_customer_detail").val(alamat);

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengemasanBarang/GetDataDetailPackingDOWhenStatusConfirmed') ?>",
			data: {
				id_pl: id_pll,
				id_pld: id_pldd
			},
			dataType: "json",
			asyc: "true",
			success: function(response) {

				$("#detailitempackingdodetail > tbody").empty();
				$.each(response, (index, value) => {
					let qty_remaining = parseInt(value.qty) - parseInt(value.qty);
					$("#detailitempackingdodetail > tbody").append(`
                        <tr>
                            <td>${value.kode} - ${value.nama_produk}</td>
                            <td class="text-center">${value.satuan}</td>
                            <td class="text-center">${value.kemasan}</td>
                            <td class="text-center">${value.ed_do}</td>
                            <td class="text-center"><input type="text" class="form-control" value="${value.qty}" readonly/></td>
                            <td class="text-center"><input type="text" class="form-control" value="${value.qty}" readonly/></td>
                            <td class="text-center"><input type="text" class="form-control" value="${qty_remaining}" readonly/></td>
                        </tr>
                    `);
				})
			}
		});

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengemasanBarang/GetDataDetailPackingStatusConfirmed') ?>",
			data: {
				do_id: id
			},
			dataType: "json",
			asyc: "true",
			success: function(response) {
				$("#informasipengemasandodetail > tbody").empty();
				let html = "";
				let no = 1;
				$.each(response, function(index, value) {
					$.each(value.data, function(i, v) {
						html += '<tr class="text-center">';
						if (i == 0) {
							html += '<td style="vertical-align: middle" rowspan="' + value.data.length + '">' + no++ + '</td><td style="vertical-align: middle" rowspan="' + value.data.length + '">' + index + '</td><td style="vertical-align: middle" rowspan="' + value.data.length + '">' + v.tgl_packing + '</td><td style="vertical-align: middle" rowspan="' + value.data.length + '">' + v.oleh + '</td>';
						}

						html += '<td>' + v.sku_kode + ' - ' + v.sku_produk + '</td><td>' + v.berat + '</td><td>' + v.volume + '</td><td>' + v.qty + '</td>';
						html += '</tr>';
					});
				});
				$("#informasipengemasandodetail > tbody").html(html);
			}
		});

	});

	//modal close informasi packing delivery order yang status packing has confirmed
	$(document).on("click", ".btnclosemodalpackingdodetail", function() {
		$("#modalpackingdodetail").modal("hide");
		$("#modaldetailpickinglist").modal("show");
	});

	//modal untuk button buat packaging delivery order
	function buttonaddpacking(arr_id_pld2, arr_sku_id, arr_kode, arr_nama_produk, arr_satuan, arr_kemasan, arr_ed_do, arr_berat, arr_volume, arr_qty_remaining) {
		$(document).on("click", "#btnbuatpackaging", function() {
			$("#modalbuatpackaging").modal("show");
			$("#modalpackingdo").modal("hide");

			$("#detailbuatpackingdo > tbody").empty();
			let arr_temp_berat = [];
			let arr_temp_volume = [];
			for (let index = 0; index < arr_id_pld2.length; index++) {
				arr_temp_berat.push(arr_berat[index]);
				arr_temp_volume.push(arr_volume[index]);
				$("#detailbuatpackingdo > tbody").append(`
                    <tr>
                        <td>` + arr_kode[index] + ` - ` + arr_nama_produk[index] + ` <input type="hidden" class="form-control" name="id_pld2" id="id_pld2" value="` + arr_id_pld2[index] + `"/></td>
                        <td class="text-center">` + arr_satuan[index] + `<input type="hidden" class="form-control" name="sku_id" id="sku_id" value="` + arr_sku_id[index] + `"/></td>
                        <td class="text-center">` + arr_kemasan[index] + `</td>
                        <td class="text-center">` + arr_ed_do[index] + `</td>
                        <td class="text-center">` + arr_qty_remaining[index] + `</td>
                        <td class="text-center"><input type="text" class="numeric form-control add_qty_packed" placeholder="qty" name="add_qty_packed[]" id="add_qty_packed"/></td>
                        <td class="text-center add_berat_packed">` + arr_berat[index] + `</td>
                        <td class="text-center add_volume_packed">` + arr_volume[index] + `</td>
                        <td class="text-center"><button type="button" class="btndeletepackingdo" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button></td>
                    </tr>
                `);

			}

			$("#detailbuatpackingdo > tbody tr").each(function(i, v) {
				let currentRow = $(this);
				let berat = currentRow.find("td:eq(6)");
				let volume = currentRow.find("td:eq(7)");
				let qty = currentRow.find(".add_qty_packed");
				// console.log(temp_arr_berat);
				qty.change(function(e) {
					berat.text(arr_temp_berat[i]);
					volume.text(arr_temp_volume[i]);
					if (qty.val() != "") {
						let val_qty = qty.val() == 0 ? 1 : parseInt(qty.val());
						let berat_tot = val_qty * parseInt(berat.text());
						let volume_tot = val_qty * parseInt(volume.text());
						berat.text(berat_tot);
						volume.text(volume_tot);
					} else {
						berat.text(arr_temp_berat[i]);
						volume.text(arr_temp_volume[i]);
					}
				});
			});
		});

		//btn delete
		$(document).on("click", ".btndeletepackingdo", function() {
			$(this).parent().parent().remove();
		});

	}

	//button modal save buat packing delivery order
	$(document).on("click", ".btnsavebuatpackingdo", function() {
		let id = $(this).attr("data-id");
		let id_pl = $(this).attr("data-id-pl");
		let id_pld = $(this).attr("data-id-pld");
		let response_ = $(this).attr("data-response");
		let response = JSON.parse(response_);
		let tanggal_packing = $("#tanggal_buat_packing").val();
		let id_packer_packing = $("#nama_packer");
		let nama_packer_packing = $("#nama_packer option:selected").text();
		let berat_packing = $("#berat_buat_packing");
		let volume_packing = $("#volume_buat_packing");
		let keterangan_packing = $("#keterangan_packing");
		let arr_product = [];
		let arr_satuan = [];
		let arr_kemasan = [];
		let arr_ed_do = [];
		let arr_id_pld2 = [];
		let arr_sku_id = [];
		let arr_qty_packed = [];
		let arr_berat_tot = [];
		let arr_volume_tot = [];
		let arr_final_detail = [];

		if (id_packer_packing.val() == "") {
			message('Error!', 'Nama packer tidak boleh kosong', 'error');
			$("#count_error").val("1");
		} else if (berat_packing.val() == "") {
			message('Error!', 'Berat tidak boleh kosong', 'error');
			$("#count_error").val("1");
		} else if (volume_packing.val() == "") {
			message('Error!', 'Volume tidak boleh kosong', 'error');
			$("#count_error").val("1");
		} else {
			var countSKU = $('#detailbuatpackingdo > tbody').children('tr').length;
			if (countSKU == 0) {
				message('Error!', 'Tidak dapat simpan, SKU kosong!!! silahkan tutup dan buat packing kembali', 'error');
				$("#count_error").val("1");
				return false;
			} else {
				$("#detailbuatpackingdo > tbody tr").each(function(index, value) {
					let product = $(this).find("td:eq(0)");
					let id_pld2 = $(this).find("td:eq(0) input[type='hidden']");
					let satuan = $(this).find("td:eq(1)");
					let sku_id = $(this).find("td:eq(1) input[type='hidden']");
					let kemasan = $(this).find("td:eq(2)");
					let ed_do = $(this).find("td:eq(3)");
					let qty_remaining = $(this).find("td:eq(4)").text();
					let qty_packked = $(this).find("td:eq(5) input[type='text']");
					let berat = $(this).find("td:eq(6)");
					let volume = $(this).find("td:eq(7)");

					if (qty_packked.val() == "" || parseInt(qty_packked.val()) == 0) {
						message('Error!', 'Qty packed tidak boleh kosong, jika kosong / 0 silahkan hapus SKU', 'error');
						$("#count_error").val("1");
						return false;
					} else if (parseInt(qty_packked.val()) > parseInt(qty_remaining)) {
						message('Error!', 'Qty melebihi qty dari remaining', 'error');
						$("#count_error").val("1");
						return false;
					} else {
						$("#count_error").val("0");
						product.map(function() {
							arr_product.push($(this).text());
						}).get();
						satuan.map(function() {
							arr_satuan.push($(this).text());
						}).get();
						kemasan.map(function() {
							arr_kemasan.push($(this).text());
						}).get();
						ed_do.map(function() {
							arr_ed_do.push($(this).text());
						}).get();
						id_pld2.map(function() {
							arr_id_pld2.push($(this).val());
						}).get();
						sku_id.map(function() {
							arr_sku_id.push($(this).val());
						}).get();
						qty_packked.map(function() {
							arr_qty_packed.push($(this).val());
						}).get();

						arr_berat_tot.push(berat.text());
						arr_volume_tot.push(volume.text());
					}
				});
			}
		}


		let error = $("#count_error");
		if (error.val() != 0) {
			return false;
		} else {
			if (arr_id_pld2 && arr_qty_packed != null) {
				for (let index = 0; index < arr_id_pld2.length; index++) {
					arr_final_detail.push({
						'id_pld2': arr_id_pld2[index],
						'sku_id': arr_sku_id[index],
						'qty_packed': arr_qty_packed[index],
						'product': arr_product[index],
						'satuan': arr_satuan[index],
						'kemasan': arr_kemasan[index],
						'ed_do': arr_ed_do[index],
						'berat_tot': arr_berat_tot[index],
						'volume_tot': arr_volume_tot[index]
					});
				}
			}

			var items = new getDataLocalStorage();

			items.push({
				'do_id': id,
				'id_pl': id_pl,
				'id_pld': id_pld,
				'tanggal_packing': tanggal_packing,
				'id_packer_packing': id_packer_packing.val(),
				'nama_packer_packing': nama_packer_packing,
				'berat_packing': berat_packing.val(),
				'volume_packing': volume_packing.val(),
				'keterangan_packing': keterangan_packing.val(),
				'status': 1,
				'data': arr_final_detail
			});

			localStorage.setItem("items", JSON.stringify(items));

			$("#modalbuatpackaging").modal("hide");
			$('#modalpackingdo').fadeOut("slow", function() {
				$(this).modal('hide');
				id_packer_packing.html("");
				berat_packing.val("");
				volume_packing.val("");
				keterangan_packing.val("");
				error.val("");
				get_data_list_karyawan();
			}).fadeIn("slow", function() {
				$(this).modal('show');
				var itemss = new getDataLocalStorage();
				GetDataDetailPackingDO(response, itemss, id_pl, id_pld);
				AppendToInformationPacking(response, itemss, id_pl, id_pld);
				message_topright("success", "Data packing berhasil dibuat");
			});
		}
	});

	//set and append detail data packing do
	function GetDataDetailPackingDO(response, items, id_pll, id_pldd) {
		$('#detailitempackingdo tbody').empty();

		let arr_id_pld2 = [];
		if (response != null) {
			//menggunakan filter
			const existingItem = items.filter((val) => {
				if ((val.id_pl === id_pll) && (val.id_pld === id_pldd)) {
					return val;
				}
			});

			let id_before = [];
			for (let i = 0; i < existingItem.length; i++) {
				for (let index = 0; index < existingItem[i].data.length; index++) {
					id_before.push({
						'id_pld2': existingItem[i].data[index].id_pld2,
						'qty_packed': existingItem[i].data[index].qty_packed != "" ? parseInt(existingItem[i].data[index].qty_packed) : 0,
						'status': existingItem[i].status
					});
				};
			}

			const qty_before = Object.values(
				id_before.reduce((acc, item) => {
					let qty = item.qty_packed != "" ? parseInt(item.qty_packed) : 0;
					acc[item.id_pld2] = acc[item.id_pld2] ? {
							...item,
							qty_packed: qty + acc[item.id_pld2].qty_packed
						} :
						item;
					return acc;
				}, {})
			);
			let arr_remaining = [];
			for (i = 0; i < response.length; i++) {
				let id_pld2 = response[i].id_pld2;
				let sku_id = response[i].sku_id;
				let kode = response[i].kode;
				let nama_produk = response[i].nama_produk;
				let berat = response[i].berat;
				let volume = response[i].volume;
				let qty = response[i].qty;
				let satuan = response[i].satuan;
				let kemasan = response[i].kemasan;
				let ed_do = response[i].ed_do;
				let qty_packed = "";
				let chkConfirmed = "";
				if (qty_before[i]) {
					qty_packed = qty_before[i].qty_packed;
					chkConfirmed = qty_before[i].status;
				} else {
					qty_packed = 0;
				}

				let qty_remaining = qty - qty_packed;

				let strmenu = '';

				strmenu += '<tr>';
				strmenu += '<td>' + kode + ' - ' + nama_produk + '<input type="hidden" name="id_pld2[]" value="' + id_pld2 + '"/><input type="hidden" name="sku_id[]" value="' + sku_id + '"/><input type="hidden" name="kode[]" value="' + kode + '"/><input type="hidden" name="nama_produk[]" value="' + nama_produk + '"/><input type="hidden" name="berat[]" value="' + berat + '"/><input type="hidden" name="volume[]" value="' + volume + '"/><input type="hidden" name="satuan[]" value="' + satuan + '"/><input type="hidden" name="kemasan[]" value="' + kemasan + '"/><input type="hidden" name="ed_do[]" value="' + ed_do + '"/><input type="hidden" name="chkConfirmed[]" value="' + chkConfirmed + '"/></td>';
				strmenu += '<td class="text-center">' + satuan + '</td>';
				strmenu += '<td class="text-center">' + kemasan + '</td>';
				strmenu += '<td class="text-center">' + ed_do + '</td>';
				strmenu += '<td class="text-center"><input type="text" readonly name="qty_ordered[]" id="qty_ordered" class="form-control" value="' + qty + '" /></td>';
				strmenu += '<td class="text-center"><input type="text" readonly name="qty_packed[]" class="form-control qty_packed" value="' + qty_packed + '" /></td>';
				strmenu += '<td class="text-center"><input type="text" readonly name="qty_remaining[]" class="form-control qty_remaining" value="' + qty_remaining + '"/></td>';
				strmenu += '</tr>';

				$("#detailitempackingdo > tbody").append(strmenu);
			}

		}

		//id_pld2
		$("input[name='id_pld2[]']").each(function(i, v) {
			arr_id_pld2.push($(this).val());
		});

		let arr_sku_id = $("input[name='sku_id[]']").map(function() {
			return this.value;
		}).get();

		let arr_kode = $("input[name='kode[]']").map(function() {
			return this.value;
		}).get();

		let arr_nama_produk = $("input[name='nama_produk[]']").map(function() {
			return this.value;
		}).get();

		let arr_satuan = $("input[name='satuan[]']").map(function() {
			return this.value;
		}).get();

		let arr_kemasan = $("input[name='kemasan[]']").map(function() {
			return this.value;
		}).get();

		let arr_ed_do = $("input[name='ed_do[]']").map(function() {
			return this.value;
		}).get();

		//berat
		let arr_berat = $("input[name='berat[]']").map(function() {
			return this.value;
		}).get();

		//volume
		let arr_volume = $("input[name='volume[]']").map(function() {
			return this.value;
		}).get();

		//qty_remaining
		let arr_qty_remaining = [];
		$("input[name='qty_remaining[]']").each(function(i, v) {
			arr_qty_remaining.push($(this).val());
		});

		let chkConfirmed = [];
		$("input[name='chkConfirmed[]']").each(function(i, v) {
			chkConfirmed.push($(this).val());
		});

		check_confirmed(arr_qty_remaining, chkConfirmed, id_pll, id_pldd);

		buttonaddpacking(arr_id_pld2, arr_sku_id, arr_kode, arr_nama_produk, arr_satuan, arr_kemasan, arr_ed_do, arr_berat, arr_volume, arr_qty_remaining);
	}

	//function untuk check status button konfirmasi
	function check_confirmed(arr_qty_remaining, chkConfirmed, id_pll, id_pldd) {
		let chkConfirmed_ = chkConfirmed[0];

		let items = new getDataLocalStorage();

		let tot_qty_remaining = 0;
		let chkConf = 0;
		if (items.length != 0) {
			$.each(items, (i, v) => {
				if ((v.id_pl == id_pll) && (v.id_pld == id_pldd)) {
					$.each(arr_qty_remaining, (index, value) => {
						tot_qty_remaining += parseInt(value);
						if (chkConfirmed_[index] != undefined) {
							chkConf = parseInt(chkConfirmed_[index]);
						}
					});
					validation_button(tot_qty_remaining, chkConf);
				} else {
					validation_button_qtyRemaining_null();
					validation_button(tot_qty_remaining, chkConf);
				}
			});
		} else {
			validation_button_qtyRemaining_null();
			validation_button(tot_qty_remaining, chkConf);
		}
	}

	//function untuk validasi button disable dan enable
	function validation_button(tot_qty_remaining, chkConf) {
		if (tot_qty_remaining > 0) {
			validation_button_qtyRemaining_null();
		} else if ((tot_qty_remaining == 0) && (chkConf == 1)) {
			//for button buat packing
			$("#btnbuatpackaging").attr("disabled", true);
			$("#btnbuatpackaging").attr({
				"data-toggle": "tooltip",
				"data-placement": "top", // attributes which contain dash(-) should be covered in quotes.
				title: "Tidak bisa melakukan pembuatan packing kembali, dikarenakan qty remaining kosong",
			});
			//end for button buat packing

			$(".btnkonfirmasimodalpackingdo").attr("disabled", false);
			$(".btnkonfirmasimodalpackingdo").removeAttr('data-toggle data-placement title');
			// $(".btnsimpanmodalpackingdo").removeAttr('data-toggle data-placement title');
			// $(".btnsimpanmodalpackingdo").attr("disabled", false);

			//for button simpan 
			$(".btnsimpanmodalpackingdo").attr("disabled", true);
			$(".btnsimpanmodalpackingdo").attr({
				"data-toggle": "tooltip",
				"data-placement": "top", // attributes which contain dash(-) should be covered in quotes.
				title: "Tidak dapat melakukan simpan, silahkan konfirmasi packaging selesai terlebih dahulu",
			});
			//end for button simpan
		} else if ((tot_qty_remaining == 0) && (chkConf == 2)) {
			//for button konfrimasi
			$(".btnkonfirmasimodalpackingdo").hide();
			//end for button konfirmasi

			//for button simpan 
			$(".btnsimpanmodalpackingdo").attr("disabled", false);
			$(".btnsimpanmodalpackingdo").removeAttr('data-toggle data-placement title');
			//end for button simpan

			//for button tutup
			$(".btnclosemodalpackingdo").attr("disabled", true);
			$(".btnclosemodalpackingdo").attr({
				"data-toggle": "tooltip",
				"data-placement": "top", // attributes which contain dash(-) should be covered in quotes.
				title: "tidak dapat menutup modal ini, silahkan lakukan simpan data terlebih dahulu",
			});
			//end for button tutup
		}
	}

	//function untuk validasi jika qty remaining null
	function validation_button_qtyRemaining_null() {
		//for button buat packing
		$("#btnbuatpackaging").attr("disabled", false);
		$("#btnbuatpackaging").removeAttr('data-toggle data-placement title');
		//end for button buat packing

		//for button konfirmasi packing
		$(".btnkonfirmasimodalpackingdo").attr("disabled", true);
		$(".btnkonfirmasimodalpackingdo").attr({
			"data-toggle": "tooltip",
			"data-placement": "top", // attributes which contain dash(-) should be covered in quotes.
			title: "Tidak dapat melakukan konformasi packing, dikarenakan qty remaining masih ada sisa",
		});
		//end for button konfirmasi packing

		//for button simpan 
		$(".btnkonfirmasimodalpackingdo").show();
		$(".btnsimpanmodalpackingdo").attr("disabled", true);
		$(".btnsimpanmodalpackingdo").attr({
			"data-toggle": "tooltip",
			"data-placement": "top", // attributes which contain dash(-) should be covered in quotes.
			title: "Tidak dapat melakukan simpan, dikarenakan qty remaining masih ada sisa",
		});
		//end for button simpan

		//for button tutup 
		$(".btnclosemodalpackingdo").attr("disabled", false);
		$(".btnclosemodalpackingdo").removeAttr('data-toggle data-placement title');
		//end for button tutup
	}


	//set data save packing delivery order to localstorage
	function getDataLocalStorage() {
		let items = [];

		if (localStorage.getItem("items")) {
			items = JSON.parse(localStorage.getItem("items"));
		}
		return items;
	}

	//append data packing delivery order to modal
	function AppendToInformationPacking(response, items, id_pl, id_pld) {
		$("#informasipengemasando > tbody").empty();
		if (items != null) {
			$.each(items, (i, v) => {
				if ((v.id_pl == id_pl) && (v.id_pld == id_pld)) {
					$("#informasipengemasando > tbody").append(`
                        <tr class="text-center">
                            <td>` + v.tanggal_packing + `</td>
                            <td>` + v.nama_packer_packing + `</td>
                            <td>Auto generate</td>
                            <td>` + v.berat_packing + `</td>
                            <td>` + v.volume_packing + `</td>
                            <td>
                                <button type="button" class="btndetailinformasipacking" data-key="` + i + `" style="border:none;background:transparent"><i class="fas fa-eye text-primary" style="cursor: pointer"></i></button>
                                <button type="button" class="btndeleteinformasipacking" data-key="` + i + `" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button>
                            </td>
                        </tr>
                    `);
				}
			});

			$(".btndeleteinformasipacking").attr("data-idpl", id_pl);
			$(".btndeleteinformasipacking").attr("data-idpld", id_pld);
			btndeleteinformasipacking(response);
		}
	}

	//button delete informasi packing
	function btndeleteinformasipacking(response) {
		$(document).on("click", ".btndeleteinformasipacking", function() {
			let key = $(this).attr('data-key');
			let id_pl = $(this).attr('data-idpl');
			let id_pld = $(this).attr('data-idpld');
			let storageItems = new getDataLocalStorage();
			Swal.fire({
				title: "Apakah anda yakin?",
				text: "Data packing yang anda pilih dihapus",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Hapus",
				cancelButtonText: "Tidak, Tutup"
			}).then((result) => {
				if (result.value == true) {
					let items = storageItems.filter((value, index) => index !== parseInt(key));
					localStorage.setItem("items", JSON.stringify(items));
					$('#modalpackingdo').fadeOut("slow", function() {
						$(this).modal('hide');
					}).fadeIn("slow", function() {
						$(this).modal('show');
						var itemss = new getDataLocalStorage();
						GetDataDetailPackingDO(response, itemss, id_pl, id_pld);
						AppendToInformationPacking(response, itemss, id_pl, id_pld);
						message_topright("success", "Data packing berhasil dihapus");
					});
				}
			});
		});
	}

	//button modal close buat packing delivery order
	$(document).on("click", ".btnclosemodalbuatpackingdo", function() {
		$("#modalbuatpackaging").modal("hide");
		let id_packer_packing = $("#nama_packer");
		let berat_packing = $("#berat_buat_packing");
		let volume_packing = $("#volume_buat_packing");
		let keterangan_packing = $("#keterangan_packing");
		let error = $("#count_error");
		id_packer_packing.html("");
		berat_packing.val("");
		volume_packing.val("");
		keterangan_packing.val("");
		error.val("");
		get_data_list_karyawan();
		$("#modalpackingdo").modal("show");
	});

	//button detail informasi packing
	$(document).on("click", ".btndetailinformasipacking", function() {
		$("#modalpackingdo").modal("hide");
		$("#modaldetailbuatpackaging").modal("show");
		let key = $(this).attr('data-key');
		let items = new getDataLocalStorage();

		let FindByKeys = items.filter((value, index) => index === parseInt(key));

		$("#tanggal_buat_packing_detail").val(FindByKeys[0].tanggal_packing)
		$("#berat_buat_packing_detail").val(FindByKeys[0].berat_packing)
		$("#volume_buat_packing_detail").val(FindByKeys[0].volume_packing)
		$("#nama_packer_buat_packing_detail").val(FindByKeys[0].nama_packer_packing)
		$("#keterangan_packing_detail").val(FindByKeys[0].keterangan_packing)

		$("#detail2buatpackingdo > tbody").empty();
		$.each(FindByKeys[0].data, (i, v) => {
			let qty = v.qty_packed != "" ? v.qty_packed : 0;
			$("#detail2buatpackingdo > tbody").append(`
                <tr>
                    <td>` + v.product + `</td>
                    <td class="text-center">` + v.satuan + `</td>
                    <td class="text-center">` + v.kemasan + `</td>
                    <td class="text-center">` + v.ed_do + `</td>
                    <td class="text-center">` + qty + `</td>
                    <td class="text-center">` + v.berat_tot + `</td>
                    <td class="text-center">` + v.volume_tot + `</td>
                </tr>
            `);
		})

	});

	//button close modal detail buat packing
	$(document).on("click", ".btnclosemodaldetailbuatpackingdo", function() {
		$("#modaldetailbuatpackaging").modal("hide");
		$("#modalpackingdo").modal("show");
	});

	//btn konfirmasi packagin do
	function btnkonfirmasipacking(response) {
		$(document).on("click", ".btnkonfirmasimodalpackingdo", function() {
			let do_id = $(this).attr('data-do-id');
			let DoBatchId = $(this).attr('data-DoBatchId');
			let id_pll = $(this).attr('data-id-pl');
			let id_pldd = $(this).attr('data-id-pld');

			Swal.fire({
				title: "Apakah anda yakin?",
				text: "Ingin melakukan konfirmasi packing selesai!!!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Konfirmasi",
				cancelButtonText: "Tidak, Tutup"
			}).then((result) => {
				if (result.value == true) {
					$.ajax({
						type: "POST",
						url: "<?= base_url('WMS/Distribusi/PengemasanBarang/UpdateStatus3Table') ?>",
						data: {
							do_id: do_id,
							id_pld: id_pldd,
							DoBatchId: DoBatchId
						},
						dataType: "JSON",
						beforeSend: function() {
							$(".btnkonfirmasimodalpackingdo").attr("disabled", true);
							$(".btnkonfirmasimodalpackingdo").html(
								"<span id='loadingsearch'><i class='fa fa-spinner fa-spin'></i> Loading...</span>"
							);
						},
						success: function(res) {
							if (res == 1) {
								$("#modalpackingdo").modal("hide");
								$('#modalpackingdo').fadeOut("slow", function() {
									$(this).modal('hide');
								}).fadeIn("slow", function() {
									$(this).modal('show');
									const oldItems = JSON.parse(localStorage.getItem("items")) || [];
									const existingItem = oldItems.filter(({
										id_pl,
										id_pld
									}) => (id_pl == id_pll) && (id_pld == id_pldd));
									if (existingItem) {
										$.each(existingItem, function(i, v) {
											Object.assign(v, {
												status: 2,
											});
										})
									}

									localStorage.setItem("items", JSON.stringify(oldItems));
									var itemss = new getDataLocalStorage();
									GetDataDetailPackingDO(response, itemss, id_pll, id_pldd);
									AppendToInformationPacking(response, itemss, id_pll, id_pldd);
									message_topright("success", "Data packing berhasil dikonfirmasi");
								});
							} else {
								message_topright("error", "Data packing gagal dikonfirmasi");
							}
						}
					}).complete(function() {
						$(".btnkonfirmasimodalpackingdo").attr("disabled", false);
						$(".btnkonfirmasimodalpackingdo").html("Konfirmasi Packaging Selesai");
					});
				}
			});

		});
	}

	//btn simpan packagin do
	function btnsinpampacking(response, id_pl, id_pld) {
		$(document).on("click", ".btnsimpanmodalpackingdo", function() {
			let data = [];
			let id_localstorage = [];
			const items = new getDataLocalStorage();
			if (items.length != 0) {
				$.each(items, (i, v) => {
					if ((v.id_pl == id_pl) && (v.id_pld == id_pld)) {
						data.push(v);
						id_localstorage.push(i);
					}
				});
			}

			Swal.fire({
				title: "Apakah anda yakin?",
				text: "Data akan disimpan!!!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Simpan",
				cancelButtonText: "Tidak, Tutup"
			}).then((result) => {

				if (result.value == true) {
					let item = items.filter((i, v) => !id_localstorage.includes(v));
					localStorage.setItem("items", JSON.stringify(item));
					$.ajax({
						type: "POST",
						url: "<?= base_url('WMS/Distribusi/PengemasanBarang/InsertToPacking') ?>",
						data: {
							data: data
						},
						dataType: "JSON",
						beforeSend: function() {
							$(".btnsimpanmodalpackingdo").attr("disabled", true);
							$(".btnsimpanmodalpackingdo").html(
								"<span id='loadingsearch'><i class='fa fa-spinner fa-spin'></i> Loading...</span>"
							);
						},
						success: function(res) {
							if (res == 1) {
								let item = items.filter((i, v) => !id_localstorage.includes(v));
								localStorage.setItem("items", JSON.stringify(item));
								$("#modalpackingdo").modal("hide");
								$('#modaldetailpickinglist').fadeOut("slow", function() {
									$(this).modal('hide');
									ajaxdetaildatabeforebtnpacking(id_pl);
								}).fadeIn("slow", function() {
									$(this).modal('show');
									var itemss = new getDataLocalStorage();
									GetDataDetailPackingDO(response, itemss, id_pl, id_pld);
									AppendToInformationPacking(response, itemss, id_pl, id_pld);
									message_topright("success", "Data packing berhasil disimpan");
								});

							} else {
								message_topright("error", "Data packing gagal disimpan");
							}
						}
					}).complete(function() {
						$(".btnsimpanmodalpackingdo").attr("disabled", false);
						$(".btnsimpanmodalpackingdo").html("Simpan");
					});
				}
			});
		});
	}
</script>