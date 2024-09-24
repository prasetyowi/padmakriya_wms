<script>
	let arrDetail = []
	let arrDetailTersimpan = []
	// {
	// 	principle_id : item.principle_id,
	// 	principle_kode : item.principle_kode,
	// 	top : item.client_pt_principle_top,
	// 	is_kredit : item.client_pt_principle_top,
	// }
	let arrDraftDetail = []
	let arrSlcPrinciple = []
	let arrSlcDivisi = []
	let arrSlcLevel = []
	let arrApprovalGroup = []
	let arrDetailPrincipleDraft = []
	let arrAlamat = [] //aalamat outlet yg 1 group corp
	var total_nominal_aktual = 0;
	loadingBeforeReadyPage()
	$(document).ready(function() {
		// alert('aaa')
		$(".select2").select2({
			width: "100%"
		});
		$(".custom-select").select2({
			width: "100%"
		});
		getListKaryawanDivisi();
		getListKaryawanLevel();

		if ($("#add_id").val() != "") {
			getDataApproval();
		}

		getListNamaGroupApproval()
	});

	function getListKaryawanDivisi() {
		// add karyawan divisi
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/getListKaryawanDivisi') ?>",
			success: function(response) {
				let data = response;
				data.forEach(function(item, index) {
					arrSlcDivisi.push({
						divisi_nama: item.karyawan_divisi_nama,
						divisi_id: item.karyawan_divisi_id
					})

				});
				console.log(arrSlcDivisi);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}

	function getListKaryawanLevel() {
		// add karyawan level
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/getListKaryawanLevel') ?>",
			success: function(response) {
				let data = response;
				data.forEach(function(item, index) {
					arrSlcLevel.push({
						level_nama: item.karyawan_level_nama,
						level_id: item.karyawan_level_id
					})

				});
				console.log(arrSlcLevel);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}

	function getListKaryawanLevelByDivisi(index) {
		// add karyawan level

		arrSlcLevel = [];
		let divisi_id = $("#slc_divisi-" + index).val()
		$("#slc_level-" + index + " option").remove()
		$("#slc_level-" + index).append('<option value="" data-kode="">--Pilih Level--</option>')
		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/getListKaryawanLevelByDivisi') ?>",
			data: {
				divisi_id: divisi_id
			},
			async: false,
			success: function(response) {
				let data = response;
				data.forEach(function(item) {
					$("#slc_level-" + index).append('<option value="' + item.karyawan_level_id +
						'" data-kode="' + item.karyawan_level_nama +
						'">' + item.karyawan_level_nama +
						'</option>')

				});
				// console.log(arrSlcLevel);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	}

	function getListNamaGroupApproval() {
		// add karyawan level
		$.ajax({
			async: false,
			type: 'GET',
			url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/getListNamaGroupApproval') ?>",
			dataType: 'json',
			success: function(response) {
				let data = response;
				if (data)
					data.forEach(function(item, index) {
						arrApprovalGroup.push(item)

					});
				console.log(arrApprovalGroup);
			}
		});
	}

	// function checkParameterTersimpan() {
	//     // alert(12)
	//     let parameter = $('#add_parameter').val();
	//     $('#add_parameter').focus();
	//     // getAlamatPelangganById
	//     $.ajax({
	//         type: 'GET',
	//         url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/CheckParameterTersimpan') ?>",
	//         data: {
	//             parameter: parameter
	//         },
	//         success: function(response) {
	//             // alert()
	//             // console.log(response.is_duplikat);
	//             if (response.is_duplikat == 1) {
	//                 alert('Parameter ' + parameter + ' sudah digunakan !')
	//                 $('#add_parameter').focus();
	//                 $('#info_check_param').css({
	//                     "display": "show"
	//                 });
	//             } else {
	//                 $('#info_check_param').css({
	//                     "display": "show"
	//                 });
	//             }
	//         }
	//     });

	// }


	function addDataApprover() {
		var rowCount = $('#tableDetail tbody tr').length;
		let no = rowCount + 1;
		let option_divisi = '<option value="" >-- Pilih Divisi --</option>';
		let option_level = '<option value="" >-- Pilih Level --</option>';
		arrSlcDivisi.forEach(function(item, index) {
			option_divisi = option_divisi +
				'<option value="' + item.divisi_id + '" data-kode="' + item.divisi_nama +
				'">' + item.divisi_nama +
				'</option>';
		});
		let option_approval_group = '<option value="" >-- Pilih Group --</option>';

		arrApprovalGroup.forEach(function(item, index) {
			option_approval_group = option_approval_group +
				'<option value="' + item.approval_group_id + '" data-kode="' + item.approval_group_nama +
				'">' + item.approval_group_nama +
				'</option>';
		});
		// arrSlcLevel.forEach(function(item, index) {
		//     option_level = option_level +
		//         '<option value="' + item.level_id + '" data-kode="' + item.level_nama +
		//         '">' + item.level_nama +
		//         '</option>';
		// });
		$('#tableDetail tbody').append(`
            	<tr>
                    <td style="text-align: center;">
						<input type="checkbox" onclick="chkSupervisor(${no})" class="is_direct_supervisor" id="is_direct_supervisor-${no}" name="is_direct_supervisor[]" class="" style="width: 20px;height: 20px;" />
                    </td>
					<td>
					<select id="slc_approval_group-${no}" name="slc_approval_group[]"
							class="form-control custom-select" >
							${option_approval_group}
						</select>
                    </td>
					<td style='display:none'>
						<select id="slc_level-${no}" name="slc_level[]"
							class="form-control custom-select" >
							
						</select>
					</td>
					<td><input type="number" id="min_nilai[]" name="min_nilai[]" class=" form-control" value="0"/></td>
					<td><input type="number" id="max_nilai[]" name="max_nilai[]" class=" form-control" value="0"/></td>
					<td><a class="btn btn-danger DeleteRow"><i class="fa fa-trash"></i><a><a class="btn btn-info infoDetail ${no}"><i class="fa fa-eye"></i><a></td>
				</tr>
        `);
	}

	function chkSupervisor(no) {
		if ($("#is_direct_supervisor-" + no).is(':checked')) {
			$('#slc_level-' + no).prop('disabled', true);
			$('#slc_approval_group-' + no).prop('disabled', true);
			$('#slc_approval_group-' + no).val(null)
			$('#slc_level-' + no).val(null)
			$("#slc_level-" + index + " option").remove()
			$("#slc_level-" + index).append('<option value="" data-kode="">--Pilih Level--</option>')
		} else {
			$('#slc_level-' + no).prop('disabled', false);
			$('#slc_approval_group-' + no).prop('disabled', false);
			$('#slc_approval_group-' + no).val(null)
			$('#slc_level-' + no).val(null)
			$("#slc_level-" + index + " option").remove()
			$("#slc_level-" + index).append('<option value="" data-kode="">--Pilih Level--</option>')
		}
	}

	$("#tableDetail").on("click", ".DeleteRow", function() {
		$(this).closest("tr").remove();
	});

	$("#tableDetail").on("click", ".infoDetail", function() {
		let cek = $(this).attr('class').split(' ')[3]
		let id_approval_group = $(`#slc_approval_group-${cek} option:selected`).val()
		$('#tabledetailGroup > tbody').empty()
		if (id_approval_group == '') {
			message('Info!', 'Mohon pilih approval group terlebih dahulu!!', 'info')
			return false
		} else {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/getDataByApprovalGroupId') ?>",
				data: {
					id: id_approval_group
				},
				dataType: 'json',
				success: function(response) {
					$.each(response, function(i, v) {
						$('#tabledetailGroup > tbody').append(` 
						<tr>
						<td class="text-center" style="font-weight:bold">${i+1}</td>
						<td class="text-center" style="font-weight:bold">${v.karyawan_nama==null?'':v.karyawan_nama}</td>
						<td class="text-center" style="font-weight:bold">${v.approval_group_detail_no_urut==null?'':v.approval_group_detail_no_urut}</td>
						<td class="text-center" style="font-weight:bold">${v.karyawan_divisi_nama==null?'':v.karyawan_divisi_nama}</td>
						</tr>
						`)
					})

				}
			});
			$('#modalDetailGroup').modal('show')

		}
	});



	//save Pengeluaran
	$('#saveData').click(function(e) {
		e.preventDefault();

		// input header
		let add_id = $("#add_id").val();
		let add_parameter = $("#add_parameter").val();
		let add_keterangan = $("#add_keterangan").val();
		let add_jenis = $("#add_jenis").val();
		let add_url = $("#add_url").val();
		let add_menu = $("#add_menu").val();
		let add_menu_kode = $('#add_menu option:selected').attr('data-kode');
		let add_status = $("#add_status").is(':checked') ? 1 : 0;
		let add_is_paralel = $("#add_is_paralel").is(':checked') ? 1 : 0;
		// cek jumlah input tr tbledetail;
		var rowCount = $('#tableDetail tbody tr').length;

		// validasi header
		if (add_menu == '') {
			alert('Harap pilih menu approval terlebih dahulu !')
			$("#add_menu").focus();
			return false;
		}
		if (add_keterangan == '') {
			alert('Harap masukkan keterangan pengaturan approval terlebih dahulu !')
			$("#add_keterangan").focus();
			return false;
		}
		if (add_parameter == '') {
			alert('Harap masukkan parameter pengaturan approval terlebih dahulu !')
			$("#add_parameter").focus();
			return false;
		}
		if (rowCount <= 0) {
			alert('Harap tabahkan data approver terlebih dahulu')
			return false;
		}
		let error = 0;
		//cek duplicat param
		// $.ajax({
		//     type: 'GET',
		//     async: false,
		//     url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/CheckParameterTersimpan') ?>",
		//     data: {
		//         parameter: add_parameter
		//     },
		//     success: function(response) {
		//         if (response.is_duplikat == 1) {
		//             alert('Parameter ' + add_parameter + ' sudah digunakan !')
		//             $('#add_parameter').focus();
		//             $('#info_check_param').css({
		//                 "display": "show"
		//             });
		//             error++
		//             return false;
		//         } else {
		//             $('#info_check_param').css({
		//                 "display": "show"
		//             });
		//         }
		//     }
		// });

		// input detail
		let is_direct_supervisor = $("input[name='is_direct_supervisor[]']").map(function() {
			if ($(this).is(':checked')) {
				return 1;
			} else {
				return 0;
			}
		}).get();

		let slc_approval_group = $("[name='slc_approval_group[]']").map(function() {
			return this.value
		}).get();
		let slc_level = $("[name='slc_level[]']").map(function() {
			return this.value
		}).get();
		let min_nilai = $("input[name='min_nilai[]']").map(function() {
			return this.value
		}).get();
		let max_nilai = $("input[name='max_nilai[]']").map(function() {
			return this.value
		}).get();

		arrDetail = []
		const encounteredIds = {};

		let hasDuplicateItemApprovalId = false;

		// validasi detail
		is_direct_supervisor.forEach((v, i) => {
			if (v == 0 && (slc_approval_group[i] == '')) {
				alert('Harap pastikan memilih Group terlebih dahulu!')
				error++;
				return false;
			}
			arrDetail.push({
				is_direct_supervisor: v,
				group: slc_approval_group[i],
				level: slc_level[i],
				min_nilai: min_nilai[i],
				max_nilai: max_nilai[i],
			})
		});
		console.log(slc_approval_group);
		$.each(slc_approval_group, function(index, item) {
			const item_approval_id = item;
			console.log(item_approval_id);
			if (encounteredIds[item_approval_id]) {
				hasDuplicateItemApprovalId = true;
				return false;
			}
			encounteredIds[item_approval_id] = true;
		});

		if (hasDuplicateItemApprovalId) {
			message('info', 'Data approval group ada yang sama!', 'info')
			return false;
		}
		if (error > 0) {
			return false
		}
		// console.log(arrDetail);

		Swal.fire({
			title: 'Apakah Anda yakin?',
			text: "Pastikan data yang diinput sudah benar!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya!'
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/UpdatePengaturanApproval') ?>",
					type: 'POST',
					data: {
						id: add_id,
						is_paralel: add_is_paralel,
						parameter: add_parameter,
						menu_id: add_menu,
						menu_kode: add_menu_kode,
						keterangan: add_keterangan,
						jenis: add_jenis,
						reff_url: add_url,
						status: add_status,
						dataDetail: arrDetail
					},
					dataType: "JSON",
					async: false,
					success: function(response) {
						if (response.status == 1) {
							Swal.fire(
								'Success!',
								'Data has been saved.',
								'success'
							)
							setTimeout(function() {
								window.location.href =
									"<?= base_url('WMS/Pengaturan/PengaturanApproval/PengaturanApprovalMenu') ?>";
							}, 3000);
						} else {
							Swal.fire(
								'Error!',
								response.message,
								'warning'
							)
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(xhr.status);
						alert(thrownError);
					}
				});
			}
		})
	});

	//get level karyawan by divisi mode edit
	function getDataApproval() {
		var rowCount = $('#tableDetail tbody tr').length;
		let no = rowCount + 1;


		//mendapatkan data dari table detail approval by id
		$.ajax({
			type: 'GET',
			url: "<?= base_url() ?>WMS/Pengaturan/PengaturanApproval/getApprovalByID",
			dataType: "JSON",
			data: {
				id: $("#add_id").val()
			},
			success: function(response) {
				$.each(response, function(i, v) {
					//membulatkan nilai desimal
					var min = Math.round(v.approval_setting_detail_min_nilai);
					var max = Math.round(v.approval_setting_detail_max_nilai);
					let option_divisi = "";
					option_level = "";
					option_divisi = '<option value="" >-- Pilih Divisi --</option>';
					option_level =
						'<option value="" >-- Pilih Level --</option>';
					let option_approval_group = '<option value="" >-- Pilih Group --</option>';
					console.log("tes", arrApprovalGroup);
					let selected = '';
					arrApprovalGroup.forEach(function(item, index) {
						console.log(v.approval_group_id, "==", item.approval_group_id);
						// option_approval_group = option_approval_group +
						// 	'<option value="' + item.approval_group_id + '" data-kode="' + item.approval_group_nama +
						// 	'">' + item.approval_group_nama +
						// 	'</option>';
						if (v.approval_group_id == item.approval_group_id) {
							selected = 'selected'
						} else {
							selected = ''
						}
						option_approval_group = option_approval_group +
							'<option value="' + item.approval_group_id + '" data-kode="' + item.approval_group_nama + '" ' + selected + '>' + item.approval_group_nama +
							'</option>';
						'</option>';

					});
					// arrSlcDivisi.forEach(function(item, index) {
					// 	//kondisi selected ketika divisi sesuai sama yang dipilih
					// 	if (v.karyawan_divisi_id == item.divisi_id) {
					// 		option_divisi = option_divisi +
					// 			'<option value="' + item.divisi_id + '" data-kode="' + item
					// 			.divisi_nama +
					// 			'" selected>' + item.divisi_nama +
					// 			'</option>';

					// 		$("#slc_level-" + index + " option").remove()
					// 		$("#slc_level-" + index).append(
					// 			'<option value="" data-kode="">--Pilih Level--</option>')
					// 		//mendapatkan data dari level karyawan by divisi yang dipilih
					// 		$.ajax({
					// 			type: 'GET',
					// 			url: "<?= base_url('WMS/Pengaturan/PengaturanApproval/getListKaryawanLevelByDivisi') ?>",
					// 			data: {
					// 				divisi_id: v.karyawan_divisi_id
					// 			},
					// 			async: false,
					// 			success: function(response) {
					// 				let data = response;
					// 				data.forEach(function(item) {

					// 					//menampilkan selected jika level yang dipilih sebelumnya sesuai dengan divisi
					// 					if (v.karyawan_level_id == item
					// 						.karyawan_level_id) {
					// 						option_level = option_level +
					// 							'<option value="' + item
					// 							.karyawan_level_id +
					// 							'" data-kode="' +
					// 							item
					// 							.karyawan_level_nama +
					// 							'" selected>' + item
					// 							.karyawan_level_nama +
					// 							'</option>';
					// 					} else {
					// 						option_level = option_level +
					// 							'<option value="' + item
					// 							.karyawan_level_id +
					// 							'" data-kode="' +
					// 							item
					// 							.karyawan_level_nama +
					// 							'">' + item
					// 							.karyawan_level_nama +
					// 							'</option>';
					// 					}
					// 				});
					// 			}
					// 		});
					// 	} else {
					// 		option_divisi = option_divisi +
					// 			'<option value="' + item.divisi_id + '" data-kode="' + item
					// 			.divisi_nama +
					// 			'">' + item.divisi_nama +
					// 			'</option>';
					// 	}
					// });

					//memasukkan data table detail approval 
					$('#tableDetail tbody').append(`
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" onclick="chkSupervisor(${no})" class="is_direct_supervisor" id="is_direct_supervisor-${no}" name="is_direct_supervisor[]" class="" style="width: 20px;height: 20px;" />
                        </td>
                        <td>
						<select id="slc_approval_group-${no}" name="slc_approval_group[]"
							class="form-control custom-select" >
							${option_approval_group}
						</select>
                        </td>
                        <td style='display:none'>
                            <select id="slc_level-${no}" name="slc_level[]"
                                class="form-control custom-select" >
                                ${option_level}
                            </select>
                        </td>
                        <td><input type="number" id="min_nilai[]" name="min_nilai[]" class=" form-control" value="${min}"/></td>
                        <td><input type="number" id="max_nilai[]" name="max_nilai[]" class=" form-control" value="${max}"/></td>
                        <td><a class="btn btn-danger DeleteRow"><i class="fa fa-trash"></i><a><a class="btn btn-info infoDetail ${no}"><i class="fa fa-eye"></i><a></td>
                    </tr>
                `);
					no++;
				})
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		})

	}

	function formatRupiah(angka, prefix) {
		var rupiah = '';
		var angkarev = angka.toString().split('').reverse().join('');
		for (
			var i = 0; i < angkarev.length; i++)
			if (i % 3 == 0) rupiah += angkarev.substr(i, 3) + '.';
		return rupiah.split('', rupiah.length - 1).reverse().join('');
	}
</script>