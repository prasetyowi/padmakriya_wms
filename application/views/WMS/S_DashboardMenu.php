td<script type="text/javascript">
    loadingBeforeReadyPage()
    var Outlet = '<?= $_SESSION['depo_nama'] ?>';
    var DepoID = '<?= $_SESSION['depo_id'] ?>';

    $(document).ready(function() {
        var Outlet = '<?= $_SESSION['depo_nama'] ?>';
        var DepoID = '<?= $_SESSION['depo_id'] ?>';

        titleCase(Outlet);

        // var mode = $("#filter_mode option:selected").val();
        // if (mode == 0) {
        //     // if ($('#filterstatus').length > 0) {
        //     //     $('#filterstatus').daterangepicker({
        //     //         'applyClass': 'btn-sm btn-success',
        //     //         'cancelClass': 'btn-sm btn-default',
        //     //         locale: {
        //     //             "format": "DD/MM/YYYY",
        //     //             applyLabel: 'Apply',
        //     //             cancelLabel: 'Cancel',
        //     //         },
        //     //         'startDate': new Date(),
        //     //         'endDate': new Date(),
        //     //     });
        //     // }

        //     $('#filterstatus').on('apply.daterangepicker', function(ev, picker) {
        //         GetCountByStatusDORealTime(DepoID, 1)
        //     })
        // }

        // var timeout = setTimeout(function() {
        //     GetCountByStatusDORealTime(DepoID, 1)
        // }, 3000);

        countStatusDO();

        GetDataApproval();

        const urlSearchParams = new URLSearchParams(window.location.search);
        const approvalName = Object.fromEntries(urlSearchParams.entries()).approvalName;

        // resetUrlPushState();
        if (typeof approvalName !== 'undefined') {
            detailApproval(approvalName).click();
        }

        initDataListSKUNotInWMS()

    });

    const handlerRefreshSKU = () => {
        $("#listSKUNotInWMS > tbody").empty();
        initDataListSKUNotInWMS()
    }

    const initDataListSKUNotInWMS = () => {
        $.ajax({
            type: 'GET',
            url: "<?= base_url('WMS/MainDashboard/getSKUNotInWMS') ?>",
            dataType: "JSON",
            beforeSend: function() {
                $("#listSKUNotInWMS > tbody").append(`
                    <tr class="text-center">
                        <td colspan="5">Loading...</td>
                    </tr>
                `)
            },
            success: function(response) {
                if (response.length > 0) {
                    $("#listSKUNotInWMS > tbody").empty();

                    if ($.fn.DataTable.isDataTable('#listSKUNotInWMS')) {
                        $('#listSKUNotInWMS').DataTable().clear();
                        $('#listSKUNotInWMS').DataTable().destroy();
                    }
                    response.map((item, index) => {
                        $("#listSKUNotInWMS > tbody").append(`
                            <tr class="text-center">
                                <td>${index + 1}</td>
                                <td>${item.principle_kode}</td>
                                <td>${item.sku_konversi_group}</td>
                                <td>${item.sku_nama_produk}</td>
                                <td>${item.datasource}</td>
                            </tr>
                        `)
                    })

                    $('#listSKUNotInWMS').DataTable({
                        'ordering': false
                    });
                }
            }
        })
    }

    window.onload = function(e) {
        resetUrlPushState();
    };

    // $("#filter_mode").change(
    //     function() {
    //         var DepoID = '<?= $_SESSION['depo_id'] ?>';
    //         var timeout = setTimeout(function() {
    //             GetCountByStatusDORealTime(DepoID, 1)
    //         }, 60000);

    //         var mode = $("#filter_mode option:selected").val();

    //         if (mode == 1) {
    //             clearTimeout(timeout)
    //         } else {
    //             if ($('#filterstatus').length > 0) {
    //                 $('#filterstatus').daterangepicker({
    //                     'applyClass': 'btn-sm btn-success',
    //                     'cancelClass': 'btn-sm btn-default',
    //                     locale: {
    //                         "format": "DD/MM/YYYY",
    //                         applyLabel: 'Apply',
    //                         cancelLabel: 'Cancel',
    //                     },
    //                     'startDate': new Date(),
    //                     'endDate': new Date(),
    //                 });
    //             }

    //             $('#filterstatus').on('apply.daterangepicker', function(ev, picker) {
    //                 GetCountByStatusDORealTime(DepoID, 1)
    //             })
    //         }
    //     }
    // );

    function filterMode(value) {
        if (value == "0") {
            $("#filter_tgl_status").prop('disabled', true);
            $("#filter_tgl_status").val('<?= getLastTbgDepo() ?>');

            countStatusDO();
        } else {
            $("#filter_tgl_status").prop('disabled', false);
            $("#filter_tgl_status").val('<?= $lastTbgDepo ?>');
            $("#filter_tgl_status").attr('max', '<?= $lastTbgDepo ?>');

            countStatusDO();
        }
    }

    function filterTglStatus() {
        countStatusDO();
    }

    function countStatusDO() {
        Swal.fire({
            title: 'Loading ...',
            html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
            timerProgressBar: false,
            showConfirmButton: false
        });


        var mode = $("#filter_mode option:selected").val();
        var Tgl_By_Status = $("#filter_tgl_status").val();

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MainDashboard/GetCountByStatusDORealTime') ?>",
            data: {
                DepoID: DepoID,
                Tgl_By_Status: Tgl_By_Status,
                mode: mode
            },
            dataType: "JSON",
            success: function(response) {
                Swal.close();

                $("#table_status_do tbody").empty();

                $.each(response.statusDO, function(i, v) {
                    $("#table_status_do tbody").append(`
                    <tr>
                        <td style='vertical-align:middle;'>${v.delivery_order_status}</td>
                        <td style='vertical-align:middle;'>
                            <button onclick="listdo('${v.delivery_order_status}')" class="btn btn-primary" style="margin-bottom: 1px">${v.jumlah}</button>
                        </td>
                    </tr>
                `)
                })

                $.each(response.CardDashboard, function(i, v) {
                    $(`#${v.modul}`).html(v.jum);
                });
            }
        })
    }

    // function GetCountByStatusDORealTime(DepoID, index) {
    //     var mode = $("#filter_mode option:selected").val();
    //     var Tgl_By_Status = $("#filterstatus").val();

    //     if (index <= 13) {
    //         $.ajax({
    //             type: 'POST',
    //             url: "<?= base_url('WMS/MainDashboard/GetCountByStatusDORealTime') ?>",
    //             data: {
    //                 DepoID: DepoID,
    //                 index: index,
    //                 Tgl_By_Status: Tgl_By_Status,
    //                 mode: mode
    //             },
    //             dataType: "JSON",
    //             success: function(response) {
    //                 if (response.StatusInProgress != 0 && index == 1) {
    //                     var InProgress = response.StatusInProgress[0].inprogress;
    //                     $("#countinprogress").html(InProgress);
    //                 }

    //                 if (response.StatusInProgressItemRequest != 0 && index == 2) {
    //                     var inprogressitemrequest = response.StatusInProgressItemRequest[0]
    //                         .inprogressitemrequest;
    //                     $("#countinprogressitemrequest").html(inprogressitemrequest);
    //                 }

    //                 if (response.StatusInProgressPickUpItem != 0 && index == 3) {
    //                     var inprogresspickupitem = response.StatusInProgressPickUpItem[0].inprogresspickupitem;
    //                     $("#countinprogresspickupitem").html(inprogresspickupitem);
    //                 }

    //                 if (response.StatusPickUpItemConfirmed != 0 && index == 4) {
    //                     var pickupitemconfirmed = response.StatusPickUpItemConfirmed[0].pickupitemconfirmed;
    //                     $("#countpickupitemconfirmed").html(pickupitemconfirmed);
    //                 }

    //                 if (response.StatusInProgressPackingItem != 0 && index == 5) {
    //                     var inprogresspackingitem = response.StatusInProgressPackingItem[0]
    //                         .inprogresspackingitem;
    //                     $("#countinprogresspackingitem").html(inprogresspackingitem);
    //                 }

    //                 if (response.StatusPackingItemConfirmed != 0 && index == 6) {
    //                     var packingitemconfirmed = response.StatusPackingItemConfirmed[0].packingitemconfirmed;
    //                     $("#countpackingitemconfirmed").html(packingitemconfirmed);
    //                 }

    //                 if (response.StatusInTransitValidation != 0 && index == 7) {
    //                     var intransitvalidation = response.StatusInTransitValidation[0].intransitvalidation;
    //                     $("#countintransitvalidation").html(intransitvalidation);
    //                 }

    //                 if (response.StatusInTransitValidationCompleted != 0 && index == 8) {
    //                     var intransitvalidationcompleted = response.StatusInTransitValidationCompleted[0]
    //                         .intransitvalidationcompleted;
    //                     $("#countintransitvalidationcompleted").html(intransitvalidationcompleted);
    //                 }

    //                 if (response.StatusInTransit != 0 && index == 9) {
    //                     var intransit = response.StatusInTransit[0].intransit;
    //                     $("#countintransit").html(intransit);
    //                 }

    //                 if (response.StatusDelivered != 0 && index == 10) {
    //                     var delivered = response.StatusDelivered[0].delivered;
    //                     $("#countdelivered").html(delivered);
    //                 }

    //                 if (response.StatusPartiallyDelivered != 0 && index == 11) {
    //                     var partiallydelivered = response.StatusPartiallyDelivered[0].partiallydelivered;
    //                     $("#countpartiallydelivered").html(partiallydelivered);
    //                 }

    //                 if (response.StatusNotDelivered != 0 && index == 12) {
    //                     var notdelivered = response.StatusNotDelivered[0].notdelivered;
    //                     $("#countnotdelivered").html(notdelivered);
    //                 }

    //                 if (response.StatusCanceled != 0 && index == 13) {
    //                     var canceled = response.StatusCanceled[0].canceled;
    //                     $("#countcanceled").html(canceled);
    //                 }

    //                 $.each(response.CardDashboard, function(i, v) {
    //                     $(`#${v.modul}`).html(v.jum);
    //                 });

    //                 index = parseInt(index) + 1;

    //                 GetCountByStatusDORealTime(DepoID, index);
    //             }
    //         })
    //     } else {
    //         $("#filter_mode").trigger('change');

    //     }
    // }

    function listdo(status) {
        var Tgl_By_Status = $("#filterstatus").val();

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/MainDashboard/GetListDOByStatus') ?>",
            data: {
                Tgl_By_Status: Tgl_By_Status,
                status: status
            },
            dataType: "JSON",
            success: function(response) {
                $('#statusdo').html(status);

                if ($.fn.DataTable.isDataTable('#table-dodetail')) {
                    $('#table-dodetail').DataTable().clear();
                    $('#table-dodetail').DataTable().destroy();
                }

                $('#table-dodetail > tbody').empty();

                if (response != 0) {
                    $.each(response, function(i, v) {
                        $('#table-dodetail > tbody').append(`
                    <tr>
                    <td class="text-center">${v.tgl}</td>
                    <td class="text-center">${v.kode}</td>
                    <td class="text-center">${v.nama}</td>
                    <td class="text-center">${v.alamat}</td>
                    <td class="text-center"><button class="btn btn-primary" onclick="viewdetail('${v.do_id}')" title="view-detail"><i class="fa-solid fa-eye"></i></button></td>
                    </tr>
                    `)
                    });

                    $('#table-dodetail').DataTable({
                        lengthMenu: [
                            [50, 100, -1],
                            [50, 100, 'All']
                        ]
                    })
                } else {
                    $("#table-dodetail > tbody").html(`
                    <tr><td colspan="2" class="text-center text-danger">Data Kosong</td></tr>`);
                }
            }
        });

        $('#modal-listdostatus').modal('show');
    }

    function viewdetail(do_id) {
        var data = encodeURIComponent(do_id);
        window.open("<?= base_url('WMS/StatusDO/StatusDOMenu?data=') ?>" + data + "", "_blank");
    }

    const resetUrlPushState = () => {
        let url = window.location.href;
        if (url.indexOf("?") != -1) {
            let resUrl = url.split("?");

            if (typeof window.history.pushState == 'function') {
                window.history.pushState({}, "Hide", resUrl[0]);
            }
        }
    }

    function GetDataApproval() {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/Approval/GetDataApproval') ?>",
            data: {

            },
            success: function(response) {
                console.log(response);

                let no = 1;
                let data = response;

                if ($.fn.DataTable.isDataTable('#table_approval')) {
                    $('#table_approval').DataTable().destroy();
                }

                $("#table_approval tbody").empty();
                $("#table_approval tbody").html('');
                if (data.length > 0) {
                    $.each(data, function() {

                        $('#table_approval tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; ' >${no}</td>
                                    <td style='vertical-align:middle; ' >${this.approval_setting_jenis}</td>
									<td>	
                                    <a class="btn btn-primary" onclick="detailApproval('${this.approval_setting_id}')">${this.jumlah}</a>
                                    </td>
                                </tr>
                            `);
                        no++;
                    });
                } else {
                    $("#table_approval tbody").html('');
                }

                // $('#table_approval').DataTable();

            }
        });
    }

    function detailApproval(approval_setting_id) {
        $('#modalApproval').modal("show");
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/Approval/getApprovalByApprovalSettingId') ?>",
            data: {
                approval_setting_id
            },
            success: function(response) {
                let no = 1;
                let data = response.data;
                let gudang = response.gudang;
                let checker = response.checker;

                if ($.fn.DataTable.isDataTable('#table_detail_approval')) {
                    $('#table_detail_approval').DataTable().destroy();
                }

                $("#table_detail_approval tbody").empty();
                $("#table_detail_approval tbody").html('');

                if (gudang != 400) {
                    $("#showGudang").show();
                    $("#showChecker").show();
                    $("#showAsal").show();
                } else {
                    $("#showGudang").hide();
                    $("#showChecker").hide();
                    $("#showAsal").hide();
                }

                if (data.length > 0) {
                    $.each(data, function() {

                        $("#txt_jenis_pengajuan_detail").val(`${this.approval_reff_dokumen_jenis}`);
                        // <td style='vertical-align:middle; text-align: center;' ><a href='<?= base_url() ?>${this.url_doc}/?kode=${this.approval_reff_dokumen_kode}' target='_blank'>${this.approval_reff_dokumen_kode}</a></td>

                        var slcGudang = '';
                        var slcChecker = '';
                        if (gudang != 400 && checker != 400) {
                            $(gudang).each(function(i, v) {
                                slcGudang +=
                                    `<option value="${v.depo_detail_id}">${v.depo_detail_nama}</option>`
                            });

                            $(checker).each(function(i, v) {
                                slcChecker +=
                                    `<option value="${v.karyawan_id}">${v.karyawan_nama}</option>`
                            });
                        }

                        if (this.approval_reff_dokumen_kode.includes("RPK")) {

                            $('#table_detail_approval tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; text-align: center;' >${no}<input type="hidden" name="approval_id[]" value="${this.approval_id}" /></td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.tgl}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.diajukan_oleh}</td>
									${this.approval_reff_dokumen_jenis == 'Dokumen Pembongkaran / Pengemasan Barang' ? `<td style='vertical-align:middle; text-align: center;' ><a style="cursor: pointer;" onclick="detailSKU('${this.approval_reff_dokumen_id}')">${this.approval_reff_dokumen_kode}</a></td>` : `<td style='vertical-align:middle; text-align: center;' ><a href='<?= base_url() ?>${this.url_doc}/?kode=${this.approval_reff_dokumen_kode}' target='_blank'>${this.approval_reff_dokumen_kode}</a></td>`}
                                    <td style='vertical-align:middle; text-align: center;width:15%;'><button class="btn btn-md btn-primary" onclick="showHistory('${this.approval_reff_dokumen_kode}')">History</button></td>
									${this.approval_reff_dokumen_jenis == 'Dokumen Pembongkaran / Pengemasan Barang' ? `<td style='vertical-align:middle; text-align: center;' >${this.gudang_asal}</td>
                                    <td style='vertical-align:middle; text-align: center;' >
                                        <select id="gudangTujuan" class="form-control select2_approval gudangTujuan" name="gudangTujuan" disabled>
                                            <option value="${this.depo_detail_id_asal}">${this.gudang_asal}</option>
                                        </select>								
                                    </td>
									<td style='vertical-align:middle; text-align: center;width:15%;' >
                                        <select id="checker" class="form-control select2_approval checker" name="checker">
                                        <option value="">** <span name="CAPTION-CHECKER">Checker</span> **</option>
                                            ${slcChecker}
                                        </select>								
									</td>` : `<td hidden></td><td hidden></td>`}
                                    <td style='vertical-align:middle; text-align: center;'>	
										<div class="custom-checkbox-input">
											<input style="width:20px;height:20px" type='checkbox' value="0" name='approval[]' class="cly cl-${no} form-check-input-acc custom-color-white" id='approval-${no}' data-id-tr-konversi-sku="${this.approval_reff_dokumen_id}" onclick="approvalCheck(this.id,${no})"/>
										</div>
									</td>
									<td style='vertical-align:middle; text-align: center;'>
										<div class="custom-checkbox-input">
											<input style="width:20px;height:20px" type='checkbox' value="0" name='reject[]' class="cln cl-${no} form-check-input custom-color-white" id='reject-${no}' data-id-tr-konversi-sku="${this.approval_reff_dokumen_id}" onclick="approvalCheck(this.id,${no})"/>
										</div>
									</td>
                                    <td style='vertical-align:middle; text-align: center;width:25%'>
									    <textarea name="note[]" id="note[]" style="width:100%" cols="50" rows="1"></textarea>										
                                    </td>
                                </tr>
                            `);

                        } else {


                            $('#table_detail_approval tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; text-align: center;' >${no}<input type="hidden" name="approval_id[]" value="${this.approval_id}" /></td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.tgl}</td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.diajukan_oleh}</td>
									${this.approval_reff_dokumen_jenis == 'Dokumen Pembongkaran / Pengemasan Barang' ? `<td style='vertical-align:middle; text-align: center;' ><a style="cursor: pointer;" onclick="detailSKU('${this.approval_reff_dokumen_id}')">${this.approval_reff_dokumen_kode}</a></td>` : `<td style='vertical-align:middle; text-align: center;' ><a href='<?= base_url() ?>${this.url_doc}/?kode=${this.approval_reff_dokumen_kode}' target='_blank'>${this.approval_reff_dokumen_kode}</a></td>`}
                                    <td style='vertical-align:middle; text-align: center;width:15%;'><button class="btn btn-md btn-primary" onclick="showHistory('${this.approval_reff_dokumen_kode}')">History</button></td>
									${this.approval_reff_dokumen_jenis == 'Dokumen Pembongkaran / Pengemasan Barang' ? `<td style='vertical-align:middle; text-align: center;' >${this.gudang_asal}</td>
                                    <td style='vertical-align:middle; text-align: center;' >
                                        <select id="gudangTujuan" class="form-control select2_approval gudangTujuan" name="gudangTujuan">
                                            <option value="">** <span name="CAPTION-GUDANGTUJUAN">Gudang Tujuan</span> **</option>
                                            ${slcGudang}
                                        </select>								
                                    </td>
									<td style='vertical-align:middle; text-align: center;width:15%;' >
                                        <select id="checker" class="form-control select2_approval checker" name="checker">
                                        <option value="">** <span name="CAPTION-CHECKER">Checker</span> **</option>
                                            ${slcChecker}
                                        </select>								
									</td>` : `<td hidden></td><td hidden></td>`}
                                    <td style='vertical-align:middle; text-align: center;'>	
										<div class="custom-checkbox-input">
											<input style="width:20px;height:20px" type='checkbox' value="0" name='approval[]' class="cly cl-${no} form-check-input-acc custom-color-white" id='approval-${no}' data-id-tr-konversi-sku="${this.approval_reff_dokumen_id}" onclick="approvalCheck(this.id,${no})"/>
										</div>
									</td>
									<td style='vertical-align:middle; text-align: center;'>
										<div class="custom-checkbox-input">
											<input style="width:20px;height:20px" type='checkbox' value="0" name='reject[]' class="cln cl-${no} form-check-input custom-color-white" id='reject-${no}' data-id-tr-konversi-sku="${this.approval_reff_dokumen_id}" onclick="approvalCheck(this.id,${no})"/>
										</div>
									</td>
                                    <td style='vertical-align:middle; text-align: center;width:25%'>
									    <textarea name="note[]" id="note[]" style="width:100%" cols="50" rows="1"></textarea>										
                                    </td>
                                </tr>
                            `);

                        }
                        no++;
                    });
                } else {
                    $("#table_detail_approval tbody").html('');
                }

                $('#table_detail_approval').DataTable({
                    paging: false
                });

                $(".select2_approval").select2({
                    width: "100%"
                });

            }
        });

    }

    function detailSKU(id) {
        $('#modalApproval').modal("hide");

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/MainDashboard/getDataSKU') ?>",
            data: {
                id: id
            },
            dataType: "JSON",
            success: function(response) {
                $("#table-list-sku > tbody").empty();

                if (response.length > 0) {
                    $(response).each(function(i, v) {
                        $("#table-list-sku > tbody").append(`
							<tr>
								<td style="text-align: center; vertical-align: middle;">${i + 1}</td>
								<td style="text-align: center; vertical-align: middle;"><span class="sku-kode-label">${v.sku_kode}</span></td>
								<td style="text-align: center; vertical-align: middle;"><span class="sku-nama-produk-label">${v.sku_nama_produk}</span></td>
								<td style="text-align: center; vertical-align: middle;">${v.brand}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_kemasan}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_satuan}</td>
								<td style="text-align: center; vertical-align: middle;">${v.sku_stock_expired_date}</td>
								<td style="text-align: center; vertical-align: middle;"> ${v.tr_konversi_sku_detail_qty_plan}</td>
							</tr>
						`)
                    })
                } else {
                    $("#table-list-sku > tbody").append(`
							<tr>
								<td style="text-align: center; vertical-align: middle;" colspan="8">Data Kosong</td>
							</tr>
						`)
                }
                $('#modalListSKU').modal('show');
            }
        })
    }

    function closeModalListSKU() {
        $('#modalListSKU').modal('hide');
        $('#modalApproval').modal("show");
    }


    $("#saveApproval").prop('disabled', true);
    // mengatasi checkbox double
    function approvalCheck(id, no) {
        $("#saveApproval").prop('disabled', true);

        var cly = $(".cly").length;
        var cln = $(".cln").length;

        var isdisabled = 0;

        for (i = 0; i < cln; i++) {
            if ($(".cln").eq(i).prop('checked') == true) {
                isdisabled++;
            }
        }

        for (i = 0; i < cly; i++) {
            if ($(".cly").eq(i).prop('checked') == true) {
                isdisabled++;
            }
        }

        if (isdisabled > 0) {
            $("#saveApproval").prop('disabled', false);
        }
        //jika cheked maka val = 1
        if (document.getElementById(id).checked) {
            $("#" + id + "").val(1)
        } else {
            $("#" + id + "").val(0)
        }
        $('.cl-' + no + '').not("#" + id + "").prop('checked', false);
    }

    function showHistory(approval_reff_dokumen_kode) {
        $('#modalHistoryApproval').modal("show");
        $.ajax({
            type: 'GET',
            url: "<?= base_url('WMS/Approval/getHistoryApproval') ?>",
            data: {
                approval_reff_dokumen_kode: approval_reff_dokumen_kode
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
                        $("#txt_jenis_pengajuan_detail").val(`${this.approval_reff_dokumen_jenis}`);
                        if (this.approval_status == "Approved") {
                            var color = "style='background-color:green;color:white'"
                        } else {
                            var color = "style='background-color:red;color:white'"
                        }
                        $('#tableHistoryApproval tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; text-align: center;' >${no}<input type="hidden" name="approval_id[]" value="${this.approval_id}" /></td>
                                    <td style='vertical-align:middle; text-align: center;' >${this.tgl}</td>
                                    <td style='vertical-align:middle; text-align: center;width:10%;' >${this.approval_reff_dokumen_kode}</td>
                                    <td style='vertical-align:middle; text-align: center;' ><a ${color} class="btn btn-md">${this.approval_status}<a/></td>
                                    <td style='vertical-align:middle; text-align: center;width:15%;' >${this.karyawan_nama}</td>
                                    <td style='vertical-align:middle; text-align: center;width:25%;' >${this.approval_keterangan}</td>
																		<td style='vertical-align:middle; text-align: center;' >${this.approval_group_nama}</td>
                                </tr>
                            `);
                        no++;
                    });
                } else {
                    $("#tableHistoryApproval tbody").html('');
                }

                // $('#table_detail_approval').DataTable({
                //     paging: false
                // });

            }
        });
    }
    $('input[type="checkbox"]').on('click', function() {
        // alert('asdasd')
        $('input[name="' + this.name + '"]').not(this).prop('checked', false);
    });

    $('#saveApproval').click(function(e) {
        var lengthGudangTujuan = $(".gudangTujuan").length;
        var lengthChecker = $(".checker").length;

        // data header
        var approval_id = $("input[name='approval_id[]']").map(function() {
            return $(this).val();
        }).get();
        let approv_check = $("input[name='approval[]']").map(function() {
            return parseInt($(this).val());
        }).get();
        let reject_check = $("input[name='reject[]']").map(function() {
            return parseInt($(this).val());
        }).get()
        let note = $("[name='note[]']").map(function() {
            return $(this).val();
        }).get();

        if (lengthGudangTujuan > 0) {
            var gudang = $(".gudangTujuan").map(function() {
                return $(this).val();
            }).get();
            var checker = $(".checker").map(function() {
                return $(this).val();
            }).get();
        }


        let dataAcc = [];
        let dataReject = [];
        let error = 0;
        //cari data approval yg di acc
        approval_id.forEach((value, index) => {
            // var keterangan = null
            // console.log(note[index]);
            if (note[index] == "undefined") {
                var keterangan = null
            } else {
                var keterangan = note[index]
            }
            if (approv_check[index] > 0) {
                if (lengthGudangTujuan > 0) {
                    if (gudang[index] == "" || checker[index] == "") {
                        error = 1;
                    }

                    dataAcc.push({
                        approval_id: value,
                        approval_keterangan: keterangan,
                        gudang: gudang[index],
                        checker: checker[index],
                        tr_konversi_sku_id: $("#approval-" + [index + 1] + "").attr(
                            'data-id-tr-konversi-sku')
                    });
                } else {
                    dataAcc.push({
                        approval_id: value,
                        approval_keterangan: keterangan,
                        gudang: "",
                        checker: "",
                        tr_konversi_sku_id: ""
                    });
                }


            }
            if (reject_check[index] > 0) {
                if (lengthGudangTujuan > 0) {
                    if (gudang[index] == "" || checker[index] == "") {
                        error = 1;
                    }

                    dataReject.push({
                        approval_id: value,
                        approval_keterangan: keterangan,
                        gudang: gudang[index],
                        checker: checker[index],
                        tr_konversi_sku_id: $("#reject-" + [index + 1] + "").attr(
                            'data-id-tr-konversi-sku')
                    });
                } else {
                    dataReject.push({
                        approval_id: value,
                        approval_keterangan: keterangan,
                        gudang: "",
                        checker: "",
                        tr_konversi_sku_id: ""
                    });
                }
            }
        });

        if (error > 0) {
            Swal.fire(
                'Error!',
                'Gudang Tujuan dan Checker tidak boleh kosong',
                'warning'
            );

            return false;
        }

        Swal.fire({
            title: 'Apakah anda yakin menyimpan perubahan ?',
            text: "Pastikan data sudah benar !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes'
        }).then((result) => {
            if (result.value == true) {
                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('WMS/Approval/ApprovalProses') ?>",
                    data: {
                        dataAcc: dataAcc,
                        dataReject: dataReject,
                        mode: lengthGudangTujuan > 0 ? 1 : 0 // 1 update permbongkaran, 0 no action
                    },
                    async: "true",
                    beforeSend: function() {
                        $("#saveApproval").prop('disabled', true);
                    },
                    dataType: "json",
                    success: function(response) {
                        console.log(response);
                        if (response.status == 1) {
                            Swal.fire(
                                'Success!',
                                'Data berhasil diupdate.',
                                'success'
                            )
                            setTimeout(function() {
                                window.location.href =
                                    "<?= base_url('WMS/MainDashboard/MainDashboardMenu') ?>";
                            }, 3000);
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'warning'
                            )
                            $('#saveApproval').prop('disabled', false);
                        }
                    }
                });
            }
        })
        console.log(dataAcc);
        console.log(dataReject);

    });

    function titleCase(string) {
        var sentence = string.toLowerCase().split(" ");
        for (var i = 0; i < sentence.length; i++) {
            sentence[i] = sentence[i][0].toUpperCase() + sentence[i].slice(1);
        }

        $("#lbtitleoutlet").html(sentence.join(" ") + " Dashboard");

    }
</script>