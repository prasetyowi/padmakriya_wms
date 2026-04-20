<script type="text/javascript">
	loadingBeforeReadyPage()
	$(document).ready(function() {
		$('.select2').select2({
			width: "100%"
		});

		if ($('#filter_tanggal').length > 0) {
			$('#filter_tanggal').daterangepicker({
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

		if ($("#trOpnameResultID").length == 0) {
			getDataDokumenOpname();
		}
	});

	function getDataDokumenOpname() {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/TindakLanjutHasilStockOpname/getDataDokumenOpname') ?>",
			dataType: "JSON",
			success: function(response) {
				if (response != 0) {
					$(response).each(function(i, v) {
						$("#kode_dokumen").append(`
                    <option value="${v.id}">${v.kode}</option>
                    `)
					});
				}
			}
		})
	}

	function getPrinciple(value) {

		if (value != "") {
			$.ajax({
				type: "POST",
				url: "<?= base_url('WMS/TindakLanjutHasilStockOpname/getPrincipleByID') ?>",
				data: {
					value
				},
				dataType: "JSON",
				success: function(response) {
					if (response != 0) {
						$(response).each(function(i, v) {
							$("#filter_principle").append(`
                    <option value="${v.principle_id}">${v.principle_nama}</option>
                    `)
						});
					}
				}
			})
		} else {
			$('#filter_principle').empty();

			$("#filter_principle").append(`
                    <option value="">--Pilih Principle--</option>
            `)
		}

	}

	function searchFilter() {
		var tgl = $("#filter_tanggal").val();
		var perusahaan = $("#filter_pt").val();
		var principle = $("#filter_principle").val();

		requestAjax("<?= base_url('WMS/TindakLanjutHasilStockOpname/searchFilter'); ?>", {
			tgl: tgl,
			perusahaan: perusahaan,
			principle: principle,
		}, "POST", "JSON", function(response) {
			$("#tableTindakLanjutHasilStokOpname > tbody").empty();

			if ($.fn.DataTable.isDataTable('#tableTindakLanjutHasilStokOpname')) {
				$('#tableTindakLanjutHasilStokOpname').DataTable().clear();
				$('#tableTindakLanjutHasilStokOpname').DataTable().destroy();
			}

			if (response != 0) {
				var no = 1;
				$(response).each(function(i, v) {
					var button = "";

					// if (v.status == 'Completed') {
					//     button = `<a href="<?= base_url('WMS/TindakLanjutHasilStockOpname/detail/') ?>${v.tr_opname_result_id}" class="btn btn-success"><i class="fa fa-eye"></i></a>`
					// } else {
					//     button = `<a href="<?= base_url('WMS/TindakLanjutHasilStockOpname/edit/') ?>${v.tr_opname_result_id}" class="btn btn-warning"><i class="fa fa-pencil"></i></a>`
					// }

					if (v.status == 'Completed') {
						button = `<button onclick="checkLastUpdate('${v.tglUpdate}', '${v.status}', '${v.tr_opname_result_id}')" class="btn btn-success"><i class="fa fa-eye"></i></button>`
					} else {
						button = `<button onclick="checkLastUpdate('${v.tglUpdate}', '${v.status}', '${v.tr_opname_result_id}')" class="btn btn-warning"><i class="fa fa-edit"></i></button>`
					}

					$("#tableTindakLanjutHasilStokOpname > tbody").append(`
                    <tr>
                    <td class="text-center">${no++}</td>
                    <td class="text-center">${v.tgl}</td>
                    <td class="text-center">${v.tr_opname_result_kode}</td>
                    <td class="text-center">${v.client_wms_nama}</td>
                    <td class="text-center">${v.principle_kode}</td>
                    <td class="text-center">${button}</td>
                    </tr>
                    `)
				});

				$("#tableTindakLanjutHasilStokOpname").DataTable({
					lengthMenu: [
						[10, 25, 50, 100],
						[10, 25, 50, 100]
					]
				});
			} else {
				$("#tableTindakLanjutHasilStokOpname > tbody").append(`
                <tr>
                    <td colspan="6" class="text-danger text-center">
                        Data Kosong
                    </td>
                </tr>
                `)
			}
		})
	}

	function checkLastUpdate(tglUpdate, status, tr_opname_result_id) {
		$.ajax({
			type: "POST",
			url: "<?= base_url('WMS/TindakLanjutHasilStockOpname/checkLastUpdate') ?>",
			data: {
				tglUpdate: tglUpdate,
				status: status,
				tr_opname_result_id: tr_opname_result_id
			},
			dataType: "JSON",
			success: function(response) {
				if (response == 400) {
					return messageNotSameLastUpdated();
				} else {
					if (status == 'Completed') {
						location.href = "<?= base_url('WMS/TindakLanjutHasilStockOpname/detail/') ?>" + tr_opname_result_id + "";
					} else {
						location.href = "<?= base_url('WMS/TindakLanjutHasilStockOpname/edit/') ?>" + tr_opname_result_id + "";
					}
				}
			}
		})
	}

	function handlerGetDataOpnameByKode(value) {
		if (value == "") {
			message("Error!", "Kode Dokumen tidak boleh kosong!", "error");
			removeField();
			return false;
		} else {
			$("#btnSaveDataOpname").attr("disabled", false);

			$.ajax({
				type: "POST",
				url: "<?= base_url('WMS/TindakLanjutHasilStockOpname/getDataOpnameByKode') ?>",
				data: {
					value
				},
				dataType: "JSON",
				success: function(response) {
					if (response.header != 0) {
						$("#penanggung_jawab").val(response.header.penanggung_jawab);
						$("#tgl").val(response.header.tanggal);
						$("#perusahaan").val(response.header.perusahaan);
						$("#principle").val(response.header.principle);
						$("#jenis_stock").val(response.header.jenis_stock);
						$("#tipe_stock_opname").val(response.header.tipe_stock_opname);
						$("#unit_cabang").val(response.header.unit_cabang);
						$("#area_opname").val(response.header.area_opname);
						$("#status").val(response.header.status);
						$("#keterangan").html(response.header.keterangan);
						$("#trOpnameID").val(response.header.id);
					}

					if (response.detail != 0) {
						$("#tableSKUOpname > tbody").empty();
						$("#jmlDataDetail").val(response.detail.length);

						$(response.detail).each(function(i, v) {
							$("#tableSKUOpname > tbody").append(`
                            <tr>
                                <td class="text-center">${i + 1}</td>
                                <td class="text-center">${v.sku_kode}</td>
                                <td class="text-center">${v.sku_nama_produk}</td>
                                <td class="text-center">${v.sku_satuan}</td>
                                <td class="text-center">${v.sku_actual}</td>
                                <td class="text-center">${v.sku_qty_sistem}</td>
                                <td class="text-center">${v.selisih}<input type="hidden" data-sku="${v.sku_stock_id}_${v.sku_id}" name="selisihQty" id="selisihQty-${i}" value="${v.selisih}" /></td>
                                <td class="text-center"><input class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" name="qtyInvoice" id="qtyInvoice" value="" placeholder="0" /></td>
                                <td class="text-center"><input class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" name="qtyPemutihan" id="qtyPemutihan" value="" placeholder="0" /></td>
                                <td class="text-center"><input class="form-control" onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" name="qtyOpnameUlang" id="qtyOpnameUlang" value="" placeholder="0" /></td>
                            </tr>
                            `)
						})

						$("#showDetailTable").show('slow');
					}
				}
			})
		}
	}

	function handlerSaveDataOpname(mode) {
		// var trOpnameID = $("#trOpnameID").val();
		var arr_chk = [];
		var boolean = true;
		// var jmlAwalChk = parseInt($("#jmlDataDetail").val(), 10);
		// var jmlEndChk = $(".jmlChk:checked").length;

		$("#tableSKUOpname > tbody > tr").each(function() {
			var qtyActual = parseInt($(this).find("td:eq(4)").text(), 10);
			var qtySistem = parseInt($(this).find("td:eq(5)").text(), 10);
			var selisihAwal = parseInt($(this).find("td:eq(6)").text(), 10);
			var dataSku = $(this).find("input[type='hidden'][name='selisihQty']").attr('data-sku').split("_");
			var qtyInvoice = $(this).find("input[type='text'][name='qtyInvoice']").val();
			var qtyPemutihan = $(this).find("input[type='text'][name='qtyPemutihan']").val();
			var qtyOpnameUlang = $(this).find("input[type='text'][name='qtyOpnameUlang']").val();

			if (qtyInvoice == "") {
				qtyInvoice = 0;
			}

			if (qtyPemutihan == "") {
				qtyPemutihan = 0;
			}

			if (qtyOpnameUlang == "") {
				qtyOpnameUlang = 0;
			}

			if (mode == 'konfirmasi') {
				var qtyAll = parseInt(qtyInvoice, 10) + parseInt(qtyPemutihan, 10) + parseInt(qtyOpnameUlang, 10);

				if (selisihAwal != qtyAll) {
					message("Peringatan!", "Jumlah Qty tidak boleh melebihi atau kurang dari selisih", "warning");
					boolean = false;
					return false;
				}
			}

			arr_chk.push({
				sku_stock_id: dataSku[0],
				sku_id: dataSku[1],
				qtyActual: qtyActual,
				qtySistem: qtySistem,
				selisihAwal: selisihAwal,
				qtyInvoice: parseInt(qtyInvoice, 10),
				qtyPemutihan: parseInt(qtyPemutihan, 10),
				qtyOpnameUlang: parseInt(qtyOpnameUlang, 10)
			});

		})

		// for (let i = 0; i < jmlAwalChk; i++) {
		//     var checkJml = $(".jmlChk-" + i + ":checked").length;

		//     if (checkJml == 0) {
		//         message("Peringatan!", "Periksa Kembali List SKU !!", "warning");
		//         return false;
		//     }

		// }

		// if (jmlAwalChk != jmlEndChk) {
		//     message("Error!", "Periksa Kembali List SKU !!", "error");
		//     return false;
		// } else {

		// }

		// var checkedElements = $('.jmlChk:checked');

		// for (let i = 0; i < jmlAwalChk; i++) {
		//     var qtyInvoice = $("#qtyInvoice-" + i + "").val();
		//     var qtyPemutihan = $("#qtyPemutihan-" + i + "").val();
		//     var qtyOpnameUlang = $("#qtyOpnameUlang-" + i + "").val();
		//     var selisihQtyAwal = parseInt($("#selisihQty-" + i + "").val(), 10);

		//     if (qtyInvoice == "" || qtyPemutihan == "" || qtyOpnameUlang == "") {
		//         message("Peringatan!", "Form input Qty tidak boleh kosong!", "warning");
		//         boolean = false;
		//         return false;
		//     }

		//     if (typeof qtyInvoice === 'undefined') {
		//         qtyInvoice = 0;
		//     }

		//     if (typeof qtyPemutihan === 'undefined') {
		//         qtyPemutihan = 0;
		//     }

		//     if (typeof qtyOpnameUlang === 'undefined') {
		//         qtyOpnameUlang = 0;
		//     }

		//     var qtyAll = parseInt(qtyInvoice, 10) + parseInt(qtyPemutihan, 10) + parseInt(qtyOpnameUlang, 10)

		//     if (selisihQtyAwal != qtyAll) {
		//         message("Peringatan!", "Jumlah Qty tidak boleh melebihi atau kurang dari selisih", "warning");
		//         boolean = false;
		//         return false;
		//     }

		//     var statusInvoice = 0;
		//     var statusPemutihan = 0;
		//     var statusOpnameUlang = 0;

		//     if (parseInt(qtyInvoice, 10) > 0) {
		//         statusInvoice = 1;
		//     }

		//     if (parseInt(qtyPemutihan, 10) > 0) {
		//         statusPemutihan = 1;
		//     }

		//     if (parseInt(qtyOpnameUlang, 10) > 0) {
		//         statusOpnameUlang = 1;
		//     }

		//     var dataSku = $("#selisihQty-" + i + "").attr('data-sku').split("_");

		//     arr_chk.push({
		//         sku_stock_id: dataSku[0],
		//         sku_id: dataSku[1],
		//         statusInvoice: statusInvoice,
		//         statusPemutihan: statusPemutihan,
		//         statusOpnameUlang: statusOpnameUlang,
		//         qtyInvoice: parseInt(qtyInvoice, 10),
		//         qtyPemutihan: parseInt(qtyPemutihan, 10),
		//         qtyOpnameUlang: parseInt(qtyOpnameUlang, 10)
		//     });
		// }

		// $(checkedElements).each(function(i) {
		//     var qtyInvoice = $("#qtyInvoice-" + i + "").val();
		//     var qtyPemutihan = $("#qtyPemutihan-" + i + "").val();
		//     var qtyOpnameUlang = $("#qtyOpnameUlang-" + i + "").val();
		//     var selisihQtyAwal = parseInt($("#selisihQty-" + i + "").val(), 10);

		//     if (qtyInvoice == "" || qtyPemutihan == "" || qtyOpnameUlang == "") {
		//         message("Peringatan!", "Form input Qty tidak boleh kosong!", "warning");
		//         boolean = false;
		//         return false;
		//     }

		//     if (typeof qtyInvoice === 'undefined') {
		//         qtyInvoice = 0;
		//     }

		//     if (typeof qtyPemutihan === 'undefined') {
		//         qtyPemutihan = 0;
		//     }

		//     if (typeof qtyOpnameUlang === 'undefined') {
		//         qtyOpnameUlang = 0;
		//     }

		//     var qtyAll = parseInt(qtyInvoice, 10) + parseInt(qtyPemutihan, 10) + parseInt(qtyOpnameUlang, 10)
		//     console.log(selisihQtyAwal, qtyAll, i);
		//     if (selisihQtyAwal != qtyAll) {
		//         message("Peringatan!", "Jumlah Qty tidak boleh melebihi atau kurang dari selisih", "warning");
		//         boolean = false;
		//         return false;
		//     }
		// });

		// $(checkedElements).each(function(i) {
		//     var value = $(this).val().split("_");
		//     var status = $(this).attr('data-status');

		//     var idInput = $(this).attr('getIdInput');
		//     var valueInput = $("#" + idInput + "").val();
		//     var selisihQty = $("#selisihQty-" + i + "").val();

		//     if (valueInput == "") {
		//         message("Peringatan!", "Form input Qty tidak boleh kosong!", "warning");
		//         boolean = false;
		//         return false;
		//     } else if (selisihQty != valueInput) {
		//         console.log(valueInput, selisihQty);
		//         message("Peringatan!", "Jumlah Qty tidak boleh melebihi atau kurang dari selisih", "warning");
		//         boolean = false;
		//         return false;
		//     } else {
		//         arr_chk.push({
		//             sku_stock_id: value[0],
		//             sku_id: value[1],
		//             status: status,
		//             qty: parseInt(valueInput, 10)
		//         });
		//     }
		// });

		// console.log(arr_chk);
		// return false;

		if (boolean) {
			messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((result) => {
				if (result.value == true) {
					requestAjax("<?= base_url('WMS/TindakLanjutHasilStockOpname/saveDataOpname'); ?>", {
						mode: mode,
						id: typeof $("#trOpnameID").val() == 'undefined' ? $("#trOpnameResultID").val() : $("#trOpnameID").val(),
						arr_chk: arr_chk,
						tglUpdate: $("#lastUpdate").val()
					}, "POST", "JSON", function(response) {
						if (response.status == 400) {
							return messageNotSameLastUpdated('WMS/TindakLanjutHasilStockOpname/TindakLanjutHasilStockOpnameMenu');
						}

						if (response.status == 200) {
							message_topright("success", "Data berhasil disimpan");
							setTimeout(() => {
								location.href = "<?= base_url('WMS/TindakLanjutHasilStockOpname/TindakLanjutHasilStockOpnameMenu') ?>";
							}, 2000);
						} else {
							message_topright("error", "Data gagal disimpan");
						}
					}, "#btnSaveDataOpname")
				}
			});
		}
	}

	// function chkAll(status, mode) {
	//     var jmlAwalChk = parseInt($("#jmlDataDetail").val(), 10);

	//     if (status == 'invoice') {
	//         if (mode == true) {
	//             // $("input[type='checkbox'][name='chkAllPemutihan']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkAllOpnameUlang']").prop('checked', false);

	//             $("input[type='checkbox'][name='chkInvoice']").prop('checked', true);
	//             // $("input[type='checkbox'][name='chkPemutihan']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkOpnameUlang']").prop('checked', false);

	//             for (let index = 0; index < jmlAwalChk; index++) {
	//                 $("#inputInvoice-" + index + "").html(`
	//             <input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control qtyInvoice" name="qtyInvoice-${index}" id="qtyInvoice-${index}" value="" placeholder="Qty Invoice">
	//             `)
	//             }
	//         } else {
	//             // $("input[type='checkbox'][name='chkAllPemutihan']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkAllOpnameUlang']").prop('checked', false);

	//             $("input[type='checkbox'][name='chkInvoice']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkPemutihan']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkOpnameUlang']").prop('checked', false);

	//             $(".inputInvoice").empty();
	//         }
	//     }

	//     if (status == 'pemutihan') {
	//         if (mode == true) {
	//             // $("input[type='checkbox'][name='chkAllInvoice']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkAllOpnameUlang']").prop('checked', false);

	//             $("input[type='checkbox'][name='chkPemutihan']").prop('checked', true);
	//             // $("input[type='checkbox'][name='chkInvoice']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkOpnameUlang']").prop('checked', false);

	//             for (let index = 0; index < jmlAwalChk; index++) {
	//                 $("#inputPemutihan-" + index + "").html(`
	//             <input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control qtyPemutihan" name="qtyPemutihan-${index}" id="qtyPemutihan-${index}" value="" placeholder="Qty Pemutihan">
	//             `)

	//             }
	//         } else {
	//             // $("input[type='checkbox'][name='chkAllInvoice']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkAllOpnameUlang']").prop('checked', false);

	//             // $("input[type='checkbox'][name='chkInvoice']").prop('checked', false);
	//             $("input[type='checkbox'][name='chkPemutihan']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkOpnameUlang']").prop('checked', false);

	//             $(".inputPemutihan").empty();
	//         }
	//     }

	//     if (status == 'opnameUlang') {
	//         if (mode == true) {
	//             // $("input[type='checkbox'][name='chkAllInvoice']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkAllPemutihan']").prop('checked', false);

	//             $("input[type='checkbox'][name='chkOpnameUlang']").prop('checked', true);
	//             // $("input[type='checkbox'][name='chkInvoice']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkPemutihan']").prop('checked', false);

	//             for (let index = 0; index < jmlAwalChk; index++) {
	//                 $("#inputOpnameUlang-" + index + "").html(`
	//             <input onkeypress="return event.charCode >= 48 && event.charCode <= 57"  type="text" class="form-control qtyOpnameUlang" name="qtyOpnameUlang-${index}" id="qtyOpnameUlang-${index}" value="" placeholder="Qty Opname Ulang">
	//             `)
	//             }

	//         } else {
	//             // $("input[type='checkbox'][name='chkAllInvoice']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkAllPemutihan']").prop('checked', false);

	//             // $("input[type='checkbox'][name='chkInvoice']").prop('checked', false);
	//             // $("input[type='checkbox'][name='chkPemutihan']").prop('checked', false);
	//             $("input[type='checkbox'][name='chkOpnameUlang']").prop('checked', false);

	//             $(".inputOpnameUlang").empty();
	//         }
	//     }
	// }

	// function chkSingle(index, status, mode) {
	//     if (status == 'invoice') {
	//         if (mode == true) {
	//             $("#inputInvoice-" + index + "").html(`
	//             <input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control qtyInvoice" data-status="invoice" name="qtyInvoice-${index}" id="qtyInvoice-${index}" value="" placeholder="Qty Invoice">
	//             `)

	//             $("input[type='checkbox'][name='chkAllInvoice']").prop('checked', false);
	//         } else {
	//             $("#inputInvoice-" + index + "").html(``)

	//             $("input[type='checkbox'][name='chkAllInvoice']").prop('checked', false);
	//         }
	//     }

	//     if (status == 'pemutihan') {
	//         if (mode == true) {
	//             $("#inputPemutihan-" + index + "").html(`
	//             <input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control qtyPemutihan" data-status="pemutihan" name="qtyPemutihan-${index}" id="qtyPemutihan-${index}" value="" placeholder="Qty Pemutihan">
	//             `)

	//             $("input[type='checkbox'][name='chkAllPemutihan']").prop('checked', false);
	//         } else {
	//             $("#inputPemutihan-" + index + "").html(``)

	//             $("input[type='checkbox'][name='chkAllPemutihan']").prop('checked', false);
	//         }
	//     }

	//     if (status == 'opnameUlang') {
	//         if (mode == true) {
	//             $("#inputOpnameUlang-" + index + "").html(`
	//             <input onkeypress="return event.charCode >= 48 && event.charCode <= 57" type="text" class="form-control qtyOpnameUlang" data-status="opnameUlang" name="qtyOpnameUlang-${index}" id="qtyOpnameUlang-${index}" value="" placeholder="Qty Opname Ulang">
	//             `)

	//             $("input[type='checkbox'][name='chkAllOpnameUlang']").prop('checked', false);
	//         } else {
	//             $("#inputOpnameUlang-" + index + "").html(``)

	//             $("input[type='checkbox'][name='chkAllOpnameUlang']").prop('checked', false);
	//         }
	//     }

	//     // if (status == 'invoice') {
	//     //     $("input[type='checkbox'][name='chkAllInvoice']").prop('checked', false);
	//     //     $("input[type='checkbox'][name='chkAllPemutihan']").prop('checked', false);
	//     //     $("input[type='checkbox'][name='chkAllOpnameUlang']").prop('checked', false);

	//     //     $("input[type='checkbox'][id='chkPemutihan-" + index + "']").prop('checked', false);
	//     //     $("input[type='checkbox'][id='chkOpnameUlang-" + index + "']").prop('checked', false);
	//     // }

	//     // if (status == 'pemutihan') {
	//     //     $("input[type='checkbox'][name='chkAllInvoice']").prop('checked', false);
	//     //     $("input[type='checkbox'][name='chkAllPemutihan']").prop('checked', false);
	//     //     $("input[type='checkbox'][name='chkAllOpnameUlang']").prop('checked', false);

	//     //     $("input[type='checkbox'][id='chkInvoice-" + index + "']").prop('checked', false);
	//     //     $("input[type='checkbox'][id='chkOpnameUlang-" + index + "']").prop('checked', false);
	//     // }

	//     // if (status == 'opnameUlang') {
	//     //     $("input[type='checkbox'][name='chkAllInvoice']").prop('checked', false);
	//     //     $("input[type='checkbox'][name='chkAllPemutihan']").prop('checked', false);
	//     //     $("input[type='checkbox'][name='chkAllOpnameUlang']").prop('checked', false);

	//     //     $("input[type='checkbox'][id='chkInvoice-" + index + "']").prop('checked', false);
	//     //     $("input[type='checkbox'][id='chkPemutihan-" + index + "']").prop('checked', false);
	//     // }
	// }

	function removeField() {
		$("#btnSaveDataOpname").attr("disabled", true);

		$("#penanggung_jawab").val("");
		$("#tgl").val("");
		$("#perusahaan").val("");
		$("#principle").val("");
		$("#jenis_stock").val("");
		$("#tipe_stock_opname").val("");
		$("#unit_cabang").val("");
		$("#area_opname").val("");
		$("#status").val("");

		$("#showDetailTable").hide('slow');
		$("#tableSKUOpname > tbody").empty();
	}
</script>