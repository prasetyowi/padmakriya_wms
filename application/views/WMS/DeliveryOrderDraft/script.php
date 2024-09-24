<script type="text/javascript">
	var jumlah_sku = 0;
	var layanan = "";
	let arr_sku = [];
	let arr_header = [];
	let arr_detail = [];
	let arr_detail2 = [];
	let arr_checkbox_sku = [];
	var cek_qty = 0;
	let cek_tipe_stock = 0;
	var arr_do_draft = [];
	var index_arr_detail2 = 0;
	var table_error;
	var principle;

	loadingBeforeReadyPage()
	jQuery(document).ready(function($) {
		principle = $("#filter-principle").val();
		table_error = $("#table-pesan-error").DataTable({
			"paging": false,
			"dom": "frt",
			"ajax": {
				url: '<?= base_url("WMS/Distribusi/DeliveryOrderDraft/get_delivery_order_draft_detail_msg") ?>',
				method: "GET",
				data: function(data) {
					data.principle = $("#filter-principle").val()
				},
				complete: function() {
					setTimeout(() => {
						table_error.columns.adjust();
					}, 300);
				}
			},
			"columns": [{
					data: null,
					render: function(data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					},
					orderable: false
				},
				{
					data: 'delivery_order_draft_kode'
				},
				{
					data: 'outlet'
				},
				{
					data: 'segmen'
				},
				{
					data: 'principle'
				},
				{
					data: 'sku_kode'
				},
				{
					data: 'sku_nama_produk'
				},
				{
					data: 'flag'
				},
				{
					data: 'pesan_error'
				}
			],
			rowsGroup: [
				1,
				2,
				3,
				4,
				5,
				6,
				7,
				8
			],
			"columnDefs": [{
					targets: [0],
					class: 'text-center va-m-i',
					width: '10%'
				},
				{
					targets: [1],
					class: 'text-center va-m-i'
				},
				{
					targets: [2],
					class: 'text-center va-m-i'
				},
				{
					targets: [3],
					class: 'text-center va-m-i'
				},
				{
					targets: [4],
					class: 'text-center va-m-i'
				},
				{
					targets: [5],
					class: 'text-center va-m-i'
				},
				{
					targets: [6],
					class: 'text-center va-m-i'
				},
				{
					targets: [7],
					class: 'text-center va-m-i'
				},
				{
					targets: [8],
					class: 'text-center text-danger va-m-i'
				}
			],
		})
	})

	$(document).ready(
		function() {
			$('.select2').select2({
				width: '100%'
			});
			GetKonversiFromDO();
			getSegment();
			// delete_delivery_order_draft_detail_msg();
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

			if ($('#filter-do-date2').length > 0) {
				$('#filter-do-date2').daterangepicker({
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

			if ($('#filter-do-pembatalan-date2').length > 0) {
				$('#filter-do-pembatalan-date2').daterangepicker({
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

			if ($('#filter-tgl-ubah-ke-draft').length > 0) {
				$('#filter-tgl-ubah-ke-draft').daterangepicker({
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

			if ($("#deliveryorderdraft-client_wms_id").val() != "") {
				const urlSearchParams = new URLSearchParams(window.location.search);
				const currentUrl = window.location.href.split('/')[7];

				if (currentUrl == undefined || currentUrl == 'undefined' || currentUrl == 'index') {

				} else {
					initDataCustomer();
				}

				// initDataSKU();
			}
			// getSegment();

			<?php if ($act == "kirim ulang") { ?>
				<?php foreach ($DODetail as $value) : ?>
					arr_sku.push("<?= $value['sku_id'] ?>")
				<?php endforeach; ?>
			<?php } ?>
		}
	);

	$('input[type=radio][id=deliveryorderdraft-delivery_order_draft_tipe_pembayaran]').on('change', function() {
		if (this.value == "0") {
			$('#input_tunai').val('');
			$('#input_tunai').prop('readonly', false);
		} else {
			$('#input_tunai').prop('readonly', true);
			$('#input_tunai').val('');
		}
	})

	function previewFile() {
		const file = document.querySelector('input[type=file]').files[0];
		const reader = new FileReader();

		$('#show-file').empty();
		$('#hide-file').hide();
		reader.addEventListener("load", function() {
			let result = reader.result.split(',');
			$('#show-file').append(`
		<div class="col-md-6">
                    <a class="text-primary klik-saya" style="cursor:pointer" data-url="${result[1]}" data-ex="${result[0]}">${file.name}</a>
                </div>
                <div class="col-md-6">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="hapus file" class="btndeletefile" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button>
                </div>
				`);
		}, false);

		if (file) {
			reader.readAsDataURL(file);
		}
	}

	$(document).on('click', '.klik-saya', function() {
		let url = $(this).data('url');
		let ex = $(this).data('ex');
		let pdfWindow = window.open("")
		pdfWindow.document.write(
			"<iframe width='100%' height='100%' src='" + ex + ", " + encodeURI(url) + "'></iframe>"
		)
	});

	$(document).on('click', '.btndeletefile', function() {
		$('#show-file').empty();
		$("#file").val("");
		// $(".file-upload-wrapper").attr("data-text", "Select your file!");
	});

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

	function getSegment() {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetSegment1') ?>",
			dataType: "JSON",
			success: function(response) {
				// console.log(response);
				$("#filter-segment1").empty()
				$("#filter-segment1").append(
					'<option value=""><label name="CAPTION-SEMUA">Semua</label></option>');
				$.each(response, function(i, v) {
					let segment_id = v.client_pt_segmen_id
					let segment_nama = v.client_pt_segmen_nama

					$("#filter-segment1").append('<option value="' + segment_id + '">' + segment_nama +
						'</option>');
				})
			}
		})
	}

	$(document).on('change', '#filter-segment1', function() {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetSegment2') ?>",
			data: {
				id: $(this).val()
			},
			dataType: "JSON",
			success: function(response) {
				$("#filter-segment2").empty()
				$("#filter-segment2").append(
					'<option value=""><label name="CAPTION-SEMUA">Semua</label></option>');
				$.each(response, function(i, v) {
					let segment_id = v.client_pt_segmen_id
					let segment_nama = v.client_pt_segmen_nama

					$("#filter-segment2").append('<option value="' + segment_id + '">' +
						segment_nama + '</option>');
				})
			}
		})
	})
	$(document).on('change', '#filter-segment2', function() {

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetSegment3') ?>",
			data: {
				id: $(this).val()
			},
			dataType: "JSON",
			success: function(response) {
				$("#filter-segment3").empty()
				$("#filter-segment3").append(
					'<option value=""><label name="CAPTION-SEMUA">Semua</label></option>');
				$.each(response, function(i, v) {
					let segment_id = v.client_pt_segmen_id
					let segment_nama = v.client_pt_segmen_nama

					$("#filter-segment3").append('<option value="' + segment_id + '">' +
						segment_nama + '</option>');
				})
			}
		})
	})

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

	$('#select-skui-pembongkaran').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxSKUPembongkaran"]:checkbox').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxSKUPembongkaran"]:checkbox').each(function() {
				this.checked = false;
			});
		}
	});

	$('#select-do').click(function(event) {
		if (this.checked) {
			// Iterate each checkbox
			$('[name="CheckboxDO"]:checkbox:not(:disabled)').each(function() {
				this.checked = true;
			});
		} else {
			$('[name="CheckboxDO"]:checkbox:not(:disabled)').each(function() {
				this.checked = false;
			});
		}
	});

	function getCustomer() {
		$("#cek_customer").val(0);

		$(".factory-name").html('');
		$(".factory-address").html('');
		$(".factory-area").html('');

		$("#deliveryorderdraft-delivery_order_draft_ambil_nama").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_alamat").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_provinsi").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_kota").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_kecamatan").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_kelurahan").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_kodepos").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_telepon").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_area").val('');

		// $("#deliveryorderdraft-delivery_order_draft_ambil_area").trigger('change');
		$(".customer-name").html('');
		$(".customer-address").html('');
		$(".customer-area").html('');

		$("#deliveryorderdraft-delivery_order_draft_kirim_nama").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_alamat").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_provinsi").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_kota").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_kecamatan").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_kelurahan").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_kodepos").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_telepon").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_area").val('');

		// $("#modal-factory").modal('hide');
		// $("#modal-customer").modal('hide');

		initDataCustomer();
		reset_table_sku();
		initDataSKU();
	}

	function cek_checkbox_sku() {
		$("#table-sku-delivery-only > tbody tr").each(function() {
			var sku_id = $(this).find("td:eq(13) input[type='hidden']").val();
			arr_checkbox_sku.push(
				sku_id
			);
		})

		for (let i = 0; i < arr_checkbox_sku.length; i++) {
			$("input[name='CheckboxSKU'][value=" + arr_checkbox_sku[i] + "]").prop('checked',
				true);
		}
	}

	$("#btn-choose-prod-delivery").on("click", function() {
		// alert('awok');

		initDataSKU();
		Swal.fire({
			title: 'Memuat data!',
			text: 'Mohon tunggu sebentar...',
			icon: 'warning',
			timer: 2000,
			showCancelButton: false,
			showConfirmButton: false
		}).then(
			function() {},
			// handling the promise rejection
			function(dismiss) {
				if (dismiss === 'timer') {
					//console.log('your message')
				}
			}
		)

		//Alert sukses memuat data
		setTimeout(function() {
			Swal.fire({
				title: 'Sukses!',
				text: 'Berhasil memuat data.',
				icon: 'success',
				timer: 3000,
				showCancelButton: false,
				showConfirmButton: true
			}).then(
				function() {},
				// handling the promise rejection
				function(dismiss) {
					if (dismiss === 'timer') {
						//console.log('your message')
					}
				}
			)
		}, 1000);
		// var jumlah = $('input[type=checkbox][name=CheckboxSKU]').length;
	});

	$("#btn-search-sku").on("click", function() {
		initDataSKU();
	});

	$("#btn-search-customer").on("click", function() {
		getCustomer();

		// if ($("#filter-area").val() != "") {
		// 	getCustomer();
		// } else {
		// 	var alert = GetLanguageByKode('CAPTION-PILIHAREA');
		// 	message("Error!", alert, "error");
		// }
	});

	$("#btn-search-factory").on("click", function() {
		getCustomer();
	});

	$("#deliveryorderdraft-client_wms_id").on("change", function() {
		var perusahaan = $("#deliveryorderdraft-client_wms_id").val();

		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetPerusahaanById') ?>",
			data: {
				id: perusahaan
			},
			dataType: "JSON",
			success: function(response) {
				$("#deliveryorderdraft-client_wms_alamat").val('');

				if (response != 0) {
					$.each(response, function(i, v) {
						$("#deliveryorderdraft-client_wms_alamat").val(v.client_wms_alamat);
					});
				} else {
					$("#deliveryorderdraft-client_wms_alamat").val('');
				}
			}
		});

	});

	$("#btn-search-data-do-draft").on("click", function() {
		GetDODraftByFilter();
	});

	function GetKonversiFromDO() {
		$.ajax({
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/get_tr_konversi_sku_from_do_by_depo') ?>",
			dataType: "JSON",
			success: function(response) {

				$("#persetujuanpembongkaran-tr_konversi_sku_id").val(response.tr_konversi_sku_id);

				$('#CAPTION-JUMLAHPEMBONGKARAN').fadeOut("slow", function() {
					$(this).hide();
					$("#CAPTION-JUMLAHPEMBONGKARAN").html('');
				}).fadeIn("slow", function() {
					$(this).show();
					$("#CAPTION-JUMLAHPEMBONGKARAN").html('');
					$("#CAPTION-JUMLAHPEMBONGKARAN").append(response.konversi.length);

					if (response.konversi != "") {
						document.getElementById("btnpembongkaranbarang").classList.add('btn-danger');
						document.getElementById("btnpembongkaranbarang").classList.remove(
							'btn-primary');
					} else {
						document.getElementById("btnpembongkaranbarang").classList.add('btn-primary');
						document.getElementById("btnpembongkaranbarang").classList.remove('btn-danger');
					}
				});
			}
		});

	}

	function GetDODraftByFilter() {
		var idx = 0;

		const load_start = () => {

			Swal.fire({
				title: 'Loading ...',
				html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
				timerProgressBar: false,
				showConfirmButton: false
			});

			$("#btnapprovdodraft").prop("disabled", true);
			$("#btn-search-data-do-draft").prop("disabled", true);
			$('#select-do').prop("checked", false);

		};

		const proses = () => {

			$('#table_list_data_do_draft').DataTable().clear();
			$('#table_list_data_do_draft').DataTable().destroy();

			$('#table_list_data_do_draft').DataTable({
				"scrollX": true,
				'paging': true,
				'searching': false,
				'ordering': false,
				'processing': true,
				'serverSide': true,
				'ajax': {
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetDeliveryOrderDraftByFilter') ?>",
					type: "POST",
					dataType: "json",
					data: {
						tgl: $("#filter-do-date").val(),
						do_no: $("#filter-do-number").val(),
						customer: $("#filter-customer").val(),
						alamat: $("#filter-address").val(),
						tipe_pembayaran: $("#filter-payment-type").val(),
						tipe_layanan: $("#filter-service-type").val(),
						status: $("#filter-status").val(),
						tipe: $("#filter-do-type").val(),
						segmen1: $("#filter-segment1").val(),
						segmen2: $("#filter-segment2").val(),
						segmen3: $("#filter-segment3").val(),
						sales: $("#filter-sales").val(),
						principle: $("#filter-principle").val(),
						soEksternal: $("#filter-so-eksternal").val(),
						is_priority: $("#filter-priority").val(),
						so: $("#filter-so").val(),
						status_pending: $("#filter-status-pending").val(),
					}
				},
				"drawCallback": function(response) {
					// Here the response
					var resp = response.json;
					$("#jml_do").val(resp.recordsTotal);
				},
				'columns': [{
						render: function(v) {
							if (v.delivery_order_draft_status == "Approved") {
								return `<input type="checkbox" name="CheckboxDOApproved" id="check-do-${v.idx}" value="${v.delivery_order_draft_id}" disabled checked>
								<input type="hidden" id="item-${v.idx}-ListDODraft-delivery_order_draft_status" value="${v.delivery_order_draft_status}">
								<input type="hidden" id="counter" value="${v.idx}">`;
							} else if (v.delivery_order_draft_status == "Rejected") {
								return `<input type="checkbox" name="CheckboxDORejected" id="check-do-${v.idx}" value="${v.delivery_order_draft_id}" disabled>
								<input type="hidden" id="item-${v.idx}-ListDODraft-delivery_order_draft_status" value="${v.delivery_order_draft_status}">
								<input type="hidden" id="counter" value="${v.idx}">`;
							} else if (v.delivery_order_draft_status == "canceled") {
								return `<input type="checkbox" name="CheckboxDOCanceled" id="check-do-${v.idx}" value="${v.delivery_order_draft_id}" disabled>
								<input type="hidden" id="item-${v.idx}-ListDODraft-delivery_order_draft_status" value="${v.delivery_order_draft_status}">
								<input type="hidden" id="counter" value="${v.idx}">`;
							} else {
								return `<input ${v.delivery_order_draft_is_pending == 1 ? 'disabled' : ''} type="checkbox" name="CheckboxDO" id="check-do-${v.idx}" value="${v.delivery_order_draft_id}">
								<input type="hidden" id="item-${v.idx}-ListDODraft-delivery_order_draft_status" value="${v.delivery_order_draft_status}">
								<input type="hidden" id="counter" value="${v.idx}">`;
							}
						},
						data: null,
						targets: 0
					},
					{
						data: 'delivery_order_draft_tgl_rencana_kirim'
					},
					{
						render: function(v) {
							if (v.sales_order_kode == '') {
								return `<div style="text-align: center;">${v.delivery_order_draft_kode}</div>`;
							} else {
								return `<div style="text-align: center;">${v.delivery_order_draft_kode}</br><div style="border-top: 1px solid #ccc;  margin-top: 10px; margin-bottom: 10px"></div>${v.sales_order_kode}</div>`;
							}
						},
						data: null
						// data: 'delivery_order_draft_kode'
					},
					// {
					// 	data: 'sales_order_kode'
					// },
					{
						data: 'sales_order_no_po'
					},
					{
						render: function(v) {
							if (v.karyawan_nama == '') {
								return `<div style="text-align: center;">${v.principle_kode}</div>`;
							} else {
								return `<div style="text-align: center;">${v.principle_kode}</br><div style="border-top: 1px solid #ccc;  margin-top: 10px; margin-bottom: 10px"></div>${v.karyawan_nama}</div>`;
							}
						},
						data: null
						// data: 'principle_kode'
					},
					// {
					// 	data: 'karyawan_nama'
					// },
					{
						render: function(v) {
							if (v.delivery_order_draft_kirim_alamat == '') {
								return `<div style="text-align: center;">${v.delivery_order_draft_kirim_nama}</div>`;
							} else {
								return `<div style="text-align: center;">${v.delivery_order_draft_kirim_nama}</br><div style="border-top: 1px solid #ccc;  margin-top: 10px; margin-bottom: 10px"></div>${v.delivery_order_draft_kirim_alamat}</div>`;
							}
						},
						data: null
						// data: 'delivery_order_draft_kirim_nama'
					},
					// {
					// 	data: 'delivery_order_draft_kirim_alamat'
					// },
					{
						data: 'delivery_order_draft_kirim_area'
					},
					{
						data: 'tipe_delivery_order_alias'
					},
					{
						data: 'umur_so'
					},
					{
						render: function(v) {
							return Math.round(v.delivery_order_draft_nominal_tunai);
						},
						data: null,
						targets: 9
					},
					{
						data: 'delivery_order_draft_status'
					},
					{
						data: 'client_pt_segmen_nama1'
					},
					{
						data: 'client_pt_segmen_nama2'
					},
					{
						data: 'client_pt_segmen_nama3'
					},
					{
						data: 'delivery_order_draft_is_prioritas'
					},
					{
						render: function(v) {
							var no = 0;
							var a = '';
							var cek = '';
							if (v.delivery_order_draft_status == "Approved") {
								a = `<a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=${v.delivery_order_draft_id}" class="btn btn-success btn-small"><i class="fa fa-search"></i></a><br>`;
								// if (v.tipe_delivery_order_alias != 'Retur' && v.tipe_delivery_order_alias != 'DO Reschedule') {
								// 	if (v.is_ada_fdjr == 0) {
								// 		a += `<button class="btn btn-danger btn-small" onclick="handlerUpdateToDraft('${v.delivery_order_draft_id}')" title="Update Menjadi Draft"><i class="fa fa-pencil"></i></button>`;
								// 	}
								// }
								return a;
							} else if (v.delivery_order_draft_status == "Rejected") {
								return `<a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=${v.delivery_order_draft_id}" class="btn btn-danger btn-small"><i class="fa fa-search"></i></a>`;
							} else if (v.delivery_order_draft_status == "canceled") {
								return `<a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=${v.delivery_order_draft_id}" class="btn btn-success btn-small"><i class="fa fa-search"></i></a>`;
							} else {
								// return `<a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderDraft/edit/?id=${v.delivery_order_draft_id}" class="btn btn-primary btn-small"><i class="fa fa-pencil"></i></a>`;
								return `<div style="align-items: center;"><a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderDraft/edit/?id=${v.delivery_order_draft_id}" class="btn btn-primary btn-small"><i class="fa fa-pencil"></i></a></br><div style="border-top: 1px solid #ccc;  margin-top: 10px; margin-bottom: 10px"></div><input ${v.delivery_order_draft_is_pending == 1 ? 'checked' : ''} style="transform: scale(1.5); margin-right: 5px"  onchange="updateIsPending('${v.delivery_order_draft_id}', this.checked)" type="checkbox" id="check_is_pending_${v.delivery_order_draft_id}" class="check_is_pending"> <b>Pending</b></div><label style="width: 100px"></label>`;
							}
						},
						data: null,
						targets: 14
					},
				],
				"columnDefs": [{
					targets: 15,
					searchable: false,
					class: 'text-center',
				}, ],
			});
		};

		const load_end = () => {
			$("#btnapprovdodraft").prop("disabled", false);
			$("#btn-search-data-do-draft").prop("disabled", false);
			Swal.close();
		};

		load_start();

		setTimeout(() => {
			proses()
			load_end();
		}, 1000);

		CekErrorMsgDOMasal();

		// $.ajax({
		// 	type: 'POST',
		// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetDeliveryOrderDraftByFilter') ?>",
		// 	data: {
		// 		tgl: $("#filter-do-date").val(),
		// 		do_no: $("#filter-do-number").val(),
		// 		customer: $("#filter-customer").val(),
		// 		alamat: $("#filter-address").val(),
		// 		tipe_pembayaran: $("#filter-payment-type").val(),
		// 		tipe_layanan: $("#filter-service-type").val(),
		// 		status: $("#filter-status").val(),
		// 		tipe: $("#filter-do-type").val(),
		// 		segmen1: $("#filter-segment1").val(),
		// 		segmen2: $("#filter-segment2").val(),
		// 		segmen3: $("#filter-segment3").val(),
		// 	},
		// 	dataType: "JSON",
		// 	success: function(response) {

		// 		$("#table_list_data_do_draft > tbody").empty();

		// 		if ($.fn.DataTable.isDataTable('#table_list_data_do_draft')) {
		// 			$('#table_list_data_do_draft').DataTable().clear();
		// 			$('#table_list_data_do_draft').DataTable().destroy();
		// 		}

		// 		$("#jml_do").val(response.length);

		// 		if (response != 0) {
		// 			$.each(response, function(i, v) {
		// 				if (v.delivery_order_draft_status == "Approved") {
		// 					$("#table_list_data_do_draft > tbody").append(`
		// 					<tr>
		//                         <td class="text-center">
		//                             <input type="checkbox" name="CheckboxDOApproved" id="check-do-approved-${i}" value="${v.delivery_order_draft_id}" disabled checked>
		//                             <input type="hidden" id="item-${i}-ListDODraft-delivery_order_draft_status" value="${v.delivery_order_draft_status}">
		//                         </td>
		// 						<td class="text-center">${v.delivery_order_draft_tgl_rencana_kirim}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kode}</td>
		// 						<td class="text-center">${v.sales_order_kode}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
		// 						<td class="text-center" style="width:25%;">${v.delivery_order_draft_kirim_alamat}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
		// 						<td class="text-center">${v.tipe_delivery_order_nama}</td>
		//                         <td class="text-center">${Math.round(v.delivery_order_draft_nominal_tunai)}</td>
		// 						<td class="text-center">${v.delivery_order_draft_status}</td>
		// 						<td class="text-center">${v.client_pt_segmen_nama1==null?'-':v.client_pt_segmen_nama1}</td>
		// 						<td class="text-center">${v.client_pt_segmen_nama2==null?'-':v.client_pt_segmen_nama2}</td>
		// 						<td class="text-center">${v.client_pt_segmen_nama3==null?'-':v.client_pt_segmen_nama3}</td>
		// 						<td class="text-center"><a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=${v.delivery_order_draft_id}" class="btn btn-success btn-small btn-delete-sku"><i class="fa fa-search"></i></a></td>
		// 					</tr>
		// 				`);
		// 				} else if (v.delivery_order_draft_status == "Rejected") {
		// 					$("#table_list_data_do_draft > tbody").append(`
		// 					<tr>
		//                         <td class="text-center">
		//                             <input type="checkbox" name="CheckboxDORejected" id="check-do-rejected-${i}" value="${v.delivery_order_draft_id}" disabled>
		//                             <input type="hidden" id="item-${i}-ListDODraft-delivery_order_draft_status" value="${v.delivery_order_draft_status}">
		//                         </td>
		// 						<td class="text-center">${v.delivery_order_draft_tgl_rencana_kirim}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kode}</td>
		//                         <td class="text-center">${v.sales_order_kode}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
		// 						<td class="text-center" style="width:25%;">${v.delivery_order_draft_kirim_alamat}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
		// 						<td class="text-center">${v.tipe_delivery_order_nama}</td>
		//                         <td class="text-center">${Math.round(v.delivery_order_draft_nominal_tunai)}</td>
		// 						<td class="text-center">${v.delivery_order_draft_status}</td>
		// 						<td class="text-center">${v.client_pt_segmen_nama1==null?'-':v.client_pt_segmen_nama1}</td>
		// 						<td class="text-center">${v.client_pt_segmen_nama2==null?'-':v.client_pt_segmen_nama2}</td>
		// 						<td class="text-center">${v.client_pt_segmen_nama3==null?'-':v.client_pt_segmen_nama3}</td>
		// 						<td class="text-center"><a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=${v.delivery_order_draft_id}" class="btn btn-danger btn-small btn-delete-sku"><i class="fa fa-search"></i></a></td>sel
		// 					</tr>
		// 				`);
		// 				} else {
		// 					$("#table_list_data_do_draft > tbody").append(`
		// 					<tr>
		// 						<td class="text-center">
		//                             <input type="checkbox" name="CheckboxDO" id="check-do-${i}" value="${v.delivery_order_draft_id}">
		//                             <input type="hidden" id="item-${i}-ListDODraft-delivery_order_draft_status" value="${v.delivery_order_draft_status}">
		//                         </td>
		// 						<td class="text-center">${v.delivery_order_draft_tgl_rencana_kirim}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kode}</td>
		//                         <td class="text-center">${v.sales_order_kode}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kirim_nama}</td>
		// 						<td class="text-center" style="width:25%;">${v.delivery_order_draft_kirim_alamat}</td>
		// 						<td class="text-center">${v.delivery_order_draft_kirim_area}</td>
		// 						<td class="text-center">${v.tipe_delivery_order_nama}</td>
		// 						<td class="text-center">${Math.round(v.delivery_order_draft_nominal_tunai)}</td>
		// 						<td class="text-center">${v.delivery_order_draft_status}</td>
		// 						<td class="text-center">${v.client_pt_segmen_nama1==null?'-':v.client_pt_segmen_nama1}</td>
		// 						<td class="text-center">${v.client_pt_segmen_nama2==null?'-':v.client_pt_segmen_nama2}</td>
		// 						<td class="text-center">${v.client_pt_segmen_nama3==null?'-':v.client_pt_segmen_nama3}</td>
		// 						<td class="text-center"><a href="<?= base_url() ?>WMS/Distribusi/DeliveryOrderDraft/edit/?id=${v.delivery_order_draft_id}" class="btn btn-primary btn-small btn-delete-sku"><i class="fa fa-pencil"></i></a></td>
		// 					</tr>
		// 				`);

		// 				}
		// 			});
		// 			$('#table_list_data_do_draft').DataTable({
		// 				'lengthMenu': [
		// 					[100, 200, 250, -1],
		// 					[100, 200, 250, 'All']
		// 				],
		// 				'ordering': false,
		// 				"scrollX": true
		// 			});
		// 		} else {
		// 			$("#table_list_data_do_draft > tbody").append(`
		// 						<tr>
		// 							<td colspan="14" class="text-danger text-center">
		// 								Data Kosong
		// 							</td>
		// 						</tr>
		// 			`);
		// 		}

		// 		$("#loadingviewdodraft").hide();
		// 	}
		// });

	}

	function updateIsPending(delivery_order_draft_id, checked) {
		if (checked == true) {
			var text = 'Data akan dirubah menjadi pending!'
			var boolean = false;
		} else {
			var text = 'Data akan dirubah menjadi tidak pending!'
			var boolean = true;
		}
		Swal.fire({
			title: "Apakah anda yakin?",
			text: text,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/updateIsPending') ?>",
					data: {
						delivery_order_draft_id: delivery_order_draft_id,
						is_pending: checked == true ? 1 : 0
					},
					dataType: "JSON",
					success: function(response) {
						if (response == 1) {
							message("Success!", "Data Berhasil Dirubah!", "success");

							setTimeout(() => {
								GetDODraftByFilter();
							}, 500);
						} else {
							message("Error!", "Data Gagal Dirubah!", "error");
							return false;

						}

					}
				})
			} else {
				$(`#check_is_pending_${delivery_order_draft_id}`).prop('checked', boolean);
			}
		})
	}

	const handlerUpdateToDraft = (id) => {
		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Data Akan Dirubah Menjadi Draft!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Simpan",
			cancelButtonText: "Tidak, Tutup"
		}).then((result) => {
			if (result.value == true) {
				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/updatedraft') ?>",
					data: {
						id
					},
					dataType: "JSON",
					success: function(response) {
						if (response == 1) {
							message("Error!", "Data Berhasil Dirubah Menjadi Draft!", "error");

							setTimeout(() => {
								GetDODraftByFilter();
							}, 500);
						} else {
							message("Error!", "Data Gagal Dikembalikan!", "error");
							return false;

						}

					}
				})
			}
		})

	}

	function getSelectedCustomer(customer, tipe_layanan) {

		var perusahaan = $("#deliveryorderdraft-client_wms_id").val();

		$(".factory-name").html('');
		$(".factory-address").html('');
		$(".factory-area").html('');

		$("#deliveryorderdraft-delivery_order_draft_ambil_nama").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_alamat").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_provinsi").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_kota").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_kecamatan").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_kelurahan").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_kodepos").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_telepon").val('');
		$("#deliveryorderdraft-delivery_order_draft_ambil_area").val('');

		$(".customer-name").html('');
		$(".customer-address").html('');
		$(".customer-area").html('');

		$("#deliveryorderdraft-delivery_order_draft_kirim_nama").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_alamat").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_provinsi").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_kota").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_kecamatan").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_kelurahan").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_kodepos").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_telepon").val('');
		$("#deliveryorderdraft-delivery_order_draft_kirim_area").val('');

		$("#modal-factory").modal('hide');
		$("#modal-customer").modal('hide');

		$("#cek_customer").val(0);

		$("#table-sku-delivery-only > tbody").empty();

		if (tipe_layanan == "Pickup Only") {
			$.ajax({
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetSelectedPrinciple') ?>",
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

							$("#deliveryorderdraft-pabrik_id").val(v.principle_id);
							$("#deliveryorderdraft-delivery_order_draft_ambil_nama").val(v
								.principle_nama);
							$("#deliveryorderdraft-delivery_order_draft_ambil_alamat").val(v
								.principle_alamat);
							$("#deliveryorderdraft-delivery_order_draft_ambil_provinsi").val(v
								.principle_propinsi);
							$("#deliveryorderdraft-delivery_order_draft_ambil_kota").val(v
								.principle_kota);
							$("#deliveryorderdraft-delivery_order_draft_ambil_kecamatan").val(v
								.principle_kecamatan);
							$("#deliveryorderdraft-delivery_order_draft_ambil_kelurahan").val(v
								.principle_kelurahan);
							$("#deliveryorderdraft-delivery_order_draft_ambil_kodepos").val(v
								.principle_kodepos);
							$("#deliveryorderdraft-delivery_order_draft_ambil_telepon").val(v
								.principle_telepon);
							$("#deliveryorderdraft-delivery_order_draft_ambil_area").val(v
								.area_nama);
						});
						arr_sku = [];
						$("#table-sku-delivery-only > tbody").empty();
						initDataSKU();

						if ($("#deliveryorderdraft-pabrik_id").val() != "") {
							$("#cek_customer").val(1);
						}
					});
				}
			});
		} else {
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

							$("#deliveryorderdraft-client_pt_id").val(v.client_pt_id);
							$("#deliveryorderdraft-delivery_order_draft_kirim_nama").val(v
								.client_pt_nama);
							$("#deliveryorderdraft-delivery_order_draft_kirim_nama").val(v
								.client_pt_nama);
							$("#deliveryorderdraft-delivery_order_draft_kirim_alamat").val(v
								.client_pt_alamat);
							$("#deliveryorderdraft-delivery_order_draft_kirim_provinsi").val(v
								.client_pt_propinsi);
							$("#deliveryorderdraft-delivery_order_draft_kirim_kota").val(v
								.client_pt_kota);
							$("#deliveryorderdraft-delivery_order_draft_kirim_kecamatan").val(v
								.client_pt_kecamatan);
							$("#deliveryorderdraft-delivery_order_draft_kirim_kelurahan").val(v
								.client_pt_kelurahan);
							$("#deliveryorderdraft-delivery_order_draft_kirim_kodepos").val(v
								.client_pt_kodepos);
							$("#deliveryorderdraft-delivery_order_draft_kirim_telepon").val(v
								.client_pt_telepon);
							// $("#deliveryorderdraft-delivery_order_draft_kirim_area").val(v
							// 	.area_nama).trigger('change');
							$("#deliveryorderdraft-delivery_order_draft_kirim_area option[value='" + v.area_nama + "']").prop("selected", true).trigger('change');
						});

						arr_sku = [];
						$("#table-sku-delivery-only > tbody").empty();
						initDataSKU();

						if ($("#deliveryorderdraft-client_pt_id").val() != "") {
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
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_id" value="${v.sku_id}" class="sku-id" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-gudang_id" class="gudang-id" value="${v.gudang_id}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-gudang_detail_id" class="gudang-detail-id" value="${v.gudang_detail_id}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_harga_satuan" class="sku-harga-satuan" value="${v.sku_harga_jual}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_disc_percent" class="sku-disc-percent" value="0" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_disc_rp" class="sku-disc-rp" value="0" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_harga_nett" class="sku-harga-nett" value="${v.sku_harga_jual}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_weight" class="sku-weight" value="${v.sku_weight_product}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_weight_unit" class="sku-weight-unit" value="${v.sku_weight_product_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_length" class="sku-length" value="${v.sku_length}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_length_unit" class="sku-length-unit" value="${v.sku_length_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_width" class="sku-width" value="${v.sku_width}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_width_unit" class="sku-width-unit" value="${v.sku_width_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_height" class="sku-height" value="${v.sku_height}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_height_unit" class="sku-height-unit" value="${v.sku_height_unit}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_volume" class="sku-volume" value="${v.sku_volume}" />
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_volume_unit" class="sku-volume-unit" value="${v.sku_volume_unit}" />
								</td>
								<td class="text-center">
									<span class="sku-kode-label">${v.sku_kode}</span>
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_kode" class="form-control sku-kode" value="${v.sku_kode}" />
								</td>
								<td class="text-center" style="display: none"></td>
								<td class="text-center">
									<span class="sku-nama-produk-label">${v.sku_nama_produk}</span>
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_nama_produk" class="form-control sku-nama-produk" value="${v.sku_nama_produk}" />
								</td>
								<td class="text-center">
									<span class="sku-kemasan-label">${v.sku_kemasan}</span>
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_kemasan" class="form-control sku-kemasan" value="${v.sku_kemasan}" />
								</td>
								<td class="text-center">
									<span class="sku-satuan-label">${v.sku_satuan}</span>
									<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-sku_satuan" class="form-control sku-satuan" value="${v.sku_satuan}" />
								</td>
								<td class="text-center" style="width:10%">
									<select id="item-${i}-DeliveryOrderDetailDraft-sku_tipe_stock" class="form-control sku-tipe_stock">
									<option value="">**Pilih**</option>
									<?php if (isset($Lokasi)) { ?>
										<?php for ($x = 0; $x < count($Lokasi); $x++) { ?>
											<option value="<?= $Lokasi[$x]->nama ?>"><?= $Lokasi[$x]->nama ?></option>
										<?php } ?>
									<?php } ?>
									</select>
								</td>
								<td class="text-center" style="width:10%">
									<select id="item-${i}-DeliveryOrderDetailDraft-sku_request_expdate" class="form-control sku-request-expdate" onchange="reqFilter(this.value,'${i}')">
										<option value="0">Tidak</option>
										<option value="1">Ya</option>
									</select>
								</td>
								<td class="text-center" style="width:10%">
									<select id="item-${i}-DeliveryOrderDetailDraft-sku_filter_expdate" class="form-control sku-filter-expdate" disabled>
										<option value="">**Pilih**</option>
									</select>
								</td>
								<td class="text-center" style="width:10%">
									<select id="item-${i}-DeliveryOrderDetailDraft-sku_filter_expdatebulan" class="form-control sku-filter-expdatebulan" disabled>
									<option value="">**Pilih**</option>
									<?php if (isset($Bulan)) { ?>
									<?php for ($x = 0; $x < count($Bulan); $x++) { ?>
										<option value="<?= $Bulan[$x] ?>"><?= $Bulan[$x] ?></option>
									<?php } ?>
									<?php } ?>
									</select>
								</td>
								<td class="text-center"><input type="text" id="item-${i}-DeliveryOrderDetailDraft-sku_keterangan" class="form-control sku-keterangan" value="" /></td>
								<td class="text-center"><input min="0" type="number" id="item-${i}-DeliveryOrderDetailDraft-sku_qty" class="form-control sku-qty" value="0" onchange="UpdateTotalHarga('${i}')"/></td>
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

		var perusahaan = $("#deliveryorderdraft-client_wms_id").val();
		var tipe_layanan = document.querySelector(
			'input[id="deliveryorderdraft-delivery_order_draft_tipe_layanan"]:checked').value;
		var tipe_pembayaran = document.querySelector(
			'input[id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran"]:checked').value;

		if (tipe_layanan == "Pickup Only") {
			$("#panel-customer").hide();
			$("#panel-factory").show();

			var nama = $("#filter-principle-name").val();
			var alamat = $("#filter-principle-address").val();
			var telp = $("#filter-principle-phone").val();
			var area = $("#filter-area-principle").val();

			if (area != "") {

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetFactoryByTypePelayanan') ?>",
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
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-principle_id" value="${v.principle_id}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-principle_propinsi" value="${v.principle_propinsi}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-principle_kota" value="${v.principle_kota}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-principle_kecamatan" value="${v.principle_kecamatan}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-principle_kelurahan" value="${v.principle_kelurahan}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-principle_kodepos" value="${v.principle_kodepos}" />
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-area_id" value="${v.area_id}" />
									</td>
									<td class="text-center">
										<span class="client-pt-nama-label">${v.principle_nama}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-principle_nama" class="form-control client-pt-nama" value="${v.principle_nama}" />
									</td>
									<td class="text-center">
										<span class="client-pt-alamat-label">${v.principle_alamat}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-principle_alamat" class="form-control client-pt-alamat" value="${v.principle_alamat}" />
									</td>
									<td class="text-center">
										<span class="client-pt-telepon-label">${v.principle_telepon}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-principle_telepon" class="form-control client-pt-telepon" value="${v.principle_telepon}" />
									</td>
									<td class="text-center">
										<span class="area-nama-label">${v.area_nama}</span>
										<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-area_nama" class="form-control area-nama" value="${v.area_nama}" />
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

			}

		} else {
			$("#panel-customer").show();
			$("#panel-factory").hide();

			var nama = $("#filter-client-name").val();
			var alamat = $("#filter-client-address").val();
			var telp = $("#filter-client-phone").val();
			var area = $("#filter-area").val();

			// if (area != "") {

			// $.ajax({
			// 	type: 'POST',
			// 	url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetCustomerByTypePelayanan') ?>",
			// 	data: {
			// 		perusahaan: perusahaan,
			// 		tipe_pembayaran: tipe_pembayaran,
			// 		tipe_layanan: tipe_layanan,
			// 		nama: nama,
			// 		alamat: alamat,
			// 		telp: telp,
			// 		area: area
			// 	},
			// 	dataType: "JSON",
			// 	success: function(response) {

			// 		$("#table-customer > tbody").empty();

			// 		if (response != 0) {
			// 			$.each(response, function(i, v) {

			// 				$("#table-customer > tbody").append(`
			// 				<tr id="row-${i}">
			// 					<td style="display: none">
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_id" value="${v.client_pt_id}" />
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_propinsi" value="${v.client_pt_propinsi}" />
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_kota" value="${v.client_pt_kota}" />
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_kecamatan" value="${v.client_pt_kecamatan}" />
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_kelurahan" value="${v.client_pt_kelurahan}" />
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_kodepos" value="${v.client_pt_kodepos}" />
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-area_id" value="${v.area_id}" />
			// 					</td>
			// 					<td class="text-center">
			// 						<span class="client-pt-nama-label">${v.client_pt_nama}</span>
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_nama" class="form-control client-pt-nama" value="${v.client_pt_nama}" />
			// 					</td>
			// 					<td class="text-center">
			// 						<span class="client-pt-alamat-label">${v.client_pt_alamat}</span>
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_alamat" class="form-control client-pt-alamat" value="${v.client_pt_alamat}" />
			// 					</td>
			// 					<td class="text-center">
			// 						<span class="client-pt-telepon-label">${v.client_pt_telepon}</span>
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_telepon" class="form-control client-pt-telepon" value="${v.client_pt_telepon}" />
			// 					</td>
			// 					<td class="text-center">
			// 						<span class="area-nama-label">${v.area_nama}</span>
			// 						<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-area_nama" class="form-control area-nama" value="${v.area_nama}" />
			// 					</td>
			// 					<td class="text-center">
			// 						<button class="btn btn-primary btn-small btn-select-customer" onclick="getSelectedCustomer('${v.client_pt_id}','${tipe_layanan}')"><i class="fa fa-angle-down"></i></button>
			// 					</td>
			// 				</tr>
			// 			`);
			// 			});
			// 		} else {
			// 			$("#table-customer > tbody").append(`
			// 				<tr>
			// 					<td colspan="5" class="text-danger text-center">
			// 						Data Kosong
			// 					</td>
			// 				</tr>
			// 		`);
			// 		}
			// 	}
			// });

			if ($.fn.DataTable.isDataTable('#table-customer')) {
				$('#table-customer').DataTable().clear();
				$('#table-customer').DataTable().destroy();
			}

			$('#table-customer').DataTable({
				// "scrollX": true,
				'paging': true,
				'searching': true,
				'ordering': true,
				'order': [
					[0, 'asc']
				],
				'lengthMenu': [
					[50, 100, 150, -1], // -1 untuk menunjukkan opsi "All"
					[50, 100, 150, "All"]
				],
				'processing': true,
				'serverSide': true,
				// 'deferLoading': 0,
				'ajax': {
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetCustomerByTypePelayanan') ?>",
					type: "POST",
					dataType: "json",
					data: function(data) {
						perusahaan = perusahaan,
							tipe_pembayaran = tipe_pembayaran,
							tipe_layanan = tipe_layanan,
							nama = nama,
							alamat = alamat,
							telp = telp,
							area = area
					},
					// data: {
					// 	perusahaan: perusahaan,
					// 	tipe_pembayaran: tipe_pembayaran,
					// 	tipe_layanan: tipe_layanan,
					// 	nama: nama,
					// 	alamat: alamat,
					// 	telp: telp,
					// 	area: area
					// },
					beforeSend: function() {
						Swal.fire({
							title: 'Loading ...',
							html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
							timerProgressBar: false,
							showConfirmButton: false
						});
					},
					complete: function() {
						Swal.close();
					},
				},
				'columns': [{
						render: function(data, type, row, meta) {
							var i = meta.row;
							var str = '';
							str += `<span class="client-pt-nama-label">${row.client_pt_nama}</span>
							<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_nama" class="form-control client-pt-nama" value="${row.client_pt_nama}" />`

							return str;
						},
						data: 'client_pt_nama'
					},
					{
						render: function(data, type, row, meta) {
							var i = meta.row;
							var str = '';
							str += `<span class="client-pt-alamat-label">${row.client_pt_alamat}</span>
							<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_alamat" class="form-control client-pt-alamat" value="${row.client_pt_alamat}" />`

							return str;
						},
						data: 'client_pt_alamat'
					},
					{
						render: function(data, type, row, meta) {
							var i = meta.row;
							var str = '';
							str += `<span class="client-pt-telepon-label">${row.client_pt_telepon}</span>
							<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-client_pt_telepon" class="form-control client-pt-telepon" value="${row.client_pt_telepon}" />`

							return str;
						},
						data: 'client_pt_telepon'
					},
					{
						render: function(data, type, row, meta) {
							var i = meta.row;
							var str = '';
							str += `<span class="area-nama-label">${row.area_nama}</span>
							<input type="hidden" id="item-${i}-DeliveryOrderDetailDraft-area_nama" class="form-control area-nama" value="${row.area_nama}" />`

							return str;
						},
						data: 'area_nama'
					},
					{
						render: function(data, type, row, meta) {
							var str = '';
							str += `<button class="btn btn-primary btn-small btn-select-customer" onclick="getSelectedCustomer('${row.client_pt_id}','${tipe_layanan}')"><i class="fa fa-angle-down"></i></button>`

							return str;
						},
						data: null
					},
				],
				"columnDefs": [{
						targets: 0,
						className: 'text-center'

					},
					{
						targets: 1,
						className: 'text-center'
					},
					{
						targets: 2,
						className: 'text-center'
					},
					{
						targets: 3,
						className: 'text-center'
					},
					{
						targets: 4,
						className: 'text-center',
						searchable: false
					}
				],
				initComplete: function() {
					parent_dt = $('#table-customer').closest('.dataTables_wrapper')
					parent_dt.find('.dataTables_filter').css('width', 'auto')
					var input = parent_dt.find('.dataTables_filter input').unbind(),
						self = this.api(),
						$searchButton = $('<button class="btn btn-flat btn-success btn-sm mb-0 mr-0 ml-5 btn-search-dt">')
						.html('<i class="fa fa-fw fa-search">')
						.click(function() {
							self.search(input.val()).draw();
						}),
						$clearButton = $('<button class="btn btn-flat btn-warning btn-sm mb-0 mr-0 ml-5 btn-reset-dt">')
						.html('<i class="fa fa-fw fa-recycle">')
						.click(function() {
							input.val('');
							$searchButton.click();

						})
					parent_dt.find('.dataTables_filter').append($searchButton, $clearButton);
					parent_dt.find('.dataTables_filter input').keypress(function(e) {
						var key = e.which;
						if (key == 13) {
							$searchButton.click();
							return false;
						}
					});
				},
			});

			// }
		}
	}

	function initDataSKU() {
		var client_pt = "";
		var perusahaan = $("#deliveryorderdraft-client_wms_id").val();
		var tipe_layanan = document.querySelector(
			'input[id="deliveryorderdraft-delivery_order_draft_tipe_layanan"]:checked').value;
		var tipe_pembayaran = document.querySelector(
			'input[id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran"]:checked').value;
		var sku_induk = $("#filter-sku-induk").val();
		var sku_nama_produk = $("#filter-sku-nama-produk").val();
		var sku_kemasan = $("#filter-sku-kemasan").val();
		var sku_satuan = $("#filter-sku-satuan").val();
		var principle = $("#filter-principle").val();
		var brand = $("#filter-brand").val();

		if (tipe_layanan == "Pickup Only") {
			client_pt = $("#deliveryorderdraft-pabrik_id").val();

			if (client_pt != "") {
				$("#loadingsku").show();

				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/search_filter_chosen_sku_by_pabrik') ?>",
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

							cek_checkbox_sku();
						} else {
							$("#table-sku > tbody").html(
								`<tr><td colspan="7" class="text-center text-danger">Data Kosong</td></tr>`);
						}
					}
				});
			}
		} else {
			client_pt = $("#deliveryorderdraft-client_wms_id").val();
			if (client_pt != "") {
				$("#loadingsku").show();
				$.ajax({
					type: 'POST',
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/search_filter_chosen_sku') ?>",
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

							cek_checkbox_sku();

						} else {
							$("#table-sku > tbody").html(
								`<tr><td colspan="7" class="text-center text-danger">Data Kosong</td></tr>`);
						}
					}
				});
			}
		}


	}

	$("#btnsavedodraft").on("click", function() {
		var cek_customer = $("#cek_customer").val();
		var tipe_do = $("#deliveryorderdraft-tipe_delivery_order_id").val();
		var cek_tipe = $('#deliveryorderdraft-delivery_order_draft_tipe_pembayaran:checked').val();

		var area = $("#deliveryorderdraft-delivery_order_draft_kirim_area").val();

		if (area != "") {

			if (tipe_do != "") {

				if (cek_customer > 0) {

					if (cek_tipe == 0) {
						var cek_input_tunai = $("#input_tunai").val();

						if (cek_input_tunai == '') {
							message("Error!", "Input Pembayaran tidak boleh kosong!", "error");
							return false;
						}
					}

					if (arr_sku.length > 0) {
						$("#table-sku-delivery-only > tbody tr").each(function() {
							var is_Qty = $(this).find("td:eq(11) input[type='number']");
							var is_tipe_stock = $(this).find("td:eq(6) select");
							// console.log(is_tipe_stock.val());
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

								// var sales_order_id = "";
								// var delivery_order_draft_kode = "";
								// var delivery_order_draft_yourref = "";
								// var client_wms_id = $("#deliveryorderdraft-client_wms_id").val();
								// var delivery_order_draft_tgl_buat_do = $("#deliveryorderdraft-delivery_order_draft_tgl_buat_do").val();
								// var delivery_order_draft_tgl_expired_do = $("#deliveryorderdraft-delivery_order_draft_tgl_expired_do").val();
								// var delivery_order_draft_tgl_surat_jalan = $("#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan").val();
								// var delivery_order_draft_tgl_rencana_kirim = $("#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim").val();
								// var delivery_order_draft_tgl_aktual_kirim = "";
								// var delivery_order_draft_keterangan = $("#deliveryorderdraft-delivery_order_draft_keterangan").val();
								// var delivery_order_draft_status = "Draft";
								// var delivery_order_draft_is_prioritas = 0;
								// var delivery_order_draft_is_need_packing = 0;
								// var delivery_order_draft_tipe_layanan = $("#deliveryorderdraft-delivery_order_draft_tipe_layanan").val();
								// var delivery_order_draft_tipe_pembayaran = $("#deliveryorderdraft-delivery_order_draft_tipe_pembayaran").val();
								// var delivery_order_draft_sesi_pengiriman = 0;
								// var delivery_order_draft_request_tgl_kirim = "";
								// var delivery_order_draft_request_jam_kirim = "";
								// var tipe_pengiriman_id = "";
								// var nama_tipe = "";
								// var confirm_rate = "";
								// var delivery_order_draft_reff_id = "";
								// var delivery_order_draft_reff_no = "";
								// var delivery_order_draft_total = "";
								// var unit_mandiri_id = "<?= $this->session->userdata('unit_mandiri_id') ?>";
								// var depo_id = "<?= $this->session->userdata('depo_id') ?>";
								// var client_pt_id = $("#deliveryorderdraft-client_pt_id").val();
								// var delivery_order_draft_kirim_nama = $("#deliveryorderdraft-delivery_order_draft_kirim_nama").val();
								// var delivery_order_draft_kirim_alamat = $("#deliveryorderdraft-delivery_order_draft_kirim_alamat").val();
								// var delivery_order_draft_kirim_telp = $("#deliveryorderdraft-delivery_order_draft_kirim_telepon").val();
								// var delivery_order_draft_kirim_provinsi = $("#deliveryorderdraft-delivery_order_draft_kirim_provinsi").val();
								// var delivery_order_draft_kirim_kota = $("#deliveryorderdraft-delivery_order_draft_kirim_kota").val();
								// var delivery_order_draft_kirim_kecamatan = $("#deliveryorderdraft-delivery_order_draft_kirim_kecamatan").val();
								// var delivery_order_draft_kirim_kelurahan = $("#deliveryorderdraft-delivery_order_draft_kirim_kelurahan").val();
								// var delivery_order_draft_kirim_kodepos = $("#deliveryorderdraft-delivery_order_draft_kirim_kodepos").val();
								// var delivery_order_draft_kirim_area = $("#deliveryorderdraft-delivery_order_draft_kirim_area").val();
								// var delivery_order_draft_kirim_invoice_pdf = "";
								// var delivery_order_draft_kirim_invoice_dir = "";
								// var pabrik_id = $("#deliveryorderdraft-pabrik_id").val();
								// var delivery_order_draft_ambil_nama = $("#deliveryorderdraft-delivery_order_	draft_ambil_nama").val();
								// var delivery_order_draft_ambil_alamat = $("#deliveryorderdraft-delivery_order_draft_ambil_alamat").val();
								// var delivery_order_draft_ambil_telp = $("#deliveryorderdraft-delivery_order_draft_ambil_telepon").val();
								// var delivery_order_draft_ambil_provinsi = $("#deliveryorderdraft-delivery_order_draft_ambil_provinsi").val();
								// var delivery_order_draft_ambil_kota = $("#deliveryorderdraft-delivery_order_draft_ambil_kota").val();
								// var delivery_order_draft_ambil_kecamatan = $("#deliveryorderdraft-delivery_order_draft_ambil_kecamatan").val();
								// var delivery_order_draft_ambil_kelurahan = $("#deliveryorderdraft-delivery_order_draft_ambil_kelurahan").val();
								// var delivery_order_draft_ambil_kodepos = $("#deliveryorderdraft-delivery_order_draft_ambil_kodepos").val();
								// var delivery_order_draft_ambil_area = $("#deliveryorderdraft-delivery_order_draft_ambil_area").val();
								// var delivery_order_draft_update_who = "<?= $this->session->userdata('pengguna_id') ?>";;
								// var delivery_order_draft_update_tgl = "";
								// var delivery_order_draft_approve_who = "";
								// var delivery_order_draft_approve_tgl = "";
								// var delivery_order_draft_reject_who = "";
								// var delivery_order_draft_reject_tgl = "";
								// var delivery_order_draft_reject_reason = "";
								// var tipe_delivery_order_id = $("#deliveryorderdraft-tipe_delivery_order_id").val();

								// arr_header.push({
								// 	'sales_order_id': "",
								// 	'delivery_order_draft_kode': "",
								// 	'delivery_order_draft_yourref': "",
								// 	'client_wms_id': $("#deliveryorderdraft-client_wms_id").val(),
								// 	'delivery_order_draft_tgl_buat_do': $("#deliveryorderdraft-delivery_order_draft_tgl_buat_do").val(),
								// 	'delivery_order_draft_tgl_expired_do': $("#deliveryorderdraft-delivery_order_draft_tgl_expired_do").val(),
								// 	'delivery_order_draft_tgl_surat_jalan': $("#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan").val(),
								// 	'delivery_order_draft_tgl_rencana_kirim': $("#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim").val(),
								// 	'delivery_order_draft_tgl_aktual_kirim': "",
								// 	'delivery_order_draft_keterangan': $("#deliveryorderdraft-delivery_order_draft_keterangan").val(),
								// 	'delivery_order_draft_status': "Draft",
								// 	'delivery_order_draft_is_prioritas': 0,
								// 	'delivery_order_draft_is_need_packing': 0,
								// 	'delivery_order_draft_tipe_layanan': $("#deliveryorderdraft-delivery_order_draft_tipe_layanan").val(),
								// 	'delivery_order_draft_tipe_pembayaran': $("#deliveryorderdraft-delivery_order_draft_tipe_pembayaran").val(),
								// 	'delivery_order_draft_sesi_pengiriman': 0,
								// 	'delivery_order_draft_request_tgl_kirim': "",
								// 	'delivery_order_draft_request_jam_kirim': "",
								// 	'tipe_pengiriman_id': "",
								// 	'nama_tipe': "",
								// 	'confirm_rate': "",
								// 	'delivery_order_draft_reff_id': "",
								// 	'delivery_order_draft_reff_no': "",
								// 	'delivery_order_draft_total': "",
								// 	'unit_mandiri_id': "<?= $this->session->userdata('unit_mandiri_id') ?>",
								// 	'depo_id': "<?= $this->session->userdata('depo_id') ?>",
								// 	'client_pt_id': $("#deliveryorderdraft-client_pt_id").val(),
								// 	'delivery_order_draft_kirim_nama': $("#deliveryorderdraft-delivery_order_draft_kirim_nama").val(),
								// 	'delivery_order_draft_kirim_alamat': $("#deliveryorderdraft-delivery_order_draft_kirim_alamat").val(),
								// 	'delivery_order_draft_kirim_telp': $("#deliveryorderdraft-delivery_order_draft_kirim_telepon").val(),
								// 	'delivery_order_draft_kirim_provinsi': $("#deliveryorderdraft-delivery_order_draft_kirim_provinsi").val(),
								// 	'delivery_order_draft_kirim_kota': $("#deliveryorderdraft-delivery_order_draft_kirim_kota").val(),
								// 	'delivery_order_draft_kirim_kecamatan': $("#deliveryorderdraft-delivery_order_draft_kirim_kecamatan").val(),
								// 	'delivery_order_draft_kirim_kelurahan': $("#deliveryorderdraft-delivery_order_draft_kirim_kelurahan").val(),
								// 	'delivery_order_draft_kirim_kodepos': $("#deliveryorderdraft-delivery_order_draft_kirim_kodepos").val(),
								// 	'delivery_order_draft_kirim_area': $("#deliveryorderdraft-delivery_order_draft_kirim_area").val(),
								// 	'delivery_order_draft_kirim_invoice_pdf': "",
								// 	'delivery_order_draft_kirim_invoice_dir': "",
								// 	'pabrik_id': $("#deliveryorderdraft-pabrik_id").val(),
								// 	'delivery_order_draft_ambil_nama': $("#deliveryorderdraft-delivery_order_draft_ambil_nama").val(),
								// 	'delivery_order_draft_ambil_alamat': $("#deliveryorderdraft-delivery_order_draft_ambil_alamat").val(),
								// 	'delivery_order_draft_ambil_telp': $("#deliveryorderdraft-delivery_order_draft_ambil_telepon").val(),
								// 	'delivery_order_draft_ambil_provinsi': $("#deliveryorderdraft-delivery_order_draft_ambil_provinsi").val(),
								// 	'delivery_order_draft_ambil_kota': $("#deliveryorderdraft-delivery_order_draft_ambil_kota").val(),
								// 	'delivery_order_draft_ambil_kecamatan': $("#deliveryorderdraft-delivery_order_draft_ambil_kecamatan").val(),
								// 	'delivery_order_draft_ambil_kelurahan': $("#deliveryorderdraft-delivery_order_draft_ambil_kelurahan").val(),
								// 	'delivery_order_draft_ambil_kodepos': $("#deliveryorderdraft-delivery_order_draft_ambil_kodepos").val(),
								// 	'delivery_order_draft_ambil_area': $("#deliveryorderdraft-delivery_order_draft_ambil_area").val(),
								// 	'delivery_order_draft_update_who': "",
								// 	'delivery_order_draft_update_tgl': "",
								// 	'delivery_order_draft_approve_who': "",
								// 	'delivery_order_draft_approve_tgl': "",
								// 	'delivery_order_draft_reject_who': "",
								// 	'delivery_order_draft_reject_tgl': "",
								// 	'delivery_order_draft_reject_reason': "",
								// 	'tipe_delivery_order_id': $("#deliveryorderdraft-tipe_delivery_order_id").val()
								// });

								for (var index = 0; index < arr_sku.length; index++) {
									if (arr_sku[index] != "") {
										arr_detail.push({
											'sku_id': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_id").val(),
											'sku_kode': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_kode").val(),
											'sku_nama_produk': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_nama_produk").val(),
											'sku_harga_satuan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_harga_satuan").val(),
											'sku_disc_percent': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_disc_percent").val(),
											'sku_disc_rp': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_disc_rp").val(),
											'sku_harga_nett': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_harga_nett").val(),
											'sku_weight': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_weight").val(),
											'sku_weight_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_weight_unit").val(),
											'sku_length': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_length").val(),
											'sku_length_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_length_unit").val(),
											'sku_width': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_width").val(),
											'sku_width_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_width_unit").val(),
											'sku_height': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_height").val(),
											'sku_height_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_height_unit").val(),
											'sku_volume': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_volume").val(),
											'sku_volume_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_volume_unit").val(),
											'sku_qty': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_qty").val(),
											'sku_keterangan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_keterangan").val(),
											'sku_request_expdate': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_request_expdate").val(),
											'sku_filter_expdate': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_filter_expdate").val(),
											'sku_tipe_stock': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_tipe_stock").val(),
											'sku_filter_expdatebulan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_filter_expdatebulan")
												.val(),
											'sku_satuan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_satuan").val(),
											'sku_kemasan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_kemasan").val()
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

										var json_arr = JSON.stringify(arr_detail);
										var form_data = new FormData();
										var file_data = $('#file').prop('files')[0];
										form_data.append('file', file_data);
										form_data.append('delivery_order_draft_input_pembayaran_tunai', $(
											"#input_tunai").val());
										form_data.append('sales_order_id', $(
											"#deliveryorderdraft-sales_order_id").val());
										form_data.append('delivery_order_draft_kode', "");
										form_data.append('delivery_order_draft_yourref', "");
										form_data.append('client_wms_id', $(
											"#deliveryorderdraft-client_wms_id").val());
										form_data.append('delivery_order_draft_tgl_buat_do', $(
												"#deliveryorderdraft-delivery_order_draft_tgl_buat_do")
											.val());
										form_data.append('delivery_order_draft_tgl_expired_do', $(
											"#deliveryorderdraft-delivery_order_draft_tgl_expired_do"
										).val());
										form_data.append('delivery_order_draft_tgl_surat_jalan', $(
											"#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan"
										).val());
										form_data.append('delivery_order_draft_tgl_rencana_kirim', $(
											"#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim"
										).val());
										form_data.append('delivery_order_draft_tgl_aktual_kirim', "");
										form_data.append('delivery_order_draft_keterangan', $(
												"#deliveryorderdraft-delivery_order_draft_keterangan")
											.val());
										form_data.append('delivery_order_draft_status', "Draft");
										form_data.append('delivery_order_draft_is_prioritas', 0);
										form_data.append('delivery_order_draft_is_need_packing', 0);
										form_data.append('delivery_order_draft_tipe_layanan', document
											.querySelector(
												'input[id="deliveryorderdraft-delivery_order_draft_tipe_layanan"]:checked'
											).value);
										form_data.append('delivery_order_draft_tipe_pembayaran', document
											.querySelector(
												'input[id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran"]:checked'
											).value);
										form_data.append('delivery_order_draft_sesi_pengiriman', 0);
										form_data.append('delivery_order_draft_request_tgl_kirim', "");
										form_data.append('delivery_order_draft_request_jam_kirim', "");
										form_data.append('tipe_pengiriman_id', "");
										form_data.append('nama_tipe', "");
										form_data.append('confirm_rate', "");
										form_data.append('delivery_order_draft_reff_id', $(
												"#deliveryorderdraft-delivery_order_draft_reff_id")
											.val());
										form_data.append('delivery_order_draft_reff_no', $(
												"#deliveryorderdraft-delivery_order_draft_reff_no")
											.val());
										form_data.append('delivery_order_draft_total', "");
										form_data.append('unit_mandiri_id',
											"<?= $this->session->userdata('unit_mandiri_id') ?>");
										form_data.append('depo_id',
											"<?= $this->session->userdata('depo_id') ?>");
										form_data.append('client_pt_id', $(
											"#deliveryorderdraft-client_pt_id").val());
										form_data.append('delivery_order_draft_kirim_nama', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_nama")
											.val());
										form_data.append('delivery_order_draft_kirim_alamat', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_alamat")
											.val());
										form_data.append('delivery_order_draft_kirim_telp', $(
											"#deliveryorderdraft-delivery_order_draft_kirim_telepon"
										).val());
										form_data.append('delivery_order_draft_kirim_provinsi', $(
											"#deliveryorderdraft-delivery_order_draft_kirim_provinsi"
										).val());
										form_data.append('delivery_order_draft_kirim_kota', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_kota")
											.val());
										form_data.append('delivery_order_draft_kirim_kecamatan', $(
											"#deliveryorderdraft-delivery_order_draft_kirim_kecamatan"
										).val());
										form_data.append('delivery_order_draft_kirim_kelurahan', $(
											"#deliveryorderdraft-delivery_order_draft_kirim_kelurahan"
										).val());
										form_data.append('delivery_order_draft_kirim_kodepos', $(
											"#deliveryorderdraft-delivery_order_draft_kirim_kodepos"
										).val());
										form_data.append('delivery_order_draft_kirim_area', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_area")
											.val());
										form_data.append('delivery_order_draft_kirim_invoice_pdf', "");
										form_data.append('delivery_order_draft_kirim_invoice_dir', "");
										form_data.append('pabrik_id', $("#deliveryorderdraft-pabrik_id")
											.val());
										form_data.append('delivery_order_draft_ambil_nama', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_nama")
											.val());
										form_data.append('delivery_order_draft_ambil_alamat', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_alamat")
											.val());
										form_data.append('delivery_order_draft_ambil_telp', $(
											"#deliveryorderdraft-delivery_order_draft_ambil_telepon"
										).val());
										form_data.append('delivery_order_draft_ambil_provinsi', $(
											"#deliveryorderdraft-delivery_order_draft_ambil_provinsi"
										).val());
										form_data.append('delivery_order_draft_ambil_kota', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_kota")
											.val());
										form_data.append('delivery_order_draft_ambil_kecamatan', $(
											"#deliveryorderdraft-delivery_order_draft_ambil_kecamatan"
										).val());
										form_data.append('delivery_order_draft_ambil_kelurahan', $(
											"#deliveryorderdraft-delivery_order_draft_ambil_kelurahan"
										).val());
										form_data.append('delivery_order_draft_ambil_kodepos', $(
											"#deliveryorderdraft-delivery_order_draft_ambil_kodepos"
										).val());
										form_data.append('delivery_order_draft_ambil_area', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_area")
											.val());
										form_data.append('delivery_order_draft_update_who', "");
										form_data.append('delivery_order_draft_update_tgl', "");
										form_data.append('delivery_order_draft_approve_who', "");
										form_data.append('delivery_order_draft_approve_tgl', "");
										form_data.append('delivery_order_draft_reject_who', "");
										form_data.append('delivery_order_draft_reject_tgl', "");
										form_data.append('delivery_order_draft_reject_reason', "");
										form_data.append('tipe_delivery_order_id', $(
											"#deliveryorderdraft-tipe_delivery_order_id").val());
										form_data.append('detail', json_arr);

										//ajax save data
										$.ajax({
											async: false,
											url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft'); ?>",
											type: "POST",
											data: form_data,
											contentType: false,
											processData: false,
											dataType: "JSON",
											success: function(data) {
												if (data.status == true) {
													message_topright("success", data.message);
													setTimeout(() => {
														location.href =
															"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/";
													}, 500);
												} else {
													message_topright("error", data.message);
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
				message("Pilih Tipe DO!", "Tipe delivery order draft belum dipilih", "error");
			}

		} else {
			let alert_tes = GetLanguageByKode('CAPTION-ALERT-AREATIDAKDIPILIH');
			message_custom("Warning", "warning", alert_tes);
		}
	});

	$("#btnupdatedodraft").on("click", function() {
		var cek_customer = $("#cek_customer").val();
		var tipe_do = $("#deliveryorderdraft-tipe_delivery_order_id").val();
		var dod_id = $("#deliveryorderdraft-delivery_order_draft_id").val();
		var txt_arr_sku = $("#txt_arr_sku").val();

		var area = $("#deliveryorderdraft-delivery_order_draft_kirim_area").val();

		arr_sku_now = txt_arr_sku.split(",");
		var arr_table_sku = $("#table-sku-delivery-only > tbody tr").length

		if (area != "") {

			if (tipe_do != "") {

				if (cek_customer > 0) {

					if (arr_sku.length > 0) {
						$("#table-sku-delivery-only > tbody tr").each(function() {
							var is_Qty = $(this).find("td:eq(11) input[type='number']");
							var is_tipe_stock = $(this).find("td:eq(6) select");
							// console.log(is_Qty);
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
											'sku_id': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_id").val(),
											'sku_kode': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_kode").val(),
											'sku_nama_produk': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_nama_produk").val(),
											'sku_harga_satuan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_harga_satuan").val(),
											'sku_disc_percent': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_disc_percent").val(),
											'sku_disc_rp': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_disc_rp").val(),
											'sku_harga_nett': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_harga_nett").val(),
											'sku_weight': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_weight").val(),
											'sku_weight_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_weight_unit").val(),
											'sku_length': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_length").val(),
											'sku_length_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_length_unit").val(),
											'sku_width': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_width").val(),
											'sku_width_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_width_unit").val(),
											'sku_height': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_height").val(),
											'sku_height_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_height_unit").val(),
											'sku_volume': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_volume").val(),
											'sku_volume_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_volume_unit").val(),
											'sku_qty': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_qty").val(),
											'sku_keterangan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_keterangan").val(),
											'sku_tipe_stock': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_tipe_stock").val(),
											'sku_request_expdate': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_request_expdate").val(),
											'sku_filter_expdate': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_filter_expdate").val(),
											'sku_filter_expdatebulan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_filter_expdatebulan")
												.val(),
											'sku_satuan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_satuan").val(),
											'sku_kemasan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_kemasan").val()
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
										var json_arr = JSON.stringify(arr_detail);
										var form_data = new FormData();
										var file_data = $('#file').prop('files')[0];
										form_data.append('file', file_data);
										form_data.append('delivery_order_draft_input_pembayaran_tunai', $(
											"#input_tunai").val());
										form_data.append('delivery_order_draft_id', dod_id);
										form_data.append('sales_order_id', $(
											"#deliveryorderdraft-sales_order_id").val());
										form_data.append('delivery_order_draft_kode', $(
											"#deliveryorderdraft-delivery_order_draft_kode").val());
										form_data.append('delivery_order_draft_yourref', "");
										form_data.append('client_wms_id', $(
											"#deliveryorderdraft-client_wms_id").val());
										form_data.append('delivery_order_draft_tgl_buat_do', $(
												"#deliveryorderdraft-delivery_order_draft_tgl_buat_do")
											.val());
										form_data.append('delivery_order_draft_tgl_expired_do', $(
											"#deliveryorderdraft-delivery_order_draft_tgl_expired_do"
										).val());
										form_data.append('delivery_order_draft_tgl_surat_jalan', $(
											"#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan"
										).val());
										form_data.append('delivery_order_draft_tgl_rencana_kirim', $(
											"#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim"
										).val());
										form_data.append('delivery_order_draft_tgl_aktual_kirim', "");
										form_data.append('delivery_order_draft_keterangan', $(
												"#deliveryorderdraft-delivery_order_draft_keterangan")
											.val());
										form_data.append('delivery_order_draft_status', "Draft");
										form_data.append('delivery_order_draft_is_prioritas', 0);
										form_data.append('delivery_order_draft_is_need_packing', 0);
										form_data.append('delivery_order_draft_tipe_layanan', document
											.querySelector(
												'input[id="deliveryorderdraft-delivery_order_draft_tipe_layanan"]:checked'
											).value);
										form_data.append('delivery_order_draft_tipe_pembayaran', document
											.querySelector(
												'input[id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran"]:checked'
											).value);
										form_data.append('delivery_order_draft_sesi_pengiriman', 0);
										form_data.append('delivery_order_draft_request_tgl_kirim', "");
										form_data.append('delivery_order_draft_request_jam_kirim', "");
										form_data.append('tipe_pengiriman_id', "");
										form_data.append('nama_tipe', "");
										form_data.append('confirm_rate', "");
										form_data.append('delivery_order_draft_reff_id', $(
												"#deliveryorderdraft-delivery_order_draft_reff_id")
											.val());
										form_data.append('delivery_order_draft_reff_no', $(
												"#deliveryorderdraft-delivery_order_draft_reff_no")
											.val());
										form_data.append('delivery_order_draft_total', "");
										form_data.append('unit_mandiri_id',
											"<?= $this->session->userdata('unit_mandiri_id') ?>");
										form_data.append('depo_id',
											"<?= $this->session->userdata('depo_id') ?>");
										form_data.append('client_pt_id', $(
											"#deliveryorderdraft-client_pt_id").val());
										form_data.append('delivery_order_draft_kirim_nama', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_nama")
											.val());
										form_data.append('delivery_order_draft_kirim_alamat', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_alamat")
											.val());
										form_data.append('delivery_order_draft_kirim_telp', $(
											"#deliveryorderdraft-delivery_order_draft_kirim_telepon"
										).val());
										form_data.append('delivery_order_draft_kirim_provinsi', $(
											"#deliveryorderdraft-delivery_order_draft_kirim_provinsi"
										).val());
										form_data.append('delivery_order_draft_kirim_kota', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_kota")
											.val());
										form_data.append('delivery_order_draft_kirim_kecamatan', $(
											"#deliveryorderdraft-delivery_order_draft_kirim_kecamatan"
										).val());
										form_data.append('delivery_order_draft_kirim_kelurahan', $(
											"#deliveryorderdraft-delivery_order_draft_kirim_kelurahan"
										).val());
										form_data.append('delivery_order_draft_kirim_kodepos', $(
											"#deliveryorderdraft-delivery_order_draft_kirim_kodepos"
										).val());
										form_data.append('delivery_order_draft_kirim_area', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_area")
											.val());
										form_data.append('delivery_order_draft_kirim_invoice_pdf', "");
										form_data.append('delivery_order_draft_kirim_invoice_dir', "");
										form_data.append('pabrik_id', $("#deliveryorderdraft-pabrik_id")
											.val());
										form_data.append('delivery_order_draft_ambil_nama', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_nama")
											.val());
										form_data.append('delivery_order_draft_ambil_alamat', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_alamat")
											.val());
										form_data.append('delivery_order_draft_ambil_telp', $(
											"#deliveryorderdraft-delivery_order_draft_ambil_telepon"
										).val());
										form_data.append('delivery_order_draft_ambil_provinsi', $(
											"#deliveryorderdraft-delivery_order_draft_ambil_provinsi"
										).val());
										form_data.append('delivery_order_draft_ambil_kota', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_kota")
											.val());
										form_data.append('delivery_order_draft_ambil_kecamatan', $(
											"#deliveryorderdraft-delivery_order_draft_ambil_kecamatan"
										).val());
										form_data.append('delivery_order_draft_ambil_kelurahan', $(
											"#deliveryorderdraft-delivery_order_draft_ambil_kelurahan"
										).val());
										form_data.append('delivery_order_draft_ambil_kodepos', $(
											"#deliveryorderdraft-delivery_order_draft_ambil_kodepos"
										).val());
										form_data.append('delivery_order_draft_ambil_area', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_area")
											.val());
										form_data.append('delivery_order_draft_update_who', "");
										form_data.append('delivery_order_draft_update_tgl', $(
												"#deliveryorderdraft-delivery_order_draft_update_tgl")
											.val());
										form_data.append('delivery_order_draft_approve_who', "");
										form_data.append('delivery_order_draft_approve_tgl', "");
										form_data.append('delivery_order_draft_reject_who', "");
										form_data.append('delivery_order_draft_reject_tgl', "");
										form_data.append('delivery_order_draft_reject_reason', "");
										form_data.append('tipe_delivery_order_id', $(
											"#deliveryorderdraft-tipe_delivery_order_id").val());
										form_data.append('detail', json_arr);
										messageBoxBeforeRequest(
											'Pastikan data yang sudah anda input benar!', 'Iya, Simpan',
											'Tidak, Tutup').then((result) => {
											if (result.value == true) {
												requestAjax(
													"<?= base_url('WMS/Distribusi/DeliveryOrderDraft/update_delivery_order_draft'); ?>", {
														dataForm: form_data
													}, "POST", "JSON",
													function(response) {
														if (response.status == true) {
															if (response.message == "reload") {
																messageNotSameLastUpdated();
																return false;
															} else {
																message_topright("success",
																	response.message);
																setTimeout(() => {
																	location.href =
																		"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/edit/?id=" +
																		dod_id;
																}, 500);
															}
														} else {
															message_topright("error", response
																.message);
														}
													}, "#btnupdatedodraft", "multipart-formdata"
												)
											}
										});
									}
								});

							} else {
								cek_qty = 0;
								message("Error!", "Qty tidak boleh 0!", "error");
							}
						} else {
							cek_tipe_stock = 0;
							message("Error!", "Tipe Stock tidak boleh kosong!", "error");
						}
					} else {
						if (arr_table_sku > 0) {
							$("#table-sku-delivery-only > tbody tr").each(function() {
								var is_Qty = $(this).find("td:eq(11) input[type='number']");
								var is_tipe_stock = $(this).find("td:eq(6) select");
								// console.log(is_Qty);
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

									for (var index = 0; index < arr_sku_now.length; index++) {
										if (arr_sku_now[index] != "") {
											arr_detail.push({
												'sku_id': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_id").val(),
												'sku_kode': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_kode").val(),
												'sku_nama_produk': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_nama_produk").val(),
												'sku_harga_satuan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_harga_satuan").val(),
												'sku_disc_percent': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_disc_percent").val(),
												'sku_disc_rp': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_disc_rp").val(),
												'sku_harga_nett': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_harga_nett").val(),
												'sku_weight': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_weight").val(),
												'sku_weight_unit': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_weight_unit").val(),
												'sku_length': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_length").val(),
												'sku_length_unit': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_length_unit").val(),
												'sku_width': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_width").val(),
												'sku_width_unit': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_width_unit").val(),
												'sku_height': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_height").val(),
												'sku_height_unit': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_height_unit").val(),
												'sku_volume': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_volume").val(),
												'sku_volume_unit': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_volume_unit").val(),
												'sku_qty': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_qty").val(),
												'sku_keterangan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_keterangan").val(),
												'sku_tipe_stock': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_tipe_stock").val(),
												'sku_request_expdate': $("#item-" + index +
														"-DeliveryOrderDetailDraft-sku_request_expdate")
													.val(),
												'sku_filter_expdate': $("#item-" + index +
														"-DeliveryOrderDetailDraft-sku_filter_expdate")
													.val(),
												'sku_filter_expdatebulan': $("#item-" + index +
														"-DeliveryOrderDetailDraft-sku_filter_expdatebulan")
													.val(),
												'sku_satuan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_satuan").val(),
												'sku_kemasan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_kemasan").val()
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
											var json_arr = JSON.stringify(arr_detail);
											var form_data = new FormData();
											var file_data = $('#file').prop('files')[0];
											form_data.append('file', file_data);
											form_data.append('delivery_order_draft_input_pembayaran_tunai',
												$("#input_tunai").val());
											form_data.append('delivery_order_draft_id', dod_id);
											form_data.append('sales_order_id', $(
												"#deliveryorderdraft-sales_order_id").val());
											form_data.append('delivery_order_draft_kode', $(
													"#deliveryorderdraft-delivery_order_draft_kode")
												.val());
											form_data.append('delivery_order_draft_yourref', "");
											form_data.append('client_wms_id', $(
												"#deliveryorderdraft-client_wms_id").val());
											form_data.append('delivery_order_draft_tgl_buat_do', $(
												"#deliveryorderdraft-delivery_order_draft_tgl_buat_do"
											).val());
											form_data.append('delivery_order_draft_tgl_expired_do', $(
												"#deliveryorderdraft-delivery_order_draft_tgl_expired_do"
											).val());
											form_data.append('delivery_order_draft_tgl_surat_jalan', $(
												"#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan"
											).val());
											form_data.append('delivery_order_draft_tgl_rencana_kirim', $(
												"#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim"
											).val());
											form_data.append('delivery_order_draft_tgl_aktual_kirim', "");
											form_data.append('delivery_order_draft_keterangan', $(
												"#deliveryorderdraft-delivery_order_draft_keterangan"
											).val());
											form_data.append('delivery_order_draft_status', "Draft");
											form_data.append('delivery_order_draft_is_prioritas', 0);
											form_data.append('delivery_order_draft_is_need_packing', 0);
											form_data.append('delivery_order_draft_tipe_layanan', document
												.querySelector(
													'input[id="deliveryorderdraft-delivery_order_draft_tipe_layanan"]:checked'
												).value);
											form_data.append('delivery_order_draft_tipe_pembayaran',
												document.querySelector(
													'input[id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran"]:checked'
												).value);
											form_data.append('delivery_order_draft_sesi_pengiriman', 0);
											form_data.append('delivery_order_draft_request_tgl_kirim', "");
											form_data.append('delivery_order_draft_request_jam_kirim', "");
											form_data.append('tipe_pengiriman_id', "");
											form_data.append('nama_tipe', "");
											form_data.append('confirm_rate', "");
											form_data.append('delivery_order_draft_reff_id', $(
													"#deliveryorderdraft-delivery_order_draft_reff_id")
												.val());
											form_data.append('delivery_order_draft_reff_no', $(
													"#deliveryorderdraft-delivery_order_draft_reff_no")
												.val());
											form_data.append('delivery_order_draft_total', "");
											form_data.append('unit_mandiri_id',
												"<?= $this->session->userdata('unit_mandiri_id') ?>");
											form_data.append('depo_id',
												"<?= $this->session->userdata('depo_id') ?>");
											form_data.append('client_pt_id', $(
												"#deliveryorderdraft-client_pt_id").val());
											form_data.append('delivery_order_draft_kirim_nama', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_nama"
											).val());
											form_data.append('delivery_order_draft_kirim_alamat', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_alamat"
											).val());
											form_data.append('delivery_order_draft_kirim_telp', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_telepon"
											).val());
											form_data.append('delivery_order_draft_kirim_provinsi', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_provinsi"
											).val());
											form_data.append('delivery_order_draft_kirim_kota', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_kota"
											).val());
											form_data.append('delivery_order_draft_kirim_kecamatan', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_kecamatan"
											).val());
											form_data.append('delivery_order_draft_kirim_kelurahan', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_kelurahan"
											).val());
											form_data.append('delivery_order_draft_kirim_kodepos', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_kodepos"
											).val());
											form_data.append('delivery_order_draft_kirim_area', $(
												"#deliveryorderdraft-delivery_order_draft_kirim_area"
											).val());
											form_data.append('delivery_order_draft_kirim_invoice_pdf', "");
											form_data.append('delivery_order_draft_kirim_invoice_dir', "");
											form_data.append('pabrik_id', $("#deliveryorderdraft-pabrik_id")
												.val());
											form_data.append('delivery_order_draft_ambil_nama', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_nama"
											).val());
											form_data.append('delivery_order_draft_ambil_alamat', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_alamat"
											).val());
											form_data.append('delivery_order_draft_ambil_telp', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_telepon"
											).val());
											form_data.append('delivery_order_draft_ambil_provinsi', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_provinsi"
											).val());
											form_data.append('delivery_order_draft_ambil_kota', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_kota"
											).val());
											form_data.append('delivery_order_draft_ambil_kecamatan', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_kecamatan"
											).val());
											form_data.append('delivery_order_draft_ambil_kelurahan', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_kelurahan"
											).val());
											form_data.append('delivery_order_draft_ambil_kodepos', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_kodepos"
											).val());
											form_data.append('delivery_order_draft_ambil_area', $(
												"#deliveryorderdraft-delivery_order_draft_ambil_area"
											).val());
											form_data.append('delivery_order_draft_update_who', "");
											form_data.append('delivery_order_draft_update_tgl', $(
												"#deliveryorderdraft-delivery_order_draft_update_tgl"
											).val());
											form_data.append('delivery_order_draft_approve_who', "");
											form_data.append('delivery_order_draft_approve_tgl', "");
											form_data.append('delivery_order_draft_reject_who', "");
											form_data.append('delivery_order_draft_reject_tgl', "");
											form_data.append('delivery_order_draft_reject_reason', "");
											form_data.append('tipe_delivery_order_id', $(
												"#deliveryorderdraft-tipe_delivery_order_id").val());
											form_data.append('detail', json_arr);
											messageBoxBeforeRequest(
												'Pastikan data yang sudah anda input benar!',
												'Iya, Simpan', 'Tidak, Tutup').then((result) => {
												if (result.value == true) {
													requestAjax(
														"<?= base_url('WMS/Distribusi/DeliveryOrderDraft/update_delivery_order_draft'); ?>", {
															dataForm: form_data
														}, "POST", "JSON",
														function(response) {
															if (response.status == true) {
																if (response.message ==
																	"reload") {
																	messageNotSameLastUpdated();
																	return false;
																} else {
																	message_topright("success",
																		response.message);
																	setTimeout(() => {
																		location.href =
																			"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/edit/?id=" +
																			dod_id;
																	}, 500);
																}
															} else {
																message_topright("error",
																	response.message);
															}
														}, "#btnupdatedodraft",
														"multipart-formdata")
												}
											});
										}
									});

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
					}
				} else {
					message("Pilih Customer!", "Customer belum dipilih", "error");

				}

			} else {
				message("Pilih Tipe DO!", "Tipe delivery order draft belum dipilih", "error");
			}
		} else {
			let alert_tes = GetLanguageByKode('CAPTION-ALERT-AREATIDAKDIPILIH');
			message_custom("Warning", "warning", alert_tes);
		}
	});

	$("#btnconfirmdodraft").on("click", function() {
		var cek_customer = $("#cek_customer").val();
		var tipe_do = $("#deliveryorderdraft-tipe_delivery_order_id").val();
		var dod_id = $("#deliveryorderdraft-delivery_order_draft_id").val();
		var txt_arr_sku = $("#txt_arr_sku").val();

		var area = $("#deliveryorderdraft-delivery_order_draft_kirim_area").val();

		arr_sku_now = txt_arr_sku.split(",");
		var arr_table_sku = $("#table-sku-delivery-only > tbody tr").length;

		if (area != "") {
			if (tipe_do != "") {
				if (cek_customer > 0) {
					if (arr_sku.length > 0) {
						$("#table-sku-delivery-only > tbody tr").each(function() {
							var is_Qty = $(this).find("td:eq(11) input[type='number']");
							var is_tipe_stock = $(this).find("td:eq(6) select");
							// console.log(is_Qty);
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
											'sku_id': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_id").val(),
											'sku_kode': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_kode").val(),
											'sku_nama_produk': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_nama_produk").val(),
											'sku_harga_satuan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_harga_satuan").val(),
											'sku_disc_percent': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_disc_percent").val(),
											'sku_disc_rp': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_disc_rp").val(),
											'sku_harga_nett': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_harga_nett").val(),
											'sku_weight': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_weight").val(),
											'sku_weight_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_weight_unit").val(),
											'sku_length': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_length").val(),
											'sku_length_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_length_unit").val(),
											'sku_width': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_width").val(),
											'sku_width_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_width_unit").val(),
											'sku_height': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_height").val(),
											'sku_height_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_height_unit").val(),
											'sku_volume': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_volume").val(),
											'sku_volume_unit': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_volume_unit").val(),
											'sku_qty': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_qty").val(),
											'sku_keterangan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_keterangan").val(),
											'sku_request_expdate': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_request_expdate").val(),
											'sku_tipe_stock': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_tipe_stock").val(),
											'sku_filter_expdate': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_filter_expdate").val(),
											'sku_filter_expdatebulan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_filter_expdatebulan")
												.val(),
											'sku_satuan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_satuan").val(),
											'sku_kemasan': $("#item-" + index +
												"-DeliveryOrderDetailDraft-sku_kemasan").val()
										});
									}
								}

								messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan',
									'Tidak, Tutup').then((result) => {
									if (result.value == true) {
										requestAjax(
											"<?= base_url('WMS/Distribusi/DeliveryOrderDraft/confirm_delivery_order_draft'); ?>", {
												delivery_order_draft_id: dod_id,
												sales_order_id: "",
												delivery_order_draft_kode: $(
														"#deliveryorderdraft-delivery_order_draft_kode")
													.val(),
												delivery_order_draft_yourref: "",
												client_wms_id: $("#deliveryorderdraft-client_wms_id")
													.val(),
												delivery_order_draft_tgl_buat_do: $(
													"#deliveryorderdraft-delivery_order_draft_tgl_buat_do"
												).val(),
												delivery_order_draft_tgl_expired_do: $(
													"#deliveryorderdraft-delivery_order_draft_tgl_expired_do"
												).val(),
												delivery_order_draft_tgl_surat_jalan: $(
													"#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan"
												).val(),
												delivery_order_draft_tgl_rencana_kirim: $(
													"#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim"
												).val(),
												delivery_order_draft_tgl_aktual_kirim: "",
												delivery_order_draft_keterangan: $(
													"#deliveryorderdraft-delivery_order_draft_keterangan"
												).val(),
												delivery_order_draft_status: "Draft",
												delivery_order_draft_is_prioritas: 0,
												delivery_order_draft_is_need_packing: 0,
												delivery_order_draft_tipe_layanan: document
													.querySelector(
														'input[id="deliveryorderdraft-delivery_order_draft_tipe_layanan"]:checked'
													).value,
												delivery_order_draft_tipe_pembayaran: document
													.querySelector(
														'input[id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran"]:checked'
													).value,
												delivery_order_draft_sesi_pengiriman: 0,
												delivery_order_draft_request_tgl_kirim: "",
												delivery_order_draft_request_jam_kirim: "",
												tipe_pengiriman_id: "",
												nama_tipe: "",
												confirm_rate: "",
												delivery_order_draft_reff_id: "",
												delivery_order_draft_reff_no: "",
												delivery_order_draft_total: "",
												unit_mandiri_id: "<?= $this->session->userdata('unit_mandiri_id') ?>",
												depo_id: "<?= $this->session->userdata('depo_id') ?>",
												client_pt_id: $("#deliveryorderdraft-client_pt_id")
													.val(),
												delivery_order_draft_kirim_nama: $(
													"#deliveryorderdraft-delivery_order_draft_kirim_nama"
												).val(),
												delivery_order_draft_kirim_alamat: $(
													"#deliveryorderdraft-delivery_order_draft_kirim_alamat"
												).val(),
												delivery_order_draft_kirim_telp: $(
													"#deliveryorderdraft-delivery_order_draft_kirim_telepon"
												).val(),
												delivery_order_draft_kirim_provinsi: $(
													"#deliveryorderdraft-delivery_order_draft_kirim_provinsi"
												).val(),
												delivery_order_draft_kirim_kota: $(
													"#deliveryorderdraft-delivery_order_draft_kirim_kota"
												).val(),
												delivery_order_draft_kirim_kecamatan: $(
													"#deliveryorderdraft-delivery_order_draft_kirim_kecamatan"
												).val(),
												delivery_order_draft_kirim_kelurahan: $(
													"#deliveryorderdraft-delivery_order_draft_kirim_kelurahan"
												).val(),
												delivery_order_draft_kirim_kodepos: $(
													"#deliveryorderdraft-delivery_order_draft_kirim_kodepos"
												).val(),
												delivery_order_draft_kirim_area: $(
													"#deliveryorderdraft-delivery_order_draft_kirim_area"
												).val(),
												delivery_order_draft_kirim_invoice_pdf: "",
												delivery_order_draft_kirim_invoice_dir: "",
												pabrik_id: $("#deliveryorderdraft-pabrik_id").val(),
												delivery_order_draft_ambil_nama: $(
													"#deliveryorderdraft-delivery_order_draft_ambil_nama"
												).val(),
												delivery_order_draft_ambil_alamat: $(
													"#deliveryorderdraft-delivery_order_draft_ambil_alamat"
												).val(),
												delivery_order_draft_ambil_telp: $(
													"#deliveryorderdraft-delivery_order_draft_ambil_telepon"
												).val(),
												delivery_order_draft_ambil_provinsi: $(
													"#deliveryorderdraft-delivery_order_draft_ambil_provinsi"
												).val(),
												delivery_order_draft_ambil_kota: $(
													"#deliveryorderdraft-delivery_order_draft_ambil_kota"
												).val(),
												delivery_order_draft_ambil_kecamatan: $(
													"#deliveryorderdraft-delivery_order_draft_ambil_kecamatan"
												).val(),
												delivery_order_draft_ambil_kelurahan: $(
													"#deliveryorderdraft-delivery_order_draft_ambil_kelurahan"
												).val(),
												delivery_order_draft_ambil_kodepos: $(
													"#deliveryorderdraft-delivery_order_draft_ambil_kodepos"
												).val(),
												delivery_order_draft_ambil_area: $(
													"#deliveryorderdraft-delivery_order_draft_ambil_area"
												).val(),
												delivery_order_draft_update_who: "",
												delivery_order_draft_update_tgl: $(
													"#deliveryorderdraft-delivery_order_draft_update_tgl"
												).val(),
												delivery_order_draft_approve_who: "",
												delivery_order_draft_approve_tgl: "",
												delivery_order_draft_reject_who: "",
												delivery_order_draft_reject_tgl: "",
												delivery_order_draft_reject_reason: "",
												tipe_delivery_order_id: $(
														"#deliveryorderdraft-tipe_delivery_order_id")
													.val(),
												detail: arr_detail,
												detail2: arr_detail2,
												delivery_order_draft_input_pembayaran_tunai: $(
													"#input_tunai").val()
											}, "POST", "JSON",
											function(data) {
												console.log(data);
												if (data.type === 200) {
													message_topright("success",
														"Data berhasil diapprove");
													setTimeout(() => {
														location.href =
															"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=" +
															dod_id;
													}, 500);
												}
												if (data.type === 400) {
													messageNotSameLastUpdated();
													return false;
												}
												if (data.type === 201) {
													let arrayOfErrorsToDisplay = [];
													let indexError = [];
													$.each(data.data, (index, item) => {
														let response = item.data;
														// arrayOfErrorsToDisplay = []
														// indexError = []
														indexError.push(index + 1);
														arrayOfErrorsToDisplay.push({
															title: 'Data Gagal diapprove!',
															html: `Qty dari SKU <strong>${response.sku}</strong> tidak cukup, silahkan dicek kembali!`
														});
														// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
													});
													Swal.mixin({
														icon: 'error',
														confirmButtonText: 'Next &rarr;',
														showCancelButton: true,
														progressSteps: indexError
													}).queue(arrayOfErrorsToDisplay)
												}
												if (data.type === 202) {
													message_topright("error", "Data gagal Diapprove");
												}
												if (data.type === 203) {
													let arrayOfErrorsToDisplayEmptySku = [];
													let indexErrorEmptySku = [];
													arrayOfErrorsToDisplayEmptySku = []
													indexErrorEmptySku = []
													$.each(data.data, (index, item) => {
														let response = item.data;
														indexErrorEmptySku.push(index + 1);
														arrayOfErrorsToDisplayEmptySku.push({
															title: 'Data Gagal diapprove!',
															html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
														});
														// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
													});
													Swal.mixin({
														icon: 'error',
														confirmButtonText: 'Next &rarr;',
														showCancelButton: true,
														progressSteps: indexErrorEmptySku
													}).queue(arrayOfErrorsToDisplayEmptySku)
												}
												if (data.type === 204) {
													let arrayOfErrorsToDisplaySkuStokKurang = [];
													let indexErrorSkuStokKurang = [];
													$.each(data.data, (index, item) => {
														let response = item.data;
														// arrayOfErrorsToDisplaySkuStokKurang
														//     = []
														// indexErrorSkuStokKurang = []
														indexErrorSkuStokKurang.push(index + 1);
														arrayOfErrorsToDisplaySkuStokKurang
															.push({
																title: 'Data Gagal diapprove!',
																html: `Qty dari SKU <strong>${response.sku}</strong> kurang`
															});
														// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
													});
													Swal.mixin({
														icon: 'error',
														confirmButtonText: 'Next &rarr;',
														showCancelButton: true,
														progressSteps: indexErrorSkuStokKurang
													}).queue(arrayOfErrorsToDisplaySkuStokKurang)
												}

												if ($("#deliveryorderdraft-tipe_delivery_order_id")
													.val() == "C5BE83E2-01E8-4E24-B766-26BB4158F2CD") {
													$.each(data, function(i, v) {
														if (v.type == 210) {
															let arrayOfErrorsToDisplay = [];
															let indexError = [];
															indexError = 0;
															arrayOfErrorsToDisplay.push({
																title: 'Data Gagal diapprove!',
																html: `Jumlah Qty dari SKU Stock <strong>${v.sku_nama_produk}</strong> tidak cocok, silahkan dicek kembali!`
															});
															Swal.mixin({
																icon: 'error',
																confirmButtonText: 'Next &rarr;',
																showCancelButton: true,
																progressSteps: indexError
															}).queue(arrayOfErrorsToDisplay)
														}
														if (v.type == 211) {
															message_topright("success",
																"SKU Stock Cocok");
														}
													})
												}
											}, "#btnconfirmdodraft")
									}
								});

								// Swal.fire({
								// 	title: "Apakah anda yakin konfirmasi data ini ?",
								// 	text: "Data yang sudah dikonfirmasi tidak bisa diubah!",
								// 	icon: "warning",
								// 	showCancelButton: true,
								// 	confirmButtonColor: "#3085d6",
								// 	cancelButtonColor: "#d33",
								// 	confirmButtonText: "Ya, Simpan",
								// 	cancelButtonText: "Tidak, Tutup"
								// }).then((result) => {
								// 	if (result.value == true) {
								// 		//ajax save data
								// 		$.ajax({
								// 			async: false,
								// 			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/confirm_delivery_order_draft'); ?>",
								// 			type: "POST",
								// 			data: {
								// 				delivery_order_draft_id: dod_id,
								// 				sales_order_id: "",
								// 				delivery_order_draft_kode: $(
								// 						"#deliveryorderdraft-delivery_order_draft_kode")
								// 					.val(),
								// 				delivery_order_draft_yourref: "",
								// 				client_wms_id: $("#deliveryorderdraft-client_wms_id")
								// 					.val(),
								// 				delivery_order_draft_tgl_buat_do: $(
								// 					"#deliveryorderdraft-delivery_order_draft_tgl_buat_do"
								// 				).val(),
								// 				delivery_order_draft_tgl_expired_do: $(
								// 					"#deliveryorderdraft-delivery_order_draft_tgl_expired_do"
								// 				).val(),
								// 				delivery_order_draft_tgl_surat_jalan: $(
								// 					"#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan"
								// 				).val(),
								// 				delivery_order_draft_tgl_rencana_kirim: $(
								// 					"#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim"
								// 				).val(),
								// 				delivery_order_draft_tgl_aktual_kirim: "",
								// 				delivery_order_draft_keterangan: $(
								// 					"#deliveryorderdraft-delivery_order_draft_keterangan"
								// 				).val(),
								// 				delivery_order_draft_status: "Draft",
								// 				delivery_order_draft_is_prioritas: 0,
								// 				delivery_order_draft_is_need_packing: 0,
								// 				delivery_order_draft_tipe_layanan: document
								// 					.querySelector(
								// 						'input[id="deliveryorderdraft-delivery_order_draft_tipe_layanan"]:checked'
								// 					).value,
								// 				delivery_order_draft_tipe_pembayaran: document
								// 					.querySelector(
								// 						'input[id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran"]:checked'
								// 					).value,
								// 				delivery_order_draft_sesi_pengiriman: 0,
								// 				delivery_order_draft_request_tgl_kirim: "",
								// 				delivery_order_draft_request_jam_kirim: "",
								// 				tipe_pengiriman_id: "",
								// 				nama_tipe: "",
								// 				confirm_rate: "",
								// 				delivery_order_draft_reff_id: "",
								// 				delivery_order_draft_reff_no: "",
								// 				delivery_order_draft_total: "",
								// 				unit_mandiri_id: "<?= $this->session->userdata('unit_mandiri_id') ?>",
								// 				depo_id: "<?= $this->session->userdata('depo_id') ?>",
								// 				client_pt_id: $("#deliveryorderdraft-client_pt_id")
								// 					.val(),
								// 				delivery_order_draft_kirim_nama: $(
								// 					"#deliveryorderdraft-delivery_order_draft_kirim_nama"
								// 				).val(),
								// 				delivery_order_draft_kirim_alamat: $(
								// 					"#deliveryorderdraft-delivery_order_draft_kirim_alamat"
								// 				).val(),
								// 				delivery_order_draft_kirim_telp: $(
								// 					"#deliveryorderdraft-delivery_order_draft_kirim_telepon"
								// 				).val(),
								// 				delivery_order_draft_kirim_provinsi: $(
								// 					"#deliveryorderdraft-delivery_order_draft_kirim_provinsi"
								// 				).val(),
								// 				delivery_order_draft_kirim_kota: $(
								// 					"#deliveryorderdraft-delivery_order_draft_kirim_kota"
								// 				).val(),
								// 				delivery_order_draft_kirim_kecamatan: $(
								// 					"#deliveryorderdraft-delivery_order_draft_kirim_kecamatan"
								// 				).val(),
								// 				delivery_order_draft_kirim_kelurahan: $(
								// 					"#deliveryorderdraft-delivery_order_draft_kirim_kelurahan"
								// 				).val(),
								// 				delivery_order_draft_kirim_kodepos: $(
								// 					"#deliveryorderdraft-delivery_order_draft_kirim_kodepos"
								// 				).val(),
								// 				delivery_order_draft_kirim_area: $(
								// 					"#deliveryorderdraft-delivery_order_draft_kirim_area"
								// 				).val(),
								// 				delivery_order_draft_kirim_invoice_pdf: "",
								// 				delivery_order_draft_kirim_invoice_dir: "",
								// 				pabrik_id: $("#deliveryorderdraft-pabrik_id").val(),
								// 				delivery_order_draft_ambil_nama: $(
								// 					"#deliveryorderdraft-delivery_order_draft_ambil_nama"
								// 				).val(),
								// 				delivery_order_draft_ambil_alamat: $(
								// 					"#deliveryorderdraft-delivery_order_draft_ambil_alamat"
								// 				).val(),
								// 				delivery_order_draft_ambil_telp: $(
								// 					"#deliveryorderdraft-delivery_order_draft_ambil_telepon"
								// 				).val(),
								// 				delivery_order_draft_ambil_provinsi: $(
								// 					"#deliveryorderdraft-delivery_order_draft_ambil_provinsi"
								// 				).val(),
								// 				delivery_order_draft_ambil_kota: $(
								// 					"#deliveryorderdraft-delivery_order_draft_ambil_kota"
								// 				).val(),
								// 				delivery_order_draft_ambil_kecamatan: $(
								// 					"#deliveryorderdraft-delivery_order_draft_ambil_kecamatan"
								// 				).val(),
								// 				delivery_order_draft_ambil_kelurahan: $(
								// 					"#deliveryorderdraft-delivery_order_draft_ambil_kelurahan"
								// 				).val(),
								// 				delivery_order_draft_ambil_kodepos: $(
								// 					"#deliveryorderdraft-delivery_order_draft_ambil_kodepos"
								// 				).val(),
								// 				delivery_order_draft_ambil_area: $(
								// 					"#deliveryorderdraft-delivery_order_draft_ambil_area"
								// 				).val(),
								// 				delivery_order_draft_update_who: "",
								// 				delivery_order_draft_update_tgl: $("#deliveryorderdraft-delivery_order_draft_update_tgl").val(),
								// 				delivery_order_draft_approve_who: "",
								// 				delivery_order_draft_approve_tgl: "",
								// 				delivery_order_draft_reject_who: "",
								// 				delivery_order_draft_reject_tgl: "",
								// 				delivery_order_draft_reject_reason: "",
								// 				tipe_delivery_order_id: $(
								// 						"#deliveryorderdraft-tipe_delivery_order_id")
								// 					.val(),
								// 				detail: arr_detail,
								// 				delivery_order_draft_input_pembayaran_tunai: $(
								// 					"#input_tunai").val()
								// 			},
								// 			dataType: "JSON",
								// 			success: function(data) {
								// 				if (data.type === 200) {
								// 					message_topright("success",
								// 						"Data berhasil diapprove");
								// 					setTimeout(() => {
								// 						location.href =
								// 							"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=" +
								// 							dod_id;
								// 					}, 500);
								// 				}

								// 				if (data.type === 201) {
								// 					let arrayOfErrorsToDisplay = [];
								// 					let indexError = [];
								// 					$.each(data.data, (index, item) => {
								// 						let response = item.data;
								// 						// arrayOfErrorsToDisplay = []
								// 						// indexError = []

								// 						indexError.push(index + 1);

								// 						arrayOfErrorsToDisplay.push({
								// 							title: 'Data Gagal diapprove!',
								// 							html: `Qty dari SKU <strong>${response.sku}</strong> tidak cukup, silahkan dicek kembali!`
								// 						});
								// 						// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
								// 					});

								// 					Swal.mixin({
								// 							icon: 'error',
								// 							confirmButtonText: 'Next &rarr;',
								// 							showCancelButton: true,
								// 							progressSteps: indexError
								// 						})
								// 						.queue(arrayOfErrorsToDisplay)
								// 				}

								// 				if (data.type === 202) {
								// 					message_topright("error", "Data gagal Diapprove");
								// 				}

								// 				if (data.type === 203) {
								// 					let arrayOfErrorsToDisplayEmptySku = [];
								// 					let indexErrorEmptySku = [];
								// 					arrayOfErrorsToDisplayEmptySku = []
								// 					indexErrorEmptySku = []
								// 					$.each(data.data, (index, item) => {
								// 						let response = item.data;

								// 						indexErrorEmptySku.push(index + 1);

								// 						arrayOfErrorsToDisplayEmptySku
								// 							.push({
								// 								title: 'Data Gagal diapprove!',
								// 								html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
								// 							});

								// 						// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
								// 					});

								// 					Swal.mixin({
								// 							icon: 'error',
								// 							confirmButtonText: 'Next &rarr;',
								// 							showCancelButton: true,
								// 							progressSteps: indexErrorEmptySku
								// 						})
								// 						.queue(arrayOfErrorsToDisplayEmptySku)
								// 				}

								// 				if (data.type === 204) {
								// 					let arrayOfErrorsToDisplaySkuStokKurang = [];
								// 					let indexErrorSkuStokKurang = [];
								// 					$.each(data.data, (index, item) => {
								// 						let response = item.data;

								// 						// arrayOfErrorsToDisplaySkuStokKurang
								// 						//     = []
								// 						// indexErrorSkuStokKurang = []

								// 						indexErrorSkuStokKurang.push(index +
								// 							1);

								// 						arrayOfErrorsToDisplaySkuStokKurang
								// 							.push({
								// 								title: 'Data Gagal diapprove!',
								// 								html: `Qty dari SKU <strong>${response.sku}</strong> kurang`
								// 							});
								// 						// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
								// 					});

								// 					Swal.mixin({
								// 							icon: 'error',
								// 							confirmButtonText: 'Next &rarr;',
								// 							showCancelButton: true,
								// 							progressSteps: indexErrorSkuStokKurang
								// 						})
								// 						.queue(arrayOfErrorsToDisplaySkuStokKurang)
								// 				}
								// 			}
								// 		});
								// 	}
								// });

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
						if (arr_table_sku > 0) {
							$("#table-sku-delivery-only > tbody tr").each(function() {
								var is_Qty = $(this).find("td:eq(11) input[type='number']");
								var is_tipe_stock = $(this).find("td:eq(6) select");
								// console.log(is_Qty);
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

									for (var index = 0; index < arr_sku_now.length; index++) {
										if (arr_sku_now[index] != "") {
											arr_detail.push({
												'sku_id': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_id").val(),
												'sku_kode': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_kode").val(),
												'sku_nama_produk': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_nama_produk").val(),
												'sku_harga_satuan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_harga_satuan").val(),
												'sku_disc_percent': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_disc_percent").val(),
												'sku_disc_rp': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_disc_rp").val(),
												'sku_harga_nett': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_harga_nett").val(),
												'sku_weight': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_weight").val(),
												'sku_weight_unit': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_weight_unit").val(),
												'sku_length': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_length").val(),
												'sku_length_unit': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_length_unit").val(),
												'sku_width': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_width").val(),
												'sku_width_unit': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_width_unit").val(),
												'sku_height': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_height").val(),
												'sku_height_unit': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_height_unit").val(),
												'sku_volume': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_volume").val(),
												'sku_volume_unit': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_volume_unit").val(),
												'sku_qty': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_qty").val(),
												'sku_keterangan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_keterangan").val(),
												'sku_request_expdate': $("#item-" + index +
														"-DeliveryOrderDetailDraft-sku_request_expdate")
													.val(),
												'sku_tipe_stock': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_tipe_stock").val(),
												'sku_filter_expdate': $("#item-" + index +
														"-DeliveryOrderDetailDraft-sku_filter_expdate")
													.val(),
												'sku_filter_expdatebulan': $("#item-" + index +
														"-DeliveryOrderDetailDraft-sku_filter_expdatebulan")
													.val(),
												'sku_satuan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_satuan").val(),
												'sku_kemasan': $("#item-" + index +
													"-DeliveryOrderDetailDraft-sku_kemasan").val()
											});
										}
									}

									messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!',
										'Iya, Simpan', 'Tidak, Tutup').then((result) => {
										if (result.value == true) {
											requestAjax(
												"<?= base_url('WMS/Distribusi/DeliveryOrderDraft/confirm_delivery_order_draft'); ?>", {
													delivery_order_draft_id: dod_id,
													sales_order_id: "",
													delivery_order_draft_kode: $(
														"#deliveryorderdraft-delivery_order_draft_kode"
													).val(),
													delivery_order_draft_yourref: "",
													client_wms_id: $(
														"#deliveryorderdraft-client_wms_id").val(),
													delivery_order_draft_tgl_buat_do: $(
														"#deliveryorderdraft-delivery_order_draft_tgl_buat_do"
													).val(),
													delivery_order_draft_tgl_expired_do: $(
														"#deliveryorderdraft-delivery_order_draft_tgl_expired_do"
													).val(),
													delivery_order_draft_tgl_surat_jalan: $(
														"#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan"
													).val(),
													delivery_order_draft_tgl_rencana_kirim: $(
														"#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim"
													).val(),
													delivery_order_draft_tgl_aktual_kirim: "",
													delivery_order_draft_keterangan: $(
														"#deliveryorderdraft-delivery_order_draft_keterangan"
													).val(),
													delivery_order_draft_status: "Draft",
													delivery_order_draft_is_prioritas: 0,
													delivery_order_draft_is_need_packing: 0,
													delivery_order_draft_tipe_layanan: document
														.querySelector(
															'input[id="deliveryorderdraft-delivery_order_draft_tipe_layanan"]:checked'
														).value,
													delivery_order_draft_tipe_pembayaran: document
														.querySelector(
															'input[id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran"]:checked'
														).value,
													delivery_order_draft_sesi_pengiriman: 0,
													delivery_order_draft_request_tgl_kirim: "",
													delivery_order_draft_request_jam_kirim: "",
													tipe_pengiriman_id: "",
													nama_tipe: "",
													confirm_rate: "",
													delivery_order_draft_reff_id: "",
													delivery_order_draft_reff_no: "",
													delivery_order_draft_total: "",
													unit_mandiri_id: "<?= $this->session->userdata('unit_mandiri_id') ?>",
													depo_id: "<?= $this->session->userdata('depo_id') ?>",
													client_pt_id: $("#deliveryorderdraft-client_pt_id")
														.val(),
													delivery_order_draft_kirim_nama: $(
														"#deliveryorderdraft-delivery_order_draft_kirim_nama"
													).val(),
													delivery_order_draft_kirim_alamat: $(
														"#deliveryorderdraft-delivery_order_draft_kirim_alamat"
													).val(),
													delivery_order_draft_kirim_telp: $(
														"#deliveryorderdraft-delivery_order_draft_kirim_telepon"
													).val(),
													delivery_order_draft_kirim_provinsi: $(
														"#deliveryorderdraft-delivery_order_draft_kirim_provinsi"
													).val(),
													delivery_order_draft_kirim_kota: $(
														"#deliveryorderdraft-delivery_order_draft_kirim_kota"
													).val(),
													delivery_order_draft_kirim_kecamatan: $(
														"#deliveryorderdraft-delivery_order_draft_kirim_kecamatan"
													).val(),
													delivery_order_draft_kirim_kelurahan: $(
														"#deliveryorderdraft-delivery_order_draft_kirim_kelurahan"
													).val(),
													delivery_order_draft_kirim_kodepos: $(
														"#deliveryorderdraft-delivery_order_draft_kirim_kodepos"
													).val(),
													delivery_order_draft_kirim_area: $(
														"#deliveryorderdraft-delivery_order_draft_kirim_area"
													).val(),
													delivery_order_draft_kirim_invoice_pdf: "",
													delivery_order_draft_kirim_invoice_dir: "",
													pabrik_id: $("#deliveryorderdraft-pabrik_id").val(),
													delivery_order_draft_ambil_nama: $(
														"#deliveryorderdraft-delivery_order_draft_ambil_nama"
													).val(),
													delivery_order_draft_ambil_alamat: $(
														"#deliveryorderdraft-delivery_order_draft_ambil_alamat"
													).val(),
													delivery_order_draft_ambil_telp: $(
														"#deliveryorderdraft-delivery_order_draft_ambil_telepon"
													).val(),
													delivery_order_draft_ambil_provinsi: $(
														"#deliveryorderdraft-delivery_order_draft_ambil_provinsi"
													).val(),
													delivery_order_draft_ambil_kota: $(
														"#deliveryorderdraft-delivery_order_draft_ambil_kota"
													).val(),
													delivery_order_draft_ambil_kecamatan: $(
														"#deliveryorderdraft-delivery_order_draft_ambil_kecamatan"
													).val(),
													delivery_order_draft_ambil_kelurahan: $(
														"#deliveryorderdraft-delivery_order_draft_ambil_kelurahan"
													).val(),
													delivery_order_draft_ambil_kodepos: $(
														"#deliveryorderdraft-delivery_order_draft_ambil_kodepos"
													).val(),
													delivery_order_draft_ambil_area: $(
														"#deliveryorderdraft-delivery_order_draft_ambil_area"
													).val(),
													delivery_order_draft_update_who: "",
													delivery_order_draft_update_tgl: $(
														"#deliveryorderdraft-delivery_order_draft_update_tgl"
													).val(),
													delivery_order_draft_approve_who: "",
													delivery_order_draft_approve_tgl: "",
													delivery_order_draft_reject_who: "",
													delivery_order_draft_reject_tgl: "",
													delivery_order_draft_reject_reason: "",
													tipe_delivery_order_id: $(
														"#deliveryorderdraft-tipe_delivery_order_id"
													).val(),
													detail: arr_detail,
													detail2: arr_detail2,
													delivery_order_draft_input_pembayaran_tunai: $(
														"#input_tunai").val()
												}, "POST", "JSON",
												function(data) {
													console.log(data);
													if (data.type === 200) {
														message_topright("success",
															"Data berhasil diapprove");
														setTimeout(() => {
															location.href =
																"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=" +
																dod_id;
														}, 500);
													}
													if (data.type === 400) {
														messageNotSameLastUpdated();
														return false;
													}
													if (data.type === 201) {
														let arrayOfErrorsToDisplay = [];
														let indexError = [];
														$.each(data.data, (index, item) => {
															let response = item.data;
															// arrayOfErrorsToDisplay = []
															// indexError = []
															indexError.push(index + 1);
															arrayOfErrorsToDisplay.push({
																title: 'Data Gagal diapprove!',
																html: `Qty dari SKU <strong>${response.sku}</strong> tidak cukup, silahkan dicek kembali!`
															});
															// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
														});
														Swal.mixin({
															icon: 'error',
															confirmButtonText: 'Next &rarr;',
															showCancelButton: true,
															progressSteps: indexError
														}).queue(arrayOfErrorsToDisplay)
													}
													if (data.type === 202) {
														message_topright("error",
															"Data gagal Diapprove");
													}
													if (data.type === 203) {
														let arrayOfErrorsToDisplayEmptySku = [];
														let indexErrorEmptySku = [];
														arrayOfErrorsToDisplayEmptySku = []
														indexErrorEmptySku = []
														$.each(data.data, (index, item) => {
															let response = item.data;
															indexErrorEmptySku.push(index + 1);
															arrayOfErrorsToDisplayEmptySku
																.push({
																	title: 'Data Gagal diapprove!',
																	html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
																});
															// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
														});
														Swal.mixin({
															icon: 'error',
															confirmButtonText: 'Next &rarr;',
															showCancelButton: true,
															progressSteps: indexErrorEmptySku
														}).queue(arrayOfErrorsToDisplayEmptySku)
													}
													if (data.type === 204) {
														let arrayOfErrorsToDisplaySkuStokKurang = [];
														let indexErrorSkuStokKurang = [];
														$.each(data.data, (index, item) => {
															let response = item.data;
															// arrayOfErrorsToDisplaySkuStokKurang
															//     = []
															// indexErrorSkuStokKurang = []
															indexErrorSkuStokKurang.push(index +
																1);
															arrayOfErrorsToDisplaySkuStokKurang
																.push({
																	title: 'Data Gagal diapprove!',
																	html: `Qty dari SKU <strong>${response.sku}</strong> kurang`
																});
															// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
														});
														Swal.mixin({
															icon: 'error',
															confirmButtonText: 'Next &rarr;',
															showCancelButton: true,
															progressSteps: indexErrorSkuStokKurang
														}).queue(
															arrayOfErrorsToDisplaySkuStokKurang)
													}

													if ($("#deliveryorderdraft-tipe_delivery_order_id")
														.val() == "C5BE83E2-01E8-4E24-B766-26BB4158F2CD"
													) {

														$.each(data, function(i, v) {
															if (v.type == 210) {
																let arrayOfErrorsToDisplay = [];
																let indexError = [];
																indexError = 0;
																arrayOfErrorsToDisplay.push({
																	title: 'Data Gagal diapprove!',
																	html: `Jumlah Qty dari SKU Stock <strong>${v.sku_nama_produk}</strong> tidak cocok, silahkan dicek kembali!`
																});
																Swal.mixin({
																	icon: 'error',
																	confirmButtonText: 'Next &rarr;',
																	showCancelButton: true,
																	progressSteps: indexError
																}).queue(
																	arrayOfErrorsToDisplay)
															}
															if (v.type == 211) {
																message_topright("success",
																	"SKU Stock Cocok");
															}
														})
													}
												}, "#btnconfirmdodraft")
										}
									});

									// Swal.fire({
									// 	title: "Apakah anda yakin konfirmasi data ini ?",
									// 	text: "Data yang sudah dikonfirmasi tidak bisa diubah!",
									// 	icon: "warning",
									// 	showCancelButton: true,
									// 	confirmButtonColor: "#3085d6",
									// 	cancelButtonColor: "#d33",
									// 	confirmButtonText: "Ya, Simpan",
									// 	cancelButtonText: "Tidak, Tutup"
									// }).then((result) => {
									// 	if (result.value == true) {
									// 		//ajax save data
									// 		$.ajax({
									// 			async: false,
									// 			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/confirm_delivery_order_draft'); ?>",
									// 			type: "POST",
									// 			data: {
									// 				delivery_order_draft_id: dod_id,
									// 				sales_order_id: "",
									// 				delivery_order_draft_kode: $("#deliveryorderdraft-delivery_order_draft_kode").val(),
									// 				delivery_order_draft_yourref: "",
									// 				client_wms_id: $("#deliveryorderdraft-client_wms_id").val(),
									// 				delivery_order_draft_tgl_buat_do: $("#deliveryorderdraft-delivery_order_draft_tgl_buat_do").val(),
									// 				delivery_order_draft_tgl_expired_do: $("#deliveryorderdraft-delivery_order_draft_tgl_expired_do").val(),
									// 				delivery_order_draft_tgl_surat_jalan: $("#deliveryorderdraft-delivery_order_draft_tgl_surat_jalan").val(),
									// 				delivery_order_draft_tgl_rencana_kirim: $("#deliveryorderdraft-delivery_order_draft_tgl_rencana_kirim").val(),
									// 				delivery_order_draft_tgl_aktual_kirim: "",
									// 				delivery_order_draft_keterangan: $("#deliveryorderdraft-delivery_order_draft_keterangan").val(),
									// 				delivery_order_draft_status: "Draft",
									// 				delivery_order_draft_is_prioritas: 0,
									// 				delivery_order_draft_is_need_packing: 0,
									// 				delivery_order_draft_tipe_layanan: document.querySelector('input[id="deliveryorderdraft-delivery_order_draft_tipe_layanan"]:checked').value,
									// 				delivery_order_draft_tipe_pembayaran: document.querySelector('input[id="deliveryorderdraft-delivery_order_draft_tipe_pembayaran"]:checked').value,
									// 				delivery_order_draft_sesi_pengiriman: 0,
									// 				delivery_order_draft_request_tgl_kirim: "",
									// 				delivery_order_draft_request_jam_kirim: "",
									// 				tipe_pengiriman_id: "",
									// 				nama_tipe: "",
									// 				confirm_rate: "",
									// 				delivery_order_draft_reff_id: "",
									// 				delivery_order_draft_reff_no: "",
									// 				delivery_order_draft_total: "",
									// 				unit_mandiri_id: "<?= $this->session->userdata('unit_mandiri_id') ?>",
									// 				depo_id: "<?= $this->session->userdata('depo_id') ?>",
									// 				client_pt_id: $("#deliveryorderdraft-client_pt_id").val(),
									// 				delivery_order_draft_kirim_nama: $("#deliveryorderdraft-delivery_order_draft_kirim_nama").val(),
									// 				delivery_order_draft_kirim_alamat: $("#deliveryorderdraft-delivery_order_draft_kirim_alamat").val(),
									// 				delivery_order_draft_kirim_telp: $("#deliveryorderdraft-delivery_order_draft_kirim_telepon").val(),
									// 				delivery_order_draft_kirim_provinsi: $("#deliveryorderdraft-delivery_order_draft_kirim_provinsi").val(),
									// 				delivery_order_draft_kirim_kota: $("#deliveryorderdraft-delivery_order_draft_kirim_kota").val(),
									// 				delivery_order_draft_kirim_kecamatan: $("#deliveryorderdraft-delivery_order_draft_kirim_kecamatan").val(),
									// 				delivery_order_draft_kirim_kelurahan: $("#deliveryorderdraft-delivery_order_draft_kirim_kelurahan").val(),
									// 				delivery_order_draft_kirim_kodepos: $("#deliveryorderdraft-delivery_order_draft_kirim_kodepos").val(),
									// 				delivery_order_draft_kirim_area: $("#deliveryorderdraft-delivery_order_draft_kirim_area").val(),
									// 				delivery_order_draft_kirim_invoice_pdf: "",
									// 				delivery_order_draft_kirim_invoice_dir: "",
									// 				pabrik_id: $("#deliveryorderdraft-pabrik_id").val(),
									// 				delivery_order_draft_ambil_nama: $("#deliveryorderdraft-delivery_order_draft_ambil_nama").val(),
									// 				delivery_order_draft_ambil_alamat: $("#deliveryorderdraft-delivery_order_draft_ambil_alamat").val(),
									// 				delivery_order_draft_ambil_telp: $("#deliveryorderdraft-delivery_order_draft_ambil_telepon").val(),
									// 				delivery_order_draft_ambil_provinsi: $("#deliveryorderdraft-delivery_order_draft_ambil_provinsi").val(),
									// 				delivery_order_draft_ambil_kota: $("#deliveryorderdraft-delivery_order_draft_ambil_kota").val(),
									// 				delivery_order_draft_ambil_kecamatan: $("#deliveryorderdraft-delivery_order_draft_ambil_kecamatan").val(),
									// 				delivery_order_draft_ambil_kelurahan: $("#deliveryorderdraft-delivery_order_draft_ambil_kelurahan").val(),
									// 				delivery_order_draft_ambil_kodepos: $("#deliveryorderdraft-delivery_order_draft_ambil_kodepos").val(),
									// 				delivery_order_draft_ambil_area: $("#deliveryorderdraft-delivery_order_draft_ambil_area").val(),
									// 				delivery_order_draft_update_who: "",
									// 				delivery_order_draft_update_tgl: $("#deliveryorderdraft-delivery_order_draft_update_tgl").val(),
									// 				delivery_order_draft_approve_who: "",
									// 				delivery_order_draft_approve_tgl: "",
									// 				delivery_order_draft_reject_who: "",
									// 				delivery_order_draft_reject_tgl: "",
									// 				delivery_order_draft_reject_reason: "",
									// 				tipe_delivery_order_id: $("#deliveryorderdraft-tipe_delivery_order_id").val(),
									// 				detail: arr_detail,
									// 				delivery_order_draft_input_pembayaran_tunai: $("#input_tunai").val()
									// 			},
									// 			dataType: "JSON",
									// 			success: function(data) {
									// 				if (data.type === 200) {
									// 					message_topright("success",
									// 						"Data berhasil diapprove");
									// 					setTimeout(() => {
									// 						location.href =
									// 							"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=" +
									// 							dod_id;
									// 					}, 500);
									// 				}

									// 				if (data.type === 201) {
									// 					let arrayOfErrorsToDisplay = [];
									// 					let indexError = [];
									// 					$.each(data.data, (index, item) => {
									// 						let response = item.data;
									// 						// arrayOfErrorsToDisplay = []
									// 						// indexError = []

									// 						indexError.push(index + 1);

									// 						arrayOfErrorsToDisplay.push({
									// 							title: 'Data Gagal diapprove!',
									// 							html: `Qty dari SKU <strong>${response.sku}</strong> tidak cukup, silahkan dicek kembali!`
									// 						});
									// 						// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
									// 					});

									// 					Swal.mixin({
									// 							icon: 'error',
									// 							confirmButtonText: 'Next &rarr;',
									// 							showCancelButton: true,
									// 							progressSteps: indexError
									// 						})
									// 						.queue(arrayOfErrorsToDisplay)
									// 				}

									// 				if (data.type === 202) {
									// 					message_topright("error",
									// 						"Data gagal Diapprove");
									// 				}

									// 				if (data.type === 203) {
									// 					let arrayOfErrorsToDisplayEmptySku = [];
									// 					let indexErrorEmptySku = [];
									// 					$.each(data.data, (index, item) => {
									// 						let response = item.data;

									// 						// arrayOfErrorsToDisplayEmptySku
									// 						//     = []
									// 						// indexErrorEmptySku = []

									// 						indexErrorEmptySku.push(index +
									// 							1);


									// 						arrayOfErrorsToDisplayEmptySku
									// 							.push({
									// 								title: 'Data Gagal diapprove!',
									// 								html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
									// 							});

									// 						// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
									// 					});

									// 					Swal.mixin({
									// 							icon: 'error',
									// 							confirmButtonText: 'Next &rarr;',
									// 							showCancelButton: true,
									// 							progressSteps: indexErrorEmptySku
									// 						})
									// 						.queue(arrayOfErrorsToDisplayEmptySku)
									// 				}

									// 				if (data.type === 204) {
									// 					let
									// 						arrayOfErrorsToDisplaySkuStokKurang = [];
									// 					let indexErrorSkuStokKurang = [];
									// 					$.each(data.data, (index, item) => {
									// 						let response = item.data;

									// 						// arrayOfErrorsToDisplaySkuStokKurang
									// 						//     = []
									// 						// indexErrorSkuStokKurang = []

									// 						indexErrorSkuStokKurang.push(
									// 							index +
									// 							1);

									// 						arrayOfErrorsToDisplaySkuStokKurang
									// 							.push({
									// 								title: 'Data Gagal diapprove!',
									// 								html: `Qty dari SKU <strong>${response.sku}</strong> kurang`
									// 							});
									// 						// message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
									// 					});

									// 					Swal.mixin({
									// 							icon: 'error',
									// 							confirmButtonText: 'Next &rarr;',
									// 							showCancelButton: true,
									// 							progressSteps: indexErrorSkuStokKurang
									// 						})
									// 						.queue(
									// 							arrayOfErrorsToDisplaySkuStokKurang)
									// 				}
									// 			}
									// 		});
									// 	}
									// });

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
					}
				} else {
					message("Pilih Customer!", "Customer belum dipilih", "error");

				}

			} else {
				message("Pilih Tipe DO!", "Tipe delivery order draft belum dipilih", "error");
			}

		} else {
			let alert_tes = GetLanguageByKode('CAPTION-ALERT-AREATIDAKDIPILIH');
			message_custom("Warning", "warning", alert_tes);
		}
	});

	$("#btnrejectdodraft").on("click", function() {
		var dod_id = $("#deliveryorderdraft-delivery_order_draft_id").val();
		var delivery_order_draft_update_tgl = $("#deliveryorderdraft-delivery_order_draft_update_tgl").val();

		messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup').then((
			result) => {
			if (result.value == true) {
				requestAjax(
					"<?= base_url('WMS/Distribusi/DeliveryOrderDraft/reject_delivery_order_draft') ?>", {
						delivery_order_draft_id: dod_id,
						delivery_order_draft_update_tgl: delivery_order_draft_update_tgl
					}, "POST", "JSON",
					function(response) {
						if (response == 1) {
							message_topright("success", "Data berhasil dibatalkan");
							setTimeout(() => {
								location.href =
									"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=" +
									dod_id;
							}, 500);
						} else if (response == 2) {
							messageNotSameLastUpdated();
							return false;
						} else {
							message_topright("error", "Data gagal dibatalkan");
						}
					}, "#btnrejectdodraft")
			}
		});

		// Swal.fire({
		// 	title: "Apakah anda yakin membatalkan data ini?",
		// 	text: "Data yang sudah dibatalkan tidak bisa diubah!",
		// 	icon: "warning",
		// 	showCancelButton: true,
		// 	confirmButtonColor: "#3085d6",
		// 	cancelButtonColor: "#d33",
		// 	confirmButtonText: "Ya, Simpan",
		// 	cancelButtonText: "Tidak, Tutup"
		// }).then((result) => {
		// 	if (result.value == true) {
		// 		//ajax save data
		// 		$.ajax({
		// 			async: false,
		// 			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/reject_delivery_order_draft'); ?>",
		// 			type: "POST",
		// 			data: {
		// 				delivery_order_draft_id: dod_id
		// 			},
		// 			dataType: "JSON",
		// 			success: function(data) {
		// 				if (data == 1) {
		// 					message_topright("success", "Data berhasil dibatalkan");
		// 					setTimeout(() => {
		// 						location.href =
		// 							"<?= base_url(); ?>WMS/Distribusi/DeliveryOrderDraft/detail/?id=" +
		// 							dod_id;
		// 					}, 500);
		// 				} else {
		// 					message_topright("error", "Data gagal dibatalkan");
		// 				}
		// 			}
		// 		});
		// 	}
		// });
	});

	$("#btnapprovdodraft").on("click", function() {
		var count_do_list = $("#jml_do").val();
		var cek_success = 0;
		var principle = $("#filter-principle").val();
		// var count_do_list = $("#table_list_data_do_draft > tbody tr").length;

		// console.log(count_do_list);


		if (count_do_list > 0) {
			arr_do_draft = [];
			for (var i = 0; i < count_do_list; i++) {
				var checked = $('[id="check-do-' + i + '"]:checked').length;
				var do_id = "'" + $("#check-do-" + i).val() + "'";
				var do_status = $("#item-" + i + "-ListDODraft-delivery_order_draft_status").val();

				// console.log(i + " - " + checked + " - " + do_id + " - " + do_status);

				if (checked > 0 && do_status == "Draft") {
					arr_do_draft.push(do_id);
				}
			}

			// console.log(arr_do_draft);

			// $("#table_list_data_do_draft > tbody tr").each(function(i) {
			//     var checked = $('[id="check-do-' + i + '"]:checked').length;
			//     var do_id = "'" + $("#check-do-" + i).val() + "'";
			//     var do_status = $("#item-" + i + "-ListDODraft-delivery_order_draft_status").val();

			//     if (checked > 0 && do_status == "Draft") {
			//         arr_do_draft.push(do_id);
			//     }
			// });

			var result = arr_do_draft.reduce((unique, o) => {
				if (!unique.some(obj => obj === o)) {
					unique.push(o);
				}
				return unique;
			}, []);

			arr_do_draft = result;

			// console.log(arr_do_draft);

			if (arr_do_draft.length > 0) {
				Swal.fire({
					title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
					icon: "warning",
					showCancelButton: true,
					confirmButtonColor: "#3085d6",
					cancelButtonColor: "#d33",
					confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
					cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
				}).then((result) => {
					if (result.value == true) {
						//ajax save data
						$("#btnapprovdodraft").prop("disabled", true);
						$("#btn-search-data-do-draft").prop("disabled", true);
						$("#loadingviewdodraft").show();

						delete_delivery_order_draft_detail_msg(principle);

						setTimeout(function() {
							$.ajax({
								async: false,
								type: 'POST',
								url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetDeliveryOrderDraftById'); ?>",
								data: {
									arr_do_draft: arr_do_draft
								},
								dataType: "JSON",
								success: function(response) {
									if (response.DOHeader != 0) {

										$.each(response.DOHeader, function(i, v) {
											if (v.client_wms_id !== null) {
												$.ajax({
													async: false,
													url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetDeliveryOrderDraftDetailByListId'); ?>",
													type: "POST",
													data: {
														id: v
															.delivery_order_draft_id
													},
													dataType: "JSON",
													success: function(
														response) {

														arr_detail
															= [];
														if (response
															.DODetail !=
															0) {
															$.each(response
																.DODetail,
																function(
																	i2,
																	v2
																) {
																	arr_detail
																		.push({
																			'delivery_order_detail_draft_id': v2
																				.delivery_order_detail_draft_id,
																			'delivery_order_draft_id': v2
																				.delivery_order_draft_id,
																			'sku_id': v2
																				.sku_id,
																			'gudang_id': v2
																				.gudang_id,
																			'gudang_detail_id': v2
																				.gudang_detail_id,
																			'sku_kode': v2
																				.sku_kode,
																			'sku_nama_produk': v2
																				.sku_nama_produk,
																			'sku_harga_satuan': v2
																				.sku_harga_satuan,
																			'sku_disc_percent': v2
																				.sku_disc_percent,
																			'sku_disc_rp': v2
																				.sku_disc_rp,
																			'sku_harga_nett': v2
																				.sku_harga_nett,
																			'sku_request_expdate': v2
																				.sku_request_expdate,
																			'sku_filter_expdate': v2
																				.sku_filter_expdate,
																			'sku_filter_expdatebulan': v2
																				.sku_filter_expdatebulan,
																			'sku_filter_expdatetahun': v2
																				.sku_filter_expdatetahun,
																			'sku_weight': v2
																				.sku_weight,
																			'sku_weight_unit': v2
																				.sku_weight_unit,
																			'sku_length': v2
																				.sku_length,
																			'sku_length_unit': v2
																				.sku_length_unit,
																			'sku_width': v2
																				.sku_width,
																			'sku_width_unit': v2
																				.sku_width_unit,
																			'sku_height': v2
																				.sku_height,
																			'sku_height_unit': v2
																				.sku_height_unit,
																			'sku_volume': v2
																				.sku_volume,
																			'sku_volume_unit': v2
																				.sku_volume_unit,
																			'sku_qty': v2
																				.sku_qty,
																			'sku_keterangan': v2
																				.sku_keterangan,
																			'sku_tipe_stock': v2
																				.tipe_stock,
																			'use_gudang_bonus': v2
																				.use_gudang_bonus,
																		});
																}
															);
														}
													},
													error: function(xhr,
														status,
														error) {
														$("#btnapprovdodraft")
															.prop(
																"disabled",
																false
															);
														$("#btn-search-data-do-draft")
															.prop(
																"disabled",
																false
															);
														$("#loadingviewdodraft")
															.hide();
														message_topright
															("error",
																"Data gagal Diapprove"
															);
													}
												});

												$.ajax({
													async: false,
													url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/GetDeliveryOrderDraftDetail2ByListId'); ?>",
													type: "POST",
													data: {
														id: v
															.delivery_order_draft_id
													},
													dataType: "JSON",
													success: function(
														response) {
														arr_detail2
															= [];
														if (response
															.DODetail2 !=
															0) {
															$.each(response
																.DODetail2,
																function(
																	i2,
																	v2
																) {
																	arr_detail2
																		.push({
																			'idx': v2
																				.delivery_order_detail2_draft_id,
																			'sku_id': v2
																				.sku_id,
																			'sku_kode': '',
																			'sku_nama_produk': '',
																			'sku_stock_id': v2
																				.sku_stock_id,
																			'sku_stock_expired_date': v2
																				.sku_expdate,
																			'sku_qty': v2
																				.sku_qty,
																			'sku_konversi_faktor': v2
																				.sku_qty_composite
																		});
																}
															);
														}
													},
													error: function(xhr,
														status,
														error) {
														$("#btnapprovdodraft")
															.prop(
																"disabled",
																false
															);
														$("#btn-search-data-do-draft")
															.prop(
																"disabled",
																false
															);
														$("#loadingviewdodraft")
															.hide();
														message_topright
															("error",
																"Data gagal Diapprove"
															);
													}
												});

												if (arr_detail.length > 0) {
													if (v
														.delivery_order_draft_kirim_area !=
														"") {
														if (v
															.delivery_order_draft_status ==
															"Draft") {

															$.ajax({
																async: false,
																url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/confirm_delivery_order_draft'); ?>",
																type: "POST",
																data: {
																	delivery_order_draft_id: v
																		.delivery_order_draft_id,
																	sales_order_id: v
																		.sales_order_id,
																	delivery_order_draft_kode: v
																		.delivery_order_draft_kode,
																	delivery_order_draft_yourref: v
																		.delivery_order_draft_yourref,
																	client_wms_id: v
																		.client_wms_id,
																	delivery_order_draft_tgl_buat_do: v
																		.delivery_order_draft_tgl_buat_do,
																	delivery_order_draft_tgl_expired_do: v
																		.delivery_order_draft_tgl_expired_do,
																	delivery_order_draft_tgl_surat_jalan: v
																		.delivery_order_draft_tgl_surat_jalan,
																	delivery_order_draft_tgl_rencana_kirim: v
																		.delivery_order_draft_tgl_rencana_kirim,
																	delivery_order_draft_tgl_aktual_kirim: v
																		.delivery_order_draft_tgl_aktual_kirim,
																	delivery_order_draft_keterangan: v
																		.delivery_order_draft_keterangan,
																	delivery_order_draft_status: "Approved",
																	delivery_order_draft_is_prioritas: v
																		.delivery_order_draft_is_prioritas,
																	delivery_order_draft_is_need_packing: v
																		.delivery_order_draft_is_need_packing,
																	delivery_order_draft_tipe_layanan: v
																		.delivery_order_draft_tipe_layanan,
																	delivery_order_draft_tipe_pembayaran: v
																		.delivery_order_draft_tipe_pembayaran,
																	delivery_order_draft_sesi_pengiriman: v
																		.delivery_order_draft_sesi_pengiriman,
																	delivery_order_draft_request_tgl_kirim: v
																		.delivery_order_draft_request_tgl_kirim,
																	delivery_order_draft_request_jam_kirim: v
																		.delivery_order_draft_request_jam_kirim,
																	tipe_pengiriman_id: v
																		.tipe_pengiriman_id,
																	nama_tipe: v
																		.nama_tipe,
																	confirm_rate: v
																		.confirm_rate,
																	delivery_order_draft_reff_id: v
																		.delivery_order_draft_reff_id,
																	delivery_order_draft_reff_no: v
																		.delivery_order_draft_reff_no,
																	delivery_order_draft_total: v
																		.delivery_order_draft_total,
																	unit_mandiri_id: v
																		.unit_mandiri_id,
																	depo_id: v
																		.depo_id,
																	client_pt_id: v
																		.client_pt_id,
																	delivery_order_draft_kirim_nama: v
																		.delivery_order_draft_kirim_nama,
																	delivery_order_draft_kirim_alamat: v
																		.delivery_order_draft_kirim_alamat,
																	delivery_order_draft_kirim_telp: v
																		.delivery_order_draft_kirim_telp,
																	delivery_order_draft_kirim_provinsi: v
																		.delivery_order_draft_kirim_provinsi,
																	delivery_order_draft_kirim_kota: v
																		.delivery_order_draft_kirim_kota,
																	delivery_order_draft_kirim_kecamatan: v
																		.delivery_order_draft_kirim_kecamatan,
																	delivery_order_draft_kirim_kelurahan: v
																		.delivery_order_draft_kirim_kelurahan,
																	delivery_order_draft_kirim_kodepos: v
																		.delivery_order_draft_kirim_kodepos,
																	delivery_order_draft_kirim_area: v
																		.delivery_order_draft_kirim_area,
																	delivery_order_draft_kirim_invoice_pdf: v
																		.delivery_order_draft_kirim_invoice_pdf,
																	delivery_order_draft_kirim_invoice_dir: v
																		.delivery_order_draft_kirim_invoice_dir,
																	pabrik_id: v
																		.pabrik_id,
																	delivery_order_draft_ambil_nama: v
																		.delivery_order_draft_ambil_nama,
																	delivery_order_draft_ambil_alamat: v
																		.delivery_order_draft_ambil_alamat,
																	delivery_order_draft_ambil_telp: v
																		.delivery_order_draft_ambil_telp,
																	delivery_order_draft_ambil_provinsi: v
																		.delivery_order_draft_ambil_provinsi,
																	delivery_order_draft_ambil_kota: v
																		.delivery_order_draft_ambil_kota,
																	delivery_order_draft_ambil_kecamatan: v
																		.delivery_order_draft_ambil_kecamatan,
																	delivery_order_draft_ambil_kelurahan: v
																		.delivery_order_draft_ambil_kelurahan,
																	delivery_order_draft_ambil_kodepos: v
																		.delivery_order_draft_ambil_kodepos,
																	delivery_order_draft_ambil_area: v
																		.delivery_order_draft_ambil_area,
																	delivery_order_draft_update_who: v
																		.delivery_order_draft_update_who,
																	delivery_order_draft_update_tgl: v
																		.delivery_order_draft_update_tgl,
																	delivery_order_draft_approve_who: v
																		.delivery_order_draft_approve_who,
																	delivery_order_draft_approve_tgl: v
																		.delivery_order_draft_approve_tgl,
																	delivery_order_draft_reject_who: v
																		.delivery_order_draft_reject_who,
																	delivery_order_draft_reject_tgl: v
																		.delivery_order_draft_reject_tgl,
																	delivery_order_draft_reject_reason: v
																		.delivery_order_draft_reject_reason,
																	tipe_delivery_order_id: v
																		.tipe_delivery_order_id,
																	is_from_so: v
																		.is_from_so,
																	delivery_order_draft_nominal_tunai: v
																		.delivery_order_draft_nominal_tunai,
																	delivery_order_draft_attachment: v
																		.delivery_order_draft_attachment,
																	is_promo: v
																		.is_promo,
																	is_canvas: v
																		.is_canvas,
																	detail: arr_detail,
																	detail2: arr_detail2
																},
																dataType: "JSON",
																success: function(
																	data
																) {
																	console
																		.log(
																			data
																			.data
																		);
																	if (data
																		.type ===
																		200
																	) {
																		cek_success++;
																	}
																	if (data
																		.type ===
																		201
																	) {
																		let alert_tes =
																			GetLanguageByKode(
																				"CAPTION-ALERT-QTYDOTIDAKCUKUP"
																			);
																		var msg =
																			"<span style='font-weight:bold'>DO " +
																			v
																			.delivery_order_draft_kode +
																			"</span> " +
																			alert_tes;
																		var msgtype =
																			'error';
																		//if (!window.__cfRLUnblockHandlers) return false;
																		new PNotify
																			({
																				title: 'Info',
																				text: msg,
																				type: msgtype,
																				styling: 'bootstrap3',
																				delay: 3000,
																				stack: stack_center
																			});
																	}
																	if (data
																		.type ===
																		202
																	) {
																		message_topright
																			("error",
																				"Data gagal Diapprove"
																			);
																	}
																	if (data
																		.type ===
																		203
																	) {
																		let alert_tes =
																			GetLanguageByKode(
																				"CAPTION-ALERT-QTYDOTIDAKADASTOCK"
																			);
																		var msg =
																			"<span style='font-weight:bold'>DO " +
																			v
																			.delivery_order_draft_kode +
																			"</span> " +
																			alert_tes;
																		var msgtype =
																			'error';
																		//if (!window.__cfRLUnblockHandlers) return false;
																		new PNotify
																			({
																				title: 'Info',
																				text: msg,
																				type: msgtype,
																				styling: 'bootstrap3',
																				delay: 3000,
																				stack: stack_center
																			});
																		$.each(data
																			.data,
																			(index,
																				item
																			) => {
																				let response =
																					item
																					.data;

																				$.ajax({
																					async: false,
																					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																					type: "POST",
																					dataType: "JSON",
																					data: {
																						delivery_order_draft_id: v
																							.delivery_order_draft_id,
																						sku_kode: response
																							.sku_kode,
																						msg: "Qty dari SKU <strong>" +
																							response
																							.sku_kode +
																							", " +
																							response
																							.sku +
																							"</strong> tidak cukup, silahkan dicek kembali!!",
																						principle: v
																							.principle_id,
																					},
																					success: function(
																						data
																					) {
																						console
																							.log(
																								"insert_delivery_order_draft_detail_msg success"
																							);
																					}
																				});
																			}
																		);
																		// let arrayOfErrorsToDisplayEmptySku = [];
																		// let indexErrorEmptySku = [];
																		// arrayOfErrorsToDisplayEmptySku = []
																		// indexErrorEmptySku = []
																		// $.each(data.data, (index, item) => {
																		//     let response = item.data;
																		//     indexErrorEmptySku.push(index + 1);
																		//     arrayOfErrorsToDisplayEmptySku
																		//         .push({
																		//             title: 'Data Gagal diapprove!',
																		//             html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
																		//         });
																		//     // message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
																		// });
																		// Swal.mixin({
																		//         icon: 'error',
																		//         confirmButtonText: 'Next &rarr;',
																		//         showCancelButton: true,
																		//         progressSteps: indexErrorEmptySku
																		//     })
																		//     .queue(arrayOfErrorsToDisplayEmptySku)
																	}
																	if (data
																		.type ===
																		204
																	) {
																		let alert_tes =
																			GetLanguageByKode(
																				"CAPTION-ALERT-QTYDOKURANG"
																			);
																		var msg =
																			"<span style='font-weight:bold'>DO " +
																			v
																			.delivery_order_draft_kode +
																			"</span> " +
																			alert_tes;
																		var msgtype =
																			'error';
																		//if (!window.__cfRLUnblockHandlers) return false;
																		new PNotify
																			({
																				title: 'Info',
																				text: msg,
																				type: msgtype,
																				styling: 'bootstrap3',
																				delay: 3000,
																				stack: stack_center
																			});
																		$.each(data
																			.data,
																			(index,
																				item
																			) => {
																				let response =
																					item
																					.data;
																				// arrayOfErrorsToDisplay = []
																				// indexError = []
																				$.ajax({
																					async: false,
																					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																					type: "POST",
																					dataType: "JSON",
																					data: {
																						delivery_order_draft_id: v
																							.delivery_order_draft_id,
																						sku_kode: response
																							.sku_kode,
																						msg: "Qty dari SKU <strong>" +
																							response
																							.sku_kode +
																							", " +
																							response
																							.sku +
																							"</strong> kurang!!",
																						principle: v
																							.principle_id
																					},
																					success: function(
																						data
																					) {
																						console
																							.log(
																								"insert_delivery_order_draft_detail_msg success"
																							);
																					}
																				});
																			}
																		);

																	}
																	if (data
																		.type ===
																		205
																	) {
																		let alert_tes =
																			GetLanguageByKode(
																				"CAPTION-ALERT-QTYDOTIDAKADASTOCK"
																			);
																		var msg =
																			"<span style='font-weight:bold'>DO " +
																			v
																			.delivery_order_draft_kode +
																			"</span> " +
																			alert_tes;
																		var msgtype =
																			'error';
																		//if (!window.__cfRLUnblockHandlers) return false;
																		new PNotify
																			({
																				title: 'Info',
																				text: msg,
																				type: msgtype,
																				styling: 'bootstrap3',
																				delay: 3000,
																				stack: stack_center
																			});
																		$.each(data
																			.data,
																			(index,
																				item
																			) => {
																				let response =
																					item
																					.data;
																				// arrayOfErrorsToDisplay = []
																				// indexError = []
																				$.ajax({
																					async: false,
																					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																					type: "POST",
																					dataType: "JSON",
																					data: {
																						delivery_order_draft_id: v
																							.delivery_order_draft_id,
																						sku_kode: response
																							.sku_kode,
																						msg: "Qty dari SKU <strong>" +
																							response
																							.sku_kode +
																							", " +
																							response
																							.sku +
																							"</strong> tidak ada!!",
																						principle: v
																							.principle_id
																					},
																					success: function(
																						data
																					) {
																						console
																							.log(
																								"insert_delivery_order_draft_detail_msg success"
																							);
																					}
																				});
																			}
																		);
																		// let arrayOfErrorsToDisplayEmptySku = [];
																		// let indexErrorEmptySku = [];
																		// arrayOfErrorsToDisplayEmptySku = []
																		// indexErrorEmptySku = []
																		// $.each(data.data, (index, item) => {
																		//     let response = item.data;
																		//     indexErrorEmptySku.push(index + 1);
																		//     arrayOfErrorsToDisplayEmptySku
																		//         .push({
																		//             title: 'Data Gagal diapprove!',
																		//             html: `Qty dari SKU <strong>${response.sku}</strong> tidak ada di Stock`
																		//         });
																		//     // message_custom("Data gagal disimpan", "error", `<strong>${response.sku_kode} 2</strong>`);
																		// });
																		// Swal.mixin({
																		//         icon: 'error',
																		//         confirmButtonText: 'Next &rarr;',
																		//         showCancelButton: true,
																		//         progressSteps: indexErrorEmptySku
																		//     })
																		//     .queue(arrayOfErrorsToDisplayEmptySku)
																	}
																	if (data
																		.type ===
																		206
																	) {
																		let alert_tes =
																			GetLanguageByKode(
																				"CAPTION-ALERT-QTYDOTIDAKADASTOCK"
																			);
																		var msg =
																			"<span style='font-weight:bold'>DO " +
																			v
																			.delivery_order_draft_kode +
																			"</span> " +
																			`Ada Sku Perlu Dibongkar`;
																		var msgtype =
																			'error';
																		//if (!window.__cfRLUnblockHandlers) return false;
																		new PNotify
																			({
																				title: 'Info',
																				text: msg,
																				type: msgtype,
																				styling: 'bootstrap3',
																				delay: 3000,
																				stack: stack_center
																			});
																		$.each(data
																			.data,
																			(index,
																				item
																			) => {
																				let response =
																					item
																					.data;
																				// arrayOfErrorsToDisplay = []
																				// indexError = []
																				$.ajax({
																					async: false,
																					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/insert_delivery_order_draft_detail_msg'); ?>",
																					type: "POST",
																					dataType: "JSON",
																					data: {
																						delivery_order_draft_id: v
																							.delivery_order_draft_id,
																						sku_kode: response
																							.sku_kode,
																						msg: "SKU <strong>" +
																							response
																							.sku_kode +
																							", " +
																							response
																							.sku +
																							"</strong> Perlu Dibongkar Kartonnya!",
																						principle: v
																							.principle_id
																					},
																					success: function(
																						data
																					) {
																						console
																							.log(
																								"insert_delivery_order_draft_detail_msg success"
																							);
																					}
																				});
																			}
																		);
																	}

																	if (v
																		.tipe_delivery_order_id ==
																		"C5BE83E2-01E8-4E24-B766-26BB4158F2CD"
																	) {
																		if (data
																			.type ===
																			null
																		) {
																			$.each(data,
																				function(
																					i2,
																					v2
																				) {
																					if (v2
																						.type ==
																						210
																					) {
																						let
																							arrayOfErrorsToDisplay = [];
																						let
																							indexError = [];
																						indexError
																							=
																							0;
																						arrayOfErrorsToDisplay
																							.push({
																								title: 'Data Gagal diapprove!',
																								html: `Jumlah Qty dari SKU Stock <strong>${v.sku_nama_produk}</strong> tidak cocok, silahkan dicek kembali!`
																							});
																						Swal.mixin({
																								icon: 'error',
																								confirmButtonText: 'Next &rarr;',
																								showCancelButton: true,
																								progressSteps: indexError
																							})
																							.queue(
																								arrayOfErrorsToDisplay
																							)
																					}
																					if (v
																						.type ==
																						211
																					) {
																						message_topright
																							("success",
																								"SKU Stock Cocok"
																							);
																					}
																				}
																			)
																		}
																	}
																},
																error: function(
																	xhr,
																	status,
																	error
																) {
																	$("#btnapprovdodraft")
																		.prop(
																			"disabled",
																			false
																		);
																	$("#btn-search-data-do-draft")
																		.prop(
																			"disabled",
																			false
																		);
																	$("#loadingviewdodraft")
																		.hide();
																	message_topright
																		("error",
																			"Data gagal Diapprove"
																		);
																}
															});
														} else {
															console.log(
																"DO not Draft"
															);
														}
													} else {
														let alert_tes =
															GetLanguageByKode(
																"CAPTION-ALERT-AREATIDAKDIPILIH"
															);
														var msg =
															"<span style='font-weight:bold'>DO " +
															v
															.delivery_order_draft_kode +
															"</span> " +
															alert_tes;
														var msgtype = 'warning';
														//if (!window.__cfRLUnblockHandlers) return false;
														new PNotify({
															title: 'Info',
															text: msg,
															type: msgtype,
															styling: 'bootstrap3',
															delay: 3000,
															stack: stack_center
														});
													}
												} else {
													console.log(
														"DO Detail Not Found"
													);
												}
											} else {
												let alert_tes =
													GetLanguageByKode(
														"CAPTION-ALERT-ADADOYGTIDAKMEMILIKIPERUSAHAAN"
													);
												var msg =
													"<span style='font-weight:bold'>DO " +
													v
													.delivery_order_draft_kode +
													"</span> " + alert_tes;
												var msgtype = 'error';
												//if (!window.__cfRLUnblockHandlers) return false;
												new PNotify({
													title: 'Info',
													text: msg,
													type: msgtype,
													styling: 'bootstrap3',
													delay: 3000,
													stack: stack_center
												});
											}
										});

										if (cek_success > 0) {
											$("#btnapprovdodraft").prop("disabled",
												false);
											$("#btn-search-data-do-draft").prop(
												"disabled", false);
											$("#loadingviewdodraft").hide();

											GetDODraftByFilter();
											GetKonversiFromDO();

											setTimeout(() => {
												message_topright("success",
													"Data berhasil diapprove"
												);
											}, 500);

										} else {
											$("#btnapprovdodraft").prop("disabled",
												false);
											$("#btn-search-data-do-draft").prop(
												"disabled", false);
											$("#loadingviewdodraft").hide();

											GetKonversiFromDO();
											setTimeout(() => {
												message_topright("error",
													"Data gagal Diapprove");
											}, 500);
										}

									}
								}
							});

							CekErrorMsgDOMasal();
						}, 500);
					}
				});
			} else {
				let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
				message("Error", alert_tes, "error");
			}
		} else {
			let alert_tes = GetLanguageByKode("CAPTION-ALERT-CHECKBOXDOHRSDIPILIH");
			message("Error", alert_tes, "error");
		}
	});

	function DeleteSKU(row, index) {
		var row = row.parentNode.parentNode;
		row.parentNode.removeChild(row);

		// arr_sku[index] = "";
	}

	function reset_table_sku() {
		$("#table-sku-delivery-only > tbody").empty();
		initDataSKU();
	}

	function reqFilter(val, i) {
		if (val == 1) {
			// $("#item-" + i + "-DeliveryOrderDetailDraft-sku_filter_expdate").prop('disabled', false);
			$("#item-" + i + "-DeliveryOrderDetailDraft-sku_filter_expdatebulan").prop('disabled', false);

			$("#item-" + i + "-DeliveryOrderDetailDraft-sku_filter_expdate").append(
				"<option value='>=' selected>=></option>").change();
		} else {
			$("#item-" + i + "-DeliveryOrderDetailDraft-sku_filter_expdate").val(0).change();
			$("#item-" + i + "-DeliveryOrderDetailDraft-sku_filter_expdatebulan").val(0).change();

			// $("#item-" + i + "-DeliveryOrderDetailDraft-sku_filter_expdate").prop('disabled', true);
			$("#item-" + i + "-DeliveryOrderDetailDraft-sku_filter_expdatebulan").prop('disabled', true);
		}

	}

	const handlerPembatalanDO = () => {
		$("#areaPembatalan").val("")
		$("#areaPembatalan").trigger("change")
		$("#filter-status-pembatalan").val("")
		$("#filter-status-pembatalan").trigger("change")

		if ($.fn.DataTable.isDataTable('#tableDataDOPembatalan')) {
			$('#tableDataDOPembatalan').DataTable().destroy();
		}

		$("#tableDataDOPembatalan > tbody").empty();

		$("#modalPembatalanDO").modal('show');
	}

	const handlerUbahKeDraft = () => {
		$("#areaUbahKeDraft").val("")
		$("#areaUbahKeDraft").trigger("change")

		var dateRangePicker = $('#filter-tgl-ubah-ke-draft').data('daterangepicker');
		dateRangePicker.setStartDate('<?= date("01-m-Y") ?>');
		dateRangePicker.setEndDate('<?= date("t-m-Y") ?>');

		if ($.fn.DataTable.isDataTable('#tableDataUbahKeDraft')) {
			$('#tableDataUbahKeDraft').DataTable().destroy();
		}

		$("#tableDataUbahKeDraft > tbody").empty();

		$("#modalUbahKeDraft").modal('show');
	}

	const handlerDataSearchUbahKeDraft = () => {
		const dateRange = $("#filter-tgl-ubah-ke-draft").val();
		const area = $("#areaUbahKeDraft").val();

		$.ajax({
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/getDataSearchUbahKeDraft'); ?>",
			type: "POST",
			data: {
				dateRange,
				area
			},
			dataType: "JSON",
			success: function(response) {
				if ($.fn.DataTable.isDataTable('#tableDataUbahKeDraft')) {
					$('#tableDataUbahKeDraft').DataTable().destroy();
				}

				$("#tableDataUbahKeDraft > tbody").empty();

				if (response.length > 0) {

					response.map((item, index) => {
						$("#tableDataUbahKeDraft > tbody").append(`
								<tr>
										<td>
											<input type="checkbox" class="form-control check-item" style="transform: scale(1.5)" name="chk-data-ubah-draft[]" id="chk-data-ubah-draft[]" value="${item.delivery_order_draft_id}"/>
										</td>
										<td>${item.delivery_order_draft_kode}</td>
										<td>${item.sales_order_kode}</td>
										<td>${item.sales_order_no_po}</td>
										<td>${item.delivery_order_draft_kirim_nama}</td>
										<td>${item.delivery_order_draft_kirim_alamat}</td>
										<td>${item.delivery_order_draft_kirim_area}</td>
										<td>${item.delivery_order_draft_status}</td>
										<td>${item.tipe_delivery_order_nama}</td>
								</tr>
						`);
					})

					$('#tableDataUbahKeDraft').DataTable({
						lengthMenu: [
							[-1],
							['All']
						],
						ordering: false,
					});
				} else {
					$("#tableDataUbahKeDraft > tbody").append(`
						<tr>
							<td class="text-center" colspan="8"><strong class="text-danger">Data Kosong</strong></td>
						</tr>
					`);
				}
			}
		});

	}

	function checkAllUbahDraft(e) {
		var checkboxes = $("input[name='chk-data-ubah-draft[]']");
		if (e.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
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

	function handlerSaveUbahKeDraft() {
		const checkboxes = $("input[name='chk-data-ubah-draft[]']");
		var arrUbahDraft = [];

		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true) {
				const valueId = checkboxes[i].value

				arrUbahDraft.push(valueId);
			}
		}

		if (arrUbahDraft.length > 0) {
			Swal.fire({
				title: "Apakah anda yakin?",
				text: "Ingin Mengubah data ini!",
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
						url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/updateDraft'); ?>",
						type: "POST",
						data: {
							arrUbahDraft
						},
						dataType: "JSON",
						success: function(response) {
							if (response == true) {
								message_topright("success", "Data berhasil Diubah");
								setTimeout(() => {
									location.reload();
								}, 1000);
							} else {
								message_topright("error", "Data gagal diubah");
							}
						}
					});
				}
			});
		} else {
			message("Error!", "Tidak Ada Data Yang Dipilih!", 'error')
			return false;
		}


	}

	const handlerEditMultiDateDO = () => {
		$("#modalEditDataDO").modal('show')
	}

	const handlerDataSearchEditDO = () => {
		const dateRange = $("#filter-do-date2").val();
		const area = $("#areaEdit").val();
		const status_do = $("#filter-status-edit").val();

		$.ajax({
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/getDataSearchEditDO'); ?>",
			type: "POST",
			data: {
				dateRange,
				area,
				status_do
			},
			dataType: "JSON",
			success: function(response) {
				if (response.length > 0) {
					$("#fieldChangeAllDate").show();
					if ($.fn.DataTable.isDataTable('#tableDataDOEdit')) {
						$('#tableDataDOEdit').DataTable().destroy();
					}
					$("#tableDataDOEdit > tbody").empty();

					response.map((item, index) => {
						$("#tableDataDOEdit > tbody").append(`
								<tr>
										<td>
											<input type="checkbox" class="form-control check-item" style="transform: scale(1.5)" name="chk-data[]" id="chk-data[]" value="${item.delivery_order_draft_id}" onchange="handlerRemoveAktualWhenUnchecked(event)"/>
										</td>
										<td>${item.delivery_order_draft_kode}</td>
										<td>${item.sales_order_kode}</td>
										<td>${item.sales_order_no_po}</td>
										<td>${item.delivery_order_draft_kirim_nama}</td>
										<td>${item.delivery_order_draft_kirim_alamat}</td>
										<td>${item.delivery_order_draft_kirim_area}</td>
										<td>${item.delivery_order_draft_status}</td>
										<td>
											<input type="date" value="${item.delivery_order_draft_tgl_rencana_kirim}" class="form-control" disabled/>
										</td>
										<td>
											<input type="date" class="form-control aktualRencanaKirim" disabled id="aktualRencanaKirim${item.delivery_order_draft_id}"/>
										</td>
								</tr>
						
						`);


					})

					$('#tableDataDOEdit').DataTable({
						lengthMenu: [
							[-1],
							['All']
						],
						columnDefs: [{
							sortable: false,
							targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
						}],
					});
				}
			}
		});

	}

	const handlerDataSearchPembatalanDO = () => {
		const dateRange = $("#filter-do-pembatalan-date2").val();
		const area = $("#areaPembatalan").val();
		const principle = $("#principlePembatalan").val();
		const status_do = $("#filter-status-pembatalan").val();

		$.ajax({
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/getDataSearchEditDO'); ?>",
			type: "POST",
			data: {
				dateRange,
				area,
				principle,
				status_do
			},
			dataType: "JSON",
			success: function(response) {
				if ($.fn.DataTable.isDataTable('#tableDataDOPembatalan')) {
					$('#tableDataDOPembatalan').DataTable().destroy();
				}

				$("#tableDataDOPembatalan > tbody").empty();

				if (response.length > 0) {

					response.map((item, index) => {
						$("#tableDataDOPembatalan > tbody").append(`
								<tr>
										<td>
											<input type="checkbox" class="form-control check-item" style="transform: scale(1.5)" name="chk-data[]" id="chk-data[]" data-tipe="${item.tipe_delivery_order_nama}" data-status="${item.delivery_order_draft_status}" value="${item.delivery_order_draft_id}"/>
										</td>
										<td>${item.principle_kode}</td>
										<td>${item.delivery_order_draft_tgl_rencana_kirim}</td>
										<td>${item.delivery_order_draft_kode}</td>
										<td>${item.sales_order_kode}</td>
										<td>${item.sales_order_no_po}</td>
										<td>${item.delivery_order_draft_kirim_nama}</td>
										<td>${item.delivery_order_draft_kirim_alamat}</td>
										<td>${item.delivery_order_draft_kirim_area}</td>
										<td>${item.delivery_order_draft_status}</td>
										<td>${item.tipe_delivery_order_nama}</td>
								</tr>
						`);
					})

					$('#tableDataDOPembatalan').DataTable({
						lengthMenu: [
							[-1],
							['All']
						],
						// ordering: false,
					});
				} else {
					$("#tableDataDOPembatalan > tbody").append(`
						<tr>
							<td class="text-center" colspan="11"><strong class="text-danger">Data Kosong</strong></td>
						</tr>
					`);
				}
			}
		});

	}

	function checkAllSJ(e) {
		var checkboxes = $("input[name='chk-data[]']");
		if (e.checked) {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = true;
					$(".aktualRencanaKirim").val($("#allChangeRencanaKirim").val() == '' ? '' : $("#allChangeRencanaKirim")
						.val())
					$(".aktualRencanaKirim").prop('disabled', false)
				}
			}
		} else {
			for (var i = 0; i < checkboxes.length; i++) {
				if (checkboxes[i].type == 'checkbox') {
					checkboxes[i].checked = false;
					$(".aktualRencanaKirim").val('')
					$(".aktualRencanaKirim").prop('disabled', true)
				}
			}
		}
	}

	const handlerRemoveAktualWhenUnchecked = (event) => {
		if (event.currentTarget.checked == false) {
			$(`#aktualRencanaKirim${event.currentTarget.value}`).val('');
			$(`#aktualRencanaKirim${event.currentTarget.value}`).prop('disabled', true);
		} else {
			$(`#aktualRencanaKirim${event.currentTarget.value}`).val($("#allChangeRencanaKirim").val() == '' ? '' : $(
				"#allChangeRencanaKirim").val())
			$(`#aktualRencanaKirim${event.currentTarget.value}`).prop('disabled', false);
		}
	}

	const handlerAllChangeRencanaKirim = (value) => {
		if (value == "") {
			message("Error!", "field tidak boleh kosong", 'error')
			return false;
		}

		const checkboxes = $("input[name='chk-data[]']");

		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true) {
				const valueId = checkboxes[i].value
				$(`#aktualRencanaKirim${valueId}`).val(value);
			}
		}
	}

	const handlerSaveEditDO = () => {
		const checkboxes = $("input[name='chk-data[]']");

		let arrData = [];

		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true) {
				const valueId = checkboxes[i].value
				const aktualRencanaKirim = $(`#aktualRencanaKirim${valueId}`).val();
				if (aktualRencanaKirim == "") {
					message("Error!", "Aktual Rencana Kirim tidak boleh kosong yang sudah dichecked", 'error')
					return false;
				} else {
					arrData.push({
						valueId,
						aktualRencanaKirim
					})
				}
			}
		}

		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Ingin Simpan data ini!",
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
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/updateDateRencanaKirim'); ?>",
					type: "POST",
					data: {
						arrData
					},
					dataType: "JSON",
					success: function(response) {
						if (response == true) {
							message_topright("success", "Data berhasil Disimpan");
							setTimeout(() => {
								location.reload();
							}, 1000);
						} else {
							message_topright("error", "Data gagal disimpan");
						}
					}
				});
			}
		});
	}

	const handlerSavePembatalanDO = () => {
		const checkboxes = $("input[name='chk-data[]']");

		let arrData = [];

		for (var i = 0; i < checkboxes.length; i++) {
			if (checkboxes[i].checked == true) {
				const valueId = checkboxes[i].value
				const status = checkboxes[i].getAttribute('data-status');
				const tipe = checkboxes[i].getAttribute('data-tipe');

				arrData.push({
					valueId,
					status,
					tipe
				})
			}
		}

		Swal.fire({
			title: "Apakah anda yakin?",
			text: "Ingin Membatalkan data ini!",
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
					url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/updateStatusPembatalan'); ?>",
					type: "POST",
					data: {
						arrData
					},
					dataType: "JSON",
					success: function(response) {
						if (response == true) {
							message_topright("success", "Data berhasil Dibatalkan");
							setTimeout(() => {
								location.reload();
							}, 1000);
						} else {
							message_topright("error", "Data gagal dibatalkan");
						}
					}
				});
			}
		});
	}

	const handlerCloseEditDO = () => {
		$("#modalEditDataDO").modal('hide');
		$("#tableDataDOPembatalan > tbody").empty();
		$("#allChangeRencanaKirim").val('');
		$("#fieldChangeAllDate").hide();
	}

	const handlerClosePembatalanDO = () => {
		$("#modalPembatalanDO").modal('hide');
		$("#tableDataDOEdit > tbody").empty();
	}

	function PembongkaranBarang() {

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/KonversiSKUFromDO') ?>",
			dataType: "JSON",
			beforeSend: function() {

				Swal.fire({
					title: 'Loading ...',
					html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
					timerProgressBar: false,
					showConfirmButton: false
				});

				$("#btnapprovdodraft").prop("disabled", true);
				$("#btn-search-data-do-draft").prop("disabled", true);
				$("#loadingviewdodraft").show();
			},
			success: function(response) {

				if (response.data != 0) {

					Swal.close();

					$("#modal-pembongkaran-sku").modal('show');

					$('#table-pembongkaran-sku').fadeOut("slow", function() {
						$(this).hide();

						$("#table-pembongkaran-sku > tbody").empty();

						if ($.fn.DataTable.isDataTable('#table-pembongkaran-sku')) {
							$('#table-pembongkaran-sku').DataTable().clear();
							$('#table-pembongkaran-sku').DataTable().destroy();
						}

					}).fadeIn("slow", function() {
						$(this).show();

						// console.log(response.CanvasInDO);
						$.each(response.data, function(i, v) {
							$("#table-pembongkaran-sku > tbody").append(`
								<tr>
									<td class="text-center">
										${v.sku_kode}
										<input type="hidden" id="item-${i}-persetujuanpembongkarandetail-sku_id" value="${v.sku_id}">
                                        <input type="hidden" id="item-${i}-persetujuanpembongkarandetail-principle_kode" value="${v.principle_kode}">
									</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.sku_nama_produk}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.principle_kode}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.principle_brand_nama}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.sku_kemasan}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;width:100%;">
										<select id="item-${i}-persetujuanpembongkaran-client_wms_id" class="form-control select2" disabled>
											<option value = ""> ** <span name="CAPTION-PILIH"> PILIH </span> **</option>
										</select>
									</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;width:100%;">
										<select id="item-${i}-persetujuanpembongkaran-depo_detail_id" class="form-control select2" onchange="GetSKUStockByGudang('${i}')" disabled>
											<option value="${v.depo_detail_id}">${v.depo_detail_nama}</option>
										</select>
									</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;width:100%;">
										<select id="item-${i}-persetujuanpembongkarandetail-sku_stock_expired_date" class="form-control" disabled>
										    <option value="${v.sku_stock_id}">${v.sku_stock_expired_date} || ${v.sku_stock_batch_no}</option>
										</select>
									</td>
									<td class="text-center" style="text-align: center; vertical-align: middle;width:100%;">${v.sku_qty}</td>
								</tr>
							`).fadeIn("slow");

							// $.each(response.ed, function(x, y) {
							//     if (y.sku_id == v.sku_id && y.client_wms_id == v
							//         .client_wms_id && y.depo_detail_id ==
							//         'E594658C-F814-48EA-A406-0947800446E1' && y
							//         .depo_id ==
							//         '<?= $this->session->userdata('depo_id') ?>') {
							//         $("#item-" + i + "-persetujuanpembongkarandetail-sku_stock_expired_date").append(`<option value="${y.sku_stock_id}">${y.sku_stock_expired_date} || ${y.sku_stock_batch_no}</option>`);
							//     }
							// });
						});

						$.each(response.data, function(i, v) {
							$("#item-" + i + "-persetujuanpembongkaran-client_wms_id").html('');
							<?php if (isset($Perusahaan) && $Perusahaan != "0") { ?>
								$("#item-" + i + "-persetujuanpembongkaran-client_wms_id").append(
									`<option value="">** <span name="CAPTION-PILIH">PILIH</span> **</option>`
								);
								<?php foreach ($Perusahaan as $row) : ?>
									$("#item-" + i + "-persetujuanpembongkaran-client_wms_id").append(
										`<option value="<?= $row['client_wms_id'] ?>" ${v.client_wms_id == '<?= $row['client_wms_id'] ?>' ? 'selected' : ''}><?= $row['client_wms_nama'] ?></option>`
									);
								<?php endforeach; ?>
							<?php } else { ?>
								$("#item-" + i + "-persetujuanpembongkaran-client_wms_id").append(
									`<option value="">** <span name="CAPTION-PILIH">PILIH</span> **</option>`
								);
							<?php } ?>
						});

						// $.each(response.data, function(i, v) {
						//     $("#item-" + i + "-persetujuanpembongkaran-depo_detail_id").html(
						//         '');
						//     <?php if (isset($Gudang) && $Gudang != "0") { ?>
						//         $("#item-" + i + "-persetujuanpembongkaran-depo_detail_id").append(
						//             `<option value="">** <span name="CAPTION-PILIH">PILIH</span> **</option>`
						//         );
						//         <?php foreach ($Gudang as $row) : ?>
						//             $("#item-" + i + "-persetujuanpembongkaran-depo_detail_id").append(
						//                 `<option value="<?= $row['depo_detail_id'] ?>" ${'<?= $row['depo_detail_id'] ?>' == 'E594658C-F814-48EA-A406-0947800446E1' ? 'selected' : ''} ><?= $row['depo_detail_nama'] ?></option>`
						//             );
						//         <?php endforeach; ?>
						//     <?php } else { ?>
						//         $("#item-" + i + "-persetujuanpembongkaran-depo_detail_id").append(
						//             `<option value="">** <span name="CAPTION-PILIH">PILIH</span> **</option>`
						//         );
						//     <?php } ?>
						// });


						// $.each(response, function(i, v) {
						// 	$.ajax({
						// 		type: 'GET',
						// 		url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/get_sku_stock_by_sku') ?>",
						// 		data: {
						// 			sku_id: v.sku_id
						// 		},
						// 		dataType: "JSON",
						// 		success: function(response) {
						// 			if (response != 0) {
						// 				$.each(response, function(i2, v2) {
						// 					$("#item-" + i + "-persetujuanpembongkarandetail-sku_stock_expired_date").append(`<option value="${v2.sku_stock_id}">${v2.sku_stock_expired_date}</option>`);
						// 				});
						// 			} else {
						// 				$("#item-" + i + "-persetujuanpembongkarandetail-sku_stock_expired_date").append(`<option value="">** <span name="CAPTION-PILIH">PILIH</span> **</option>`);
						// 			}
						// 		}
						// 	});
						// });

						$('#table-pembongkaran-sku').DataTable({
							lengthMenu: [
								[50, 100, 200, -1],
								[50, 100, 200, 'All']
							],
							ordering: false,
							searching: false,
							"scrollX": true
						});

						$(".select2").select2();
					});

				} else {
					$("#modal-pembongkaran-sku").modal('hide');

					let alert_tes = GetLanguageByKode('CAPTION-ALERT-PERSETUJUANPEMBONGKARANTIDAKADA');
					let alert_text = GetLanguageByKode('CAPTION-ALERT-LAKUKANAPPROVEDODAHULU');
					message_custom(alert_tes, "error", alert_text);

				}

				$("#btnapprovdodraft").prop("disabled", false);
				$("#btn-search-data-do-draft").prop("disabled", false);
				$("#loadingviewdodraft").hide();

			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure", "error");

				$("#btnapprovdodraft").prop("disabled", false);
				$("#btn-search-data-do-draft").prop("disabled", false);
				$("#loadingviewdodraft").hide();
			},
			complete: function() {

				$("#btnapprovdodraft").prop("disabled", false);
				$("#btn-search-data-do-draft").prop("disabled", false);
				$("#loadingviewdodraft").hide();
			}
		});
	}

	function GetSKUStockByGudang(index) {
		var client_wms_id = $("#item-" + index + "-persetujuanpembongkaran-client_wms_id").val();
		var depo_detail_id = $("#item-" + index + "-persetujuanpembongkaran-depo_detail_id").val();
		var sku_id = $("#item-" + index + "-persetujuanpembongkarandetail-sku_id").val();

		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/get_sku_stock_by_gudang') ?>",
			data: {
				client_wms_id: client_wms_id,
				depo_detail_id: depo_detail_id,
				sku_id: sku_id
			},
			dataType: "JSON",
			success: function(response) {
				$("#item-" + index + "-persetujuanpembongkarandetail-sku_stock_expired_date").html('');

				if (response != 0) {
					$.each(response, function(i, v) {
						$("#item-" + index + "-persetujuanpembongkarandetail-sku_stock_expired_date")
							.append(
								`<option value="${v.sku_stock_id}">${v.sku_stock_expired_date} || ${v.sku_stock_batch_no}</option>`
							);
					});
				} else {
					$("#item-" + index + "-persetujuanpembongkarandetail-sku_stock_expired_date").append(
						`<option value="">** <span name="CAPTION-PILIH">PILIH</span> **</option>`);
				}
			}
		});
	}

	$("#persetujuanpembongkaran-konversi_is_need_approval").on('click', function() {
		if ($(this).is(':checked')) {
			$("#persetujuanpembongkaran-tr_konversi_sku_status").val('In Progress Approval')
		} else {
			$("#persetujuanpembongkaran-tr_konversi_sku_status").val('Draft')
		}
	})

	$("#btnsavekonversi").on("click", function() {
		var tipe_konversi = $("#persetujuanpembongkaran-tipe_konversi_id").val();
		var cek_completed = 0;
		var cek_exp_date = 0;
		var cek_pembongkaran_completed = 0;

		var gudang_penerima = "";

		var arr_konversi_detail = [];
		var arr_client_wms_konversi = [];
		var arr_client_wms_konversi_detail = [];

		$.ajax({
			type: 'GET',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/get_client_wms_konversi_sku') ?>",
			dataType: "JSON",
			async: false,
			success: function(response) {

				if (response != "0") {
					$.each(response, function(i, v) {
						arr_client_wms_konversi.push({
							'tr_konversi_sku_id': v.tr_konversi_sku_id,
							'client_wms_id': v.client_wms_id
						})
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				message("Error", "Error 500 Internal Server Connection Failure, Get Client WMS Failed",
					"error");
			}
		});

		// alert('tes')

		$("#table-pembongkaran-sku > tbody tr").each(function(idx) {

			var sku_stock_expired_date = $("#item-" + idx +
				"-persetujuanpembongkarandetail-sku_stock_expired_date option:selected").text();
			var arr_sku_stock_expired_date = sku_stock_expired_date.split(" || ");

			// console.log($("#item-" + idx + "-persetujuanpembongkarandetail-sku_stock_expired_date").val());
			if ($("#item-" + idx + "-persetujuanpembongkarandetail-sku_stock_expired_date").val() != "") {
				arr_konversi_detail.push({
					'client_wms_id': $("#item-" + idx + "-persetujuanpembongkaran-client_wms_id")
						.val(),
					'depo_detail_id': $("#item-" + idx + "-persetujuanpembongkaran-depo_detail_id")
						.val(),
					'sku_id': $("#item-" + idx + "-persetujuanpembongkarandetail-sku_id").val(),
					'sku_stock_id': $("#item-" + idx +
						"-persetujuanpembongkarandetail-sku_stock_expired_date").val(),
					'sku_stock_expired_date': arr_sku_stock_expired_date[0],
					'tr_konversi_sku_detail_qty_plan': 0,
					'principle_kode': $("#item-" + idx +
						"-persetujuanpembongkarandetail-principle_kode").val()
				});

			} else {
				cek_exp_date++;

			}
		});

		Swal.fire({
			title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
			cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
		}).then((result) => {
			if (result.value == true) {
				//ajax save data

				if (cek_exp_date > 0) {
					let alert_tes = GetLanguageByKode('CAPTION-ALERT-PILIHTANGGALKADALUWARSA');
					message_custom("Error", "error", alert_tes);
				} else {

					if (arr_konversi_detail.length > 0) {

						if (arr_client_wms_konversi.length > 0) {

							$.each(arr_client_wms_konversi, function(i, v) {

								// GetKonversiFromDO();
								arr_client_wms_konversi_detail = [];
								cek_completed = 0;
								gudang_penerima = "";

								$.each(arr_konversi_detail, function(idx, val) {

									if (v.client_wms_id == $("#item-" + idx +
											"-persetujuanpembongkaran-client_wms_id")
										.val()) {

										gudang_penerima = val.depo_detail_id;

										arr_client_wms_konversi_detail.push({
											'client_wms_id': val.client_wms_id,
											'depo_detail_id': val.depo_detail_id,
											'sku_id': val.sku_id,
											'sku_stock_id': val.sku_stock_id,
											'sku_stock_expired_date': val
												.sku_stock_expired_date,
											'tr_konversi_sku_detail_qty_plan': val
												.tr_konversi_sku_detail_qty_plan,
											'principle_kode': val.principle_kode
										})
									}

								});

								if (tipe_konversi == "") {
									let alert_tes = GetLanguageByKode(
										'CAPTION-ALERT-PILIHTIPEKONVERSI');
									message_custom("Error", "error", alert_tes);
								} else {
									cek_completed++;
								}

								if (arr_client_wms_konversi_detail.length == 0) {
									let alert_tes = GetLanguageByKode(
										'CAPTION-ALERT-PILIHSKUKONVERSI');
									message_custom("Error", "error", alert_tes);
								} else {
									cek_completed++;
								}

								if (v.client_wms_id == "") {
									let alert_tes = GetLanguageByKode('CAPTION-MSG03');
									message_custom("Error", "error", alert_tes);
								} else {
									cek_completed++;
								}

								if (v.depo_detail_id == "") {
									let alert_tes = GetLanguageByKode('CAPTION-PILIHGUDANG');
									message_custom("Error", "error", alert_tes);
								} else {
									cek_completed++;
								}

								if (cek_completed == 4) {

									$.ajax({
										async: false,
										url: "<?= base_url('WMS/PersetujuanPembongkaranBarang/insert_tr_konversi_sku_from_another_menu'); ?>",
										type: "POST",
										data: {
											// tr_konversi_sku_id: $("#persetujuanpembongkaran-tr_konversi_sku_id").val(),
											// client_wms_id: $("#persetujuanpembongkaran-client_wms_id").val(),
											// depo_detail_id: $("#persetujuanpembongkaran-depo_detail_id").val(),
											tr_konversi_sku_id: v.tr_konversi_sku_id,
											client_wms_id: v.client_wms_id,
											depo_detail_id: gudang_penerima,
											tipe_konversi_id: $(
												"#persetujuanpembongkaran-tipe_konversi_id"
											).val(),
											tr_konversi_sku_kode: "",
											tr_konversi_sku_tanggal: $(
												"#persetujuanpembongkaran-tr_konversi_sku_tanggal"
											).val(),
											tr_konversi_sku_status: $(
												"#persetujuanpembongkaran-tr_konversi_sku_status"
											).val(),
											tr_konversi_sku_keterangan: $(
												"#persetujuanpembongkaran-tr_konversi_sku_keterangan"
											).val(),
											tr_konversi_sku_tgl_create: "",
											tr_konversi_sku_who_create: "<?= $this->session->userdata('pengguna_username') ?>",
											// konversi_is_need_approval: $("#persetujuanpembongkaran-konversi_is_need_approval:checked").val(),
											konversi_is_need_approval: 0,
											is_from_do: 1,
											detail: arr_client_wms_konversi_detail
										},
										dataType: "JSON",
										async: false,
										beforeSend: function() {

											Swal.fire({
												title: 'Loading ...',
												html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
												timerProgressBar: false,
												showConfirmButton: false
											});

											$("#btnapprovdodraft").prop("disabled",
												true);
											$("#btn-search-data-do-draft").prop(
												"disabled", true);
											$("#loadingviewdodraft").show();
										},
										success: function(data) {
											if (data == 1) {
												$.ajax({
													type: 'POST',
													url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/UpdateSKUQtyKonversi') ?>",
													dataType: "JSON",
													data: {
														tr_konversi_sku_id: v
															.tr_konversi_sku_id
													},
													async: false,
													success: function(
														response) {
														console.log(
															"UpdateSKUQtyKonversi success"
														);
													},
													error: function(xhr,
														ajaxOptions,
														thrownError) {
														message("Error",
															"Error 500 Internal Server Connection Failure, Update SKU Qty Konversi Failed",
															"error");

														$("#btnapprovdodraft")
															.prop(
																"disabled",
																false);
														$("#btn-search-data-do-draft")
															.prop(
																"disabled",
																false);
														$("#loadingviewdodraft")
															.hide();
													},
												})
											} else {
												console.log(
													"insert_tr_konversi_sku_from_another_menu gagal"
												);
											}

											$("#btnapprovdodraft").prop("disabled",
												false);
											$("#btn-search-data-do-draft").prop(
												"disabled", false);
											$("#loadingviewdodraft").hide();
										},
										error: function(xhr, ajaxOptions, thrownError) {
											message("Error",
												"Error 500 Internal Server Connection Failure",
												"error");

											$("#btnapprovdodraft").prop("disabled",
												false);
											$("#btn-search-data-do-draft").prop(
												"disabled", false);
											$("#loadingviewdodraft").hide();
										},
										complete: function() {
											cek_pembongkaran_completed++;
										}
									});

								} else {
									cek_completed = 0;
									$("#loadingviewdodraft").hide();
								}
							});

						} else {
							// var alert = GetLanguageByKode('CAPTION-ALERT-PILIHSKUKONVERSI');
							// message("Error", alert, "error");

							alert('client_wms_konversi tidak ada');
						}

					} else {
						// let alert_tes = GetLanguageByKode('CAPTION-ALERT-PILIHSKUKONVERSI');
						// message_custom("Error", "error", alert_tes);

						alert('arr_konversi_detail tidak ada');
					}
				}

				setTimeout(() => {

					if (cek_pembongkaran_completed > 0 && arr_konversi_detail.length > 0) {

						var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
						message("Success", alert, "success");

						$("#modal-pembongkaran-sku").modal('hide');

						$("#btnapprovdodraft").prop("disabled", false);
						$("#btn-search-data-do-draft").prop("disabled", false);
						$("#loadingviewdodraft").hide();

						GetKonversiFromDO();

					} else {

						cek_pembongkaran_completed = 0;

						var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
						message("Error", alert, "error");
					}

				}, 1000);
			}
		});
	});

	function ErrorMsgDOMasal() {
		principle = $("#filter-principle").val();

		table_error.ajax.reload();

		$("#modal-pesan-error").modal('show');

		// $('#btnerrormsg').fadeOut("slow", function() {
		//     $(this).hide();
		//     // $("#CAPTION-JUMLAHERRORMSG").html('');
		// }).fadeIn("slow", function() {
		//     $(this).show();
		//     // $("#CAPTION-JUMLAHERRORMSG").html('');
		//     // $("#CAPTION-JUMLAHERRORMSG").append(response.length);

		//     if (response != "0") {
		//         document.getElementById("btnerrormsg").classList.add('btn-warning');
		//         document.getElementById("btnerrormsg").classList.remove('btn-primary');
		//     } else {
		//         document.getElementById("btnerrormsg").classList.add('btn-primary');
		//         document.getElementById("btnerrormsg").classList.remove('btn-warning');
		//         // $("#CAPTION-JUMLAHERRORMSG").append(`0`);
		//     }
		// });

		// $.ajax({
		//     async: false,
		//     type: 'POST',
		//     url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/get_delivery_order_draft_detail_msg') ?>",
		//     data: {
		//         principle: principle
		//     },
		//     dataType: "JSON",
		//     beforeSend: function() {
		//         Swal.fire({
		//             title: 'Loading ...',
		//             html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
		//             timerProgressBar: false,
		//             showConfirmButton: false
		//         });

		//         $("#btnapprovdodraft").prop("disabled", true);
		//         $("#btn-search-data-do-draft").prop("disabled", true);
		//         $("#loadingviewdodraft").show();
		//     },
		//     success: function(response) {

		//         Swal.close();

		// $('#btnerrormsg').fadeOut("slow", function() {
		//     $(this).hide();
		//     // $("#CAPTION-JUMLAHERRORMSG").html('');
		// }).fadeIn("slow", function() {
		//     $(this).show();
		//     // $("#CAPTION-JUMLAHERRORMSG").html('');
		//     // $("#CAPTION-JUMLAHERRORMSG").append(response.length);

		//     if (response != "") {
		//         document.getElementById("btnerrormsg").classList.add('btn-warning');
		//         document.getElementById("btnerrormsg").classList.remove('btn-primary');
		//     } else {
		//         document.getElementById("btnerrormsg").classList.add('btn-primary');
		//         document.getElementById("btnerrormsg").classList.remove('btn-warning');
		//     }
		// });

		//         $("#modal-pesan-error").modal('show');

		//         $("#table-pesan-error > tbody").empty();

		//         if ($.fn.DataTable.isDataTable('#table-pesan-error')) {
		//             $('#table-pesan-error').DataTable().clear();
		//             $('#table-pesan-error').DataTable().destroy();
		//         }

		//         if (response != 0) {

		//             $('#table-pesan-error').fadeOut("slow", function() {
		//                 $(this).hide();
		//             }).fadeIn("slow", function() {
		//                 $(this).show();
		//                 var deliveryOrderKodeSpan = "";
		//                 $.each(response, function(i, v) {

		//                     $("#table-pesan-error > tbody").append(`
		//                         <tr>
		//                             <td class="text-center" style="text-align: center; vertical-align: middle;">${v.delivery_order_draft_kode}</td>
		//                             <td class="text-center" style="text-align: center; vertical-align: middle;">${v.outlet}</td>
		//                             <td class="text-center" style="text-align: center; vertical-align: middle;">${v.segmen}</td>
		//                             <td class="text-center" style="text-align: center; vertical-align: middle;">${v.principle}</td>
		//                             <td class="text-center" style="text-align: center; vertical-align: middle;">${v.sku_kode}</td>
		//                             <td class="text-center" style="text-align: center; vertical-align: middle;">${v.sku_nama_produk}</td>
		//                             <td class="text-center" style="text-align: center; vertical-align: middle;">${v.flag}</td>
		//                             <td class="text-center text-danger" style="text-align: center; vertical-align: middle;">${v.pesan_error}</td>
		//                         </tr>
		//             	    `).fadeIn("slow");
		//                 });

		//                 $('#table-pesan-error').DataTable({
		//                     lengthChange: false,
		//                     searching: true,
		//                     paging: false,
		//                     order: [],
		//                     "bInfo": false,
		//                     rowsGroup: [
		//                         0,
		//                         1,
		//                         2,
		//                         3,
		//                         4,
		//                         5,
		//                         6,
		//                         7
		//                     ],
		//                 });
		//             });

		//         } else {
		//             $("#table-pesan-error > tbody").append(`
		// 				<tr>
		// 					<td class="text-center" colspan="7"><strong class="text-danger">DATA NOT FOUND</strong></td>
		// 				</tr>
		// 			`);
		//         }

		//         $("#btnapprovdodraft").prop("disabled", false);
		//         $("#btn-search-data-do-draft").prop("disabled", false);
		//         $("#loadingviewdodraft").hide();

		//     },
		//     error: function(xhr, ajaxOptions, thrownError) {
		//         message("Error", "Error 500 Internal Server Connection Failure", "error");

		//         $("#btnapprovdodraft").prop("disabled", false);
		//         $("#btn-search-data-do-draft").prop("disabled", false);
		//         $("#loadingviewdodraft").hide();
		//     },
		//     complete: function() {

		//         $("#btnapprovdodraft").prop("disabled", false);
		//         $("#btn-search-data-do-draft").prop("disabled", false);
		//         $("#loadingviewdodraft").hide();
		//     }
		// });
	}

	function delete_delivery_order_draft_detail_msg(principle) {
		$.ajax({
			async: false,
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/delete_delivery_order_draft_detail_msg'); ?>",
			data: {
				principle: principle
			},
			type: "POST",
			dataType: "JSON",
			success: function(data) {
				console.log("delete_delivery_order_draft_detail_msg success");
			}
		});
	}

	function CekErrorMsgDOMasal() {
		principle = $("#filter-principle").val();

		$.ajax({
			async: false,
			type: 'POST',
			url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/get_delivery_order_draft_detail_msg') ?>",
			data: {
				principle: principle
			},
			dataType: "JSON",
			success: function(response) {

				$('#btnerrormsg').fadeOut("slow", function() {
					$(this).hide();
					// $("#CAPTION-JUMLAHERRORMSG").html('');
				}).fadeIn("slow", function() {
					$(this).show();
					// $("#CAPTION-JUMLAHERRORMSG").html('');
					// $("#CAPTION-JUMLAHERRORMSG").append(response.length);

					if (response.data != "0") {
						document.getElementById("btnerrormsg").classList.add('btn-warning');
						document.getElementById("btnerrormsg").classList.remove('btn-primary');
					} else {
						document.getElementById("btnerrormsg").classList.add('btn-primary');
						document.getElementById("btnerrormsg").classList.remove('btn-warning');
						// $("#CAPTION-JUMLAHERRORMSG").append(`0`);
					}
				});

			}
		});
	}

	$("#btnaddskustock").on("click", function() {

		index_arr_detail2 = arr_detail2.length;

		arr_detail2.push({
			'idx': index_arr_detail2,
			'sku_id': $("#filter-sku_id-detail2").val(),
			'sku_kode': $("#filter-sku_kode-detail2").val(),
			'sku_nama_produk': $("#filter-sku_nama_produk-detail2").val(),
			'sku_stock_id': '',
			'sku_stock_expired_date': '',
			'sku_qty': '0',
			'sku_konversi_faktor': $("#filter-sku_konversi_faktor-detail2").val(),
		});

		GetSKUSTockDODetail2();
	});

	function UpdateDODetail2(id, idx) {
		var sku_id = $("#filter-sku_id-detail2").val();
		var sku_kode = $("#filter-sku_kode-detail2").val();
		var sku_nama_produk = $("#filter-sku_nama_produk-detail2").val();
		var sku_konversi_faktor = $("#filter-sku_konversi_faktor-detail2").val();
		var sku_stock_id = $("#item-" + idx + "-sku_stock_expired_date-do_detail2").val();
		var sku_qty = $("#item-" + idx + "-sku_qty-do_detail2").val();

		sku_qty = sku_qty == '' ? 0 : sku_qty;
		sku_konversi_faktor = sku_konversi_faktor == '' ? 0 : sku_konversi_faktor;

		let slc_sku_stock = $("#item-" + idx + "-sku_stock_expired_date-do_detail2 option:selected").text();
		const sku_stock = slc_sku_stock.split(" | ");
		let sku_stock_expired_date = sku_stock[0];
		let sku_stock_batch_no = sku_stock[1];

		var index = -1;

		$.each(arr_detail2, function(i, obj) {
			if (obj.idx == id) {
				index = i;
				return false; // Exit the loop once the desired object is found
			}
		});

		arr_detail2[index] = {
			'idx': id,
			'sku_id': sku_id,
			'sku_kode': sku_kode,
			'sku_nama_produk': sku_nama_produk,
			'sku_stock_id': $("#item-" + idx + "-sku_stock_expired_date-do_detail2").val(),
			'sku_stock_expired_date': sku_stock_expired_date,
			'sku_qty': sku_qty,
			'sku_konversi_faktor': sku_qty * sku_konversi_faktor
		}

		GetSKUSTockDODetail2();
	}

	function DeleteDODetail2(idx) {
		var sku_stock_id = $("#item-" + idx + "-sku_stock_expired_date-do_detail2").val();
		sku_stock_id = sku_stock_id === null ? "" : sku_stock_id;

		// const findIndexData = arr_detail2.findIndex((value) => value.sku_stock_id == sku_stock_id);

		// if (findIndexData > -1) { // only splice array when item is found
		// 	arr_detail2.splice(findIndexData, 1); // 2nd parameter means remove one item only
		// }

		arr_detail2 = jQuery.grep(arr_detail2, function(value) {
			return value.sku_stock_id != sku_stock_id;
		});

		GetSKUSTockDODetail2();
	}

	function ViewSKUStock(sku_id, sku_kode, sku_nama_produk, sku_qty, sku_konversi_faktor) {
		$("#modal-do-detail2").modal('show');

		$("#filter-sku_id-detail2").val(sku_id);
		$("#filter-sku_kode-detail2").val(sku_kode);
		$("#filter-sku_nama_produk-detail2").val(sku_nama_produk);
		$("#filter-sku_qty-detail2").val(sku_qty);
		$("#filter-sku_konversi_faktor-detail2").val(sku_konversi_faktor);

		GetSKUSTockDODetail2();
	}

	function GetSKUSTockDODetail2() {

		if (arr_detail2.length > 0) {
			$.ajax({
				async: false,
				type: 'POST',
				url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/get_do_detail2_sementara') ?>",
				dataType: "JSON",
				data: {
					sku_id: $("#filter-sku_id-detail2").val(),
					arr_do_detail2: arr_detail2
				},
				beforeSend: function() {
					Swal.fire({
						title: 'Loading ...',
						html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
						timerProgressBar: false,
						showConfirmButton: false
					});
				},
				success: function(response) {

					$('#table-sku-do-detail2').fadeOut("fast", function() {
						$(this).hide();

						$("#table-sku-do-detail2 > tbody").html('');
						$("#table-sku-do-detail2 > tbody").empty();

					}).fadeIn("fast", function() {
						$(this).show();
						$.each(response, function(i, v) {
							$("#table-sku-do-detail2 > tbody").append(`
								<tr>
									<td class="text-center">${v.sku_kode}</td>
									<td class="text-center">${v.sku_nama_produk}</td>
									<td class="text-center">
										<select class="form-control slc_sku_exp_date_detail2 select_detail2" id="item-${i}-sku_stock_expired_date-do_detail2"  onchange="UpdateDODetail2('${v.idx}','${i}')"></select>
									</td>
									<td class="text-center">
										<input type="number" class="form-control" id="item-${i}-sku_qty-do_detail2" value="${v.sku_qty}" onchange="UpdateDODetail2('${v.idx}','${i}')">
									</td>
									<td class="text-center">
										<button class="btn btn-danger btn-small btn-delete-sku" onclick="DeleteDODetail2('${v.idx}','${i}')"><i class="fa fa-trash"></i></button>
									</td>
								</tr>
							`);
						});

						setTimeout(() => {
							$.each(response, function(i, v) {
								$.ajax({
									async: false,
									type: 'GET',
									url: "<?= base_url('WMS/Distribusi/DeliveryOrderDraft/get_sku_stock_by_sku_id') ?>",
									dataType: "JSON",
									data: {
										sku_id: $("#filter-sku_id-detail2")
											.val()
									},
									success: function(response2) {

										$("#item-" + i +
											"-sku_stock_expired_date-do_detail2"
										).html('');
										$("#item-" + i +
											"-sku_stock_expired_date-do_detail2"
										).append(
											`<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>`
										);

										if (response2.length > 0) {
											$.each(response2, function(i2,
												v2) {
												$("#item-" + i +
													"-sku_stock_expired_date-do_detail2"
												).append(
													`<option value="${v2.sku_stock_id}" ${v2.sku_stock_id == v.sku_stock_id ? 'selected' : ''}>${v2.sku_stock_expired_date + " | " + v2.depo_detail_nama + " | " + v2.sku_stock_batch_no}</option>`
												);
											});
										}

									}
								});
							});

						}, 100);

						$(".select_detail2").select2();
					});
				},
				error: function(xhr, ajaxOptions, thrownError) {
					message("Error", "Error 500 Internal Server Connection Failure", "error");
				},
				complete: function() {
					Swal.close();
				}
			});
		}

	}

	function UpdateTotalHarga() {
		var total_harga = 0;
		var jml = $("#table-sku-delivery-only > tbody tr").length;

		// $.each(arr_sku, function(i, v) {
		// 	var qty = isNaN(parseInt($("#item-" + i + "-DeliveryOrderDetailDraft-sku_qty").val())) ? 0 : parseInt($("#item-" + i + "-DeliveryOrderDetailDraft-sku_qty").val());
		// 	var harga = isNaN(parseInt($("#item-" + i + "-DeliveryOrderDetailDraft-sku_harga_satuan").val())) ? 0 : parseInt($("#item-" + i + "-DeliveryOrderDetailDraft-sku_harga_satuan").val());

		// 	total_harga += qty * harga;

		// });

		for (var i = 0; i < jml; i++) {
			var qty = isNaN(parseInt($("#item-" + i + "-DeliveryOrderDetailDraft-sku_qty").val())) ? 0 : parseInt($("#item-" + i + "-DeliveryOrderDetailDraft-sku_qty").val());
			var harga = isNaN(parseInt($("#item-" + i + "-DeliveryOrderDetailDraft-sku_harga_satuan").val())) ? 0 : parseInt($("#item-" + i + "-DeliveryOrderDetailDraft-sku_harga_satuan").val());

			total_harga += qty * harga;

		};


		$("#input_tunai").val(total_harga);
	}
</script>