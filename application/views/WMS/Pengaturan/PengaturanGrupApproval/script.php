<script type="text/javascript">
	loadingBeforeReadyPage()
	$(document).ready(function() {
		$("#tableListGrup").DataTable();
	});

	// $("#perusahaan").on('change', function() {
	// 	var client_wms_id = $("#perusahaan").val();

	// 	$.ajax({
	// 		type: "POST",
	// 		url: "<?= base_url('WMS/Pengaturan/PengaturanGroupApproval/getApprovalGrup') ?>",
	// 		data: {
	// 			client_wms_id: client_wms_id
	// 		},
	// 		dataType: "JSON",
	// 		success: function(response) {
	// 			if (response.length != 0) {
	// 				$("#tableListGrup > tbody").empty();

	// 				$.each(response, function(i, v) {
	// 					$("#tableListGrup > tbody").append(`
	//                         <tr>
	//                             <td>${i + 1}</td>
	//                             <td>${v.approval_group_nama}</td>
	//                             <td>${v.approval_group_keterangan}</td>
	//                             <td>
	//                                 <button type="button" id="btnaddlevel-${i}" class="btn btn-success" onclick="openGrup('${v.approval_group_id}', '${i}')">
	//                                     <i class="fa fa-plus"></i>
	//                                 </button>
	//                             </td>
	//                         </tr>
	//                     `);
	// 				})
	// 			} else {
	// 				$("#tableListGrup > tbody").empty().append(`
	//                     <tr>
	//                         <td colspan="4">No data available in table</td>
	//                     </tr>
	//                 `);
	// 			}
	// 		}
	// 	})
	// });

	function addGrup() {
		$("#modalGrup").modal('show');
	}

	$("#saveGrup").on('click', function() {
		var nama = $('#nama_grup').val();
		var keterangan = $('#keterangan_grup').val();

		if (nama == '' || keterangan == '') {
			message_topright('warning', 'Form tidak boleh kosong, harap periksa kembali!');
			return false;
		}

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Pengaturan/PengaturanGroupApproval/saveGroup') ?>",
			data: {
				nama: nama,
				keterangan: keterangan
			},
			dataType: "JSON",
			success: function(response) {
				if (response == 1) {
					message("Selamat", "Grup berhasil ditambahkan", "success");
					setTimeout(() => {
						location.reload();
					}, 2000);
					$("#modalGrup").modal('hide');
				} else {
					message("Gagal", "Terdapat kesalahan sistem saat menambahkan grup", "error");
				}
			}
		})
	})

	function openGrup(approval_group_id, idx) {
		$("#modalGrupDetail").modal('show');
		$("#approval_group_id").val(approval_group_id);
		var client_wms_id = $("#perusahaan").val();

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Pengaturan/PengaturanGroupApproval/getDetailByGrupID') ?>",
			data: {
				approval_group_id: approval_group_id
			},
			dataType: "JSON",
			success: function(response) {
				$.ajax({
					type: "POST",
					url: "<?= base_url('WMS/Pengaturan/PengaturanGroupApproval/getKaryawan') ?>",
					data: {
						client_wms_id: client_wms_id
					},
					dataType: "JSON",
					success: function(data) {
						var count = $("#tableGrupDetail > tbody tr").length;
						var karyawan = '';

						if (response.detail.length != 0) {
							$("#tableGrupDetail > tbody").empty();

							$.each(response.detail, function(i, v) {
								karyawan = '';
								$.each(data, function(x, y) {
									karyawan += '<option value="' + y.karyawan_id + '" ' + (y.karyawan_id == v.karyawan_id ? "selected" : "") + '>' + y.karyawan_nama + '</option>';
								})

								$("#tableGrupDetail > tbody").append(`
                                    <tr>
                                        <td>${i + 1}</td>
                                        <td>
                                            <select class="form-control" id="karyawan-${i + 1}" onchange="getDivisiAndLevel('', '${i + 1}')">
                                                <option>Pilih karyawan</option>
                                                ${karyawan}
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" id="divisi-${i + 1}" placeholder="Level..." disabled/></td>
                                        <td><input type="text" class="form-control" id="level-${i + 1}" placeholder="Posisi..." disabled/></td>
                                        <td><input type="text" class="form-control" placeholder="Posisi..." value="${v.approval_group_detail_no_urut}" onkeypress="return event.charCode >= 48 && event.charCode <= 57"/></td>
                                        <td>
                                            <button type="button" class="btn btn-danger" onclick="delLevel(this)">
                                                <i class="fa fa-close"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `);
								getDivisiAndLevel(v.karyawan_id, i + 1);
							});
						}
					}
				});
			}
		});
	}


	function addDetail() {
		var client_wms_id = $("#perusahaan").val();

		$.ajax({
			type: "GET",
			url: "<?= base_url('WMS/Pengaturan/PengaturanGroupApproval/getKaryawan') ?>",
			dataType: "JSON",
			success: function(response) {
				var count = $("#tableGrupDetail > tbody tr").length;
				var karyawan = '';

				if (response.length > 0) {
					$.each(response, function(i, v) {
						karyawan += '<option value="' + v.karyawan_id + '">' + v.karyawan_nama + '</option/>';
					})
				}

				$("#tableGrupDetail > tbody").append(`
                    <tr>
                        <td>${count + 1}</td>
                        <td>
                            <select class="form-control" id="karyawan-${count + 1}" onchange="getDivisiAndLevel('', '${count + 1}')">
                                <option>Pilih karyawan</option/>
                                ${karyawan}
                            <select/>
                        </td>
                        <td><input type="text" class="form-control" id="divisi-${count + 1}" placeholder="Level..." disabled/></td>
                        <td><input type="text" class="form-control" id="level-${count + 1}" placeholder="Posisi..." disabled/></td>
                        <td><input type="text" class="form-control" placeholder="Posisi..."onkeypress="return event.charCode >= 48 && event.charCode <= 57"/></td>
                        <td>
                            <button type="button" class="btn btn-danger" onclick="delLevel(this)">
                                <i class="fa fa-close"></i>
                            </button>
                        </td>
                    </tr>
                `);
			}
		})
	}

	function delLevel(Idx) {
		var row = Idx.parentNode.parentNode;
		row.parentNode.removeChild(row);

		var no = 1;
		$("#tableGrupDetail > tbody tr").each(function() {
			$(this).find("td:eq(0)").text(no++);
		})

		message_topright('success', 'Berhasil menghapus level');
	}

	function getDivisiAndLevel(karyawan_id, idx) {

		if (karyawan_id != '') {
			karyawan_id == karyawan_id;
		} else {
			karyawan_id = $("#karyawan-" + idx).val();
		}

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Pengaturan/PengaturanGroupApproval/getDivisiAndLevel') ?>",
			data: {
				karyawan_id: karyawan_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#divisi-" + idx).val(response.karyawan_divisi_nama);
				$("#level-" + idx).val(response.karyawan_level_nama);
			}
		})
	}

	$("#saveDetail").on('click', function() {
		var grup_id = $("#approval_group_id").val();
		var arr_detail = [];

		$("#tableGrupDetail > tbody tr").each(function(i, v) {
			var karyawan_id = $(this).find("td:eq(1) select option:selected").val();
			var urutan = $(this).find("td:eq(4) input[type='text']").val();

			arr_detail.push({
				karyawan_id,
				urutan
			});
		});


		$.each(arr_detail, function(i, v) {
			if (v.karyawan_id == '' || v.urutan == '') {
				message_topright('warning', 'Form tidak boleh kosong, harap periksa kembali!');
				return false;
			}
		})

		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/Pengaturan/PengaturanGroupApproval/saveDetail') ?>",
			data: {
				grup_id: grup_id,
				arr_detail
			},
			dataType: "JSON",
			success: function(response) {
				if (response == 1) {
					message("Selamat", "Detail berhasil ditambahkan", "success");
					setTimeout(() => {
						location.reload();
					}, 2000);
					$("#modalAddLevel").modal('hide');
				} else {
					message("Gagal", "Terdapat kesalahan sistem saat menambahkan detail", "error");
				}
			}
		})
	})
</script>