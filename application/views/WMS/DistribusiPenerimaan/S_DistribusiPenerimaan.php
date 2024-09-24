<script>
	const html5QrCode = new Html5Qrcode("preview");
	var camera2 = "";
	loadingBeforeReadyPage()
	$(document).ready(function() {
		select2();
		$(document).on("input", ".numeric", function(event) {
			this.value = this.value.replace(/[^0-9]/g, '');
		});

		// $("#simpan").attr("disabled", true);

		$("#list_data_hasil_scan").DataTable({
			columnDefs: [{
				sortable: false,
				targets: [0, 1, 2, 3, 4, 5]
			}],
			lengthMenu: [
				[10, 25, 50, 100, -1],
				[10, 25, 50, 100, 'All']
			],
		});
	});

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

	function select2() {
		$(".select2").select2({
			width: "100%"
		});
	}

	$("#no_penerimaan").on("change", function() {
		let id = $(this).val();
		//ajax request for get data
		$.ajax({
			url: "<?= base_url('WMS/DistribusiPenerimaan/get_data_by_no_penerimaan'); ?>",
			type: "POST",
			data: {
				id: id
			},
			dataType: "JSON",
			beforeSend: () => {
				Swal.fire({
					title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
					showConfirmButton: false,
					width: 250
				});
			},
			success: function(data) {
				$("#gudang_asal").empty();
				let tgl = data.tgl_btb.split(" ");
				$("#surat_jalan").val(data.sj_kode);
				$("#principle").val(data.principle_id);
				$("#surat_jalan_eksternal").val(data.sj_no);
				$("#tipe_penerimaan").val(data.tipe);
				$("#tgl_btb").val(tgl[0]);
				$("#expedisi").val(data.eks_kode + " - " + data.eks_nama);
				$("#no_kendaraan").val(data.nopol);
				$("#nama_pengemudi").val(data.pengemudi);
				$("#gudang_asal").html("<option value='" + data.gudang_id + "' selected>" + data.gudang + "</option>");
				$("#checker").html("<option value='" + data.cheker_id + "' selected>" + data.checker + "</option>");
				$('#list_data_hasil_scan').fadeOut("slow", function() {
					$(this).hide();
				}).fadeIn("slow", function() {
					$(this).show();
					get_data_dpdt();
				});
			},
			complete: () => {
				swal.close();
			}
		});
	});

	function get_data_dpdt() {
		let no_penerimaan = $("#no_penerimaan").val();
		$.ajax({
			url: "<?= base_url('WMS/DistribusiPenerimaan/get_data_dpdt'); ?>",
			type: "POST",
			data: {
				id: no_penerimaan
			},
			dataType: "JSON",
			async: false,
			success: function(data) {
				$("#list_data_hasil_scan > tbody").empty();
				let no = 1;
				$.each(data.data, function(i, v) {
					let str = "";
					if (v.status == 0) {
						str += "<span class='btn btn-danger' style='cursor: context-menu;padding:0px'>Belum Divalidasi</span>";
					} else {
						str += "<span class='btn btn-success' style='cursor: context-menu;padding:0px'>Valid </span>";
					}
					$("#list_data_hasil_scan > tbody").append(`
            <tr>
                <td class="text-center">${no++} <input type="hidden" class="form-control" name="id_detail[]" id="id_detail" value="` + v.id + `"/></td>
                <td class="text-center">${v.kode}</td>
                <td class="text-center">${v.nama}</td>
                <td>
                  <select class="form-control select2 gudang" id="gudang_` + i + `" name="gudang"></select>
                </td>
                <td class="text-center">${str}</td>
                <td class="text-center">
                    <button type="button" data-toggle="tooltip" data-placement="top" data-id="` + v.id + `" title="detail pallet" class="btn btn-primary detail_pallet"><i class="fas fa-eye"></i> Detail</button>
                </td>
            </tr>
        `);
				});
				select2();

				for (i = 0; i < data.data.length; i++) {
					var id = data.data[i].gudang_id;
					// console.log(pallet_jenis_id);

					$("#gudang_" + i).empty();

					$("#gudang_" + i).append('<option value="">--Pilih Gudang Tujuan--</option>');
					if (data.depo != 0) {
						for (x = 0; x < data.depo.length; x++) {
							depo_detail_id = data.depo[x].depo_detail_id;
							depo_detail_nama = data.depo[x].depo_detail_nama;
							if (id == depo_detail_id) {
								$("#gudang_" + i).append('<option value="' + depo_detail_id + '" selected>' + depo_detail_nama + '</option>');
							} else {
								$("#gudang_" + i).append('<option value="' + depo_detail_id + '">' + depo_detail_nama + '</option>');
							}

						}
					}
				}

				update_gudang_tujuan_by_id();
			}
		});
	}

	function update_gudang_tujuan_by_id() {
		$("#list_data_hasil_scan > tbody tr").each(function(i, v) {
			let currentRow = $(this);
			let id = currentRow.find("td:eq(0) input[type='hidden']").val();
			let gudang = currentRow.find("td:eq(3) select");
			gudang.change(function() {
				let gudang_tujuan_id = $(this).children("option").filter(":selected").val();
				$.ajax({
					url: "<?= base_url('WMS/DistribusiPenerimaan/update_gudang_tujuan_by_id'); ?>",
					type: "POST",
					data: {
						id: id,
						gudang_tujuan_id: gudang_tujuan_id
					},
					dataType: "JSON",
					success: function(data) {}
				});
			});
		});
	}

	$(document).on("change", "#check_scan", function(e) {
		if (e.target.checked) {
			$("#start_scan").hide();
			$("#input_manual").show();
			$("#stop_scan").hide();
			$("#preview").hide();
			$("#txtpreviewscan").val("");
			$("#select_kamera").empty();
		} else {
			$("#start_scan").show();
			$("#input_manual").hide();
			$("#close_input").hide();
			$("#preview_input_manual").hide();
			$("#kode_barcode").val("");
			$("#txtpreviewscan").val("");
			$('#myFileInput').val("");
			$('#show-file').empty();
		}
	});

	$(document).on("click", "#start_scan", function() {
		let no_penerimaan = $("#no_penerimaan").val();
		if (no_penerimaan == "") {
			message("Error!", "Silahkan pilih No. Penerimaan terlebih dahulu sebelum melakukan scan", "error");
			return false;
		} else {
			$("#start_scan").hide();
			$("#stop_scan").show();
			Swal.fire({
				title: '<span ><i class="fa fa-spinner fa-spin"></i> Memuat Kamera</span>',
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
					$("#preview").show();

					//handle succes scan
					const qrCodeSuccessCallback = (decodedText, decodedResult) => {
						let temp = decodedText;
						if (temp != "") {
							html5QrCode.pause();
							scan(no_penerimaan, decodedText);
						}
					};

					const scan = (no_penerimaan, decodedText) => {
						//check ada apa kosong di tabel distribusi_penerimaan_detail_temp
						//jika ada update statusnya jadi 1
						$.ajax({
							url: "<?= base_url('WMS/DistribusiPenerimaan/check_kode_pallet_by_no_penerimaan'); ?>",
							type: "POST",
							data: {
								po_id: no_penerimaan,
								kode_pallet: decodedText
							},
							dataType: "JSON",
							success: function(data) {
								$("#txtpreviewscan").val(data.kode);
								if (data.type == 200) {
									Swal.fire("Success!", data.message, "success").then(function(result) {
										if (result.value == true) {
											html5QrCode.resume();
										}
									});
									$('#list_data_hasil_scan').fadeOut("slow", function() {
										$(this).hide();
									}).fadeIn("slow", function() {
										$(this).show();
										get_data_dpdt();
									});
								} else if (data.type == 201) {
									Swal.fire("Error!", data.message, "error").then(function(result) {
										if (result.value == true) {
											html5QrCode.resume();
										}
									});
								} else {
									Swal.fire("Info!", data.message, "info").then(function(result) {
										if (result.value == true) {
											html5QrCode.resume();
										}
									});
								}
							},
						});
					}

					// atur kotak nng kini, set 0.sekian pngin jlok brpa persen
					const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
						let minEdgePercentage = 0.9; // 90%
						let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
						let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
						return {
							width: qrboxSize,
							height: qrboxSize
						};
					}

					const config = {
						fps: 10,
						qrbox: qrboxFunction,
						// rememberLastUsedCamera: true,
						// Only support camera scan type.
						supportedScanTypes: [Html5Qrcode.SCAN_TYPE_CAMERA]
					};
					Html5Qrcode.getCameras().then(devices => {
						if (devices && devices.length) {
							$.each(devices, function(i, v) {
								$("#select_kamera").append(`
              <input class="checkbox-tools" type="radio" name="tools" value="${v.id}" id="tool-${i}">
              <label class="for-checkbox-tools" for="tool-${i}">
                ${v.label}
              </label>
            `)
							});

							$("#select_kamera :input[name='tools']").each(function(i, v) {
								if (i == 0) {
									$(this).attr('checked', true);
								}
							});

							let cameraId = devices[0].id;
							// html5QrCode.start(devices[0]);
							$('input[name="tools"]').on('click', function() {
								// alert($(this).val());
								html5QrCode.stop();
								html5QrCode.start({
									deviceId: {
										exact: $(this).val()
									}
								}, config, qrCodeSuccessCallback);
							});
							//start scan
							html5QrCode.start({
								deviceId: {
									exact: cameraId
								}
							}, config, qrCodeSuccessCallback);

						}
					}).catch(err => {
						message("Error!", "Kamera tidak ditemukan", "error");
						$("#start_scan").show();
						$("#stop_scan").hide();
					});
				}
			});
		}
	});

	$(document).on("click", "#input_manual", function() {
		let no_penerimaan = $("#no_penerimaan").val();
		if (no_penerimaan == "") {
			message("Error!", "Silahkan pilih No. Penerimaan terlebih dahulu sebelum melakukan input manual", "error");
			return false;
		} else {
			$("#input_manual").hide();
			$("#close_input").show();
			$("#preview_input_manual").show();
		}
	});

	function previewFile() {
		const file = document.querySelector('input[id=myFileInput]').files[0];
		const reader = new FileReader();

		$('#show-file').empty();
		reader.addEventListener("load", function() {
			$('#show-file').append(`
        <a href="${reader.result}" data-lightbox="image-1">
          <img src="${reader.result}" style="cursor: pointer" class="img-fluid" width="300" height="350" />
        </a>
      `);
			// preview.src = reader.result;
		}, false);

		if (file) {
			reader.readAsDataURL(file);
		}
	}

	$(document).on("click", "#check_kode", () => {
		let no_penerimaan = $("#no_penerimaan").val();
		let barcode = $("#kode_barcode").val();
		let attachment = $("#myFileInput")

		if (barcode == "") {
			message("Error!", "Kode Pallet tidak boleh kosong", "error");
			return false;
		} else if (attachment.val() == "") {
			message("Error!", "Bukti cek fisik tidak boleh kosong", "error");
			return false;
		} else {
			let new_form = new FormData();
			let files = attachment[0].files[0];
			new_form.append('po_id', no_penerimaan);
			new_form.append('kode_pallet', barcode);
			new_form.append('file', files);

			$.ajax({
				url: "<?= base_url('WMS/DistribusiPenerimaan/check_kode_pallet_by_no_penerimaan'); ?>",
				type: "POST",
				data: new_form,
				contentType: false,
				processData: false,
				dataType: "JSON",
				beforeSend: () => {
					$("#loading_cek_manual").show();
				},
				success: function(data) {
					console.log(data);
					$("#txtpreviewscan").val(data.kode);
					if (data.type == 200) {
						message("Success!", data.message, "success");
						$("#kode_barcode").val("");
						$('#myFileInput').val("");
						$('#show-file').empty();
						$('#list_data_hasil_scan').fadeOut("slow", function() {
							$(this).hide();
						}).fadeIn("slow", function() {
							$(this).show();
							get_data_dpdt();
						});
					} else if (data.type == 201) {
						message("Error!", data.message, "error");
					} else {
						message("Info!", data.message, "info");
					}
				},
				complete: () => {
					$("#loading_cek_manual").hide();
				},
			});
		}
	})

	$(document).on("click", "#stop_scan", function() {
		remove_stop_scan();
	});

	$(document).on("click", "#close_input", function() {
		remove_close_input();
	});

	function remove_stop_scan() {
		$("#start_scan").show();
		$("#stop_scan").hide();
		$("#preview").hide();
		$("#txtpreviewscan").val("");
		$("#select_kamera").empty();
		html5QrCode.stop();
	}

	function remove_close_input() {
		$("#input_manual").show();
		$("#close_input").hide();
		$("#preview_input_manual").hide();
		$("#kode_barcode").val("");
		$("#txtpreviewscan").val("");
		$('#myFileInput').val("");
		$('#show-file').empty();
	}

	$(document).on("click", ".detail_pallet", function() {
		let id = $(this).attr('data-id');
		$("#modal_view_detail").modal('show');
		//ajax request detail
		$.ajax({
			url: "<?= base_url('WMS/DistribusiPenerimaan/get_data_detail_pallet'); ?>",
			type: "POST",
			data: {
				id: id
			},
			dataType: "JSON",
			async: false,
			success: function(data) {
				if (data != null) {
					if ($.fn.DataTable.isDataTable('#table_list_detail')) {
						$('#table_list_detail').DataTable().destroy();
					}
					$('#table_list_detail > tbody').empty();
					let no = 1;
					$.each(data, function(i, v) {
						$("#table_list_detail > tbody").append(`
                <tr>
                    <td class="text-center">${no++}</td>
                    <td class="text-center">${v.principle}</td>
                    <td class="text-center">${v.sku_kode}</td>
                    <td>${v.sku_nama}</td>
                    <td class="text-center">${v.sku_kemasan}</td>
                    <td class="text-center">${v.sku_satuan}</td>
                    <td class="text-center">${v.ed}</td>
                    <td class="text-center">${v.tipe}</td>
                    <td class="text-center">${v.qty}</td>
                </tr>
            `);
					});
				} else {
					$("#table_list_detail > tbody").html(`<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
				}

				$('#table_list_detail').DataTable({
					lengthMenu: [
						[10, 25, 50, 100, -1],
						[10, 25, 50, 100, 'All']
					],
				});
			},
		});
	});

	$(document).on("click", ".btn_close_detail", function() {
		$("#modal_view_detail").modal('hide');
	});

	$(document).on("click", "#simpan", function() {
		let tgl = $("#tgl").val();
		let no_penerimaan = $("#no_penerimaan").val();
		let surat_jalan = $("#surat_jalan").val();
		let principle = $("#principle").val();
		let surat_jalan_eksternal = $("#surat_jalan_eksternal").val();
		let tipe_penerimaan = $("#tipe_penerimaan").val();
		let tgl_btb = $("#tgl_btb").val();
		let expedisi = $("#expedisi").val();
		let no_kendaraan = $("#no_kendaraan").val();
		let nama_pengemudi = $("#nama_pengemudi").val();
		let status = $("#status").val();
		let keterangan = $("#keterangan").val();
		let gudang_asal = $("#gudang_asal").val();
		let checker = $("#checker option:selected").text();

		if (no_penerimaan == "") {
			message("Error!", "No. Penerimaan tidak boleh kosong", "error");
			// $("#error").val("0");
			return false;
		} else {
			let count_pallet = $("#list_data_hasil_scan > tbody tr").length;
			if (count_pallet == 0) {
				// $("#error").val("0");
				message("Error!", "Pallet kosong, silahkan tambah pallet di menu penerimaan barang", "error");
				return false;
			} else {
				$("#list_data_hasil_scan > tbody tr").each(function() {
					let gudang = $(this).find("td:eq(3) select").children("option").filter(":selected");
					let is_valid = $(this).find("td:eq(4)").text().replace(/\s+/g, '');
					if (gudang.val() == "") {
						message("Error!", "Gudang Tujuan tidak boleh kosong", "error");
					} else if (is_valid != "Valid") {
						// $("#error").val("0");
						message("Error!", "Pallet masih ada yang belum tervalidasi, silahkan cek terlebih dahulu!", "error");
						return false;
					} else {
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
									url: "<?= base_url('WMS/DistribusiPenerimaan/save_data'); ?>",
									type: "POST",
									data: {
										tgl: tgl,
										principle: principle,
										tipe_penerimaan: tipe_penerimaan,
										no_penerimaan: no_penerimaan,
										status: status,
										keterangan: keterangan,
										gudang_asal: gudang_asal,
										checker: checker
									},
									dataType: "JSON",
									success: function(data) {
										// console.log(data);
										if (data == 1) {
											message_topright("success", "Data berhasil disimpan");
											setTimeout(() => {
												location.href = "<?= base_url('WMS/DistribusiPenerimaan/DistribusiPenerimaanMenu') ?>";
											}, 500);
										} else {
											message_topright("error", "Data gagal disimpan");
										}
									}
								});
							}
						});

					}
				});
			}
		}
	});

	document.getElementById('kode_barcode').addEventListener('keyup', function() {
		const typeScan = $("#tempValForScan").val();
		if (this.value != "") {
			fetch('<?= base_url('WMS/DistribusiPenerimaan/getKodeAutoComplete?params='); ?>' + this.value)
				.then(response => response.json())
				.then((results) => {
					if (!results[0]) {
						document.getElementById('table-fixed').style.display = 'none';
					} else {
						let data = "";
						// console.log(results);
						results.forEach(function(e, i) {
							data += `
												<tr onclick="getNoSuratJalanEks('${e.kode}')" style="cursor:pointer">
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

	function getNoSuratJalanEks(data) {
		$("#kode_barcode").val(data);
		document.getElementById('table-fixed').style.display = 'none'

		$("#check_kode").click();
		// handlereRquestToCheckKodePallet(str, typeScan, skuId, index);
	}
</script>