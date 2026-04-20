td<script type="text/javascript">
    var arr_report = [];
    var temp_report = [];
    var barChartReport1 = '';
    var barChartReport2 = '';
    var perusahaanChartReport = '';
    var principleChartReport = '';
    var gudangChartReport = '';
    var flag_report = '';
    var tabs = '';
    var status_assign = '';
    var modeReport = '';
    var tanggal = '';
    var value = '';
    var textChart1 = '';
    var textChart2 = '';
    var textChart3 = '';
    var area = '';
    var kendaraan_model = '';

    loadingBeforeReadyPage()
    var Outlet = '<?= $_SESSION['depo_nama'] ?>';
    var DepoID = '<?= $_SESSION['depo_id'] ?>';

    $(document).ready(function() {
        changeTab('dashboard_utama');
    });

    // $(document).ready(function() {
    //     var Outlet = '<?= $_SESSION['depo_nama'] ?>';
    //     var DepoID = '<?= $_SESSION['depo_id'] ?>';

    //     titleCase(Outlet);

    //     // var mode = $("#filter_mode option:selected").val();
    //     // if (mode == 0) {
    //     //     // if ($('#filterstatus').length > 0) {
    //     //     //     $('#filterstatus').daterangepicker({
    //     //     //         'applyClass': 'btn-sm btn-success',
    //     //     //         'cancelClass': 'btn-sm btn-default',
    //     //     //         locale: {
    //     //     //             "format": "DD/MM/YYYY",
    //     //     //             applyLabel: 'Apply',
    //     //     //             cancelLabel: 'Cancel',
    //     //     //         },
    //     //     //         'startDate': new Date(),
    //     //     //         'endDate': new Date(),
    //     //     //     });
    //     //     // }

    //     //     $('#filterstatus').on('apply.daterangepicker', function(ev, picker) {
    //     //         GetCountByStatusDORealTime(DepoID, 1)
    //     //     })
    //     // }

    //     // var timeout = setTimeout(function() {
    //     //     GetCountByStatusDORealTime(DepoID, 1)
    //     // }, 3000);

    //     countStatusDO();

    //     GetDataApproval();

    //     const urlSearchParams = new URLSearchParams(window.location.search);
    //     const approvalName = Object.fromEntries(urlSearchParams.entries()).approvalName;

    //     // resetUrlPushState();
    //     if (typeof approvalName !== 'undefined') {
    //         detailApproval(approvalName).click();
    //     }

    //     initDataListSKUNotInWMS()

    // });

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

    function showListDataByCard(modul) {
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
            url: "<?= base_url('WMS/MainDashboard/listDataByCard') ?>",
            data: {
                modul: modul,
                Tgl_By_Status: Tgl_By_Status,
            },
            dataType: "JSON",
            success: function(response) {
                Swal.close();

                $("#titleDetailShowCard").html('List Data ' +
                    modul);

                if ($.fn.DataTable.isDataTable('#table-detailShowCard')) {
                    $('#table-detailShowCard').DataTable().clear().destroy();
                }

                $('#table-detailShowCard thead').empty();
                $('#table-detailShowCard tbody').empty();

                if (modul == 'PengeluaranBarang') {
                    $("#table-detailShowCard thead").append(`
                            <tr>
								<th class="text-center" name="CAPTION-KODE">Kode</th>
								<th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
								<th class="text-center" name="CAPTION-KARYAWAN">Karyawan</th>
								<th class="text-center" name="CAPTION-Status">Status</th>
							</tr>
                    `)

                    $.each(response, function(i, v) {
                        $("#table-detailShowCard tbody").append(`
                            <tr>
                            <td class="text-center">${v.kode}</td>
                            <td class="text-center">${v.tgl}</td>
                            <td class="text-center">${v.karyawan_nama}</td>
                            <td class="text-center">${v.status}</td>
                            </tr>
                        `)
                    })
                } else if (modul == 'MutasiPallet') {
                    $("#table-detailShowCard thead").append(`
                            <tr>
                            <th class="text-center" name="CAPTION-KODE">Kode</th>
                            <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
                            <th class="text-center" name="CAPTION-CHECKER">Checker</th>
							<th class="text-center" name="CAPTION-Status">Status</th>
							</tr>
                    `)

                    $.each(response, function(i, v) {
                        $("#table-detailShowCard tbody").append(`
                            <tr>
                                <td class="text-center">${v.kode}</td>
                                <td class="text-center">${v.tgl}</td>
                                <td class="text-center">${v.tr_mutasi_pallet_draft_nama_checker}</td>
                                <td class="text-center">${v.status}</td>
                            </tr>
                        `)
                    })
                } else if (modul == 'KoreksiBarang') {
                    $("#table-detailShowCard thead").append(`
                            <tr>
                            <th class="text-center" name="CAPTION-KODE">Kode</th>
                            <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
                            <th class="text-center" name="CAPTION-CHECKER">Checker</th>
                            <th class="text-center" name="CAPTION-PENGEMUDI">Pengemudi</th>
                            <th class="text-center" name="CAPTION-KENDARAAN">Kendaraan</th>
                            <th class="text-center" name="CAPTION-NOPOL">Nopol</th>
                            <th class="text-center" name="CAPTION-TIPEDOKUMEN">Tipe Dokumen</th>
                            <th class="text-center" name="CAPTION-TIPEMUTASI">Tipe Mutasi</th>
							<th class="text-center" name="CAPTION-Status">Status</th>
							</tr>
                    `)

                    $.each(response, function(i, v) {
                        $("#table-detailShowCard tbody").append(`
                            <tr>
                                <td class="text-center">${v.kode}</td>
                                <td class="text-center">${v.tgl}</td>
                                <td class="text-center">${v.tr_mutasi_pallet_draft_nama_checker}</td>
                                <td class="text-center">${v.tr_koreksi_stok_draft_pengemudi}</td>
                                <td class="text-center">${v.tr_koreksi_stok_draft_kendaraan}</td>
                                <td class="text-center">${v.tr_koreksi_stok_draft_nopol}</td>
                                <td class="text-center">${v.tipe_dokumen}</td>
                                <td class="text-center">${v.tipe_mutasi_nama}</td>
                                <td class="text-center">${v.status}</td>
                            </tr>
                        `)
                    })
                } else if (modul == 'Pemusnahan') {
                    $("#table-detailShowCard thead").append(`
                            <tr>
                            <th class="text-center" name="CAPTION-KODE">Kode</th>
                            <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
                            <th class="text-center" name="CAPTION-CHECKER">Checker</th>
                            <th class="text-center" name="CAPTION-PENGEMUDI">Pengemudi</th>
                            <th class="text-center" name="CAPTION-KENDARAAN">Kendaraan</th>
                            <th class="text-center" name="CAPTION-NOPOL">Nopol</th>
                            <th class="text-center" name="CAPTION-TIPEDOKUMEN">Tipe Dokumen</th>
                            <th class="text-center" name="CAPTION-TIPEMUTASI">Tipe Mutasi</th>
							<th class="text-center" name="CAPTION-Status">Status</th>
							</tr>
                    `)

                    $.each(response, function(i, v) {
                        $("#table-detailShowCard tbody").append(`
                            <tr>
                                <td class="text-center">${v.kode}</td>
                                <td class="text-center">${v.tgl}</td>
                                <td class="text-center">${v.tr_koreksi_stok_draft_nama_checker}</td>
                                <td class="text-center">${v.tr_koreksi_stok_draft_pengemudi}</td>
                                <td class="text-center">${v.tr_koreksi_stok_draft_kendaraan}</td>
                                <td class="text-center">${v.tr_koreksi_stok_draft_nopol}</td>
                                <td class="text-center">${v.tipe_dokumen}</td>
                                <td class="text-center">${v.tipe_mutasi_nama}</td>
                                <td class="text-center">${v.status}</td>
                            </tr>
                        `)
                    })
                } else if (modul == 'PermintaanBarang') {
                    $("#table-detailShowCard thead").append(`
                            <tr>
                            <th class="text-center" name="CAPTION-KODE">Kode</th>
                            <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
							<th class="text-center" name="CAPTION-Status">Status</th>
							</tr>
                    `)

                    $.each(response, function(i, v) {
                        $("#table-detailShowCard tbody").append(`
                            <tr>
                                <td class="text-center">${v.kode}</td>
                                <td class="text-center">${v.tgl}</td>
                                <td class="text-center">${v.status}</td>
                            </tr>
                        `)
                    })
                } else if (modul == 'PengemasanBarang') {
                    $("#table-detailShowCard thead").append(`
                            <tr>
                            <th class="text-center" name="CAPTION-KODE">Kode</th>
                            <th class="text-center" name="CAPTION-TANGGAL">Tanggal Kirim</th>
                            <th class="text-center" name="CAPTION-DOBATCHKODE">DO Batch Kode</th>
                            <th class="text-center" name="CAPTION-PICKINGORDERKODE">Picking Order Kode</th>
							<th class="text-center" name="CAPTION-Status">Status</th>
							</tr>
                    `)

                    $.each(response, function(i, v) {
                        $("#table-detailShowCard tbody").append(`
                            <tr>
                                <td class="text-center">${v.kode}</td>
                                <td class="text-center">${v.tgl}</td>
                                <td class="text-center">${v.delivery_order_batch_kode}</td>
                                <td class="text-center">${v.picking_order_kode}</td>
                                <td class="text-center">${v.status}</td>
                            </tr>
                        `)
                    })
                } else if (modul == 'KoreksiPallet') {
                    $("#table-detailShowCard thead").append(`
                            <tr>
                            <th class="text-center" name="CAPTION-KODE">Kode</th>
                            <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
							<th class="text-center" name="CAPTION-Status">Status</th>
							</tr>
                    `)

                    $.each(response, function(i, v) {
                        $("#table-detailShowCard tbody").append(`
                            <tr>
                                <td class="text-center">${v.kode}</td>
                                <td class="text-center">${v.tgl}</td>
                                <td class="text-center">${v.status}</td>
                            </tr>
                        `)
                    })
                } else if (modul == 'StockOpname') {
                    $("#table-detailShowCard thead").append(`
                            <tr>
                            <th class="text-center" name="CAPTION-KODE">Kode</th>
                            <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
                            <th class="text-center" name="CAPTION-TIPESTOK">Tipe Stok</th>
							<th class="text-center" name="CAPTION-Status">Status</th>
							</tr>
                    `)

                    $.each(response, function(i, v) {
                        $("#table-detailShowCard tbody").append(`
                            <tr>
                                <td class="text-center">${v.kode}</td>
                                <td class="text-center">${v.tgl}</td>
                                <td class="text-center">${v.tipe_stok}</td>
                                <td class="text-center">${v.status}</td>
                            </tr>
                        `)
                    })
                } else if (modul == 'PenerimaanBarang') {
                    $("#table-detailShowCard thead").append(`
                            <tr>
                            <th class="text-center" name="CAPTION-KODE">Kode</th>
                            <th class="text-center" name="CAPTION-TANGGAL">Tanggal</th>
                            <th class="text-center" name="CAPTION-PENGEMUDI">Pengemudi</th>
                            <th class="text-center" name="CAPTION-NOPOL">Nopol</th>
							<th class="text-center" name="CAPTION-Status">Status</th>
							</tr>
                    `)

                    $.each(response, function(i, v) {
                        $("#table-detailShowCard tbody").append(`
                            <tr>
                                <td class="text-center">${v.kode}</td>
                                <td class="text-center">${v.tgl}</td>
                                <td class="text-center">${v.penerimaan_pembelian_pengemudi}</td>
                                <td class="text-center">${v.penerimaan_pembelian_nopol}</td>
                                <td class="text-center">${v.status}</td>
                            </tr>
                        `)
                    })
                }



                $('#table-detailShowCard').DataTable({
                    "lengthMenu": [
                        [50],
                        [50]
                    ]
                });

                $("#modal-listShowDetailByCard").modal('show');
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
        var Tgl_By_Status = $("#filter_tgl_status").val();

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

    document.querySelectorAll('.toggleBarPerusahaan1 input[type="checkbox"]').forEach(function(el) {
        el.addEventListener('change', function() {
            perusahaanChartReport.data.datasets[1].hidden = !this.checked;
            perusahaanChartReport.update();

            // Update label text (jQuery)
            if (!this.checked) {
                $(".titleBarPerusahaan1").html(`<del>${textChart1}</del>`);
            } else {
                $(".titleBarPerusahaan1").html(`${textChart1}`);
            }
        });
    });

    document.querySelectorAll('.toggleBarPerusahaan2 input[type="checkbox"]').forEach(function(el) {
        el.addEventListener('change', function() {
            perusahaanChartReport.data.datasets[2].hidden = !this.checked;
            perusahaanChartReport.update();

            // Update label text (jQuery)
            if (!this.checked) {
                $(".titleBarPerusahaan2").html(`<del>${textChart2}</de>`);
            } else {
                $(".titleBarPerusahaan2").html(`${textChart2}`);
            }
        });
    });

    document.querySelectorAll('.toggleLinePerusahaan input[type="checkbox"]').forEach(function(el) {
        el.addEventListener('change', function() {
            perusahaanChartReport.data.datasets[0].hidden = !this.checked;
            perusahaanChartReport.update();

            // Update label text (jQuery)
            if (!this.checked) {
                $(".titleLinePerusahaan").html(`<del>${textChart3}</del>`);
            } else {
                $(".titleLinePerusahaan").html(`${textChart3}`);
            }
        });
    });

    document.querySelectorAll('.toggleBarPrinciple1 input[type="checkbox"]').forEach(function(el) {
        el.addEventListener('change', function() {
            principleChartReport.data.datasets[1].hidden = !this.checked;
            principleChartReport.update();

            // Update label text (jQuery)
            if (!this.checked) {
                $(".titleBarPrinciple1").html(`<del>${textChart1}</del>`);
            } else {
                $(".titleBarPrinciple1").html(`${textChart1}`);
            }
        });
    });

    document.querySelectorAll('.toggleBarPrinciple2 input[type="checkbox"]').forEach(function(el) {
        el.addEventListener('change', function() {
            principleChartReport.data.datasets[2].hidden = !this.checked;
            principleChartReport.update();

            // Update label text (jQuery)
            if (!this.checked) {
                $(".titleBarPrinciple2").html(`<del>${textChart2}</de>`);
            } else {
                $(".titleBarPrinciple2").html(`${textChart2}`);
            }
        });
    });

    document.querySelectorAll('.toggleLinePrinciple input[type="checkbox"]').forEach(function(el) {
        el.addEventListener('change', function() {
            principleChartReport.data.datasets[0].hidden = !this.checked;
            principleChartReport.update();

            // Update label text (jQuery)
            if (!this.checked) {
                $(".titleLinePrinciple").html(`<del>${textChart3}</del>`);
            } else {
                $(".titleLinePrinciple").html(`${textChart3}`);
            }
        });
    });

    $(document).on("change", ".toggleDonut", function() {
        let index = $(this).data("value");
        let text = $(this).data("text");
        let isChecked = $(this).is(":checked");

        // Ambil elemen chart berdasarkan index
        let chartElement = gudangChartReport.getDatasetMeta(0).data[index];

        if (chartElement) {
            chartElement.hidden = !isChecked; // Hide/show elemen tanpa mengganti data
        }
        // Update chart agar perubahan langsung terlihat
        gudangChartReport.update();

        // Update label text (jQuery)
        if (!isChecked) {
            $(`.textChartDonut_${index}`).html(`<del>${text}</del>`);
        } else {
            $(`.textChartDonut_${index}`).html(`${text}`);
        }
    });

    function setDateRange() {
        // Mengisi input daterangepicker
        $('#filter_tanggal_truck_supplier').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                "format": "DD/MM/YYYY",
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            },
            'startDate': '<?= date("01-m-Y") ?>',
            'endDate': new Date(),
        });

        $('#filter_tanggal_fleet_productivity').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                "format": "DD/MM/YYYY",
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            },
            'startDate': '<?= date("01-m-Y") ?>',
            'endDate': new Date(),
        });

        $('#filter_tanggal_sj_supplier').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                "format": "DD/MM/YYYY",
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            },
            'startDate': '<?= date("01-m-Y") ?>',
            'endDate': new Date(),
        });

        $('#filter_tanggal_service_level').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                "format": "DD/MM/YYYY",
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            },
            'startDate': '<?= date("01-m-Y") ?>',
            'endDate': new Date(),
        });

        $('#filter_tanggal_status_do').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                "format": "DD/MM/YYYY",
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            },
            'startDate': '<?= date("01-m-Y") ?>',
            'endDate': new Date(),
        });

        $('#filter_tanggal_stock').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                "format": "DD/MM/YYYY",
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            },
            'startDate': '<?= $lastTbg ?>',
            'endDate': '<?= $lastTbg ?>',
        });
    }

    function changeTanggalMasuk() {
        if (tabs == 'dashboard_truck_supplier') {
            filterReportTruckSupplier('filter_perusahaan', null);
        } else if (tabs == 'dashboard_fleet_productivity') {
            filterReportFleetProductivity('status_kendaraan', null);
        } else if (tabs == 'dashboard_sj_supplier') {
            filterReportSJSupplier('filter_perusahaan', null);
        } else if (tabs == 'dashboard_service_level') {
            filterReportServiceLevel('filter_chart_1', null);
        } else if (tabs == 'dashboard_status_do') {
            filterReportStatusDO('filter_chart_1', null);
        }
    }

    function changeTab(tab) {
        tabs = tab;
        if (tab == 'dashboard_utama') {
            var Outlet = '<?= $_SESSION['depo_nama'] ?>';
            var DepoID = '<?= $_SESSION['depo_id'] ?>';

            titleCase(Outlet);

            countStatusDO();

            GetDataApproval();

            const urlSearchParams = new URLSearchParams(window.location.search);
            const approvalName = Object.fromEntries(urlSearchParams.entries()).approvalName;

            // resetUrlPushState();
            if (typeof approvalName !== 'undefined') {
                detailApproval(approvalName).click();
            }

            initDataListSKUNotInWMS()
        } else if (tab == 'dashboard_doi') {
            $(".titleBarPerusahaan1, .titleBarPrinciple1").html('Stok');
            $(".titleBarPerusahaan2, .titleBarPrinciple2").html('AVG Sales');
            $(".titleLinePerusahaan, .titleLinePrinciple").html('DOI');
            textChart1 = 'Stok';
            textChart2 = 'AVG Sales';
            textChart3 = 'DOI';

            keterangan('dashboard_doi')

            $("#filter_client_wms_report_doi").trigger('change');
        } else if (tab == 'dashboard_sj_supplier') {
            $(".titleBarPerusahaan1, .titleBarPrinciple1").html('Penerimaan SJ');
            $(".titleBarPerusahaan2, .titleBarPrinciple2").html('Penerimaan Barang');
            $(".titleLinePerusahaan, .titleLinePrinciple").html('Persentase');
            textChart1 = 'Penerimaan SJ';
            textChart2 = 'Penerimaan Barang';
            textChart3 = 'Persentase';

            setDateRange();

            keterangan('dashboard_sj_vs_penerimaan')

            filterReportSJSupplier('filter_perusahaan', null);
        } else if (tab == 'dashboard_truck_supplier') {
            $(".titleBarPerusahaan1, .titleBarPrinciple1").html('Jumlah Truk');
            $(".titleBarPerusahaan2, .titleBarPrinciple2").html('Selesai Bongkar');
            $(".titleLinePerusahaan, .titleLinePrinciple").html('Persentase');
            textChart1 = 'Jumlah Truk';
            textChart2 = 'Selesai Bongkar';
            textChart3 = 'Persentase';

            setDateRange();

            keterangan('dashboard_truck_supplier')

            filterReportTruckSupplier('filter_perusahaan', null);
        } else if (tab == 'dashboard_stock') {
            setDateRange();

            keterangan('dashboard_stock')

            filterReportStock('filter_perusahaan', null);
        } else if (tab == 'dashboard_service_level') {
            keterangan('dashboard_service_level')

            setDateRange();

            filterReportServiceLevel('filter_chart_1', null);
        } else if (tab == 'dashboard_status_do') {
            keterangan('dashboard_status_do')

            setDateRange();

            filterReportStatusDO('filter_chart_1', null);
        } else if (tab == 'dashboard_fleet_productivity') {
            setDateRange();

            keterangan('dashboard_fleet_productivity');

            filterReportFleetProductivity('status_kendaraan', null);
        }
    }

    function filterReportDOI(flag, label) {
        var client_wms = $("#filter_client_wms_report_doi option:selected").val();
        var doi_internal = $("#filter_doi_internal").val();
        var filter_month = $("#filter_month").val();
        var principle = null;

        if (flag == 'filter_perusahaan') {
            if (client_wms == '') {
                flag_report = 'filter_all_perusahaan'
                $("#nama_perusahaan").text('Perusahaan');
                $("#show_perusahaan_report_doi").show();
                $("#show_principle_report_doi").hide();
                $("#show_sku_report_doi").hide();
            } else {
                flag_report = 'filter_by_perusahaan'
                $("#nama_principle").text(client_wms);
                $("#show_perusahaan_report_doi").hide();
                $("#show_principle_report_doi").show();
                $("#show_sku_report_doi").hide();
            }
        } else {
            flag_report = flag;
            if (flag == 'canvas_perusahaan') {
                client_wms = label;
                $("#nama_principle").text(client_wms);
                $("#show_perusahaan_report_doi").show();
                $("#show_principle_report_doi").show();
                $("#show_sku_report_doi").hide();
            } else {
                client_wms = $("#nama_principle").text();
                principle = label;
                // $("#nama_principle").text(label);
                $("#principle_sku_doi").text(label);
                $("#show_principle_report_doi").show();
                $("#show_sku_report_doi").show();
            }
        }

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MainDashboard/getReportDashboardDOI') ?>",
            data: {
                client_wms: client_wms,
                principle: principle,
                doi_internal: doi_internal,
                filter_month: filter_month
            },
            dataType: "JSON",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                arr_report = response;

                report_dashboard_doi();
            },
            complete: function() {
                swal.close();
            }
        })
    }

    function filterReportSJSupplier(flag, label) {
        var client_wms = '';
        var principle = null;
        modeReport = $("#filter_mode_sj_supplier option:selected").val();
        tanggal = $("#filter_tanggal_sj_supplier").val();


        if (flag == 'filter_perusahaan') {
            flag_report = 'filter_all_perusahaan'
            $("#nama_perusahaan_sj_supplier").text('Perusahaan');
            $("#show_perusahaan_sj_supplier").show();
            $("#show_principle_sj_supplier").hide();
            $("#show_sku_sj_supplier").hide();
        } else {
            flag_report = flag;
            if (flag == 'canvas_perusahaan') {
                client_wms = label;
                $("#nama_principle_sj_supplier").text(client_wms);
                $("#show_perusahaan_sj_supplier").show();
                $("#show_principle_sj_supplier").show();
                $("#show_sku_sj_supplier").hide();
            } else {
                client_wms = $("#nama_principle_sj_supplier").text();
                principle = label;
                // $("#nama_principle_sj_supplier").text(label);
                $("#principle_sku_sj_supplier").text(label);
                $("#show_principle_sj_supplier").show();
                $("#show_sku_sj_supplier").show();
            }
        }

        if (modeReport == 'daily' && $("#filter_tanggal_sj_supplier").prop('disabled')) {
            $("#filter_tanggal_sj_supplier").removeAttr('disabled', true);
            $("#nama_perusahaan_sj_supplier").text('Perusahaan');
            $("#show_perusahaan_sj_supplier").hide();
            $("#show_principle_sj_supplier").hide();
            $("#show_sku_sj_supplier").hide();
            return false;
        } else if (modeReport == 'mtd') {
            setDateRange();
            $("#filter_tanggal_sj_supplier").attr('disabled', true);
        }

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MainDashboard/getDashboardSJSupplier') ?>",
            data: {
                client_wms: client_wms,
                principle: principle,
                mode: modeReport,
                tanggal: tanggal
            },
            dataType: "JSON",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                arr_report = response;

                report_dashboard_sj_supplier();
            },
            complete: function() {
                swal.close();
            }
        })
    }

    function filterReportTruckSupplier(flag, label) {
        var client_wms = '';
        var principle = null;
        modeReport = $("#filter_mode_truck_supplier option:selected").val();
        tanggal = $("#filter_tanggal_truck_supplier").val();


        if (flag == 'filter_perusahaan') {
            flag_report = 'filter_all_perusahaan'
            $("#nama_perusahaan_truck_supplier").text('Perusahaan');
            $("#show_perusahaan_truck_supplier").show();
            $("#show_principle_truck_supplier").hide();
            $("#show_sku_truck_supplier").hide();
        } else {
            flag_report = flag;
            if (flag == 'canvas_perusahaan') {
                client_wms = label;
                $("#nama_principle_truck_supplier").text(client_wms);
                $("#show_perusahaan_truck_supplier").show();
                $("#show_principle_truck_supplier").show();
                $("#show_sku_truck_supplier").hide();
            } else {
                client_wms = $("#nama_principle_truck_supplier").text();
                principle = label;
                $("#principle_list_kedatangan_truck").text(label);
                $("#show_principle_truck_supplier").show();
                $("#show_sku_truck_supplier").show();
            }
        }

        if (modeReport == 'daily' && $("#filter_tanggal_truck_supplier").prop('disabled')) {
            $("#filter_tanggal_truck_supplier").removeAttr('disabled', true);
            $("#nama_perusahaan_truck_supplier").text('Perusahaan');
            $("#show_perusahaan_truck_supplier").hide();
            $("#show_principle_truck_supplier").hide();
            $("#show_sku_truck_supplier").hide();
            return false;
        } else if (modeReport == 'mtd') {
            setDateRange();
            $("#filter_tanggal_truck_supplier").attr('disabled', true);
        }

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MainDashboard/getDashboardTruckSupplier') ?>",
            data: {
                client_wms: client_wms,
                principle: principle,
                mode: modeReport,
                tanggal: tanggal
            },
            dataType: "JSON",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                arr_report = response;

                report_dashboard_truck_supplier();
            },
            complete: function() {
                swal.close();
            }
        })
    }

    function filterReportStock(flag, label) {
        var client_wms = '';
        var principle = null;
        var depo_detail = null;

        if (flag == 'filter_perusahaan') {
            flag_report = 'filter_all_perusahaan'
            $("#nama_perusahaan_stock").text('Perusahaan');
            $("#show_perusahaan_stock").show();
            $("#show_principle_stock").hide();
            $("#show_gudang_stock").hide();
            $("#show_sku_stock").hide();
        } else {
            flag_report = flag;
            if (flag == 'canvas_perusahaan') {
                client_wms = label;
                $("#nama_principle_stock").text(client_wms);
                $("#show_perusahaan_stock").show();
                $("#show_principle_stock").show();
                $("#show_gudang_stock").hide();
                $("#show_sku_stock").hide();
            } else if (flag == 'canvas_principle') {
                client_wms = $("#nama_principle_stock").text();
                principle = label;
                $("#nama_gudang_stock").text(principle);
                $("#show_principle_stock").show();
                $("#show_gudang_stock").show();
                $("#show_sku_stock").hide();
            } else {
                client_wms = $("#nama_principle_stock").text();
                principle = $("#nama_gudang_stock").text();
                depo_detail = label;
                $("#gudang_sku_stock").text(depo_detail);
                $("#show_principle_stock").show();
                $("#show_gudang_stock").show();
                $("#show_sku_stock").show();
            }
        }

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MainDashboard/getDashboardStock') ?>",
            data: {
                client_wms: client_wms,
                principle: principle,
                depo_detail: depo_detail
            },
            dataType: "JSON",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                arr_report = response;

                report_dashboard_stock();
            },
            complete: function() {
                swal.close();
            }
        })
    }

    function filterReportServiceLevel(flag, label) {
        var client_wms = '';
        var principle = null;
        var depo_detail = null;
        var tipe = null;
        modeReport = $("#filter_mode_service_level option:selected").val();
        tanggal = $("#filter_tanggal_service_level").val();

        if (flag == 'filter_chart_1') {
            flag_report = 'filter_chart_1'
            $("#show_tipe_service_level").show();
            $("#show_perusahaan_service_level").hide();
            $("#show_principle_service_level").hide();
            $("#show_so_service_level").hide();
        } else if (flag == 'canvas_tipe') {
            flag_report = flag
            tipe = label
            $("#nama_perusahaan_service_level").text(label);
            $("#show_perusahaan_service_level").show();
            $("#show_principle_service_level").hide();
            $("#show_so_service_level").hide();
        } else {
            flag_report = flag;
            tipe = $("#nama_perusahaan_service_level").text();
            if (flag == 'canvas_perusahaan') {
                client_wms = label;
                $("#nama_principle_service_level").text(client_wms);
                $("#show_perusahaan_service_level").show();
                $("#show_principle_service_level").show();
                $("#show_so_service_level").hide();
            } else if (flag == 'canvas_principle') {
                client_wms = $("#nama_principle_service_level").text();
                principle = label;
                $("#so_service_level").text(principle);
                $("#show_so_service_level").show();
            }
        }

        if (modeReport == 'daily' && $("#filter_tanggal_service_level").prop('disabled')) {
            $("#filter_tanggal_service_level").removeAttr('disabled', true);
            $("#show_perusahaan_service_level").hide();
            $("#show_principle_service_level").hide();
            $("#show_so_service_level").hide();
            return false;
        } else if (modeReport == 'mtd') {
            setDateRange();
            $("#filter_tanggal_service_level").attr('disabled', true);
        }

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MainDashboard/getDashboardServiceLevel') ?>",
            data: {
                client_wms: client_wms,
                principle: principle,
                tipe: tipe,
                mode: modeReport,
                tanggal: tanggal
            },
            dataType: "JSON",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                arr_report = response;

                report_dashboard_service_level();
            },
            complete: function() {
                swal.close();
            }
        })
    }

    function filterReportStatusDO(flag, label) {
        var client_wms = '';
        var principle = null;
        var depo_detail = null;
        var status = null;
        modeReport = $("#filter_mode_status_do option:selected").val();
        tanggal = $("#filter_tanggal_status_do").val();

        if (flag == 'filter_chart_1') {
            flag_report = 'filter_chart_1'
            $("#show_status_status_do").show();
            $("#show_perusahaan_status_do").hide();
            $("#show_principle_status_do").hide();
            $("#show_do_status_do").hide();
        } else if (flag == 'canvas_status') {
            flag_report = flag
            status = label
            $("#nama_perusahaan_status_do").text(label);
            $("#show_perusahaan_status_do").show();
            $("#show_principle_status_do").hide();
            $("#show_do_status_do").hide();
        } else {
            flag_report = flag;
            status = $("#nama_perusahaan_status_do").text();
            if (flag == 'canvas_perusahaan') {
                client_wms = label;
                $("#nama_principle_status_do").text(client_wms);
                $("#show_perusahaan_status_do").show();
                $("#show_principle_status_do").show();
                $("#show_do_status_do").hide();
            } else if (flag == 'canvas_principle') {
                client_wms = $("#nama_principle_status_do").text();
                principle = label;
                $("#do_status_do").text(principle);
                $("#show_do_status_do").show();
            }
        }

        if (modeReport == 'daily' && $("#filter_tanggal_status_do").prop('disabled')) {
            $("#filter_tanggal_status_do").removeAttr('disabled', true);
            $("#show_perusahaan_status_do").hide();
            $("#show_principle_status_do").hide();
            $("#show_do_status_do").hide();
            return false;
        } else if (modeReport == 'mtd') {
            setDateRange();
            $("#filter_tanggal_status_do").attr('disabled', true);
        }

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MainDashboard/getDashboardStatusDO') ?>",
            data: {
                client_wms: client_wms,
                principle: principle,
                status: status,
                mode: modeReport,
                tanggal: tanggal
            },
            dataType: "JSON",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                arr_report = response;

                report_dashboard_status_do();
            },
            complete: function() {
                swal.close();
            }
        })
    }

    function filterReportFleetProductivity(flag, label) {
        modeReport = $("#filter_mode_fleet_productivity option:selected").val();
        tanggal = $("#filter_tanggal_fleet_productivity").val();
        value = label;

        if (flag == 'status_kendaraan') {
            flag_report = 'status_kendaraan'
            $("#show_kendaraan_fleet_productivity").show();
            $("#show_kendaraan_model_fleet_productivity").hide();
            $("#show_list_kendaraan_fleet_productivity").hide();
        } else if (flag == 'list_detail_kendaraan_nopol') {
            area = label;
            flag_report = flag;
        } else {
            flag_report = flag;
            if (flag == 'kendaraan_model') {
                status_assign = label;
                $("#kendaraan_model_fleet_productivity").text(label);
                $("#show_kendaraan_fleet_productivity").show();
                $("#show_kendaraan_model_fleet_productivity").show();
                $("#show_list_kendaraan_fleet_productivity").hide();
            } else {
                kendaraan_model = label;
                $("#list_kendaraan_model").text(label);
                $("#show_kendaraan_fleet_productivity").show();
                $("#show_kendaraan_model_fleet_productivity").show();
                $("#show_list_kendaraan_fleet_productivity").show();
                $("#show_list_detail_kendaraan_fleet_productivity").hide();
                $("#show_kendaraan_area_fleet_productivity").show();
                $("#title_detail_kendaraan").text(label)
            }
        }

        if (modeReport == 'daily' && $("#filter_tanggal_fleet_productivity").prop('disabled')) {
            $("#filter_tanggal_fleet_productivity").removeAttr('disabled', true);
            $("#show_kendaraan_fleet_productivity").hide();
            $("#show_kendaraan_model_fleet_productivity").hide();
            $("#show_list_kendaraan_fleet_productivity").hide();
            return false;
        } else if (modeReport == 'mtd') {
            setDateRange();
            $("#filter_tanggal_fleet_productivity").attr('disabled', true);
        }

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MainDashboard/getDashboardFleetProductivity') ?>",
            data: {
                mode: modeReport,
                flag: flag,
                status_assign: status_assign,
                tanggal: tanggal,
                value: kendaraan_model,
                area: area
            },
            dataType: "JSON",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                arr_report = response;

                if (flag == 'list_detail_kendaraan_model') {
                    temp_report = response;
                }

                report_dashboard_fleet_productivity();
            },
            complete: function() {
                swal.close();
            }
        })

    }

    function report_dashboard_doi() {
        if (flag_report == 'filter_all_perusahaan' || flag_report == 'filter_by_perusahaan') {
            if (perusahaanChartReport) {
                perusahaanChartReport.destroy();
            }

            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_sku_report_doi > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_doi')) {
                $('#table_sku_report_doi').DataTable().clear();
                $('#table_sku_report_doi').DataTable().destroy();
            }

            if (flag_report == 'filter_all_perusahaan') {
                var ctx = document.getElementById('chart_perusahaan_report_doi').getContext("2d");
                tampilCanvas(ctx, flag_report, 'doi');
            } else {
                var ctx = document.getElementById('chart_principle_report_doi').getContext("2d");
                tampilCanvas(ctx, flag_report, 'doi');
            }
        } else if (flag_report == 'canvas_perusahaan') {
            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_sku_report_doi > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_doi')) {
                $('#table_sku_report_doi').DataTable().clear();
                $('#table_sku_report_doi').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_principle_report_doi').getContext("2d");
            tampilCanvas(ctx, flag_report, 'doi');
        } else {
            $("#table_sku_report_doi > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_doi')) {
                $('#table_sku_report_doi').DataTable().clear();
                $('#table_sku_report_doi').DataTable().destroy();
            }

            $.each(arr_report, function(i, v) {
                $("#table_sku_report_doi > tbody").append(`
                    <tr>
                        <td>${v.sku_konversi_group}</td>
                        <td>${v.sku_nama_produk}</td>
                        <td>${v.satuan}</td>
                        <td>${parseFloat(v.stok).toFixed(2)}</td>
                        <td>${parseFloat(v.avg_sales).toFixed(2)}</td>
                        <td>${parseFloat(v.doi).toFixed(2)}</td>
                    </tr>
               `)
            });

            dataTableDetailDashboard("table_sku_report_doi", `DOI SKU (${$("#principle_sku_doi").text()})`)
        }
    }

    function report_dashboard_sj_supplier() {
        if (flag_report == 'filter_all_perusahaan' || flag_report == 'filter_by_perusahaan') {

            if (perusahaanChartReport) {
                perusahaanChartReport.destroy();
            }

            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_sku_report_supplier > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_supplier')) {
                $('#table_sku_report_supplier').DataTable().clear();
                $('#table_sku_report_supplier').DataTable().destroy();
            }

            if (flag_report == 'filter_all_perusahaan') {
                var ctx = document.getElementById('chart_perusahaan_report_supplier').getContext("2d");
                tampilCanvas(ctx, flag_report, 'sj_supplier');
            } else {
                var ctx = document.getElementById('chart_principle_report_supplier').getContext("2d");
                tampilCanvas(ctx, flag_report, 'sj_supplier');
            }
        } else if (flag_report == 'canvas_perusahaan') {
            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_sku_report_supplier > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_supplier')) {
                $('#table_sku_report_supplier').DataTable().clear();
                $('#table_sku_report_supplier').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_principle_report_supplier').getContext("2d");
            tampilCanvas(ctx, flag_report, 'sj_supplier');
        } else {
            $("#table_sku_report_supplier > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_supplier')) {
                $('#table_sku_report_supplier').DataTable().clear();
                $('#table_sku_report_supplier').DataTable().destroy();
            }

            $.each(arr_report, function(i, v) {
                $("#table_sku_report_supplier > tbody").append(`
                    <tr>
                        <td>${v.tglawal}</td>
                        <td>${v.tglakhir}</td>
                        <td>${v.sku_konversi_group}</td>
                        <td>${v.sku_nama_produk}</td>
                        <td>${v.satuan}</td>
                        <td>${parseFloat(v.qty_penerimaan_sj).toFixed(2)}</td>
                        <td>${parseFloat(v.qty_penerimaan_brg).toFixed(2)}</td>
                        <td>${parseFloat(v.persentase).toFixed(2)}</td>
                    </tr>
               `)
            });

            dataTableDetailDashboard("table_sku_report_supplier", `SJ VS Penerimaan SKU (${$("#principle_sku_sj_supplier").text()})`)
        }
    }

    function report_dashboard_truck_supplier() {
        if (flag_report == 'filter_all_perusahaan' || flag_report == 'filter_by_perusahaan') {

            if (perusahaanChartReport) {
                perusahaanChartReport.destroy();
            }

            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_sku_report_truck_supplier > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_truck_supplier')) {
                $('#table_sku_report_truck_supplier').DataTable().clear();
                $('#table_sku_report_truck_supplier').DataTable().destroy();
            }

            if (flag_report == 'filter_all_perusahaan') {
                var ctx = document.getElementById('chart_perusahaan_report_truck_supplier').getContext("2d");
                tampilCanvas(ctx, flag_report, 'truck_supplier');
            } else {
                var ctx = document.getElementById('chart_principle_report_truck_supplier').getContext("2d");
                tampilCanvas(ctx, flag_report, 'truck_supplier');
            }
        } else if (flag_report == 'canvas_perusahaan') {
            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_sku_report_truck_supplier > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_truck_supplier')) {
                $('#table_sku_report_truck_supplier').DataTable().clear();
                $('#table_sku_report_truck_supplier').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_principle_report_truck_supplier').getContext("2d");
            tampilCanvas(ctx, flag_report, 'truck_supplier');
        } else {
            $("#table_sku_report_truck_supplier > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_truck_supplier')) {
                $('#table_sku_report_truck_supplier').DataTable().clear();
                $('#table_sku_report_truck_supplier').DataTable().destroy();
            }

            $.each(arr_report, function(i, v) {
                $("#table_sku_report_truck_supplier > tbody").append(`
                    <tr>
                        <td>${v.tanggal}</td>
                        <td>${v.security_logbook_kode}</td>
                        <td>${v.nama_driver}</td>
                        <td>${v.tgl_masuk}</td>
                        <td>${v.tgl_keluar ?? '-'}</td>
                        <td>${v.durasi ?? '-'}</td>
                    </tr>
               `)
            });

            dataTableDetailDashboard("table_sku_report_truck_supplier", `List Kedatangan (${$("#principle_list_kedatangan_truck").text()})`)

        }
    }

    function report_dashboard_stock() {
        if (flag_report == 'filter_all_perusahaan' || flag_report == 'filter_by_perusahaan') {

            if (perusahaanChartReport) {
                perusahaanChartReport.destroy();
            }

            if (principleChartReport) {
                principleChartReport.destroy();
            }

            if (gudangChartReport) {
                gudangChartReport.destroy();
            }

            $("#table_sku_report_stock > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_stock')) {
                $('#table_sku_report_stock').DataTable().clear();
                $('#table_sku_report_stock').DataTable().destroy();
            }

            if (flag_report == 'filter_all_perusahaan') {
                var ctx = document.getElementById('chart_perusahaan_report_stock').getContext("2d");
                tampilCanvas(ctx, flag_report, 'stock');
            } else {
                var ctx = document.getElementById('chart_principle_report_stock').getContext("2d");
                tampilCanvas(ctx, flag_report, 'stock');
            }
        } else if (flag_report == 'canvas_perusahaan') {
            if (principleChartReport) {
                principleChartReport.destroy();
            }

            if (gudangChartReport) {
                gudangChartReport.destroy();
            }

            $("#table_sku_report_stock > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_stock')) {
                $('#table_sku_report_stock').DataTable().clear();
                $('#table_sku_report_stock').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_principle_report_stock').getContext("2d");
            tampilCanvas(ctx, flag_report, 'stock');
        } else if (flag_report == 'canvas_principle') {
            if (gudangChartReport) {
                gudangChartReport.destroy();
            }

            $("#table_sku_report_stock > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_stock')) {
                $('#table_sku_report_stock').DataTable().clear();
                $('#table_sku_report_stock').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_gudang_report_stock')
            tampilCanvas(ctx, flag_report, 'stock');
        } else {

            $("#table_sku_report_stock > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_sku_report_stock')) {
                $('#table_sku_report_stock').DataTable().clear();
                $('#table_sku_report_stock').DataTable().destroy();
            }

            $.each(arr_report, function(i, v) {
                $("#table_sku_report_stock > tbody").append(`
                    <tr>
                        <td>${v.sku_nama_produk}</td>
                        <td>${v.sku_kemasan}</td>
                        <td>${v.sku_kode}</td>
                        <td>${v.sku_stock_expired_date}</td>
                        <td>${v.stock_akhir}</td>
                    </tr>
               `)
            });

            dataTableDetailDashboard("table_sku_report_stock", `Stock SKU (${$("#gudang_sku_stock").text()})`)
        }
    }

    function report_dashboard_service_level() {
        if (flag_report == 'filter_chart_1') {
            if (barChartReport1) {
                barChartReport1.destroy();
            }

            if (perusahaanChartReport) {
                perusahaanChartReport.destroy();
            }

            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_so_report_service_level > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_so_report_service_level')) {
                $('#table_so_report_service_level').DataTable().clear();
                $('#table_so_report_service_level').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_tipe_report_service_level').getContext("2d");
            tampilCanvas(ctx, flag_report, 'service_level');
        } else if (flag_report == 'canvas_tipe') {

            if (perusahaanChartReport) {
                perusahaanChartReport.destroy();
            }

            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_so_report_service_level > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_so_report_service_level')) {
                $('#table_so_report_service_level').DataTable().clear();
                $('#table_so_report_service_level').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_perusahaan_report_service_level').getContext("2d");
            tampilCanvas(ctx, flag_report, 'service_level');
        } else if (flag_report == 'canvas_perusahaan') {
            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_so_report_service_level > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_so_report_service_level')) {
                $('#table_so_report_service_level').DataTable().clear();
                $('#table_so_report_service_level').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_principle_report_service_level').getContext("2d");
            tampilCanvas(ctx, flag_report, 'service_level');
        } else {
            $("#table_so_report_service_level > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_so_report_service_level')) {
                $('#table_so_report_service_level').DataTable().clear();
                $('#table_so_report_service_level').DataTable().destroy();
            }

            $.each(arr_report, function(i, v) {
                $("#table_so_report_service_level > tbody").append(`
                    <tr>
                        <td>${v.sales_order_tgl}</td>
                        <td>${v.sales_order_tgl_kirim}</td>
                        <td>${v.sales_order_kode}</td>
                        <td>${v.sales_order_no_po}</td>
                        <td>${v.szSalesId}</td>
                        <td>${v.karyawan_nama}</td>
                        <td>${v.kode_customer_eksternal}</td>
                        <td>${v.principle_kode}</td>
                        <td>${v.client_wms_nama}</td>
                        <td>${v.delivery_order_draft_kirim_nama}</td>
                        <td>${v.delivery_order_draft_kirim_alamat}</td>
                        <td>${v.tipe_sales_order_nama}</td>
                        <td>${v.delivery_order_draft_status}</td>
                        <td>${v.delivery_order_draft_nominal_tunai}</td>
                        <td>${v.sales_order_keterangan}</td>
                        <td>${v.delivery_order_draft_is_prioritas}</td>
                    </tr>
               `)
            });

            dataTableDetailDashboard("table_so_report_service_level", `SO (${$("#so_service_level").text()})`)
        }
    }

    function report_dashboard_status_do() {
        if (flag_report == 'filter_chart_1') {
            if (barChartReport1) {
                barChartReport1.destroy();
            }

            if (perusahaanChartReport) {
                perusahaanChartReport.destroy();
            }

            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_do_report_status_do > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_do_report_status_do')) {
                $('#table_do_report_status_do').DataTable().clear();
                $('#table_do_report_status_do').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_status_report_status_do').getContext("2d");
            tampilCanvas(ctx, flag_report, 'status_do');
        } else if (flag_report == 'canvas_status') {

            if (perusahaanChartReport) {
                perusahaanChartReport.destroy();
            }

            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_do_report_status_do > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_do_report_status_do')) {
                $('#table_do_report_status_do').DataTable().clear();
                $('#table_do_report_status_do').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_perusahaan_report_status_do').getContext("2d");
            tampilCanvas(ctx, flag_report, 'status_do');
        } else if (flag_report == 'canvas_perusahaan') {
            if (principleChartReport) {
                principleChartReport.destroy();
            }

            $("#table_do_report_status_do > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_do_report_status_do')) {
                $('#table_do_report_status_do').DataTable().clear();
                $('#table_do_report_status_do').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_principle_report_status_do').getContext("2d");
            tampilCanvas(ctx, flag_report, 'status_do');
        } else {
            $("#table_do_report_status_do > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_do_report_status_do')) {
                $('#table_do_report_status_do').DataTable().clear();
                $('#table_do_report_status_do').DataTable().destroy();
            }

            $.each(arr_report, function(i, v) {
                $("#table_do_report_status_do > tbody").append(`
                    <tr>
                        <td>${v.delivery_order_tgl_rencana_kirim}</td>
                        <td>${v.delivery_order_kode}</td>
                        <td>${v.sales_order_kode}</td>
                        <td>${v.sales_order_no_po}</td>
                        <td>${v.principle_kode}</td>
                        <td>${v.karyawan_nama}</td>
                        <td>${v.delivery_order_kirim_nama}</td>
                        <td>${v.delivery_order_kirim_alamat}</td>
                        <td>${v.delivery_order_kirim_area}</td>
                        <td>${v.tipe_delivery_order_nama}</td>
                        <td>${v.tipe_layanan}</td>
                    </tr>
               `)
            });

            dataTableDetailDashboard("table_do_report_status_do", `SO (${$("#do_status_do").text()})`)
        }
    }

    function report_dashboard_fleet_productivity() {

        if (flag_report == 'status_kendaraan') {

            if (gudangChartReport) {
                gudangChartReport.destroy();
            }

            if (barChartReport1) {
                barChartReport1.destroy();
            }

            if (principleChartReport) {
                principleChartReport.destroy();
            }

            if (perusahaanChartReport) {
                perusahaanChartReport.destroy();
            }

            $("#table_list_kendaraan_report_fleet_productivity > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_list_kendaraan_report_fleet_productivity')) {
                $('#table_list_kendaraan_report_fleet_productivity').DataTable().clear();
                $('#table_list_kendaraan_report_fleet_productivity').DataTable().destroy();
            }


            var ctx = document.getElementById('chart_kendaraan_report_fleet_productivity').getContext("2d");
            tampilCanvas(ctx, flag_report, 'fleet_productivity');

        } else if (flag_report == 'kendaraan_model') {
            if (barChartReport1) {
                barChartReport1.destroy();
            }
            if (principleChartReport) {
                principleChartReport.destroy();
            }
            if (perusahaanChartReport) {
                perusahaanChartReport.destroy();
            }

            $("#table_list_kendaraan_report_fleet_productivity > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_list_kendaraan_report_fleet_productivity')) {
                $('#table_list_kendaraan_report_fleet_productivity').DataTable().clear();
                $('#table_list_kendaraan_report_fleet_productivity').DataTable().destroy();
            }

            var ctx = document.getElementById('chart_kendaraan_model_report_fleet_productivity').getContext("2d");
            tampilCanvas(ctx, flag_report, 'fleet_productivity');
        } else if (flag_report == 'list_detail_kendaraan_nopol') {
            if (barChartReport2) {
                barChartReport2.destroy();
            }

            var ctx = document.getElementById('chart_detail_kendaraan_nopol_report_fleet_productivity').getContext("2d");
            tampilCanvas(ctx, flag_report, 'fleet_productivity');

            $("#modalDetalKendaraanModel").modal('show');
        } else {
            $.each(arr_report, function(i, v) {
                $("#fleet_area").append(`<option selected value="${v.delivery_order_kirim_area}">${v.delivery_order_kirim_area}(${v.jumlah})</option>`);
            })

            $('.selectpicker').selectpicker('refresh');

            if (principleChartReport) {
                principleChartReport.destroy();
            }

            var ctx = document.getElementById('chart_kendaraan_area_report_fleet_productivity').getContext("2d");
            tampilCanvas(ctx, flag_report, 'fleet_productivity');

            $("#table_list_kendaraan_report_fleet_productivity > tbody").empty();

            if ($.fn.DataTable.isDataTable('#table_list_kendaraan_report_fleet_productivity')) {
                $('#table_list_kendaraan_report_fleet_productivity').DataTable().clear();
                $('#table_list_kendaraan_report_fleet_productivity').DataTable().destroy();
            }

            table_list_do_batch = $('#table_list_kendaraan_report_fleet_productivity').DataTable({
                // "scrollX": true,
                'paging': true,
                'searching': true,
                'ordering': false,
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
                    url: "<?= base_url('WMS/MainDashboard/getDashboardFleetProductivity') ?>",
                    type: "POST",
                    dataType: "json",
                    data: function(data) {
                        data.mode = modeReport,
                            data.flag = flag_report,
                            data.status_assign = status_assign,
                            data.tanggal = tanggal,
                            data.value = value
                    },
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
                        data: 'delivery_order_batch_tanggal'
                    },
                    {
                        data: 'delivery_order_batch_tanggal_kirim'
                    },
                    {
                        data: 'karyawan_nama'
                    },
                    {
                        data: 'kendaraan_nopol'
                    },
                    {
                        data: 'delivery_order_batch_h_ritasi'
                    },
                    {
                        render: function(data, type, row, meta) {
                            var str = '';
                            str += `${formatNumber(data)}`

                            return str;
                        },
                        data: 'berat_max'
                    },
                    {
                        render: function(data, type, row, meta) {
                            var str = '';
                            str += `${formatNumber(data)}`

                            return str;
                        },
                        data: 'volume_max'
                    },
                    {
                        render: function(data, type, row, meta) {
                            var str = '';
                            str += `${formatNumber(data)}`

                            return str;
                        },
                        data: 'berat_terpakai'
                    },
                    {
                        render: function(data, type, row, meta) {
                            var str = '';
                            str += `${formatNumber(data)}`

                            return str;
                        },
                        data: 'volume_terpakai'
                    },
                    {
                        render: function(data, type, row, meta) {
                            var str = '';
                            str += parseFloat(data).toFixed(2);

                            return str + '%';
                        },
                        data: 'presentase_berat_terpakai'
                    },
                    {
                        render: function(data, type, row, meta) {
                            var str = '';
                            str += parseFloat(data).toFixed(2)

                            return str + '%';
                        },
                        data: 'presentase_volume_terpakai'
                    },
                    {
                        data: 'delivery_order_batch_kode'
                    },
                    {
                        data: 'tipe_delivery_order_alias'
                    },
                    {
                        data: 'delivery_order_batch_tipe_layanan_nama'
                    },
                    {
                        data: 'total_outlet'
                    },
                    {
                        data: 'delivery_order_batch_status'
                    },
                ],
                "rowsGroup": [
                    0,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                ],
                "columnDefs": [{
                    targets: [...Array(15).keys()], // ini berarti [0,1,2,...,14]
                    className: 'text-center align-middle'
                }],
                initComplete: function() {
                    parent_dt = $('#table_list_kendaraan_report_fleet_productivity').closest('.dataTables_wrapper')
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

            // dataTableDetailDashboard("table_list_kendaraan_report_fleet_productivity", `List Kendaraan Model (${$("#list_kendaraan_model").text()})`)

        }
    }


    function tampilCanvas(ctx, flag_report, dashboard) {
        var datasets = [];
        var scales = [];
        var label_string = '';

        if (dashboard == 'doi') {
            var data1 = arr_report.map(item => parseFloat(item.stok));
            var data2 = arr_report.map(item => parseFloat(item.avg_sales));
            var data3 = arr_report.map(item => parseFloat(item.doi));
            var label1 = 'STOK';
            var label2 = 'AVG Sales';
            var label3 = 'DOI';
            var caption_chart_bar = 'Stok VS AVG Sales'
            var caption_chart_line = 'DOI'

        } else if (dashboard == 'sj_supplier') {
            var data1 = arr_report.map(item => parseFloat(item.qty_penerimaan_sj));
            var data2 = arr_report.map(item => parseFloat(item.qty_penerimaan_brg));
            var data3 = arr_report.map(item => parseFloat(item.persentase));
            var label1 = 'Penerimaan SJ';
            var label2 = 'Penerimaan Barang';
            var label3 = 'Persentase';
            var caption_chart_bar = 'Surat Jalan VS Penerimaan'
            var caption_chart_line = 'Persentase'
        } else if (dashboard == 'truck_supplier') {
            var data1 = arr_report.map(item => parseFloat(item.jumlah_truk));
            var data2 = arr_report.map(item => parseFloat(item.jumlah_selesai_bongkar));
            var data3 = arr_report.map(item => parseFloat(item.persentase));
            var label1 = 'Jumlah Truk';
            var label2 = 'Selesai Bongkar';
            var label3 = 'Persentase';
            var caption_chart_bar = 'Jumlah Truk VS Selesai Bongkar'
            var caption_chart_line = 'Persentase'
        } else if (dashboard == 'stock') {
            var data1 = arr_report.map(item => parseFloat(item.stock_akhir));
            var label1 = 'Stock Akhir';
            var caption_chart_bar = 'Stock Akhir'
        } else if (dashboard == 'service_level') {
            var data1 = arr_report.map(item => parseFloat(item.jml));
            var label1 = 'Jumlah DO';
            var caption_chart_bar = 'Jumlah DO'
            var labels = arr_report.map(item => item.tipe);
            label_string = "Lead Time Delivery Order"
        } else if (dashboard == 'status_do') {
            var data1 = arr_report.map(item => parseFloat(item.jml));
            var label1 = 'Jumlah DO';
            var caption_chart_bar = 'Jumlah DO'
            var labels = arr_report.map(item => item.status);
            label_string = "Status"
        } else if (dashboard == 'fleet_productivity') {
            var data1 = arr_report.map(item => parseFloat(item.jumlah));
            var caption_chart_bar = 'Jumlah DO'


            if (flag_report == 'status_kendaraan') {
                label_string = "Status"
                var labels = arr_report.map(item => item.kendaraan);
            } else if (flag_report == 'kendaraan_model') {
                label_string = "Kendaraan Model"
                var labels = arr_report.map(item => item.kendaraan);
            } else if (flag_report == 'list_detail_kendaraan_model') {
                var label1 = 'Area';
                var labels = arr_report.map(item => item.delivery_order_kirim_area);
            } else if (flag_report == 'list_detail_kendaraan_nopol') {
                var labels = arr_report.map(item => item.kendaraan_nopol);
            }
        }

        // Tambahkan dataset untuk Persentase (Line Chart) jika ada
        if (typeof data3 !== "undefined") {
            datasets.push({
                label: label3,
                data: data3,
                type: 'line',
                borderColor: "#E67E22",
                backgroundColor: "transparent",
                borderWidth: 2,
                pointRadius: 5,
                pointBackgroundColor: "#E67E22",
                yAxisID: 'y-right',
                order: 2
            });
        }

        // Tambahkan dataset untuk Bar Chart (Jumlah Truk atau Stock Akhir)
        if (typeof data1 !== "undefined") {
            if (flag_report != 'canvas_principle' && flag_report != 'status_kendaraan') {
                datasets.push({
                    label: label1,
                    data: data1,
                    type: 'bar',
                    backgroundColor: "#26B99A",
                    yAxisID: 'y-left',
                    order: 1
                });
            } else {
                datasets.push({
                    data: data1,
                    backgroundColor: [
                        "#455C73", "#9B59B6", "#BDC3C7", "#26B99A", "#3498DB",
                        "#E74C3C", "#F1C40F", "#2ECC71", "#1ABC9C", "#8E44AD", "#D35400"
                    ],
                    hoverBackgroundColor: [
                        "#34495E", "#B370CF", "#CFD4D8", "#36CAAB", "#49A9EA",
                        "#C0392B", "#F39C12", "#27AE60", "#16A085", "#7D3C98", "#E67E22"
                    ]
                });
            }
        }

        if (typeof data2 !== "undefined") {
            datasets.push({
                label: label2,
                data: data2,
                type: 'bar',
                backgroundColor: "#03586A",
                yAxisID: 'y-left',
                order: 1
            });
        }

        if (typeof caption_chart_bar !== "undefined") {
            scales.push({
                id: "y-left",
                position: "left",
                ticks: {
                    beginAtZero: true
                },
                scaleLabel: {
                    display: true,
                    labelString: caption_chart_bar
                },
                barPercentage: 0.5, // Perkecil bar agar tidak terlalu lebar
            }, );
        }

        if (typeof caption_chart_line !== "undefined") {
            scales.push({
                id: "y-right",
                position: "right",
                ticks: {
                    beginAtZero: true
                },
                gridLines: {
                    drawOnChartArea: false
                }, // Menghilangkan garis dari sumbu kanan
                scaleLabel: {
                    display: true,
                    labelString: caption_chart_line
                }
            })
        }


        if (flag_report == 'filter_chart_1' || flag_report == 'kendaraan_model') {

            barChartReport1 = new Chart(ctx, {
                type: 'bar', // Default type (bar)
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Supaya bisa bebas atur tinggi
                    onClick: function(event, elements) {
                        let flag = event.target.getAttribute('data-flag')

                        if (elements.length > 0) {
                            let dataIndex = elements[0]._index; // Ambil indeks data (label)
                            let label = this.data.labels[dataIndex]; // Tahun yang dipilih

                            if (dashboard == 'service_level') {
                                filterReportServiceLevel(flag, label)
                            } else if (dashboard == 'status_do') {
                                filterReportStatusDO(flag, label)
                            } else if (dashboard == 'fleet_productivity') {
                                if (status_assign == 'Assign') {
                                    filterReportFleetProductivity(flag, label)
                                }
                            }
                        }
                    },
                    tooltips: {
                        callbacks: {
                            // Ubah tampilan tooltip dengan format Rupiah
                            label: function(tooltipItem, data) {
                                var value = tooltipItem.yLabel; // Ambil nilai tooltip
                                // Format nilai menjadi Rupiah
                                var formattedValue = formatRupiahNumber(value);

                                return formattedValue;
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            scaleLabel: {
                                display: true, // Menampilkan label di bawah sumbu x
                                labelString: label_string
                            }
                        }],
                        yAxes: scales
                    }
                }
            });
        } else if (flag_report == 'filter_all_perusahaan' || flag_report == 'canvas_tipe' || flag_report == 'canvas_status') {
            var labels = arr_report.map(item => item.client_wms_kode);
            label_string = 'Perusahaan';


            perusahaanChartReport = new Chart(ctx, {
                type: 'bar', // Default type (bar)
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Supaya bisa bebas atur tinggi
                    onClick: function(event, elements) {
                        let flag = event.target.getAttribute('data-flag')

                        if (elements.length > 0) {
                            let dataIndex = elements[0]._index; // Ambil indeks data (label)
                            let label = this.data.labels[dataIndex]; // Tahun yang dipilih

                            if (dashboard == 'doi') {
                                filterReportDOI(flag, label)
                            } else if (dashboard == 'sj_supplier') {
                                filterReportSJSupplier(flag, label)
                            } else if (dashboard == 'truck_supplier') {
                                filterReportTruckSupplier(flag, label)
                            } else if (dashboard == 'stock') {
                                filterReportStock(flag, label)
                            } else if (dashboard == 'service_level') {
                                filterReportServiceLevel(flag, label)
                            } else if (dashboard == 'status_do') {
                                filterReportStatusDO(flag, label)
                            }
                        }
                    },
                    tooltips: {
                        callbacks: {
                            // Ubah tampilan tooltip dengan format Rupiah
                            label: function(tooltipItem, data) {
                                var value = tooltipItem.yLabel; // Ambil nilai tooltip
                                // Format nilai menjadi Rupiah
                                var formattedValue = formatRupiahNumber(value);

                                return formattedValue;
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            scaleLabel: {
                                display: true, // Menampilkan label di bawah sumbu x
                                labelString: label_string, // Ganti dengan teks yang diinginkan
                            }
                        }],
                        yAxes: scales
                    }
                }
            });
        } else if (flag_report == 'filter_by_perusahaan' || flag_report == 'canvas_perusahaan' || flag_report == 'list_detail_kendaraan_model') {

            if (flag_report == 'list_detail_kendaraan_model') {
                label_string = 'Kendaraan Area';
            } else {
                label_string = 'Principle';
                var labels = arr_report.map(item => item.principle_kode);
            }

            principleChartReport = new Chart(ctx, {
                type: 'bar', // Default type (bar)
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Supaya bisa bebas atur tinggi
                    onClick: function(event, elements) {
                        let flag = event.target.getAttribute('data-flag')

                        if (elements.length > 0) {
                            let dataIndex = elements[0]._index; // Ambil indeks data (label)
                            let label = this.data.labels[dataIndex]; // Tahun yang dipilih

                            if (dashboard == 'doi') {
                                filterReportDOI(flag, label)
                            } else if (dashboard == 'sj_supplier') {
                                filterReportSJSupplier(flag, label)
                            } else if (dashboard == 'truck_supplier') {
                                filterReportTruckSupplier(flag, label)
                            } else if (dashboard == 'stock') {
                                filterReportStock(flag, label)
                            } else if (dashboard == 'service_level') {
                                filterReportServiceLevel(flag, label)
                            } else if (dashboard == 'status_do') {
                                filterReportStatusDO(flag, label)
                            } else if (dashboard == 'fleet_productivity') {
                                filterReportFleetProductivity(flag, label)
                            }
                        }
                    },
                    tooltips: {
                        callbacks: {
                            // Ubah tampilan tooltip dengan format Rupiah
                            label: function(tooltipItem, data) {
                                var value = tooltipItem.yLabel; // Ambil nilai tooltip
                                // Format nilai menjadi Rupiah
                                var formattedValue = formatRupiahNumber(value);

                                return formattedValue;
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            scaleLabel: {
                                display: true, // Menampilkan label di bawah sumbu x
                                labelString: label_string, // Ganti dengan teks yang diinginkan
                            }
                        }],
                        yAxes: scales
                    }
                }
            });
        } else if (flag_report == 'canvas_principle' || flag_report == 'status_kendaraan') {

            if (flag_report == 'canvas_principle') {
                var labels = arr_report.map(item => item.depo_detail_nama);
            }

            $(".filter_chart_donut").html('');
            // <label style="padding-right: 10px;"><input type="checkbox" class="toggleDonut" data-value="${i}" checked> ${v}</label>

            $.each(labels, function(i, v) {
                $(".filter_chart_donut").append(`
                        <label class="custom-box" style="vertical-align: middle; background-color: ${datasets[0].backgroundColor[i]}"><input type="checkbox" class="toggleDonut" data-value="${i}" data-text="${v}" checked style="display: none;"></label> <strong class="textChartDonut_${i}">${v}</strong>
                    `)
            })

            gudangChartReport = new Chart(ctx, {
                type: "doughnut",
                tooltipFillColor: "rgba(51, 51, 51, 0.55)",
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Supaya bisa bebas atur tinggi
                    onClick: function(event, elements) {
                        let flag = event.target.getAttribute('data-flag')

                        if (elements.length > 0) {
                            let dataIndex = elements[0]._index; // Ambil indeks data (label)
                            let label = this.data.labels[dataIndex]; // Tahun yang dipilih

                            if (dashboard == 'doi') {
                                filterReportDOI(flag, label)
                            } else if (dashboard == 'sj_supplier') {
                                filterReportSJSupplier(flag, label)
                            } else if (dashboard == 'truck_supplier') {
                                filterReportTruckSupplier(flag, label)
                            } else if (dashboard == 'stock') {
                                filterReportStock(flag, label)
                            } else if (dashboard == 'fleet_productivity') {
                                filterReportFleetProductivity(flag, label)
                            }
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var dataset = data.datasets[tooltipItem.datasetIndex];
                                var value = dataset.data[tooltipItem.index]; // Ambil nilai mentah
                                var label = data.labels[tooltipItem.index]; // Ambil label

                                var formattedValue = formatRupiahNumber(value);
                                return label + ": " + formattedValue;
                            }
                        }
                    },
                }
            })
        } else if (flag_report == 'list_detail_kendaraan_nopol') {
            if (flag_report == 'list_detail_kendaraan_nopol') {
                label_string = 'Kendaraan Nopol';
            }

            barChartReport2 = new Chart(ctx, {
                type: 'bar', // Default type (bar)
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Supaya bisa bebas atur tinggi
                    // onClick: function(event, elements) {
                    //     let flag = event.target.getAttribute('data-flag')

                    //     if (elements.length > 0) {
                    //         let dataIndex = elements[0]._index; // Ambil indeks data (label)
                    //         let label = this.data.labels[dataIndex]; // Tahun yang dipilih

                    //         if (dashboard == 'doi') {
                    //             filterReportDOI(flag, label)
                    //         } else if (dashboard == 'sj_supplier') {
                    //             filterReportSJSupplier(flag, label)
                    //         } else if (dashboard == 'truck_supplier') {
                    //             filterReportTruckSupplier(flag, label)
                    //         } else if (dashboard == 'stock') {
                    //             filterReportStock(flag, label)
                    //         } else if (dashboard == 'service_level') {
                    //             filterReportServiceLevel(flag, label)
                    //         } else if (dashboard == 'status_do') {
                    //             filterReportStatusDO(flag, label)
                    //         } else if (dashboard == 'fleet_productivity') {
                    //             filterReportFleetProductivity(flag, label)
                    //         }
                    //     }
                    // },
                    tooltips: {
                        callbacks: {
                            // Ubah tampilan tooltip dengan format Rupiah
                            label: function(tooltipItem, data) {
                                var value = tooltipItem.yLabel; // Ambil nilai tooltip
                                // Format nilai menjadi Rupiah
                                var formattedValue = formatRupiahNumber(value);

                                return formattedValue;
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            scaleLabel: {
                                display: true, // Menampilkan label di bawah sumbu x
                                labelString: label_string, // Ganti dengan teks yang diinginkan
                            }
                        }],
                        yAxes: scales
                    }
                }
            });
        }
    }

    function filterAreaFleet() {
        var selectedAreas = $("#fleet_area").val();

        if (selectedAreas == null) {
            message("Info!", "Wajib Pilih Area!", "info");
            return false;
        }
        // Filter data asli berdasarkan area terpilih
        const filtered = temp_report.filter(item => selectedAreas.includes(item.delivery_order_kirim_area));

        // Ambil labels dan data hasil filter
        const newLabels = filtered.map(item => item.delivery_order_kirim_area);
        const newData = filtered.map(item => item.jumlah);

        // Update chart
        principleChartReport.data.labels = newLabels;
        principleChartReport.data.datasets[0].data = newData;
        principleChartReport.update();



    }

    function keterangan(mode) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MainDashboard/getKeterangan') ?>",
            data: {
                mode: mode,
            },
            dataType: "JSON",
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                $(".keterangan").text(response['judul']);
            },
            // complete: function() {
            //     swal.close();
            // }
        })
    }

    function dataTableDetailDashboard(id, title_name) {
        $(`#${id}`).DataTable({
            lengthMenu: [
                [50, 100, -1],
                [50, 100, 'All']
            ],
            dom: "<'row'<'col-md-6'B><'col-md-6'f>>" + // Tombol di kiri, search di kanan
                "<'row'<'col-md-12'tr>>" +
                "<'row'<'col-md-6'l><'col-md-6'p>>",
            buttons: [{
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i> Excel',
                className: 'btn btn-success',
                filename: `${title_name}`
            }],
        })
    }

    function showDetailKendaraan(flag) {
        if (flag == 'kendaraan_by_area') {
            $("#btn_detail_chart_kendaraan").removeClass('btn-default');
            $("#btn_detail_chart_kendaraan").addClass('btn-primary');
            $("#btn_list_detail_kendaraan").removeClass('btn-primary');
            $("#btn_list_detail_kendaraan").addClass('btn-default');
            $("#show_list_detail_kendaraan_fleet_productivity").hide();
            $("#show_kendaraan_area_fleet_productivity").show();
        } else {
            $("#btn_list_detail_kendaraan").removeClass('btn-default');
            $("#btn_list_detail_kendaraan").addClass('btn-primary');
            $("#btn_detail_chart_kendaraan").removeClass('btn-primary');
            $("#btn_detail_chart_kendaraan").addClass('btn-default');
            $("#show_list_detail_kendaraan_fleet_productivity").show();
            $("#show_kendaraan_area_fleet_productivity").hide();
        }
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function formatRupiahNumber(num) {
        nilai = parseFloat(num);

        // Pastikan angka dibulatkan ke 2 desimal
        let fixedNilai = nilai.toFixed(2); // Contoh: 13068.238570 -> "13068.24"

        // Pisahkan angka dan desimal
        let [integerPart, decimalPart] = fixedNilai.split('.');

        // Tambahkan titik sebagai pemisah ribuan untuk bagian integer
        let integerFormatted = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Gabungkan kembali bagian integer dan desimal
        return `${integerFormatted},${decimalPart}`;
    }
</script>