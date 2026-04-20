<script>
	$(document).ready(function() {
		document.getElementById('table-fixed').style.display = 'none';
	});

	$("#search_kode_ppb").on('click', function() {
		if ($("#txtnoppb").val() != "") {
			$.ajax({
				url: '<?= base_url('WMS/Maintenance/HapusPickingOrder2/getKodeAutoComplete?params='); ?>' + $("#txtnoppb").val(),
				method: 'GET',
				dataType: 'json',
				beforeSend: function() {
					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false,
						allowOutsideClick: false // Menonaktifkan klik di luar SweetAlert
					});
				},
				success: function(results) {
					if (!results[0]) {
						$('#table-fixed').hide();
						message("Error!", "Data tidak ditemukan!", "error");

						$("#panelbkbbulk").hide();
						$("#panelbkbstandar").hide();
						$("#panelbkbkirimulang").hide();
						$("#panelbkbcanvas").hide();
						$(".fieldFormChecker").hide();

						$("#txtidppb").val('');
						$("#txtnoppb2").val('');
						$("#txtketerangan").val('');
						$("#txtpickinglistid").val('');
						$("#txtdepoid").val('');
						$("#txtnofdjr").val('');
						$("#dateppb").val('');
						$("#driverppb").val('');
						$("#slcgudang").html('');
						$("#slcnopb").html('');
						$("#slctipepb").html('');
						$("#slcstatusppb").html('');
						$("#slccheckerheaderpb").html('');

						message("Error!", "Data tidak ditemukan!", "error");
						return false;
						Swal.close();
					} else {
						let data = "";
						results.forEach(function(e, i) {
							data += `
								<tr onclick="GetPengeluaranBarangManualMenu('${e.kode}')" style="cursor:pointer">
									<td class="col-xs-1">${i + 1}.</td>
									<td class="col-xs-11">${e.kode}</td>
								</tr>
							`;
						});
						$('#konten-table').html(data);
						$('#table-fixed').show();

						Swal.close();
					}
				},
				error: function(xhr, status, error) {
					console.error("Error fetching data:", error);
					message("Error!", "Terjadi kesalahan saat mengambil data.", "error");
				},
			});
		} else {
			document.getElementById('table-fixed').style.display = 'none';

			$("#panelbkbbulk").hide();
			$("#panelbkbstandar").hide();
			$("#panelbkbkirimulang").hide();
			$("#panelbkbcanvas").hide();
			$(".fieldFormChecker").hide();

			$("#txtidppb").val('');
			$("#txtnoppb2").val('');
			$("#txtketerangan").val('');
			$("#txtpickinglistid").val('');
			$("#txtdepoid").val('');
			$("#txtnofdjr").val('');
			$("#dateppb").val('');
			$("#driverppb").val('');
			$("#slcgudang").html('');
			$("#slcnopb").html('');
			$("#slctipepb").html('');
			$("#slcstatusppb").html('');
			$("#slccheckerheaderpb").html('');

			message("Error!", "Data tidak ditemukan!", "error");
			return false;
			Swal.close();
		}
	})

	function GetPengeluaranBarangManualMenu(value) {
		document.getElementById('table-fixed').style.display = 'none'
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Maintenance/HapusPickingOrder2/GetDetailPengeluaranBarangManualMenu') ?>",
			data: "picking_order_kode=" + value,
			beforeSend: function() {
				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false,
					allowOutsideClick: false // Menonaktifkan klik di luar SweetAlert
				});
			},
			success: function(response) {
				var cek = JSON.parse(response);

				if (cek.PengeluaranBarangManual.length > 0) {
					ChPengeluaranBarangManualMenu(response);
				} else {
					$("#panelbkbbulk").hide();
					$("#panelbkbstandar").hide();
					$("#panelbkbkirimulang").hide();
					$("#panelbkbcanvas").hide();
					$(".fieldFormChecker").hide();

					$("#txtidppb").val('');
					$("#txtnoppb2").val('');
					$("#txtketerangan").val('');
					$("#txtpickinglistid").val('');
					$("#txtdepoid").val('');
					$("#txtnofdjr").val('');
					$("#dateppb").val('');
					$("#driverppb").val('');
					$("#slcgudang").html('');
					$("#slcnopb").html('');
					$("#slctipepb").html('');
					$("#slcstatusppb").html('');
					$("#slccheckerheaderpb").html('');

					message("Error!", "Data tidak ditemukan!", "error");
					return false;
					Swal.close();

				}
			}
		});
	}

	function ChPengeluaranBarangManualMenu(JSONChannel) {
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

		if (Channel.PengeluaranBarangManual != 0) {
			var picking_order_id = Channel.PengeluaranBarangManual[0].picking_order_id;
			var picking_order_kode = Channel.PengeluaranBarangManual[0].picking_order_kode;
			var picking_list_id = Channel.PengeluaranBarangManual[0].picking_list_id;
			var depo_id = Channel.PengeluaranBarangManual[0].depo_id;
			var depo_detail_id = Channel.PengeluaranBarangManual[0].depo_detail_id;
			var depo_detail_nama = Channel.PengeluaranBarangManual[0].depo_detail_nama;
			var picking_list_kode = Channel.PengeluaranBarangManual[0].picking_list_kode;
			var delivery_order_batch_kode = Channel.PengeluaranBarangManual[0].delivery_order_batch_kode;
			var picking_list_tipe = Channel.PengeluaranBarangManual[0].picking_list_tipe;
			var picking_order_plan_tipe = Channel.PengeluaranBarangManual[0].picking_order_plan_tipe;
			var picking_order_tanggal = Channel.PengeluaranBarangManual[0].picking_order_tanggal;
			var picking_order_status = Channel.PengeluaranBarangManual[0].picking_order_status;
			var picking_order_keterangan = Channel.PengeluaranBarangManual[0].picking_order_keterangan;
			var driver = Channel.PengeluaranBarangManual[0].driver;
			var tipe_delivery_order_nama = Channel.PengeluaranBarangManual[0].tipe_delivery_order_nama;
			// var lastUpdated = Channel.PengeluaranBarangManual[0].picking_order_tgl_update;

			var karyawan_id = Channel.PengeluaranBarangManual[0].karyawan_id;
			var karyawan_nama = Channel.PengeluaranBarangManual[0].karyawan_nama;

			// console.log('awd', {
			//     karyawan_id,
			//     karyawan_nama
			// });

			var NoPPB = Channel.NoPPB;

			$("#txtidppb").val(picking_order_id);
			$("#txtnoppb2").val(picking_order_kode);
			$("#txtketerangan").val(picking_order_keterangan);
			$("#txtpickinglistid").val(picking_list_id);
			$("#txtdepoid").val(depo_id);
			$("#txtnofdjr").val(delivery_order_batch_kode);
			$("#dateppb").val(picking_order_tanggal);
			$("#driverppb").val(driver);
			$("#slcgudang").append('<option value="' + depo_detail_id + '">' + depo_detail_nama + '</option>');
			$("#slcnopb").append('<option value="' + picking_list_kode + '">' + picking_list_kode + '</option>');
			$("#slctipepb").append('<option value="' + picking_order_plan_tipe + '">' + picking_order_plan_tipe +
				'</option>');
			$("#slcstatusppb").append('<option value="' + picking_order_status + '">' + picking_order_status + '</option>');
			$("#slccheckerheaderpb").append('<option value="' + karyawan_id + '">' + karyawan_nama + '</option>');
			// $("#lastUpdated").val(lastUpdated);

			if (picking_order_status == "Draft") {

				// document.getElementById("chkpb").checked = false;


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

				// document.getElementById("chkpb").checked = true;

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

				// document.getElementById("chkpb").checked = false;

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

				// document.getElementById("chkpb").checked = true;

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

				for (i = 0; i < Channel.PengeluaranBarangManual.length; i++) {
					var picking_order_plan_id = Channel.PengeluaranBarangManual[i].picking_order_plan_id;
					var principle_id = Channel.PengeluaranBarangManual[i].principle_id;
					var principal = Channel.PengeluaranBarangManual[i].principal;
					var sku_kode = Channel.PengeluaranBarangManual[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarangManual[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarangManual[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarangManual[i].sku_satuan;
					var sku_stock_qty_ambil = Channel.PengeluaranBarangManual[i].sku_stock_qty_ambil;
					var sku_stock_qty_ambil_aktual = Channel.PengeluaranBarangManual[i].sku_stock_qty_ambil_aktual;
					var sku_stock_expired_date = Channel.PengeluaranBarangManual[i].sku_stock_expired_date;
					var sku_stock_expired_date_aktual = Channel.PengeluaranBarangManual[i].sku_stock_expired_date_aktual;
					var rak_nama = Channel.PengeluaranBarangManual[i].rak_nama;
					var karyawan_nama = Channel.PengeluaranBarangManual[i].karyawan_nama;
					jumlah_barang += sku_stock_qty_ambil_aktual;

					// pushPrincipleToAray('bulk', principal)

					var strmenu = '';

					strmenu = strmenu + '<tr>';
					strmenu = strmenu + '	<td>' + no + '</td>';
					strmenu = strmenu + '	<td>' + principal + '</td>';
					strmenu = strmenu + '	<td>' + sku_kode + '</td>';
					strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
					// strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
					strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
					strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
					// strmenu = strmenu + '	<td>' + rak_nama + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
					strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_aktual) + '</td>';
					// strmenu = strmenu + '	<td>'+ sku_stock_expired_date_aktual +'</td>';
					// strmenu = strmenu + '	<td>' + karyawan_nama + '</td>';
					// strmenu = strmenu + '	<td>';
					// strmenu = strmenu +
					// 	'		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' +
					// 	picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
					// strmenu = strmenu + '	</td>';
					strmenu = strmenu + '</tr>';

					$("#tabledetailpbbulk > tbody").append(strmenu);
					no++;
				}

				$("#txtjumlahbarang").val(jumlah_barang);
				$('#tabledetailpbbulk').DataTable({
					"lengthMenu": [
						[10],
						[100]
					],
					"bAutoWidth": false,
					aoColumns: [{
						"sWidth": "2px",
						"aTargets": [0]
					}, ]
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
								$("#slccheckerpb").append('<option value="' + karyawan_id_checker + ' || ' +
									karyawan_nama_checker + '" selected>' + karyawan_nama_checker + '</option>');
							} else {
								$("#slccheckerpb").append('<option value="' + karyawan_id_checker + ' || ' +
									karyawan_nama_checker + '">' + karyawan_nama_checker + '</option>');
							}
						}
					}

				} else if (picking_order_status == "In Progress") {

					$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '" selected>' +
						karyawan_nama + '</option>');

				} else if (picking_order_status == "Cancel") {

					$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '" selected>' +
						karyawan_nama + '</option>');

				} else if (picking_order_status == "Completed") {

					$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama + '" selected>' +
						karyawan_nama + '</option>');
				}

				if ($.fn.DataTable.isDataTable('#tabledetailpbstandar')) {
					$('#tabledetailpbstandar').DataTable().destroy();
				}

				$('#tabledetailpbstandar tbody').empty();

				for (i = 0; i < Channel.PengeluaranBarangManual.length; i++) {
					var picking_order_plan_id = Channel.PengeluaranBarangManual[i].picking_order_plan_id;
					var delivery_order_kode = Channel.PengeluaranBarangManual[i].delivery_order_kode;
					var principal = Channel.PengeluaranBarangManual[i].principal;
					var sku_kode = Channel.PengeluaranBarangManual[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarangManual[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarangManual[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarangManual[i].sku_satuan;
					var sku_stock_qty_ambil = Channel.PengeluaranBarangManual[i].sku_stock_qty_ambil;
					var sku_stock_qty_ambil_aktual = Channel.PengeluaranBarangManual[i].sku_stock_qty_ambil_aktual;
					var sku_stock_expired_date = Channel.PengeluaranBarangManual[i].sku_stock_expired_date;
					var sku_stock_expired_date_aktual = Channel.PengeluaranBarangManual[i].sku_stock_expired_date_aktual;
					var rak_nama = Channel.PengeluaranBarangManual[i].rak_nama;
					jumlah_barang += sku_stock_qty_ambil_aktual;

					// pushPrincipleToAray('standar', principal)

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
					// strmenu = strmenu + '	<td>';
					// strmenu = strmenu +
					// 	'		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' +
					// 	picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
					// strmenu = strmenu + '	</td>';
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

				for (i = 0; i < Channel.PengeluaranBarangManual.length; i++) {
					var picking_order_plan_id = Channel.PengeluaranBarangManual[i].picking_order_plan_id;
					var principal = Channel.PengeluaranBarangManual[i].principal;
					var sku_kode = Channel.PengeluaranBarangManual[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarangManual[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarangManual[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarangManual[i].sku_satuan;
					var sku_stock_qty_ambil = Channel.PengeluaranBarangManual[i].sku_stock_qty_ambil;
					var sku_stock_qty_ambil_aktual = Channel.PengeluaranBarangManual[i].sku_stock_qty_ambil_aktual;
					var sku_stock_expired_date = Channel.PengeluaranBarangManual[i].sku_stock_expired_date;
					var sku_stock_expired_date_aktual = Channel.PengeluaranBarangManual[i].sku_stock_expired_date_aktual;
					var rak_nama = Channel.PengeluaranBarangManual[i].rak_nama;
					var karyawan_nama = Channel.PengeluaranBarangManual[i].karyawan_nama;
					jumlah_barang += sku_stock_qty_ambil_aktual;

					// pushPrincipleToAray('reschedule', principal)

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
					// strmenu = strmenu + '	<td>';
					// strmenu = strmenu +
					// 	'		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' +
					// 	picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
					// strmenu = strmenu + '	</td>';
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

				for (i = 0; i < Channel.PengeluaranBarangManual.length; i++) {
					var picking_order_plan_id = Channel.PengeluaranBarangManual[i].picking_order_plan_id;
					var principal = Channel.PengeluaranBarangManual[i].principal;
					var sku_kode = Channel.PengeluaranBarangManual[i].sku_kode;
					var sku_nama_produk = Channel.PengeluaranBarangManual[i].sku_nama_produk;
					var sku_kemasan = Channel.PengeluaranBarangManual[i].sku_kemasan;
					var sku_satuan = Channel.PengeluaranBarangManual[i].sku_satuan;
					var sku_stock_qty_ambil = Channel.PengeluaranBarangManual[i].sku_stock_qty_ambil;
					var sku_stock_qty_ambil_aktual = Channel.PengeluaranBarangManual[i].sku_stock_qty_ambil_aktual;
					var sku_stock_expired_date = Channel.PengeluaranBarangManual[i].sku_stock_expired_date;
					var sku_stock_expired_date_aktual = Channel.PengeluaranBarangManual[i].sku_stock_expired_date_aktual;
					var rak_nama = Channel.PengeluaranBarangManual[i].rak_nama;
					var karyawan_nama = Channel.PengeluaranBarangManual[i].karyawan_nama;
					jumlah_barang += sku_stock_qty_ambil_aktual;

					// pushPrincipleToAray('canvas', principal)

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
					strmenu = strmenu +
						'		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' +
						picking_order_id + '\',\'' + picking_order_plan_id + '\')"+><i class="fa fa-search"></i></button>';
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

				Swal.close();
			} else if (tipe_delivery_order_nama == "Mix") {
				var txtnoppb = $("#txtnoppb2").val();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Maintenance/HapusPickingOrder2/GetDetailPengeluaranBarangManualMixMenu') ?>",
					data: "picking_order_kode=" + txtnoppb,
					dataType: "JSON",
					success: function(response) {
						ChPengeluaranBarangManualMixMenu(response, picking_order_status);
					}
				});
			}
		}

		function ChPengeluaranBarangManualMixMenu(Channel, status) {
			// console.log(Channel.PengeluaranBarangManual);
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

			if (Channel.PengeluaranBarangManual != 0) {
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

				$.each(Channel.PengeluaranBarangManual, function(i, v) {
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

					$("#slccheckerheaderpb").append('<option value="' + karyawan_id + '">' + karyawan_nama +
						'</option>');
					$("#txtcheckerpb").val(karyawan_id + ' || ' + karyawan_nama);
					if (['Bulk', 'Standar', 'Flush Out'].includes(tipe_delivery_order_nama)) {
						$("#tipeBKBMix").val(tipe_delivery_order_nama);
					}

					if (tipe_delivery_order_nama == "Bulk") {
						$(".fieldFormChecker").show();
						countDataBulk++;

						// pushPrincipleToAray('bulk', principal)

						let strmenu = '';

						strmenu = strmenu + '<tr>';
						strmenu = strmenu + '	<td>' + no + '</td>';
						strmenu = strmenu + '	<td>' + principal + '</td>';
						strmenu = strmenu + '	<td>' + sku_kode + '</td>';
						strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
						strmenu = strmenu + '	<td style="display:none;">' + sku_kemasan + '</td>';
						strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
						strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
						strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil) + '</td>';
						// strmenu = strmenu + '	<td>' + rak_nama + '</td>';
						strmenu = strmenu + '	<td>' + Math.round(sku_stock_qty_ambil_aktual) + '</td>';
						// strmenu = strmenu + '	<td>'+ sku_stock_expired_date_aktual +'</td>';
						// strmenu = strmenu + '	<td>' + karyawan_nama + '</td>';
						// strmenu = strmenu + '	<td>';
						// strmenu = strmenu +
						// 	'		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' +
						// 	picking_order_id + '\',\'' + picking_order_plan_id +
						// 	'\')"+><i class="fa fa-search"></i></button>';
						// strmenu = strmenu + '	</td>';
						strmenu = strmenu + '</tr>';

						$("#tabledetailpbbulk > tbody").append(strmenu);
						no++;

					} else if (tipe_delivery_order_nama == "Standar") {
						$(".fieldFormChecker").hide();
						countDataStandar++;

						// pushPrincipleToAray('standar', principal)

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
						// strmenu = strmenu + '	<td>';
						// strmenu = strmenu +
						// 	'		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' +
						// 	picking_order_id + '\',\'' + picking_order_plan_id +
						// 	'\')"+><i class="fa fa-search"></i></button>';
						// strmenu = strmenu + '	</td>';
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
										$("#slccheckerpb").append('<option value="' + karyawan_id_checker +
											' || ' + karyawan_nama_checker + '" selected>' +
											karyawan_nama_checker + '</option>');
									} else {
										$("#slccheckerpb").append('<option value="' + karyawan_id_checker +
											' || ' + karyawan_nama_checker + '">' + karyawan_nama_checker +
											'</option>');
									}
								})
							}

						} else if (status == "In Progress") {

							$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama +
								'" selected>' + karyawan_nama + '</option>');

						} else if (status == "Cancel") {

							$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama +
								'" selected>' + karyawan_nama + '</option>');

						} else if (status == "Completed") {

							$("#slccheckerpb").append('<option value="' + karyawan_id + ' || ' + karyawan_nama +
								'" selected>' + karyawan_nama + '</option>');
						}

					} else if (tipe_delivery_order_nama == "Reschedule") {
						$(".fieldFormChecker").show();
						countDataKirimUlang++;

						// pushPrincipleToAray('reschedule', principal)

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
						// strmenu = strmenu + '	<td>';
						// strmenu = strmenu +
						// 	'		<button style="width: 40px;" class="btn btn-primary form-control" onclick="GetDetailBKBAktualPlan(\'' +
						// 	picking_order_id + '\',\'' + picking_order_plan_id +
						// 	'\')"+><i class="fa fa-search"></i></button>';
						// strmenu = strmenu + '	</td>';
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
					],
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

			Swal.close();
		}
	}

	function deletePickingOrder() {
		var picking_order_kode = $("#txtnoppb2").val();

		if (picking_order_kode != '') {
			Swal.fire({
				title: "Apakah anda yakin menghapus data ini?",
				text: "Data yang sudah dihapus akan hilang!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Hapus",
				cancelButtonText: "Tidak, Tutup"
			}).then((result) => {
				if (result.value == true) {
					$.ajax({
						type: 'POST',
						url: "<?= base_url('WMS/Maintenance/HapusPickingOrder2/deletePickingOrder') ?>",
						data: "picking_order_kode=" + picking_order_kode,
						beforeSend: function() {
							Swal.fire({
								title: 'Loading ...',
								html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
								timerProgressBar: false,
								showConfirmButton: false,
								allowOutsideClick: false // Menonaktifkan klik di luar SweetAlert
							});
						},
						success: function(response) {
							if (response != null) {
								message("Success!", "Sukses berhasil menghapus data!", "success");

								setTimeout(() => {
									window.location.reload();
								}, 2000);
								return false;
							} else {
								message("Error!", response.ErrMsg, "error");
								return false;
							}
						}
					})
				}
			})
		} else {
			message("Error!", "Cari No PPB Terlebih Dahulu!", "error");
			return false;
		}

	}
</script>