<script type="text/javascript">
var ChannelCode = '';
loadingBeforeReadyPage()
$(document).ready(
    function() {
        GetSuratTugasPengirimanMenu();
        $('#filter_fdjr_driver').select2();
        $('#filter_fdjr_status').select2();
        $('#slcaddcheckerbkbstandar').select2();
        $('#slctipepb').select2();
    }
);

function GetSuratTugasPengirimanMenu() {
    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/SuratTugasPengiriman/GetSuratTugasPengirimanMenu') ?>",
        //data: "Location="+ Location,
        success: function(response) {
            if (response) {
                ChSuratTugasPengirimanMenu(response);
            }
        }
    });
}

//var DTABLE;

function ChSuratTugasPengirimanMenu(JSONChannel) {
    $("#tablefdjrmenu > tbody").html('');
    $("#filter_fdjr_driver").html('');
    $("#filter_fdjr_status").html('');

    var Channel = JSON.parse(JSONChannel);

    var StatusC = Channel.AuthorityMenu[0].status_c;
    var StatusU = Channel.AuthorityMenu[0].status_u;
    var StatusD = Channel.AuthorityMenu[0].status_d;

    if (Channel.Driver != 0) {
        $("#filter_fdjr_driver").append('<option value="">All</option>');

        for (i = 0; i < Channel.Driver.length; i++) {
            karyawan_id = Channel.Driver[i].karyawan_id;
            karyawan_nama = Channel.Driver[i].karyawan_nama;
            $("#filter_fdjr_driver").append('<option value="' + karyawan_id + '">' + karyawan_nama + '</option>');
        }
    }

    if (Channel.StatusFDJR != 0) {
        $("#filter_fdjr_status").append('<option value="">All</option>');

        for (i = 0; i < Channel.StatusFDJR.length; i++) {
            delivery_order_batch_status = Channel.StatusFDJR[i].delivery_order_batch_status;
            $("#filter_fdjr_status").append('<option value="' + delivery_order_batch_status + '">' +
                delivery_order_batch_status + '</option>');
        }
    }
}

$("#btnviewfdjr").click(
    function() {
        var Tgl_FDJR = $("#filter_fdjr_date").val();
        var No_FDJR = $("#filter_fdjr_no").val();
        var karyawan_id = $("#filter_fdjr_driver").val();
        var Status_FDJR = $('#filter_fdjr_status').val();

        $("#loadingview").show();

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/GetSuratTugasPengirimanTable') ?>",
            data: {
                Tgl_FDJR: Tgl_FDJR,
                No_FDJR: No_FDJR,
                karyawan_id: karyawan_id,
                Status_FDJR: Status_FDJR
            },
            success: function(response) {
                if (response) {
                    // $('#tablefdjrmenu').DataTable().clear().draw();
                    ChSuratTugasPengirimanTable(response);
                }
            }
        });
    }
);

function ChSuratTugasPengirimanTable(JSONChannel) {
    $("#tablefdjrmenu > tbody").html('');

    var Channel = JSON.parse(JSONChannel);
    var no = 1;

    if (Channel.SuratTugasPengiriman != 0) {
        if ($.fn.DataTable.isDataTable('#tablefdjrmenu')) {
            $('#tablefdjrmenu').DataTable().clear();
            $('#tablefdjrmenu').DataTable().destroy();
        }

        $('#tablefdjrmenu tbody').empty();

        var arr = [];

        $.each(Channel.SuratTugasPengiriman, function(i, v) {
            if (arr[v.delivery_order_batch_kode]) {
                arr[v.delivery_order_batch_kode] += 1;
            } else {
                arr[v.delivery_order_batch_kode] = 1;
            }
        })

        var cek_do_batch_kode = '';
        for (i = 0; i < Channel.SuratTugasPengiriman.length; i++) {
            var delivery_order_batch_id = Channel.SuratTugasPengiriman[i].delivery_order_batch_id;
            var delivery_order_batch_kode = Channel.SuratTugasPengiriman[i].delivery_order_batch_kode;
            var picking_list_id = Channel.SuratTugasPengiriman[i].picking_list_id;
            var picking_list_kode = Channel.SuratTugasPengiriman[i].picking_list_kode;
            var picking_order_id = Channel.SuratTugasPengiriman[i].picking_order_id;
            var picking_order_kode = Channel.SuratTugasPengiriman[i].picking_order_kode;
            var delivery_order_batch_tanggal_kirim = Channel.SuratTugasPengiriman[i].delivery_order_batch_tanggal_kirim;
            var delivery_order_batch_status = Channel.SuratTugasPengiriman[i].delivery_order_batch_status;
            var karyawan_id = Channel.SuratTugasPengiriman[i].karyawan_id;
            var karyawan_nama = Channel.SuratTugasPengiriman[i].karyawan_nama;
            var tipe_delivery_order_id = Channel.SuratTugasPengiriman[i].tipe_delivery_order_id;
            var tipe_delivery_order_alias = Channel.SuratTugasPengiriman[i].tipe_delivery_order_alias;
            var serah_terima_kirim_id = Channel.SuratTugasPengiriman[i].serah_terima_kirim_id;
            var serah_terima_kirim_kode = Channel.SuratTugasPengiriman[i].serah_terima_kirim_kode;
            var settlement_status = Channel.SuratTugasPengiriman[i].settlement_status;
            var jml_btb = Channel.SuratTugasPengiriman[i].jml_btb;

            var strmenu = '';

            if (arr[delivery_order_batch_kode]) {
                if (cek_do_batch_kode == delivery_order_batch_kode) {
                    strmenu = strmenu + '<tr>';
                    strmenu = strmenu +
                        '<td><a href="<?php echo base_url(); ?>WMS/Distribusi/PermintaanBarang/PickingDetail/' +
                        picking_list_id + '" class="btn btn-link" target="_blank">' + picking_list_kode + '</a></td>';
                    strmenu = strmenu +
                        '<td><a href="<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=' +
                        picking_order_kode + '" class="btn btn-link" target="_blank">' + picking_order_kode +
                        '</a></td>';
                    strmenu = strmenu +
                        '<td><a href="<?php echo base_url(); ?>WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/' +
                        serah_terima_kirim_id + '" class="btn btn-link" target="_blank">' + serah_terima_kirim_kode +
                        '</a></td>';
                    strmenu = strmenu + '</tr>';
                } else {
                    cek_do_batch_kode = delivery_order_batch_kode;

                    strmenu = strmenu + '<tr>';
                    strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                        delivery_order_batch_kode] + '">' + no + '</td>';
                    strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                        delivery_order_batch_kode] + '">' + delivery_order_batch_tanggal_kirim + '</td>';
                    strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                        delivery_order_batch_kode] + '">' + karyawan_nama + '</td>';
                    strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                        delivery_order_batch_kode] + '">' + delivery_order_batch_status + '</td>';
                    strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                        delivery_order_batch_kode] + '">' + tipe_delivery_order_alias + '</td>';
                    strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                            delivery_order_batch_kode] +
                        '"><a href="<?php echo base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/detail/?id=' +
                        delivery_order_batch_id + '" class="btn btn-link" target="_blank">' +
                        delivery_order_batch_kode + '</a></td>';
                    strmenu = strmenu +
                        '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PermintaanBarang/PickingDetail/' +
                        picking_list_id + '" class="btn btn-link" target="_blank">' + picking_list_kode + '</a></td>';
                    strmenu = strmenu +
                        '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=' +
                        picking_order_kode + '" class="btn btn-link" target="_blank">' + picking_order_kode +
                        '</a></td>';
                    strmenu = strmenu +
                        '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/' +
                        serah_terima_kirim_id + '" class="btn btn-link" target="_blank">' + serah_terima_kirim_kode +
                        '</a></td>';
                    strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                        delivery_order_batch_kode] + '">' + settlement_status + '</td>';
                    // strmenu = strmenu + '	<td><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' + delivery_order_batch_id + '" class="btn btn-link" target="_blank">Closing Pengiriman</a></td>';
                    if (delivery_order_batch_status == "in transit" || delivery_order_batch_status ==
                        "In Process Closing" || delivery_order_batch_status == "In Process Closing Delivery") {
                        strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                                delivery_order_batch_kode] +
                            '"><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' +
                            delivery_order_batch_id +
                            '" class="btn btn-link" target="_blank">Closing Pengiriman</a></td>';
                    } else if (delivery_order_batch_status == "In Process Receiving Outlet") {
                        strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                                delivery_order_batch_kode] +
                            '"><a href="<?php echo base_url(); ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=' +
                            delivery_order_batch_id +
                            '" class="btn btn-link" target="_blank">Proses BTB</a><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' +
                            delivery_order_batch_id +
                            '" class="btn btn-link" target="_blank">View Closing Delivery</a></td>';
                    } else if (delivery_order_batch_status == "Closing Delivery Confirm" ||
                        delivery_order_batch_status == "Confirmed" || delivery_order_batch_status == "completed") {

                        strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                            delivery_order_batch_kode] + '">';

                        if (jml_btb > 0) {
                            strmenu = strmenu +
                                ' <a href = "<?php echo base_url(); ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=' +
                                delivery_order_batch_id + '" class="btn btn-link" target="_blank">View BTB</a>';
                        }

                        strmenu = strmenu +
                            '<a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' +
                            delivery_order_batch_id +
                            '" class="btn btn-link" target="_blank">View Closing Delivery</a>';
                        strmenu = strmenu + '</td>';
                    } else {
                        strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[
                            delivery_order_batch_kode] + '"></td>';
                    }
                    strmenu = strmenu + '</tr>';
                }
            }

            no++;

            $("#tablefdjrmenu > tbody").append(strmenu);
        }

        $("#loadingview").hide();

        $('#tablefdjrmenu').DataTable({
            "lengthMenu": [
                [50],
                [50]
            ]
        });
    } else {
        ResetForm();
    }
}

$("#btnback").click(
    function() {
        ResetForm();
        GetSuratTugasPengirimanMenu();
    }
);

function ResetForm() {
    <?php
		if ($Menu_Access["U"] == 1) {
		?>
    $("#txtupdatechannelnama").val('');
    $("#cbupdatechanneljenis").prop('selectedIndex', 0);
    $("#chupdatechannelsppbr").prop('checked', false);
    $("#chupdatechanneltipeecomm").prop('checked', false);
    $("#chupdatechannelisactive").prop('checked', false);

    $("#loadingview").hide();
    $("#btnsaveupdatenewchannel").prop("disabled", false);
    <?php
		}
		?>

    <?php
		if ($Menu_Access["C"] == 1) {
		?>
    $("#txtchannelnama").val('');
    $("#cbchanneljenis").prop('selectedIndex', 0);
    $("#chchannelsppbr").prop('checked', false);
    $("#chchanneltipeecomm").prop('checked', false);
    $("#chchannelisactive").prop('checked', false);

    $("#loadingview").hide();
    $("#btnsaveaddnewchannel").prop("disabled", false);
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

});
</script>