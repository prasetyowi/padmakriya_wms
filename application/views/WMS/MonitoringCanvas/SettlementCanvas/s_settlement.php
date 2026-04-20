<script type="text/javascript">
var ChannelCode = '';

loadingBeforeReadyPage();

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

    PenerimaanBarang();
});

function PenerimaanBarang() {
    var delivery_order_batch_id = $("#delivery_order_batch_id").val();

    Swal.fire({
        title: 'Loading ...',
        html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
        timerProgressBar: false,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/MonitoringCanvas/SettlementCanvas/PenerimaanBarang') ?>",
        data: {
            delivery_order_batch_id: delivery_order_batch_id
        },
        dataType: "JSON",
        success: function(response) {
            //Alert sukses memuat data
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

            $('#tablesettlementbarang > tbody').empty();

            $.each(response, function(i, v) {
                $("#tablesettlementbarang > tbody").append(`
                    <tr>
                        <td class="text-center">${i + 1}</td>
                        <td class="text-center">${v.sku_kode}</td>
                        <td class="text-center">${v.principle}</td>
                        <td class="text-center">${v.brand}</td>
                        <td class="text-center">${v.sku_nama}</td>
                        <td class="text-center">${v.sku_kemasan}</td>
                        <td class="text-center">${v.sku_satuan}</td>
                        <td class="text-center">${v.qty_canvas}</td>
                        <td class="text-center">${v.qty_terjual}</td>
                        <td class="text-center">${v.qty_sisa}</td>
                        <td class="text-center">${v.qty_barang_kembali}</td>
                        <td class="text-center ${v.Status == 'Cocok' ? 'text-success' : 'text-danger'}">
                            <input type="hidden" id="status_settlement_barang_${i}" value="${v.Status}">
                            ${v.Status}
                        </td>
                    </tr>
                `);
            })
        }
    });
}

function KomparasiInvoice() {
    var delivery_order_batch_id = $("#delivery_order_batch_id").val();

    Swal.fire({
        title: 'Loading ...',
        html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
        timerProgressBar: false,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/MonitoringCanvas/SettlementCanvas/KomparasiInvoice') ?>",
        data: {
            delivery_order_batch_id: delivery_order_batch_id
        },
        dataType: "JSON",
        success: function(response) {
            //Alert sukses memuat data
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

            $('#tablekomparasi > tbody').empty();

            $.each(response, function(i, v) {
                $("#tablekomparasi > tbody").append(`
                        <tr>
                            <td class="text-center">${v.idx}</td>
                            <td class="text-center">${v.groupurut}</td>
                            <td class="text-center">${v.tgl}</td>
                            <td class="text-center">
                                <button type="button" id="btn_document_no${i}" class="btn btn-link" onclick="ViewDetailDO('${v.documentno}')">${v.documentno}</button>
                            </td>
                            <td class="text-center">${v.namaoutlet}</td>
                            <td class="text-center">${v.alamatoutlet}</td>
                            <td class="text-center">${v.keterangan}</td>
                            <td class="text-center">${Math.round(v.nominal)}</td>
                            <td class="text-center">
                            <input type="hidden" id="status_settlement_tunai_${i}" value="${v.nominalkumulatif}">
                                ${Math.round(v.nominalkumulatif)}
                            </td>
                        </tr>
                    `);
            })
        }
    });
}

function PenerimaanTunai() {
    var delivery_order_batch_id = $("#delivery_order_batch_id").val();

    Swal.fire({
        title: 'Loading ...',
        html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
        timerProgressBar: false,
        showConfirmButton: false,
        allowOutsideClick: false
    });

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/MonitoringCanvas/SettlementCanvas/PenerimaanTunai') ?>",
        data: {
            delivery_order_batch_id: delivery_order_batch_id
        },
        dataType: "JSON",
        success: function(response) {
            //Alert sukses memuat data
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

            $('#tablesettlementtunai > tbody').empty();

            $.each(response, function(i, v) {
                $("#tablesettlementtunai > tbody").append(`
                        <tr>
                            <td class="text-center">${v.idx}</td>
                            <td class="text-center">${v.groupurut}</td>
                            <td class="text-center">${v.tgl}</td>
                            <td class="text-center">
                                <button type="button" id="btn_document_no${i}" class="btn btn-link" onclick="ViewDetailDO('${v.documentno}')">${v.documentno}</button>
                            </td>
                            <td class="text-center">${v.namaoutlet}</td>
                            <td class="text-center">${v.alamatoutlet}</td>
                            <td class="text-center">${v.keterangan}</td>
                            <td class="text-center">${Math.round(v.nominal)}</td>
                            <td class="text-center">
                            <input type="hidden" id="status_settlement_tunai_${i}" value="${v.nominalkumulatif}">
                                ${Math.round(v.nominalkumulatif)}
                            </td>
                        </tr>
                    `);
            })
        }
    });
}

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

    PenerimaanBarang();
    // $("#penerimaanBG").hide('slow');
})

$("#slcKomparasi").on('click', function() {
    $("#penerimaanBarang").hide('slow');
    $("#KomparasiTunai").show('slow');
    $("#penerimaanTunai").hide('slow');

    KomparasiInvoice();
    // $("#penerimaanBG").hide('slow');
})

$("#slcTunai").on('click', function() {
    $("#penerimaanBarang").hide('slow');
    $("#KomparasiTunai").hide('slow');
    $("#penerimaanTunai").show('slow');

    PenerimaanTunai();
    // $("#penerimaanBG").hide('slow');
})

// $("#slcBG").on('click', function() {
//     $("#penerimaanBarang").hide('slow');
//     $("#KomparasiTunai").hide('slow');
//     $("#penerimaanTunai").hide('slow');
//     $("#penerimaanBG").show('slow');
// })

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
                    url: "<?= base_url('WMS/MonitoringCanvas/SettlementCanvas/InsertSettlement') ?>",
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
                                    "<?= base_url(); ?>WMS/MonitoringCanvas/SettlementCanvas/SettlementCanvasMenu";
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

function ViewDetailDO(documentno, sku_id) {
    $("#loadingbkb").show();
    $("#previewdetaildo").modal('show');

    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/MonitoringCanvas/SettlementCanvas/GetDOById') ?>",
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
</script>