<script type="text/javascript">
	var ChannelCode = '';
	let dataBulkGlobal = [];
	let dataKirimUlangGlobal = [];
	let dataStandarGlobal = [];
	let dataCanvasGlobal = [];

	let qtyAktualTempArr = [];
	let scanPalletArr = [];
	let qtyAktualSaveTemp = [];

	let arrScanPallet = [];
	let arrScanPilihDetail = []
	let arrScanPilihDetail2 = []

	let dataBulkNew = [];
	let dataKirimUlangNew = [];
	let dataCanvasNew = [];

	let arrDataChkBKB = [];

	let principleArray = [];
	const html5QrCode = new Html5Qrcode("previewScanPallet");
	// const html5QrCode3 = new Html5Qrcode("preview_by_one");

	loadingBeforeReadyPage();

	$(document).ready(
		function() {
			GetPengeluaranBarangMenu();
			$('#slcaddcheckerbkbbulk, #slcaddcheckerbkbstandar, #slcaddcheckerbkbkirimulang, #slccheckerpb, #slcaddnodostandar, #slcaddnodostandar, .principleFilter').select2({
				width: '100%'
			});
			$("#btnkonfirmasibarang").prop("disabled", true);
			$("#btnlihatbkbbulk").prop("disabled", true);
			$("#btnlihatbkbstandar").prop("disabled", true);

			handlerCheckedRemainderTake(event).trigger('change');
			handlerCheckedRemainderTakeKirimUlang(event).trigger('change');
			handlerCheckedRemainderTakeCanvas(event).trigger('change');

			$("#initScanManualPalletDetail").DataTable({
				lengthMenu: [
					[-1],
					['All']
				],
			});
			$('.numeric').on('input', function(event) {
				this.value = this.value.replace(/[^0-9]/g, '');
			});
		}
	);

	// function message(msg, msgtext, msgtype) {
	// 	Swal.fire({
	// 		title: msg,
	// 		html: msgtext,
	// 		icon: msgtype
	// 	});
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
	// 		html: msg,
	// 	});
	// }


	function GetPengeluaranBarangMenu() {
		var picking_order_kode = $("#txtnoppbhidden").val();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDetailPengeluaranBarangMenu') ?>",
			data: "picking_order_kode=" + picking_order_kode,
			success: function(response) {
				if (response) {
					ChPengeluaranBarangMenu(response);
				}
			}
		});
	}

	//var DTABLE;

	const pushPrincipleToAray = (typeBKB, principle) => {
		principleArray.push({
			typeBKB,
			principle
		})
	}

	function ChPengeluaranBarangMenu(JSONChannel) {
		$("#tabledetailpbbulk > tbody").html('');
		$("#tabledetailpbstandar > tbody").html('');
		$("#slcgudang").html('');
		$("#slctipepb").html('');
		$("#slcstatusppb").html('');
		$("#slcnopb").html('');
		$("#tipeBKBMix").val('')

		$("#slcaddcheckerbkbbulk").html('');
		$("#slccheckerpb").html('');
		$("#txtcheckerpb").val('');
		$("#slccheckerheaderpb").html('');

		var Channel = JSON.parse(JSONChannel);

		if (Channel.PengeluaranBarang != 0) {
			var picking_order_id = Channel.PengeluaranBarang[0].picking_order_id;
			var picking_order_kode = Channel.PengeluaranBarang[0].picking_order_kode;
			var picking_list_id = Channel.PengeluaranBarang[0].picking_list_id;
			var depo_id = Channel.PengeluaranBarang[0].depo_id;
			var depo_detail_id = Channel.PengeluaranBarang[0].depo_detail_id;
			var depo_detail_nama = Channel.PengeluaranBarang[0].depo_detail_nama;
			var picking_list_kode = Channel.PengeluaranBarang[0].picking_list_kode;
			var delivery_order_batch_kode = Channel.PengeluaranBarang[0].delivery_order_batch_kode;
			var picking_list_tipe = Channel.PengeluaranBarang[0].picking_list_tipe;
			var picking_order_plan_tipe = Channel.PengeluaranBarang[0].picking_order_plan_tipe;
			var picking_order_tanggal = Channel.PengeluaranBarang[0].picking_order_tanggal;
			var picking_order_status = Channel.PengeluaranBarang[0].picking_order_status;
			var picking_order_keterangan = Channel.PengeluaranBarang[0].picking_order_keterangan;
			var driver = Channel.PengeluaranBarang[0].driver;
			var tipe_delivery_order_nama = Channel.PengeluaranBarang[0].tipe_delivery_order_nama;
			// var lastUpdated = Channel.PengeluaranBarang[0].picking_order_tgl_update;

			var karyawan_id = Channel.PengeluaranBarang[0].karyawan_id;
			var karyawan_nama = Channel.PengeluaranBarang[0].karyawan_nama;

			console.log('awd', {
				karyawan_id,
				karyawan_nama
			});

			var NoPPB = Channel.NoPPB;

			$("#txtidppb").val(picking_order_id);
			$("#txtnoppb").val(picking_order_kode);
			$("#txtketerangan").val(picking_order_keterangan);
			$("#txtpickinglistid").val(picking_list_id);
			$("#txtdepoid").val(depo_id);
			$("#txtnofdjr").val(delivery_order_batch_kode);
			$("#dateppb").val(picking_order_tanggal);
			$("#driverppb").val(driver);
			$("#slcgudang").append('<option value="' + depo_detail_id + '">' + depo_detail_nama + '</option>');
			$("#slcnopb").append('<option value="' + picking_list_kode + '">' + picking_list_kode + '</option>');
			$("#slctipepb").append('<option value="' + picking_order_plan_tipe + '">' + picking_order_plan_tipe + '</option>');
			$("#slcstatusppb").append('<option value="' + picking_order_status + '">' + picking_order_status + '</option>');
			$("#slccheckerheaderpb").append('<option value="' + karyawan_id + '">' + karyawan_nama + '</option>');
			// $("#lastUpdated").val(lastUpdated);

			if (picking_order_status == "Draft") {

				document.getElementById("chkpb").checked = false;

				$("#chkpb").prop("disabled", false);
				$("#btnkonfirmasibarang").prop("disabled", true);
				$("#btnsavebkb").prop("disabled", false);
				$("#btnbatalbkb").prop("disabled", false);
				$("#btnlihatbkbbulk").prop("disabled", true);
				$("#btnlihatbkbstandar").prop("disabled", true);
				$("#btnaddbkbbulk").prop("disabled", true);
				$("#btnaddbkbstandar").prop("disabled", true);
				$("#btnaddbkbkirimulang").prop("disabled", true);
				$("#slccheckerheaderpb").prop("disabled", true);

			} else if (picking_order_status == "In Progress") {

				document.getElementById("chkpb").checked = true;

				$("#chkpb").prop("disabled", true);
				$("#btnkonfirmasibarang").prop("disabled", false);
				$("#btnsavebkb").prop("disabled", true);
				$("#btnbatalbkb").prop("disabled", true);
				$("#btnlihatbkbbulk").prop("disabled", false);
				$("#btnlihatbkbstandar").prop("disabled", false);
				$("#btnaddbkbbulk").prop("disabled", false);
				$("#btnaddbkbstandar").prop("disabled", false);
				$("#btnaddbkbkirimulang").prop("disabled", false);
				$("#slccheckerpb").prop("disabled", true);
				$("#slccheckerheaderpb").prop("disabled", true);

			} else if (picking_order_status == "Cancel") {

				document.getElementById("chkpb").checked = false;

				$("#chkpb").prop("disabled", true);
				$("#btnkonfirmasibarang").prop("disabled", true);
				$("#btnsavebkb").prop("disabled", true);
				$("#btnbatalbkb").prop("disabled", true);
				$("#btnlihatbkbbulk").prop("disabled", true);
				$("#btnlihatbkbstandar").prop("disabled", true);
				$("#btnaddbkbbulk").prop("disabled", true);
				$("#btnaddbkbstandar").prop("disabled", true);
				$("#btnaddbkbkirimulang").prop("disabled", true);
				$("#slccheckerpb").prop("disabled", true);
				$("#slccheckerheaderpb").prop("disabled", true);

			} else if (picking_order_status == "Completed") {

				document.getElementById("chkpb").checked = true;

				$("#chkpb").prop("disabled", true);
				$("#btnkonfirmasibarang").prop("disabled", true);
				$("#btnsavebkb").prop("disabled", true);
				$("#btnbatalbkb").prop("disabled", true);
				$("#btnlihatbkbbulk").prop("disabled", false);
				$("#btnlihatbkbstandar").prop("disabled", false);
				$("#btnaddbkbbulk").prop("disabled", true);
				$("#btnaddbkbstandar").prop("disabled", true);
				$("#btnaddbkbkirimulang").prop("disabled", true);
				$("#txtketerangan").prop("disabled", true);
				$("#slccheckerpb").prop("disabled", true);
				$("#slccheckerheaderpb").prop("disabled", true);
			}

			if (tipe_delivery_order_nama == "Bulk" || tipe_delivery_order_nama == "Flush Out") {
				var no = 1;
				var jumlah_barang = 0;

				$("#panelbkbbulk").show();
				$("#panelbkbstandar").hide();
				$("#panelbkbkirimulang").hide();
				$("#panelbkbcanvas").hide();
				$(".fieldFormChecker").show();

				$("#txtcheckerpb").val(karyawan_id + ' || ' + karyawan_nama);

				if ($.fn.DataTable.isDataTable('#tabledetailpbbulk')) {
					$('#tabledetailpbbulk').DataTable().destroy();
				}

				$('#tabledetailpbbulk tbody').empty();

				for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
					var picking_order_plan_id = Channel.PengeluaranBarang[i].picking_order_plan_id;
					var principal = Channel.PengeluaranBarang[i].principal;
					var sku_kode = Channel.PengeluaranBarang[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarang[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarang[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarang[i].sku_satuan;
					var sku_stock_qty_ambil = Channel.PengeluaranBarang[i].sku_stock_qty_ambil;
					var sku_stock_qty_ambil_aktual = Channel.PengeluaranBarang[i].sku_stock_qty_ambil_aktual;
					var sku_stock_expired_date = Channel.PengeluaranBarang[i].sku_stock_expired_date;
					var sku_stock_expired_date_aktual = Channel.PengeluaranBarang[i].sku_stock_expired_date_aktual;
					var rak_nama = Channel.PengeluaranBarang[i].rak_nama;
					var karyawan_nama = Channel.PengeluaranBarang[i].karyawan_nama;
					jumlah_barang += sku_stock_qty_ambil_aktual;

					pushPrincipleToAray('bulk', principal)

					var strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + no + '</td>';
					strmenu = strmenu + '	<td>' + principal + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
					// strmenu = strmenu + '	<td>' + rak_nama + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_aktual) + '</td>';
					// strmenu = strmenu + '	<td>'+ sku_stock_expired_date_aktual +'</td>';
					// strmenu = strmenu + '	<td>' + karyawan_nama + '</td>';
					strmenu = strmenu + '	<td>';
					strmenu = strmenu + '		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' + picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
					strmenu = strmenu + '	</td>';
					strmenu = strmenu + '</tr>';

					$("#tabledetailpbbulk > tbody").append(strmenu);
					no++;
				}

				$("#txtjumlahbarang").val(jumlah_barang);
				$('#tabledetailpbbulk').DataTable({
					"lengthMenu": [
						[10],
						[10]
					]
				});
			} else if (tipe_delivery_order_nama == "Standar") {
				var no = 1;
				var jumlah_barang = 0;

				$("#panelbkbstandar").show();
				$("#panelbkbbulk").hide();
				$("#panelbkbkirimulang").hide();
				$("#panelbkbcanvas").hide();
				$(".fieldFormChecker").hide();

				$("#txtcheckerpb").val(karyawan_id + ' || ' + karyawan_nama);

				if (picking_order_status == "Draft") {

					$('#slccheckerpb').append('<option value="">** Pilih Checker Gudang **</option>');
					if (Channel.Checker != 0) {
						for (i = 0; i < Channel.Checker.length; i++) {
							karyawan_id_checker = Channel.Checker[i].karyawan_id;
							karyawan_nama_checker = Channel.Checker[i].karyawan_nama;

							if (karyawan_id == karyawan_id_checker) {
								$("#slccheckerpb").append('<option value="' + karyawan_id_checker + ' || ' + karyawan_nama_checker + '" selected>' + karyawan_nama_checker + '</option>');
							} else {
								$("#slccheckerpb").append('<option value="' + karyawan_id_checker + ' || ' + karyawan_nama_checker + '">' + karyawan_nama_checker + '</option>');
							}
						}
					}

				} else if (picking_order_status == "In Progress") {

					$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '" selected>' + karyawan_nama + '</option>');

				} else if (picking_order_status == "Cancel") {

					$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '" selected>' + karyawan_nama + '</option>');

				} else if (picking_order_status == "Completed") {

					$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '" selected>' + karyawan_nama + '</option>');
				}

				if ($.fn.DataTable.isDataTable('#tabledetailpbstandar')) {
					$('#tabledetailpbstandar').DataTable().destroy();
				}

				$('#tabledetailpbstandar tbody').empty();

				for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
					var picking_order_plan_id = Channel.PengeluaranBarang[i].picking_order_plan_id;
					var delivery_order_kode = Channel.PengeluaranBarang[i].delivery_order_kode;
					var principal = Channel.PengeluaranBarang[i].principal;
					var sku_kode = Channel.PengeluaranBarang[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarang[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarang[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarang[i].sku_satuan;
					var sku_stock_qty_ambil = Channel.PengeluaranBarang[i].sku_stock_qty_ambil;
					var sku_stock_qty_ambil_aktual = Channel.PengeluaranBarang[i].sku_stock_qty_ambil_aktual;
					var sku_stock_expired_date = Channel.PengeluaranBarang[i].sku_stock_expired_date;
					var sku_stock_expired_date_aktual = Channel.PengeluaranBarang[i].sku_stock_expired_date_aktual;
					var rak_nama = Channel.PengeluaranBarang[i].rak_nama;
					jumlah_barang += sku_stock_qty_ambil_aktual;

					pushPrincipleToAray('standar', principal)

					var strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + `	<td>${no} <input type="hidden" value="${picking_order_plan_id}"></td>`;
					strmenu = strmenu + '	<td>' + delivery_order_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
					strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
					strmenu = strmenu + '	<td>' + rak_nama + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_aktual) + '</td>';
					// strmenu = strmenu + '	<td>'+ sku_stock_expired_date_aktual +'</td>';
					strmenu = strmenu + '	<td>';
					strmenu = strmenu + '		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' + picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
					strmenu = strmenu + '	</td>';
					strmenu = strmenu + '</tr>';

					$("#tabledetailpbstandar > tbody").append(strmenu);
					no++;
				}

				$("#txtjumlahbarang").val(jumlah_barang);
				$('#tabledetailpbstandar').DataTable({
					"lengthMenu": [
						[10],
						[10]
					]
				});
			} else if (tipe_delivery_order_nama == "Reschedule") {
				var no = 1;
				var jumlah_barang = 0;

				$("#panelbkbbulk").hide();
				$("#panelbkbstandar").hide();
				$("#panelbkbkirimulang").show();
				$("#panelbkbcanvas").hide();
				$(".fieldFormChecker").show();

				$("#txtcheckerpb").val(karyawan_id + ' || ' + karyawan_nama);

				if ($.fn.DataTable.isDataTable('#tabledetailpbkirimulang')) {
					$('#tabledetailpbkirimulang').DataTable().destroy();
				}

				$('#tabledetailpbkirimulang tbody').empty();

				for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
					var picking_order_plan_id = Channel.PengeluaranBarang[i].picking_order_plan_id;
					var principal = Channel.PengeluaranBarang[i].principal;
					var sku_kode = Channel.PengeluaranBarang[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarang[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarang[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarang[i].sku_satuan;
					var sku_stock_qty_ambil = Channel.PengeluaranBarang[i].sku_stock_qty_ambil;
					var sku_stock_qty_ambil_aktual = Channel.PengeluaranBarang[i].sku_stock_qty_ambil_aktual;
					var sku_stock_expired_date = Channel.PengeluaranBarang[i].sku_stock_expired_date;
					var sku_stock_expired_date_aktual = Channel.PengeluaranBarang[i].sku_stock_expired_date_aktual;
					var rak_nama = Channel.PengeluaranBarang[i].rak_nama;
					var karyawan_nama = Channel.PengeluaranBarang[i].karyawan_nama;
					jumlah_barang += sku_stock_qty_ambil_aktual;

					pushPrincipleToAray('reschedule', principal)

					var strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + no + '</td>';
					strmenu = strmenu + '	<td>' + principal + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
					// strmenu = strmenu + '	<td>' + rak_nama + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_aktual) + '</td>';
					// strmenu = strmenu + '	<td>'+ sku_stock_expired_date_aktual +'</td>';
					// strmenu = strmenu + '	<td>' + karyawan_nama + '</td>';
					strmenu = strmenu + '	<td>';
					strmenu = strmenu + '		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' + picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
					strmenu = strmenu + '	</td>';
					strmenu = strmenu + '</tr>';

					$("#tabledetailpbkirimulang > tbody").append(strmenu);
					no++;
				}

				$("#txtjumlahbarang").val(jumlah_barang);
				$('#tabledetailpbkirimulang').DataTable({
					"lengthMenu": [
						[10],
						[10]
					]
				});
			} else if (tipe_delivery_order_nama == "Canvas") {
				var no = 1;
				var jumlah_barang = 0;

				$("#panelbkbbulk").hide();
				$("#panelbkbstandar").hide();
				$("#panelbkbkirimulang").hide();
				$("#panelbkbcanvas").show();
				$(".fieldFormChecker").show();

				$("#txtcheckerpb").val(karyawan_id + ' || ' + karyawan_nama);

				if ($.fn.DataTable.isDataTable('#tabledetailpbcanvas')) {
					$('#tabledetailpbcanvas').DataTable().destroy();
				}

				$('#tabledetailpbcanvas tbody').empty();

				for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
					var picking_order_plan_id = Channel.PengeluaranBarang[i].picking_order_plan_id;
					var principal = Channel.PengeluaranBarang[i].principal;
					var sku_kode = Channel.PengeluaranBarang[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarang[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarang[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarang[i].sku_satuan;
					var sku_stock_qty_ambil = Channel.PengeluaranBarang[i].sku_stock_qty_ambil;
					var sku_stock_qty_ambil_aktual = Channel.PengeluaranBarang[i].sku_stock_qty_ambil_aktual;
					var sku_stock_expired_date = Channel.PengeluaranBarang[i].sku_stock_expired_date;
					var sku_stock_expired_date_aktual = Channel.PengeluaranBarang[i].sku_stock_expired_date_aktual;
					var rak_nama = Channel.PengeluaranBarang[i].rak_nama;
					var karyawan_nama = Channel.PengeluaranBarang[i].karyawan_nama;
					jumlah_barang += sku_stock_qty_ambil_aktual;

					pushPrincipleToAray('canvas', principal)

					var strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + no + '</td>';
					strmenu = strmenu + '	<td>' + principal + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
					// strmenu = strmenu + '	<td>' + rak_nama + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_aktual) + '</td>';
					// strmenu = strmenu + '	<td>'+ sku_stock_expired_date_aktual +'</td>';
					// strmenu = strmenu + '	<td>' + karyawan_nama + '</td>';
					strmenu = strmenu + '	<td>';
					strmenu = strmenu + '		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' + picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
					strmenu = strmenu + '	</td>';
					strmenu = strmenu + '</tr>';

					$("#tabledetailpbcanvas > tbody").append(strmenu);
					no++;
				}

				$("#txtjumlahbarang").val(jumlah_barang);
				$('#tabledetailpbcanvas').DataTable({
					"lengthMenu": [
						[10],
						[10]
					]
				});
			} else if (tipe_delivery_order_nama == "Mix") {
				var picking_order_kode = $("#txtnoppbhidden").val();
				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDetailPengeluaranBarangMixMenu') ?>",
					data: "picking_order_kode=" + picking_order_kode,
					dataType: "JSON",
					success: function(response) {
						ChPengeluaranBarangMixMenu(response, picking_order_status);
					}
				});
			}
		}
	}


	function ChPengeluaranBarangMixMenu(Channel, status) {
		console.log(Channel.PengeluaranBarang);
		$("#tabledetailpbbulk > tbody").html('');
		$("#tabledetailpbstandar > tbody").html('');
		$("#tabledetailpbkirimulang > tbody").html('');
		$("#slccheckerheaderpb").html('');

		// var Channel = JSON.parse(JSONChannel);

		var no = 1,
			no2 = 1,
			no3 = 1;
		let countDataBulk = 0,
			countDataStandar = 0,
			countDataKirimUlang = 0;
		var jumlah_barang = 0;

		if (Channel.PengeluaranBarang != 0) {
			$("#panelbkbstandar").show();
			$("#panelbkbbulk").show();
			$("#panelbkbkirimulang").show();
			$("#panelbkbcanvas").hide();


			if ($.fn.DataTable.isDataTable('#tabledetailpbbulk')) {
				$('#tabledetailpbbulk').DataTable().destroy();
			}

			$('#tabledetailpbbulk tbody').empty();

			if ($.fn.DataTable.isDataTable('#tabledetailpbstandar')) {
				$('#tabledetailpbstandar').DataTable().destroy();
			}

			$('#tabledetailpbstandar tbody').empty();

			if ($.fn.DataTable.isDataTable('#tabledetailpbkirimulang')) {
				$('#tabledetailpbkirimulang').DataTable().destroy();
			}

			$('#tabledetailpbkirimulang tbody').empty();

			$.each(Channel.PengeluaranBarang, function(i, v) {
				let picking_order_id = v.picking_order_id;
				let picking_order_kode = v.picking_order_kode;
				let picking_list_id = v.picking_list_id;
				let depo_id = v.depo_id;
				let depo_detail_id = v.depo_detail_id;
				let depo_detail_nama = v.depo_detail_nama;
				let picking_list_kode = v.picking_list_kode;
				let delivery_order_batch_kode = v.delivery_order_batch_kode;
				let picking_list_tipe = v.picking_list_tipe;
				let picking_order_plan_id = v.picking_order_plan_id;
				let picking_order_plan_tipe = v.picking_order_plan_tipe;
				let tipe_delivery_order_nama = v.tipe_delivery_order_nama;
				let picking_order_tanggal = v.picking_order_tanggal;
				let picking_order_status = v.picking_order_status;
				let picking_order_keterangan = v.picking_order_keterangan;
				let karyawan_id = v.karyawan_id;
				let karyawan_nama = v.karyawan_nama;
				let delivery_order_kode = v.delivery_order_kode;
				let principal = v.principal;
				let sku_kode = v.sku_kode;
				let sku_nama_produk = v.sku_nama_produk;
				let sku_kemasan = v.sku_kemasan;
				let sku_satuan = v.sku_satuan;
				let sku_stock_qty_ambil = v.sku_stock_qty_ambil;
				let sku_stock_qty_ambil_aktual = v.sku_stock_qty_ambil_aktual;
				let sku_stock_expired_date = v.sku_stock_expired_date;
				let sku_stock_expired_date_aktual = v.sku_stock_expired_date_aktual;
				let rak_nama = v.rak_nama;
				jumlah_barang += sku_stock_qty_ambil_aktual;

				$("#slccheckerheaderpb").append('<option value="' + karyawan_id + '">' + karyawan_nama + '</option>');
				$("#txtcheckerpb").val(karyawan_id + ' || ' + karyawan_nama);
				if (['Bulk', 'Standar', 'Flush Out'].includes(tipe_delivery_order_nama)) {
					$("#tipeBKBMix").val(tipe_delivery_order_nama);
				}

				if (tipe_delivery_order_nama == "Bulk") {
					$(".fieldFormChecker").show();
					countDataBulk++;

					pushPrincipleToAray('bulk', principal)

					let strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + no + '</td>';
					strmenu = strmenu + '	<td>' + principal + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
					// strmenu = strmenu + '	<td>' + rak_nama + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_aktual) + '</td>';
					// strmenu = strmenu + '	<td>'+ sku_stock_expired_date_aktual +'</td>';
					// strmenu = strmenu + '	<td>' + karyawan_nama + '</td>';
					strmenu = strmenu + '	<td>';
					strmenu = strmenu + '		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' + picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
					strmenu = strmenu + '	</td>';
					strmenu = strmenu + '</tr>';

					$("#tabledetailpbbulk > tbody").append(strmenu);
					no++;

				} else if (tipe_delivery_order_nama == "Standar") {
					$(".fieldFormChecker").hide();
					countDataStandar++;

					pushPrincipleToAray('standar', principal)

					let strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + `	<td>${no2} <input type="hidden" value="${picking_order_plan_id}"></td>`;
					strmenu = strmenu + '	<td>' + delivery_order_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
					strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
					strmenu = strmenu + '	<td>' + rak_nama + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_aktual) + '</td>';
					// strmenu = strmenu + '	<td>'+ sku_stock_expired_date_aktual +'</td>';
					strmenu = strmenu + '	<td>';
					strmenu = strmenu + '		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' + picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
					strmenu = strmenu + '	</td>';
					strmenu = strmenu + '</tr>';

					$("#tabledetailpbstandar > tbody").append(strmenu);
					no2++;


					if (status == "Draft") {

						$('#slccheckerpb').append('<option value="">** Pilih Checker Gudang **</option>');
						if (Channel.Checker != 0) {
							$("#slccheckerpb").empty();
							$.each(Channel.Checker, function(index, value) {
								let karyawan_id_checker = value.karyawan_id;
								let karyawan_nama_checker = value.karyawan_nama;

								if (karyawan_id == karyawan_id_checker) {
									$("#slccheckerpb").append('<option value="' + karyawan_id_checker + ' || ' + karyawan_nama_checker + '" selected>' + karyawan_nama_checker + '</option>');
								} else {
									$("#slccheckerpb").append('<option value="' + karyawan_id_checker + ' || ' + karyawan_nama_checker + '">' + karyawan_nama_checker + '</option>');
								}
							})
						}

					} else if (status == "In Progress") {

						$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '" selected>' + karyawan_nama + '</option>');

					} else if (status == "Cancel") {

						$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '" selected>' + karyawan_nama + '</option>');

					} else if (status == "Completed") {

						$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '" selected>' + karyawan_nama + '</option>');
					}

				} else if (tipe_delivery_order_nama == "Reschedule") {
					$(".fieldFormChecker").show();
					countDataKirimUlang++;

					pushPrincipleToAray('reschedule', principal)

					let strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + no3 + '</td>';
					strmenu = strmenu + '	<td>' + principal + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
					// strmenu = strmenu + '	<td>' + rak_nama + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_aktual) + '</td>';
					// strmenu = strmenu + '	<td>'+ sku_stock_expired_date_aktual +'</td>';
					// strmenu = strmenu + '	<td>' + karyawan_nama + '</td>';
					strmenu = strmenu + '	<td>';
					strmenu = strmenu + '		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' + picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
					strmenu = strmenu + '	</td>';
					strmenu = strmenu + '</tr>';

					$("#tabledetailpbkirimulang > tbody").append(strmenu);
					no3++;

				}
			})

			if (countDataBulk == 0) $("#panelbkbbulk").hide()
			if (countDataStandar == 0) $("#panelbkbstandar").hide()
			if (countDataKirimUlang == 0) $("#panelbkbkirimulang").hide()

			$("#txtjumlahbarang").val(jumlah_barang);
			$('#tabledetailpbbulk').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
			$('#tabledetailpbstandar').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
			$('#tabledetailpbkirimulang').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
		}
	}

	$("#btnbackbulk").click(
		function() {
			$("#previewformbkbbulk").modal("hide");
			ResetForm();
			GetPengeluaranBarangMenu();
		}
	);

	$("#btnbackstandar").click(
		function() {
			ResetForm();
			GetPengeluaranBarangMenu();
		}
	);
	$("#btnbackkirimulang").click(
		function() {
			ResetForm();
			GetPengeluaranBarangMenu();
		}
	);

	$("#btnlihatbkbstandar").click(
		function() {
			ResetForm();
			$("#previewformbkbstandar").modal('show');
			var picking_order_id = $("#txtidppb").val();
			$("#loadingbkbstandar").show();

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDaftarBKB') ?>",
				data: {
					picking_order_id: picking_order_id,
					tipe_bkb: 'Standar'
				},
				success: function(response) {
					if (response) {
						ChDaftarBKBMenu(response);
					}
				}
			});
		}
	);

	// Add New Channel
	$("#btnlihatbkbbulk").click(
		function() {
			ResetForm();
			$("#previewformbkbbulk").modal('show');
			var picking_order_id = $("#txtidppb").val();
			$("#loadingbkbbulk").show();

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDaftarBKB') ?>",
				data: {
					picking_order_id: picking_order_id,
					tipe_bkb: 'Bulk'
				},
				success: function(response) {
					if (response) {
						ChDaftarBKBMenu(response);
					}
				}
			});
		}
	);

	$("#btnlihatbkbkirimulang").click(
		function() {
			ResetForm();
			$("#previewformbkbkirimulang").modal('show');
			var picking_order_id = $("#txtidppb").val();
			$("#loadingbkbkirimulang").show();

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDaftarBKB') ?>",
				data: {
					picking_order_id: picking_order_id,
					tipe_bkb: 'Reschedule'
				},
				success: function(response) {
					if (response) {
						ChDaftarBKBMenu(response);
					}
				}
			});
		}
	);

	const displayPrincipleWhenClickAction = (type) => {
		return principleArray.reduce((unique, o) => {
			if (!unique.some(obj => obj.principle === o.principle && obj.typeBKB === o.typeBKB)) {
				unique.push(o);
			}
			return unique;
		}, []).filter((value) => value.typeBKB === type);
	}

	// Add New Channel
	$("#btnlihatbkbcanvas").click(
		function() {
			ResetForm();
			$("#previewformbkbcanvas").modal('show');
			var picking_order_id = $("#txtidppb").val();
			$("#loadingbkbcanvas").show();

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDaftarBKB') ?>",
				data: {
					picking_order_id: picking_order_id,
					tipe_bkb: 'Canvas'
				},
				success: function(response) {
					if (response) {
						ChDaftarBKBMenu(response);
					}
				}
			});
		}
	);

	function ChDaftarBKBMenu(JSONChannel) {
		$("#tablebkbbulk > tbody").html('');
		$("#tablebkbstandar > tbody").html('');
		$("#tablebkbkirimulang > tbody").html('');
		$("#tablebkbcanvas > tbody").html('');

		var picking_order_id = $("#txtidppb").val();
		var picking_order_kode = $("#txtnoppb").val();
		var picking_list_kode = $("#slcnopb").val();
		var delivery_order_batch_kode = $("#txtnofdjr").val();

		$("#txtidppbbulk").val(picking_order_id);
		$("#txtnoppbbulk").val(picking_order_kode);
		$("#txtnopbbulk").val(picking_list_kode);
		$("#txtnofdjrbkb").val(delivery_order_batch_kode);

		$("#txtidppbstandar").val(picking_order_id);
		$("#txtnoppbstandar").val(picking_order_kode);
		$("#txtnopbstandar").val(picking_list_kode);
		$("#txtnofdjrstandar").val(delivery_order_batch_kode);

		$("#txtidppbkirimulang").val(picking_order_id);
		$("#txtnoppbkirimulang").val(picking_order_kode);
		$("#txtnopbkirimulang").val(picking_list_kode);
		$("#txtnofdjrbkbkirimulang").val(delivery_order_batch_kode);

		$("#txtidppbcanvas").val(picking_order_id);
		$("#txtnoppbcanvas").val(picking_order_kode);
		$("#txtnopbcanvas").val(picking_list_kode);
		$("#txtnofdjrbkbcanvas").val(delivery_order_batch_kode);

		$("#loadingbkbbulk").hide();
		$("#loadingbkbstandar").hide();
		$("#loadingbkbkirimulang").hide();
		$("#loadingbkbcanvas").hide();

		var Channel = JSON.parse(JSONChannel);

		if (Channel.PengeluaranBarang != 0) {


			var no = 1;

			if ($.fn.DataTable.isDataTable('#tablebkbbulk')) {
				$('#tablebkbbulk').DataTable().destroy();
			}

			if ($.fn.DataTable.isDataTable('#tablebkbstandar')) {
				$('#tablebkbstandar').DataTable().destroy();
			}

			if ($.fn.DataTable.isDataTable('#tablebkbkirimulang')) {
				$('#tablebkbkirimulang').DataTable().destroy();
			}

			if ($.fn.DataTable.isDataTable('#tablebkbcanvas')) {
				$('#tablebkbcanvas').DataTable().destroy();
			}

			$('#tablebkbbulk tbody').empty();
			$('#tablebkbstandar tbody').empty();
			$('#tablebkbkirimulang tbody').empty();
			$('#tablebkbcanvas tbody').empty();

			for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
				var karyawan_id = Channel.PengeluaranBarang[i].karyawan_id;
				var karyawan_nama = Channel.PengeluaranBarang[i].karyawan_nama;
				var picking_order_aktual_h_id = Channel.PengeluaranBarang[i].picking_order_aktual_h_id;
				var picking_order_aktual_kode = Channel.PengeluaranBarang[i].picking_order_aktual_kode;
				var picking_order_aktual_tgl = Channel.PengeluaranBarang[i].picking_order_aktual_tgl;
				var tipe_bkb = Channel.PengeluaranBarang[i].tipe_bkb;
				var tipe_delivery_order_nama = Channel.PengeluaranBarang[i].tipe_delivery_order_nama;

				var strmenu = '';

				var strU = '';
				var strD = '';

				<?php
				if ($Menu_Access["U"] == 1) {
				?>
					if (tipe_delivery_order_nama == "Standar") {
						strU = '<button style="width: 40px;" class="btn btn-warning btneditbkb form-control" onclick="UpdateBKBStandar(\'' + picking_order_aktual_h_id + '\')"+><i class="fa fa-pencil"></i></button><button style="width: 40px;" class="btn btn-primary form-control" onclick="CetakBKBById(\'' + picking_order_aktual_h_id + '\')"+><i class="fa fa-print"></i></button>';
					} else if (tipe_delivery_order_nama == "Bulk") {
						strU = '<button style="width: 40px;" class="btn btn-warning btneditbkb form-control" onclick="UpdateBKBBulk(\'' + picking_order_aktual_h_id + '\')"+><i class="fa fa-pencil"></i></button><button style="width: 40px;" class="btn btn-primary form-control" onclick="CetakBKBById(\'' + picking_order_aktual_h_id + '\')"+><i class="fa fa-print"></i></button>';
					} else if (tipe_delivery_order_nama == "Reschedule") {
						strU = '<button style="width: 40px;" class="btn btn-warning btneditbkb form-control" onclick="UpdateBKBKirimUlang(\'' + picking_order_aktual_h_id + '\')"+><i class="fa fa-pencil"></i></button><button style="width: 40px;" class="btn btn-primary form-control" onclick="CetakBKBById(\'' + picking_order_aktual_h_id + '\')"+><i class="fa fa-print"></i></button>';
					} else if (tipe_delivery_order_nama == "Canvas") {
						strU = '<button style="width: 40px;" class="btn btn-warning btneditbkb form-control" onclick="UpdateBKBCanvas(\'' + picking_order_aktual_h_id + '\')"+><i class="fa fa-pencil"></i></button><button style="width: 40px;" class="btn btn-primary form-control" onclick="CetakBKBById(\'' + picking_order_aktual_h_id + '\')"+><i class="fa fa-print"></i></button>';
					}
				<?php
				}
				?>

				if (picking_order_aktual_h_id == null) {

				} else {

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + picking_order_aktual_kode + '</td>';
					strmenu = strmenu + '	<td>' + karyawan_nama + '</td>';
					strmenu = strmenu + '	<td>' + picking_order_aktual_tgl + '</td>';
					strmenu = strmenu + '	<td>' + karyawan_nama + '</td>';
					strmenu = strmenu + '	<td><span>';
					strmenu = strmenu + strU;
					strmenu = strmenu + '	</span></td>';
					strmenu = strmenu + '</tr>';

					$("#tablebkbbulk > tbody").append(strmenu);
					$("#tablebkbstandar > tbody").append(strmenu);
					$("#tablebkbkirimulang > tbody").append(strmenu);
					$("#tablebkbcanvas > tbody").append(strmenu);
					no++;

				}
			}

			$('#tablebkbstandar').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
			$('#tablebkbbulk').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
			$('#tablebkbkirimulang').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});
			$('#tablebkbcanvas').DataTable({
				"lengthMenu": [
					[10],
					[10]
				]
			});

		}

	}

	function GetDetailBKBAktualPlan(picking_order_id, picking_order_plan_id) {
		$("#previewdetailbkbaktualplan").modal('show');

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDetailBKBAktualPlan') ?>",
			data: {
				picking_order_id: picking_order_id,
				picking_order_plan_id: picking_order_plan_id
			},
			success: function(response) {
				if (response) {
					ChGetDetailBKBAktualPlanTable(response);
				}
			}
		});

	}

	function ChGetDetailBKBAktualPlanTable(JSONChannel) {
		$("#tablebkbaktualplan > tbody").html('');

		var Channel = JSON.parse(JSONChannel);

		if (Channel.PengeluaranBarang != 0) {
			if ($.fn.DataTable.isDataTable('#tablebkbaktualplan')) {
				$('#tablebkbaktualplan').DataTable().destroy();
			}

			$('#tablebkbaktualplan tbody').empty();

			for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
				var picking_order_aktual_kode = Channel.PengeluaranBarang[i].picking_order_aktual_kode;
				var principal = Channel.PengeluaranBarang[i].principal;
				var brand = Channel.PengeluaranBarang[i].brand;
				var sku_id = Channel.PengeluaranBarang[i].sku_id;
				var sku_kode = Channel.PengeluaranBarang[i].sku_kode;
				var sku_nama_produk = Channel.PengeluaranBarang[i].sku_nama_produk;
				var sku_kemasan = Channel.PengeluaranBarang[i].sku_kemasan;
				var sku_satuan = Channel.PengeluaranBarang[i].sku_satuan;
				var picking_order_plan_id = Channel.PengeluaranBarang[i].picking_order_plan_id;
				var picking_order_plan_nourut = Channel.PengeluaranBarang[i].picking_order_plan_nourut;
				var sku_stock_qty_ambil_plan = Channel.PengeluaranBarang[i].sku_stock_qty_ambil_plan;
				var sku_stock_expired_date_plan = Channel.PengeluaranBarang[i].sku_stock_expired_date_plan;
				var picking_order_aktual_nourut = Channel.PengeluaranBarang[i].picking_order_aktual_nourut;
				var sku_stock_qty_ambil_aktual = Channel.PengeluaranBarang[i].sku_stock_qty_ambil_aktual;
				var sku_stock_expired_date_aktual = Channel.PengeluaranBarang[i].sku_stock_expired_date_aktual;

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>' + picking_order_aktual_kode + '</td>';
				strmenu = strmenu + '	<td>' + principal + '</td>';
				strmenu = strmenu + '	<td>' + brand + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + picking_order_plan_nourut + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_qty_ambil_plan + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_expired_date_plan + '</td>';
				strmenu = strmenu + '	<td>' + picking_order_aktual_nourut + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_qty_ambil_aktual + '</td>';
				strmenu = strmenu + '	<td>' + sku_stock_expired_date_aktual + '</td>';
				strmenu = strmenu + '</tr>';

				$("#tablebkbaktualplan > tbody").append(strmenu);
			}

			$('#tablebkbaktualplan').DataTable({
				"lengthMenu": [
					[10],
					[10]
				],
				"ordering": false
			});

		}

	}

	$("#btncetakbkbbulk").click(
		function() {
			var picking_order_id = $("#txtidppb").val();
			var driverppb = $("#driverppb").val();

			Swal.fire({
				title: '',
				icon: '',
				html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
				showCancelButton: true,
				showCloseButton: true,
				confirmButtonText: 'Per Satuan',
				cancelButtonText: 'Komposite',
				confirmButtonColor: '#2c3e50',
				cancelButtonColor: '#2c3e50'
			}).then((result) => {
				if (result.value) {
					Swal.fire({
						title: '',
						icon: '',
						html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
						showCancelButton: true,
						showCloseButton: true,
						confirmButtonText: 'Per Principle',
						cancelButtonText: 'All Principle',
						confirmButtonColor: '#2c3e50',
						cancelButtonColor: '#2c3e50'
					}).then((result) => {
						if (result.value) {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulk/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=perprinciple", "_blank");
						} else if (result.dismiss == "cancel") {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulk/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=allprinciple", "_blank");
						}
					});
				} else if (result.dismiss == "cancel") {
					Swal.fire({
						title: '',
						icon: '',
						html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
						showCancelButton: true,
						showCloseButton: true,
						confirmButtonText: 'Per Principle',
						cancelButtonText: 'All Principle',
						confirmButtonColor: '#2c3e50',
						cancelButtonColor: '#2c3e50'
					}).then((result) => {
						if (result.value) {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulkComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=perprinciple", "_blank");
						} else if (result.dismiss == "cancel") {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulkComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=allprinciple", "_blank");
						}
					});
				}
			});
		}
	);

	$("#btncetakbkbbulkplan").click(
		function() {
			var picking_order_id = $("#txtidppb").val();
			var driverppb = $("#driverppb").val();

			Swal.fire({
				title: '',
				icon: '',
				html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
				showCancelButton: true,
				showCloseButton: true,
				confirmButtonText: 'Per Satuan',
				cancelButtonText: 'Komposite',
				confirmButtonColor: '#2c3e50',
				cancelButtonColor: '#2c3e50'
			}).then((result) => {
				if (result.value) {
					Swal.fire({
						title: '',
						icon: '',
						html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
						showCancelButton: true,
						showCloseButton: true,
						confirmButtonText: 'Per Principle',
						cancelButtonText: 'All Principle',
						confirmButtonColor: '#2c3e50',
						cancelButtonColor: '#2c3e50'
					}).then((result) => {
						if (result.value) {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulk/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=perprinciple", "_blank");
						} else if (result.dismiss == "cancel") {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulk/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=allprinciple", "_blank");
						}
					});

					// window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulk/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan", "_blank");
				} else if (result.dismiss == "cancel") {
					Swal.fire({
						title: '',
						icon: '',
						html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
						showCancelButton: true,
						showCloseButton: true,
						confirmButtonText: 'Per Principle',
						cancelButtonText: 'All Principle',
						confirmButtonColor: '#2c3e50',
						cancelButtonColor: '#2c3e50'
					}).then((result) => {
						if (result.value) {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulkComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=perprinciple", "_blank");
						} else if (result.dismiss == "cancel") {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulkComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=allprinciple", "_blank");
						}
					});

					// window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulkComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan", "_blank");
				}
			});
		}
	);

	$("#btncetakbkbstandar").click(
		function() {
			var picking_order_id = $("#txtidppb").val();

			location.href = "<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBStandar/?picking_order_id=" + picking_order_id;
		}
	);

	$("#btncetakbkbKirimulangplan").click(function() {
		var picking_order_id = $("#txtidppb").val();
		var driverppb = $("#driverppb").val();

		Swal.fire({
			title: '',
			icon: '',
			html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
			showCancelButton: true,
			showCloseButton: true,
			confirmButtonText: 'Per Satuan',
			cancelButtonText: 'Komposite',
			confirmButtonColor: '#2c3e50',
			cancelButtonColor: '#2c3e50'
		}).then((result) => {
			if (result.value) {
				Swal.fire({
					title: '',
					icon: '',
					html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
					showCancelButton: true,
					showCloseButton: true,
					confirmButtonText: 'Per Principle',
					cancelButtonText: 'All Principle',
					confirmButtonColor: '#2c3e50',
					cancelButtonColor: '#2c3e50'
				}).then((result) => {
					if (result.value) {
						window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBKirimUlang/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=perprinciple", "_blank");
					} else if (result.dismiss == "cancel") {
						window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBKirimUlang/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=allprinciple", "_blank");
					}
				});
			} else if (result.dismiss == "cancel") {
				Swal.fire({
					title: '',
					icon: '',
					html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
					showCancelButton: true,
					showCloseButton: true,
					confirmButtonText: 'Per Principle',
					cancelButtonText: 'All Principle',
					confirmButtonColor: '#2c3e50',
					cancelButtonColor: '#2c3e50'
				}).then((result) => {
					if (result.value) {
						window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBKirimUlangComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=perprinciple", "_blank");
					} else if (result.dismiss == "cancel") {
						window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBKirimUlangComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=allprinciple", "_blank");
					}
				});
			}
		});
	});

	$("#btncetakbkbkirimulang").click(function() {
		var picking_order_id = $("#txtidppb").val();
		var driverppb = $("#driverppb").val();

		Swal.fire({
			title: '',
			icon: '',
			html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
			showCancelButton: true,
			showCloseButton: true,
			confirmButtonText: 'Per Satuan',
			cancelButtonText: 'Komposite',
			confirmButtonColor: '#2c3e50',
			cancelButtonColor: '#2c3e50'
		}).then((result) => {
			if (result.value) {
				Swal.fire({
					title: '',
					icon: '',
					html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
					showCancelButton: true,
					showCloseButton: true,
					confirmButtonText: 'Per Principle',
					cancelButtonText: 'All Principle',
					confirmButtonColor: '#2c3e50',
					cancelButtonColor: '#2c3e50'
				}).then((result) => {
					if (result.value) {
						window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBKirimUlang/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=perprinciple", "_blank");
					} else if (result.dismiss == "cancel") {
						window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBKirimUlang/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=allprinciple", "_blank");
					}
				});
			} else if (result.dismiss == "cancel") {
				Swal.fire({
					title: '',
					icon: '',
					html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
					showCancelButton: true,
					showCloseButton: true,
					confirmButtonText: 'Per Principle',
					cancelButtonText: 'All Principle',
					confirmButtonColor: '#2c3e50',
					cancelButtonColor: '#2c3e50'
				}).then((result) => {
					if (result.value) {
						window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBKirimUlangComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=perprinciple", "_blank");
					} else if (result.dismiss == "cancel") {
						window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBKirimUlangComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=allprinciple", "_blank");
					}
				});
			}
		});

		// location.href = "<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBKirimUlang/?picking_order_id=" + picking_order_id + "&driver=" + driverppb;
	});

	// $("#btncetakbkbcanvas").click(
	// 	function() {
	// 		var picking_order_id = $("#txtidppb").val();
	// 		var driverppb = $("#driverppb").val();

	// 		location.href = "<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBCanvas/?picking_order_id=" + picking_order_id + "&driver=" + driverppb;
	// 	}
	// );

	$("#btncetakbkbcanvas").click(
		function() {
			var picking_order_id = $("#txtidppb").val();
			var driverppb = $("#driverppb").val();

			Swal.fire({
				title: '',
				icon: '',
				html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
				showCancelButton: true,
				showCloseButton: true,
				confirmButtonText: 'Per Satuan',
				cancelButtonText: 'Komposite',
				confirmButtonColor: '#2c3e50',
				cancelButtonColor: '#2c3e50'
			}).then((result) => {
				if (result.value) {
					Swal.fire({
						title: '',
						icon: '',
						html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
						showCancelButton: true,
						showCloseButton: true,
						confirmButtonText: 'Per Principle',
						cancelButtonText: 'All Principle',
						confirmButtonColor: '#2c3e50',
						cancelButtonColor: '#2c3e50'
					}).then((result) => {
						if (result.value) {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBCanvas/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=perprinciple", "_blank");
						} else if (result.dismiss == "cancel") {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBCanvas/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=allprinciple", "_blank");
						}
					});
				} else if (result.dismiss == "cancel") {
					Swal.fire({
						title: '',
						icon: '',
						html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
						showCancelButton: true,
						showCloseButton: true,
						confirmButtonText: 'Per Principle',
						cancelButtonText: 'All Principle',
						confirmButtonColor: '#2c3e50',
						cancelButtonColor: '#2c3e50'
					}).then((result) => {
						if (result.value) {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBCanvasComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=perprinciple", "_blank");
						} else if (result.dismiss == "cancel") {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBCanvasComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=aktual&cetak=allprinciple", "_blank");
						}
					});
				}
			});
		}
	);

	$("#btncetakbkbcanvasplan").click(
		function() {
			var picking_order_id = $("#txtidppb").val();
			var driverppb = $("#driverppb").val();

			Swal.fire({
				title: '',
				icon: '',
				html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
				showCancelButton: true,
				showCloseButton: true,
				confirmButtonText: 'Per Satuan',
				cancelButtonText: 'Komposite',
				confirmButtonColor: '#2c3e50',
				cancelButtonColor: '#2c3e50'
			}).then((result) => {
				if (result.value) {
					Swal.fire({
						title: '',
						icon: '',
						html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
						showCancelButton: true,
						showCloseButton: true,
						confirmButtonText: 'Per Principle',
						cancelButtonText: 'All Principle',
						confirmButtonColor: '#2c3e50',
						cancelButtonColor: '#2c3e50'
					}).then((result) => {
						if (result.value) {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBCanvasComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=perprinciple", "_blank");
						} else if (result.dismiss == "cancel") {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBCanvasComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=allprinciple", "_blank");
						}
					});

					// window.open("<?php echo base_url(); ?>PengeluaranBarang/CetakBKBBulk/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan", "_blank");
				} else if (result.dismiss == "cancel") {
					Swal.fire({
						title: '',
						icon: '',
						html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
						showCancelButton: true,
						showCloseButton: true,
						confirmButtonText: 'Per Principle',
						cancelButtonText: 'All Principle',
						confirmButtonColor: '#2c3e50',
						cancelButtonColor: '#2c3e50'
					}).then((result) => {
						if (result.value) {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBCanvasComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=perprinciple", "_blank");
						} else if (result.dismiss == "cancel") {
							window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBCanvasComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan&cetak=allprinciple", "_blank");
						}
					});

					// window.open("<?php echo base_url(); ?>PengeluaranBarang/CetakBKBBulkComposite/?picking_order_id=" + picking_order_id + "&driver=" + driverppb + "&tipe=plan", "_blank");
				}
			});
		}
	);

	$("#btncetaklabel").click(
		function() {
			var picking_order_id = $("#txtidppb").val();

			location.href = "<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakLabel/?picking_order_id=" + picking_order_id;
		}
	);

	$("#btnbackaddbkbstandar").click(
		function() {
			ResetForm();
			$("#previewformbkbstandar").modal('show');
			$("#previewaddformbkbstandar").modal('hide');
		}
	);

	$("#btnbackupdatebkbstandar").click(
		function() {
			ResetForm();
			$("#previewformbkbstandar").modal('show');
			$("#previewupdateformbkbstandar").modal('hide');
		}
	);

	$("#btnbackaddbkbbulk").click(
		function() {
			ResetForm();
			$("#previewformbkbbulk").modal('show');
			$("#previewaddformbkbbulk").modal('hide');
		}
	);

	$("#btnbackupdatebkbbulk").click(
		function() {
			ResetForm();
			$("#previewformbkbbulk").modal('show');
			$("#previewupdateformbkbbulk").modal('hide');
		}
	);

	$("#btnbackaddbkbkirimulang").click(
		function() {
			ResetForm();
			$("#previewformbkbkirimulang").modal('show');
			$("#previewaddformbkbkirimulang").modal('hide');
		}
	);

	$("#btnbackupdatebkbkirimulang").click(
		function() {
			ResetForm();
			$("#previewformbkbkirimulang").modal('show');
			$("#previewupdateformbkbkirimulang").modal('hide');
		}
	);

	$("#btnbackaddbkbcanvas").click(
		function() {
			ResetForm();
			$("#previewformbkbcanvas").modal('show');
			$("#previewaddformbkbcanvas").modal('hide');
		}
	);

	$("#btnbackupdatebkbcanvas").click(
		function() {
			ResetForm();
			$("#previewformbkbcanvas").modal('show');
			$("#previewupdateformbkbcanvas").modal('hide');
		}
	);

	<?php
	if ($Menu_Access["D"] == 1) {
	?>

	<?php
	}
	?>

	<?php
	if ($Menu_Access["C"] == 1) {
	?>

		function UpdateBKBStandar(picking_order_aktual_h_id) {
			$("#previewformbkbstandar").modal('hide');
			$("#previewupdateformbkbstandar").modal('show');

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDetailBKB') ?>",
				data: "picking_order_aktual_h_id=" + picking_order_aktual_h_id,
				success: function(response) {
					if (response) {
						ChBKBStandarDetailMenu(response);
					}
				}
			});
		}

		function UpdateBKBBulk(picking_order_aktual_h_id) {
			$("#previewformbkbbulk").modal('hide');
			$("#previewupdateformbkbbulk").modal('show');

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDetailBKB') ?>",
				data: "picking_order_aktual_h_id=" + picking_order_aktual_h_id,
				success: function(response) {
					if (response) {
						ChBKBBulkDetailMenu(response);
					}
				}
			});
		}

		function UpdateBKBKirimUlang(picking_order_aktual_h_id) {
			$("#previewformbkbkirimulang").modal('hide');
			$("#previewupdateformbkbkirimulang").modal('show');

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDetailBKB') ?>",
				data: "picking_order_aktual_h_id=" + picking_order_aktual_h_id,
				success: function(response) {
					if (response) {
						ChBKBKirimUlangDetailMenu(response);
					}
				}
			});
		}

		function UpdateBKBCanvas(picking_order_aktual_h_id) {
			$("#previewformbkbcanvas").modal('hide');
			$("#previewupdateformbkbcanvas").modal('show');

			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDetailBKB') ?>",
				data: "picking_order_aktual_h_id=" + picking_order_aktual_h_id,
				success: function(response) {
					if (response) {
						ChBKBCanvasDetailMenu(response);
					}
				}
			});
		}

		function ChBKBBulkDetailMenu(JSONChannel) {
			$("#tableupdatebkbbulk > tbody").html('');
			$("#slcupdatecheckerbkbbulk").html('');

			var Channel = JSON.parse(JSONChannel);

			if (Channel.PengeluaranBarang != 0) {
				var picking_order_id = Channel.PengeluaranBarang[0].picking_order_id;
				var picking_order_kode = Channel.PengeluaranBarang[0].picking_order_kode;
				var picking_order_aktual_h_id = Channel.PengeluaranBarang[0].picking_order_aktual_h_id;
				var picking_order_aktual_kode = Channel.PengeluaranBarang[0].picking_order_aktual_kode;
				var karyawan_id = Channel.PengeluaranBarang[0].karyawan_id;
				var karyawan_nama = Channel.PengeluaranBarang[0].karyawan_nama;

				$("#txtpickingorderid").val(picking_order_id);
				$("#txtupdatenoppbbulk").val(picking_order_kode);
				$("#txtupdatenobkbbulk").val(picking_order_aktual_kode);
				$("#txtupdateidbkbbulk").val(picking_order_aktual_h_id);

				$("#slcupdatecheckerbkbbulk").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '">' + karyawan_nama + '</option>');

				if ($.fn.DataTable.isDataTable('#tableupdatebkbbulk')) {
					$('#tableupdatebkbbulk').DataTable().destroy();
				}

				$('#tableupdatebkbbulk tbody').empty();

				for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
					var principal = Channel.PengeluaranBarang[i].principal;
					var brand = Channel.PengeluaranBarang[i].brand;
					var sku_id = Channel.PengeluaranBarang[i].sku_id;
					var sku_stock_qty_ambil = Channel.PengeluaranBarang[i].sku_stock_qty_ambil;
					var sku_kode = Channel.PengeluaranBarang[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarang[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarang[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarang[i].sku_satuan;
					var karyawan_nama = Channel.PengeluaranBarang[i].karyawan_nama;
					var picking_order_plan_id = Channel.PengeluaranBarang[i].picking_order_plan_id;
					var delivery_order_id = Channel.PengeluaranBarang[i].delivery_order_id;
					var sku_stock_id = Channel.PengeluaranBarang[i].sku_stock_id;
					var sku_stock_expired_date = Channel.PengeluaranBarang[i].sku_stock_expired_date;
					var sku_stock_qty_ambil_plan = Channel.PengeluaranBarang[i].sku_stock_qty_ambil_plan;
					var sisa_stock = sku_stock_qty_ambil_plan - Channel.PengeluaranBarang[i].total_ambil;

					var strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + principal + '</td>';
					strmenu = strmenu + '	<td>' + brand + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_plan) + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sisa_stock) + '</td>';
					strmenu = strmenu + '	<td><input type="number" class="form-control" id="txtqtyambil' + i + '" value="' + sku_stock_qty_ambil + '" readonly></td>';
					strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" readonly><option value="' + sku_stock_expired_date + '" >' + sku_stock_expired_date + '</option></select></td>';
					strmenu = strmenu + '</tr>';

					$("#tableupdatebkbbulk > tbody").append(strmenu);
				}
				$('#tableupdatebkbbulk').DataTable({
					"lengthMenu": [
						[10],
						[10]
					]
				});
			}

		}

		function ChBKBStandarDetailMenu(JSONChannel) {
			$("#tableupdatebkbstandar > tbody").html('');
			$("#slcupdatecheckerbkbstandar").html('');

			var Channel = JSON.parse(JSONChannel);

			if (Channel.PengeluaranBarang != 0) {
				var picking_order_id = Channel.PengeluaranBarang[0].picking_order_id;
				var picking_order_kode = Channel.PengeluaranBarang[0].picking_order_kode;
				var picking_order_aktual_h_id = Channel.PengeluaranBarang[0].picking_order_aktual_h_id;
				var picking_order_aktual_kode = Channel.PengeluaranBarang[0].picking_order_aktual_kode;
				var karyawan_id = Channel.PengeluaranBarang[0].karyawan_id;
				var karyawan_nama = Channel.PengeluaranBarang[0].karyawan_nama;
				var delivery_order_kode = Channel.PengeluaranBarang[0].delivery_order_kode;

				$("#txtpickingorderid").val(picking_order_id);
				$("#txtupdatenoppbstandar").val(picking_order_kode);
				$("#txtupdatenobkbstandar").val(picking_order_aktual_kode);
				$("#txtupdateidbkbstandar").val(picking_order_aktual_h_id);
				$("#txtupdatenodostandar").val(delivery_order_kode);

				$("#slcupdatecheckerbkbstandar").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '">' + karyawan_nama + '</option>');

				if ($.fn.DataTable.isDataTable('#tableupdatebkbstandar')) {
					$('#tableupdatebkbstandar').DataTable().destroy();
				}

				$('#tableupdatebkbstandar tbody').empty();

				for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
					var principal = Channel.PengeluaranBarang[i].principal;
					var brand = Channel.PengeluaranBarang[i].brand;
					var sku_id = Channel.PengeluaranBarang[i].sku_id;
					var sku_stock_qty_ambil = Channel.PengeluaranBarang[i].sku_stock_qty_ambil;
					var sku_kode = Channel.PengeluaranBarang[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarang[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarang[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarang[i].sku_satuan;
					var karyawan_nama = Channel.PengeluaranBarang[i].karyawan_nama;
					var picking_order_plan_id = Channel.PengeluaranBarang[i].picking_order_plan_id;
					var delivery_order_id = Channel.PengeluaranBarang[i].delivery_order_id;
					var sku_stock_id = Channel.PengeluaranBarang[i].sku_stock_id;
					var sku_stock_expired_date = Channel.PengeluaranBarang[i].sku_stock_expired_date;
					var sku_stock_expired_date_plan = Channel.PengeluaranBarang[i].sku_stock_expired_date_plan;
					var sku_stock_qty_ambil_plan = Channel.PengeluaranBarang[i].sku_stock_qty_ambil_plan;
					var sisa_stock = sku_stock_qty_ambil_plan - Channel.PengeluaranBarang[i].total_ambil;

					var strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + delivery_order_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_plan) + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sisa_stock) + '</td>';
					strmenu = strmenu + '	<td>' + sku_stock_expired_date_plan + '</td>';
					strmenu = strmenu + '	<td><input type="number" class="form-control" id="txtqtyambil' + i + '" value="' + sku_stock_qty_ambil + '" readonly></td>';
					strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" readonly><option value="' + sku_stock_expired_date + '" >' + sku_stock_expired_date + '</option></select></td>';
					strmenu = strmenu + '</tr>';

					$("#tableupdatebkbstandar > tbody").append(strmenu);
				}
				$('#tableupdatebkbstandar').DataTable({
					"lengthMenu": [
						[10],
						[10]
					]
				});
			}

		}

		function ChBKBKirimUlangDetailMenu(JSONChannel) {
			$("#tableupdatebkbkirimulang > tbody").html('');
			$("#slcupdatecheckerbkbkirimulang").html('');

			var Channel = JSON.parse(JSONChannel);

			if (Channel.PengeluaranBarang != 0) {
				var picking_order_id = Channel.PengeluaranBarang[0].picking_order_id;
				var picking_order_kode = Channel.PengeluaranBarang[0].picking_order_kode;
				var picking_order_aktual_h_id = Channel.PengeluaranBarang[0].picking_order_aktual_h_id;
				var picking_order_aktual_kode = Channel.PengeluaranBarang[0].picking_order_aktual_kode;
				var karyawan_id = Channel.PengeluaranBarang[0].karyawan_id;
				var karyawan_nama = Channel.PengeluaranBarang[0].karyawan_nama;

				$("#txtpickingorderid").val(picking_order_id);
				$("#txtupdatenoppbkirimulang").val(picking_order_kode);
				$("#txtupdatenobkbkirimulang").val(picking_order_aktual_kode);
				$("#txtupdateidbkbkirimulang").val(picking_order_aktual_h_id);

				$("#slcupdatecheckerbkbkirimulang").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '">' + karyawan_nama + '</option>');

				if ($.fn.DataTable.isDataTable('#tableupdatebkbkirimulang')) {
					$('#tableupdatebkbkirimulang').DataTable().destroy();
				}

				$('#tableupdatebkbkirimulang tbody').empty();

				for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
					var principal = Channel.PengeluaranBarang[i].principal;
					var brand = Channel.PengeluaranBarang[i].brand;
					var sku_id = Channel.PengeluaranBarang[i].sku_id;
					var sku_stock_qty_ambil = Channel.PengeluaranBarang[i].sku_stock_qty_ambil;
					var sku_kode = Channel.PengeluaranBarang[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarang[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarang[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarang[i].sku_satuan;
					var karyawan_nama = Channel.PengeluaranBarang[i].karyawan_nama;
					var picking_order_plan_id = Channel.PengeluaranBarang[i].picking_order_plan_id;
					var delivery_order_id = Channel.PengeluaranBarang[i].delivery_order_id;
					var sku_stock_id = Channel.PengeluaranBarang[i].sku_stock_id;
					var sku_stock_expired_date = Channel.PengeluaranBarang[i].sku_stock_expired_date;
					var sku_stock_qty_ambil_plan = Channel.PengeluaranBarang[i].sku_stock_qty_ambil_plan;
					var sisa_stock = sku_stock_qty_ambil_plan - Channel.PengeluaranBarang[i].total_ambil;

					var strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + principal + '</td>';
					strmenu = strmenu + '	<td>' + brand + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_plan) + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sisa_stock) + '</td>';
					strmenu = strmenu + '	<td><input type="number" class="form-control" id="txtqtyambil' + i + '" value="' + sku_stock_qty_ambil + '" readonly></td>';
					strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" readonly><option value="' + sku_stock_expired_date + '" >' + sku_stock_expired_date + '</option></select></td>';
					strmenu = strmenu + '</tr>';

					$("#tableupdatebkbkirimulang > tbody").append(strmenu);
				}
				$('#tableupdatebkbkirimulang').DataTable({
					"lengthMenu": [
						[10],
						[10]
					]
				});
			}

		}

		function ChBKBCanvasDetailMenu(JSONChannel) {
			$("#tableupdatebkbcanvas > tbody").html('');
			$("#slcupdatecheckerbkbcanvas").html('');

			var Channel = JSON.parse(JSONChannel);

			if (Channel.PengeluaranBarang != 0) {
				var picking_order_id = Channel.PengeluaranBarang[0].picking_order_id;
				var picking_order_kode = Channel.PengeluaranBarang[0].picking_order_kode;
				var picking_order_aktual_h_id = Channel.PengeluaranBarang[0].picking_order_aktual_h_id;
				var picking_order_aktual_kode = Channel.PengeluaranBarang[0].picking_order_aktual_kode;
				var karyawan_id = Channel.PengeluaranBarang[0].karyawan_id;
				var karyawan_nama = Channel.PengeluaranBarang[0].karyawan_nama;

				$("#txtpickingorderid").val(picking_order_id);
				$("#txtupdatenoppbcanvas").val(picking_order_kode);
				$("#txtupdatenobkbcanvas").val(picking_order_aktual_kode);
				$("#txtupdateidbkbcanvas").val(picking_order_aktual_h_id);

				$("#slcupdatecheckerbkbcanvas").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '">' + karyawan_nama + '</option>');

				if ($.fn.DataTable.isDataTable('#tableupdatebkbcanvas')) {
					$('#tableupdatebkbcanvas').DataTable().destroy();
				}

				$('#tableupdatebkbcanvas tbody').empty();

				for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
					var principal = Channel.PengeluaranBarang[i].principal;
					var brand = Channel.PengeluaranBarang[i].brand;
					var sku_id = Channel.PengeluaranBarang[i].sku_id;
					var sku_stock_qty_ambil = Channel.PengeluaranBarang[i].sku_stock_qty_ambil;
					var sku_kode = Channel.PengeluaranBarang[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarang[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarang[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarang[i].sku_satuan;
					var karyawan_nama = Channel.PengeluaranBarang[i].karyawan_nama;
					var picking_order_plan_id = Channel.PengeluaranBarang[i].picking_order_plan_id;
					var delivery_order_id = Channel.PengeluaranBarang[i].delivery_order_id;
					var sku_stock_id = Channel.PengeluaranBarang[i].sku_stock_id;
					var sku_stock_expired_date = Channel.PengeluaranBarang[i].sku_stock_expired_date;
					var sku_stock_qty_ambil_plan = Channel.PengeluaranBarang[i].sku_stock_qty_ambil_plan;
					var sisa_stock = sku_stock_qty_ambil_plan - Channel.PengeluaranBarang[i].total_ambil;

					var strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + principal + '</td>';
					strmenu = strmenu + '	<td>' + brand + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_plan) + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sisa_stock) + '</td>';
					strmenu = strmenu + '	<td><input type="number" class="form-control" id="txtqtyambil' + i + '" value="' + sku_stock_qty_ambil + '" readonly></td>';
					strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" readonly><option value="' + sku_stock_expired_date + '" >' + sku_stock_expired_date + '</option></select></td>';
					strmenu = strmenu + '</tr>';

					$("#tableupdatebkbcanvas > tbody").append(strmenu);
				}
				$('#tableupdatebkbcanvas').DataTable({
					"lengthMenu": [
						[10],
						[10]
					]
				});
			}

		}
	<?php
	}
	?>

	<?php
	if ($Menu_Access["U"] == 1) {
	?> $("#btnaddbkbstandar").removeAttr('style');
		$("#btnaddbkbbulk").removeAttr('style');
		$("#btnaddbkbkirimulang").removeAttr('style');
		$("#btnaddbkbcanvas").removeAttr('style');

		function ConfirmDilaksanakan() {
			var checkBox = document.getElementById("chkpb");

			if (checkBox.checked == true) {
				$("#slcstatusppb").html('');
				$("#slcstatusppb").append('<option value="In Progress">In Progress</option>');
				// $("#btnkonfirmasibarang").prop("disabled",false);
				$("#btnbatalbkb").prop("disabled", true);
				$("#btnlihatbkbbulk").prop("disabled", false);
				$("#btnlihatbkbstandar").prop("disabled", false);
				$("#btnlihatbkbkirimulang").prop("disabled", false);
				$("#btnlihatbkbcanvas").prop("disabled", false);
			} else {
				checkBox.checked = false;

				$("#slcstatusppb").html('');
				$("#slcstatusppb").append('<option value="Draft">Draft</option>');
				// $("#btnkonfirmasibarang").prop("disabled",true);
				$("#btnbatalbkb").prop("disabled", false);
				$("#btnlihatbkbbulk").prop("disabled", true);
				$("#btnlihatbkbstandar").prop("disabled", true);
				$("#btnlihatbkbkirimulang").prop("disabled", true);
				$("#btnlihatbkbcanvas").prop("disabled", true);
			}
		}

		$("#btnsavebkb").click(
			function() {
				$("#previewkonfirmasibkb").modal('show');
			}

		);

		$("#btnyeslaksanakanbkb").click(
			function() {

				var picking_order_id = $("#txtidppb").val();
				var picking_order_status = $("#slcstatusppb").val();
				var picking_list_id = $("#txtpickinglistid").val();
				var no_pb = $("#slcnopb").val();
				// var tipe_do_arr = $("#slctipepb").val().split(" || ");
				var tipe_do = $("#slctipepb").val();
				var picking_order_keterangan = $("#txtketerangan").val();

				var karyawan = "";
				// karyawanTemp = [];
				let ckhKaryawan = $("#slccheckerpb").val();

				if (tipe_do == "Mix") {
					let countStandar = $("#tabledetailpbstandar > tbody tr").length;
					if (countStandar > 0) {
						if (ckhKaryawan === "") {
							message("Error!", "<span>Checker tidak boleh kosong</span>", "error");
							return false;
						} else {
							karyawan = ckhKaryawan;
						}
					}
				}

				if (tipe_do == "Standar") {
					if (ckhKaryawan === "") {
						message("Error!", "<span>Checker tidak boleh kosong</span>", "error");
						return false;
					} else {
						karyawan = ckhKaryawan;
					}
				}

				let arr_picking_order_plan_id = [];
				let countStandar = 0;

				$("#tabledetailpbstandar > tbody tr").each(function(index, value) {
					let picking_order_plan_id = $(this).find("td:eq(0) input[type='hidden']");
					if (typeof picking_order_plan_id.val() !== "undefined") {
						countStandar++
						picking_order_plan_id.map(function() {
							arr_picking_order_plan_id.push($(this).val());
						}).get();
					}
				});


				// var karyawan = "";

				// if (tipe_pb == "Bulk") {
				// 	karyawan = $("#txtcheckerpb").val();
				// } else if (tipe_pb == "Standar") {
				// 	karyawan = $("#slccheckerpb").val();
				// } else if (tipe_pb == "Mix") {
				// 	karyawan = $("#slccheckerpb").val();
				// }

				// $("#loadingadd").show();
				// $("#btnsavebkb").prop("disabled", true);

				if (no_pb !== "") {
					if (picking_order_status == "In Progress") {
						// alert(picking_list_id);
						$.ajax({
							type: 'POST',
							url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/UpdateDilaksanakanPickingOrder') ?>",
							data: {
								picking_order_id: picking_order_id,
								picking_list_id: picking_list_id,
								karyawan: karyawan,
								picking_order_status: picking_order_status,
								picking_order_keterangan: picking_order_keterangan,
								tipe_do: tipe_do,
								standar: countStandar,
								picking_order_plan_id: arr_picking_order_plan_id
							},
							success: function(response) {
								$("#loadingadd").hide();
								$("#btnsavebkb").prop("disabled", false);
								if (response == 1) {

									message_topright('success', '<span>BKB Berhasil Disimpan</span>');

									ResetForm();
									GetPengeluaranBarangMenu();
								} else {
									if (response == 2) {
										var msg = '<span>Kode Channel sudah ada</span>';
									} else if (response == 3) {
										var msg = '<span>PPB sudah ada</span>';
									} else {
										var msg = response;
									}

									message("Error!", msg, "error");
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {

								$("#loadingadd").hide();
								$("#btnsavebkb").prop("disabled", false);
							}
						});

					} else {
						$.ajax({
							type: 'POST',
							url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/UpdatePickingOrder') ?>",
							data: {
								picking_order_id: picking_order_id,
								karyawan: karyawan,
								picking_order_status: picking_order_status,
								picking_order_keterangan: picking_order_keterangan,
								tipe_do: tipe_do,
								standar: countStandar,
								picking_order_plan_id: arr_picking_order_plan_id
							},
							success: function(response) {
								$("#loadingadd").hide();
								$("#btnsavebkb").prop("disabled", false);
								if (response == 1) {
									message_topright('success', '<span>BKB Berhasil Disimpan</span>');

									ResetForm();
									GetPengeluaranBarangMenu();
								} else {
									if (response == 2) {
										var msg = '<span>Kode Channel sudah ada</span>';
									} else if (response == 3) {
										var msg = '<span>PPB sudah ada</span>';
									} else {
										var msg = response;
									}
									var msgtype = 'error';

									new PNotify
										({
											title: 'Error',
											text: msg,
											type: msgtype,
											styling: 'bootstrap3',
											delay: 3000,
											stack: stack_center
										});
								}
							},
							error: function(xhr, ajaxOptions, thrownError) {

								$("#loadingadd").hide();
								$("#btnsavebkb").prop("disabled", false);
							}
						});

					}

				} else {
					message("Error!", "<span>No PB Kosong</span>", "error");

					$("#loadingadd").hide();
					$("#btnsavebkb").prop("disabled", false);
				}
			}
		);

		$("#btnnolaksanakanbkb").click(
			function() {
				$("#previewkonfirmasibkb").modal('hide');
			}

		);

		// Add New Channel
		$("#btnaddbkbstandar").click(
			function() {
				ResetForm();
				$("#previewformbkbstandar").modal('hide');
				$("#previewaddformbkbstandar").modal('show');

				$("#principleFilterStandar").empty();

				$("#principleFilterStandar").append(`<option value="all">--All--</option>`)
				if (displayPrincipleWhenClickAction('standar')) displayPrincipleWhenClickAction('standar').map((value) => $("#principleFilterStandar").append(`<option value="${value.principle}">${value.principle}</option>`))

				var picking_order_id = $("#txtidppb").val();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPickingBKB') ?>",
					data: "picking_order_id=" + picking_order_id,
					success: function(response) {
						if (response) {
							$('#tableaddbkbstandar').DataTable().clear().draw();
							ChPickingBKBStandarMenu(response);
						}
					}
				});
			}
		);

		// Add New Channel
		$("#btnaddbkbbulk").click(
			function() {
				ResetForm();
				$("#previewformbkbbulk").modal('hide');
				$("#previewaddformbkbbulk").modal('show');

				$("#principleFilterBulk").empty();

				$("#principleFilterBulk").append(`<option value="all">--All--</option>`)
				if (displayPrincipleWhenClickAction('bulk')) displayPrincipleWhenClickAction('bulk').map((value) => $("#principleFilterBulk").append(`<option value="${value.principle}">${value.principle}</option>`))

				var picking_order_id = $("#txtidppb").val();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPickingBKB') ?>",
					data: "picking_order_id=" + picking_order_id,
					success: function(response) {
						if (response) {
							$('#tableaddbkbbulk').DataTable().clear().draw();
							if ($.fn.DataTable.isDataTable('#tableaddbkbbulk')) {
								$('#tableaddbkbbulk').DataTable().clear();
								$('#tableaddbkbbulk').DataTable().destroy();
							}
							ChPickingBKBBulkMenu(response);
						}
					}
				});

			}
		);

		// Add New Channel
		$("#btnaddbkbkirimulang").click(
			function() {
				ResetForm();
				$("#previewformbkbkirimulang").modal('hide');
				$("#previewaddformbkbkirimulang").modal('show');

				$("#principleFilterKirimUlang").empty();

				$("#principleFilterKirimUlang").append(`<option value="all">--All--</option>`)
				if (displayPrincipleWhenClickAction('reschedule')) displayPrincipleWhenClickAction('reschedule').map((value) => $("#principleFilterKirimUlang").append(`<option value="${value.principle}">${value.principle}</option>`))

				var picking_order_id = $("#txtidppb").val();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPickingBKB') ?>",
					data: "picking_order_id=" + picking_order_id,
					success: function(response) {
						if (response) {
							$('#tableaddbkbkirimulang').DataTable().clear().draw();
							if ($.fn.DataTable.isDataTable('#tableaddbkbkirimulang')) {
								$('#tableaddbkbkirimulang').DataTable().clear();
								$('#tableaddbkbkirimulang').DataTable().destroy();
							}
							ChPickingBKBKirimUlangMenu(response);
						}
					}
				});

			}
		);

		// Add New Channel
		$("#btnaddbkbcanvas").click(
			function() {
				ResetForm();
				$("#previewformbkbcanvas").modal('hide');
				$("#previewaddformbkbcanvas").modal('show');

				$("#principleFilterCanvas").empty();

				$("#principleFilterCanvas").append(`<option value="all">--All--</option>`)
				if (displayPrincipleWhenClickAction('canvas')) displayPrincipleWhenClickAction('canvas').map((value) => $("#principleFilterCanvas").append(`<option value="${value.principle}">${value.principle}</option>`))

				var picking_order_id = $("#txtidppb").val();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPickingBKB') ?>",
					data: "picking_order_id=" + picking_order_id,
					success: function(response) {
						if (response) {
							$('#tableaddbkbcanvas').DataTable().clear().draw();
							if ($.fn.DataTable.isDataTable('#tableaddbkbcanvas')) {
								$('#tableaddbkbcanvas').DataTable().clear();
								$('#tableaddbkbcanvas').DataTable().destroy();
							}
							ChPickingBKBCanvasMenu(response);
						}
					}
				});

			}
		);

		function ChPickingBKBBulkMenu(JSONChannel) {
			$("#tableaddbkbstandar > tbody").html('');
			$("#tableaddbkbbulk > tbody").html('');
			$("#tableaddbkbkirimulang > tbody").html('');
			$("#tableaddbkbcanvas > tbody").html('');
			$("#slcaddcheckerbkbstandar").html('');
			$("#slcaddcheckerbkbbulk").html('');
			$("#slcaddcheckerbkbkirimulang").html('');
			$("#slcaddcheckerbkbcanvas").html('');

			$("#slcaddcheckerbkbbulk").append('<option value=" || ">**Pilih Karyawan**</option>');

			var Channel = JSON.parse(JSONChannel);

			var NoBKB = Channel.NoBKB;
			var IdBKB = Channel.IdBKB[0].NEW_ID;

			if (Channel.Checker != 0) {
				for (i = 0; i < Channel.Checker.length; i++) {
					karyawan_id = Channel.Checker[i].karyawan_id;
					karyawan_nama = Channel.Checker[i].karyawan_nama;

					$("#slcaddcheckerbkbbulk").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '">' + karyawan_nama + '</option>');
				}
			}

			if (Channel.PengeluaranBarang != 0) {
				var picking_order_id = Channel.PengeluaranBarang[0].picking_order_id;
				var picking_order_kode = Channel.PengeluaranBarang[0].picking_order_kode;
				var picking_list_tipe = Channel.PengeluaranBarang[0].picking_list_tipe;

				$("#txtpickingorderid").val(picking_order_id);
				$("#txtaddnoppbbulk").val(picking_order_kode);
				$("#txtaddnobkbbulk").val(NoBKB);
				$("#txtaddidbkbbulk").val(IdBKB);
			}

			$('#tableaddbkbbulk').DataTable({
				lengthMenu: [
					[-1],
					["All"]
				]
			});
		}

		function ChPickingBKBStandarMenu(JSONChannel) {
			$("#tableaddbkbstandar > tbody").html('');
			$("#tableaddbkbbulk > tbody").html('');
			$("#tableaddbkbkirimulang > tbody").html('');
			$("#tableaddbkbcanvas > tbody").html('');
			$("#slcaddcheckerbkbstandar").html('');
			$("#slcaddcheckerbkbbulk").html('');
			$("#slcaddnodostandar").html('');
			$("#slcaddcheckerbkbbulk").html('');
			$("#slcaddcheckerbkbkirimulang").html('');
			$("#slcaddcheckerbkbcanvas").html('');

			$("#slcaddcheckerbkbstandar").append('<option value=" || ">**Pilih Karyawan**</option>');
			$("#slcaddnodostandar").append('<option value="">**Pilih No DO**</option>');

			var Channel = JSON.parse(JSONChannel);

			var NoBKB = Channel.NoBKB;
			var IdBKB = Channel.IdBKB[0].NEW_ID;

			if (Channel.Checker != 0) {
				for (i = 0; i < Channel.Checker.length; i++) {
					karyawan_id = Channel.Checker[i].karyawan_id;
					karyawan_nama = Channel.Checker[i].karyawan_nama;

					$("#slcaddcheckerbkbstandar").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '">' + karyawan_nama + '</option>');
				}
			}

			if (Channel.PengeluaranBarang != 0) {
				var picking_order_id = Channel.PengeluaranBarang[0].picking_order_id;
				var picking_order_kode = Channel.PengeluaranBarang[0].picking_order_kode;
				var picking_list_tipe = Channel.PengeluaranBarang[0].picking_list_tipe;

				$("#txtpickingorderid").val(picking_order_id);
				$("#txtaddnoppbstandar").val(picking_order_kode);
				$("#txtaddnobkbstandar").val(NoBKB);
				$("#txtaddidbkbstandar").val(IdBKB);
			}
		}

		function ChPickingBKBKirimUlangMenu(JSONChannel) {
			$("#tableaddbkbstandar > tbody").html('');
			$("#tableaddbkbbulk > tbody").html('');
			$("#tableaddbkbkirimulang > tbody").html('');
			$("#tableaddbkbcanvas > tbody").html('');
			$("#slcaddcheckerbkbstandar").html('');
			$("#slcaddcheckerbkbbulk").html('');
			$("#slcaddcheckerbkbkirimulang").html('');
			$("#slcaddcheckerbkbcanvas").html('');

			$("#slcaddcheckerbkbkirimulang").append('<option value=" || ">**Pilih Karyawan**</option>');

			var Channel = JSON.parse(JSONChannel);

			var NoBKB = Channel.NoBKB;
			var IdBKB = Channel.IdBKB[0].NEW_ID;

			if (Channel.Checker != 0) {
				for (i = 0; i < Channel.Checker.length; i++) {
					karyawan_id = Channel.Checker[i].karyawan_id;
					karyawan_nama = Channel.Checker[i].karyawan_nama;

					$("#slcaddcheckerbkbkirimulang").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '">' + karyawan_nama + '</option>');
				}
			}

			if (Channel.PengeluaranBarang != 0) {
				var picking_order_id = Channel.PengeluaranBarang[0].picking_order_id;
				var picking_order_kode = Channel.PengeluaranBarang[0].picking_order_kode;
				var picking_list_tipe = Channel.PengeluaranBarang[0].picking_list_tipe;

				$("#txtpickingorderid").val(picking_order_id);
				$("#txtaddnoppbkirimulang").val(picking_order_kode);
				$("#txtaddnobkbkirimulang").val(NoBKB);
				$("#txtaddidbkbkirimulang").val(IdBKB);
			}

			$('#tableaddbkbkirimulang').DataTable({
				lengthMenu: [
					[-1],
					["All"]
				]
			});
		}

		function ChPickingBKBCanvasMenu(JSONChannel) {
			$("#tableaddbkbstandar > tbody").html('');
			$("#tableaddbkbbulk > tbody").html('');
			$("#tableaddbkbkirimulang > tbody").html('');
			$("#tableaddbkbcanvas > tbody").html('');
			$("#slcaddcheckerbkbstandar").html('');
			$("#slcaddcheckerbkbbulk").html('');
			$("#slcaddcheckerbkbkirimulang").html('');
			$("#slcaddcheckerbkbcanvas").html('');

			$("#slcaddcheckerbkbcanvas").append('<option value=" || ">**Pilih Karyawan**</option>');

			var Channel = JSON.parse(JSONChannel);

			var NoBKB = Channel.NoBKB;
			var IdBKB = Channel.IdBKB[0].NEW_ID;

			if (Channel.Checker != 0) {
				for (i = 0; i < Channel.Checker.length; i++) {
					karyawan_id = Channel.Checker[i].karyawan_id;
					karyawan_nama = Channel.Checker[i].karyawan_nama;

					$("#slcaddcheckerbkbcanvas").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '">' + karyawan_nama + '</option>');
				}
			}

			if (Channel.PengeluaranBarang != 0) {
				var picking_order_id = Channel.PengeluaranBarang[0].picking_order_id;
				var picking_order_kode = Channel.PengeluaranBarang[0].picking_order_kode;
				var picking_list_tipe = Channel.PengeluaranBarang[0].picking_list_tipe;

				$("#txtpickingorderid").val(picking_order_id);
				$("#txtaddnoppbcanvas").val(picking_order_kode);
				$("#txtaddnobkbcanvas").val(NoBKB);
				$("#txtaddidbkbcanvas").val(IdBKB);
			}

			$('#tableaddbkbcanvas').DataTable({
				lengthMenu: [
					[-1],
					["All"]
				]
			});
		}

		$("#slcaddcheckerbkbbulk").change(
			function() {
				var picking_order_id = $("#txtpickingorderid").val();
				var karyawan_id = $("#slcaddcheckerbkbbulk").val();
				const principle = $("#principleFilterBulk option:selected").val();
				var tipe_pb = $("#tipeBKBMix").val() == "" ? $("#slctipepb").val() : $("#tipeBKBMix").val();

				requestGetBKBbbulk(picking_order_id, karyawan_id, principle, tipe_pb)
			}

		);

		$("#principleFilterBulk").change(
			function() {
				var picking_order_id = $("#txtpickingorderid").val();
				var karyawan_id = $("#slcaddcheckerbkbbulk").val();
				const principle = $("#principleFilterBulk option:selected").val();
				var tipe_pb = $("#tipeBKBMix").val() == "" ? $("#slctipepb").val() : $("#tipeBKBMix").val();

				requestGetBKBbbulk(picking_order_id, karyawan_id, principle, tipe_pb)
			}

		);

		const requestGetBKBbbulk = (picking_order_id, karyawan_id, principle, tipe_pb) => {
			$('#loadingaddbkbbulk').show();

			if (karyawan_id != " || ") {

				if (tipe_pb == "Bulk") {
					$.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPickingBKBBulk') ?>",
						data: {
							picking_order_id: picking_order_id,
							karyawan_id: karyawan_id,
							principle
						},
						dataType: "JSON",
						success: function(response) {
							$('#loadingaddbkbbulk').hide();
							if (response) {
								$(".initchkData").show();
								dataBulkGlobal = [];
								dataBulkGlobal.push(...response);
								ChPickingBKBBulkDetailTable(response);
								handlerCheckedRemainderTake(event)
							}
						}
					});
				} else if (tipe_pb == "Flush Out") {
					$.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPickingBKBFlushOut') ?>",
						data: {
							picking_order_id: picking_order_id,
							karyawan_id: karyawan_id,
							principle
						},
						dataType: "JSON",
						success: function(response) {
							$('#loadingaddbkbbulk').hide();
							if (response) {
								$(".initchkData").show();
								dataBulkGlobal = [];
								dataBulkGlobal.push(...response);
								ChPickingBKBBulkDetailTable(response);
								handlerCheckedRemainderTake(event)
							}
						}
					});
				}
			} else {
				$('#loadingaddbkbbulk').hide();
				message("Error!", "<span>Pilih Checker</span>", "error");
				$("#tableaddbkbbulk > tbody").empty();
			}
		}

		$("#slcaddcheckerbkbstandar").change(
			function() {
				var picking_order_id = $("#txtpickingorderid").val();
				var karyawan_id = $("#slcaddcheckerbkbstandar").val();

				$("#slcaddnodostandar").html('');
				$('#loadingaddbkbstandar').show();

				if (karyawan_id != " || ") {

					$.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDOKode') ?>",
						dataType: "json",
						data: {
							picking_order_id: picking_order_id,
							karyawan_id: karyawan_id
						},
						success: function(response) {
							$('#loadingaddbkbstandar').hide();
							if (response) {

								$("#slcaddnodostandar").append('<option value="">**Pilih No DO**</option>');

								for (i = 0; i < response.DOKode.length; i++) {
									delivery_order_kode = response.DOKode[i].delivery_order_kode;
									$("#slcaddnodostandar").append('<option value="' + delivery_order_kode + '">' + delivery_order_kode + '</option>');
								}
							}
						}
					});

				} else {
					$('#loadingaddbkbstandar').hide();
					$("#tableaddbkbstandar > tbody").html('');

					message("Error!", "<span>Pilih Checker</span>", "error");
				}

			}

		);

		$("#slcaddcheckerbkbkirimulang").change(
			function() {
				var picking_order_id = $("#txtpickingorderid").val();
				var karyawan_id = $("#slcaddcheckerbkbkirimulang").val();
				const principle = $("#principleFilterKirimUlang option:selected").val();
				requestGetBKBKirimUlang(picking_order_id, karyawan_id, principle)
			}

		);

		$("#principleFilterKirimUlang").change(
			function() {
				var picking_order_id = $("#txtpickingorderid").val();
				var karyawan_id = $("#slcaddcheckerbkbkirimulang").val();
				const principle = $("#principleFilterKirimUlang option:selected").val();
				var tipe_pb = $("#tipeBKBMix").val() == "" ? $("#slctipepb").val() : $("#tipeBKBMix").val();

				requestGetBKBKirimUlang(picking_order_id, karyawan_id, principle, tipe_pb)
			}

		);

		const requestGetBKBKirimUlang = (picking_order_id, karyawan_id, principle) => {

			$('#loadingaddbkbkirimulang').show();

			if (karyawan_id != " || ") {
				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPickingBKBKirimUlang') ?>",
					data: {
						picking_order_id: picking_order_id,
						karyawan_id: karyawan_id,
						principle
					},
					dataType: "JSON",
					success: function(response) {
						$('#loadingaddbkbkirimulang').hide();
						if (response) {
							$(".initchkData").show();
							dataKirimUlangGlobal = [];
							dataKirimUlangGlobal.push(...response);
							ChPickingBKBKirimUlangDetailTable(response);
							// handlerCheckedRemainderTake(event)
						}
					}
				});
			} else {
				$('#loadingaddbkbkirimulang').hide();
				message("Error!", "<span>Pilih Checker</span>", "error");
				$("#tableaddbkbkirimulang > tbody").empty();
			}
		}

		$("#slcaddcheckerbkbcanvas").change(
			function() {
				var picking_order_id = $("#txtpickingorderid").val();
				var karyawan_id = $("#slcaddcheckerbkbcanvas").val();
				const principle = $("#principleFilterCanvas option:selected").val();
				var tipe_pb = $("#tipeBKBMix").val() == "" ? $("#slctipepb").val() : $("#tipeBKBMix").val();
				requestGetBKBCanvas(picking_order_id, karyawan_id, principle, tipe_pb)
			}

		);

		$("#principleFilterCanvas").change(
			function() {
				var picking_order_id = $("#txtpickingorderid").val();
				var karyawan_id = $("#slcaddcheckerbkbcanvas").val();
				const principle = $("#principleFilterCanvas option:selected").val();
				var tipe_pb = $("#tipeBKBMix").val() == "" ? $("#slctipepb").val() : $("#tipeBKBMix").val();
				requestGetBKBCanvas(picking_order_id, karyawan_id, principle, tipe_pb)
			}

		);

		const requestGetBKBCanvas = (picking_order_id, karyawan_id, principle) => {
			$('#loadingaddbkbcanvas').show();

			if (karyawan_id != " || ") {
				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPickingBKBCanvas') ?>",
					data: {
						picking_order_id: picking_order_id,
						karyawan_id: karyawan_id,
						principle
					},
					dataType: "JSON",
					success: function(response) {
						$('#loadingaddbkbcanvas').hide();
						if (response) {
							$(".initchkData").show();
							dataCanvasGlobal = [];
							dataCanvasGlobal.push(...response);
							ChPickingBKBCanvasDetailTable(response);
							// handlerCheckedRemainderTake(event)
						}
					}
				});
			} else {
				$('#loadingaddbkbcanvas').hide();
				message("Error!", "<span>Pilih Checker</span>", "error");
				$("#tableaddbkbcanvas > tbody").empty();
			}
		}

		$("#slcaddnodostandar").change(
			function() {
				var picking_order_id = $("#txtpickingorderid").val();
				var delivery_order_kode = $("#slcaddnodostandar").val();

				const principle = $("#principleFilterStandar option:selected").val();

				requestGetBKBStandar(picking_order_id, delivery_order_kode, principle)

			}
		);

		$("#principleFilterStandar").change(
			function() {
				var picking_order_id = $("#txtpickingorderid").val();
				var delivery_order_kode = $("#slcaddnodostandar").val();

				const principle = $("#principleFilterStandar option:selected").val();

				requestGetBKBStandar(picking_order_id, delivery_order_kode, principle)

			}
		);

		const requestGetBKBStandar = (picking_order_id, delivery_order_kode, principle) => {
			$('#loadingaddbkbstandar').show();

			if (delivery_order_kode != "") {
				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetPickingBKBStandar') ?>",
					data: {
						picking_order_id: picking_order_id,
						delivery_order_kode: delivery_order_kode,
						principle
					},
					success: function(response) {
						if (response) {
							dataStandarGlobal = [];
							dataStandarGlobal.push(response);
							ChPickingBKBStandarDetailTable(response);
						}
					}
				});

			} else {
				$("#tableaddbkbstandar > tbody").html('');
				$('#loadingaddbkbstandar').hide();

				message("Error!", "<span>Pilih No DO</span>", "error")
			}
		}


		const handlerCheckedRemainderTake = (event) => {

			const chkChecked = $("#slcremainderchk").is(":checked");
			const checker = $("#slcaddcheckerbkbbulk").val();

			// console.log(dataBulkNew);
			let filterData = []

			dataBulkNew.map((item) => {
				if (chkChecked == true) {
					if (parseInt(item.total_ambil) != 0) {
						filterData.push({
							...item
						})
					}
				} else {
					if (parseInt(item.total_ambil) == 0) {
						filterData.push({
							...item
						})
					}
				}
			})

			if (typeof checker != null) {
				$(".initchkData").show();

				if (filterData.length > 0) {
					if ($.fn.DataTable.isDataTable('#tableaddbkbbulk')) {
						$('#tableaddbkbbulk').DataTable().destroy();
					}

					$('#tableaddbkbbulk tbody').empty();

					const type = "Bulk";

					for (i = 0; i < filterData.length; i++) {
						var principal = filterData[i].principal;
						var brand = filterData[i].brand;
						var sku_id = filterData[i].sku_id;
						var sku_kode = filterData[i].sku_kode;
						var sku_nama_produk = filterData[i].sku_nama_produk;
						var sku_kemasan = filterData[i].sku_kemasan;
						var sku_satuan = filterData[i].sku_satuan;
						var karyawan_nama = filterData[i].karyawan_nama;
						var picking_order_plan_id = filterData[i].picking_order_plan_id;
						var delivery_order_id = filterData[i].delivery_order_id;
						var sku_stock_id = filterData[i].sku_stock_id;
						var sku_stock_expired_date = filterData[i].sku_stock_expired_date;
						var sku_stock_qty_ambil = filterData[i].sku_stock_qty_ambil;
						var total_ambil = filterData[i].total_ambil;
						var sisa_stock = sku_stock_qty_ambil - filterData[i].total_ambil;
						var depo_detail_id = filterData[i].depo_detail_id;
						var depo_detail_nama = filterData[i].depo_detail_nama;
						var actual_qty_ambil = filterData[i].actual_qty_ambil;
						var chckedScan = filterData[i].chckedScan;

						var strmenu = '';

						strmenu = strmenu + '<tr>';
						strmenu = strmenu + '	<td>';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtjml" value="' + filterData.length + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtdeliveryorderid' + i + '" value="' + delivery_order_id + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtpickingorderplanid' + i + '" value="' + picking_order_plan_id + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskuid' + i + '" value="' + sku_id + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtnamasku' + i + '" value="' + sku_nama_produk + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskustockid' + i + '" value="' + sku_stock_id + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="depoDetailId' + i + '" value="' + depo_detail_id + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="maxqty' + i + '" value="' + Math.round(sku_stock_qty_ambil) + '">';
						strmenu = strmenu + principal;
						strmenu = strmenu + '	</td>';
						strmenu = strmenu + '	<td>' + brand + '</td>';
						strmenu = strmenu + '	<td>' + sku_kode + '</td>';
						strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
						strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
						strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
						strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
						strmenu = strmenu + `	<td> ${total_ambil}</td>`;
						strmenu = strmenu + `	<td><input type="number" class="form-control" id="txtqtyambil${i}" value="${actual_qty_ambil}" disabled></td>`;
						strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" disabled><option value="' + sku_stock_id + ' || ' + sku_stock_expired_date + '">' + sku_stock_expired_date + '</option></select></td>';
						strmenu = strmenu + `	<td><input type="checkbox" style="transform:scale(1.5)" id="pilihManual${i}" ${chckedScan == "false" ? '' : 'checked'} onchange="handlerPilihManul(event, '${i}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}')"/></td>`;
						strmenu = strmenu + `	<td>
																			<button type="button" class="btn btn-primary" id="previewHandlerLocationRak${i}" onclick="previewHandlerLocationRak('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${total_ambil}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')" style="display: ${chckedScan == "false" ? 'block' : 'none'}">${depo_detail_nama}</button>

																			<button type="button" class="btn btn-info" id="handlerPilihByScanPallet${i}" onclick="handlerPilihByScanPallet('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${total_ambil}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')" style="display: ${chckedScan == "false" ? 'none' : 'block'}">Pilih</button>
																</td>`;
						strmenu = strmenu + '</tr>';

						$("#tableaddbkbbulk > tbody").append(strmenu);
					}

					$('#tableaddbkbbulk').DataTable({
						"lengthMenu": [
							[-1],
							['All']
						]
					});
				} else {
					$('#tableaddbkbbulk tbody').empty();
				}
			}
		}

		const handlerCheckedRemainderTakeKirimUlang = (event) => {

			const chkChecked = $("#slcremainderchkkirimulang").is(":checked");
			const checker = $("#slcaddcheckerbkbkirimulang").val();

			// console.log(dataKirimUlangNew);
			let filterData = []

			dataKirimUlangNew.map((item) => {
				if (chkChecked == true) {
					if (parseInt(item.total_ambil) != 0) {
						filterData.push({
							...item
						})
					}
				} else {
					if (parseInt(item.total_ambil) == 0) {
						filterData.push({
							...item
						})
					}
				}
			})

			if (typeof checker != null) {
				$(".initchkData").show();

				if (filterData.length > 0) {
					if ($.fn.DataTable.isDataTable('#tableaddbkbkirimulang')) {
						$('#tableaddbkbkirimulang').DataTable().destroy();
					}

					$('#tableaddbkbkirimulang tbody').empty();

					const type = "Reschedule";

					for (i = 0; i < filterData.length; i++) {
						var principal = filterData[i].principal;
						var brand = filterData[i].brand;
						var sku_id = filterData[i].sku_id;
						var sku_kode = filterData[i].sku_kode;
						var sku_nama_produk = filterData[i].sku_nama_produk;
						var sku_kemasan = filterData[i].sku_kemasan;
						var sku_satuan = filterData[i].sku_satuan;
						var karyawan_nama = filterData[i].karyawan_nama;
						var picking_order_plan_id = filterData[i].picking_order_plan_id;
						var delivery_order_id = filterData[i].delivery_order_id;
						var sku_stock_id = filterData[i].sku_stock_id;
						var sku_stock_expired_date = filterData[i].sku_stock_expired_date;
						var sku_stock_qty_ambil = filterData[i].sku_stock_qty_ambil;
						var total_ambil = filterData[i].total_ambil;
						var sisa_stock = sku_stock_qty_ambil - filterData[i].total_ambil;
						var depo_detail_id = filterData[i].depo_detail_id;
						var depo_detail_nama = filterData[i].depo_detail_nama;
						var actual_qty_ambil = filterData[i].actual_qty_ambil;

						var strmenu = '';

						strmenu = strmenu + '<tr>';
						strmenu = strmenu + '	<td>';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtjml" value="' + filterData.length + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtdeliveryorderid' + i + '" value="' + delivery_order_id + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtpickingorderplanid' + i + '" value="' + picking_order_plan_id + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskuid' + i + '" value="' + sku_id + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtnamasku' + i + '" value="' + sku_nama_produk + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskustockid' + i + '" value="' + sku_stock_id + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="depoDetailId' + i + '" value="' + depo_detail_id + '">';
						strmenu = strmenu + '		<input type="hidden" class="form-control" id="maxqty' + i + '" value="' + Math.round(sku_stock_qty_ambil) + '">';
						strmenu = strmenu + principal;
						strmenu = strmenu + '	</td>';
						strmenu = strmenu + '	<td>' + brand + '</td>';
						strmenu = strmenu + '	<td>' + sku_kode + '</td>';
						strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
						strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
						strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
						strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
						strmenu = strmenu + `	<td> ${total_ambil}</td>`;
						strmenu = strmenu + `	<td><input type="number" class="form-control" id="txtqtyambil${i}" value="${actual_qty_ambil}" disabled></td>`;
						strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" disabled><option value="' + sku_stock_id + ' || ' + sku_stock_expired_date + '">' + sku_stock_expired_date + '</option></select></td>';
						strmenu = strmenu + `	<td>
																			<button type="button" class="btn btn-primary" id="previewHandlerLocationRak${i}" onclick="previewHandlerLocationRak('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${total_ambil}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')">${depo_detail_nama}</button>
																</td>`;
						strmenu = strmenu + '</tr>';

						$("#tableaddbkbkirimulang > tbody").append(strmenu);
					}

					$('#tableaddbkbkirimulang').DataTable({
						"lengthMenu": [
							[-1],
							['All']
						]
					});
				} else {
					$('#tableaddbkbkirimulang tbody').empty();
				}
			}
		}

		const handlerCheckedRemainderTakeCanvas = (event) => {

			const chkChecked = $("#slcremainderchkcanvas").is(":checked");
			const checker = $("#slcaddcheckerbkbcanvas").val();

			// console.log(dataBulkNew);
			let filterData = []
			filterData = [];

			dataCanvasNew.map((item) => {
				if (chkChecked === true) {
					if (parseInt(item.total_ambil) > 0) {
						filterData.push({
							...item
						})
					}
				} else {
					if (parseInt(item.total_ambil) === 0) {
						filterData.push({
							...item
						})
					}
				}
			})

			$(".initchkData").show();

			if (filterData.length > 0) {
				if ($.fn.DataTable.isDataTable('#tableaddbkbcanvas')) {
					$('#tableaddbkbcanvas').DataTable().clear();
					$('#tableaddbkbcanvas').DataTable().destroy();
				}

				$('#tableaddbkbcanvas tbody').empty();

				const type = "Canvas";

				for (i = 0; i < filterData.length; i++) {
					var principal = filterData[i].principal;
					var brand = filterData[i].brand;
					var sku_id = filterData[i].sku_id;
					var sku_kode = filterData[i].sku_kode;
					var sku_nama_produk = filterData[i].sku_nama_produk;
					var sku_kemasan = filterData[i].sku_kemasan;
					var sku_satuan = filterData[i].sku_satuan;
					var karyawan_nama = filterData[i].karyawan_nama;
					var picking_order_plan_id = filterData[i].picking_order_plan_id;
					var delivery_order_id = filterData[i].delivery_order_id;
					var sku_stock_id = filterData[i].sku_stock_id;
					var sku_stock_expired_date = filterData[i].sku_stock_expired_date;
					var sku_stock_qty_ambil = filterData[i].sku_stock_qty_ambil;
					var total_ambil = filterData[i].total_ambil;
					var sisa_stock = sku_stock_qty_ambil - filterData[i].total_ambil;
					var depo_detail_id = filterData[i].depo_detail_id;
					var depo_detail_nama = filterData[i].depo_detail_nama;
					var actual_qty_ambil = filterData[i].actual_qty_ambil;
					var chckedScan = filterData[i].chckedScan;

					var strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>';
					strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtjml" value="' + filterData.length + '">';
					strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtdeliveryorderid' + i + '" value="' + delivery_order_id + '">';
					strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtpickingorderplanid' + i + '" value="' + picking_order_plan_id + '">';
					strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskuid' + i + '" value="' + sku_id + '">';
					strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtnamasku' + i + '" value="' + sku_nama_produk + '">';
					strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskustockid' + i + '" value="' + sku_stock_id + '">';
					strmenu = strmenu + '		<input type="hidden" class="form-control" id="depoDetailId' + i + '" value="' + depo_detail_id + '">';
					strmenu = strmenu + '		<input type="hidden" class="form-control" id="maxqty' + i + '" value="' + Math.round(sku_stock_qty_ambil) + '">';
					strmenu = strmenu + principal;
					strmenu = strmenu + '	</td>';
					strmenu = strmenu + '	<td>' + brand + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
					strmenu = strmenu + `	<td> ${total_ambil}</td>`;
					strmenu = strmenu + `	<td><input type="number" class="form-control" id="txtqtyambil${i}" value="${actual_qty_ambil}" disabled></td>`;
					strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" disabled><option value="' + sku_stock_id + ' || ' + sku_stock_expired_date + '">' + sku_stock_expired_date + '</option></select></td>';
					strmenu = strmenu + `	<td><input type="checkbox" style="transform:scale(1.5)" id="pilihManual${i}" ${chckedScan == "false" ? '' : 'checked'} onchange="handlerPilihManul(event, '${i}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}')"/></td>`;
					strmenu = strmenu + `	<td>
																<button type="button" class="btn btn-primary" id="previewHandlerLocationRak${i}" onclick="previewHandlerLocationRak('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${total_ambil}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')" style="display: ${chckedScan == "false" ? 'block' : 'none'}">${depo_detail_nama}</button>

																<button type="button" class="btn btn-info" id="handlerPilihByScanPallet${i}" onclick="handlerPilihByScanPallet('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${total_ambil}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')" style="display: ${chckedScan == "false" ? 'none' : 'block'}">Pilih</button>
													</td>`;
					strmenu = strmenu + '</tr>';

					$("#tableaddbkbcanvas > tbody").append(strmenu);
				}

				$('#tableaddbkbcanvas').DataTable({
					"lengthMenu": [
						[-1],
						['All']
					]
				});
			} else {
				$('#tableaddbkbcanvas tbody').empty();
			}

		}

		$("#btnbatalbkb").click(
			function() {
				$("#previewkonfirmasibatalbkb").modal('show');
			}

		);

		$("#btnyesbatalbkb").click(
			function() {
				var picking_order_id = $("#txtidppb").val();
				var picking_order_status = 'Cancel';
				var no_pb = $("#slcnopb").val();
				var tipe_pb = $("#slctipepb").val();
				// var karyawan = "";
				var picking_order_keterangan = $("#txtketerangan").val();
				// const lastUpdated = $("#lastUpdated").val();

				// if (tipe_pb == "Bulk") {
				// 	karyawan = $("#txtcheckerpb").val();
				// } else if (tipe_pb == "Standar") {
				// 	karyawan = $("#slccheckerpb").val();
				// } else if (tipe_pb == "Mix") {
				// 	karyawan = $("#slccheckerpb").val();
				// }

				var karyawan = "";
				// karyawanTemp = [];
				let ckhKaryawan = $("#slccheckerpb").val();

				if (tipe_pb == "Mix") {
					let countStandar = $("#tabledetailpbstandar > tbody tr").length;
					if (countStandar > 0) {
						if (ckhKaryawan === "") {
							message("Error!", "<span>Checker tidak boleh kosong</span>", "error");
							return false;
						} else {
							karyawan = ckhKaryawan;
						}
					}
				}

				if (tipe_pb == "Standar") {
					if (ckhKaryawan === "") {
						message("Error!", "<span>Checker tidak boleh kosong</span>", "error");
						return false;
					} else {
						karyawan = ckhKaryawan;
					}
				}


				let arr_picking_order_plan_id = [];
				let countStandar = 0;

				$("#tabledetailpbstandar > tbody tr").each(function(index, value) {
					let picking_order_plan_id = $(this).find("td:eq(0) input[type='hidden']");
					if (typeof picking_order_plan_id.val() !== "undefined") {
						countStandar++
						picking_order_plan_id.map(function() {
							arr_picking_order_plan_id.push($(this).val());
						}).get();
					}

				});

				if (no_pb !== "") {

					requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/UpdatePickingOrder'); ?>", {
						picking_order_id: picking_order_id,
						karyawan: karyawan,
						picking_order_status: picking_order_status,
						picking_order_keterangan,
						tipe_do: tipe_pb,
						standar: countStandar,
						picking_order_plan_id: arr_picking_order_plan_id,
						// lastUpdated
					}, "POST", "JSON", function(response) {
						if (response.status === 200) {
							// $("#lastUpdated").val(response.lastUpdatedNew);
							message_topright('success', '<span>BKB Berhasil Dibatalkan</span>');

							ResetForm();
							GetPengeluaranBarangMenu();
						}

						if (response.status === 401) return message_topright("error", response.message);
						// if (response.status === 400) return messageNotSameLastUpdated()
					})

				} else {
					message("Error!", "<span>No PB Kosong</span>", "error");

					return false;
				}
			}
		);

		$("#btnkonfirmasibarang").click(
			function() {
				$("#previewkonfirmasiselesaibkb").modal('show');
			}

		);

		$("#btnyeskonfirmasibkb").click(
			function() {
				var picking_order_id = $("#txtidppb").val();
				var picking_order_status = $("#slcstatusppb").val();
				var picking_list_id = $("#txtpickinglistid").val();
				var picking_order_status = 'Completed';
				var no_pb = $("#slcnopb").val();
				var tipe_do = $("#slctipepb").val();
				// var karyawan = "";
				var picking_order_keterangan = $("#txtketerangan").val();
				var jumlah_barang = $("#txtjumlahbarang").val();

				// const lastUpdated = $("#lastUpdated").val();

				var karyawan = "";
				// karyawanTemp = [];
				let ckhKaryawan = $("#slccheckerpb").val();

				if (tipe_do == "Mix") {
					let countStandar = $("#tabledetailpbstandar > tbody tr").length;
					if (countStandar > 0) {
						if (ckhKaryawan === "") {
							message("Error!", "<span>Checker tidak boleh kosong</span>", "error");
							return false;
						} else {
							karyawan = ckhKaryawan;
						}
					}
				}

				if (tipe_do == "Standar") {
					if (ckhKaryawan === "") {
						message("Error!", "<span>Checker tidak boleh kosong</span>", "error");
						return false;
					} else {
						karyawan = ckhKaryawan;
					}
				}

				let arr_picking_order_plan_id = [];
				let countStandar = 0;

				$("#tabledetailpbstandar > tbody tr").each(function(index, value) {
					let picking_order_plan_id = $(this).find("td:eq(0) input[type='hidden']");
					if (typeof picking_order_plan_id.val() !== "undefined") {
						countStandar++
						picking_order_plan_id.map(function() {
							arr_picking_order_plan_id.push($(this).val());
						}).get();
					}
				});

				if (no_pb !== "") {
					if (jumlah_barang > 0) {
						let jmlNull = 0
						$("#tabledetailpbbulk > tbody tr").each(function() {
							let qty = $(this).find("td:eq(8)").text();
							if (qty == 0) {
								jmlNull++;
							}
						});

						requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/UpdateSelesaiPickingOrder'); ?>", {
							picking_order_id: picking_order_id,
							picking_list_id: picking_list_id,
							karyawan: karyawan,
							picking_order_status: picking_order_status,
							picking_order_keterangan,
							tipe_do: tipe_do,
							standar: countStandar,
							picking_order_plan_id: arr_picking_order_plan_id,
							// lastUpdated
						}, "POST", "JSON", function(response) {
							if (response.status === 200) {
								// $("#lastUpdated").val(response.lastUpdatedNew);
								message_topright('success', '<span>BKB Berhasil Dilaksanakan</span>');

								GetPengeluaranBarangMenu();
							}

							if (response.status === 401) return message_topright("error", response.message);
							// if (response.status === 400) return messageNotSameLastUpdated()
						})

						// $.ajax({
						// 	type: 'POST',
						// 	url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/UpdateSelesaiPickingOrder') ?>",
						// 	data: {
						// 		picking_order_id: picking_order_id,
						// 		picking_list_id: picking_list_id,
						// 		karyawan: karyawan,
						// 		picking_order_status: picking_order_status,
						// 		picking_order_keterangan,
						// 		tipe_do: tipe_do,
						// 		standar: countStandar,
						// 		picking_order_plan_id: arr_picking_order_plan_id
						// 	},
						// 	success: function(response) {
						// 		if (response == 1) {
						// 			message_topright('success', '<span>BKB Berhasil Dilaksanakan</span>');

						// 			GetPengeluaranBarangMenu();
						// 		} else {
						// 			if (response == 2) {
						// 				var msg = 'Kode Channel sudah ada';
						// 			} else if (response == 3) {
						// 				var msg = 'PPB Sudah Ada';
						// 			} else {
						// 				var msg = response;
						// 			}
						// 			var msgtype = 'error';

						// 			new PNotify
						// 				({
						// 					title: 'Error',
						// 					text: msg,
						// 					type: msgtype,
						// 					styling: 'bootstrap3',
						// 					delay: 3000,
						// 					stack: stack_center
						// 				});
						// 		}
						// 	}

						// });


					} else {
						message("Error!", "<span>Jumlah Barang 0</span>", "error");

						return false;

					}
				} else {
					message("Error!", "<span>No PB Kosong</span>", "error");

					return false;
				}
			}

		);

	<?php
	}
	?>

	function ResetForm() {
		<?php
		if ($Menu_Access["U"] == 1) {
		?>
			$("#txtupdatechannelnama").val('');
			$("#txtaddnodostandar").val('');
			$("#cbupdatechanneljenis").prop('selectedIndex', 0);
			$("#chupdatechannelsppbr").prop('checked', false);
			$("#chupdatechanneltipeecomm").prop('checked', false);
			$("#chupdatechannelisactive").prop('checked', false);

			$("#loadingadd").hide();

		<?php
		}
		?>

		<?php
		if ($Menu_Access["C"] == 1) {
		?>
			$("#txtchannelnama").val('');
			$("#txtaddnodostandar").val('');
			$("#cbchanneljenis").prop('selectedIndex', 0);
			$("#chchannelsppbr").prop('checked', false);
			$("#chchanneltipeecomm").prop('checked', false);
			$("#chchannelisactive").prop('checked', false);

			$("#loadingadd").hide();
		<?php
		}
		?>

	}

	const closeHandlerLocationRak = () => {
		$("#previewlokasirak").modal('hide');
		// scanPalletArr = [];
		// qtyAktualTempArr = [];
	}

	const changeScanPalletHandler = (e, index) => {
		if (e.target.checked) {
			$(`#input_pallet_${index}`).show();
			$(`#start_scan_pallet_${index}`).hide();
		} else {
			$(`#input_pallet_${index}`).hide();
			$(`#start_scan_pallet_${index}`).show();
		}
	}
	const changeScanPalletHandlerIndex = (e) => {
		if (e.target.checked) {
			$(`#input_pallet`).show();
			$(`#start_scan_pallet`).hide();
		} else {
			$(`#input_pallet`).hide();
			$(`#start_scan_pallet`).show();
		}
	}

	const changeScanSKUHandler = (e, i) => {
		if (e.target.checked) {
			$(`#input_sku_${i}`).show();
			$(`#start_scan_sku_${i}`).hide();
		} else {
			$(`#input_sku_${i}`).hide();
			$(`#start_scan_sku_${i}`).show();
		}
	}

	const changeScanPalletPilihHandler = (e) => {
		if (e.target.checked) {
			$(`#input_pallet_pilih`).show();
			$(`#start_scan_pallet_pilih`).hide();
		} else {
			$(`#input_pallet_pilih`).hide();
			$(`#start_scan_pallet_pilih`).show();
		}
	}


	//start scan pallet //

	const startScanPalletHandler = (nama_rak, depo_detail_id, rak_lajur_detail_id, increment, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type, typeScan, palletId) => {
		Swal.fire({
			title: '<span ><i class="fa fa-spinner fa-spin"></i> <label name="CAPTION-ALERT-MEMUATKAMERA"></label></span>',
			timer: 1000,
			timerProgressBar: true,
			showConfirmButton: false,
			allowOutsideClick: false,
			didOpen: () => {
				Swal.showLoading();
				const b = Swal.getHtmlContainer().querySelector('b')
				timerInterval = setInterval(() => {
					b.textContent = Swal.getTimerLeft()
				}, 100)
			},
			willClose: () => {
				clearInterval(timerInterval)
			}
		}).then((result) => {
			/* Read more about handling dismissals below */
			if (result.dismiss === Swal.DismissReason.timer) {
				$("#modalScanPallet").modal('show');

				//handle succes scan
				const qrCodeSuccessCallback2 = (decodedText, decodedResult) => {
					let temp = decodedText;
					if (temp != "") {
						html5QrCode.pause();
						scan(decodedText);
					}
				};

				const scan = async (decodedText) => {
					const url = "<?= base_url('WMS/Distribusi/PengeluaranBarang/CheckScanPalletInRak') ?>";

					let postData = new FormData();
					postData.append('depo_detail_id', depo_detail_id);
					postData.append('nama_rak', nama_rak);
					postData.append('kode', decodedText);
					postData.append('typeInput', typeScan);
					postData.append('skuKode', sku_kode);
					postData.append('palletId', palletId);

					const request = await fetch(url, {
						method: 'POST',
						body: postData
					});

					const response = await request.json();

					if (typeScan == "non-pilih") {
						if (response.type == 200) {
							$("#txtPreviewScanPallet").val(response.kode)
							if (scanPalletArr.length === 0) {
								scanPalletArr.push({
									depo_detail_id,
									nama_rak: response.data.rak_lajur_detail_nama,
									pallet_id: response.data.pallet_id,
									picking_order_plan_id,
									sku_kode,
									expired_date,
									type
								});
								message('Success!', response.message, 'success');
								$(`#detailRakPallet_${response.data.rak_lajur_detail_nama}_${type}`).fadeOut("slow", function() {
									$(this).hide();
									$("#nama_rak").val('')
									$("#modalInputPallet").modal('hide');
								}).fadeIn("slow", function() {
									$(this).show();
									detailHandlerClick(rak_lajur_detail_id == null ? response.data.rak_lajur_detail_id : rak_lajur_detail_id, increment, nama_rak == null ? response.data.rak_lajur_detail_nama : nama_rak, depo_detail_id, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type);
								});
							} else {
								const findData = scanPalletArr.find((item) => (item.depo_detail_id === depo_detail_id) && (item.nama_rak === response.data.rak_lajur_detail_nama) && (item.pallet_id === response.data.pallet_id) && (item.picking_order_plan_id === picking_order_plan_id) && (item.sku_kode === sku_kode) && (item.expired_date === expired_date) && (item.type === type));
								if (typeof findData === 'undefined') {
									scanPalletArr.push({
										depo_detail_id,
										nama_rak: response.data.rak_lajur_detail_nama,
										pallet_id: response.data.pallet_id,
										picking_order_plan_id,
										sku_kode,
										expired_date,
										type
									});
									message('Success!', response.message, 'success');
									$(`#detailRakPallet_${response.data.rak_lajur_detail_nama}_${type}`).fadeOut("slow", function() {
										$(this).hide();
										$("#nama_rak").val('')
										$("#modalInputPallet").modal('hide');
									}).fadeIn("slow", function() {
										$(this).show();
										detailHandlerClick(rak_lajur_detail_id == null ? response.data.rak_lajur_detail_id : rak_lajur_detail_id, increment, nama_rak == null ? response.data.rak_lajur_detail_nama : nama_rak, depo_detail_id, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type);
									});
								} else {
									message('Info!', 'Pallet sudah discan', 'info');
								}
							}
						}
					}

					// if (typeScan == "pilih") {
					// 	if (response.type == 200) {
					// 		initDataPallet(response)
					// 	}
					// }

					if (typeScan == "scan-sku") {
						if (response.type == 200) {
							initDataPalletDetailScan(response)
						}
					}



					if (response.type == 201) {
						message('Error!', response.message, 'error');
					}
				}

				// atur kotak nng kini, set 0.sekian pngin jlok brpa persen
				const qrboxFunction2 = function(viewfinderWidth, viewfinderHeight) {
					let minEdgePercentage = 0.9; // 90%
					let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
					let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
					return {
						width: qrboxSize,
						height: qrboxSize
					};
				}

				const config2 = {
					fps: 10,
					qrbox: qrboxFunction2,
				};
				Html5Qrcode.getCameras().then(devices => {
					if (devices && devices.length) {
						$("#selectCameraPallet").empty();
						$.each(devices, function(i, v) {
							$("#selectCameraPallet").append(`
	                      <input class="checkbox-tools" type="radio" name="tools2" value="${v.id}" id="tool-${i}">
	                      <label class="for-checkbox-tools" for="tool-${i}">
	                          ${v.label}
	                      </label>
	                  `)
						});

						$("#selectCameraPallet :input[name='tools2']").each(function(i, v) {
							if (i == 0) {
								$(this).attr('checked', true);
							}
						});

						let cameraId2 = devices[0].id;
						// html5QrCode.start(devices[0]);
						$('input[name="tools2"]').on('click', function() {
							// alert($(this).val());
							html5QrCode.stop();
							html5QrCode.start({
								deviceId: {
									exact: $(this).val()
								}
							}, config2, qrCodeSuccessCallback2);
						});
						//start scan
						html5QrCode.start({
							deviceId: {
								exact: cameraId2
							}
						}, config2, qrCodeSuccessCallback2);

					}
				}).catch(err => {
					message("Error!", "Kamera tidak ditemukan", "error");
					$("#modalScanPallet").modal('hide');
				});
			}
		});
	}
	const closeScanPalletHandler = () => {
		html5QrCode.stop();
		$("#modalScanPallet").modal('hide');
	}

	//end scan pallet//


	//input scan pallet //

	const inputScanPalletHandler = (nama_rak, depo_detail_id, rak_lajur_detail_id, increment, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type, typeScan, palletId) => {
		$("#modalInputPallet").modal('show');

		$(".label-input").html(`<label name="CAPTION-KODEPALLET"></label>&nbsp;<span name="CAPTION-MASUKKANKODEPERTAMA"></span>`)
		$(".nama_rak").attr('placeholder', '/PALLET/NoKode')
		// if (palletId == null) {
		// 	$(".label-input").html(`<label name="CAPTION-KODEPALLET"></label>&nbsp;<span name="CAPTION-MASUKKANKODEPERTAMA"></span>`)
		// 	$(".nama_rak").attr('placeholder', '/PALLET/NoKode')
		// } else {
		// 	$(".label-input").html(`<label name="CAPTION-SKUKODE"></label>`)
		// 	$(".nama_rak").attr('placeholder', 'kode sku')
		// }

		Translate();

		const data = {
			nama_rak,
			depo_detail_id,
			rak_lajur_detail_id,
			increment,
			qty_ambil,
			qty_sisa,
			sku_kode,
			expired_date,
			picking_order_plan_id,
			type,
			typeScan,
			palletId
		}

		$("#nama_rak").attr("data-handler", JSON.stringify(data));

	}
	const inputScanPalletHandlerIndex = (nama_rak, depo_detail_id, rak_lajur_detail_id, increment, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type, typeScan, palletId) => {
		$("#modalInputPallet").modal('show');

		$(".label-input").html(`<label name="CAPTION-KODEPALLET"></label>&nbsp;<span name="CAPTION-MASUKKANKODEPERTAMA"></span>`)
		$(".nama_rak").attr('placeholder', '/PALLET/NoKode')
		// if (palletId == null) {
		// 	$(".label-input").html(`<label name="CAPTION-KODEPALLET"></label>&nbsp;<span name="CAPTION-MASUKKANKODEPERTAMA"></span>`)
		// 	$(".nama_rak").attr('placeholder', '/PALLET/NoKode')
		// } else {
		// 	$(".label-input").html(`<label name="CAPTION-SKUKODE"></label>`)
		// 	$(".nama_rak").attr('placeholder', 'kode sku')
		// }

		Translate();

		const data = {
			nama_rak,
			depo_detail_id,
			rak_lajur_detail_id,
			increment,
			qty_ambil,
			qty_sisa,
			sku_kode,
			expired_date,
			picking_order_plan_id,
			type,
			typeScan,
			palletId
		}

		$("#nama_rak").attr("data-handler", JSON.stringify(data));

	}

	const closeInputPalletHandler = () => {
		$("#modalInputPallet").modal('hide');
		$("#nama_rak").val("");
	}

	// const handlerInputPalletPilih = () => {
	// 	$("#modalInputPallet").modal('show');
	// 	$("#nama_rak").attr("data-typeInput", 'pilih');
	// }


	const changeInputPalletHandler = async (event) => {
		const value = event.currentTarget.value;

		const dataHandler = JSON.parse(event.currentTarget.getAttribute('data-handler'));
		requestCheckScanKode(dataHandler, value)
	}

	const requestCheckScanKode = async (dataHandler, value) => {
		const depo_detail_id = dataHandler.depo_detail_id;
		const nama_rak = dataHandler.nama_rak;
		const rak_lajur_detail_id = dataHandler.rak_lajur_detail_id;
		const increment = dataHandler.increment;
		const qty_ambil = dataHandler.qty_ambil;
		const qty_sisa = dataHandler.qty_sisa;
		const sku_kode = dataHandler.sku_kode;
		const expired_date = dataHandler.expired_date;
		const picking_order_plan_id = dataHandler.picking_order_plan_id;
		const type = dataHandler.type;
		const typeScan = dataHandler.typeScan;
		const palletId = dataHandler.palletId;

		const url = "<?= base_url('WMS/Distribusi/PengeluaranBarang/CheckScanPalletInRak') ?>";

		let postData = new FormData();
		postData.append('depo_detail_id', depo_detail_id);
		postData.append('nama_rak', nama_rak);
		postData.append('kode', value);
		postData.append('typeInput', typeScan);
		postData.append('skuKode', sku_kode);
		postData.append('palletId', palletId);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();


		if (typeScan == 'non-pilih') {
			if (response.type == 200) {
				if (scanPalletArr.length === 0) {
					scanPalletArr.push({
						depo_detail_id,
						nama_rak: response.data.rak_lajur_detail_nama,
						pallet_id: response.data.pallet_id,
						picking_order_plan_id,
						sku_kode,
						expired_date,
						type
					});
					message('Success!', response.message, 'success');
					$(`#detailRakPallet_${response.data.rak_lajur_detail_nama}_${type}`).fadeOut("slow", function() {
						$(this).hide();
						$("#nama_rak").val('')
						$("#modalInputPallet").modal('hide');
					}).fadeIn("slow", function() {
						$(this).show();
						detailHandlerClick(rak_lajur_detail_id == null ? response.data.rak_lajur_detail_id : rak_lajur_detail_id, increment, nama_rak == null ? response.data.rak_lajur_detail_nama : nama_rak, depo_detail_id, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type);
					});
				} else {
					const findData = scanPalletArr.find((item) => (item.depo_detail_id === depo_detail_id) && (item.nama_rak === response.data.rak_lajur_detail_nama) && (item.pallet_id === response.data.pallet_id) && (item.picking_order_plan_id === picking_order_plan_id) && (item.sku_kode === sku_kode) && (item.expired_date === expired_date) && (item.type === type));
					console.log(findData);
					if (typeof findData === 'undefined') {
						scanPalletArr.push({
							depo_detail_id,
							nama_rak: response.data.rak_lajur_detail_nama,
							pallet_id: response.data.pallet_id,
							picking_order_plan_id,
							sku_kode,
							expired_date,
							type
						});
						message('Success!', response.message, 'success');
						$(`#detailRakPallet_${response.data.rak_lajur_detail_nama}_${type}`).fadeOut("slow", function() {
							$(this).hide();
							$("#nama_rak").val('')
							$("#modalInputPallet").modal('hide');
						}).fadeIn("slow", function() {
							$(this).show();
							detailHandlerClick(rak_lajur_detail_id == null ? response.data.rak_lajur_detail_id : rak_lajur_detail_id, increment, nama_rak == null ? response.data.rak_lajur_detail_nama : nama_rak, depo_detail_id, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type);
						});
					} else {
						message('Info!', 'Pallet sudah discan', 'info');
					}
				}
			}
		}

		// if (typeScan == 'pilih') {
		// 	if (response.type == 200) {
		// 		initDataPallet(response)
		// 	}
		// }

		if (typeScan == 'scan-sku') {
			if (response.type == 200) {
				initDataPalletDetailScan(response)
			}
		}

		if (response.type == 201) {
			message('Error!', response.message, 'error');
		}
	}

	const initDataPallet = (response) => {
		initDataPalletLayout(response)
	}

	const initDataPalletLayout = (response) => {

		$("#initScanManualPallet .panel-body table > tbody").empty()

		console.log(response);

		let html = "";

		if (response) {
			response.map((value, index) => {
				if (arrScanPallet.length == 0) {
					arrScanPallet.push({
						pallet_id: value.pallet_id,
						pallet_kode: value.pallet_kode,
						pallet_jenis_kode: value.pallet_jenis_kode,
						depo_detail_nama: value.depo_detail_nama,
						pickingOrderPlanId: $("#pickingOrderPlanId").val(),
						skuKodeDiatas: $("#skuKodeDiatas").val(),
						expDateDiatas: $("#expDateDiatas").val(),
						typeData: $("#typeData").val(),
						is_valid: false
					})
				} else {
					const findPallet = arrScanPallet.find((item) => (item.pallet_id === value.pallet_id) && (item.pickingOrderPlanId === $("#pickingOrderPlanId").val()) && (item.skuKodeDiatas === $("#skuKodeDiatas").val()) && (item.expDateDiatas === $("#expDateDiatas").val()) && (item.typeData === $("#typeData").val()))

					if (typeof findPallet === 'undefined') {
						arrScanPallet.push({
							pallet_id: value.pallet_id,
							pallet_kode: value.pallet_kode,
							pallet_jenis_kode: value.pallet_jenis_kode,
							depo_detail_nama: value.depo_detail_nama,
							pickingOrderPlanId: $("#pickingOrderPlanId").val(),
							skuKodeDiatas: $("#skuKodeDiatas").val(),
							expDateDiatas: $("#expDateDiatas").val(),
							typeData: $("#typeData").val(),
							is_valid: false
						})
					}
				}
			})
		}

		const datas = arrScanPallet.filter((item) => (item.pickingOrderPlanId === $("#pickingOrderPlanId").val()) && (item.skuKodeDiatas === $("#skuKodeDiatas").val()) && (item.expDateDiatas === $("#expDateDiatas").val()) && (item.typeData === $("#typeData").val()))

		if (datas) {
			datas.map((value, index) => {
				// arrScanPilihDetail = [];
				// const filterData = arrScanPilihDetail.map((item, index) => {
				// 	if ((item.pallet_id === value.pallet_id) && (item.picking_order_plan_id === $("#pickingOrderPlanId").val()) && (item.skuKodeDiatas === $("#skuKodeDiatas").val()) && (item.expDateDiatas === $("#expDateDiatas").val()) && (item.typeData === $("#typeData").val())) {
				// 		return index
				// 	}
				// }).filter((data) => typeof data !== 'undefined').filter(String);

				// for (let i = filterData.length - 1; i >= 0; i--) {
				// 	arrScanPilihDetail.splice(filterData[i], 1)
				// }

				// const filterPallet = arrScanPallet.findIndex((item) => (item.pallet_id !== value.pallet_id) && (item.pickingOrderPlanId === $("#pickingOrderPlanId").val()) && (item.skuKodeDiatas === $("#skuKodeDiatas").val()) && (item.expDateDiatas === $("#expDateDiatas").val()) && (item.typeData === $("#typeData").val()))

				// filterPallet.map((data) => arrScanPallet.push({
				// 	...data
				// }))

				html += `<tr class="text-center">
										<td>
												<div class="head-switch" style="display:none;">
														<div class="switch-holder">
																<div class="switch-toggle">
																		<input type="checkbox" id="check_scan_${value.pallet_id}" class="check_scan" onchange="changeScanPalletHandler(event, '${value.pallet_id}')">
																		<label for="check_scan_${value.pallet_id}"></label>
																</div>
														
																<div class="switch-label" style="margin-left: -40px;">
																		<button type="button" class="btn btn-info start_scan_pallet" id="start_scan_pallet_${value.pallet_id}" name="start_scan_pallet_${value.pallet_id}" onclick="startScanPalletHandler(null, null, null, null, null, null, '${$("#skuKodeDiatas").val()}', null, null, null, 'scan-sku', '${value.pallet_id}')"> <i class="fas fa-qrcode"></i> Scan</button>
																		<button type="button" class="btn btn-warning input_pallet" id="input_pallet_${value.pallet_id}" name="input_pallet_${value.pallet_id}" style="display:none" onclick="inputScanPalletHandler(null, null, null, null, null, null, '${$("#skuKodeDiatas").val()}', null, null, null, 'scan-sku', '${value.pallet_id}')"> <i class="fas fa-keyboard"></i> Input</button>
																</div>
														</div>
												</div>
										</td>
										<td>${value.pallet_kode}</td>
										<td>${value.pallet_jenis_kode}</td>
										<td>${value.depo_detail_nama}</td>
										<td>${value.is_valid ? 'Valid' : ''}</td>
										<td>
											<button type="button" class="btn btn-primary btn-sm input_pallet" ${value.is_valid ? '' : 'disabled'} onclick="initDataPalletDetail('${value.pallet_id}')"> <i class="fas fa-arrow-right"></i></button>
										</td>
								</tr>`
			})
		}

		$("#initScanManualPallet .panel-body table > tbody").append(html)
	}

	const initDataPalletDetail = (palletId) => {
		if ($.fn.DataTable.isDataTable('#initScanManualPalletDetail')) {
			$('#initScanManualPalletDetail').DataTable().destroy();
		}
		$("#initScanManualPalletDetail tbody").empty()

		const filterData = arrScanPilihDetail.find((item) => (item.palletId === palletId) && (item.picking_order_plan_id === $("#pickingOrderPlanId").val()) && (item.skuKodeDiatas === $("#skuKodeDiatas").val()) && (item.expDateDiatas === $("#expDateDiatas").val()) && (item.typeData === $("#typeData").val()))

		if (filterData) {
			initDataPalletDetailLayout(filterData)

			$("#initScanManualPalletDetail").DataTable({
				lengthMenu: [
					[-1],
					['All']
				],
			});
		}
	}

	const initDataPalletDetailLayout = (filterData) => {

		filterData.data.map((data) => {
			$("#initScanManualPalletDetail tbody").append(`
						<tr class="text-center">
								<td style="display:none">
										<input type="hidden" class="form-control" id="skuIdPalletDetail" value="${data.sku_id}"/>
										<input type="hidden" class="form-control" id="skuStockIdPalletDetail" value="${data.sku_stock_id}"/>
										<input type="hidden" class="form-control" id="palletDetailIdPalletDetail" value="${data.pallet_detail_id}"/>
										<input type="hidden" class="form-control" id="palletIdPalletDetail" value="${data.pallet_id}"/>
								</td>
								<td>${data.sku_kode}</td>
								<td>${data.sku_nama_produk}</td>
								<td>${data.sku_satuan}</td>
								<td>${data.sku_kemasan}</td>
								<td>${data.qty_available}</td>
								<td>
										<input type="number" name="qtyAktualPilihManual" class="form-control qtyAktualPilihManual" id="qtyAktualPilihManual${data.pallet_id}" placeholder="qty aktual" value="${data.qtyAktual == 0 ? '' : data.qtyAktual}" onchange="handlerChangeQtyAktual(event, '${data.sku_id}', '${data.sku_stock_expired_date}', '${data.pallet_id}', '${data.qty_available}')"/>
								</td>
								<td>${data.sku_stock_expired_date}</td>
						</tr>
				`)
		})

	}

	const initDataPalletDetailScan = (response) => {

		if (response.type == 200) {
			message('Success!', response.message, 'success');
			$("#nama_rak").val('')
			$("#modalInputPallet").modal('hide');

			if (arrScanPilihDetail.length == 0) {
				arrScanPilihDetail.push({
					picking_order_plan_id: $("#pickingOrderPlanId").val(),
					skuKodeDiatas: $("#skuKodeDiatas").val(),
					expDateDiatas: $("#expDateDiatas").val(),
					typeData: $("#typeData").val(),
					palletId: response.data.pallet_id,
					data: response.data.data.map((value, index) => {
						return {
							...value,
							qtyAktual: 0
						}
					})
				})
			} else {
				const findData = arrScanPilihDetail.find((item) => (item.picking_order_plan_id === $("#pickingOrderPlanId").val()) && (item.skuKodeDiatas === $("#skuKodeDiatas").val()) && (item.expDateDiatas === $("#expDateDiatas").val()) && (item.typeData === $("#typeData").val()) && (item.pallet_id === response.data.pallet_id))

				if (typeof findData === 'undefined') {
					arrScanPilihDetail.push({
						picking_order_plan_id: $("#pickingOrderPlanId").val(),
						skuKodeDiatas: $("#skuKodeDiatas").val(),
						expDateDiatas: $("#expDateDiatas").val(),
						typeData: $("#typeData").val(),
						palletId: response.data.pallet_id,
						data: response.data.data.map((value, index) => {
							return {
								...value,
								qtyAktual: 0
							}
						})
					})
				} else {
					message('Info!', `Pallet ${response.message} sudah discan`, 'info');
				}
			}

			//kesok lanjut kini


			const findPallet = arrScanPallet.findIndex((item) => (item.pallet_id === response.data.pallet_id) && (item.pickingOrderPlanId === $("#pickingOrderPlanId").val()) && (item.skuKodeDiatas === $("#skuKodeDiatas").val()) && (item.expDateDiatas === $("#expDateDiatas").val()) && (item.typeData === $("#typeData").val()))

			arrScanPallet[findPallet]['is_valid'] = true;

			const filterData = arrScanPallet.filter((item) => (item.pickingOrderPlanId === $("#pickingOrderPlanId").val()) && (item.skuKodeDiatas === $("#skuKodeDiatas").val()) && (item.expDateDiatas === $("#expDateDiatas").val()) && (item.typeData === $("#typeData").val()))

			$("#initScanManualPallet").fadeOut("slow", function() {
				$(this).hide();
			}).fadeIn("slow", function() {
				$(this).show();
				initDataPalletLayout(filterData);
				setTimeout(() => initDataPalletDetail(response.data.pallet_id), 500)
			})
		} else {
			message('Error!', response.message, 'error');
			return false
		}

	}

	const handlerChangeQtyAktual = (event, sku_id, sku_stock_expired_date, pallet_id, qty_available) => {
		const pickingOrderPlanId = $("#pickingOrderPlanId").val()
		const skuKodeDiatas = $("#skuKodeDiatas").val()
		const expDateDiatas = $("#expDateDiatas").val()
		const typeData = $("#typeData").val()
		const valueQty = event.currentTarget.value == '' ? 0 : parseInt(event.currentTarget.value)
		const qtyPlanAmbil = parseInt($(`#qtyPlanAmbil_${pickingOrderPlanId}_${skuKodeDiatas}_${expDateDiatas}_${typeData}`).text())
		const sisaAmbil = $(`#qtySisaAmbil_${pickingOrderPlanId}_${skuKodeDiatas}_${expDateDiatas}_${typeData}`)

		const dataku = $(`#qtyAktualPilihManual${pallet_id}`).map(function() {
			return this.value == '' ? 0 : parseInt(this.value)
		}).get().reduce(function(acc, akum) {
			return acc + akum
		}, 0);

		const akumulasi = qtyPlanAmbil - dataku;
		// sisaAmbil.html(akumulasi);

		if (valueQty > parseInt(qty_available)) {
			message('Error!', `Qty aktual ambil melebihi qty available <strong>${qty_available}</strong>`, 'error');
			return false;
		} else {

			const findParrent = arrScanPilihDetail.find((item) => (item.palletId === pallet_id) && (item.picking_order_plan_id === pickingOrderPlanId) && (item.skuKodeDiatas === skuKodeDiatas) && (item.expDateDiatas === expDateDiatas) && (item.typeData === typeData));

			const findIndexChild = findParrent.data.findIndex((item) => (item.pallet_id === pallet_id) && (item.sku_id === sku_id) && (item.sku_stock_expired_date === sku_stock_expired_date))

			findParrent.data[findIndexChild]['qtyAktual'] = valueQty
			// sisaAmbil.html(newValueSisa)
			initDataPalletDetail(findParrent.palletId);

			// if (valueQty > parseInt(sisaAmbil.text())) {
			// 	message('Error!', `Qty aktual ambil melebihi qty sisa <strong>${sisaAmbil.text()}</strong>`, 'error');
			// 	return false;
			// } else {
			// 	// const newValueSisa = parseInt(sisaAmbil.text()) - valueQty;

			// }
		}
	}

	const handlerDeletePalletDetail = (event, sku_id, sku_stock_expired_date, pallet_id) => {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "ingin delete row pallet detail!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Yakin",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {

				const pickingOrderPlanId = $("#pickingOrderPlanId").val()
				const skuKodeDiatas = $("#skuKodeDiatas").val()
				const expDateDiatas = $("#expDateDiatas").val()
				const typeData = $("#typeData").val()

				const findIndex = arrScanPilihDetail.findIndex((item) => (item.pallet_id === pallet_id) && (item.picking_order_plan_id === pickingOrderPlanId) && (item.skuKodeDiatas === skuKodeDiatas) && (item.expDateDiatas === expDateDiatas) && (item.typeData === typeData) && (item.sku_id === sku_id) && (item.sku_stock_expired_date === sku_stock_expired_date) && (item.pallet_id === pallet_id))

				const findData = arrScanPilihDetail.find((item) => (item.pallet_id === pallet_id) && (item.picking_order_plan_id === pickingOrderPlanId) && (item.skuKodeDiatas === skuKodeDiatas) && (item.expDateDiatas === expDateDiatas) && (item.typeData === typeData) && (item.sku_id === sku_id) && (item.sku_stock_expired_date === sku_stock_expired_date) && (item.pallet_id === pallet_id))


				const sisaAmbil = $(`#qtySisaAmbil_${pickingOrderPlanId}_${skuKodeDiatas}_${expDateDiatas}_${typeData}`)

				sisaAmbil.html(parseInt(sisaAmbil.text()) + parseInt(findData.qtyAktual));

				if (findIndex > -1) arrScanPilihDetail.splice(findIndex, 1);
				message('Success!', `Berhasil delete row pallet detail`, 'success');
				$("#initScanManualPalletDetail").fadeOut("slow", function() {
					$(this).hide();
				}).fadeIn("slow", function() {
					$(this).show();
					initDataPalletDetail(pallet_id);
				})
			}
		});

	}

	const chkQtyAktualAmbil = (event, qty_ambil, qty_sisa, qty, nama_rak, depo_detail_id, index, sku_kode, expired_date, pallet_detail_id, picking_order_plan_id, type) => {
		const value = event.currentTarget.value;
		if (isNaN(value) || value < 0) {
			message('Warning!', `Tidak boleh minus <strong>${value}</strong>`, 'warning');
			numberInput.value = ""; // Mengosongkan input jika nilai tidak valid
		}

		if (parseInt(value) > parseInt(qty_sisa)) {
			// console.log('oke');
			message('Error!', `Qty aktual ambil melebihi qty sisa <strong>${qty_sisa}</strong>`, 'error');
			return false;
		}

		if (parseInt(value) > parseInt(qty)) {
			// console.log('oke oke');
			message('Error!', `Qty aktual ambil melebihi qty tersedia <strong>${qty}</strong>`, 'error');
			return false;
		}

		checkDataInArray(depo_detail_id, nama_rak, index, sku_kode, expired_date, value, pallet_detail_id, false, picking_order_plan_id, type)
	}


	const chkedAmbilSemuaQty = (event, qty_ambil, qty_sisa, increment, nama_rak, depo_detail_id, sku_kode, expired_date, index, pallet_detail_id, picking_order_plan_id, type) => {
		let check = event;
		// console.log(event.srcElement.checked);

		let jumlah = $(`#jmlData_${index}`).val();
		let idx = $(`#index_${index}`).val();

		let idd = "";

		for (let i = 0; i < jumlah; i++) {
			let input = $(`#inputQtyAktual_${index}_${i}`);
			let qtyPlanAmbil = $(`#qtyPlanAmbil_${depo_detail_id}_${sku_kode}_${expired_date}_${picking_order_plan_id}_${type}`).text();
			let qtyTersedia = $(`#qtyTersedia_${depo_detail_id}_${nama_rak}_${index}_${sku_kode}_${expired_date}_${type}`).text();


			if (index == idx) {
				if (check.currentTarget.checked) {
					if (parseInt(qtyPlanAmbil) != 0) {
						if (parseInt(qty_sisa) > parseInt(qtyTersedia)) {
							if (parseInt(qty_sisa) != 0) {
								checkDataInArray(depo_detail_id, nama_rak, index, sku_kode, expired_date, parseInt(qtyTersedia), pallet_detail_id, true, picking_order_plan_id, type)
								input.val(parseInt(qtyTersedia));
								input.prop('disabled', true);
							} else {
								message("Error!", "gagal ambil semua, Sisa ambil 0", "error");
								event.srcElement.checked = false
								return false
							}
						} else {
							if (parseInt(qty_sisa) > parseInt(qtyTersedia)) {
								message("Error!", "gagal ambil semua, qty yang tersedia tidak mencukupi", "error");
								event.srcElement.checked = false
								return false
							} else {
								// console.log(parseInt(qtyTersedia) + " < " + parseInt(qtyPlanAmbil));
								if (parseInt(qty_sisa) != 0) {
									checkDataInArray(depo_detail_id, nama_rak, index, sku_kode, expired_date, qty_sisa, pallet_detail_id, true, picking_order_plan_id, type)
									input.val(qty_sisa);
									input.prop('disabled', true);
								} else {
									message("Error!", "gagal ambil semua, Sisa ambil 0", "error");
									event.srcElement.checked = false
									return false
								}
								// if (parseInt(qtyTersedia) < parseInt(qtyPlanAmbil)) {
								// 	message("Error!", "gagal ambil semua, qty yang tersedia melebihi qty plan ambil", "error");
								// 	event.srcElement.checked = false
								// 	return false
								// } else {

								// }

							}
						}
					}
				} else {
					input.prop("disabled", false);
					const findIndexData = qtyAktualTempArr.findIndex((item) => (item.depo_detail_id == depo_detail_id) && (item.nama_rak == nama_rak) && (item.pallet_id == index) && (item.sku_kode == sku_kode) && (item.expired_date == expired_date) && (item.picking_order_plan_id == picking_order_plan_id) && (item.type == type));
					idd += findIndexData;
					// qtyAktualTempArr[findIndexData]['value'] = 0;
					// qtyAktualTempArr[findIndexData]['checked'] = false;
					input.val(0);
					input.prop('disabled', false);
				}
			} else {
				message("Error!", "gagal ambil semua, sku tidak ada", "error");
				// const findIndexData = qtyAktualTempArr.findIndex((item) => (item.depo_detail_id == depo_detail_id) && (item.nama_rak == nama_rak) && (item.pallet_id == index) && (item.sku_kode == sku_kode) && (item.expired_date == expired_date));
				// qtyAktualTempArr[findIndexData]['checked'] = false;
				event.srcElement.checked = false
				return false
			}
		}

		if (idd != "") {
			const dt = Array.from(new Set(idd.split(''))).toString();
			const filterData = qtyAktualTempArr.filter((item, index) => index !== parseInt(dt));
			qtyAktualTempArr = [];

			$.each(filterData, function(i, v) {
				qtyAktualTempArr.push({
					depo_detail_id: v.depo_detail_id,
					nama_rak: v.nama_rak,
					pallet_id: v.pallet_id,
					sku_kode: v.sku_kode,
					expired_date: v.expired_date,
					pallet_detail_id: v.pallet_detail_id,
					value: v.value,
					checked: v.checked,
					picking_order_plan_id: v.picking_order_plan_id,
					type: v.type
				});
			})
		}
	}

	const checkDataInArray = (depo_detail_id, nama_rak, index, sku_kode, expired_date, value, pallet_detail_id, checked, picking_order_plan_id, type) => {
		if (qtyAktualTempArr.length === 0) {
			qtyAktualTempArr.push({
				depo_detail_id,
				nama_rak,
				pallet_id: index,
				sku_kode,
				expired_date,
				value: parseInt(value),
				pallet_detail_id,
				checked,
				picking_order_plan_id,
				type
			});

		} else {

			const findData = qtyAktualTempArr.find((item) => (item.depo_detail_id == depo_detail_id) && (item.nama_rak == nama_rak) && (item.pallet_id == index) && (item.sku_kode == sku_kode) && (item.expired_date == expired_date) && (item.picking_order_plan_id == picking_order_plan_id) && (item.type == type));

			if (typeof findData === 'undefined') {
				qtyAktualTempArr.push({
					depo_detail_id,
					nama_rak,
					pallet_id: index,
					sku_kode,
					expired_date,
					value: parseInt(value),
					pallet_detail_id,
					checked,
					picking_order_plan_id,
					type
				});
			} else {
				const findIndexData = qtyAktualTempArr.findIndex((item) => (item.depo_detail_id == depo_detail_id) && (item.nama_rak == nama_rak) && (item.pallet_id == index) && (item.sku_kode == sku_kode) && (item.expired_date == expired_date) && (item.picking_order_plan_id == picking_order_plan_id) && (item.type == type));
				qtyAktualTempArr[findIndexData]['value'] = parseInt(value);
				qtyAktualTempArr[findIndexData]['checked'] = checked;
			}
		}
	}

	const simpanHandlerLocationRak = (event) => {
		const dataParams = JSON.parse(event.currentTarget.getAttribute('data-params'));
		const datas = qtyAktualTempArr;

		let helper = {};
		let result = datas.reduce(function(r, o) {
			const key = o.sku_kode + '-' + o.expired_date + '-' + o.type;

			if (!helper[key]) {
				helper[key] = Object.assign({}, o); // create a copy of o
				r.push(helper[key]);
			} else {
				helper[key].value += o.value;
			}

			return r;
		}, []);

		$.each(result, (index, data) => {
			if ((data.sku_kode === dataParams.sku_kode) && (data.expired_date === dataParams.expired_date) && (data.picking_order_plan_id === dataParams.picking_order_plan_id) && (data.type === dataParams.type)) {

				if (parseInt(data.value) > parseInt(dataParams.qty_sisa)) {
					message("Error!", "Aktual Qty Ambil melebihi Sisa", 'error');
					return false;
				} else {
					Swal.fire({
						title: "Apakah anda yakin?",
						text: "Data akan disimpan!",
						icon: "warning",
						showCancelButton: true,
						confirmButtonColor: "#3085d6",
						cancelButtonColor: "#d33",
						confirmButtonText: "Ya, Simpan",
						cancelButtonText: "Tidak, Tutup"
					}).then((result) => {
						if (result.value == true) {

							checkFixedDataAktual(data.sku_kode, data.expired_date, parseInt(data.value), data.picking_order_plan_id, data.type, 'non-manual')

							$("#previewlokasirak").modal('hide');

							if (data.type === "Bulk") {
								$('#previewaddformbkbbulk').fadeOut("slow", function() {
									$(this).modal('hide');
								}).fadeIn("slow", function() {
									$(this).modal('show');
									ChPickingBKBBulkDetailTable(dataBulkGlobal)
									handlerCheckedRemainderTake(event)
									// alert('data berhasil disimpan');
									message_topright('success', 'data berhasil disimpan');
								});
							}

							if (data.type === "Standar") {
								$('#previewaddformbkbstandar').fadeOut("slow", function() {
									$(this).modal('hide');
								}).fadeIn("slow", function() {
									$(this).modal('show');
									ChPickingBKBStandarDetailTable(dataStandarGlobal)
									// alert('data berhasil disimpan');
									message_topright('success', 'data berhasil disimpan');
								});
							}

							if (data.type === "Reschedule") {
								$('#previewaddformbkbkirimulang').fadeOut("slow", function() {
									$(this).modal('hide');
								}).fadeIn("slow", function() {
									$(this).modal('show');
									ChPickingBKBKirimUlangDetailTable(dataKirimUlangGlobal)
									handlerCheckedRemainderTakeKirimUlang(event)
									// alert('data berhasil disimpan');
									message_topright('success', 'data berhasil disimpan');
								});
							}

							if (data.type === "Canvas") {
								$('#previewaddformbkbcanvas').fadeOut("slow", function() {
									$(this).modal('hide');
								}).fadeIn("slow", function() {
									$(this).modal('show');
									ChPickingBKBCanvasDetailTable(dataCanvasGlobal)
									handlerCheckedRemainderTakeCanvas(event)
									// alert('data berhasil disimpan');
									message_topright('success', 'data berhasil disimpan');
								});
							}
						}
					});
				}
			}
		})
	}

	const simpanHandlerScanManual = (event) => {

		const pickingOrderPlanId = $("#pickingOrderPlanId").val()
		const skuKodeDiatas = $("#skuKodeDiatas").val()
		const expDateDiatas = $("#expDateDiatas").val()
		const type = $("#typeData").val();

		// let error = false
		// $("input[name*='qtyAktualPilihManual']").map(function() {
		// 	if (this.value == "") {
		// 		error = true;
		// 		message("Error!", 'qty aktual tidak boleh kosong', 'error')
		// 		return false;
		// 	}
		// }).get();

		console.log('sebelum', arrScanPilihDetail);

		// if (error) return false;

		let helper = {};
		const datas = arrScanPilihDetail.filter((item) => {
			if ((item.picking_order_plan_id === pickingOrderPlanId) && (item.skuKodeDiatas === skuKodeDiatas) && (item.expDateDiatas === expDateDiatas) && (item.typeData === type)) {
				return item;
				console.log(item);
			}
		}).map((filtered) => {
			const response = filtered.data.map((child) => {
				return {
					picking_order_plan_id: filtered.picking_order_plan_id,
					skuKodeDiatas: filtered.skuKodeDiatas,
					expDateDiatas: filtered.expDateDiatas,
					typeData: filtered.typeData,
					...child
				}
			})
			return response
		});

		const response = JSON.parse(JSON.stringify(datas.flat())).reduce(function(r, o) {
			// const key = o.skuKodeDiatas + '-' + o.expDateDiatas + '-' + o.typeData + '-' + o.sku_id + '-' + o.sku_stock_expired_date + '-' + o.sku_stock_id;
			const key = `${o.skuKodeDiatas}-${o.expDateDiatas}-${o.typeData}-${o.sku_id}-${o.sku_stock_expired_date}-${o.sku_stock_id}`;

			if (!helper[key]) {
				helper[key] = Object.assign({}, o); // create a copy of o
				r.push(helper[key]);
			} else {
				helper[key].qtyAktual += o.qtyAktual;
			}
			return r;
		}, [])


		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Data akan disimpan!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {

				response.map((data) => {
					if (data.qtyAktual > 0) {
						if (arrScanPilihDetail2.length == 0) {
							arrScanPilihDetail2.push({
								...data
							})
						} else {
							const findData = arrScanPilihDetail2.find((item) => (item.picking_order_plan_id === data.picking_order_plan_id) && (item.skuKodeDiatas === data.skuKodeDiatas) && (item.expDateDiatas === data.expDateDiatas) && (item.typeData === data.typeData))
							if (typeof findData !== 'undefined') {
								arrScanPilihDetail2.push({
									...data
								})
							}
						}

						checkFixedDataAktual(data.skuKodeDiatas, data.expDateDiatas, parseInt(data.qtyAktual), data.picking_order_plan_id, data.typeData, 'manual')
					}

				})

				$("#modalPilihManual").modal('hide');

				if (type === "Bulk") {
					$('#previewaddformbkbbulk').fadeOut("slow", function() {
						$(this).modal('hide');
					}).fadeIn("slow", function() {
						$(this).modal('show');
						ChPickingBKBBulkDetailTable(dataBulkGlobal)
						handlerCheckedRemainderTake(event)
						// alert('data berhasil disimpan');
						message_topright('success', 'data berhasil disimpan');
					});
				}

				if (type === "Canvas") {
					$('#previewaddformbkbcanvas').fadeOut("slow", function() {
						$(this).modal('hide');
					}).fadeIn("slow", function() {
						$(this).modal('show');
						ChPickingBKBCanvasDetailTable(dataCanvasGlobal)
						handlerCheckedRemainderTakeCanvas(event)
						// alert('data berhasil disimpan');
						message_topright('success', 'data berhasil disimpan');
					});
				}

				// if (data.type === "Standar") {
				// 	$('#previewaddformbkbstandar').fadeOut("slow", function() {
				// 		$(this).modal('hide');
				// 	}).fadeIn("slow", function() {
				// 		$(this).modal('show');
				// 		ChPickingBKBStandarDetailTable(dataStandarGlobal)
				// 		// alert('data berhasil disimpan');
				// 		message_topright('success', 'data berhasil disimpan');
				// 	});
				// }
			}
		});


	}

	const checkFixedDataAktual = (sku_kode, expired_date, value, picking_order_plan_id, type, typeScan) => {
		if (typeScan == 'non-manual') {
			if (qtyAktualSaveTemp.length === 0) {
				qtyAktualSaveTemp.push({
					sku_kode,
					expired_date,
					value,
					picking_order_plan_id,
					type,
					typeScan: 'non-manual'
				});
			} else {

				const findData = qtyAktualSaveTemp.find((item) => (item.sku_kode == sku_kode) && (item.expired_date == expired_date) && (item.picking_order_plan_id == picking_order_plan_id) && (item.type == type) && (item.typeScan == 'non-manual'));

				if (typeof findData === 'undefined') {
					qtyAktualSaveTemp.push({
						sku_kode,
						expired_date,
						value,
						picking_order_plan_id,
						type,
						typeScan: 'non-manual'
					});
				} else {
					const findIndexData = qtyAktualSaveTemp.findIndex((item) => (item.sku_kode == sku_kode) && (item.expired_date == expired_date) && (item.picking_order_plan_id == picking_order_plan_id) && (item.type == type) && (item.typeScan == 'non-manual'));
					qtyAktualSaveTemp[findIndexData]['value'] = value;
				}
			}
		}

		if (typeScan == 'manual') {
			if (qtyAktualSaveTemp.length === 0) {
				qtyAktualSaveTemp.push({
					sku_kode,
					expired_date,
					value,
					picking_order_plan_id,
					type,
					typeScan: 'manual'
				});
			} else {

				const findData = qtyAktualSaveTemp.find((item) => (item.sku_kode == sku_kode) && (item.expired_date == expired_date) && (item.picking_order_plan_id == picking_order_plan_id) && (item.type == type) && (item.typeScan == 'manual'));

				if (typeof findData === 'undefined') {
					qtyAktualSaveTemp.push({
						sku_kode,
						expired_date,
						value,
						picking_order_plan_id,
						type,
						typeScan: 'manual'
					});
				} else {
					const findIndexData = qtyAktualSaveTemp.findIndex((item) => (item.sku_kode == sku_kode) && (item.expired_date == expired_date) && (item.picking_order_plan_id == picking_order_plan_id) && (item.type == type) && (item.typeScan == 'manual'));
					qtyAktualSaveTemp[findIndexData]['value'] = value;
				}
			}
		}

	}



	const detailHandlerClick = async (rak_lajur_detail_id, increment, nama_rak, depo_detail_id, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type) => {
		const url = "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDetailRakPalletById') ?>";

		let postData = new FormData();
		postData.append('skuKode', sku_kode);
		postData.append('expiredDate', expired_date);
		postData.append('rak_lajur_detail_id', rak_lajur_detail_id);
		postData.append('sameSKU', $("#sameSKU").prop('checked') == true ? 1 : 0);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const jmlQtyTemp = await qtyAktualTempArr;

		const response = await request.json();

		$(".detailRakPallet").empty();

		let html = "";
		html += `<div style="margin-left:25px" class="table-responsive">
								<table class="table table-striped table-bordered" width="100%" id="tableDetailRakPallet">
									<thead>
											<tr class="text-center">
												<td width="5%"><strong>#</strong></td>
												<td width="10%"><strong>Pallet Kode</strong></td>
												<td width="8%"><strong>Jenis</strong></td>
												<td width="15%"><strong>Keluarkan pallet dalam rak</strong></td>
												<td width="8%"><strong>SKU Kode</strong></td>
												<td width="20%"><strong>SKU</strong></td>
												<td width="8%"><strong>QTY</strong></td>
												<td width="8%"><strong>Aktual Qty Ambil</strong></td>
												<td width="10%"><strong>Expired Date</strong></td>
											</tr>
									</thead>
									<tbody>`;
		let no = 1;
		let disble = 0;
		let nondisble = 0;

		let isScan = 0;
		let notScan = 0;

		let arr_chek = [];
		let arrScan = [];

		$.each(response, function(index, value) {


			let style = "";

			isScan = 0;
			notScan = 0;

			if (scanPalletArr.length != 0) {
				const findData = scanPalletArr.find((item) => (item.depo_detail_id === depo_detail_id) && (item.nama_rak === nama_rak) && (item.pallet_id === index) && (item.sku_kode === sku_kode) && (item.expired_date === expired_date) && (item.type === type));
				if (typeof findData !== 'undefined') {
					if (findData.pallet_id === index) {
						style += "background-color: #86efac; color:#0f172a";
						isScan++;
					}
				} else {
					style += "";
					notScan++;
				}
			} else {
				notScan++;
			}

			let new_qty = "";
			let chkData = "";
			let dsbData = "";

			if (jmlQtyTemp.length != 0) {
				const findData = jmlQtyTemp.find((item) => (item.depo_detail_id === depo_detail_id) && (item.nama_rak === nama_rak) && (item.pallet_id === index) && (item.type === type));
				if (typeof findData !== 'undefined') {
					if ((findData.pallet_id === index) && (findData.sku_kode === sku_kode) && (findData.expired_date === expired_date)) {
						new_qty += findData.value;

						dsbData += findData.checked ? "disabled" : "";
					}

					if (findData.pallet_id === index) {
						chkData += findData.checked ? "checked" : "";
					}
				} else {
					new_qty += "";
					chkData += "";
					dsbData += "";
				}
			}

			nondisble = 0
			disble = 0


			let initRow = "";

			$.each(value.data, function(i, v) {
				if ((v.sku_kode == sku_kode) && (v.sku_stock_expired_date == expired_date)) {
					nondisble++
				} else {
					disble++
				}

				html += `<tr style="${style}">`;
				if (i == 0) {
					html += `<td style="vertical-align: middle" class="text-center" rowspan="${value.data.length}">${no++}</td>
									 <td style="vertical-align: middle" class="text-center" rowspan="${value.data.length}">${value.pallet_kode}</td>
									 <td style="vertical-align: middle" class="text-center" rowspan="${value.data.length}">${value.pallet_jenis_nama}</td>
									 <td style="vertical-align: middle" class="text-center" rowspan="${value.data.length}">
									 		<div class="head-switch" style="display:none;>
													<div class="switch-holder">
															<div class="switch-toggle">
																	<input type="checkbox" id="check_scan_${index}" class="check_scan" onchange="changeScanPalletHandler(event, '${index}')">
																	<label for="check_scan_${index}"></label>
															</div>
													
															<div class="switch-label" style="margin-left: -40px;">
																	<button type="button" class="btn btn-info start_scan_pallet" id="start_scan_pallet_${index}" name="start_scan_pallet_${index}" onclick="startScanPalletHandler('${nama_rak}', '${depo_detail_id}', '${rak_lajur_detail_id}', '${increment}', '${qty_ambil}', '${qty_sisa}', '${sku_kode}', '${expired_date}', '${picking_order_plan_id}', '${type}', 'non-pilih', null)"> <i class="fas fa-qrcode"></i> Scan</button>
																	<button type="button" class="btn btn-warning input_pallet" id="input_pallet_${index}" name="input_pallet_${index}" style="display:none" onclick="inputScanPalletHandler('${nama_rak}', '${depo_detail_id}', '${rak_lajur_detail_id}', '${increment}','${qty_ambil}', '${qty_sisa}', '${sku_kode}', '${expired_date}', '${picking_order_plan_id}','${type}', 'non-pilih', null)"> <i class="fas fa-keyboard"></i> Input</button>
															</div>
													</div>
											</div>

											<div style="margin-top:10px">
												<input type="checkbox" style="transform:scale(1.5); margin-right: 7px" id="chkedAll_${index}" onchange="chkedAmbilSemuaQty(event, '${qty_ambil}', '${qty_sisa}', '${increment}', '${nama_rak}', '${depo_detail_id}', '${sku_kode}', '${expired_date}', '${index}', '${v.pallet_detail_id}', '${picking_order_plan_id}', '${type}')" ${chkData}/> 
											</div>
											<div class="alert" id="information_chk_${index}" style="background-color: #cffafe; margin-top:10px; font-size: 12px; display: none" role="alert">
												<i class="fa fa-exclamation-triangle"></i>
												<strong>Informasi!</strong> pallet yang diambil dari rak akan merubah status rak jika rak nya kosong
											</div>
									 </td>`;
				}

				html += `<td style="display:none">
										<input type="hidden" id="jmlData_${index}" data-index="${index}" value="${value.data.length}"/>
										<input type="hidden" id="index_${index}"  value="${index}"/>
								 </td>
								 <td class="text-center">${v.sku_kode}</td>
								 <td>${v.sku_nama_produk}</td>
								 <td class="text-center" id="qtyTersedia_${depo_detail_id}_${nama_rak}_${index}_${sku_kode}_${expired_date}_${type}">${v.sku_stock_qty}</td>`;

				if ((v.sku_kode === sku_kode) && (v.sku_stock_expired_date === expired_date)) {
					html += `<td class="text-center">
										  <input type="number"  min="0" class="form-control numeric inputQtyAktual_${depo_detail_id}_${nama_rak}_${index}_${sku_kode}_${expired_date}_${type}" id="inputQtyAktual_${index}_${i}" value="${(new_qty != "") ? new_qty : 0}" onchange="chkQtyAktualAmbil(event, '${qty_ambil}', '${qty_sisa}', '${v.sku_stock_qty}', '${nama_rak}', '${depo_detail_id}', '${index}', '${sku_kode}', '${expired_date}', '${v.pallet_detail_id}', '${picking_order_plan_id}', '${type}')" ${dsbData}/>
									</td>`;
				} else {
					html += `<td class="text-center">
											<input type="number" class="form-control" disabled/>
									</td>`
				}

				// html += "<td class='text-center'>";

				// html += "</td>";
				html += `<td class="text-center">${v.sku_stock_expired_date}</td>`;
				html += '</tr>';
			});

			arr_chek.push({
				pallet_id: index,
				nondisble,
				disble
			});

			arrScan.push({
				isScan,
				notScan,
				pallet_id: index,
				sku_kode,
				expired_date,
				nama_rak,
				depo_detail_id,
				type
			});

		});

		html += `</tbody>
						</table>
					</div>`;

		$(`#detailRakPallet_${nama_rak}_${type}`).append(html);

		$.each(arr_chek, (i, {
			nondisble,
			pallet_id
		}) => {
			//non disabled checkbox
			if (nondisble > 0) {
				$(`#chkedAll_${pallet_id}`).prop('disabled', false);
				$(`#information_chk_${pallet_id}`).show();

			} else {
				//disabled checkbox
				$(`#chkedAll_${pallet_id}`).prop('disabled', true);
				$(`#information_chk_${pallet_id}`).hide();
			}
		})

		$.each(arrScan, (i, v) => {
			//non disabled checkbox
			if (v.isScan > 0) {
				$(`.inputQtyAktual_${v.depo_detail_id}_${v.nama_rak}_${v.pallet_id}_${v.sku_kode}_${v.expired_date}_${v.type}`).prop('disabled', false);
				$(`.inputQtyAktual_${v.depo_detail_id}_${v.nama_rak}_${v.pallet_id}_${v.sku_kode}_${v.expired_date}_${v.type}`).removeAttr('data-toggle data-placement title');

				$(`#chkedAll_${v.pallet_id}`).prop('disabled', false);
				$(`#information_chk_${v.pallet_id}`).show();


			} else {
				//disabled checkbox
				$(`.inputQtyAktual_${v.depo_detail_id}_${v.nama_rak}_${v.pallet_id}_${v.sku_kode}_${v.expired_date}_${v.type}`).prop('disabled', true);
				$(`.inputQtyAktual_${v.depo_detail_id}_${v.nama_rak}_${v.pallet_id}_${v.sku_kode}_${v.expired_date}_${v.type}`).attr({
					"data-toggle": "tooltip",
					"data-placement": "top", // attributes which contain dash(-) should be covered in quotes.
					title: "Scan Pallet terlebih dahulu",
				});

				$(`#chkedAll_${v.pallet_id}`).prop('disabled', true);
				$(`#information_chk_${v.pallet_id}`).hide();
			}
		})
		$('.numeric').on('input', function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});

	}

	const previewHandlerLocationRak = async (depo_detail_id, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type, sku_id, sku_nama_produk) => {

		$("#previewlokasirak").modal('show');

		const url = "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetLocationRak') ?>";

		let postData = new FormData();
		postData.append('depo_detail_id', depo_detail_id);
		postData.append('sku_id', sku_id);
		postData.append('expired_date', expired_date);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		const params = {
			sku_kode,
			expired_date,
			qty_sisa,
			picking_order_plan_id,
			type
		}

		$("#simpanHandlerLocationRak").attr('data-params', JSON.stringify(params));

		initDataLocationRak(response, depo_detail_id, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type, sku_nama_produk);
		$("#rowswitch").empty()
		$("#rowswitch").append(`
		<div><h4 style="margin-left: 20px;"><strong>Scan Pallet</strong>
</h4></div>
		<div class="head-switchindex">
													<div class="switch-holder">
															<div class="switch-toggle">
																	<input type="checkbox" id="check_scan" class="check_scan" onchange="changeScanPalletHandlerIndex(event)">
																	<label for="check_scan"></label>
															</div>
													
															<div class="switch-label" style="margin-left: -40px;">
																	<button type="button" class="btn btn-info start_scan_pallet" id="start_scan_pallet" name="start_scan_pallet" onclick="startScanPalletHandler(null, '${depo_detail_id}', null, null, '${qty_ambil}', '${qty_sisa}', '${sku_kode}', '${expired_date}', '${picking_order_plan_id}', '${type}', 'non-pilih', null)"> <i class="fas fa-qrcode"></i> Scan</button>
																	<button type="button" class="btn btn-warning input_pallet" id="input_pallet" name="input_pallet" style="display:none" onclick="inputScanPalletHandler(null, '${depo_detail_id}', null, null,'${qty_ambil}', '${qty_sisa}', '${sku_kode}', '${expired_date}', '${picking_order_plan_id}','${type}', 'non-pilih', null)"> <i class="fas fa-keyboard"></i> Input</button>
															</div>
													</div>
											</div>`)

	}

	const initDataLocationRak = (response, depo_detail_id, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type, sku_nama_produk) => {

		$("#informasi-sku").empty();
		$("#informasi-sku").append(`
			<p>Kode SKU : <strong>${sku_kode}</strong></p>
			<p>Nama Produk : <strong>${sku_nama_produk}</strong></p>
		`);

		$("#is-filled").empty();
		$("#is-filled").append(`
		<span><strong>Plan Qty Ambil</strong> &nbsp;: <span id="qtyPlanAmbil_${depo_detail_id}_${sku_kode}_${expired_date}_${picking_order_plan_id}_${type}">${qty_ambil}</span></span>&nbsp; | <span>&nbsp; <strong>Sisa Ambil</strong> &nbsp;: ${qty_sisa}</span>
		`);

		$("#dtlocationrak").empty();

		if (Object.keys(response).length > 0) {
			$.each(response, function(i, v) {
				let html = "";
				html += `<h2 style="color:#0f172a;font-weight: 900; text-transform: uppercase; margin-left:10px;letter-spacing:2px;border-bottom: 2px solid black; width:45%;font-family: 'Quicksand'">${i}</h2>`;
				html += `<div class="location-rak">`
				$.each(v.data, function(index, value) {
					html += `<div class="sub-location-rak" onclick="detailHandlerClick('${value.id}', '${index}', '${value.sub_nama}', '${depo_detail_id}', '${qty_ambil}', '${qty_sisa}', '${sku_kode}', '${expired_date}', '${picking_order_plan_id}', '${type}')">
					${value.sub_nama} 
					</div>`;
					html += `<div id="detailRakPallet_${value.sub_nama}_${type}" class="detailRakPallet"></div>`
				});
				html += "</div>"
				// <span><i class="fas fa-check"></i></span>
				$("#dtlocationrak").append(html);
			});

		} else {
			$("#dtlocationrak").append("<h4 class='text-center text-danger'>Data Kosong</h4>")
		}
	}

	const handlerPilihByScanPallet = async (depo_detail_id, qty_ambil, qty_sisa, sku_kode, expired_date, picking_order_plan_id, type, sku_id, sku_nama_produk) => {

		$("#modalPilihManual").modal('show');

		$('#check_scan_pilih').prop('checked', false)
		$("#start_scan_pallet_pilih").show();
		$("#input_pallet_pilih").hide();
		$("#is-filled-pilih").empty();
		// $("#initScanManualPallet .panel-heading").empty();
		$("#initScanManualPallet .panel-body table tbody").empty();
		$("#pickingOrderPlanId").val('');
		$("#skuKodeDiatas").val('');
		$("#expDateDiatas").val('');
		$("#typeData").val('');

		if ($.fn.DataTable.isDataTable('#initScanManualPalletDetail')) {
			$('#initScanManualPalletDetail').DataTable().destroy();
		}

		$("#initScanManualPalletDetail tbody").empty();
		// $("#initScanManualPalletDetail").DataTable({
		// 	lengthMenu: [
		// 		[-1],
		// 		['All']
		// 	],
		// });

		$("#informasi-sku-pilih").empty();
		$("#informasi-sku-pilih").append(`
			<p>Kode SKU : <strong>${sku_kode}</strong></p>
			<p>Nama Produk : <strong>${sku_nama_produk}</strong></p>
		`);

		$("#is-filled-pilih").empty();
		$("#is-filled-pilih").append(`
					<span><strong>Plan Qty Ambil</strong> &nbsp;: <span id="qtyPlanAmbil_${picking_order_plan_id}_${sku_kode}_${expired_date}_${type}">${qty_ambil}</span></span>&nbsp; | <span>&nbsp; <strong>Sisa Ambil</strong> &nbsp;: <span id="qtySisaAmbil_${picking_order_plan_id}_${sku_kode}_${expired_date}_${type}">${qty_sisa}</span></span>`);

		$("#pickingOrderPlanId").val(picking_order_plan_id)
		$("#skuKodeDiatas").val(sku_kode)
		$("#expDateDiatas").val(expired_date)
		$("#typeData").val(type)

		const url = "<?= base_url('WMS/Distribusi/PengeluaranBarang/getPalletBySkuId') ?>";

		let postData = new FormData();
		postData.append('sku_id', sku_id);

		const request = await fetch(url, {
			method: 'POST',
			body: postData
		});

		const response = await request.json();

		initDataPallet(response)
		return false;

		const findPallet = arrScanPallet.find((item) => (item.pickingOrderPlanId === picking_order_plan_id) && (item.skuKodeDiatas === sku_kode) && (item.expDateDiatas === expired_date) && (item.typeData === type))


		if (typeof findPallet !== 'undefined') {
			const oldDataPallet = {
				data: {
					pallet_id: findPallet.pallet_id,
					pallet_kode: findPallet.pallet_kode,
					pallet_jenis_kode: findPallet.pallet_jenis_kode,
					depo_detail_nama: findPallet.depo_detail_nama,
				}
			}

			const findPalletDetail = arrScanPilihDetail.filter((item) => (item.pallet_id === findPallet.pallet_id) && (item.skuKodeDiatas === sku_kode) && (item.expDateDiatas === expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.typeData === type))

			initDataPalletLayout(oldDataPallet)

			if (findPalletDetail.length > 0) {
				initDataPalletDetailLayout(findPalletDetail)

				$("#initScanManualPalletDetail").DataTable({
					lengthMenu: [
						[-1],
						['All']
					],
				});
			}

			const qtyPlanAmbil = parseInt($(`#qtyPlanAmbil_${picking_order_plan_id}_${sku_kode}_${expired_date}_${type}`).text())
			const sisaAmbil = $(`#qtySisaAmbil_${picking_order_plan_id}_${sku_kode}_${expired_date}_${type}`)

			const dataku = $("input[name*='qtyAktualPilihManual']").map(function() {
				return this.value == '' ? 0 : parseInt(this.value)
			}).get().reduce(function(acc, akum) {
				return acc + akum
			}, 0);

			const akumulasi = qtyPlanAmbil - dataku;
			// sisaAmbil.html(akumulasi);
		}
	}

	const handlerPilihManul = (event, index, sku_kode, sku_stock_expired_date, picking_order_plan_id, type, sku_id) => {

		const findIndex = arrDataChkBKB.findIndex((item) => (item.sku_id === sku_id) && (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type))


		if (event.currentTarget.checked) {
			Swal.fire({
				title: "Apakah anda yakin?",
				text: "untuk pakai manual!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Pakai",
				cancelButtonText: "Tidak, Tutup"
			}).then((result) => {
				if (result.value == true) {

					arrDataChkBKB[findIndex]['checked'] = true;

					const findIndexQtyAktualTempArr = qtyAktualTempArr.findIndex((item) => (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type))
					const findIndexScanPalletArr = scanPalletArr.findIndex((item) => (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type))
					const findIndexQtyAktualSaveTemp = qtyAktualSaveTemp.findIndex((item) => (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type) && (item.typeScan === 'non-manual'))

					if (findIndexQtyAktualTempArr > -1) qtyAktualTempArr.splice(findIndexQtyAktualTempArr, 1);
					if (findIndexScanPalletArr > -1) scanPalletArr.splice(findIndexScanPalletArr, 1);
					if (findIndexQtyAktualSaveTemp > -1) qtyAktualSaveTemp.splice(findIndexQtyAktualSaveTemp, 1);

					$("#dtlocationrak").empty();
					$("#informasi-sku").empty()
					$("#is-filled").empty()

					if (type === "Bulk") {
						$('#previewaddformbkbbulk').fadeOut("slow", function() {
							$(this).modal('hide');
						}).fadeIn("slow", function() {
							$(this).modal('show');
							ChPickingBKBBulkDetailTable(dataBulkGlobal)
							handlerCheckedRemainderTake(event)
							// alert('data berhasil disimpan');
							// message_topright('success', 'data berhasil disimpan');
						});
					}

					if (type === "Canvas") {
						$('#previewaddformbkbcanvas').fadeOut("slow", function() {
							$(this).modal('hide');
						}).fadeIn("slow", function() {
							$(this).modal('show');
							ChPickingBKBCanvasDetailTable(dataCanvasGlobal)
							handlerCheckedRemainderTakeCanvas(event)
							// alert('data berhasil disimpan');
							// message_topright('success', 'data berhasil disimpan');
						});
					}

					// $(`#previewHandlerLocationRak${index}`).hide();
					// $(`#handlerPilihByScanPallet${index}`).show();
				}

				if (typeof result.value === 'undefined') {
					event.target.checked = false;
				}
			});

		} else {

			Swal.fire({
				title: "Apakah anda yakin?",
				text: "untuk pakai non-manual!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Pakai",
				cancelButtonText: "Tidak, Tutup"
			}).then((result) => {
				if (result.value == true) {

					arrDataChkBKB[findIndex]['checked'] = false;

					const findIndexArrScanPilihPallet = arrScanPallet.map((item, index) => {
						if ((item.skuKodeDiatas == sku_kode) && (item.expDateDiatas == sku_stock_expired_date) && (item.pickingOrderPlanId == picking_order_plan_id) && (item.typeData == type)) {
							return index
						}
					});

					const findIndexArrScanPilihDetail = arrScanPilihDetail.map((item, index) => {
						if ((item.skuKodeDiatas === sku_kode) && (item.expDateDiatas === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.typeData === type)) {
							return index
						}
					});

					const findIndexArrScanPilihDetail2 = arrScanPilihDetail2.map((item, index) => {
						if ((item.skuKodeDiatas === sku_kode) && (item.expDateDiatas === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.typeData === type)) {
							return index
						}
					});

					const findIndexQtyAktualSaveTemp = qtyAktualSaveTemp.findIndex((item) => (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type) && (item.typeScan === 'manual'))

					for (let i = findIndexArrScanPilihPallet.length - 1; i >= 0; i--) {
						arrScanPallet.splice(findIndexArrScanPilihPallet[i], 1)
					}
					for (let i = findIndexArrScanPilihDetail.length - 1; i >= 0; i--) {
						arrScanPilihDetail.splice(findIndexArrScanPilihDetail[i], 1)
					}
					for (let i = findIndexArrScanPilihDetail2.length - 1; i >= 0; i--) {
						arrScanPilihDetail2.splice(findIndexArrScanPilihDetail2[i], 1)
					}

					if (findIndexQtyAktualSaveTemp > -1) qtyAktualSaveTemp.splice(findIndexQtyAktualSaveTemp, 1);

					$('#check_scan_pilih').prop('checked', false)
					$("#start_scan_pallet_pilih").show();
					$("#input_pallet_pilih").hide();
					$("#informasi-sku-pilih").empty();
					$("#is-filled-pilih").empty();
					// $("#initScanManualPallet .panel-heading").empty();
					$("#initScanManualPallet .panel-body table tbody").empty();
					$("#pickingOrderPlanId").val('');
					$("#skuKodeDiatas").val('');
					$("#expDateDiatas").val('');
					$("#typeData").val('');

					if ($.fn.DataTable.isDataTable('#initScanManualPalletDetail')) {
						$('#initScanManualPalletDetail').DataTable().destroy();
					}

					$("#initScanManualPalletDetail tbody").empty();
					$("#initScanManualPalletDetail").DataTable({
						lengthMenu: [
							[-1],
							['All']
						],
					});

					if (type === "Bulk") {
						$('#previewaddformbkbbulk').fadeOut("slow", function() {
							$(this).modal('hide');
						}).fadeIn("slow", function() {
							$(this).modal('show');
							ChPickingBKBBulkDetailTable(dataBulkGlobal)
							handlerCheckedRemainderTake(event)
							// alert('data berhasil disimpan');
							// message_topright('success', 'data berhasil disimpan');
						});
					}

					if (type === "Canvas") {
						$('#previewaddformbkbcanvas').fadeOut("slow", function() {
							$(this).modal('hide');
						}).fadeIn("slow", function() {
							$(this).modal('show');
							ChPickingBKBCanvasDetailTable(dataCanvasGlobal)
							handlerCheckedRemainderTakeCanvas(event)
							// alert('data berhasil disimpan');
							// message_topright('success', 'data berhasil disimpan');
						});
					}


					// $(`#previewHandlerLocationRak${index}`).show();
					// $(`#handlerPilihByScanPallet${index}`).hide();
				}

				if (typeof result.value === 'undefined') {
					event.target.checked = true;
				}
			});

		}
	}

	function ChPickingBKBBulkDetailTable(JSONChannel) {

		$("#tableaddbkbbulk > tbody").html('');

		$('#loadingaddbkbbulk').hide();

		// var Channel = JSON.parse(JSONChannel);
		var Channel = JSONChannel;
		// console.log(Channel);
		const type = "Bulk";

		if (Channel.length > 0) {

			if ($.fn.DataTable.isDataTable('#tableaddbkbbulk')) {
				$('#tableaddbkbbulk').DataTable().destroy();
			}

			$('#tableaddbkbbulk tbody').empty();

			dataBulkNew = [];

			for (i = 0; i < Channel.length; i++) {
				var principal = Channel[i].principal;
				var brand = Channel[i].brand;
				var sku_id = Channel[i].sku_id;
				var sku_kode = Channel[i].sku_kode;
				var sku_nama_produk = Channel[i].sku_nama_produk;
				var sku_kemasan = Channel[i].sku_kemasan;
				var sku_satuan = Channel[i].sku_satuan;
				var karyawan_nama = Channel[i].karyawan_nama;
				var picking_order_plan_id = Channel[i].picking_order_plan_id;
				var delivery_order_id = Channel[i].delivery_order_id;
				var sku_stock_id = Channel[i].sku_stock_id;
				var sku_stock_expired_date = Channel[i].sku_stock_expired_date;
				var sku_stock_qty_ambil = Channel[i].sku_stock_qty_ambil;
				var total_ambil = Channel[i].total_ambil;
				var sisa_stock = sku_stock_qty_ambil - Channel[i].total_ambil;
				var depo_detail_id = Channel[i].depo_detail_id;
				var depo_detail_nama = Channel[i].depo_detail_nama;
				var delivery_order_batch_kode = Channel[i].delivery_order_batch_kode;
				var delivery_order_batch_tanggal_kirim = Channel[i].delivery_order_batch_tanggal_kirim;
				var delivery_order_kode = Channel[i].delivery_order_kode;
				var karyawan_id = Channel[i].karyawan_id;
				var picking_order_id = Channel[i].picking_order_id;
				var picking_order_keterangan = Channel[i].picking_order_keterangan;
				var picking_order_kode = Channel[i].picking_order_kode;
				var picking_order_plan_nourut = Channel[i].picking_order_plan_nourut;
				var picking_order_plan_status = Channel[i].picking_order_plan_status;
				var picking_order_tanggal = Channel[i].picking_order_tanggal;
				var tipe_delivery_order_id = Channel[i].tipe_delivery_order_id;
				var tipe_delivery_order_alias = Channel[i].tipe_delivery_order_alias;

				let new_qty = "";

				console.log(qtyAktualSaveTemp);

				if (qtyAktualSaveTemp.length != 0) {
					const findData = qtyAktualSaveTemp.find((item) => (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type));
					if (typeof findData !== 'undefined') {
						new_qty += findData.value;
					} else {
						new_qty += "";
					}
				}

				let finalQty = new_qty != "" ? parseInt(Math.round(sisa_stock)) - parseInt(new_qty) : Math.round(sisa_stock);

				const findDataChk = arrDataChkBKB.find((item) => (item.sku_id === sku_id) && (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type));
				let chckedScan = "";
				if (typeof findDataChk === 'undefined') {
					arrDataChkBKB.push({
						sku_id,
						sku_kode,
						expired_date: sku_stock_expired_date,
						picking_order_plan_id,
						type,
						checked: false
					})
					chckedScan += false
				} else {
					chckedScan += findDataChk.checked
				}

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtjml" value="' + Channel.length + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtdeliveryorderid' + i + '" value="' + delivery_order_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtpickingorderplanid' + i + '" value="' + picking_order_plan_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskuid' + i + '" value="' + sku_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtnamasku' + i + '" value="' + sku_nama_produk + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskustockid' + i + '" value="' + sku_stock_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="depoDetailId' + i + '" value="' + depo_detail_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="maxqty' + i + '" value="' + Math.round(sku_stock_qty_ambil) + '">';
				strmenu = strmenu + principal;
				strmenu = strmenu + '	</td>';
				strmenu = strmenu + '	<td>' + brand + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
				strmenu = strmenu + `	<td> ${finalQty}</td>`;
				strmenu = strmenu + `	<td><input type="number" class="form-control" id="txtqtyambil${i}" value="${(new_qty != "") && (total_ambil != null) ? parseInt(new_qty) + parseInt(total_ambil) : new_qty != "" ? new_qty : total_ambil != null ? total_ambil : 0}" disabled></td>`;
				strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" disabled><option value="' + sku_stock_id + ' || ' + sku_stock_expired_date + '">' + sku_stock_expired_date + '</option></select></td>';
				strmenu = strmenu + `	<td><input type="checkbox" style="transform:scale(1.5)" id="pilihManual${i}" ${chckedScan == "false" ? '' : 'checked'} onchange="handlerPilihManul(event, '${i}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}')"/></td>`;
				strmenu = strmenu + `	<td>
																		<button type="button" class="btn btn-primary" id="previewHandlerLocationRak${i}" onclick="previewHandlerLocationRak('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${finalQty}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')" style="display: ${chckedScan == "false" ? 'block' : 'none'}">${depo_detail_nama}</button>

																		<button type="button" class="btn btn-info" id="handlerPilihByScanPallet${i}" onclick="handlerPilihByScanPallet('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${finalQty}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')" style="display: ${chckedScan == "false" ? 'none' : 'block'}">Pilih</button>
																</td>`;
				strmenu = strmenu + '</tr>';


				dataBulkNew.push({
					brand,
					delivery_order_batch_kode,
					delivery_order_batch_tanggal_kirim,
					delivery_order_id,
					delivery_order_kode,
					depo_detail_id,
					depo_detail_nama,
					karyawan_id,
					karyawan_nama,
					picking_order_id,
					picking_order_keterangan,
					picking_order_kode,
					picking_order_plan_id,
					picking_order_plan_nourut,
					picking_order_plan_status,
					picking_order_tanggal,
					principal,
					sku_id,
					sku_kemasan,
					sku_kode,
					sku_nama_produk,
					sku_satuan,
					sku_stock_expired_date,
					sku_stock_id,
					sku_stock_qty_ambil,
					tipe_delivery_order_id,
					tipe_delivery_order_alias,
					total_ambil: new_qty != "" ? parseInt(Math.round(sisa_stock)) - parseInt(new_qty) : Math.round(sisa_stock),
					actual_qty_ambil: parseInt((new_qty != "") && (total_ambil != null) ? parseInt(new_qty) + parseInt(total_ambil) : new_qty != "" ? new_qty : total_ambil != null ? total_ambil : 0),
					chckedScan

				})

				$("#tableaddbkbbulk > tbody").append(strmenu);
			}

			$('#tableaddbkbbulk').DataTable({
				"lengthMenu": [
					[-1],
					['All']
				]
			});
		}
	}

	function ChPickingBKBStandarDetailTable(JSONChannel) {
		$("#tableaddbkbstandar > tbody").html('');

		$('#loadingaddbkbstandar').hide();

		var Channel = JSON.parse(JSONChannel);
		const type = "Standar";

		if (Channel.PengeluaranBarang != 0) {

			if ($.fn.DataTable.isDataTable('#tableaddbkbstandar')) {
				$('#tableaddbkbstandar').DataTable().destroy();
			}

			$('#tableaddbkbstandar tbody').empty();

			for (i = 0; i < Channel.PengeluaranBarang.length; i++) {
				var delivery_order_kode = Channel.PengeluaranBarang[i].delivery_order_kode;
				var principal = Channel.PengeluaranBarang[i].principal;
				var brand = Channel.PengeluaranBarang[i].brand;
				var sku_id = Channel.PengeluaranBarang[i].sku_id;
				var sku_kode = Channel.PengeluaranBarang[i].sku_kode;
				var sku_nama_produk = Channel.PengeluaranBarang[i].sku_nama_produk;
				var sku_kemasan = Channel.PengeluaranBarang[i].sku_kemasan;
				var sku_satuan = Channel.PengeluaranBarang[i].sku_satuan;
				var sku_stock_qty_ambil = Channel.PengeluaranBarang[i].sku_stock_qty_ambil;
				var karyawan_nama = Channel.PengeluaranBarang[i].karyawan_nama;
				var picking_order_plan_id = Channel.PengeluaranBarang[i].picking_order_plan_id;
				var delivery_order_id = Channel.PengeluaranBarang[i].delivery_order_id;
				var sku_stock_id = Channel.PengeluaranBarang[i].sku_stock_id;
				var sku_stock_expired_date = Channel.PengeluaranBarang[i].sku_stock_expired_date;
				var sku_stock_qty_ambil = Channel.PengeluaranBarang[i].sku_stock_qty_ambil;
				var total_ambil = Channel.PengeluaranBarang[i].total_ambil;
				var sisa_stock = sku_stock_qty_ambil - Channel.PengeluaranBarang[i].total_ambil;
				var depo_detail_id = Channel.PengeluaranBarang[i].depo_detail_id;
				var depo_detail_nama = Channel.PengeluaranBarang[i].depo_detail_nama;

				let new_qty = "";
				if (qtyAktualSaveTemp.length != 0) {
					const findData = qtyAktualSaveTemp.find((item) => (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type));
					if (typeof findData !== 'undefined') {
						new_qty += findData.value;
					} else {
						new_qty += "";
					}
				}

				let finalQty = new_qty != "" ? parseInt(Math.round(sisa_stock)) - parseInt(new_qty) : Math.round(sisa_stock);

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtjml" value="' + Channel.PengeluaranBarang.length + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtdeliveryorderid' + i + '" value="' + delivery_order_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtpickingorderplanid' + i + '" value="' + picking_order_plan_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskuid' + i + '" value="' + sku_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtnamasku' + i + '" value="' + sku_nama_produk + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskustockid' + i + '" value="' + sku_stock_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="depoDetailId' + i + '" value="' + depo_detail_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="maxqty' + i + '" value="' + Math.round(sku_stock_qty_ambil) + '">';
				strmenu = strmenu + delivery_order_kode;
				strmenu = strmenu + '	</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
				strmenu = strmenu + `	<td>${finalQty}</td>`;
				strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
				strmenu = strmenu + `	<td><input type="number" class="form-control" id="txtqtyambil${i}" value="${(new_qty != "") && (total_ambil != null) ? parseInt(new_qty) + parseInt(total_ambil) : new_qty != "" ? new_qty : total_ambil != null ? total_ambil : 0}" disabled></td>`;
				strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" disabled><option value="' + sku_stock_id + ' || ' + sku_stock_expired_date + '">' + sku_stock_expired_date + '</option></select></td>';
				strmenu = strmenu + `	<td>
																		<button type="button" class="btn btn-primary" onclick="previewHandlerLocationRak('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${finalQty}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')">${depo_detail_nama}</button>
																</td>`;
				strmenu = strmenu + '</tr>';

				$("#tableaddbkbstandar > tbody").append(strmenu);
			}

			$('#tableaddbkbstandar').DataTable({
				"lengthMenu": [
					[10],
					[10]
				],
				"searching": false,
				"ordering": false
			});
		}

	}


	function ChPickingBKBKirimUlangDetailTable(JSONChannel) {

		$("#tableaddbkbkirimulang > tbody").html('');

		$('#loadingaddbkbkirimulang').hide();

		// var Channel = JSON.parse(JSONChannel);
		var Channel = JSONChannel;
		// console.log(Channel);
		const type = "Reschedule";

		if (Channel.length > 0) {

			if ($.fn.DataTable.isDataTable('#tableaddbkbkirimulang')) {
				$('#tableaddbkbkirimulang').DataTable().destroy();
			}

			$('#tableaddbkbkirimulang tbody').empty();

			dataKirimUlangNew = [];

			for (i = 0; i < Channel.length; i++) {
				var principal = Channel[i].principal;
				var brand = Channel[i].brand;
				var sku_id = Channel[i].sku_id;
				var sku_kode = Channel[i].sku_kode;
				var sku_nama_produk = Channel[i].sku_nama_produk;
				var sku_kemasan = Channel[i].sku_kemasan;
				var sku_satuan = Channel[i].sku_satuan;
				var karyawan_nama = Channel[i].karyawan_nama;
				var picking_order_plan_id = Channel[i].picking_order_plan_id;
				var delivery_order_id = Channel[i].delivery_order_id;
				var sku_stock_id = Channel[i].sku_stock_id;
				var sku_stock_expired_date = Channel[i].sku_stock_expired_date;
				var sku_stock_qty_ambil = Channel[i].sku_stock_qty_ambil;
				var total_ambil = Channel[i].total_ambil;
				var sisa_stock = sku_stock_qty_ambil - Channel[i].total_ambil;
				var depo_detail_id = Channel[i].depo_detail_id;
				var depo_detail_nama = Channel[i].depo_detail_nama;
				var delivery_order_batch_kode = Channel[i].delivery_order_batch_kode;
				var delivery_order_batch_tanggal_kirim = Channel[i].delivery_order_batch_tanggal_kirim;
				var delivery_order_kode = Channel[i].delivery_order_kode;
				var karyawan_id = Channel[i].karyawan_id;
				var picking_order_id = Channel[i].picking_order_id;
				var picking_order_keterangan = Channel[i].picking_order_keterangan;
				var picking_order_kode = Channel[i].picking_order_kode;
				var picking_order_plan_nourut = Channel[i].picking_order_plan_nourut;
				var picking_order_plan_status = Channel[i].picking_order_plan_status;
				var picking_order_tanggal = Channel[i].picking_order_tanggal;
				var tipe_delivery_order_id = Channel[i].tipe_delivery_order_id;
				var tipe_delivery_order_alias = Channel[i].tipe_delivery_order_alias;

				let new_qty = "";

				console.log(qtyAktualSaveTemp);

				if (qtyAktualSaveTemp.length != 0) {
					const findData = qtyAktualSaveTemp.find((item) => (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type));
					if (typeof findData !== 'undefined') {
						new_qty += findData.value;
					} else {
						new_qty += "";
					}
				}

				let finalQty = new_qty != "" ? parseInt(Math.round(sisa_stock)) - parseInt(new_qty) : Math.round(sisa_stock);

				// const findDataChk = arrDataChkBKB.find((item) => (item.sku_id === sku_id) && (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type));
				// let chckedScan = "";
				// if (typeof findDataChk === 'undefined') {
				// 	arrDataChkBKB.push({
				// 		sku_id,
				// 		sku_kode,
				// 		expired_date: sku_stock_expired_date,
				// 		picking_order_plan_id,
				// 		type,
				// 		checked: false
				// 	})
				// 	chckedScan += false
				// } else {
				// 	chckedScan += findDataChk.checked
				// }

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtjml" value="' + Channel.length + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtdeliveryorderid' + i + '" value="' + delivery_order_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtpickingorderplanid' + i + '" value="' + picking_order_plan_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskuid' + i + '" value="' + sku_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtnamasku' + i + '" value="' + sku_nama_produk + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskustockid' + i + '" value="' + sku_stock_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="depoDetailId' + i + '" value="' + depo_detail_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="maxqty' + i + '" value="' + Math.round(sku_stock_qty_ambil) + '">';
				strmenu = strmenu + principal;
				strmenu = strmenu + '	</td>';
				strmenu = strmenu + '	<td>' + brand + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
				strmenu = strmenu + `	<td> ${finalQty}</td>`;
				strmenu = strmenu + `	<td><input type="number" class="form-control" id="txtqtyambil${i}" value="${(new_qty != "") && (total_ambil != null) ? parseInt(new_qty) + parseInt(total_ambil) : new_qty != "" ? new_qty : total_ambil != null ? total_ambil : 0}" disabled></td>`;
				strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" disabled><option value="' + sku_stock_id + ' || ' + sku_stock_expired_date + '">' + sku_stock_expired_date + '</option></select></td>';
				strmenu = strmenu + `	<td>
																		<button type="button" class="btn btn-primary" id="previewHandlerLocationRak${i}" onclick="previewHandlerLocationRak('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${finalQty}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')">${depo_detail_nama}</button>
																</td>`;
				strmenu = strmenu + '</tr>';


				dataKirimUlangNew.push({
					brand,
					delivery_order_batch_kode,
					delivery_order_batch_tanggal_kirim,
					delivery_order_id,
					delivery_order_kode,
					depo_detail_id,
					depo_detail_nama,
					karyawan_id,
					karyawan_nama,
					picking_order_id,
					picking_order_keterangan,
					picking_order_kode,
					picking_order_plan_id,
					picking_order_plan_nourut,
					picking_order_plan_status,
					picking_order_tanggal,
					principal,
					sku_id,
					sku_kemasan,
					sku_kode,
					sku_nama_produk,
					sku_satuan,
					sku_stock_expired_date,
					sku_stock_id,
					sku_stock_qty_ambil,
					tipe_delivery_order_id,
					tipe_delivery_order_alias,
					total_ambil: new_qty != "" ? parseInt(Math.round(sisa_stock)) - parseInt(new_qty) : Math.round(sisa_stock),
					actual_qty_ambil: parseInt((new_qty != "") && (total_ambil != null) ? parseInt(new_qty) + parseInt(total_ambil) : new_qty != "" ? new_qty : total_ambil != null ? total_ambil : 0)

				})

				$("#tableaddbkbkirimulang > tbody").append(strmenu);
			}

			$('#tableaddbkbkirimulang').DataTable({
				"lengthMenu": [
					[-1],
					['All']
				]
			});
		}
	}

	function ChPickingBKBCanvasDetailTable(JSONChannel) {

		$("#tableaddbkbcanvas > tbody").html('');

		$('#loadingaddbkbcanvas').hide();

		// var Channel = JSON.parse(JSONChannel);
		var Channel = JSONChannel;
		// console.log(Channel);
		const type = "Canvas";

		if (Channel.length > 0) {

			if ($.fn.DataTable.isDataTable('#tableaddbkbcanvas')) {
				$('#tableaddbkbcanvas').DataTable().destroy();
			}

			$('#tableaddbkbcanvas tbody').empty();

			dataCanvasNew = [];

			for (i = 0; i < Channel.length; i++) {
				var principal = Channel[i].principal;
				var brand = Channel[i].brand;
				var sku_id = Channel[i].sku_id;
				var sku_kode = Channel[i].sku_kode;
				var sku_nama_produk = Channel[i].sku_nama_produk;
				var sku_kemasan = Channel[i].sku_kemasan;
				var sku_satuan = Channel[i].sku_satuan;
				var karyawan_nama = Channel[i].karyawan_nama;
				var picking_order_plan_id = Channel[i].picking_order_plan_id;
				var delivery_order_id = Channel[i].delivery_order_id;
				var sku_stock_id = Channel[i].sku_stock_id;
				var sku_stock_expired_date = Channel[i].sku_stock_expired_date;
				var sku_stock_qty_ambil = Channel[i].sku_stock_qty_ambil;
				var total_ambil = Channel[i].total_ambil;
				var sisa_stock = sku_stock_qty_ambil - Channel[i].total_ambil;
				var depo_detail_id = Channel[i].depo_detail_id;
				var depo_detail_nama = Channel[i].depo_detail_nama;
				var delivery_order_batch_kode = Channel[i].delivery_order_batch_kode;
				var delivery_order_batch_tanggal_kirim = Channel[i].delivery_order_batch_tanggal_kirim;
				var delivery_order_kode = Channel[i].delivery_order_kode;
				var karyawan_id = Channel[i].karyawan_id;
				var picking_order_id = Channel[i].picking_order_id;
				var picking_order_keterangan = Channel[i].picking_order_keterangan;
				var picking_order_kode = Channel[i].picking_order_kode;
				var picking_order_plan_nourut = Channel[i].picking_order_plan_nourut;
				var picking_order_plan_status = Channel[i].picking_order_plan_status;
				var picking_order_tanggal = Channel[i].picking_order_tanggal;
				var tipe_delivery_order_id = Channel[i].tipe_delivery_order_id;
				var tipe_delivery_order_alias = Channel[i].tipe_delivery_order_alias;

				let new_qty = "";

				console.log(qtyAktualSaveTemp);

				if (qtyAktualSaveTemp.length != 0) {
					const findData = qtyAktualSaveTemp.find((item) => (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type));
					if (typeof findData !== 'undefined') {
						new_qty += findData.value;
					} else {
						new_qty += "";
					}
				}

				let finalQty = new_qty != "" ? parseInt(Math.round(sisa_stock)) - parseInt(new_qty) : Math.round(sisa_stock);

				const findDataChk = arrDataChkBKB.find((item) => (item.sku_id === sku_id) && (item.sku_kode === sku_kode) && (item.expired_date === sku_stock_expired_date) && (item.picking_order_plan_id === picking_order_plan_id) && (item.type === type));
				let chckedScan = "";
				if (typeof findDataChk === 'undefined') {
					arrDataChkBKB.push({
						sku_id,
						sku_kode,
						expired_date: sku_stock_expired_date,
						picking_order_plan_id,
						type,
						checked: false
					})
					chckedScan += false
				} else {
					chckedScan += findDataChk.checked
				}

				var strmenu = '';

				strmenu = strmenu + '<tr>';
				strmenu = strmenu + '	<td>';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtjml" value="' + Channel.length + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtdeliveryorderid' + i + '" value="' + delivery_order_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtpickingorderplanid' + i + '" value="' + picking_order_plan_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskuid' + i + '" value="' + sku_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtnamasku' + i + '" value="' + sku_nama_produk + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="txtskustockid' + i + '" value="' + sku_stock_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="depoDetailId' + i + '" value="' + depo_detail_id + '">';
				strmenu = strmenu + '		<input type="hidden" class="form-control" id="maxqty' + i + '" value="' + Math.round(sku_stock_qty_ambil) + '">';
				strmenu = strmenu + principal;
				strmenu = strmenu + '	</td>';
				strmenu = strmenu + '	<td>' + brand + '</td>';
				strmenu = strmenu + '	<td>' + sku_kode + '</td>';
				strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
				strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
				strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
				strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
				strmenu = strmenu + `	<td> ${finalQty}</td>`;
				strmenu = strmenu + `	<td><input type="number" class="form-control" id="txtqtyambil${i}" value="${(new_qty != "") && (total_ambil != null) ? parseInt(new_qty) + parseInt(total_ambil) : new_qty != "" ? new_qty : total_ambil != null ? total_ambil : 0}" disabled></td>`;
				strmenu = strmenu + '	<td><select id="slcskuexpireddate' + i + '" class="form-control" disabled><option value="' + sku_stock_id + ' || ' + sku_stock_expired_date + '">' + sku_stock_expired_date + '</option></select></td>';
				strmenu = strmenu + `	<td><input type="checkbox" style="transform:scale(1.5)" id="pilihManual${i}" ${chckedScan == "false" ? '' : 'checked'} onchange="handlerPilihManul(event, '${i}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}')"/></td>`;
				strmenu = strmenu + `	<td>
																<button type="button" class="btn btn-primary" id="previewHandlerLocationRak${i}" onclick="previewHandlerLocationRak('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${finalQty}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')" style="display: ${chckedScan == "false" ? 'block' : 'none'}">${depo_detail_nama}</button>

																<button type="button" class="btn btn-info" id="handlerPilihByScanPallet${i}" onclick="handlerPilihByScanPallet('${depo_detail_id}', '${Math.round(sku_stock_qty_ambil)}', '${finalQty}', '${sku_kode}', '${sku_stock_expired_date}', '${picking_order_plan_id}', '${type}', '${sku_id}', '${sku_nama_produk}')" style="display: ${chckedScan == "false" ? 'none' : 'block'}">Pilih</button>
														</td>`;
				strmenu = strmenu + '</tr>';


				dataCanvasNew.push({
					brand,
					delivery_order_batch_kode,
					delivery_order_batch_tanggal_kirim,
					delivery_order_id,
					delivery_order_kode,
					depo_detail_id,
					depo_detail_nama,
					karyawan_id,
					karyawan_nama,
					picking_order_id,
					picking_order_keterangan,
					picking_order_kode,
					picking_order_plan_id,
					picking_order_plan_nourut,
					picking_order_plan_status,
					picking_order_tanggal,
					principal,
					sku_id,
					sku_kemasan,
					sku_kode,
					sku_nama_produk,
					sku_satuan,
					sku_stock_expired_date,
					sku_stock_id,
					sku_stock_qty_ambil,
					tipe_delivery_order_id,
					tipe_delivery_order_alias,
					total_ambil: new_qty != "" ? parseInt(Math.round(sisa_stock)) - parseInt(new_qty) : Math.round(sisa_stock),
					actual_qty_ambil: parseInt((new_qty != "") && (total_ambil != null) ? parseInt(new_qty) + parseInt(total_ambil) : new_qty != "" ? new_qty : total_ambil != null ? total_ambil : 0),
					chckedScan

				})

				$("#tableaddbkbcanvas > tbody").append(strmenu);
			}

			$('#tableaddbkbcanvas').DataTable({
				"lengthMenu": [
					[-1],
					['All']
				]
			});
		}
	}

	const fullHandler = () => {
		var elem = document.documentElement;
		if (elem.requestFullscreen) {
			elem.requestFullscreen();
		} else if (elem.webkitRequestFullscreen) {
			/* Safari */
			elem.webkitRequestFullscreen();
		} else if (elem.msRequestFullscreen) {
			/* IE11 */
			elem.msRequestFullscreen();
		}
	}

	const exitHandler = () => {
		var elem = document.documentElement;
		if (document.exitFullscreen) {
			document.exitFullscreen();
		} else if (document.webkitExitFullscreen) {
			/* Safari */
			document.webkitExitFullscreen();
		} else if (document.msExitFullscreen) {
			/* IE11 */
			document.msExitFullscreen();
		}
	}

	document.getElementById('nama_rak').addEventListener('keyup', function() {

		const dataku = $(this).attr('data-handler');
		const requestReplace = dataku.replaceAll("\'", '"')
		const requestReplace2 = requestReplace.replaceAll(/\\/g, '')


		const dataHandler = JSON.parse($(this).attr('data-handler'));
		const depo_detail_id = dataHandler.depo_detail_id;
		const nama_rak = dataHandler.nama_rak;
		const rak_lajur_detail_id = dataHandler.rak_lajur_detail_id;
		const increment = dataHandler.increment;
		const qty_ambil = dataHandler.qty_ambil;
		const qty_sisa = dataHandler.qty_sisa;
		const sku_kode = dataHandler.sku_kode;
		const expired_date = dataHandler.expired_date;
		const picking_order_plan_id = dataHandler.picking_order_plan_id;
		const type = dataHandler.type;
		const typeScan = dataHandler.typeScan;
		const palletId = dataHandler.palletId;

		if (this.value != "") {
			fetch('<?= base_url('WMS/Distribusi/PengeluaranBarang/getKodeAutoComplete?params='); ?>' + this.value + `&type=${typeScan}`)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						document.getElementById('table-fixed').style.display = 'none';
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
												<tr onclick="getNoSuratJalanEks(event,'${e.kode}', '${requestReplace2.replace(/"/g, '\\\'')}')" style="cursor:pointer">
														<td class="col-xs-1">${i + 1}.</td>
														<td class="col-xs-11">${e.kode}</td>
												</tr>
												`;
						})

						document.getElementById('konten-table').innerHTML = data;
						// console.log(data);
						document.getElementById('table-fixed').style.display = 'block';
					}
				});
		} else {
			document.getElementById('table-fixed').style.display = 'none';
		}
	});

	const getNoSuratJalanEks = (event, data, dataRequest) => {


		const requestReplace = dataRequest.replaceAll("\'", '"')
		const requestReplace2 = requestReplace.replaceAll(/\\/g, '')
		const dataFix = JSON.parse(requestReplace2);

		$("#nama_rak").val(data);
		document.getElementById('table-fixed').style.display = 'none'

		requestCheckScanKode(dataFix, data)
	}

	$("#btnsaveaddbkbbulk").click(
		function() {

			var picking_order_aktual_h_id = $("#txtaddidbkbbulk").val();
			var picking_order_aktual_kode = $("#txtaddnobkbbulk").val();
			var picking_order_id = $("#txtpickingorderid").val();
			var picking_order_kode = $("#txtaddnoppbbulk").val();
			var karyawan_id = $("#slcaddcheckerbkbbulk option:selected").val();

			const countData = $("#tableaddbkbbulk > tbody tr").length;

			// const lastUpdated = $("#lastUpdated").val();

			const type = 'Bulk';

			const FixedQtyAktualTempArr = qtyAktualTempArr.filter((item) => item.type == type);

			const indexQtyAktualTempArr = FixedQtyAktualTempArr.map((item, index) => index);

			const indexQtyAktualSaveTemp = qtyAktualSaveTemp.map((value, idx) => {
				if (value.type == type) {
					return idx
				}
			});

			// var jumlah = $("#txtjml").val();
			var jumlah = dataBulkNew.length;

			var count_check = 0;

			var total_ambil = 0;

			var counter2 = 0;

			if (countData === 0) return message("Error!", 'data di table tidak boleh kosong, checklist untuk cek data', 'error');

			// $("#loadingaddbkbbulk").show();
			$("#btnsaveaddbkbbulk").prop("disabled", true);

			showLoading(true, "#btnsaveaddbkbbulk");

			if (karyawan_id != "") {
				dataBulkNew.map((value, index) => {
					const picking_order_plan_id = value.picking_order_plan_id;
					const delivery_order_id = value.delivery_order_id;
					const sku_id = value.sku_id;
					const sku_nama_produk = value.sku_nama_produk;
					const sku_stock_id = value.sku_stock_id;
					const sku_stock_expired_date = value.sku_stock_expired_date;
					const sku_stock_qty_ambil = value.actual_qty_ambil;

					var checkIstake = $.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/CheckIsTakePickingOrderPlan') ?>",
						data: {
							picking_order_plan_id: picking_order_plan_id
						},
						dataType: 'json',
						global: false,
						async: false,
						success: function(data) {
							return data.ChkIsTake;
						}
					}).responseText;

					total_ambil += parseInt(sku_stock_qty_ambil);
					var maxqty = $.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/CheckQtyAmbil') ?>",
						data: {
							sku_stock_id: sku_stock_id,
						},
						dataType: 'json',
						global: false,
						async: false,
						success: function(data) {
							return data.CheckQty;
						}
					}).responseText;

					if (parseInt(sku_stock_qty_ambil) > parseInt(Math.round(maxqty))) {

						new PNotify
							({
								title: 'Error',
								text: 'Jumlah Qty Ambil ' + sku_nama_produk + ' ED ' + sku_stock_expired_date + ' Melebehi Stock!',
								type: 'error',
								styling: 'bootstrap3',
								delay: 3000,
								stack: stack_center
							});


					} else {
						total_ambil += parseInt(sku_stock_qty_ambil);
						count_check++;
					}
				})

				if (jumlah == count_check) {
					if (total_ambil > 0) {

						requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/SaveAddNewPickingOrderAktual_H'); ?>", {
							picking_order_aktual_h_id: picking_order_aktual_h_id,
							picking_order_aktual_kode: picking_order_aktual_kode,
							picking_order_id: picking_order_id,
							picking_order_kode: picking_order_kode,
							karyawan_id: karyawan_id,
							// lastUpdated
						}, "POST", "JSON", function(response) {
							if (response.status === 200) {
								// $("#lastUpdated").val(response.lastUpdatedNew);
								let dataPickingOrderAktualD = [];
								var counter = 0;

								dataBulkNew.map((value, index) => {
									const picking_order_plan_id = value.picking_order_plan_id;
									const delivery_order_id = value.delivery_order_id;
									const sku_id = value.sku_id;
									const sku_stock_id = value.sku_stock_id;
									const sku_stock_expired_date = `${value.sku_stock_id} || ${value.sku_stock_expired_date}`;
									const sku_stock_qty_ambil = value.actual_qty_ambil;
									const depo_detail_id = value.depo_detail_id;
									const maxqty = value.sku_stock_qty_ambil;

									if (sku_stock_qty_ambil > 0) {
										// no_urut += 1;

										dataPickingOrderAktualD.push({
											picking_order_plan_id,
											delivery_order_id,
											sku_id,
											sku_stock_id,
											sku_stock_expired_date,
											sku_stock_qty_ambil,
											picking_order_id,
											tipe_do: "Bulk",
											// no_urut,
											depo_detail_id,
											maxqty
										});
									}
									counter++;
									counter2++;
								});

								requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/SaveAddNewPickingOrderAktual_D'); ?>", {
									data: dataPickingOrderAktualD,
									picking_order_aktual_h_id,
									qtyAktualTempArr: FixedQtyAktualTempArr,
									arrScanPilihDetail,
									arrScanPilihDetail2
								}, "POST", "JSON", function(response) {
									if (response.status === 400) {
										message("Error!", response.message, 'error')
										return false;
									}
									// if (response == 1) {

									// } else {
									// 	if (response == 2) {
									// 		var msg = 'Qty Melebihi Batas!';
									// 	} else {
									// 		var msg = response;
									// 	}
									// 	var msgtype = 'error';

									// 	//if (!window.__cfRLUnblockHandlers) return false;
									// 	new PNotify
									// 		({
									// 			title: 'Error',
									// 			text: msg,
									// 			type: msgtype,
									// 			styling: 'bootstrap3',
									// 			delay: 3000,
									// 			stack: stack_center
									// 		});
									// }
								})

								if (jumlah == counter) {

									message_topright("success", "<span>BKB Berhasil Ditambah</span>")

									ResetForm();
									$("#previewaddformbkbbulk").modal('hide');
									$("#previewformbkbbulk").modal('show');
									var picking_order_kode = $("#txtnoppb").val();

									scanPalletArr = [];
									for (let i = indexQtyAktualTempArr.length - 1; i >= 0; i--) {
										qtyAktualTempArr.splice(indexQtyAktualTempArr[i], 1)
									}
									for (let i = indexQtyAktualSaveTemp.length - 1; i >= 0; i--) {
										qtyAktualSaveTemp.splice(indexQtyAktualSaveTemp[i], 1)
									}

									dataBulkGlobal = [];
									arrScanPilihDetail = [];
									arrScanPilihDetail2 = [];
									arrScanPallet = []

									$.ajax({
										type: 'POST',
										url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDaftarBKB') ?>",
										data: {
											picking_order_id: picking_order_id,
											tipe_bkb: 'Bulk'
										},
										success: function(response) {
											if (response) {
												ChDaftarBKBMenu(response);
											}
										}
									});
								} else {
									if (response == 2) {
										var msg = 'Kode BKB sudah ada';
									} else {
										var msg = response;
									}
									var msgtype = 'error';

									//if (!window.__cfRLUnblockHandlers) return false;
									new PNotify
										({
											title: 'Error',
											text: msg,
											type: msgtype,
											styling: 'bootstrap3',
											delay: 3000,
											stack: stack_center
										});
								}
							}

							if (response.status === 401) return message_topright("error", response.message);
							// if (response.status === 400) return messageNotSameLastUpdated()
						}, "#btnsaveaddbkbbulk")

					} else {
						message("Error!", "<span>BKB Gagal Dibuat! \n Stock Ambil 0 Semua</span>", "error")
						$("#btnsaveaddbkbbulk").prop("disabled", true);
						return false;

					}

				} else {

					message("Error!", "<span>BKB Gagal Dibuat!</span>", "error")
					$("#btnsaveaddbkbbulk").prop("disabled", true);
					return false;

				}
			} else {

				message("Error!", "<span>Checker Kosong</span>", "error")
				$("#btnsaveaddbkbbulk").prop("disabled", true);
				return false;

			}

			showLoading(false, "#btnsaveaddbkbbulk", 10);
		}
	);

	$("#btnsaveaddbkbstandar").click(
		function() {
			var picking_order_aktual_h_id = $("#txtaddidbkbstandar").val();
			var picking_order_aktual_kode = $("#txtaddnobkbstandar").val();
			var picking_order_id = $("#txtpickingorderid").val();
			var picking_order_kode = $("#txtaddnoppbstandar").val();
			var karyawan_id = $("#slcaddcheckerbkbstandar option:selected").val();

			const countData = $("#tableaddbkbstandar > tbody tr").length;

			// const lastUpdated = $("#lastUpdated").val();

			const type = 'Standar';

			const FixedQtyAktualTempArr = qtyAktualTempArr.filter((item) => item.type == type);
			const indexQtyAktualTempArr = FixedQtyAktualTempArr.map((value, index) => index)
			const indexQtyAktualSaveTemp = qtyAktualSaveTemp.filter((item, index) => {
				if (item.type == type) {
					return index
				}
			})

			var jumlah = $("#txtjml").val();

			var count_check = 0;

			var total_ambil = 0;

			var counter2 = 0;

			if (countData === 0) return message("Error!", 'data di table tidak boleh kosong, checklist untuk cek data', 'error');

			$("#btnsaveaddbkbstandar").prop("disabled", true);

			showLoading(true, "#btnsaveaddbkbstandar");

			if (karyawan_id != "") {
				for (var x = 0; x < jumlah; x++) {
					var picking_order_plan_id = $("#txtpickingorderplanid" + x).val();
					var delivery_order_id = $("#txtdeliveryorderid" + x).val();
					var sku_id = $("#txtskuid" + x).val();
					var sku_nama_produk = $("#txtnamasku" + x).val();
					var sku_stock_id = $("#txtskustockid" + x).val();
					var sku_stock_expired_date = $("#slcskuexpireddate" + x).val();
					var sku_stock_qty_ambil = $("#txtqtyambil" + x).val();
					// var depo_detail_id = $("#depoDetailId" + x).val();
					// var maxQty = $("#maxqty" + x).val();
					const sku_stock_arr = sku_stock_expired_date.split(" || ");

					//get is_take picking_order_plan
					var checkIstake = $.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/CheckIsTakePickingOrderPlan') ?>",
						data: {
							picking_order_plan_id: picking_order_plan_id
						},
						dataType: 'json',
						global: false,
						async: false,
						success: function(data) {
							return data.ChkIsTake;
						}
					}).responseText;

					if (sku_stock_qty_ambil != "") {
						// total_ambil += sku_stock_qty_ambil;
						var maxqty = $.ajax({
							type: 'POST',
							url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/CheckQtyAmbil') ?>",
							data: {
								sku_stock_id: sku_stock_arr[0],
							},
							dataType: 'json',
							global: false,
							async: false,
							success: function(data) {
								return data.CheckQty;
							}
						}).responseText;

						if (parseInt(sku_stock_qty_ambil) > parseInt(Math.round(maxqty))) {
							new PNotify
								({
									title: 'Error',
									text: 'Jumlah Qty Ambil ' + sku_nama_produk + ' ED ' + sku_stock_arr[1] + ' Melebihi Stock!',
									type: 'error',
									styling: 'bootstrap3',
									delay: 3000,
									stack: stack_center
								});

						} else {
							total_ambil += parseInt(sku_stock_qty_ambil);
							count_check++;
						}

					} else {
						new PNotify
							({
								title: 'Error',
								text: 'Qty ' + sku_nama_produk + ' ED ' + sku_stock_expired_date + ' Required!',
								type: 'error',
								styling: 'bootstrap3',
								delay: 3000,
								stack: stack_center
							});

					}
				}

				if (jumlah == count_check) {
					if (total_ambil > 0) {

						requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/SaveAddNewPickingOrderAktual_H'); ?>", {
							picking_order_aktual_h_id: picking_order_aktual_h_id,
							picking_order_aktual_kode: picking_order_aktual_kode,
							picking_order_id: picking_order_id,
							picking_order_kode: picking_order_kode,
							karyawan_id: karyawan_id,
							// lastUpdated
						}, "POST", "JSON", function(response) {
							if (response.status === 200) {
								// $("#lastUpdated").val(response.lastUpdatedNew);
								let dataPickingOrderAktualD = [];
								var counter = 0;
								for (var x = 0; x < jumlah; x++) {
									var picking_order_plan_id = $("#txtpickingorderplanid" + x).val();
									var delivery_order_id = $("#txtdeliveryorderid" + x).val();
									var sku_id = $("#txtskuid" + x).val();
									var sku_stock_id = $("#txtskustockid" + x).val();
									var sku_stock_expired_date = $("#slcskuexpireddate" + x).val();
									var sku_stock_qty_ambil = $("#txtqtyambil" + x).val();
									var depo_detail_id = $("#depoDetailId" + x).val();
									var maxqty = $("#maxqty" + x).val();


									if (sku_stock_qty_ambil > 0) {
										// no_urut += 1;
										dataPickingOrderAktualD.push({
											picking_order_plan_id,
											delivery_order_id,
											sku_id,
											sku_stock_id,
											sku_stock_expired_date,
											sku_stock_qty_ambil,
											picking_order_id,
											tipe_do: "Standar",
											// no_urut,
											depo_detail_id,
											maxqty
										});

									}
									counter++;
									counter2++;
								}

								requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/SaveAddNewPickingOrderAktual_D'); ?>", {
									data: dataPickingOrderAktualD,
									picking_order_aktual_h_id,
									qtyAktualTempArr: FixedQtyAktualTempArr,
									arrScanPilihDetail,
									arrScanPilihDetail2
								}, "POST", "JSON", function(response) {
									if (response.status === 400) {
										message("Error!", response.message, 'error')
										return false
									}
									// if (response.status === 200) {
									// 	if (response == 1) {

									// 	} else {
									// 		if (response == 2) {
									// 			var msg = 'Qty Melebihi Batas!';
									// 		} else {
									// 			var msg = response;
									// 		}
									// 		var msgtype = 'error';

									// 		//if (!window.__cfRLUnblockHandlers) return false;
									// 		new PNotify
									// 			({
									// 				title: 'Error',
									// 				text: msg,
									// 				type: msgtype,
									// 				styling: 'bootstrap3',
									// 				delay: 3000,
									// 				stack: stack_center
									// 			});
									// 	}
									// }
								})

								if (jumlah == counter) {
									message_topright("success", "<span>BKB Berhasil Ditambah</span>")

									ResetForm();
									$("#previewaddformbkbstandar").modal('hide');
									$("#previewformbkbstandar").modal('show');
									var picking_order_kode = $("#txtnoppb").val();

									// scanPalletArr = [];
									for (let i = indexQtyAktualTempArr.length - 1; i >= 0; i--) {
										qtyAktualTempArr.splice(indexQtyAktualTempArr[i], 1)
									}
									for (let i = indexQtyAktualSaveTemp.length - 1; i >= 0; i--) {
										qtyAktualSaveTemp.splice(indexQtyAktualSaveTemp[i], 1)
									}
									// qtyAktualTempArr = [];
									dataStandarGlobal = [];
									// qtyAktualSaveTemp = [];
									arrScanPilihDetail = [];
									arrScanPilihDetail2 = [];
									arrScanPallet = [];

									$.ajax({
										type: 'POST',
										url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDaftarBKB') ?>",
										data: {
											picking_order_id: picking_order_id,
											tipe_bkb: 'Standar'
										},
										success: function(response) {
											if (response) {
												ChDaftarBKBMenu(response);

											}
										}
									});
								} else {
									if (response == 2) {
										var msg = 'Kode BKB sudah ada';
									} else {
										var msg = response;
									}
									var msgtype = 'error';

									//if (!window.__cfRLUnblockHandlers) return false;
									new PNotify
										({
											title: 'Error',
											text: msg,
											type: msgtype,
											styling: 'bootstrap3',
											delay: 3000,
											stack: stack_center
										});
								}
							}

							if (response.status === 401) return message_topright("error", response.message);
							// if (response.status === 400) return messageNotSameLastUpdated()
						}, "#btnsaveaddbkbstandar")

					} else {
						message("Error!", "<span>BKB Gagal Dibuat! \n Stock Ambil 0 Semua</span>", "error")
						$("#btnsaveaddbkbstandar").prop("disabled", false);
						return false;

					}

				} else {
					message("Error!", "<span>BKB Gagal Dibuat!</span>", "error")
					$("#btnsaveaddbkbstandar").prop("disabled", false);
					return false;

				}
			} else {

				message("Error!", "<span>Checker Kosong</span>", "error")
				$("#btnsaveaddbkbstandar").prop("disabled", false);
				return false;

			}

			showLoading(false, "#btnsaveaddbkbstandar", 10);
		}
	);

	$("#btnsaveaddbkbkirimulang").click(
		function() {

			var picking_order_aktual_h_id = $("#txtaddidbkbkirimulang").val();
			var picking_order_aktual_kode = $("#txtaddnobkbkirimulang").val();
			var picking_order_id = $("#txtpickingorderid").val();
			var picking_order_kode = $("#txtaddnoppbkirimulang").val();
			var karyawan_id = $("#slcaddcheckerbkbkirimulang option:selected").val();

			const countData = $("#tableaddbkbkirimulang > tbody tr").length;

			// const lastUpdated = $("#lastUpdated").val();

			// var jumlah = $("#txtjml").val();
			const type = 'Reschedule';

			const FixedQtyAktualTempArr = qtyAktualTempArr.filter((item) => item.type == type);

			const indexQtyAktualTempArr = FixedQtyAktualTempArr.map((item, index) => index);

			const indexQtyAktualSaveTemp = qtyAktualSaveTemp.map((value, idx) => {
				if (value.type == type) {
					return idx
				}
			});

			var jumlah = dataKirimUlangNew.length;

			var count_check = 0;

			var total_ambil = 0;

			var counter2 = 0;

			if (countData === 0) return message("Error!", 'data di table tidak boleh kosong, checklist untuk cek data', 'error');

			// $("#loadingaddbkbkirimulang").show();
			$("#btnsaveaddbkbkirimulang").prop("disabled", true);

			showLoading(true, "#btnsaveaddbkbkirimulang");

			if (karyawan_id != "") {
				dataKirimUlangNew.map((value, index) => {
					const picking_order_plan_id = value.picking_order_plan_id;
					const delivery_order_id = value.delivery_order_id;
					const sku_id = value.sku_id;
					const sku_nama_produk = value.sku_nama_produk;
					const sku_stock_id = value.sku_stock_id;
					const sku_stock_expired_date = value.sku_stock_expired_date;
					const sku_stock_qty_ambil = value.actual_qty_ambil;

					var checkIstake = $.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/CheckIsTakePickingOrderPlan') ?>",
						data: {
							picking_order_plan_id: picking_order_plan_id
						},
						dataType: 'json',
						global: false,
						async: false,
						success: function(data) {
							return data.ChkIsTake;
						}
					}).responseText;

					total_ambil += parseInt(sku_stock_qty_ambil);
					var maxqty = $.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/CheckQtyAmbil') ?>",
						data: {
							sku_stock_id: sku_stock_id,
						},
						dataType: 'json',
						global: false,
						async: false,
						success: function(data) {
							return data.CheckQty;
						}
					}).responseText;

					if (parseInt(sku_stock_qty_ambil) > parseInt(Math.round(maxqty))) {

						new PNotify
							({
								title: 'Error',
								text: 'Jumlah Qty Ambil ' + sku_nama_produk + ' ED ' + sku_stock_expired_date + ' Melebehi Stock!',
								type: 'error',
								styling: 'bootstrap3',
								delay: 3000,
								stack: stack_center
							});


					} else {
						total_ambil += parseInt(sku_stock_qty_ambil);
						count_check++;
					}
				})

				if (jumlah == count_check) {
					if (total_ambil > 0) {

						requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/SaveAddNewPickingOrderAktual_H'); ?>", {
							picking_order_aktual_h_id: picking_order_aktual_h_id,
							picking_order_aktual_kode: picking_order_aktual_kode,
							picking_order_id: picking_order_id,
							picking_order_kode: picking_order_kode,
							karyawan_id: karyawan_id,
							// lastUpdated
						}, "POST", "JSON", function(response) {
							if (response.status === 200) {
								// $("#lastUpdated").val(response.lastUpdatedNew);
								let dataPickingOrderAktualD = [];
								var counter = 0;

								dataKirimUlangNew.map((value, index) => {
									const picking_order_plan_id = value.picking_order_plan_id;
									const delivery_order_id = value.delivery_order_id;
									const sku_id = value.sku_id;
									const sku_stock_id = value.sku_stock_id;
									const sku_stock_expired_date = `${value.sku_stock_id} || ${value.sku_stock_expired_date}`;
									const sku_stock_qty_ambil = value.actual_qty_ambil;
									const depo_detail_id = value.depo_detail_id;
									const maxqty = value.sku_stock_qty_ambil;

									if (sku_stock_qty_ambil > 0) {
										// no_urut += 1;

										dataPickingOrderAktualD.push({
											picking_order_plan_id,
											delivery_order_id,
											sku_id,
											sku_stock_id,
											sku_stock_expired_date,
											sku_stock_qty_ambil,
											picking_order_id,
											tipe_do: "Reschedule",
											// no_urut,
											depo_detail_id,
											maxqty
										});
									}
									counter++;
									counter2++;
								});

								requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/SaveAddNewPickingOrderAktual_D'); ?>", {
									data: dataPickingOrderAktualD,
									picking_order_aktual_h_id,
									qtyAktualTempArr: FixedQtyAktualTempArr,
									arrScanPilihDetail: [],
									arrScanPilihDetail2: []
								}, "POST", "JSON", function(response) {
									if (response.status === 400) {
										message("Error!", response.message, 'error')
										return false;
									}
									// if (response == 1) {

									// } else {
									// 	if (response == 2) {
									// 		var msg = 'Qty Melebihi Batas!';
									// 	} else {
									// 		var msg = response;
									// 	}
									// 	var msgtype = 'error';

									// 	//if (!window.__cfRLUnblockHandlers) return false;
									// 	new PNotify
									// 		({
									// 			title: 'Error',
									// 			text: msg,
									// 			type: msgtype,
									// 			styling: 'bootstrap3',
									// 			delay: 3000,
									// 			stack: stack_center
									// 		});
									// }
								})

								if (jumlah == counter) {

									message_topright("success", "<span>BKB Berhasil Ditambah</span>")

									ResetForm();
									$("#previewaddformbkbkirimulang").modal('hide');
									$("#previewformbkbkirimulang").modal('show');
									var picking_order_kode = $("#txtnoppb").val();

									// scanPalletArr = [];
									for (let i = indexQtyAktualTempArr.length - 1; i >= 0; i--) {
										qtyAktualTempArr.splice(indexQtyAktualTempArr[i], 1)
									}
									for (let i = indexQtyAktualSaveTemp.length - 1; i >= 0; i--) {
										qtyAktualSaveTemp.splice(indexQtyAktualSaveTemp[i], 1)
									}
									dataKirimUlangGlobal = [];

									$.ajax({
										type: 'POST',
										url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDaftarBKB') ?>",
										data: {
											picking_order_id: picking_order_id,
											tipe_bkb: 'Reschedule'
										},
										success: function(response) {
											if (response) {
												ChDaftarBKBMenu(response);
											}
										}
									});
								} else {
									if (response == 2) {
										var msg = 'Kode BKB sudah ada';
									} else {
										var msg = response;
									}
									var msgtype = 'error';

									//if (!window.__cfRLUnblockHandlers) return false;
									new PNotify
										({
											title: 'Error',
											text: msg,
											type: msgtype,
											styling: 'bootstrap3',
											delay: 3000,
											stack: stack_center
										});
								}
							}

							if (response.status === 401) return message_topright("error", response.message);
							// if (response.status === 400) return messageNotSameLastUpdated()
						}, "#btnsaveaddbkbkirimulang")


					} else {

						message("Error!", "<span>BKB Gagal Dibuat! \n Stock Ambil 0 Semua</span>", "error")
						$("#btnsaveaddbkbkirimulang").prop("disabled", false);
						return false;

					}

				} else {
					message("Error!", "<span>BKB Gagal Dibuat!</span>", "error")
					$("#btnsaveaddbkbkirimulang").prop("disabled", false);
					return false;

				}
			} else {
				message("Error!", "<span>Checker Kosong</span>", "error")
				$("#btnsaveaddbkbkirimulang").prop("disabled", false);
				return false;

			}

			showLoading(false, "#btnsaveaddbkbkirimulang", 10);

			// if ($("#slcremainderchk").is(":checked")) {
			// 	 message("Info!", 'harap unchecked sisa ambil terlebih dahulu', 'info')
			// 	 return false;
			// } else {

			// }


		}
	);

	$("#btnsaveaddbkbcanvas").click(
		function() {

			var picking_order_aktual_h_id = $("#txtaddidbkbcanvas").val();
			var picking_order_aktual_kode = $("#txtaddnobkbcanvas").val();
			var picking_order_id = $("#txtpickingorderid").val();
			var picking_order_kode = $("#txtaddnoppbcanvas").val();
			var karyawan_id = $("#slcaddcheckerbkbcanvas option:selected").val();

			const countData = $("#tableaddbkbcanvas > tbody tr").length;

			// const lastUpdated = $("#lastUpdated").val();

			const type = 'Canvas';

			const FixedQtyAktualTempArr = qtyAktualTempArr.filter((item) => item.type == type);

			const indexQtyAktualTempArr = FixedQtyAktualTempArr.map((item, index) => index);

			const indexQtyAktualSaveTemp = qtyAktualSaveTemp.map((value, idx) => {
				if (value.type == type) {
					return idx
				}
			});

			// var jumlah = $("#txtjml").val();
			var jumlah = dataCanvasNew.length;

			var count_check = 0;

			var total_ambil = 0;

			var counter2 = 0;

			if (countData === 0) return message("Error!", 'data di table tidak boleh kosong, checklist untuk cek data', 'error');

			// $("#loadingaddbkbbulk").show();
			$("#btnsaveaddbkbcanvas").prop("disabled", true);

			showLoading(true, "#btnsaveaddbkbcanvas");

			if (karyawan_id != "") {
				dataCanvasNew.map((value, index) => {
					const picking_order_plan_id = value.picking_order_plan_id;
					const delivery_order_id = value.delivery_order_id;
					const sku_id = value.sku_id;
					const sku_nama_produk = value.sku_nama_produk;
					const sku_stock_id = value.sku_stock_id;
					const sku_stock_expired_date = value.sku_stock_expired_date;
					const sku_stock_qty_ambil = value.actual_qty_ambil;

					var checkIstake = $.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/CheckIsTakePickingOrderPlan') ?>",
						data: {
							picking_order_plan_id: picking_order_plan_id
						},
						dataType: 'json',
						global: false,
						async: false,
						success: function(data) {
							return data.ChkIsTake;
						}
					}).responseText;

					total_ambil += parseInt(sku_stock_qty_ambil);
					var maxqty = $.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/CheckQtyAmbil') ?>",
						data: {
							sku_stock_id: sku_stock_id,
						},
						dataType: 'json',
						global: false,
						async: false,
						success: function(data) {
							return data.CheckQty;
						}
					}).responseText;

					if (parseInt(sku_stock_qty_ambil) > parseInt(Math.round(maxqty))) {

						new PNotify
							({
								title: 'Error',
								text: 'Jumlah Qty Ambil ' + sku_nama_produk + ' ED ' + sku_stock_expired_date + ' Melebehi Stock!',
								type: 'error',
								styling: 'bootstrap3',
								delay: 3000,
								stack: stack_center
							});


					} else {
						total_ambil += parseInt(sku_stock_qty_ambil);
						count_check++;
					}
				})

				if (jumlah == count_check) {
					if (total_ambil > 0) {

						requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/SaveAddNewPickingOrderAktual_H'); ?>", {
							picking_order_aktual_h_id: picking_order_aktual_h_id,
							picking_order_aktual_kode: picking_order_aktual_kode,
							picking_order_id: picking_order_id,
							picking_order_kode: picking_order_kode,
							karyawan_id: karyawan_id,
							// lastUpdated
						}, "POST", "JSON", function(response) {
							if (response.status === 200) {
								// $("#lastUpdated").val(response.lastUpdatedNew);
								let dataPickingOrderAktualD = [];
								var counter = 0;

								dataCanvasNew.map((value, index) => {
									const picking_order_plan_id = value.picking_order_plan_id;
									const delivery_order_id = value.delivery_order_id;
									const sku_id = value.sku_id;
									const sku_stock_id = value.sku_stock_id;
									const sku_stock_expired_date = `${value.sku_stock_id} || ${value.sku_stock_expired_date}`;
									const sku_stock_qty_ambil = value.actual_qty_ambil;
									const depo_detail_id = value.depo_detail_id;
									const maxqty = value.sku_stock_qty_ambil;

									if (sku_stock_qty_ambil > 0) {
										// no_urut += 1;

										dataPickingOrderAktualD.push({
											picking_order_plan_id,
											delivery_order_id,
											sku_id,
											sku_stock_id,
											sku_stock_expired_date,
											sku_stock_qty_ambil,
											picking_order_id,
											tipe_do: "Bulk",
											// no_urut,
											depo_detail_id,
											maxqty
										});
									}
									counter++;
									counter2++;
								});

								requestAjax("<?= base_url('WMS/Distribusi/PengeluaranBarang/SaveAddNewPickingOrderAktual_D'); ?>", {
									data: dataPickingOrderAktualD,
									picking_order_aktual_h_id,
									qtyAktualTempArr: FixedQtyAktualTempArr,
									arrScanPilihDetail,
									arrScanPilihDetail2
								}, "POST", "JSON", function(response) {
									if (response.status === 400) {
										message("Error!", response.message, 'error')
										return false;
									}
								})

								if (jumlah == counter) {

									message_topright("success", "<span>BKB Berhasil Ditambah</span>")

									ResetForm();
									$("#previewaddformbkbcanvas").modal('hide');
									$("#previewformbkbcanvas").modal('show');
									var picking_order_kode = $("#txtnoppb").val();

									scanPalletArr = [];
									for (let i = indexQtyAktualTempArr.length - 1; i >= 0; i--) {
										qtyAktualTempArr.splice(indexQtyAktualTempArr[i], 1)
									}
									for (let i = indexQtyAktualSaveTemp.length - 1; i >= 0; i--) {
										qtyAktualSaveTemp.splice(indexQtyAktualSaveTemp[i], 1)
									}

									dataCanvasGlobal = [];
									arrScanPilihDetail = [];
									arrScanPilihDetail2 = [];
									arrScanPallet = []

									$.ajax({
										type: 'POST',
										url: "<?= base_url('WMS/Distribusi/PengeluaranBarang/GetDaftarBKB') ?>",
										data: {
											picking_order_id: picking_order_id,
											tipe_bkb: 'Canvas'
										},
										success: function(response) {
											if (response) {
												ChDaftarBKBMenu(response);
											}
										}
									});
								} else {
									if (response == 2) {
										var msg = 'Kode BKB sudah ada';
									} else {
										var msg = response;
									}
									var msgtype = 'error';

									//if (!window.__cfRLUnblockHandlers) return false;
									new PNotify
										({
											title: 'Error',
											text: msg,
											type: msgtype,
											styling: 'bootstrap3',
											delay: 3000,
											stack: stack_center
										});
								}
							}

							if (response.status === 401) return message_topright("error", response.message);
							// if (response.status === 400) return messageNotSameLastUpdated()
						}, "#btnsaveaddbkbcanvas")

					} else {
						message("Error!", "<span>BKB Gagal Dibuat! \n Stock Ambil 0 Semua</span>", "error")
						$("#btnsaveaddbkbcanvas").prop("disabled", true);
						return false;

					}

				} else {

					message("Error!", "<span>BKB Gagal Dibuat!</span>", "error")
					$("#btnsaveaddbkbcanvas").prop("disabled", true);
					return false;

				}
			} else {

				message("Error!", "<span>Checker Kosong</span>", "error")
				$("#btnsaveaddbkbcanvas").prop("disabled", true);
				return false;

			}

			showLoading(false, "#btnsaveaddbkbcanvas", 10);
		}
	);

	function CetakBKBById(picking_order_aktual_h_id) {

		Swal.fire({
			title: '',
			icon: '',
			html: '<strong>Pilih Cetak BKB Berdasarkan</strong>',
			showCancelButton: true,
			showCloseButton: true,
			confirmButtonText: 'Per Principle',
			cancelButtonText: 'All Principle',
			confirmButtonColor: '#2c3e50',
			cancelButtonColor: '#2c3e50'
		}).then((result) => {
			if (result.value) {
				window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulkAktual/?picking_order_aktual_h_id=" + picking_order_aktual_h_id + "&tipe=aktual&cetak=perprinciple", "_blank");
			} else if (result.dismiss == "cancel") {
				window.open("<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/CetakBKBBulkAktual/?picking_order_aktual_h_id=" + picking_order_aktual_h_id + "&tipe=aktual&cetak=allprinciple", "_blank");
			}
		});
	}
</script>