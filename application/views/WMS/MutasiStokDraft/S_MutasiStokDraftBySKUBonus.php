<script type="text/javascript">
    var index_pallet = 0;
    var arr_list_multi_sku = [];
    var arr_tr_mutasi_stok_detail = [];

    function message_custom(titleType, iconType, htmlType) {
        Swal.fire({
            title: titleType,
            icon: iconType,
            html: htmlType,
        });
    }

    <?php if (isset($act)) { ?>
        <?php if ($act == "edit") { ?>
            <?php foreach ($Detail as $key => $value) { ?>
                arr_list_multi_sku.push({
                    'sku_id': "<?= $value['sku_id'] ?>",
                    'sku_stock_id': "<?= $value['sku_stock_id'] ?>",
                    'sku_stock_qty': "<?= $value['qty'] ?>",
                    'sku_stock_expired_date': "<?= $value['sku_stock_expired_date'] ?>",
                    'pallet_id_asal': "<?= $value['pallet_id_asal'] ?>",
                    'sku_stock_qty_awal': "<?= $value['sku_stock_qty_awal'] ?>"
                });
            <?php } ?>

            Get_list_mutasi_stock_detail();
        <?php } ?>

        <?php if ($act == "detail") { ?>
            <?php foreach ($Detail as $key => $value) { ?>
                arr_list_multi_sku.push({
                    'sku_id': "<?= $value['sku_id'] ?>",
                    'sku_stock_id': "<?= $value['sku_stock_id'] ?>",
                    'sku_stock_qty': "<?= $value['qty'] ?>",
                    'sku_stock_expired_date': "<?= $value['sku_stock_expired_date'] ?>",
                    'pallet_id_asal': "<?= $value['pallet_id_asal'] ?>",
                    'sku_stock_qty_awal': "<?= $value['sku_stock_qty_awal'] ?>"
                });
            <?php } ?>

            Get_list_mutasi_stock_detail();
        <?php } ?>
    <?php } ?>

    $('#select-all-sku').click(function(event) {
        if (this.checked) {
            // Iterate each checkbox
            $('[name="CheckboxSKU"]:checkbox').each(function() {
                this.checked = true;
                var sku_id = this.getAttribute('data-sku_id');
                var sku_stock_id = this.getAttribute('data-sku_stock_id');
                var sku_stock_qty = this.getAttribute('data-sku_stock_qty');
                var sku_stock_qty_awal = this.getAttribute('data-sku_stock_qty_awal');
                var sku_stock_expired_date = this.getAttribute('data-sku_stock_expired_date');

                arr_list_multi_sku.push({
                    'sku_id': sku_id,
                    'sku_stock_id': sku_stock_id,
                    'sku_stock_qty': 0,
                    'sku_stock_expired_date': sku_stock_expired_date,
                    'pallet_id_asal': "",
                    'sku_stock_qty_awal': sku_stock_qty_awal,
                });
                // console.log(this.getAttribute('data-customer'));
            });
        } else {
            $('[name="CheckboxSKU"]:checkbox').each(function() {
                this.checked = false;
                arr_list_multi_sku = [];
            });
        }
    });

    loadingBeforeReadyPage()
    $(document).ready(
        function() {
            $(".select2").select2();
            $('#filter_mutasi_draft_tgl_draft').daterangepicker({
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

            $("#mutasi_draft_principle").append('<option value="">** <label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label> **</option>');
            $("#filter_mutasi_draft_principle").append('<option value=""><label name="CAPTION-ALL">All</label></option>');
        }
    );

    function GetPrincipleHome(client_wms_id) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/MutasiStokDraft/getDataPrincipleByClientWmsId') ?>",
            data: {
                id: client_wms_id
            },
            dataType: "JSON",
            success: function(response) {
                $("#filter_mutasi_draft_principle").empty();
                let html = "";
                html += '<option value=""><label name="CAPTION-ALL">All</label></option>';
                $.each(response, function(i, v) {
                    html += "<option value=" + v.id + ">" + v.nama + "</option>";
                });
                $("#filter_mutasi_draft_principle").append(html);

            }
        });
    }

    function GetPrinciple(client_wms_id) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/MutasiStokDraft/getDataPrincipleByClientWmsId') ?>",
            data: {
                id: client_wms_id
            },
            dataType: "JSON",
            success: function(response) {
                $("#mutasi_draft_principle").empty();
                let html = "";
                html += '<option value="">** <label name="CAPTION-PILIHPRINCIPLE">Pilih Principle</label> **</option>';
                $.each(response, function(i, v) {
                    html += "<option value=" + v.id + ">" + v.nama + "</option>";
                });
                $("#mutasi_draft_principle").append(html);

            }
        });

        Get_list_sku_bonus_tidak_ada_di_gudang_bonus();
    }

    $('#mutasi_draft_approval').click(function(event) {
        if (this.checked) {
            $("#mutasi_draft_status").val("In Progress Approval");
        } else {
            $("#mutasi_draft_status").val("Draft");
        }
    });

    function GetPencarianMutasiStokDraftTable() {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MutasiStokDraft/GetPencarianMutasiStokDraftTable') ?>",
            data: {
                tanggal: $("#filter_mutasi_draft_tgl_draft").val(),
                id: $("#filter_no_mutasi_draft").val(),
                gudang_asal: $("#filter_gudang_asal_mutasi_draft").val(),
                gudang_tujuan: $("#filter_gudang_tujuan_mutasi_draft").val(),
                tipe: $("#filter_mutasi_draft_tipe_transaksi").val(),
                client_wms: $("#filter_mutasi_draft_perusahaan").val(),
                principle: $("#filter_mutasi_draft_principle").val(),
                checker: $("#filter_mutasi_draft_checker").val(),
                status: $("#filter_mutasi_draft_status").val()
            },
            dataType: "JSON",
            success: function(response) {
                $('#table_pencarian_mutasi_draft').fadeOut("slow", function() {
                    $(this).hide();

                    $('#table_pencarian_mutasi_draft > tbody').empty('');

                    if ($.fn.DataTable.isDataTable('#table_pencarian_mutasi_draft')) {
                        $('#table_pencarian_mutasi_draft').DataTable().clear();
                        $('#table_pencarian_mutasi_draft').DataTable().destroy();
                    }

                }).fadeIn("slow", function() {
                    $(this).show();

                    if (response.MutasiStokDraft.length != 0) {

                        $.each(response.MutasiStokDraft, function(i, v) {

                            if (v.tr_mutasi_stok_status == "Draft") {
                                $("#table_pencarian_mutasi_draft > tbody").append(`
									<tr>
										<td class="text-center">${i+1}</td>
										<td class="text-left">${v.tr_mutasi_stok_kode}</td>
										<td class="text-left">${v.tr_mutasi_stok_tanggal}</td>
										<td class="text-left">${v.principle_kode}</td>
										<td class="text-left">${v.tr_mutasi_stok_nama_checker}</td>
										<td class="text-left">${v.depo_detail_nama}</td>
										<td class="text-left">${v.tr_mutasi_stok_status}</td>
										<td class="text-center">
											<a href="<?= base_url(); ?>WMS/MutasiStokDraft/MutasiStokDraftEdit/?tr_mutasi_stok_id=${v.tr_mutasi_stok_id}" class="btn btn-primary" target="_blank"><i class="fa fa-pencil"></i></a>
										</td>
									</tr>
								`);
                            } else {
                                $("#table_pencarian_mutasi_draft > tbody").append(`
									<tr>
										<td class="text-center">${i+1}</td>
										<td class="text-left">${v.tr_mutasi_stok_kode}</td>
										<td class="text-left">${v.tr_mutasi_stok_tanggal}</td>
										<td class="text-left">${v.principle_kode}</td>
										<td class="text-left">${v.tr_mutasi_stok_nama_checker}</td>
										<td class="text-left">${v.depo_detail_nama}</td>
										<td class="text-left">${v.tr_mutasi_stok_status}</td>
										<td class="text-center">
											<a href="<?= base_url(); ?>WMS/MutasiStokDraft/MutasiStokDraftDetail/?tr_mutasi_stok_id=${v.tr_mutasi_stok_id}" class="btn btn-primary" target="_blank"><i class="fa fa-eye"></i></a>
										</td>
									</tr>
								`);

                            }
                        });

                        $("#table_pencarian_mutasi_draft").DataTable({
                            lengthMenu: [
                                [50, 100, 200, -1],
                                [50, 100, 200, 'All'],
                            ],
                        });
                    }
                });
            }
        });
    }

    function ChPencarianMutasiStokDraftTable(JSONMutasiPallet) {
        $("#table_pencarian_mutasi_draft > tbody").html('');
        $("#loadingview").hide();

        var MutasiPallet = JSON.parse(JSONMutasiPallet);
        var no = 1;

        if (MutasiPallet.MutasiStokDraft != 0) {
            if ($.fn.DataTable.isDataTable('#table_pencarian_mutasi_draft')) {
                $('#table_pencarian_mutasi_draft').DataTable().destroy();
            }

            $('#table_pencarian_mutasi_draft tbody').empty();

            for (i = 0; i < MutasiPallet.MutasiStokDraft.length; i++) {
                var tr_mutasi_pallet_draft_id = MutasiPallet.MutasiStokDraft[i].tr_mutasi_pallet_draft_id;
                var tr_mutasi_pallet_draft_kode = MutasiPallet.MutasiStokDraft[i].tr_mutasi_pallet_draft_kode;
                var tr_mutasi_pallet_draft_tanggal = MutasiPallet.MutasiStokDraft[i].tr_mutasi_pallet_draft_tanggal;
                var tr_mutasi_pallet_draft_tipe = MutasiPallet.MutasiStokDraft[i].tr_mutasi_pallet_draft_tipe;
                var principle_id = MutasiPallet.MutasiStokDraft[i].principle_id;
                var principle_kode = MutasiPallet.MutasiStokDraft[i].principle_kode;
                var tr_mutasi_pallet_draft_nama_checker = MutasiPallet.MutasiStokDraft[i].tr_mutasi_pallet_draft_nama_checker;
                var depo_detail_id_asal = MutasiPallet.MutasiStokDraft[i].depo_detail_id_asal;
                var gudang_asal = MutasiPallet.MutasiStokDraft[i].gudang_asal;
                var depo_detail_id_tujuan = MutasiPallet.MutasiStokDraft[i].depo_detail_id_tujuan;
                var gudang_tujuan = MutasiPallet.MutasiStokDraft[i].gudang_tujuan;
                var tr_mutasi_pallet_draft_status = MutasiPallet.MutasiStokDraft[i].tr_mutasi_pallet_draft_status;


                var strmenu = '';

                strmenu = strmenu + '<tr>';
                strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_kode + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_tanggal + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_tipe + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + principle_kode + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_nama_checker + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + gudang_asal + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + gudang_tujuan + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + tr_mutasi_pallet_draft_status + '</td>';
                strmenu = strmenu + '	<td class="text-center"><a href="<?= base_url(); ?>WMS/MutasiStokDraft/MutasiStokDraftEdit/?tr_mutasi_pallet_draft_id=' + tr_mutasi_pallet_draft_id + '" class="btn btn-primary" target="_blank"><i class="fa fa-pencil"></i></a></td>';
                strmenu = strmenu + '</tr>';
                no++;

                $("#table_pencarian_mutasi_draft > tbody").append(strmenu);
            }

            $("#loadingview").hide();

            $('#table_pencarian_mutasi_draft').DataTable({
                "lengthMenu": [
                    [10],
                    [10]
                ]
            });
        } else {
            ResetForm();
        }
    }

    function GetSKU() {
        var gudang_asal = $("#gudang_asal_mutasi_draft").val();
        var perusahaan = $("#mutasi_draft_perusahaan").val();
        var principle = $("#mutasi_draft_principle").val();

        if (gudang_asal == "") {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Pilih Gudang Asal!'
            });
        } else if (perusahaan == "") {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Pilih Perusahaan!'
            });
        } else if (principle == "") {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Pilih Principle!'
            });
        } else {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('WMS/MutasiStokDraft/GetSKUByDOBonus') ?>",
                data: {
                    tgl: $("#mutasi_draft_tanggal_rencana_kirim").val(),
                    tipe_do: $("#mutasi_draft_tipe_do").val(),
                    gudang_asal: $("#gudang_asal_mutasi_draft").val(),
                    principle: $("#mutasi_draft_principle").val(),
                    perusahaan: perusahaan,
                    arr_list_multi_sku: arr_list_multi_sku
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.SKU.length > 0) {
                        $('#modal-sku').modal('show');

                        $('#data-table-sku').fadeOut("slow", function() {
                            $(this).hide();

                            $('#data-table-sku > tbody').empty('');

                            if ($.fn.DataTable.isDataTable('#data-table-sku')) {
                                $('#data-table-sku').DataTable().clear();
                                $('#data-table-sku').DataTable().destroy();
                            }

                        }).fadeIn("slow", function() {
                            $(this).show();

                            if (response.SKU.length != 0) {
                                $.each(response.Gudang, function(i, v) {
                                    $("#gudang_asal_sku").val(v.depo_detail_nama);
                                });

                                $.each(response.Principle, function(i, v) {
                                    $("#principle_sku").val(v.principle_kode);
                                });

                                $.each(response.Perusahaan, function(i, v) {
                                    $("#perusahaan_sku").val(v.client_wms_nama);
                                });

                                $.each(response.SKU, function(i, v) {
                                    $("#data-table-sku > tbody").append(`
										<tr>
											<td class="text-center">
												<input type="checkbox" name="CheckboxSKU" id="item-${i}-sku_stock_id" data-sku_id="${v.sku_id}" data-sku_stock_id="${v.sku_stock_id}" data-sku_stock_qty="${v.sku_stock_qty}" data-sku_stock_expired_date="${v.sku_stock_expired_date}" data-sku_stock_qty_awal="${v.sku_stock_qty_awal}" value="${v.sku_stock_id}" onClick="PushArraySKU('${i}','${v.sku_id}','${v.sku_stock_id}','${v.sku_stock_qty}','${v.sku_stock_expired_date}','${v.sku_stock_qty_awal}')">
											</td>
											<td class="text-left">${v.sku_kode}</td>
											<td class="text-left">${v.sku_nama_produk}</td>
											<td class="text-left">${v.sku_stock_expired_date}</td>
											<td class="text-right">${v.sku_stock_qty}</td>
										</tr>
									`);
                                });

                                $("#data-table-sku").DataTable({
                                    lengthMenu: [
                                        [50, 100, 200, -1],
                                        [50, 100, 200, 'All'],
                                    ],
                                });
                            }
                        });
                    }
                }
            });

            Get_list_sku_bonus_tidak_ada_di_gudang_bonus();
        }
    }

    $("#btn_pencarian_mutasi_pallet_draft").click(
        function() {
            GetPencarianMutasiStokDraftTable();
        }
    );

    $("#btn_pilih_mutasi_sku_draft").click(
        function() {
            GetSKU();
        }
    );

    $("#btn_save_mutasi_draft").click(function() {
        cek_error = 0;

        if (arr_list_multi_sku.length == 0) {

            let alert = "List SKU Tidak Boleh Kosong";
            message_custom("Error", "error", alert);

            return false;
        }

        $.each(arr_list_multi_sku, function(i, v) {
            if (parseInt(v.sku_stock_qty) == 0) {
                let alert = "SKU Stock Tidak Boleh 0";
                message_custom("Error", "error", alert);

                cek_error++;

                return false;
            }

            if (parseInt(v.sku_stock_qty) > parseInt(v.sku_stock_qty_awal)) {

                let alert = "Qty Mutasi Tidak Boleh Lebih Besar Dari SKU Stock Pallet";
                message_custom("Error", "error", alert);

                cek_error++;

                return false;
            }

            if (v.pallet_id_asal == 0) {
                let alert = "Pallet Asal Tidak Boleh Kosong";
                message_custom("Error", "error", alert);

                cek_error++;

                return false;
            }

        });

        setTimeout(() => {

            // console.log(arr_list_faktur_klaim);

            if ($("#mutasi_draft_perusahaan").val() == "") {

                let alert = "Perusahaan Tidak Boleh Kosong";
                message_custom("Error", "error", alert);

                return false;
            }

            if ($("#mutasi_draft_principle").val() == "") {

                let alert = "Principle Tidak Boleh Kosong";
                message_custom("Error", "error", alert);

                return false;
            }

            if ($("#gudang_asal_mutasi_draft").val() == "") {

                let alert = "Gudang Asal Tidak Boleh Kosong";
                message_custom("Error", "error", alert);

                return false;
            }

            if ($("#Pmutasi_draft_checker").val() == "") {

                let alert = "Checker Tidak Boleh Kosong";
                message_custom("Error", "error", alert);

                return false;
            }

            if (cek_error == 0) {

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Pastikan data yang sudah anda input benar!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak"
                }).then((result) => {
                    if (result.value == true) {

                        // ResetForm();

                        $.ajax({
                            async: false,
                            url: "<?= base_url('WMS/MutasiStokDraft/insert_tr_mutasi_stok'); ?>",
                            type: "POST",
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Loading ...',
                                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                                    timerProgressBar: false,
                                    showConfirmButton: false
                                });

                                $("#btn_save_mutasi_draft").prop("disabled", true);
                            },
                            data: {
                                tr_mutasi_stok_id: "",
                                client_wms_id: $('#mutasi_draft_perusahaan').val(),
                                principle_id: $('#mutasi_draft_principle').val(),
                                tr_mutasi_stok_kode: "",
                                tr_mutasi_stok_tanggal: $('#mutasi_draft_tanggal').val(),
                                tr_mutasi_stok_keterangan: $('#mutasi_draft_keterangan').val(),
                                tr_mutasi_stok_status: $('#mutasi_draft_status').val(),
                                depo_id_asal: "<?= $this->session->userdata('depo_id'); ?>",
                                depo_detail_id_asal: $('#gudang_asal_mutasi_draft').val(),
                                tr_mutasi_stok_tgl_create: "",
                                tr_mutasi_stok_who_create: "",
                                tr_mutasi_stok_nama_checker: $('#mutasi_draft_checker').val(),
                                tr_mutasi_stok_tgl_update: "",
                                tr_mutasi_stokt_who_update: "",
                                detail: arr_list_multi_sku
                            },
                            dataType: "JSON",
                            success: function(response) {

                                if (response == 1) {

                                    var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
                                    // var alert = "Data Berhasil Disimpan";
                                    message_custom("Success", "success", alert);
                                    setTimeout(() => {
                                        location.href = "<?= base_url() ?>WMS/MutasiStokDraft/MutasiStokDraftMenu";
                                    }, 500);

                                    ResetForm();
                                } else if (response == 500) {
                                    var msg = "Tanggal dokumen tidak boleh kurang dari atau sama dengan tanggal tutup gudang";
                                    message_custom("Error", "error", alert);
                                } else if (response == "2") {

                                    var msg = "Mutasi Stock sudah ada";
                                    message_custom("Error", "error", alert);
                                } else {
                                    // var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
                                    var alert = response.data.message;
                                    message_custom("Error", "error", alert);
                                }

                                $("#btn_save_mutasi_draft").prop("disabled", false);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                message("Error", "Error 500 Internal Server Connection Failure", "error");

                                $("#btn_save_mutasi_draft").prop("disabled", false);
                            },
                            complete: function() {
                                // Swal.close();
                                $("#btn_save_mutasi_draft").prop("disabled", false);
                            }
                        });
                    }
                });

            }

        }, 1000);
    });

    $("#btn_update_mutasi_draft").click(function() {
        cek_error = 0;

        if (arr_list_multi_sku.length == 0) {

            let alert = "List SKU Tidak Boleh Kosong";
            message_custom("Error", "error", alert);

            return false;
        }

        $.each(arr_list_multi_sku, function(i, v) {

            if (parseInt(v.sku_stock_qty) == 0) {
                let alert = "SKU Stock Tidak Boleh 0";
                message_custom("Error", "error", alert);

                cek_error++;

                return false;
            }

            if (parseInt(v.sku_stock_qty) > parseInt(v.sku_stock_qty_awal)) {

                let alert = "Qty Mutasi Tidak Boleh Lebih Besar Dari SKU Stock Pallet";
                message_custom("Error", "error", alert);

                cek_error++;

                return false;
            }

            if (v.pallet_id_asal == 0) {
                let alert = "Pallet Asal Tidak Boleh Kosong";
                message_custom("Error", "error", alert);

                cek_error++;

                return false;
            }

        });

        setTimeout(() => {

            // console.log(arr_list_faktur_klaim);

            if ($("#mutasi_draft_perusahaan").val() == "") {

                let alert = "Perusahaan Tidak Boleh Kosong";
                message_custom("Error", "error", alert);

                return false;
            }

            if ($("#mutasi_draft_principle").val() == "") {

                let alert = "Principle Tidak Boleh Kosong";
                message_custom("Error", "error", alert);

                return false;
            }

            if ($("#gudang_asal_mutasi_draft").val() == "") {

                let alert = "Gudang Asal Tidak Boleh Kosong";
                message_custom("Error", "error", alert);

                return false;
            }

            if ($("#Pmutasi_draft_checker").val() == "") {

                let alert = "Checker Tidak Boleh Kosong";
                message_custom("Error", "error", alert);

                return false;
            }

            if (cek_error == 0) {

                Swal.fire({
                    title: "Apakah anda yakin?",
                    text: "Pastikan data yang sudah anda input benar!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya",
                    cancelButtonText: "Tidak"
                }).then((result) => {
                    if (result.value == true) {

                        // ResetForm();

                        $.ajax({
                            async: false,
                            url: "<?= base_url('WMS/MutasiStokDraft/update_tr_mutasi_stok'); ?>",
                            type: "POST",
                            beforeSend: function() {
                                Swal.fire({
                                    title: 'Loading ...',
                                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                                    timerProgressBar: false,
                                    showConfirmButton: false
                                });

                                $("#btn_save_mutasi_draft").prop("disabled", true);
                            },
                            data: {
                                tr_mutasi_stok_id: $('#tr_mutasi_stok_id').val(),
                                client_wms_id: $('#mutasi_draft_perusahaan').val(),
                                principle_id: $('#mutasi_draft_principle').val(),
                                tr_mutasi_stok_kode: $('#mutasi_draft_no_draft').val(),
                                tr_mutasi_stok_tanggal: $('#mutasi_draft_tanggal').val(),
                                tr_mutasi_stok_keterangan: $('#mutasi_draft_keterangan').val(),
                                tr_mutasi_stok_status: $('#mutasi_draft_status').val(),
                                depo_id_asal: "<?= $this->session->userdata('depo_id'); ?>",
                                depo_detail_id_asal: $('#gudang_asal_mutasi_draft').val(),
                                tr_mutasi_stok_tgl_create: "",
                                tr_mutasi_stok_who_create: "",
                                tr_mutasi_stok_nama_checker: $('#mutasi_draft_checker').val(),
                                tr_mutasi_stok_tgl_update: $('#tr_mutasi_stok_tgl_update').val(),
                                tr_mutasi_stokt_who_update: $('#tr_mutasi_stokt_who_update').val(),
                                detail: arr_list_multi_sku
                            },
                            dataType: "JSON",
                            success: function(response) {

                                if (response == 1) {

                                    var alert = GetLanguageByKode('CAPTION-ALERT-DATABERHASILDISIMPAN');
                                    // var alert = "Data Berhasil Disimpan";
                                    message_custom("Success", "success", alert);
                                    setTimeout(() => {
                                        location.href = "<?= base_url() ?>WMS/MutasiStokDraft/MutasiStokDraftMenu";
                                    }, 500);

                                    ResetForm();
                                } else if (response == 500) {
                                    var msg = "Tanggal dokumen tidak boleh kurang dari atau sama dengan tanggal tutup gudang";
                                    message_custom("Error", "error", alert);
                                } else if (response == "2") {

                                    var msg = "Mutasi Stock sudah ada";
                                    message_custom("Error", "error", alert);
                                } else {
                                    // var alert = GetLanguageByKode('CAPTION-ALERT-DATAGAGALDISIMPAN');
                                    var alert = response.data.message;
                                    message_custom("Error", "error", alert);
                                }

                                $("#btn_save_mutasi_draft").prop("disabled", false);
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                message("Error", "Error 500 Internal Server Connection Failure", "error");

                                $("#btn_save_mutasi_draft").prop("disabled", false);
                            },
                            complete: function() {
                                // Swal.close();
                                $("#btn_save_mutasi_draft").prop("disabled", false);
                            }
                        });
                    }
                });

            }

        }, 1000);
    });

    function PushArraySKU(idx, sku_id, sku_stock_id, sku_stock_qty, sku_stock_expired_date, sku_stock_qty_awal) {
        $("#select-all-sku").prop("checked", false);

        if ($('[id="item-' + idx + '-sku_stock_id"]:checked').length > 0) {
            arr_list_multi_sku.push({
                'sku_id': sku_id,
                'sku_stock_id': sku_stock_id,
                'sku_stock_qty': 0,
                'sku_stock_expired_date': sku_stock_expired_date,
                'pallet_id_asal': "",
                'sku_stock_qty_awal': sku_stock_qty_awal
            });

            const uniqueArray = [];
            const seenIds = {};

            for (const obj of arr_list_multi_sku) {
                if (!seenIds[obj.sku_stock_id]) {
                    seenIds[obj.sku_stock_id] = true;
                    uniqueArray.push(obj);
                }
            }
            arr_list_multi_sku = uniqueArray;
        } else {
            const findIndexData = arr_list_multi_sku.findIndex((value) => value.sku_stock_id == sku_stock_id);
            if (findIndexData > -1) { // only splice array when item is found
                arr_list_multi_sku.splice(findIndexData, 1); // 2nd parameter means remove one item only
            }
        }
    }

    $("#btn-choose-sku-multi").click(function() {
        $("#modal-sku").hide();
        Get_list_mutasi_stock_detail();
    });

    function Get_list_mutasi_stock_detail() {
        let tr_mutasi_stok_id = "";

        <?php if (isset($act)) { ?>
            <?php if ($act == "edit") { ?>
                tr_mutasi_stok_id = "<?= $tr_mutasi_stok_id; ?>"
            <?php } ?>
            <?php if ($act == "detail") { ?>
                tr_mutasi_stok_id = "<?= $tr_mutasi_stok_id; ?>"
            <?php } ?>
        <?php } ?>

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MutasiStokDraft/Get_list_mutasi_stock_detail') ?>",
            data: {
                tr_mutasi_stok_id: tr_mutasi_stok_id,
                arr_list_multi_sku: arr_list_multi_sku
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false
                });
            },
            dataType: "JSON",
            success: function(response) {

                $('#table_mutasi_draft').fadeOut("slow", function() {
                    $(this).hide();

                    $('#table_mutasi_draft > tbody').empty('');

                    if ($.fn.DataTable.isDataTable('#table_mutasi_draft')) {
                        $('#table_mutasi_draft').DataTable().clear();
                        $('#table_mutasi_draft').DataTable().destroy();
                    }

                }).fadeIn("slow", function() {
                    $(this).show();

                    if (response.length != 0) {

                        $.each(response, function(i, v) {
                            $("#table_mutasi_draft > tbody").append(`
										<tr>
											<td class="text-center" style="width:5%;">
												${i+1}
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_id" value="${v.sku_stock_id}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_id" value="${v.sku_id}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_expired_date" value="${v.sku_stock_expired_date}">
												<input type="hidden" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_qty_awal" value="${v.sku_stock_qty_awal}">
											</td>
											<td class="text-left" style="width:10%;">${v.sku_kode}</td>
											<td class="text-left" style="width:20%;">${v.sku_nama_produk}</td>
											<td class="text-left" style="width:10%;">${v.sku_satuan}</td>
											<td class="text-left" style="width:15%;">${v.sku_stock_expired_date}</td>
											<td class="text-center" style="width:25%;">
												<select class="form-control select2_detail" style="width:100%;" id="item-${i}-tr_mutasi_stok_detail-pallet_id_asal" onchange="UpdateListSKU('${v.sku_stock_id}', '${i}','select')" <?= $act == 'detail' ? 'disabled' : '' ?>>
													<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>
												</select>
											</td>
											<td class="text-center" style="width:15%;">
												<input type="number" class="form-control" id="item-${i}-tr_mutasi_stok_detail-sku_stock_qty" value="${v.sku_stock_qty}" onchange="UpdateListSKU('${v.sku_stock_id}', '${i}','text')" <?= $act == 'detail' ? 'disabled' : '' ?>>
											</td>
											<td class="text-center" style="width:5%;">
												<button type="button" class="btn btn-danger btn-sm" style="<?= $act == 'detail' ? 'display:none' : '' ?>" onClick="DeleteListSKU('${v.sku_stock_id}','${i}')"><i class="fa fa-trash"></i></button>
											</td>
										</tr>
							`);
                        });

                        $.each(response, function(i, v) {

                            $("#item-" + i + "-tr_mutasi_stok_detail-pallet_id_asal").html('');

                            $("#item-" + i + "-tr_mutasi_stok_detail-pallet_id_asal").append(`<option value="">** <span name="CAPTION-PILIH">Pilih</span> **</option>`);

                            $.ajax({
                                type: 'POST',
                                url: "<?= base_url('WMS/MutasiStokDraft/Get_pallet_by_sku_stock_id') ?>",
                                data: {
                                    sku_stock_id: v.sku_stock_id
                                },
                                dataType: "JSON",
                                success: function(response2) {
                                    $.each(response2, function(i2, v2) {
                                        $("#item-" + i + "-tr_mutasi_stok_detail-pallet_id_asal").append(`<option value="${v2.pallet_id}" ${v2.pallet_id == v.pallet_id_asal ? 'selected' : '' }>${v2.pallet_kode} || ${v2.sku_stock_qty} ${v2.sku_satuan}</option>`);
                                    });
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    message("Error", "Error 500 Internal Server Connection Failure", "error");
                                },
                                complete: function() {
                                    Swal.close();
                                }
                            });
                        });

                        $(".select2_detail").select2();

                        $("#table_mutasi_draft").DataTable({
                            lengthMenu: [
                                [50, 100, 200, -1],
                                [50, 100, 200, 'All'],
                            ],
                        });
                    }
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

    function Get_list_sku_bonus_tidak_ada_di_gudang_bonus() {

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MutasiStokDraft/Get_list_sku_bonus_tidak_ada_di_gudang_bonus') ?>",
            data: {
                tgl: $("#mutasi_draft_tanggal_rencana_kirim").val(),
                tipe_do: $("#mutasi_draft_tipe_do").val(),
                principle: $("#mutasi_draft_principle").val(),
                perusahaan: $("#mutasi_draft_perusahaan").val()
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false
                });
            },
            dataType: "JSON",
            success: function(response) {

                $('#table_list_sku_bonus_tidak_ada_di_gudang_bonus').fadeOut("slow", function() {
                    $(this).hide();

                    $('#table_list_sku_bonus_tidak_ada_di_gudang_bonus > tbody').empty('');

                    if ($.fn.DataTable.isDataTable('#table_list_sku_bonus_tidak_ada_di_gudang_bonus')) {
                        $('#table_list_sku_bonus_tidak_ada_di_gudang_bonus').DataTable().clear();
                        $('#table_list_sku_bonus_tidak_ada_di_gudang_bonus').DataTable().destroy();
                    }

                }).fadeIn("slow", function() {
                    $(this).show();

                    if (response.length != 0) {

                        $.each(response, function(i, v) {
                            $("#table_list_sku_bonus_tidak_ada_di_gudang_bonus > tbody").append(`
								<tr>
									<td class="text-center" style="width:5%;">${i+1}</td>
									<td class="text-left" style="width:10%;">${v.sku_kode}</td>
									<td class="text-left" style="width:10%;">${v.sku_nama_produk}</td>
									<td class="text-left" style="width:10%;">${v.sku_satuan}</td>
                                    <td class="text-center" style="width:5%;">
                                        <button class="btn btn-primary" id="btn_detail_gudang_asal" onclick="Get_detail_gudang_asal('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}', '${v.sku_qty}', '${v.sku_stock_qty}')"><i class="fa fa-eye"></i></button>
                                    </td>
									<td class="text-right" style="width:10%;">${v.sku_qty}</td>
									<td class="text-right" style="width:10%;">${v.sku_stock_qty}</td>
									<td class="text-center" style="width:5%;">
                                        <button class="btn btn-primary" id="btn_detail_sku_bonus" onclick="Get_detail_sku_bonus_tidak_ada_di_gudang_bonus('${v.sku_id}', '${v.sku_kode}', '${v.sku_nama_produk}', '${v.sku_qty}', '${v.sku_stock_qty}')"><i class="fa fa-eye"></i></button>
                                    </td>
								</tr>
							`);
                        });

                        $("#table_list_sku_bonus_tidak_ada_di_gudang_bonus").DataTable({
                            lengthMenu: [
                                [50, 100, 200, -1],
                                [50, 100, 200, 'All'],
                            ],
                        });
                    }
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

    function Get_detail_sku_bonus_tidak_ada_di_gudang_bonus(sku_id, sku_kode, sku_nama_produk, sku_qty, sku_stock_qty) {

        $("#modal-detail-sku-bonus").modal('show');

        $("#SKUBonus-sku_id").val(sku_id);
        $("#SKUBonus-sku_kode").val(sku_kode);
        $("#SKUBonus-sku_nama_produk").val(sku_nama_produk);
        $("#SKUBonus-sku_qty").val(sku_qty);
        $("#SKUBonus-sku_stock_qty").val(sku_stock_qty);

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MutasiStokDraft/Get_detail_sku_bonus_tidak_ada_di_gudang_bonus') ?>",
            data: {
                tgl: $("#mutasi_draft_tanggal_rencana_kirim").val(),
                tipe_do: $("#mutasi_draft_tipe_do").val(),
                principle: $("#mutasi_draft_principle").val(),
                perusahaan: $("#mutasi_draft_perusahaan").val(),
                sku_id: sku_id
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false
                });
            },
            dataType: "JSON",
            success: function(response) {

                $('#table_detail_sku_bonus_tidak_ada_di_gudang_bonus').fadeOut("slow", function() {
                    $(this).hide();

                    $('#table_detail_sku_bonus_tidak_ada_di_gudang_bonus > tbody').empty('');

                    if ($.fn.DataTable.isDataTable('#table_detail_sku_bonus_tidak_ada_di_gudang_bonus')) {
                        $('#table_detail_sku_bonus_tidak_ada_di_gudang_bonus').DataTable().clear();
                        $('#table_detail_sku_bonus_tidak_ada_di_gudang_bonus').DataTable().destroy();
                    }

                }).fadeIn("slow", function() {
                    $(this).show();

                    if (response.length != 0) {

                        $.each(response, function(i, v) {
                            $("#table_detail_sku_bonus_tidak_ada_di_gudang_bonus > tbody").append(`
								<tr>
									<td class="text-center" style="width:5%;">${i+1}</td>
									<td class="text-left" style="width:10%;">${v.delivery_order_draft_kode}</td>
									<td class="text-left" style="width:10%;">${v.sales_order_kode}</td>
									<td class="text-left" style="width:10%;">${v.sales_order_no_po}</td>
									<td class="text-left" style="width:10%;">${v.delivery_order_draft_tgl_buat_do}</td>
									<td class="text-left" style="width:10%;">${v.delivery_order_draft_tgl_rencana_kirim}</td>
									<td class="text-left" style="width:10%;">${v.delivery_order_draft_kirim_nama}</td>
									<td class="text-right" style="width:10%;">${v.sku_qty}</td>
								</tr>
							`);
                        });

                        $("#table_detail_sku_bonus_tidak_ada_di_gudang_bonus").DataTable({
                            lengthMenu: [
                                [50, 100, 200, -1],
                                [50, 100, 200, 'All'],
                            ],
                        });
                    }
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

    function Get_detail_gudang_asal(sku_id, sku_kode, sku_nama_produk, sku_qty, sku_stock_qty) {

        $("#modal-detail-gudang-asal").modal('show');

        $("#GudangAsal-sku_id").val(sku_id);
        $("#GudangAsal-sku_kode").val(sku_kode);
        $("#GudangAsal-sku_nama_produk").val(sku_nama_produk);
        $("#GudangAsal-sku_qty").val(sku_qty);
        $("#GudangAsal-sku_stock_qty").val(sku_stock_qty);

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MutasiStokDraft/Get_detail_gudang_asal_by_sku') ?>",
            data: {
                tgl: $("#mutasi_draft_tanggal_rencana_kirim").val(),
                tipe_do: $("#mutasi_draft_tipe_do").val(),
                principle: $("#mutasi_draft_principle").val(),
                perusahaan: $("#mutasi_draft_perusahaan").val(),
                sku_id: sku_id
            },
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false
                });
            },
            dataType: "JSON",
            success: function(response) {

                $('#table_detail_gudang_asal').fadeOut("slow", function() {
                    $(this).hide();

                    $('#table_detail_gudang_asal > tbody').empty('');

                    if ($.fn.DataTable.isDataTable('#table_detail_gudang_asal')) {
                        $('#table_detail_gudang_asal').DataTable().clear();
                        $('#table_detail_gudang_asal').DataTable().destroy();
                    }

                }).fadeIn("slow", function() {
                    $(this).show();

                    if (response.length != 0) {

                        $.each(response, function(i, v) {
                            $("#table_detail_gudang_asal > tbody").append(`
								<tr>
									<td class="text-center" style="width:5%;">${i+1}</td>
									<td class="text-left" style="width:40%;">${v.depo_detail_nama}</td>
									<td class="text-left" style="width:40%;">${v.sku_stock_expired_date}</td>
									<td class="text-right" style="width:15%;">${v.sku_stock_qty}</td>
								</tr>
							`);
                        });

                        $("#table_detail_gudang_asal").DataTable({
                            lengthMenu: [
                                [50, 100, 200, -1],
                                [50, 100, 200, 'All'],
                            ],
                        });
                    }
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

    $("#btnback").click(
        function() {
            ResetForm();
            GetMutasiStokDraftMenu();
        }
    );

    $("#btn-back-detail-pallet").click(
        function() {
            $('#modal-pallet-detail').modal('hide');
            $('#modal-pallet').modal('show');
        }
    );

    $("#btn-back-detail-pallet-2").click(
        function() {
            $('#modal-pallet-detail-2').modal('hide');
        }
    );

    $("#btn_history_approval").click(
        function() {
            var dokumen_id = $("#mutasi_pallet_draft_id").val();
            $('#modalHistoryApproval').modal('show');

            $.ajax({
                type: 'POST',
                url: "<?= base_url('WMS/Approval/getApprovalByDocumentId') ?>",
                data: {
                    dokumen_id: dokumen_id
                },
                success: function(response) {

                    let no = 1;
                    let data = response;

                    if ($.fn.DataTable.isDataTable('#tableHistoryApproval')) {
                        $('#tableHistoryApproval').DataTable().destroy();
                    }

                    $("#tableHistoryApproval tbody").empty();
                    $("#tableHistoryApproval tbody").html('');
                    if (data.length > 0) {
                        $.each(data, function() {
                            if (this.approval_status == "Approved") {
                                var color = "style='background-color:green;color:white'"
                            } else {
                                var color = "style='background-color:red;color:white'"
                            }
                            $("#txt_jenis_pengajuan").val(`${this.approval_reff_dokumen_jenis}`);

                            $('#tableHistoryApproval tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; text-align: center; ' >${no}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.tgl}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.approval_reff_dokumen_kode}</td>
                                    <td style='vertical-align:middle; text-align: center;' ><a ${color} class="btn btn-md">${this.approval_status}<a/></td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.karyawan_nama}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.approval_keterangan}</td>
                                </tr>
                            `);
                            no++;
                        });
                    } else {
                        $("#tableHistoryApproval tbody").html('');
                    }

                    $('#tableHistoryApproval').DataTable({
                        paging: false
                    });

                }
            });
        }
    );

    function DeletePallet(row) {
        var jml_sku = $("#txt_jml_sku").val() - 1;

        var row = row.parentNode.parentNode;
        row.parentNode.removeChild(row);

        $("#txt_jml_sku").val(jml_sku);
    }

    function ViewPallet() {
        $("#table_mutasi_draft > tbody").empty();
        $("#txt_jml_sku").val(0);

        if ($("#mutasi_draft_tipe_transaksi").val() != "") {
            GetGudangTujuan($("#mutasi_draft_tipe_transaksi").val());
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Pilih Transaksi!'
            });
        }
    }

    function ViewDetailPallet(pallet_id) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MutasiStokDraft/GetPalletDetail') ?>",
            data: {
                pallet_id: pallet_id
            },
            success: function(response) {
                if (response) {
                    $('#modal-pallet-detail').modal('show');
                    $('#modal-pallet').modal('hide');

                    ChPalletDetail(response);
                }
            }
        });

    }

    function ViewDetailPallet2(pallet_id) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MutasiStokDraft/GetPalletDetail') ?>",
            data: {
                pallet_id: pallet_id
            },
            success: function(response) {
                if (response) {
                    $('#modal-pallet-detail-2').modal('show');

                    ChPalletDetail(response);
                }
            }
        });

    }

    function ChPalletDetail(JSONMutasiPallet) {
        $("#data-table-pallet-detail > tbody").html('');

        var MutasiPallet = JSON.parse(JSONMutasiPallet);
        var no = 1;

        var pallet_id = MutasiPallet.PalletDetail[0].pallet_id;
        var pallet_kode = MutasiPallet.PalletDetail[0].pallet_kode;
        var pallet_jenis_id = MutasiPallet.PalletDetail[0].pallet_jenis_id;
        var pallet_jenis_nama = MutasiPallet.PalletDetail[0].pallet_jenis_nama;

        $("#pallet_detail_pallet_kode").val(pallet_kode);
        $("#pallet_detail_jenis_pallet").val(pallet_jenis_nama);
        $("#pallet_detail_pallet_kode2").val(pallet_kode);
        $("#pallet_detail_jenis_pallet2").val(pallet_jenis_nama);

        if (MutasiPallet.Pallet != 0) {
            if ($.fn.DataTable.isDataTable('#data-table-pallet-detail')) {
                $('#data-table-pallet-detail').DataTable().destroy();
            }

            $('#data-table-pallet-detail tbody').empty();

            for (i = 0; i < MutasiPallet.PalletDetail.length; i++) {
                var pallet_id = MutasiPallet.PalletDetail[i].pallet_id;
                var pallet_kode = MutasiPallet.PalletDetail[i].pallet_kode;
                var pallet_jenis_id = MutasiPallet.PalletDetail[i].pallet_jenis_id;
                var pallet_jenis_nama = MutasiPallet.PalletDetail[i].pallet_jenis_nama;
                var sku_id = MutasiPallet.PalletDetail[i].sku_id;
                var sku_kode = MutasiPallet.PalletDetail[i].sku_kode;
                var principle_kode = MutasiPallet.PalletDetail[i].principle_kode;
                var sku_nama_produk = MutasiPallet.PalletDetail[i].sku_nama_produk;
                var sku_kemasan = MutasiPallet.PalletDetail[i].sku_kemasan;
                var sku_satuan = MutasiPallet.PalletDetail[i].sku_satuan;
                var sku_stock_expired_date = MutasiPallet.PalletDetail[i].sku_stock_expired_date;
                var penerimaan_tipe_nama = MutasiPallet.PalletDetail[i].penerimaan_tipe_nama;
                var sku_stock_qty = MutasiPallet.PalletDetail[i].sku_stock_qty;

                var strmenu = '';

                strmenu = strmenu + '<tr>';
                strmenu = strmenu + '	<td class="text-center">' + no + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + principle_kode + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + sku_kode + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + sku_nama_produk + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + sku_kemasan + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + sku_satuan + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + sku_stock_expired_date + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + penerimaan_tipe_nama + '</td>';
                strmenu = strmenu + '	<td class="text-center">' + sku_stock_qty + '</td>';
                strmenu = strmenu + '</tr>';
                no++;

                $("#data-table-pallet-detail > tbody").append(strmenu);
            }

            $("#loadingview").hide();

            $('#data-table-pallet-detail').DataTable({
                "lengthMenu": [
                    [-1],
                    ["All"]
                ],
                "ordering": false,
                "bInfo": false,
                "paging": false
            });
        }
    }

    function GetCheckerPrinciple(principle) {

        $("#mutasi_draft_checker").html('');
        $("#table_mutasi_draft > tbody").empty();
        $("#txt_jml_sku").val(0);

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MutasiStokDraft/GetCheckerPrinciple') ?>",
            data: {
                principle: principle,
                client_wms_id: $("#mutasi_draft_perusahaan").val()
            },
            success: function(response) {
                let data = response;

                $("#mutasi_draft_checker").html('');
                if (data.length > 0) {
                    $.each(data, function() {
                        $("#mutasi_draft_checker").append(`<option value="${this.karyawan_id} || ${this.karyawan_nama}">${this.karyawan_nama}</option>`);
                    });
                } else {
                    $("#mutasi_draft_checker").html('');
                }
            }
        });

        Get_list_sku_bonus_tidak_ada_di_gudang_bonus();

    }

    function GetGudangTujuan(tipe_mutasi) {

        $("#gudang_tujuan_mutasi_draft").html('');

        if (tipe_mutasi == "C02CC613-2FAB-49A9-9372-B27D9444FE93") {

            $.ajax({
                type: 'POST',
                url: "<?= base_url('WMS/MutasiStokDraft/GetGudangTujuanByTipe') ?>",
                data: {
                    gudang_asal: $("#gudang_asal_mutasi_draft").val()
                },
                success: function(response) {
                    let data = response;

                    $("#gudang_tujuan_mutasi_draft").html('');
                    if (data.length > 0) {
                        $("#gudang_tujuan_mutasi_draft").append(`<option value="">** Pilih Gudang Tujuan **</option>`);
                        $.each(data, function() {
                            $("#gudang_tujuan_mutasi_draft").append(`<option value="${this.depo_detail_id}">${this.depo_detail_nama}</option>`);
                        });
                    } else {
                        $("#gudang_tujuan_mutasi_draft").html('');
                    }
                }
            });
        } else {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('WMS/MutasiStokDraft/GetGudangTujuan') ?>",
                data: {
                    gudangAsal: $("#gudang_asal_mutasi_draft").val()
                },
                success: function(response) {
                    let data = response;

                    $("#gudang_tujuan_mutasi_draft").html('');
                    if (data.length > 0) {
                        $("#gudang_tujuan_mutasi_draft").append(`<option value="">** Pilih Gudang Tujuan **</option>`);
                        $.each(data, function() {
                            $("#gudang_tujuan_mutasi_draft").append(`<option value="${this.depo_detail_id}">${this.depo_detail_nama}</option>`);
                        });
                    } else {
                        $("#gudang_tujuan_mutasi_draft").html('');
                    }
                }
            });
        }
    }

    function UpdateListSKU(sku_stock_id, index, tipe) {

        const findIndexData = arr_list_multi_sku.findIndex((value) => value.sku_stock_id == sku_stock_id);
        let slc_pallet_asal = $("#item-" + index + "-tr_mutasi_stok_detail-pallet_id_asal option:selected").text();
        let pallet_asal = slc_pallet_asal.split(" || ");
        let sku_stock_qty = parseInt(pallet_asal[1].replace(/^\D+/g, ''));

        if (tipe == "select") {

            // $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty').val(0);
            $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty_awal').val(sku_stock_qty);

            arr_list_multi_sku[findIndexData] = ({
                'sku_stock_id': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_id').val(),
                'sku_id': $('#item-' + index + '-tr_mutasi_stok_detail-sku_id').val(),
                'sku_stock_qty': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty').val(),
                'sku_stock_expired_date': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_expired_date').val(),
                'pallet_id_asal': $('#item-' + index + '-tr_mutasi_stok_detail-pallet_id_asal').val(),
                'sku_stock_qty_awal': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty_awal').val()
            });

        } else {

            arr_list_multi_sku[findIndexData] = ({
                'sku_stock_id': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_id').val(),
                'sku_id': $('#item-' + index + '-tr_mutasi_stok_detail-sku_id').val(),
                'sku_stock_qty': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty').val(),
                'sku_stock_expired_date': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_expired_date').val(),
                'pallet_id_asal': $('#item-' + index + '-tr_mutasi_stok_detail-pallet_id_asal').val(),
                'sku_stock_qty_awal': $('#item-' + index + '-tr_mutasi_stok_detail-sku_stock_qty_awal').val()
            });

        }
    }

    function DeleteListSKU(sku_stock_id, index) {

        const findIndexData = arr_list_multi_sku.findIndex((value) => value.sku_stock_id == sku_stock_id);

        if (findIndexData > -1) { // only splice array when item is found
            arr_list_multi_sku.splice(findIndexData, 1); // 2nd parameter means remove one item only
        }

        // console.log(arr_list_multi_sku);

        Get_list_mutasi_stock_detail();

    }

    function ResetForm() {
        <?php
        if ($Menu_Access["U"] == 1) {
        ?>
            $("#txtupdateMutasiPalletnama").val('');
            $("#cbupdateMutasiPalletjenis").prop('selectedIndex', 0);
            $("#chupdateMutasiPalletsppbr").prop('checked', false);
            $("#chupdateMutasiPallettipeecomm").prop('checked', false);
            $("#chupdateMutasiPalletisactive").prop('checked', false);

            $("#loadingview").hide();
            $("#btnsaveupdatenewMutasiPallet").prop("disabled", false);
        <?php
        }
        ?>

        <?php
        if ($Menu_Access["C"] == 1) {
        ?>
            $("#txtMutasiPalletnama").val('');
            $("#cbMutasiPalletjenis").prop('selectedIndex', 0);
            $("#chMutasiPalletsppbr").prop('checked', false);
            $("#chMutasiPallettipeecomm").prop('checked', false);
            $("#chMutasiPalletisactive").prop('checked', false);

            $("#loadingview").hide();
            $("#btnsaveaddnewMutasiPallet").prop("disabled", false);
        <?php
        }
        ?>

    }
</script>