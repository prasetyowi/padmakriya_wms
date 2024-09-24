<script type="text/javascript">
    let global_data = [];
    let global_tipe_id = [];
    loadingBeforeReadyPage()
    $(document).ready(function() {
        select2();
        $(document).on("input", ".numeric", function(event) {
            this.value = this.value.replace(/[^\d.]+/g, '');
        });

        // $(document).on("change", ".file-upload-field", function(event) {
        //     $(this).parent(".file-upload-wrapper").attr("data-text", $(this).val().replace(/.*(\/|\\)/, ''));
        // });

        get_data_tipe_penerimaan();
        // get_data_surat_jalan();
        get_data_perusahaan();
        get_status();
        get_principle();
        // get_data_tipe_penerimaan2();

        function fixDiv() {
            var $cache = $('#form-tambah-penerimaan-modal');
            var $cache2 = $('#table_list_data_sku');
            let countData = $("#table_list_data_sku > tbody tr").length;
            if (countData > 5) {
                if ($('#form_batch_penerimaan .modal-body').scrollTop() > 30) {
                    $cache.css({
                        'position': 'fixed',
                        'top': '64px',
                        'left': '25px',
                        'width': '97%',
                        'z-index': 9999,
                        'box-shadow': 'rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px',
                        'background-color': 'white',
                        'padding': '20px'
                    });

                    $cache2.css({
                        'margin-top': '370px',
                    });
                } else {
                    $cache.css({
                        'position': 'relative',
                        'top': 'auto',
                        'width': '97%',
                        'left': '10px',
                        '-webkit-box-shadow': 'none',
                        '-moz-box-shadow': 'none',
                        'box-shadow': 'none',
                        'padding': '5px',
                        'margin-bottom': '20px'
                    });
                    $cache2.css({
                        'margin-top': '0px',
                    });

                }
            }

        }
        $('#form_batch_penerimaan .modal-body').scroll(fixDiv);
        fixDiv();
    });

    $("#viewSL").on('click', function() {
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/PenerimaanSuratJalan/getSecurityLogbook') ?>",
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {
                    $("#table_logbook_security > tbody").empty();
                    $.each(response, function(i, v) {
                        $("#table_logbook_security > tbody").append(`
							<tr>
								<td class="text-center">
									<input type="checkbox" class="form-control check-item chk-sj" name="chk-sj" style="transform: scale(1.5)" id="chk_sj" value="${v.security_logbook_id}"/>
								</td>
								<td class="text-center">${v.security_logbook_kode}</td>
								<td class="text-center">${v.security_logbook_nopol}</td>
								<td class="text-center">${v.security_logbook_nama_driver}</td>
								<td class="text-center">${v.principle_nama}</td>
								<td class="text-center">${v.security_logbook_tgl_masuk}</td>
								<td class="text-center">${v.security_logbook_tgl_keluar}</td>
								<td class="text-center">${v.total_sj}</td>
							</tr>
						`);
                    });
                    $("#table_logbook_security").DataTable();
                } else {
                    $("#table_logbook_security > tbody").empty();
                    $("#table_logbook_security > tbody").append(`
						<tr>
							<td colspan="8" class="text-center"><span class="text-danger">Data tidak ditemukan</span></td>
						</tr>
					`);
                    // $("#table_logbook_security").DataTable();
                }

                $("#view_logbook_security").modal('show');
            }
        })
    })

    $(document).on('change', '.chk-sj', function() {
        $('.chk-sj').not(this).prop('checked', false);
    });

    $("#pilihSJ").on('click', function() {
        var chk_sj = $("input[type='checkbox']:checked").val();

        if (typeof chk_sj === 'undefined') {
            message("Warning", "Harap pilih surat jalan dahulu!", "warning");
            return false;
        }

        $("#view_logbook_security").modal('hide');
        $("#surat_jalan").empty();

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/PenerimaanSuratJalan/generateSuratJalan') ?>",
            data: {
                chk_sj: chk_sj
            },
            dataType: "JSON",
            success: function(response) {
                $(response).each(function(i, v) {
                    $("#surat_jalan").append(`
					<div class="x_panel card_surat_jalan" style="border: 1px solid black;">
					<div class="x_title">
						<h3><strong>${v.no_surat_jalan_eksternal}</strong></h3>
						<div class="clearfix"></div>
					</div>
						<div class="container mt-2">
							<div class="row">
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label name="CAPTION-NOSURATJALAN">No. Surat Jalan</label>
												<input type="text" class="form-control" name="doc_batch[]" id="doc_batch"
													placeholder="auto generate" readonly />
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label name="CAPTION-TANGGAL">Tanggal</label>
												<input type="date" class="form-control input-date-start" name="tgl[]"
													id="tgl" placeholder="dd-mm-yyyy"
													value="<?php echo Date('Y-m-d') ?>" readonly>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label name="CAPTION-PERUSAHAAN">Perusahaan</label>
										<select class="form-control select2 perusahaan" name="perusahaan[]"
											id="perusahaan" disabled>
											<option value="${v.client_wms_id}" selected>${v.client_wms_nama}</option>
										</select>
									</div>
									<div class="form-group">
										<label name="CAPTION-PENYALURATAUPRINCIPLE">Penyalur / Principle</label>
										<select class="form-control select2 principle" name="principle[]" id="principle"
										 disabled>
											<option value="${v.principle_id}">${v.principle_nama}</option>	
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label name="CAPTION-TIPEPENERIMAAN">Tipe Penerimaan</label>
												<select class="form-control select2 tipe_penerimaan"
													name="tipe_penerimaan[]" id="tipe_penerimaan">
													
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label name="CAPTION-STATUS">Status</label>
												<select class="form-control select2 status" name="status[]" id="status"
													disabled>
													<option value="Open" selected>Open</option>	
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label name="CAPTION-FILEATTACHMENT">File Attachment</label>
										<input type="file" class="form-control upload up file" name="file[]" id="file-${i}"
											placeholder="upload attachment" onchange="previewFile2(${i})"
											accept="image/jpeg, image/jpg, image/png, image/gif, image/JPG, image/JPEG, image/GIF, application/pdf,.csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
										<div class="row" id="show-file-${i}" style="margin-top: 5px;"></div>
									</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label name="CAPTION-NOSURATJALANEKSTERNAL">No. Surat Jalan Eksternal</label>
                                                <input type="text" class="form-control no_surat_jalan" name="no_surat_jalan[]"
                                                    id="no_surat_jalan" placeholder="No. Surat Jalan Eksternal" value="${v.no_surat_jalan_eksternal}"
                                                disabled/>
                                            </div>
                                        </div>
                                          <div class="col-md-6">
                                            <div class="form-group">
                                                <label name="CAPTION-NOKENDARAAN">No. Kendaraan</label>
                                                <input type="text" class="form-control no_kendaraan" name="no_kendaraan[]"
                                                    id="no_kendaraan" placeholder="No. Kendaraan" value="${v.security_logbook_nopol}"
                                                disabled/>
                                            </div>
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
				`);
                    get_data_tipe_penerimaan();
                });
            }
        })
        $("#generate_logbook_security").modal('show');
    })

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, "").toString(),
            split = number_string.split(","),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan

        if (ribuan) {
            separator = sisa ? "." : "";
            rupiah += separator + ribuan.join(".");
        }
        rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;

        return prefix == undefined ? rupiah : rupiah ? rupiah : "";

    }

    function select2() {
        $(".select2").select2({
            width: "100%"
        });
    }

    function check_count_sku() {
        let total_sku = $("#table_list_data_sku > tbody tr").length;
        if (total_sku == 0) {
            //hapus readonly perusahaan dan principle
            $(".perusahaan").removeAttr("disabled");
            $(".principle").removeAttr("disabled");
        } else {
            $(".perusahaan").attr("disabled", "disabled");
            $(".principle").attr("disabled", "disabled");
        }
    }

    $(document).on("click", "#search_filter_data", function() {
        let tahun = $("#tahun_filter").val();
        let month = $("#bulan_filter").val();
        let perusahaan = $("#perusahaan_filter").val();
        let principle = $("#principle_filter").val();
        let tipe_penerimaan = $("#tipe_penerimaan_filter").val();
        let status = $("#status_filter").val();
        $("#loadingsearch").show();
        $.ajax({
            url: "<?= base_url('WMS/PenerimaanSuratJalan/get_data_surat_jalan') ?>",
            type: "POST",
            data: {
                tahun: tahun,
                bulan: month,
                perusahaan: perusahaan,
                principle: principle,
                tipe_penerimaan: tipe_penerimaan,
                status: status
            },
            async: false,
            dataType: "JSON",
            success: function(data) {
                $("#loadingsearch").hide();
                $("#list-data-form-search").show();
                let no = 1;
                if (data != null) {
                    if ($.fn.DataTable.isDataTable('#listdatasuratjalan')) {
                        $('#listdatasuratjalan').DataTable().destroy();
                    }
                    $('#listdatasuratjalan > tbody').empty();
                    $.each(data, function(i, v) {
                        let str = "";
                        if (v.status == "Open") {
                            str +=
                                "<a href='<?= base_url('WMS/PenerimaanSuratJalan/edit/') ?>" + v
                                .sj_id +
                                "' style='width: 40px;' class='btn btn-warning form-control'><i class='fa fa-pencil'></i></a>";

                            str +=
                                `<button type="button" class="btn btn-danger show_cancel_surat_jalan" data-id="${v.sj_id}" ><i class='fas fa-xmark'></i></button>`;
                        } else {
                            str +=
                                "<a href='<?= base_url('WMS/PenerimaanSuratJalan/view/') ?>" + v
                                .sj_id +
                                "' style='width: 40px;' class='btn btn-primary' data-id='" + v
                                .sj_id + "' data-kode='" + v.sj_kode +
                                "'><i class='fas fa-eye'> </i></a>";

                            str +=
                                "<a href='<?= base_url('WMS/PenerimaanSuratJalan/edit_reason/') ?>" +
                                v.sj_id +
                                "' style='width: 40px;' class='btn btn-warning' data-id='" + v
                                .sj_id + "' data-kode='" + v.sj_kode +
                                "'><i class='fas fa-edit'></i></a>";
                        }
                        $("#listdatasuratjalan > tbody").append(`
                            <tr>
                                <td class="text-center">${no++}</td>
                                <td class="text-center">${v.tgl}</td>
                                <td>${v.sj_kode}</td>
                                <td>${v.no_sj}</td>
                                <td>${v.pt}</td>
                                <td>${v.p_kode} - ${v.p_nama}</td>
                                <td class="text-center">${v.tipe}</td>
                                <td class="text-center">${v.keterangan}</td>
                                <td class="text-center">${v.status}</td>
                                <td class="text-center">${str}</td>
                            </tr>
                        `);
                    });
                } else {
                    $("#listdatasuratjalan > tbody").html(
                        `<tr><td colspan="10" class="text-center text-danger">Data Kosong</td></tr>`
                    );
                }

                $('#listdatasuratjalan').DataTable({
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, 'All']
                    ],
                });

            }
        });
    });

    $(document).on("click", ".show_cancel_surat_jalan", function() {
        let id = $(this).attr('data-id')
        $("#list_reason_surat_jalan").modal('show');
        $(".save_cancel_surat_jalan").attr('data-id', id);

    });

    const save_cancel_surat_jalan = (event) => {
        const id = event.currentTarget.getAttribute('data-id');
        const reason = $("#reason_cancel").val();
        if (reason == "") {
            message('Error!', 'Reason tidak boleh kosong', 'error');
        } else {
            $.ajax({
                url: "<?php echo base_url('WMS/PenerimaanSuratJalan/cancel_surat_jalan'); ?>",
                type: "POST",
                data: {
                    id: id,
                    reason: reason
                },
                dataType: "JSON",
                success: function(response) {
                    console.log(response);
                    if (response == 1) {
                        message_topright("success", "Data berhasil dibatalkan");
                        setTimeout(() => {
                            location.reload();
                        }, 500);
                    } else {
                        message_topright("error", "Data gagal dibatalkan");
                    }
                }
            });
        }
    };

    const close_show_cancel_surat_jalan = () => {
        $("#list_reason_surat_jalan").modal('hide');
    }

    $(document).on("click", ".btndetailsuratjalan", function() {
        let id = $(this).attr("data-id");
        let sj_kode = $(this).attr("data-kode");
        $("#list_data_detail_surat_jalan").modal("show");
        $.ajax({
            url: "<?php echo base_url('WMS/PenerimaanSuratJalan/get_data_detail_surat_jalan'); ?>",
            type: "POST",
            data: {
                id: id
            },
            dataType: "JSON",
            success: function(data) {
                $("#list_data_detail_surat_jalan .modal-title").html(
                    "<label>Detail Data Surat Jalan Kode " + sj_kode + "</label>");
                let no = 1;
                if (data != null) {
                    if ($.fn.DataTable.isDataTable('#table_list_data_detail_surat_jalan')) {
                        $('#table_list_data_detail_surat_jalan').DataTable().destroy();
                    }
                    $('#table_list_data_detail_surat_jalan > tbody').empty();
                    $.each(data, function(i, v) {
                        // let diskon = v.sjd_diskon == 0 ? 0 : Math.round(v.sjd_diskon);
                        // let value_diskon = v.sjd_value_diskon == 0 ? 0 : Math.round(v.sjd_value_diskon);
                        // let harga = v.sjd_harga == 0 ? 0 : Math.round(v.sjd_harga);
                        // let harga_total = v.sjd_harga_total == 0 ? 0 : Math.round(v.sjd_harga_total);
                        $("#table_list_data_detail_surat_jalan > tbody").append(`
                            <tr>
                                <td>${no++}</td>
                                <td>${v.sku_kode}</td>
                                <td>${v.sku_nama_produk}</td>
                                <td>${v.sku_kemasan}</td>
                                <td>${v.sku_satuan}</td>
                                <td>${v.tipe}</td>
                                <td>${v.sjd_jumlah_barang}</td>
                            </tr>
                        `);
                    });
                } else {
                    $("#table_list_data_detail_surat_jalan > tbody").html(
                        `<tr><td colspan="7" class="text-center text-danger">Data Kosong</td></tr>`);
                }

                $('#table_list_data_detail_surat_jalan').DataTable({
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, 'All']
                    ],
                });
            }
        });
    });

    function get_data_perusahaan() {
        $.ajax({
            url: "<?php echo base_url('WMS/PenerimaanSuratJalan/get_data_perusahaan'); ?>",
            type: "GET",
            dataType: "JSON",
            async: false,
            success: function(data) {
                $('.perusahaan').empty();
                $('.perusahaan').append('<option value="">--Pilih Perusahaan--</option>');
                $('.perusahaan_split').empty();
                $('.perusahaan_split').append('<option value="">--Pilih Perusahaan--</option>');
                $.each(data, function(i, v) {
                    $('.perusahaan').append('<option value="' + v.client_wms_id + '">' + v
                        .client_wms_nama + '</option>');
                    $('.perusahaan_split').append('<option value="' + v.client_wms_id + '">' + v
                        .client_wms_nama + '</option>');
                });
            }
        });
    }

    function get_status() {
        $('#status').append(`
            <option value="">--Pilih Status--</option>
            <option value="Open" selected>Open</option>
        `);
    };

    function get_principle() {
        $('.principle').append(`
            <option value="">--Pilih Principle--</option>
        `);
        $('.principle_split').append(`
            <option value="">--Pilih Principle--</option>
        `);
    };



    function get_data_tipe_penerimaan() {
        $.ajax({
            url: "<?= base_url('WMS/PenerimaanSuratJalan/get_tipe_penerimaan') ?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('.tipe_penerimaan').empty();
                $('.tipe_penerimaan').append('<option value="">--Pilih Tipe--</option>');
                $.each(data, function(i, v) {
                    $('.tipe_penerimaan').append('<option value="' + v.id + '">' + v.nama
                        .toUpperCase() + '</option>');
                });
            }
        });
    }

    $("#tambah-penerimaan").on("click", function() {
        $("#modalSplitSuratJalan").modal("show");
        // $("#form_batch_penerimaan").modal("show");

        check_count_sku();
        let total_sku = $("#table_list_data_sku > tbody tr").length;
        $("#table_list_data_sku > tfoot tr #total_sku").html("<h4><strong>Total " + total_sku +
            " SKU</strong></h4>");
    });

    $(document).on("change", ".chkTypeSuratJalan", function() {
        if ($(this).val() == "1") {
            $("#showInputNoEksternal").show('slow')
        } else {
            $("#noSuratJalanEksternal").val('');
            $("#showInputNoEksternal").hide('slow');
        }
    })

    document.getElementById('noSuratJalanEksternal').addEventListener('keyup', function() {
        if (this.value != "") {
            fetch('<?= base_url('WMS/PenerimaanSuratJalan/getNoSuratJalanEksternal?params='); ?>' + this.value)
                .then(response => response.json())
                .then((results) => {
                    if (!results[0]) {
                        document.getElementById('table-fixed').style.display = 'none';
                        $(".handlePilihSplitSJ").attr('data-split', this.value);
                    } else {
                        let data = "";
                        // console.log(results);
                        results.forEach(function(e, i) {
                            let kode = e.kode.split("-");
                            data += `
                            <tr onclick="getNoSuratJalanEks('${kode[0]}')" style="cursor:pointer">
                                <td class="col-xs-1">${i + 1}.</td>
                                <td class="col-xs-11">${kode[0]}</td>
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
            $(".handlePilihSplitSJ").attr('data-split', this.value);
        }
    });

    function getNoSuratJalanEks(data) {
        $("#noSuratJalanEksternal").val(data);
        document.getElementById('table-fixed').style.display = 'none'

        fetch('<?= base_url('WMS/PenerimaanSuratJalan/getLastNoCounterSuratJalan?params='); ?>' + data)
            .then(response => response.json())
            .then((results) => {
                if (typeof results.client_wms_id === "undefined") {
                    $(".handlePilihSplitSJ").attr('data-split', results.numberCounter);
                } else {
                    $(".handlePilihSplitSJ").attr('data-split', results.numberCounter);
                    $("#perusahaan_split").val(results.client_wms_id).trigger('change');
                    $("#principle_split").val(results.principle_id).trigger('change');
                }

            });
    }

    $(document).on("click", ".handlePilihSplitSJ", function() {
        let chkTypeSJ = $('input[name=selectSplit]:checked').val();
        let perusahaan = $(".perusahaan_split").val();
        let principle = $(".principle_split").val();

        if (typeof chkTypeSJ === 'undefined') {
            message("Error!", "Pilih salah satu dari data 2 diatas", "error");
            return false;
        } else {
            if (chkTypeSJ == "0") {
                $("#noSuratJalanEksternal").val('');
                $(".chkTypeSuratJalan").prop('checked', false);
                $("#modalSplitSuratJalan").modal("hide");
                $("#form_batch_penerimaan").modal("show");
                $("#no_surat_jalan").prop('readonly', false);

                check_count_sku();
                let total_sku = $("#table_list_data_sku > tbody tr").length;
                $("#table_list_data_sku > tfoot tr #total_sku").html("<h4><strong>Total " + total_sku +
                    " SKU</strong></h4>");
            } else {
                if (perusahaan == "") {
                    message("Error!", "Perusahaan tidak boleh kosong", "error");
                    return false;
                } else if (principle == "") {
                    message("Error!", "Perusahaan tidak boleh kosong", "error");
                    return false;
                } else if ($("#noSuratJalanEksternal").val() == "") {
                    message("Error!", "No. Surat Jalan Eksternal tidak boleh kosong jika ingin split", "error");
                    return false;
                } else {
                    let noEksSj = $(this).attr('data-split').split("-");
                    if (noEksSj.length == 1) {
                        $("#no_surat_jalan").val(noEksSj[0])
                        $("#no_surat_jalan_counter").val('001')

                    } else {
                        $("#no_surat_jalan").val(noEksSj[0])
                        $("#no_surat_jalan_counter").val(noEksSj[1]);

                        $("#perusahaan").val(perusahaan).trigger('change')
                        $("#principle").val(principle).trigger('change')
                    }

                    $("#noSuratJalanEksternal").val('');
                    $(".chkTypeSuratJalan").prop('checked', false);
                    $("#modalSplitSuratJalan").modal("hide");
                    $("#form_batch_penerimaan").modal("show");


                    $("#no_surat_jalan").prop('readonly', true);

                    check_count_sku();
                    let total_sku = $("#table_list_data_sku > tbody tr").length;
                    $("#table_list_data_sku > tfoot tr #total_sku").html("<h4><strong>Total " + total_sku +
                        " SKU</strong></h4>");
                }
            }
        }
    });

    $(document).on("click", ".handleTutupPilihSplitSJ", function() {
        $("#noSuratJalanEksternal").val('');
        $(".chkTypeSuratJalan").prop('checked', false);
        $("#modalSplitSuratJalan").modal("hide");
    });

    //onchane untuk penyalur / principle
    $(".perusahaan").on("change", function() {
        let id = $(this).val();
        let txt = $("#perusahaan option:selected").text();

        $("#pilih-sku").attr('data-id-perusahaan', id);
        $("#pilih-sku").attr('data-txt-perusahaan', txt);
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/PenerimaanSuratJalan/get_data_principle_by_client_wms_id') ?>",
            data: {
                id: id
            },
            dataType: "json",
            async: false,
            success: function(response) {
                $(".principle").empty();
                let html = "";
                html += '<option value="">--Pilih Principle</option>';
                $.each(response, function(i, v) {
                    html += "<option value=" + v.id + ">" + v.nama + "</option>";
                });
                $(".principle").append(html);
            }
        });
    });

    //onchange untuk tempo pembyaran
    $("#principle").on("change", function() {
        let id = $(this).val();
        let txt = $("#principle option:selected").text();

        $("#pilih-sku").attr('data-id-principle', id);
        $("#pilih-sku").attr('data-txt-principle', txt);
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/PenerimaanSuratJalan/get_data_principle_by_principle_id') ?>",
            data: {
                id: id
            },
            dataType: "json",
            async: false,
            success: function(response) {
                $("#sub_principle").val(response.kode + " - " + response.nama);
                $("#tempo_pembayaran").val(response.tempo != null ? response.tempo + " Hari" : "-");
            }
        });
        append_data_pilih_sku(id, txt);
    });

    //onchane untuk penyalur / principle
    $(".perusahaan_split").on("change", function() {
        let id = $(this).val();
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/PenerimaanSuratJalan/get_data_principle_by_client_wms_id') ?>",
            data: {
                id: id
            },
            dataType: "json",
            async: false,
            success: function(response) {
                $(".principle_split").empty();
                let html = "";
                html += '<option value="">--Pilih Principle</option>';
                $.each(response, function(i, v) {
                    html += "<option value=" + v.id + ">" + v.nama + "</option>";
                });
                $(".principle_split").append(html);
            }
        });
    });

    function append_data_pilih_sku(id, txt) {
        let id_perusahaan = $("#perusahaan option:selected").val();
        let txt_perusahaan = $("#perusahaan option:selected").text();
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/PenerimaanSuratJalan/get_data_sku_by_principle_id') ?>",
            data: {
                id: id,
                client_wms_id: id_perusahaan
            },
            dataType: "json",
            async: false,
            success: function(response) {
                $("#list_data_pilih_sku .modal-title").html(
                    `<span><label name="CAPTION-LISTDATASKUDARIPERUSAHAAN">List Data SKU dari Perusahaan</label> <strong>${txt_perusahaan}</strong> <label name="CAPTION-DANPRINCIPLE">dan Principle</label> <strong>${txt}</strong></span>`
                );
                if (response != null) {
                    if ($.fn.DataTable.isDataTable('#table_list_data_pilih_sku')) {
                        $('#table_list_data_pilih_sku').DataTable().destroy();
                    }
                    $('#table_list_data_pilih_sku tbody').empty();
                    for (i = 0; i < response.length; i++) {
                        let sku_id = response[i].sku_id;
                        let sku_kode = response[i].sku_kode;
                        let sku = response[i].sku;
                        let sku_satuan = response[i].sku_satuan;
                        let sku_kemasan = response[i].sku_kemasan;
                        let sku_induk = response[i].sku_induk;
                        let principle = response[i].principle;
                        let brand = response[i].brand;
                        var str = "";

                        str +=
                            `<input type="checkbox" class="form-control check-item" name="chk-data[]" style="transform: scale(1.5)" id="chk-data[]" value="${sku_id}" onclick="handleChangeInputQty('${sku_id}', this)"/>`;

                        var strmenu = '';

                        strmenu += '<tr>';
                        strmenu += '<td class="text-center">' + str + '</td>';
                        strmenu += '<td>' + sku_induk + '</td>';
                        strmenu += '<td>' + sku_kode + '</td>';
                        strmenu += '<td>' + sku + '</td>';
                        strmenu += '<td class="text-center">' + sku_kemasan + '</td>';
                        strmenu += '<td class="text-center">' + sku_satuan + '</td>';
                        strmenu += '<td>' + principle + '</td>';
                        strmenu += '<td>' + brand + '</td>';
                        strmenu += `<td>
                                        <input type="text" class="form-control numeric qtySku" id="qtySku${sku_id}" readonly>
                                    </td>`;
                        strmenu += '</tr>';
                        $("#table_list_data_pilih_sku > tbody").append(strmenu);
                    }
                } else {
                    $("#table_list_data_pilih_sku > tbody").html(
                        `<tr><td colspan="9" class="text-center text-danger">Data Kosong</td></tr>`);
                    // message('Info!', 'Data Kosong', 'info');
                }

                $('#table_list_data_pilih_sku').DataTable({
                    columnDefs: [{
                        sortable: false,
                        targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                    }],
                    lengthMenu: [
                        [-1],
                        ['All']
                    ],
                });
            }
        });
    }

    function handleChangeInputQty(sku_id, event) {
        if (event.checked == true) {
            $(`#qtySku${sku_id}`).prop("readonly", false);
        } else {
            $(`#qtySku${sku_id}`).prop("readonly", true);
        }
    }

    //modal untuk pilih sku
    $("#pilih-sku").on("click", function() {
        let id_perusahaan = $(this).attr('data-id-perusahaan');
        let txt_perusahaan = $(this).attr('data-txt-perusahaan');

        let id_principle = $(this).attr('data-id-principle');
        let txt_principle = $(this).attr('data-txt-principle');

        let res = $(this).attr('data-sku-id');
        // let sku_id = "";
        // if (res != null) {
        //     sku_id = res.split(",")
        // }
        //get data principle by id in sku
        if (id_perusahaan == null) {
            message('Info!', 'Pilih perusahaan terlebih dahulu', 'info');
            return false;
        } else if (id_principle == null) {
            message('Info!', 'Pilih principle terlebih dahulu', 'info');
            return false;
        } else {
            $("#list_data_pilih_sku").modal("show");
            // check_delete(sku_id);
        }

    });

    // function check_delete(sku_id) {
    //     let input = $(".check-item");
    //     if (sku_id != "") {
    //         input.each(function(i, v) {
    //             if (sku_id.includes($(this).val())) {
    //                 $("#pilih-sku").removeAttr('data-sku-id');
    //                 $(this).attr({
    //                     disabled: false,
    //                     checked: false
    //                 });
    //             }
    //         });
    //     }
    // }

    //function untuk checklist all pilih sku
    function checkAllSKU(e) {
        var checkboxes = $("input[name='chk-data[]']");
        if (e.checked) {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
                    checkboxes[i].checked = true;
                    $(".qtySku").prop('readonly', false);
                }
            }
        } else {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].type == 'checkbox' && !(checkboxes[i].disabled)) {
                    checkboxes[i].checked = false;
                    $(".qtySku").prop('readonly', true);
                }
            }
        }
    }

    $(document).on("click", ".btn_pilih_sku", function() {
        let arr_chk = [];
        var checkboxes = $("input[name='chk-data[]']");
        arr_chk.length = 0;
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked == true && !(checkboxes[i].disabled)) {
                // checkboxes[i].disabled = true;

                if ($(`#qtySku${checkboxes[i].value}`).val() == "") {
                    message("Error!", "Sku yang dicentang, Qtynya tidak boleh kosong", "error");
                    return false
                } else {
                    arr_chk.push({
                        id: checkboxes[i].value,
                        qty: $(`#qtySku${checkboxes[i].value}`).val()
                    });
                }


            }
        }

        if (arr_chk.length == 0) {
            message("Info!", "Pilih data yang akan dipilih", "info");
        } else {
            $.ajax({
                type: "POST",
                url: "<?= base_url('WMS/PenerimaanSuratJalan/get_sku_by_id'); ?>",
                data: {
                    data: arr_chk
                },
                dataType: "json",
                success: function(response) {
                    // $("#table_list_data_sku > tbody").empty();
                    $("input[name='chk-data[]']").prop('checked', false);

                    append_list_data_setelah_pilih_sku(response, arr_chk);
                    // $('#table_list_data_pilih_sku').DataTable().ajax.reload();
                    $('#table_list_data_pilih_sku').dataTable().fnFilter('');
                    $("#list_data_pilih_sku").modal("hide");
                }
            });
        }
    });

    function append_list_data_setelah_pilih_sku(response, arrSkuId) {
        $.each(response, function(i, v) {
            global_data.push({
                sku_id: v.sku_id,
                tipe_id: null,
                // harga: Math.round(v.sku_harga_jual),
            });
            $("#table_list_data_sku > tbody").append(`
                <tr>
                    <td>${v.sku_kode} <input type="hidden" id="id_sku" value="${v.sku_id}"/></td>
                    <td>${v.sku_nama_produk}</td>
                    <td>${v.sku_kemasan}</td>
                    <td>${v.sku_satuan}</td>
                    <td>
                        <input type="date" class="form-control expired_date" name="expired_date" id="expired_date_${v.sku_id}" onkeydown="return false"/>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="batch_no" id="batch_no"/>
                    </td>
                    <td>
                        <input type="text" class="form-control numeric" name="jumlah_barang_sku" id="jumlah_barang_sku" min="0" value="${v.qty}"/>
                    </td>
                    <td>
                        <select class="form-control select2 tipe_penerimaan_sku" name="tipe_penerimaan_sku" id="tipe_penerimaan_sku" required></select>
                    </td>
                    <td class="text-center"><button type="button" class="btndeletesku" data-id="${v.sku_id}" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button></td>
                </tr>
            `);
        });

        Swal.fire({
            html: 'Apakah anda ingin menggunakan default expired date?',
            showCancelButton: true,
            confirmButtonText: 'Iya',
            cancelButtonText: `Tidak`,
            allowOutsideClick: false,
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('WMS/PenerimaanSuratJalan/checkMinimunExpiredDate'); ?>",
                    data: {
                        data: arrSkuId
                    },
                    dataType: "JSON",
                    success: function(response) {
                        $.each(response, function(i, v) {
                            $(`#expired_date_${v.sku_id}`).val(v.date);
                        })
                    }
                });
            }
        });

        select2();
        get_data_tipe_penerimaan2();
        // $("#tipe_penerimaan").attr("data-harga", arr_harga);

        var dtToday = new Date();

        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if (month < 10)
            month = '0' + month.toString();
        if (day < 10)
            day = '0' + day.toString();

        var maxDate = year + '-' + month + '-' + day;
        $('.expired_date').attr('min', maxDate);

        let tot_jml = 0;
        $("#table_list_data_sku > tbody tr").each(function(i, v) {

            let currentRow = $(this);
            let tipe = currentRow.find("td:eq(7) select");
            //change kolom tipe
            tipe.change(function(e) {
                check_tipe(e);
            });

        });
        let total_sku = $("#table_list_data_sku > tbody tr").length;
        check_count_sku();
        $("#table_list_data_sku > tfoot tr #total_sku").html("<h4><strong>Total " + total_sku + " SKU</strong></h4>");

        //btn delete
        var arr_lokal_temp = [];
        $(document).on("click", ".btndeletesku", function() {
            let id = $(this).attr("data-id");
            // $("#principle option:selected").attr('data-id', id).trigger('change');
            arr_lokal_temp.push(id);
            $("#pilih-sku").attr('data-sku-id', arr_lokal_temp);
            let item = global_data.filter((value, index) => value.sku_id !== id);
            global_data.length = 0;
            $.each(item, function(i, v) {
                global_data.push({
                    sku_id: v.sku_id,
                    tipe: v.tipe_id
                })
            });
            $(this).parent().parent().remove();
            // hitung_total();
            let total_sku = $("#table_list_data_sku > tbody tr").length;
            check_count_sku();
            $("#table_list_data_sku > tfoot tr #total_sku").html("<h4><strong>Total " + total_sku +
                " SKU</strong></h4>");
        });
    }

    function get_data_tipe_penerimaan2(id) {
        let id_ = $('#tipe_penerimaan').val() != '' ? $('#tipe_penerimaan').val() : id;
        $.ajax({
            url: "<?= base_url('WMS/PenerimaanSuratJalan/get_tipe_penerimaan2') ?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('.tipe_penerimaan_sku').empty();
                let output = '';
                let arr_temp_tipe_id = [];
                output += '<option value="">--Pilih Tipe</option>';
                $.each(data, function(i, v) {
                    if (typeof id_ !== 'undefined') {
                        if (id_ == "7E683187-445E-4044-87BF-58804C9E09DA") {
                            output += '<option value="' + v.id + '">' + v.nama.toUpperCase() +
                                '</option>';
                        } else {
                            if (id_ == v.id) {
                                output += '<option value="' + v.id + '" selected>' + v.nama
                                    .toUpperCase() + '</option>';
                            } else {
                                output += '<option value="' + v.id + '">' + v.nama.toUpperCase() +
                                    '</option>';
                            }
                        }
                    } else {
                        output += '<option value="' + v.id + '">' + v.nama.toUpperCase() + '</option>';
                    }
                });

                $('.tipe_penerimaan_sku').append(output).trigger('change');

                if (id_ == "7E683187-445E-4044-87BF-58804C9E09DA") {
                    $.each(global_data, function(index, value) {
                        $(".tipe_penerimaan_sku").eq(index).val(value.tipe_id).trigger('change');
                    });
                }
            }
        });
    }

    //onchange untuk tipe penerimaan
    $("#tipe_penerimaan").on("change", function() {
        let txt = $(this).children("option").filter(":selected").text();
        let txt_id = $(this).val();

        if (txt_id != "7E683187-445E-4044-87BF-58804C9E09DA") {
            get_data_tipe_penerimaan2(txt_id);
            $("#table_list_data_sku > tbody tr").each(function(i, v) {
                let currentRow = $(this);
                let sku_id = currentRow.find("td:eq(0) input[type='hidden']").val();
                let jml_barang = currentRow.find("td:eq(6) input[type='text']");
                let tipe = currentRow.find("td:eq(7) select");
                // let harga_jual = currentRow.find("td:eq(6)");
                // let diskon = currentRow.find("td:eq(7) input[type='text']");
                // let value_diskon = currentRow.find("td:eq(8) input[type='text']");
                // let bruto = currentRow.find("td:eq(9)");
                // let jml = currentRow.find("td:eq(10)");

                // if (txt == 'BONUS' || txt == 'PENGGANTI KLAIM' || txt == 'RETUR' || txt == 'SAMPLE') {
                //     jml.text(0);
                //     bruto.text(0);
                //     diskon.val(0);
                //     value_diskon.val(0);
                //     harga_jual.text(0);
                //     diskon.attr('readonly', true);
                //     value_diskon.attr('readonly', true);
                // } else {
                //     let jml_barang_val = jml_barang.val() == "" ? 1 : jml_barang.val() == 0 ? 1 : jml_barang.val();
                //     let harga_jual_val = global_data[i].harga;
                //     let jml_akhir = harga_jual_val * jml_barang_val;
                //     jml.text(formatRupiah(jml_akhir.toString()));
                //     bruto.text(formatRupiah(jml_akhir.toString()));
                //     diskon.attr('readonly', false);
                //     value_diskon.attr('readonly', false);
                //     // diskon.val(0);
                //     // value_diskon.val(0);
                //     harga_jual.text(formatRupiah(harga_jual_val.toString()));
                // }
            });
        }

    });

    function check_tipe(e) {
        let _1 = "";
        let sama = true;
        $("#table_list_data_sku > tbody tr").each(function(i, v) {
            let currentRow = $(this);
            let tipe = $(this).find("td:eq(7) select").children("option").filter(":selected").val();
            if (tipe != "") {
                if (_1 == "") {
                    _1 = tipe;
                } else {
                    if (_1 != tipe) {
                        //ganti value
                        sama = false;
                        $("#tipe_penerimaan").val("7E683187-445E-4044-87BF-58804C9E09DA").trigger('change')
                    }
                }
            }
        });

        if (sama == true) {
            if (_1 != "") {
                $("#tipe_penerimaan").val(_1).trigger('change.select2');
            }
        }
    }

    // function hitung_total() {
    //     let tot_jml = 0;
    //     $("#table_list_data_sku > tfoot tr #tot_jml").html("")
    //     $("#table_list_data_sku > tbody tr").each(function(i, v) {
    //         let currentRow = $(this);
    //         let jml = currentRow.find("td:eq(10)");
    //         tot_jml += parseFloat(jml.text().split(".").join(""));
    //     });
    //     $("#table_list_data_sku > tfoot tr #tot_jml").html("<p style='margin-left:20%'><strong>" + formatRupiah(tot_jml.toString()) + "</strong></p>");
    // }

    $("form.surat_jalanModify").submit(function(e) {
        e.preventDefault();
        var str = "";
        $(".card_surat_jalan").each(function(i, v) {
            var perusahaan = $(this).find(".perusahaan").val();
            var principle = $(this).find(".principle").val();
            var tipe_penerimaan = $(this).find(".tipe_penerimaan").val();
            var status = $(this).find(".status").val();
            var file = $(this).find(".file")[0].files[0];
            var no_surat_jalan = $(this).find(".no_surat_jalan").val();
            var no_kendaraan = $(this).find(".no_kendaraan").val();

            if (perusahaan == '') {
                str = "Field Perusahaan masih ada yang kosong";
                return false;
            } else if (principle == '') {
                str = "Field principle masih ada yang kosong";
                return false;
            } else if (tipe_penerimaan == '') {
                str = "Field tipe Penerimaan masih ada yang kosong"
                return false;
            } else if (status == '') {
                str = "Field status masih ada yang kosong";
                return false;
            } else if (no_surat_jalan == '') {
                str = "Field no. Surat Jalan masih ada yang kosong";
                return false;
            } else if (no_surat_jalan == '') {
                str = "Field no. Kendaraan masih ada yang kosong";
                return false;
            }
        });

        if (str != '') {
            message("Error!", str, "error");
            return false;
        }

        var form = $(this);
        form.find('input, select').attr('disabled', false);
        // data = form.serialize()
        var formData = new FormData(form[0]);
        // return false;
        messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup')
            .then((result) => {
                if (result.value == true) {
                    requestAjax("<?= base_url('WMS/PenerimaanSuratJalan/generate'); ?>", {
                        dataForm: formData
                    }, "POST", "JSON", function(response) {
                        if (response.status == true) {
                            message_topright("success", response.message);
                            $('#form_batch_penerimaan').fadeOut("slow", function() {
                                $(this).modal('hide');
                                reset_form();
                                setTimeout(function() {
                                    location.reload();
                                }, 300);
                            });
                        } else {
                            message_topright("error", response.message);
                        }
                    }, "#simpan-penerimaan", "multipart-formdata")
                }
            });
    });

    $(document).on('click', '#simpan-penerimaan', function() {

        let perusahaan = $('#perusahaan').val();
        let principle = $('#principle').val();
        let tipe_penerimaan = $('#tipe_penerimaan').val();
        let status = $('#status').val();
        let no_surat_jalan = $('#no_surat_jalan').val();
        let no_surat_jalan_counter = $('#no_surat_jalan_counter').val();
        let no_kendaraan = $('#no_kendaraan').val();
        let keterangan = $('#keterangan').val();
        let file = $('#file').val();
        let arr_sku_id = [];
        let arr_ed = [];
        let arr_batch_no = [];
        let arr_sku_tipe = [];
        let arr_jml_brng = [];
        // let arr_diskon = [];
        // let arr_value_diskon = [];
        // let arr_bruto = [];
        // let arr_netto = [];
        let arr_detail_psj = [];

        if (perusahaan == '') {
            message("Error!", "Perusahaan tidak boleh kosong", "error");
            $("#count_error").val("1");
        } else if (principle == '') {
            message("Error!", "Principle tidak boleh kosong", "error");
            $("#count_error").val("1");
        } else if (tipe_penerimaan == '') {
            message("Error!", "Tipe Penerimaan tidak boleh kosong", "error");
            $("#count_error").val("1");
        } else if (status == '') {
            message("Error!", "Status tidak boleh kosong", "error");
            $("#count_error").val("1");
        } else if (no_surat_jalan == '') {
            message("Error!", "No. Surat Jalan tidak boleh kosong", "error");
            $("#count_error").val("1");
        } else if (no_kendaraan == '') {
            message("Error!", "No. Kendaraan tidak boleh kosong", "error");
            $("#count_error").val("1");
        } else if (file == '') {
            message("Error!", "File Attachment tidak boleh kosong", "error");
            $("#count_error").val("1");
        } else {
            let countSKU = $('#table_list_data_sku > tbody').children('tr').length;
            if (countSKU == 0) {
                message('Error!', 'Tidak dapat simpan, SKU kosong!!! silahkan pilih sku', 'error');
                $("#count_error").val("1");
                return false;
            } else {
                $("#table_list_data_sku > tbody tr").each(function(index, value) {
                    let sku_id = $(this).find("td:eq(0) input[type='hidden']");
                    let ed = $(this).find("td:eq(4) input[type='date']");
                    let batch_no = $(this).find("td:eq(5) input[type='text']");
                    let jml_brng = $(this).find("td:eq(6) input[type='text']");
                    let sku_tipe = $(this).find("td:eq(7) select").children("option").filter(":selected");
                    // let diskon = $(this).find("td:eq(7) input[type='text']");
                    // let value_diskon = $(this).find("td:eq(8) input[type='text']");
                    // let bruto = $(this).find("td:eq(9)");
                    // let netto = $(this).find("td:eq(10)");

                    if (ed.val() == '') {
                        message('Error!', 'Tidak dapat simpan, Expired date tidak boleh kosong!', 'error');
                        $("#count_error").val("1");
                        return false;
                    } else if (jml_brng.val() == '') {
                        message('Error!', 'Tidak dapat simpan, Jumlah barang tidak boleh kosong!', 'error');
                        $("#count_error").val("1");
                        return false;
                    } else if (sku_tipe.val() == '') {
                        message('Error!', 'Tidak dapat simpan, Tipe tidak boleh kosong!', 'error');
                        $("#count_error").val("1");
                        return false;
                    } else {
                        $("#count_error").val("0");
                        sku_id.map(function() {
                            arr_sku_id.push($(this).val());
                        }).get();

                        ed.map(function() {
                            arr_ed.push($(this).val());
                        }).get();

                        batch_no.map(function() {
                            arr_batch_no.push($(this).val());
                        }).get();

                        sku_tipe.map(function() {
                            arr_sku_tipe.push($(this).val());
                        }).get();

                        jml_brng.map(function() {
                            arr_jml_brng.push($(this).val());
                        }).get();

                        // diskon.map(function() {
                        //     arr_diskon.push($(this).val());
                        // }).get();

                        // value_diskon.map(function() {
                        //     arr_value_diskon.push($(this).val());
                        // }).get();

                        // bruto.map(function() {
                        //     arr_bruto.push($(this).text());
                        // }).get();

                        // netto.map(function() {
                        //     arr_netto.push($(this).text());
                        // }).get();
                    }
                });
            }
        }

        let error = $("#count_error");
        if (error.val() != 0) {
            return false;
        } else {

            if (arr_sku_id != null) {
                for (let index = 0; index < arr_sku_id.length; index++) {
                    arr_detail_psj.push({
                        'sku_id': arr_sku_id[index],
                        'ed': arr_ed[index],
                        'batch_no': arr_batch_no[index],
                        'sku_tipe': arr_sku_tipe[index],
                        'jml_brng': arr_jml_brng[index],
                        // 'diskon': arr_diskon[index],
                        // 'value_diskon': arr_value_diskon[index],
                        // 'netto': arr_netto[index],
                        // 'bruto': arr_bruto[index]
                    });
                }
            }

            var json_arr = JSON.stringify(arr_detail_psj);
            let new_form = new FormData();
            const files = $('#file')[0].files[0];
            new_form.append('file', files);
            //tambahan aja
            new_form.append('tgl', $('#tgl').val());
            new_form.append('perusahaan', perusahaan);
            new_form.append('principle', principle);
            new_form.append('tipe_penerimaan', tipe_penerimaan);
            new_form.append('status', status);
            new_form.append('no_surat_jalan', no_surat_jalan);
            new_form.append('no_surat_jalan_counter', no_surat_jalan_counter);
            new_form.append('no_kendaraan', no_kendaraan);
            new_form.append('keterangan', keterangan);
            new_form.append('data_detail', json_arr);

            messageBoxBeforeRequest('Pastikan data yang sudah anda input benar!', 'Iya, Simpan', 'Tidak, Tutup')
                .then((result) => {
                    if (result.value == true) {
                        requestAjax("<?= base_url('WMS/PenerimaanSuratJalan/save'); ?>", {
                            dataForm: new_form
                        }, "POST", "JSON", function(response) {
                            if (response.status == true) {
                                message_topright("success", response.message);
                                $('#form_batch_penerimaan').fadeOut("slow", function() {
                                    $(this).modal('hide');
                                    reset_form();
                                    setTimeout(function() {
                                        location.reload();
                                    }, 300);
                                });
                            } else {
                                message_topright("error", response.message);
                            }
                        }, "#simpan-penerimaan", "multipart-formdata")
                    }
                });
        }
    });


    $(document).on('click', '#batal-penerimaan', function() {
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Ingin membatalkan pembuatan form penerimaan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Batalkan",
            cancelButtonText: "Tidak, Tutup"
        }).then((result) => {
            if (result.value == true) {
                $('#form_batch_penerimaan').fadeOut("slow", function() {
                    $(this).modal('hide');
                    reset_form();
                    get_data_perusahaan();
                });
            }
        });
    });

    function reset_form() {
        $("#table_list_data_sku > tbody").empty();
        $("#perusahaan").html("");
        $("#principle").html("");
        $("#tipe_penerimaan").html("");
        $("#sub_principle").val("");
        $("#tempo_pembayaran").val("");
        $("#status").html("");
        $("#file").val("");
        $("#no_surat_jalan").val("");
        $("#keterangan").val("");
        get_data_tipe_penerimaan();
        get_status();
        get_data_perusahaan();
        get_principle();
        $("#table_list_data_sku > tfoot tr #tot_jml").text("");
        $("#noSuratJalanEksternal").val('');
        $(".chkTypeSuratJalan").prop('checked', false);
        global_data.length = 0;
    }

    function previewFile(idx) {
        const file = document.querySelector('input[type=file]').files[0];
        const reader = new FileReader();

        $('#show-file-' + idx).empty();
        reader.addEventListener("load", function() {
            let result = reader.result.split(',');
            // console.log(json_encode(result[1]));
            // convert image file to base64 string
            $('#show-file-' + idx).append(`
            <div class="col-md-6">
                <a class="text-primary klik-saya" style="cursor:pointer" data-url="${result[1]}" data-ex="${result[0]}">${file.name}</a>
            </div>
            <div class="col-md-6">
                <button type="button" data-toggle="tooltip" data-placement="top" title="hapus file" class="btndeletefile" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button>
            </div>
        `);
            // preview.src = reader.result;
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    function previewFile2(idx) {
        const file = $(`#file-${idx}`)[0].files[0];
        const reader = new FileReader();

        $('#show-file-' + idx).empty();
        reader.addEventListener("load", function() {
            let result = reader.result.split(',');
            $('#show-file-' + idx).append(`
            <div class="col-md-6">
                <a class="text-primary klik-saya" style="cursor:pointer" data-url="${result[1]}" data-ex="${result[0]}">${file.name}</a>
            </div>
            <div class="col-md-6">
                <button type="button" data-toggle="tooltip" data-placement="top" title="hapus file" onclick="delFile('${idx}')" style="border:none;background:transparent"><i class="fas fa-trash text-danger" style="cursor: pointer"></i></button>
            </div>
        `);
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    // Menambahkan event listener untuk setiap elemen file input
    $('[id^="file-"]').on('change', function() {
        // Mendapatkan indeks dari id elemen file input
        var idx = this.id.split('-')[1];
        previewFile(idx);
    });

    $(document).on('click', '.klik-saya', function() {
        let url = $(this).data('url');
        let ex = $(this).data('ex');
        let pdfWindow = window.open("")
        pdfWindow.document.write(
            "<iframe width='100%' height='100%' src='" + ex + ", " +
            encodeURI(url) + "'></iframe>"
        )
    });

    $(document).on('click', '.btndeletefile', function() {
        $('#show-file').empty();
        $("#file").val("");
        // $(".file-upload-wrapper").attr("data-text", "Select your file!");
    });

    function delFile(idx) {
        $('#show-file-' + idx).empty();
        $("#file-" + idx).val("");
    }

    //modal close untuk tambah penerimaan
    $(".btn_close_form_batch_penerimaan").on("click", function() {
        $("#form_batch_penerimaan").modal("hide");
    });

    //modal close untuk pilih sku
    $(".btn_close_list_data_pilih_sku").on("click", function() {
        $('#table_list_data_pilih_sku').dataTable().fnFilter('');
        $("#list_data_pilih_sku").modal("hide");
    });

    //modal close untuk detail data surat jalan
    $(".btn_close_detail_list_data").on("click", function() {
        $("#list_data_detail_surat_jalan").modal("hide");
    });
</script>