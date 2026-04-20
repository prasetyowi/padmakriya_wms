<script>
    let global_sku_id = [];
    let global_data = [];
    const html5QrCode = new Html5Qrcode("preview");
    loadingBeforeReadyPage()
    $(document).ready(function() {
        select2();

        $(document).on("input", ".numeric", function(event) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    function select2() {
        $(".select2").select2({
            width: "100%"
        });
    }

    $("#noMutasiAntarUnit").on("change", function() {
        let id = $(this).val();
        requestAjax(
            "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/getDataKoreksiDraftById'); ?>", {
                id: id
            }, "POST", "JSON",
            function(response) {
                const header = response.header;
                const detail = response.detail;

                $("#koreksi_draft_id").val(header.tr_mutasi_depo_id);
                $("#gudang_id").val(header.depo_detail_id);

                $("#gudang_nama").val(header.gudangAsal);
                $("#tgl").val(header.tgl);

                $("#depo_asal").val(header.depoAsal);
                $("#gudang_asal").val(header.gudangAsal);
                $("#depo_tujuan").val(header.depoTujuan);
                $("#ekspedisi").val(`${header.ekspedisi_kode} - ${header.ekspedisi_nama}`);
                $("#pengemudi").val(header.karyawan_nama);
                $("#kendaraan").val(`${header.kendaraan_model} - ${header.kendaraan_nopol}`);
                $("#status").val(header.tr_mutasi_depo_status);
                $("#keteranganPersiapan").val(header.tr_mutasi_depo_keterangan == null ? '' : header
                    .tr_mutasi_depo_keterangan);
                $("#keterangan").val(header.tr_mutasi_depo_detail_3_keterangan == null ? '' : header
                    .tr_mutasi_depo_detail_3_keterangan);
                $("#lastUpdated").val(header.tr_mutasi_depo_tgl_update);

                global_data = [];
                $.each(detail, function(i, v) {
                    global_data.push(v);
                })

                initDetailKoreksiDraft(detail, header.gudangAsal, header.depo_detail_id);

                //check exist data in tr koreksi stok

                requestAjax(
                    "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/check_exist_in_tr_koreksi_draft'); ?>", {
                        id: header.tr_mutasi_depo_id
                    }, "POST", "JSON",
                    function(response) {
                        if (response == null) {
                            $("#konfirmasi_koreksi").attr("disabled", true);
                        } else {
                            $("#konfirmasi_koreksi").attr("disabled", false);
                        }
                    })

                // $.ajax({
                // 	url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/check_exist_in_tr_koreksi_draft') ?>",
                // 	type: "POST",
                // 	data: {
                // 		id: header.id
                // 	},
                // 	dataType: "JSON",
                // 	async: false,
                // 	success: function(response) {
                // 		if (response == null) {
                // 			$("#konfirmasi_koreksi").attr("disabled", true);
                // 		} else {
                // 			$("#konfirmasi_koreksi").attr("disabled", false);
                // 		}
                // 	}
                // });
            })
        // $.ajax({
        // 	url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/getDataKoreksiDraftById'); ?>",
        // 	type: "POST",
        // 	data: {
        // 		id: id
        // 	},
        // 	dataType: "JSON",
        // 	async: false,
        // 	success: function(response) {
        // 		const header = response.header;
        // 		const detail = response.detail;

        // 		$("#koreksi_draft_id").val(header.id);
        // 		$("#gudang_id").val(header.gudang_id);
        // 		$("#principle_id").val(header.principle_id);
        // 		$("#tipe_id").val(header.tipe_id);
        // 		$("#checker_id").val(header.checker_id);

        // 		$("#gudang_nama").val(header.gudang);

        // 		$("#gudang_asal").val(header.gudang);
        // 		$("#principle").val(header.principle);
        // 		$("#checker").val(header.checker);
        // 		$("#tipe_transaksi").val(header.tipe);
        // 		$("#status").val(header.status);

        // 		global_data = [];
        // 		$.each(detail, function(i, v) {
        // 			global_data.push(v);
        // 		})

        // 		initDetailKoreksiDraft(detail, header.gudang, header.gudang_id);

        // 		//check exist data in tr koreksi stok
        // 		$.ajax({
        // 			url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/check_exist_in_tr_koreksi_draft') ?>",
        // 			type: "POST",
        // 			data: {
        // 				id: header.id
        // 			},
        // 			dataType: "JSON",
        // 			async: false,
        // 			success: function(response) {
        // 				if (response == null) {
        // 					$("#konfirmasi_koreksi").attr("disabled", true);
        // 				} else {
        // 					$("#konfirmasi_koreksi").attr("disabled", false);
        // 				}
        // 			}
        // 		});


        // 		// UpdateQtyAmbil(detail, header.gudang, header.gudang_id);
        // 	}
        // });
    });

    function initDetailKoreksiDraft(data, gudang, gudang_id) {
        // let test = $("#koreksi_draft_id").val();
        $("#show_data").show();

        if ($.fn.DataTable.isDataTable('#table_data_tambah_koreksi_stok')) {
            $('#table_data_tambah_koreksi_stok').DataTable().destroy();
        }

        $("#table_data_tambah_koreksi_stok > tbody").empty();

        if (data.length != 0) {
            $.each(data, function(i, v) {
                let qyt_aktual = v.qty_aktual == null ? 0 : v.qty_aktual;
                $("#table_data_tambah_koreksi_stok > tbody").append(`
            <tr>
                <td class="text-center">${i + 1}</td>
                <td>${v.sku_kode} <input type="hidden" name="sku_stock_id" id="sku_stock_id" value="${v.id}"/></td>
                <td>${v.sku_nama} <input type="hidden" name="sku_id" id="sku_id" value="${v.sku_id}"/></td>
                <td class="text-center">${v.brand} <input type="hidden" name="depo_id_edit" id="depo_id_edit" value="${v.depo}"/></td>
                <td class="text-center">${v.sku_satuan} <input type="hidden" name="depo_detail_id_edit" id="depo_detail_id_edit" value="${v.depo_detail}"/></td>
                <td class="text-center">${v.sku_kemasan} <input type="hidden" name="sku_induk_id" id="sku_induk_id" value="${v.sku_induk_id}"/></td>
                <td class="text-center">${v.ed} <input type="hidden" name="ed_edit" id="ed_edit" value="${v.ed_}"/></td>
                <td class="text-center">${v.qty_plan}</td>
                <td class="text-center">${qyt_aktual}</td>
                <td class="text-center">
                    <button type="button" data-toggle="tooltip" data-placement="top" title="Pilih Pallet" data-detail2Id="${v.detail2_id}" data-id="${v.id}" data-gudang="${gudang}" data-gudang-id="${gudang_id}" data-qty-plan="${v.qty_plan}" data-ed="${v.ed}" data-sku-kode="${v.sku_kode}" data-sku-nama="${v.sku_nama}" data-qty-aktual="${qyt_aktual}" class="btn btn-info pilihpallet" ><i class="fas fa-hand-point-up"></i></button> 
                </td>
            </tr>
        `);
            });
        } else {
            $("#table_data_tambah_koreksi_stok > tbody").html(
                `<tr><td colspan="10" class="text-center text-danger" name="CAPTION-DATAKOSONG">Data Kosong</td></tr>`);
        }

        $('#table_data_tambah_koreksi_stok').DataTable();
    }

    $(document).on("click", ".pilihpallet", function() {
        let id = $(this).attr('data-id');
        let detail2Id = $(this).attr('data-detail2Id');
        let gudang_id = $(this).attr('data-gudang-id');
        let gudang = $(this).attr('data-gudang');
        let ed = $(this).attr('data-ed');
        let sku_kode = $(this).attr('data-sku-kode');
        let sku_nama = $(this).attr('data-sku-nama');
        let qty_plan = $(this).attr('data-qty-plan');
        let qty_aktual = $(this).attr('data-qty-aktual');

        $("#start_scan").attr('data-id', id);
        $("#start_scan").attr('data-detail2Id', detail2Id);
        $("#start_scan").attr('data-gudang-id', gudang_id);
        $("#start_scan").attr('data-gudang', gudang);
        $("#start_scan").attr('data-ed', ed);
        $("#start_scan").attr('data-sku-kode', sku_kode);
        $("#start_scan").attr('data-qty-plan', qty_plan);

        $("#input_manual").attr('data-id', id);
        $("#input_manual").attr('data-detail2Id', detail2Id);
        $("#input_manual").attr('data-gudang-id', gudang_id);
        $("#input_manual").attr('data-gudang', gudang);
        $("#input_manual").attr('data-ed', ed);
        $("#input_manual").attr('data-sku-kode', sku_kode);
        $("#input_manual").attr('data-qty-plan', qty_plan);


        $("#list_data_pilih_pallet").modal("show");

        $(".is-filled").empty();
        $(".is-filled").append(`
				<span><strong>Qty Aktual Koreksi</strong> &nbsp;: <span id="qtyAktualKoreksi_${gudang_id}_${ed}_${sku_kode}">${qty_aktual}</span></span>&nbsp; | <span>&nbsp; <strong>Qty Plan Koreksi</strong> &nbsp;: ${qty_plan}</span>
		`);

        $("#gudang_asal_pallet").val(gudang);
        $("#sku_kode_pallet").val(sku_kode);
        $("#sku_nama_pallet").val(sku_nama);
        $("#ed_pallet").val(ed);

        get_data(id, detail2Id, gudang_id, ed, qty_plan, sku_kode, gudang);
    });

    function get_data(id, detail2Id, gudang_id, ed, qty_plan, sku_kode, gudang) {
        let koreksi_draft_id = $("#koreksi_draft_id").val();
        $.ajax({
            url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/getDataPalletBySkuStockId'); ?>",
            type: "POST",
            data: {
                id: id,
                detail2Id,
                gudang_id: gudang_id,
                ed: ed,
                koreksi_draft_id: koreksi_draft_id
            },
            dataType: "JSON",
            async: false,
            success: function(response) {

                let dataTemp = [];
                dataTemp = []

                if (response.length > 0) {
                    if ($.fn.DataTable.isDataTable('#table_list_data_pilih_pallet')) {
                        $('#table_list_data_pilih_pallet').DataTable().destroy();
                    }

                    $("#table_list_data_pilih_pallet tbody").empty();
                    $.each(response, function(i, v) {
                        dataTemp.push({
                            id,
                            qty_plan,
                            gudang_id,
                            gudang,
                            ed,
                            sku_kode,
                            id_temp: v.id_temp,
                            pallet_id: v.pallet_id,
                            sku_stock_id: v.sku_stock_id
                        });

                        let str = "";
                        let action = "";
                        let is_scan = "";
                        let is_lock = "";
                        let is_value = 0;
                        let style = "";
                        if (v.scan == null) {
                            str +=
                                `<input type='checkbox' class='form-control check-item' style="transform:scale(1.5)" disabled name='chk-data' id='chk-data'/>`;

                            is_scan += "readonly";
                            is_value = 0;
                        } else if (v.scan == 0) {
                            str +=
                                `<input type='checkbox' class='form-control check-item' style="transform:scale(1.5)" disabled name='chk-data' id='chk-data'/>`;

                            is_scan += "readonly";
                            is_value = 0;
                        } else {
                            str +=
                                `<input type='checkbox' class='form-control check-item' style="transform:scale(1.5)" disabled checked name='chk-data' id='chk-data'/>`;

                            action +=
                                `<button type='button' class='form-control btn btn-danger batalkan_pilih_pallet' name='batalkan_pilih_pallet' id='batalkan_pilih_pallet' title="batalkan pilih pallet" data-detail2Id="${v.detail2_id}" data-id="${id}" data-qty-plan="${qty_plan}" data-gudang-id="${gudang_id}" data-ed="${ed}" data-sku-kode="${sku_kode}" data-id-temp="${v.id_temp}" data-pallet-id="${v.pallet_id}" data-sku-stock-id="${v.sku_stock_id}"><i class="fas fa-xmark"></i></button>`;

                            is_scan += "";
                            is_value = v.qty_ambil == null ? 0 : v.qty_ambil;

                        }

                        if (v.lock == 1) {
                            is_lock += "<span>In Progress Mutasi</span>";
                            style += "background-color: #bae6fd; font-weight:bold"
                        }
                        if (v.lock == null || v.lock == 0) {
                            is_lock += "-";
                            style += ""
                        }

                        $("#table_list_data_pilih_pallet > tbody").append(`
              <tr class="text-center" style="${style}">
                <td>${str}</td>
                <td>${v.lokasi_rak}</td>
                <td>${v.lokasi_bin}</td>
                <td>${v.no_pallet}</td>
                <td>${v.ed}</td>
                <td>${v.qty}</td>
                <td><input type='text' class='form-control numeric  UpdateQtyAmbil' ${is_scan} name='qty_ambil[]' id='qty_ambil[]' data-detail2Id="${v.detail2_id}" data-id-temp="${v.id_temp}" data-pallet-id="${v.pallet_id}" data-qty-plan="${qty_plan}" data-qty="${v.qty}" data-id="${id}" data-sku-stock-id="${v.sku_stock_id}" data-ed="${ed}" data-sku-kode="${sku_kode}" data-index="${i}" value='${is_value}'/></td>
                <td>${is_lock}</td>
                <td>${action}</td>
              </tr>
          `);
                    });

                    $('#table_list_data_pilih_pallet').DataTable({
                        columnDefs: [{
                            sortable: false,
                            targets: [0, 1, 2, 3, 4, 5, 6, 7]
                        }],
                        lengthMenu: [
                            [-1],
                            ['All']
                        ],
                    });
                } else {
                    $("#table_list_data_pilih_pallet > tbody").html(
                        `<tr><td colspan="8" class="text-center text-danger">Data Kosong</td></tr>`);
                }

                if (dataTemp.length != 0) {
                    const dataFinal = Object.assign({}, ...dataTemp);

                    delete dataFinal.pallet_id;
                    // delete dataFinal.sku_stock_id;

                    dataFinal.data = dataTemp.map((d) => ({
                        pallet_id: d.pallet_id,
                    }));

                    $(".btn_close_list_data_pilih_pallet").attr('data-params', JSON.stringify(dataFinal));
                }
            }
        })
    }

    $(document).on("change", ".UpdateQtyAmbil", function() {
        let id = $(this).attr('data-id');
        let detail2Id = $(this).attr('data-detail2Id');
        let id_change = $("#id_change_no_draft").val();
        let gudang_id = $("#gudang_id").val();
        let gudang = $("#gudang_nama").val();
        let id_temp = $(this).attr('data-id-temp');
        let sku_stock_id = $(this).attr('data-sku-stock-id');
        let pallet_id = $(this).attr('data-pallet-id');
        let ed = $(this).attr('data-ed');
        let sku_kode = $(this).attr('data-sku-kode');
        let qty_plan = parseInt($(this).attr('data-qty-plan'));
        let qty_ambil = parseInt($(this).val());
        let qty_ready = parseInt($(this).attr('data-qty'));

        let new_qty_ = 0;
        $("#table_list_data_pilih_pallet > tbody tr").each(function() {
            let qty_ = $(this).find("td:eq(6) input[type='text']").val();
            new_qty_ += parseInt(qty_);
        });

        if (qty_ambil <= qty_plan) {
            if (qty_ambil <= qty_ready) {
                //requst to update qty
                $.ajax({
                    url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/UpdateQtyAmbilInDetail2Temp'); ?>",
                    type: "POST",
                    data: {
                        id: id_temp,
                        pallet_id: pallet_id,
                        qty: qty_ambil
                    },
                    dataType: "JSON",
                    async: false,
                    success: function(response) {

                        $('#table_data_tambah_koreksi_stok').fadeOut("slow", function() {
                            $(this).hide();
                        }).fadeIn("slow", function() {
                            $(this).show();
                            let new_data = global_data.findIndex((value) => value.id ==
                                sku_stock_id);
                            global_data[new_data]['qty_aktual'] = new_qty_;
                            initDetailKoreksiDraft(global_data, gudang, gudang_id)
                            $(`#qtyAktualKoreksi_${gudang_id}_${ed}_${sku_kode}`).text(new_qty_)
                        });
                        $('#list_data_pilih_pallet').fadeOut("slow", function() {
                            $(this).hide();;
                        }).fadeIn("slow", function() {
                            $(this).show();
                            $(`#qtyAktualKoreksi_${gudang_id}_${ed}_${sku_kode}`).text(new_qty_)
                            get_data(id, detail2Id, gudang_id, ed, qty_plan, sku_kode, gudang);
                        });
                    }
                });
            } else {
                message("Error!", "Qty ambil melebihi jumlah qty tersedia", "error");
                $(this).val("");
                $(this).focus();
                return false;
            }
        } else {
            message("Error!", "Qty ambil melebihi jumlah qty plan koreksi", "error");
            $(this).val("");
            $(this).focus();
            return false;
        }
    });

    $(document).on("click", ".batalkan_pilih_pallet", function() {
        let id = $(this).attr('data-id');
        let detail2Id = $(this).attr('data-detail2Id');
        let gudang_id = $(this).attr('data-gudang-id');
        let gudang = $("#gudang_nama").val();
        let ed = $(this).attr('data-ed');
        let sku_kode = $(this).attr('data-sku-kode');
        let id_temp = $(this).attr('data-id-temp');
        let pallet_id = $(this).attr('data-pallet-id');
        let sku_stock_id = $(this).attr('data-sku-stock-id');
        let qty_plan = $(this).attr('data-qty-plan');



        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Ingin membatalkan pilih pallet ini!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Batalkan",
            cancelButtonText: "Tidak, Tutup"
        }).then((result) => {
            if (result.value == true) {

                $.ajax({
                    url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/cancel_pallet') ?>",
                    type: "POST",
                    data: {
                        id: id_temp,
                        pallet_id: pallet_id,
                        sku_stock_id: sku_stock_id
                    },
                    dataType: "JSON",
                    async: false,
                    success: function(data) {
                        if (data) {
                            message_topright("success", "Berhasil membatalkan pallet");

                            $('#table_data_tambah_koreksi_stok').fadeOut("slow", function() {
                                $(this).hide();
                                get_data(id, detail2Id, gudang_id, ed, qty_plan,
                                    sku_kode, gudang);
                            }).fadeIn("slow", function() {
                                $(this).show();

                                let new_qty_ = 0;
                                $("#table_list_data_pilih_pallet > tbody tr").each(
                                    function() {
                                        let qty_ = $(this).find(
                                            "td:eq(6) input[type='text']").val();
                                        new_qty_ += parseInt(qty_);
                                    });

                                let new_data = global_data.findIndex((value) => value
                                    .id == sku_stock_id);
                                global_data[new_data]['qty_aktual'] = new_qty_;
                                initDetailKoreksiDraft(global_data, gudang, gudang_id)
                            });


                            $('#list_data_pilih_pallet').fadeOut("slow", function() {
                                $(this).hide();
                                get_data(id, detail2Id, gudang_id, ed, qty_plan,
                                    sku_kode, gudang);
                            }).fadeIn("slow", function() {
                                $(this).show();

                                let new_qty__ = 0;
                                $("#table_list_data_pilih_pallet > tbody tr").each(
                                    function() {
                                        let qty__ = $(this).find(
                                            "td:eq(6) input[type='text']").val();
                                        new_qty__ += parseInt(qty__);
                                    });


                                $(`#qtyAktualKoreksi_${gudang_id}_${ed}_${sku_kode}`)
                                    .text(new_qty__)
                                get_data(id, detail2Id, gudang_id, ed, qty_plan,
                                    sku_kode, gudang);
                            });
                        }
                    },
                });
            }
        });
    });

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
        let id = $(this).attr('data-id');
        let detail2Id = $(this).attr('data-detail2Id');
        let gudang_id = $(this).attr('data-gudang-id');
        let gudang = $(this).attr('data-gudang');
        let sku_kode = $(this).attr('data-sku-kode');
        let ed = $(this).attr('data-ed');
        let qty_plan = $(this).attr('data-qty-plan');
        let koreksi_draft_id = $("#koreksi_draft_id").val();
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
                        scan(decodedText);
                    }
                };

                const scan = (decodedText) => {

                    //check ada apa kosong di tabel distribusi_penerimaan_detail_temp
                    //jika ada update statusnya jadi 1
                    $.ajax({
                        url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/check_kode_pallet_by_no_pallet'); ?>",
                        type: "POST",
                        data: {
                            id: id,
                            detail2Id: detail2Id,
                            koreksi_draft_id: koreksi_draft_id,
                            kode_pallet: decodedText
                        },
                        dataType: "JSON",
                        success: function(data) {
                            $("#txtpreviewscan").val(data.kode);
                            if (data.type == 200) {
                                Swal.fire("Success!", data.message, "success").then(
                                    function(result) {
                                        if (result.value == true) {
                                            html5QrCode.resume();
                                        }
                                    });
                                $('#table_list_data_pilih_pallet').fadeOut("slow",
                                    function() {
                                        $(this).hide();
                                    }).fadeIn("slow", function() {
                                    $(this).show();

                                    get_data(id, detail2Id, gudang_id, ed, qty_plan,
                                        sku_kode, gudang);
                                });
                            }
                            if (data.type == 201) {
                                Swal.fire("Error!", data.message, "error").then(function(
                                    result) {
                                    if (result.value == true) {
                                        html5QrCode.resume();
                                    }
                                });
                                // message("Error!", data.message, "error");
                            }
                            if (data.type == 202) {
                                Swal.fire("Info!", data.message, "info").then(function(
                                    result) {
                                    if (result.value == true) {
                                        html5QrCode.resume();
                                    }
                                });
                            }

                            if (data.type == 203) {
                                Swal.fire("Info!", data.message, "info").then(function(
                                    result) {
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
    });

    $(document).on("click", "#input_manual", function() {
        let id = $(this).attr('data-id');
        let detail2Id = $(this).attr('data-detail2Id');
        let gudang_id = $(this).attr('data-gudang-id');
        let gudang = $(this).attr('data-gudang');
        let ed = $(this).attr('data-ed');
        let sku_kode = $(this).attr('data-sku-kode');
        let qty_plan = $(this).attr('data-qty-plan');

        $("#check_kode").attr('data-id', id);
        $("#check_kode").attr('data-detail2Id', detail2Id);
        $("#check_kode").attr('data-gudang-id', gudang_id);
        $("#check_kode").attr('data-gudang', gudang);
        $("#check_kode").attr('data-ed', ed);
        $("#check_kode").attr('data-sku-kode', sku_kode);
        $("#check_kode").attr('data-qty-plan', qty_plan);

        $("#input_manual").hide();
        $("#close_input").show();
        $("#preview_input_manual").show();
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

    $(document).on("click", "#check_kode", function() {
        let id = $(this).attr('data-id');
        let detail2Id = $(this).attr('data-detail2Id');
        let gudang_id = $(this).attr('data-gudang-id');
        let gudang = $(this).attr('data-gudang');
        let ed = $(this).attr('data-ed');
        let sku_kode = $(this).attr('data-sku-kode');
        let qty_plan = $(this).attr('data-qty-plan');
        let koreksi_draft_id = $("#koreksi_draft_id").val();
        let barcode = $("#kode_barcode").val();
        let attachment = $("#myFileInput");

        if (barcode == "") {
            message("Error!", "Kode Pallet tidak boleh kosong", "error");
            return false;
            // } else if (attachment.val() == "") {
            //     message("Error!", "Bukti cek fisik tidak boleh kosong", "error");
            //     return false;
        } else {
            let new_form = new FormData();
            let files = attachment[0].files[0];
            new_form.append('id', id);
            new_form.append('detail2Id', detail2Id);
            new_form.append('koreksi_draft_id', koreksi_draft_id);
            new_form.append('kode_pallet', barcode);
            new_form.append('file', files);

            $.ajax({
                url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/check_kode_pallet_by_no_pallet'); ?>",
                type: "POST",
                data: new_form,
                contentType: false,
                processData: false,
                dataType: "JSON",
                beforeSend: () => {
                    $("#loading_cek_manual").show();
                },
                success: function(data) {
                    $("#txtpreviewscan").val(data.kode);
                    if (data.type == 200) {
                        message("Success!", data.message, "success");
                        $("#kode_barcode").val("");
                        $('#myFileInput').val("");
                        $('#show-file').empty();
                        $('#table_list_data_pilih_pallet').fadeOut("slow", function() {
                            $(this).hide();
                        }).fadeIn("slow", function() {
                            $(this).show();
                            get_data(id, detail2Id, gudang_id, ed, qty_plan, sku_kode, gudang);
                        });
                    }
                    if (data.type == 201) {
                        message("Error!", data.message, "error");
                    }
                    if (data.type == 202) {
                        message("Info!", data.message, "info");
                    }
                    if (data.type == 203) {
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

    $(document).on("click", ".btn_pilih_pallet", function() {
        let is_undefined = 0;
        let count = $("#table_list_data_pilih_pallet > tbody tr").length;
        $("#table_list_data_pilih_pallet > tbody tr").each(function() {
            let is_checked = $(this).find("td:eq(0) input[type='checkbox']:checked");
            if (is_checked.val() == undefined) {
                is_undefined++;
            }
        });

        if (is_undefined == count) {
            message("Error!", "Belum ada pallet yang terpilih!", "error");
            return false;
        } else {
            $("#list_data_pilih_pallet").fadeOut("slow", function() {
                $(this).modal("hide");
            });
            // $("#list_data_pilih_pallet").modal("hide");
            $("#check_scan").attr('checked', false).trigger('change');
        }
    });

    $(document).on("click", ".btn_close_list_data_pilih_pallet", function() {


        let datas = JSON.parse($(this).attr('data-params'));

        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Apakah anda yakin ingin membatalkan?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Yakin",
            cancelButtonText: "Tidak, Tutup"
        }).then((result) => {
            if (result.value == true) {
                $.ajax({
                    url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/cancel_pallet_multi') ?>",
                    type: "POST",
                    data: {
                        id: datas.id_temp,
                        pallet_id: datas.data,
                        sku_stock_id: datas.sku_stock_id
                    },
                    dataType: "JSON",
                    success: function(data) {
                        if (data) {
                            message_topright("success", "Berhasil membatalkan");
                            $('#table_data_tambah_koreksi_stok').fadeOut("slow", function() {
                                $(this).hide();
                            }).fadeIn("slow", function() {
                                $(this).show();

                                // global_data.map((data) => data.qty_aktual = 0);

                                let new_data = global_data.findIndex((value) => value.id == datas.sku_stock_id);
                                global_data[new_data]['qty_aktual'] = 0;


                                initDetailKoreksiDraft(global_data, datas.gudang, datas
                                    .gudang_id)
                                $(`#qtyAktualKoreksi_${datas.gudang_id}_${datas.ed}_${datas.sku_kode}`)
                                    .text(0)
                            });
                            $("#list_data_pilih_pallet").modal("hide");
                        }
                    },
                });
            }
        });



        $("#check_scan").attr('checked', false).trigger('change');
    });

    $(document).on("click", "#simpan_koreksi", function() {
        let koreksi_draft_id = $("#koreksi_draft_id").val();
        let tgl = $("#tgl").val();
        let keterangan = $("#keterangan").val();
        let gudang_id = $("#gudang_id").val();
        let lastUpdated = $("#lastUpdated").val();

        let is_zero = 0;
        let count = $("#table_data_tambah_koreksi_stok > tbody tr").length;
        $("#table_data_tambah_koreksi_stok > tbody tr").each(function() {
            let is_Qty_plan = $(this).find("td:eq(8)").text();
            if (is_Qty_plan == 0) {
                is_zero++;
            }
        });

        if (is_zero == count) {
            message("Error!", "Qty Plan Koreksi setidaknya mininal 1 yg terisi!", "error");
            return false;
        } else {
            messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup')
                .then((result) => {
                    if (result.value == true) {
                        requestAjax(
                            "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/save_data'); ?>", {
                                koreksi_draft_id: koreksi_draft_id,
                                tgl: tgl,
                                keterangan: keterangan,
                                gudang_id: gudang_id,
                                lastUpdated
                            }, "POST", "JSON",
                            function(response) {
                                if (response == 1) {
                                    message_topright("success", "Data berhasil disimpan");
                                    setTimeout(() => {
                                        location.href =
                                            "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/PengambilanBarangMutasiAntarUnitMenu') ?>";
                                    }, 500);
                                }

                                if (response == 0) return message_topright("error", "Data gagal disimpan");
                                if (response == 2) return messageNotSameLastUpdated()
                            }, "#simpan_koreksi")
                    }
                });
        }
    });

    $(document).on("click", "#konfirmasi_koreksi", function() {
        let koreksi_draft_id = $("#koreksi_draft_id").val();
        let lastUpdated = $("#lastUpdated").val();
        let tipe_id = $("#tipe_id").val();
        let arr_sku_stock_id = [];
        let arr_sku_id = [];
        let arr_sku_induk_id = [];
        let arr_depo_id = [];
        let arr_depo_detail_id = [];
        let arr_ed = [];
        let arr_is_Qty_plan = [];
        let arr_detail = [];
        $("#table_data_tambah_koreksi_stok > tbody tr").each(function() {
            let sku_stock_id = $(this).find("td:eq(1) input[type='hidden']");
            let sku_id = $(this).find("td:eq(2) input[type='hidden']");
            let depo_id = $(this).find("td:eq(3) input[type='hidden']");
            let depo_detail_id = $(this).find("td:eq(4) input[type='hidden']");
            let sku_induk_id = $(this).find("td:eq(5) input[type='hidden']");
            let ed = $(this).find("td:eq(6) input[type='hidden']");
            let is_Qty_Ready = $(this).find("td:eq(7)");
            let is_Qty_plan = $(this).find("td:eq(8)");
            if (is_Qty_Ready.text() != is_Qty_plan.text()) {
                message("Error!",
                    "Qty Plan Koreksi dan Qty Aktual koreksi ada yang tidak compare, Silahkan cek kembali!",
                    "error");
                $("#error").val("1");
                return false;
            } else {
                $("#error").val("0");
                sku_stock_id.map(function() {
                    arr_sku_stock_id.push($(this).val());
                }).get();

                sku_id.map(function() {
                    arr_sku_id.push($(this).val());
                }).get();
                depo_id.map(function() {
                    arr_depo_id.push($(this).val());
                }).get();
                depo_detail_id.map(function() {
                    arr_depo_detail_id.push($(this).val());
                }).get();
                sku_induk_id.map(function() {
                    arr_sku_induk_id.push($(this).val());
                }).get();

                ed.map(function() {
                    arr_ed.push($(this).val());
                }).get();

                is_Qty_plan.map(function() {
                    arr_is_Qty_plan.push($(this).text());
                }).get();
            }
        });

        let error = $("#error").val();
        if (error == 1) {
            return false;
        } else {
            if (arr_sku_stock_id != null) {
                for (let index = 0; index < arr_sku_stock_id.length; index++) {
                    arr_detail.push({
                        'sku_stock_id': arr_sku_stock_id[index],
                        'sku_id': arr_sku_id[index],
                        'sku_induk_id': arr_sku_induk_id[index],
                        'depo_id': arr_depo_id[index],
                        'depo_detail_id': arr_depo_detail_id[index],
                        'ed': arr_ed[index],
                        'qty_aktual': arr_is_Qty_plan[index]
                    });
                }
            }

            messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Konfirmasi', 'Tidak, Tutup')
                .then((result) => {
                    if (result.value == true) {
                        requestAjax(
                            "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/confirm_data'); ?>", {
                                koreksi_draft_id: koreksi_draft_id,
                                tipe_id: tipe_id,
                                data_detail: arr_detail,
                                lastUpdated
                            }, "POST", "JSON",
                            function(response) {
                                if (response == 1) {
                                    message_topright("success", "Data berhasil dikonfirmasi");
                                    setTimeout(() => {
                                        location.href =
                                            "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/PengambilanBarangMutasiAntarUnitMenu') ?>";
                                    }, 500);
                                }

                                if (response == 0) return message_topright("error", "Data gagal disimpan");
                                if (response == 2) return messageNotSameLastUpdated()
                            }, "#konfirmasi_koreksi")
                    }
                });

            // Swal.fire({
            // 	title: "Apakah anda yakin?",
            // 	text: "Pastikan data yang sudah anda input benar!",
            // 	icon: "warning",
            // 	showCancelButton: true,
            // 	confirmButtonColor: "#3085d6",
            // 	cancelButtonColor: "#d33",
            // 	confirmButtonText: "Ya, Konfirmasi",
            // 	cancelButtonText: "Tidak, Tutup"
            // }).then((result) => {
            // 	if (result.value == true) {
            // 		$.ajax({
            // 			url: "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/confirm_data') ?>",
            // 			type: "POST",
            // 			data: {
            // 				koreksi_draft_id: koreksi_draft_id,
            // 				tipe_id: tipe_id,
            // 				data_detail: arr_detail
            // 			},
            // 			dataType: "JSON",
            // 			success: function(data) {
            // 				if (data == 1) {
            // 					message_topright("success", "Data berhasil dikonfirmasi");
            // 					setTimeout(() => {
            // 						location.href = "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/KoreksiStokBarangMenu') ?>";
            // 					}, 500);
            // 				} else {
            // 					message_topright("error", "Data gagal dikonfirmasi");
            // 				}
            // 			},
            // 		});
            // 	}
            // });
        }
    });

    $(document).on("click", "#kembali_koreksi", function() {
        setTimeout(() => {
            Swal.fire({
                title: '<span ><i class="fa fa-spinner fa-spin"></i> Loading...</span>',
                showConfirmButton: false,
                timer: 500
            });
            location.href =
                "<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/PengambilanBarangMutasiAntarUnitMenu') ?>";
        }, 500);
    })

    $("#search_kode_pallet").on('click', function() {
        if ($("#kode_barcode").val() != "") {
            fetch('<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/getKodeAutoComplete?params='); ?>' +
                    $("#kode_barcode").val())
                .then(response => response.json())
                .then((results) => {
                    if (!results[0]) {
                        document.getElementById('table-fixed').style.display = 'none';
                    } else {
                        let data = "";
                        results.forEach(function(e, i) {
                            data += `
                            <tr onclick="getNoSuratJalanEks('${e.kode}')" style="cursor:pointer">
                                    <td class="col-xs-1">${i + 1}.</td>
                                    <td class="col-xs-11">${e.kode}</td>
                            </tr>
                        `;
                        })

                        document.getElementById('konten-table').innerHTML = data;
                        document.getElementById('table-fixed').style.display = 'block';
                    }
                });
        } else {
            document.getElementById('table-fixed').style.display = 'none';
        }
    })

    // document.getElementById('kode_barcode').addEventListener('keyup', function() {
    // 	const typeScan = $("#tempValForScan").val();
    // 	if (this.value != "") {
    // 		fetch('<?= base_url('WMS/Transaksi/MutasiAntarUnit/PengambilanBarangMutasiAntarUnit/getKodeAutoComplete?params='); ?>' + this.value)
    // 			.then(response => response.json())
    // 			.then((results) => {
    // 				if (!results[0]) {
    // 					document.getElementById('table-fixed').style.display = 'none';
    // 				} else {
    // 					let data = "";
    // 					// console.log(results);
    // 					results.forEach(function(e, i) {
    // 						data += `
    // 											<tr onclick="getNoSuratJalanEks('${e.kode}')" style="cursor:pointer">
    // 													<td class="col-xs-1">${i + 1}.</td>
    // 													<td class="col-xs-11">${e.kode}</td>
    // 											</tr>
    // 											`;
    // 					})

    // 					document.getElementById('konten-table').innerHTML = data;
    // 					// console.log(data);
    // 					document.getElementById('table-fixed').style.display = 'block';
    // 				}
    // 			});
    // 	} else {
    // 		document.getElementById('table-fixed').style.display = 'none';
    // 	}
    // });

    function getNoSuratJalanEks(data) {
        $("#kode_barcode").val(data);
        document.getElementById('table-fixed').style.display = 'none'
        $("#check_kode").click();
    }
</script>