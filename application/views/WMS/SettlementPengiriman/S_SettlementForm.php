<script type="text/javascript">
    var ChannelCode = '';
    // loadingBeforeReadyPage()

    function message_custom(titleType, iconType, htmlType) {
        Swal.fire({
            title: titleType,
            icon: iconType,
            html: htmlType,
        })
    }

    $("#slcBarang").on('click', function() {
        $("#penerimaanBarang").show('slow');
        $("#KomparasiTunai").hide('slow');
        $("#penerimaanTunai").hide('slow');
        $("#penerimaanBG").hide('slow');
    })

    $("#slcKomparasi").on('click', function() {
        $("#penerimaanBarang").hide('slow');
        $("#KomparasiTunai").show('slow');
        $("#penerimaanTunai").hide('slow');
        $("#penerimaanBG").hide('slow');
    })

    $("#slcTunai").on('click', function() {
        $("#penerimaanBarang").hide('slow');
        $("#KomparasiTunai").hide('slow');
        $("#penerimaanTunai").show('slow');
        $("#penerimaanBG").hide('slow');
    })

    $("#slcBG").on('click', function() {
        $("#penerimaanBarang").hide('slow');
        $("#KomparasiTunai").hide('slow');
        $("#penerimaanTunai").hide('slow');
        $("#penerimaanBG").show('slow');
    })

    function detailTransaksi(do_batch_id, sku_id, sku_kode, sku_nama, sku_satuan, statussettlement) {
        $("#modalDetailTransaksi").modal('show');

        $("#delivery_order_batch_id").val(do_batch_id);
        $("#sku_id").val(sku_id);
        $("#filter_sku_kode").val(sku_kode);
        $("#filter_sku_kode").val(sku_kode);
        // $("#filter_qty_do").val(totalqtydo);
        // $("#filter_qty_aktual").val(totalqtyinout);
        // $("#filter_qty_selisih").val(totalqtyinout - totalqtydo);
        $("#filter_sku_nama").val(sku_nama);
        // $("#filter_sku_kemasan").val(sku_kemasan);
        $("#filter_sku_satuan").val(sku_satuan);
        $("#filter_status").val(statussettlement);

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/SettlementPengiriman/detailTransaksi') ?>",
            data: {
                do_batch_id: do_batch_id,
                sku_id: sku_id
            },
            dataType: "JSON",
            success: function(response) {
                $("#tableskumenu > tbody").empty();

                $.each(response, function(i, v) {
                    var button = '';
                    if (v.documentjenis == "BKB") {
                        button =
                            `<button type="button" class="btn btn-link" onclick="ViewDetailBKB('${v.documentno}','${v.skuid}')">${v.documentno}</button>`;
                    } else if (v.documentjenis == "BTB") {
                        button =
                            `<button type="button" class="btn btn-link" onclick="ViewDetailBTB('${v.documentno}','${v.skuid}')">${v.documentno}</button>`;
                    } else if (v.documentjenis == "DO") {
                        button =
                            `<button type="button" class="btn btn-link" onclick="ViewDetailDO('${v.documentno}','${v.skuid}')">${v.documentno}</button>`;
                    } else if (v.documentjenis == "DOR") {
                        button =
                            `<button type="button" class="btn btn-link" onclick="ViewDetailDOR('${v.documentno}','${v.skuid}')">${v.documentno}</button>`;
                    }

                    $("#tableskumenu > tbody").append(`
					<tr>
						<td class="text-center">${v.idx}</td>
						<td class="text-center">${v.tgl}</td>
						<td class="text-center">${button}</td>
						<td class="text-center">${v.documentjenis}</td>
						<td class="text-center">${v.documentstatus}</td>
						<td class="text-center">${v.qty}</td>
						<td class="text-center">${v.qtykumulatif}</td>
						<td class="text-center">${v.suggestion}</td>
					</tr>
				`);
                });
            }
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

    $("#btn_simpan_settlement").click(function() {
        var delivery_order_batch_id = $("#delivery_order_batch_id").val();
        var jumlah_barang = 0;
        var cek_barang = 0;
        var jumlah_tunai = 0;
        var cek_tunai = 0;
        var cek_error = 0;

        $("#tablesettlementbarang > tbody tr").each(function(idx) {
            if ($("#status_settlement_barang_" + idx).val() == "Cocok") {
                cek_barang++;
            }
            jumlah_barang++;
        });

        $("#tablesettlementtunai > tbody tr").each(function(idx) {
            var nilai_tunai = isNaN(parseInt($("#status_settlement_tunai_" + idx).val())) ? 0 : parseInt($(
                "#status_settlement_tunai_" + idx).val());
            // nilai_tunai = nilai_tunai == 'Nan' ? 0 : nilai_tunai;

            if (idx == $("#tablesettlementtunai > tbody tr").length - 1) {
                if (nilai_tunai == 0) {
                    cek_tunai++;
                }
            }
            jumlah_tunai++;
        });

        if (cek_barang != jumlah_barang) {
            let alert_tes = $('span[name="CAPTION-ALERT-SETTLEMENTBARANGTIDAKCOCOK"]').eq(0).text();
            message_custom("Error", "error", alert_tes);
            cek_error++;
        }

        if (cek_tunai == 0) {
            let alert_tes = $('span[name="CAPTION-ALERT-SETTLEMENTTUNAITIDAKCOCOK"]').eq(0).text();
            message_custom("Error", "error", alert_tes);
            cek_error++;
        }

        if (cek_error == 0) {
            $("#loadingview").show();

            Swal.fire({
                title: "Are you sure ?",
                text: "Please check again!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Save",
                cancelButtonText: "Close"
            }).then((result) => {
                if (result.value == true) {
                    //ajax save data
                    $.ajax({
                        async: false,
                        type: 'POST',
                        url: "<?= base_url('WMS/SettlementPengiriman/InsertSettlement') ?>",
                        data: {
                            delivery_order_batch_id: delivery_order_batch_id,
                            statussettlement: 'Cocok'
                        },
                        success: function(data) {
                            if (data == 1) {
                                $("#loadingview").hide();
                                message_topright("success", "Data Saved Successfully");
                                setTimeout(() => {
                                    location.href =
                                        "<?= base_url(); ?>WMS/SettlementPengiriman/SettlementPengirimanMenu";
                                    // location.reload();
                                }, 500);
                            } else {
                                $("#loadingview").hide();
                                message_topright("error", "Data Saved Failed");
                            }
                        }
                    });
                }
            });
        }
    });

    function ViewDetailDOR(documentno) {
        $("#loadingbkb").show();
        $("#previewdetaildo").modal('show');

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SettlementPengiriman/GetDetailDO') ?>",
            data: {
                documentno: documentno,
                sku_id: sku_id
            },
            success: function(response) {
                if (response) {
                    ChDetailDo(response);
                }
            }
        });
    }

    function ViewDetailBKB(documentno, sku_id) {
        $("#loadingbkb").show();
        $("#previewdetailbkb").modal('show');

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SettlementPengiriman/GetDetailBKB') ?>",
            data: {
                documentno: documentno,
                sku_id: sku_id
            },
            success: function(response) {
                if (response) {
                    ChDetailBKB(response);
                }
            }
        });
    }

    function ChDetailBKB(JSONChannel) {
        $("#tabledetailbkb > tbody").html('');

        var Channel = JSON.parse(JSONChannel);

        if (Channel.BKB != 0) {
            var picking_order_id = Channel.BKB[0].picking_order_id;
            var picking_order_kode = Channel.BKB[0].picking_order_kode;
            var picking_order_aktual_h_id = Channel.BKB[0].picking_order_aktual_h_id;
            var picking_order_aktual_kode = Channel.BKB[0].picking_order_aktual_kode;
            var karyawan_id = Channel.BKB[0].karyawan_id;
            var karyawan_nama = Channel.BKB[0].karyawan_nama;

            $("#picking_order_kode").val(picking_order_kode);
            $("#picking_order_aktual_kode").val(picking_order_aktual_kode);
            $("#karyawan_nama").val(karyawan_nama);

            if ($.fn.DataTable.isDataTable('#tabledetailbkb')) {
                $('#tabledetailbkb').DataTable().destroy();
            }

            $('#tabledetailbkb tbody').empty();

            for (i = 0; i < Channel.BKB.length; i++) {

                var delivery_order_kode = Channel.BKB[i].delivery_order_kode;
                var principal = Channel.BKB[i].principal;
                var brand = Channel.BKB[i].brand;
                var sku_id = Channel.BKB[i].sku_id;
                var sku_stock_qty_ambil = Channel.BKB[i].sku_stock_qty_ambil;
                var sku_kode = Channel.BKB[i].sku_kode;
                var sku_nama_produk = Channel.BKB[i].sku_nama_produk;
                var sku_kemasan = Channel.BKB[i].sku_kemasan;
                var sku_satuan = Channel.BKB[i].sku_satuan;
                var karyawan_nama = Channel.BKB[i].karyawan_nama;
                var picking_order_plan_id = Channel.BKB[i].picking_order_plan_id;
                var delivery_order_id = Channel.BKB[i].delivery_order_id;
                var sku_stock_id = Channel.BKB[i].sku_stock_id;
                var sku_stock_expired_date = Channel.BKB[i].sku_stock_expired_date;
                var sku_stock_expired_date_plan = Channel.BKB[i].sku_stock_expired_date_plan;
                var sku_stock_qty_ambil_plan = Channel.BKB[i].sku_stock_qty_ambil_plan;

                var strmenu = '';

                strmenu = strmenu + '<tr>';
                strmenu = strmenu + '	<td>' + delivery_order_kode + '</td>';
                strmenu = strmenu + '	<td>' + sku_kode + '</td>';
                strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
                strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
                strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
                strmenu = strmenu + '	<td>' + sku_stock_qty_ambil_plan + '</td>';
                strmenu = strmenu + '	<td>' + sku_stock_expired_date_plan + '</td>';
                strmenu = strmenu + '	<td>' + sku_stock_qty_ambil + '</td>';
                strmenu = strmenu + '	<td>' + sku_stock_expired_date + '</td>';
                strmenu = strmenu + '</tr>';

                $("#tabledetailbkb > tbody").append(strmenu);
            }

            $("#loadingbkb").hide();
            $('#tabledetailbkb').DataTable({
                "lengthMenu": [
                    [10],
                    [10]
                ],
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false
            });
        }

    }

    function ViewDetailBTB(documentno) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SettlementPengiriman/GetSettlementDetailMenu') ?>",
            data: {
                documentno: documentno
            },
            success: function(response) {
                if (response) {
                    ChSettlementDetailMenu(response);
                }
            }
        });
    }

    function ViewDetailDO(documentno, sku_id) {
        $("#loadingbkb").show();
        $("#previewdetaildo").modal('show');

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SettlementPengiriman/GetDOById') ?>",
            data: {
                documentno: documentno
            },
            success: function(response) {
                if (response) {
                    ChDetailDo(response);
                }
            }
        });
    }

    function ChDetailDo(JSONChannel) {
        $("#tabledodetail > tbody").html('');

        $("#delivery_order_id").html('');
        $("#delivery_order_kode").html('');
        $("#tipe_delivery_order_id").html('');
        $("#tipe_delivery_order_nama").html('');
        $("#delivery_order_status").html('');
        $("#delivery_order_tgl_buat_do").html('');
        $("#delivery_order_tgl_expired_do").html('');
        $("#delivery_order_tgl_surat_jalan").html('');
        $("#delivery_order_tgl_rencana_kirim").html('');
        $("#client_wms_nama").html('');
        $("#client_wms_alamat").html('');
        $("#delivery_order_keterangan").html('');
        $("#delivery_order_tipe_pembayaran").html('');
        $("#delivery_order_tipe_layanan").html('');
        $("#delivery_order_kirim_nama").html('');
        $("#delivery_order_kirim_alamat").html('');
        $("#delivery_order_kirim_area").html('');
        $("#delivery_order_ambil_nama").html('');
        $("#delivery_order_ambil_alamat").html('');
        $("#delivery_order_ambil_area").html('');

        var Channel = JSON.parse(JSONChannel);

        if (Channel.HeaderDO != 0) {
            var delivery_order_id = Channel.HeaderDO[0].delivery_order_id;
            var delivery_order_kode = Channel.HeaderDO[0].delivery_order_kode;
            var tipe_delivery_order_id = Channel.HeaderDO[0].tipe_delivery_order_id;
            var tipe_delivery_order_alias = Channel.HeaderDO[0].tipe_delivery_order_alias;
            var delivery_order_status = Channel.HeaderDO[0].delivery_order_status;
            var delivery_order_tgl_buat_do = Channel.HeaderDO[0].delivery_order_tgl_buat_do;
            var delivery_order_tgl_expired_do = Channel.HeaderDO[0].delivery_order_tgl_expired_do;
            var delivery_order_tgl_surat_jalan = Channel.HeaderDO[0].delivery_order_tgl_surat_jalan;
            var delivery_order_tgl_rencana_kirim = Channel.HeaderDO[0].delivery_order_tgl_rencana_kirim;
            var client_wms_nama = Channel.HeaderDO[0].client_wms_nama;
            var client_wms_alamat = Channel.HeaderDO[0].client_wms_alamat;
            var delivery_order_keterangan = Channel.HeaderDO[0].delivery_order_keterangan;
            var delivery_order_tipe_pembayaran = Channel.HeaderDO[0].delivery_order_tipe_pembayaran;
            var delivery_order_tipe_layanan = Channel.HeaderDO[0].delivery_order_tipe_layanan;
            var delivery_order_kirim_nama = Channel.HeaderDO[0].delivery_order_kirim_nama;
            var delivery_order_kirim_alamat = Channel.HeaderDO[0].delivery_order_kirim_alamat;
            var delivery_order_kirim_area = Channel.HeaderDO[0].delivery_order_kirim_area;
            var delivery_order_ambil_nama = Channel.HeaderDO[0].delivery_order_ambil_nama;
            var delivery_order_ambil_alamat = Channel.HeaderDO[0].delivery_order_ambil_alamat;
            var delivery_order_ambil_area = Channel.HeaderDO[0].delivery_order_ambil_area;

            if (delivery_order_ambil_nama == "") {
                $("#panel-factory").hide();
            }

            $("#delivery_order_id").append(delivery_order_id);
            $("#delivery_order_kode").append(delivery_order_kode);
            $("#tipe_delivery_order_id").append(tipe_delivery_order_id);
            $("#tipe_delivery_order_nama").append(tipe_delivery_order_alias);
            $("#delivery_order_status").append(delivery_order_status);
            $("#delivery_order_tgl_buat_do").append(delivery_order_tgl_buat_do);
            $("#delivery_order_tgl_expired_do").append(delivery_order_tgl_expired_do);
            $("#delivery_order_tgl_surat_jalan").append(delivery_order_tgl_surat_jalan);
            $("#delivery_order_tgl_rencana_kirim").append(delivery_order_tgl_rencana_kirim);
            $("#client_wms_nama").append(client_wms_nama);
            $("#client_wms_alamat").append(client_wms_alamat);
            $("#delivery_order_keterangan").append(delivery_order_keterangan);
            $("#delivery_order_tipe_pembayaran").append(delivery_order_tipe_pembayaran);
            $("#delivery_order_tipe_layanan").append(delivery_order_tipe_layanan);
            $("#delivery_order_kirim_nama").append(delivery_order_kirim_nama);
            $("#delivery_order_kirim_alamat").append(delivery_order_kirim_alamat);
            $("#delivery_order_kirim_area").append(delivery_order_kirim_area);
            $("#delivery_order_ambil_nama").append(delivery_order_ambil_nama);
            $("#delivery_order_ambil_alamat").append(delivery_order_ambil_alamat);
            $("#delivery_order_ambil_area").append(delivery_order_ambil_area);

            if ($.fn.DataTable.isDataTable('#tabledodetail')) {
                $('#tabledodetail').DataTable().destroy();
            }

            $('#tabledodetail tbody').empty();

            for (i = 0; i < Channel.DetailDO.length; i++) {
                var sku_id = Channel.DetailDO[i].sku_id;
                var sku_kode = Channel.DetailDO[i].sku_kode;
                var sku_nama_produk = Channel.DetailDO[i].sku_nama_produk;
                var sku_kemasan = Channel.DetailDO[i].sku_kemasan;
                var sku_satuan = Channel.DetailDO[i].sku_satuan;
                var sku_request_expdate = Channel.DetailDO[i].sku_request_expdate;
                var sku_keterangan = Channel.DetailDO[i].sku_keterangan;
                var sku_qty = Channel.DetailDO[i].sku_qty;
                var sku_qty_kirim = Channel.DetailDO[i].sku_qty_kirim;

                var strmenu = '';

                strmenu = strmenu + '<tr>';
                strmenu = strmenu + '	<td>' + sku_kode + '</td>';
                strmenu = strmenu + '	<td></td>';
                strmenu = strmenu + '	<td>' + sku_nama_produk + '</td>';
                strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
                strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
                strmenu = strmenu + '	<td>' + sku_request_expdate + '</td>';
                strmenu = strmenu + '	<td>' + sku_keterangan + '</td>';
                strmenu = strmenu + '	<td>' + sku_qty + '</td>';
                strmenu = strmenu + '	<td>' + sku_qty_kirim + '</td>';
                strmenu = strmenu + '</tr>';

                $("#tabledodetail > tbody").append(strmenu);
            }

            $("#loadingbkb").hide();
            $('#tabledodetail').DataTable({
                "lengthMenu": [
                    [-1],
                    ['All']
                ],
                "paging": false,
                "ordering": false,
                "info": false,
                "searching": false
            });
        }

    }

    function ResetForm() {
        <?php
        if ($Menu_Access["U"] == 1) {
        ?>
            $("#loadingview").hide();
            $("#btn_simpan_settlement").prop("disabled", false);
        <?php
        }
        ?>

        <?php
        if ($Menu_Access["C"] == 1) {
        ?>
            $("#loadingview").hide();
            $("#btn_simpan_settlement").prop("disabled", false);
        <?php
        }
        ?>

    }

    $(document).ready(function() {
        if ($('#filter_fdjr_date').length > 0) {
            $('#filter_fdjr_date').daterangepicker({
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
        // $('#tablesettlementbarang, #tablesettlementtunai, #tablepenerimaansettlementtunai, #tablepenerimaanbgsettlementtunai')
        //     .dataTable({
        //         "lengthMenu": [
        //             [-1],
        //             ['All']
        //         ]
        //     });

        // $('#tablesettlementtunai').dataTable();
        // $('#tablepenerimaansettlementtunai').dataTable();
        // $('#tablepenerimaanbgsettlementtunai').dataTable();

        getData();
    });

    function getData() {
        var do_batch_id = $("#delivery_order_batch_id").val();

        Swal.fire({
            title: 'Loading ...',
            html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
            timerProgressBar: false,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/SettlementPengiriman/getData') ?>",
            data: {
                do_batch_id: do_batch_id
            },
            dataType: "JSON",
            success: function(response) {
                //Alert sukses memuat data
                setTimeout(function() {
                    Swal.fire({
                        title: 'Sukses!',
                        text: 'Berhasil memuat data.',
                        icon: 'success',
                        timer: 3000,
                        showCancelButton: false,
                        showConfirmButton: true,
                        allowOutsideClick: false
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

                checkDataTable();

                // // SETTLEMENTHEADER
                $.each(response.SettlementHeader, function(i, v) {
                    $('#filter_fdjr_no').val(v.delivery_order_batch_kode);
                    $('#txt_driver_fdjr').val(v.karyawan_nama);
                    $('#text_tgl_kirim').val(v.delivery_order_batch_tanggal_kirim);

                    v.delivery_order_batch_status == 'completed' ? $('#proses_barang_selisih, #btn_simpan_settlement').attr('style', 'display: none') : $('#proses_barang_selisih, #btn_simpan_settlement').removeAttr('style');
                })

                // // PENERIMAAN BARANG
                $.each(response.PenerimaanBarang, function(i, v) {
                    $("#tablesettlementbarang tbody").append(`
                            <tr>
                                    <td class="text-center" style="vertical-align: middle;">${i + 1}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.principle}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.brand}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.sku_kode}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.sku_nama}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.sku_satuan}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.qtyorder}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.qtyterkirim}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.qtygagal}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.qtyreturterkirim}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.qtyreturgagal}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.qtybtb}</td>
                                    <td class="text-center ${v.status_settlement == 'Cocok' ? 'text-success' : 'text-danger'}" style="vertical-align: middle;">
                                        <input type="hidden" id="status_settlement_barang_${i}" value="${v.status_settlement}">
                                        ${v.status_settlement}
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-primary" title="Lihat detail transaksi" onclick="detailTransaksi('${do_batch_id}', '${v.sku_id}', '${v.sku_kode}', '${v.sku_nama}', '${v.sku_satuan}', '${v.status_settlement}')"><i class="fas fa-eye"></i></button>
                                    </td>
                            </tr>
                    `)
                })

                // // PENERIMAAN TUNAI
                $.each(response.PenerimaanTunai, function(i, v) {
                    $("#tablepenerimaansettlementtunai tbody").append(`
                             <tr>
                                    <td class="text-center" style="vertical-align: middle;">${v.idx}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.groupurut}</td>
                                    <td class="text-center" style="vertical-align: middle;">${v.tgl}
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <button type="button" id="btn_document_no${i}" class="btn btn-link" onclick="ViewDetailDO('${v.documentno}')">${v.documentno}></button>
                                    </td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.namaoutlet}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.alamatoutlet}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        ${v.keterangan}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                       ${Math.round(v.nominal)}</td>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <input type="hidden" id="status_settlement_tunai_${i}" value="${v.nominalkumulatif}">
                                        ${Math.round(v.nominalkumulatif)}
                                    </td>
                            </tr>
                    `)
                })

                // // PENERIMAAN BG
                if (response.PenerimaanBG.length > 0) {
                    $.each(response.PenerimaanBG, function(i, v) {
                        $("#tablepenerimaanbgsettlementtunai tbody").append(`
                            <tr>
                                    <td class="text-center" style="vertical-align: middle;">${v.idx}</td>
                                    <td class="text-center" style="vertical-align: middle;">${v.groupurut}</td>
                                    <td class="text-center" style="vertical-align: middle;">${v.tgl}</td>
                                    <td class="text-center" style="vertical-align: middle;"><button type="button" id="btn_document_no${i}" class="btn btn-link" onclick="ViewDetailDO('${v.documentno}')">${v.documentno}</button></td>
                                    <td class="text-center" style="vertical-align: middle;">${v.namaoutlet}</td>
                                    <td class="text-center" style="vertical-align: middle;">${v.alamatoutlet}</td>
                                    <td class="text-center" style="vertical-align: middle;">${v.keterangan}</td>
                                    <td class="text-center" style="vertical-align: middle;">${Math.round(v.nominal)}</td>
                                    <td class="text-center" style="vertical-align: middle;"><input type="hidden" id="status_settlement_tunai_${i}" value="${v.nominalkumulatif}">${Math.round(v.nominalkumulatif)}</td>
                            </tr>
                    `)
                    })
                }

                // KOMPARASIINVOICE
                $.each(response.KomparasiInvoice, function(i, v) {
                    $("#tablesettlementtunai tbody").append(`
                            <tr>
                                        <td class="text-center" style="vertical-align: middle;">${v.idx}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            ${v.groupurut}</td>
                                        <td class="text-center" style="vertical-align: middle;">${v.tgl}
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <button type="button" id="btn_document_no${i}" class="btn btn-link" onclick="ViewDetailDO('${v.documentno}')">${v.documentno}</button>
                                        </td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            ${v.namaoutlet}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            ${v.alamatoutlet}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            ${v.keterangan}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            ${Math.round(v.nominal)}</td>
                                        <td class="text-center" style="vertical-align: middle;">
                                            <input type="hidden" id="status_settlement_tunai_${i}" value="${v.nominalkumulatif}">
                                            ${Math.round(v.nominalkumulatif)}
                                        </td>
                            </tr>
                    `)
                })

                var endDataForKomparison = 0;

                // Memeriksa jika ada data pada KomparasiInvoice
                if (response.KomparasiInvoice && response.KomparasiInvoice.length > 0) {
                    // Mendapatkan nilai terakhir dari nominalkumulatif
                    endDataForKomparison = parseInt(response.KomparasiInvoice[response.KomparasiInvoice.length - 1].nominalkumulatif);
                }

                $("#tablesettlementtunai tfoot").append(`
                                 <tr style="font-size: 1.3em;font-weight: bold; background: grey; color: white">
                                                <td colspan=" 9">
                                                    <div style="display: flex;justify-content:start; align-items:center;width:100%;gap:3px;padding: 5px">
                                                        <div style="width: 33.33%;">
                                                            <div style="display: flex;justify-content:start; align-items:center;width:100%;gap:3px;padding: 5px">
                                                                <div style="width:38%"><span name="Toleransi">Toleransi</span></div>
                                                                <div style="width:2%">:</div>
                                                                <div style="width:60%">+/-${response.getToleransi}</div>
                                                            </div>
                                                        </div>
                                                        <div style="width: 33.33%;">
                                                            <div style="display: flex;justify-content:start; align-items:center;width:100%;gap:3px;padding: 5px">
                                                                <div style="width:38%"><span name="CAPTION-TOLERANSI">Kumulatif Total</span>
                                                                </div>
                                                                <div style="width:2%">:</div>
                                                                <div style="width:60%">${endDataForKomparison}</div>
                                                            </div>
                                                        </div>
                                                        <div style="width: 33.33%;">
                                                            <div style="display: flex;justify-content:start; align-items:center;width:100%;gap:3px;padding: 5px">
                                                                <div style="width:38%"><span name="CAPTION-STATUS">Status</span></div>
                                                                <div style="width:2%">:</div>
                                                                <div style="width:60%">
                                                                   ${ (endDataForKomparison >= (parseInt(response.getToleransi) * -1)) && (endDataForKomparison <= parseInt(response.getToleransi)) ? 'Cocok' : 'Tidak Cocok'}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                 </tr>
                 `);

                // getPengirimanArea
                var str = '';
                $.each(response.getPengirimanArea, function(i, v) {
                    str += v.area_nama + ', ';
                })

                $("#fdjr_area").text(str);

                $('#tablesettlementbarang, #tablesettlementtunai, #tablepenerimaansettlementtunai, #tablepenerimaanbgsettlementtunai')
                    .DataTable({
                        "lengthMenu": [
                            [-1],
                            ['All']
                        ]
                    });
            }
        })
    }

    function checkDataTable() {
        $('#tablesettlementbarang tbody').empty();
        $('#tablepenerimaansettlementtunai tbody').empty();
        $('#tablepenerimaanbgsettlementtunai tbody').empty();
        $('#tablesettlementtunai tbody').empty();
        $('#tablesettlementtunai tfoot').empty();

        if ($.fn.DataTable.isDataTable('#tablesettlementbarang')) {
            $('#tablesettlementbarang').DataTable().destroy();
        }

        if ($.fn.DataTable.isDataTable('#tablepenerimaansettlementtunai')) {
            $('#tablepenerimaansettlementtunai').DataTable().destroy();
        }

        if ($.fn.DataTable.isDataTable('#tablepenerimaanbgsettlementtunai')) {
            $('#tablepenerimaanbgsettlementtunai').DataTable().destroy();
        }

        if ($.fn.DataTable.isDataTable('#tablesettlementtunai')) {
            $('#tablesettlementtunai').DataTable().destroy();
        }
    }
</script>