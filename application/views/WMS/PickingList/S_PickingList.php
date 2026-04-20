<script type="text/javascript">
// loadingBeforeReadyPage()
$(document).ready(function() {
    // alert('aa')
    $('.select2').select2({
        width: '100%'
    });

    if ($('input[name="daterange"]').length > 0) {
        $('input[name="daterange"]').daterangepicker({
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

$("#driver").on('change', function() {
    var driver = $("#driver").val();

    $.ajax({
        type: "POST",
        url: "<?= base_url('WMS/Distribusi/PermintaanBarang/getFDJRByDriver') ?>",
        data: {
            driver: driver
        },
        dataType: "JSON",
        success: function(response) {
            if (response.length > 0) {
                $("#fdjr").html('');
                $("#fdjr").append('<option value="">-- Pilih FDJR --</option>');
                $.each(response, function(i, v) {
                    $("#fdjr").append(
                        `<option value="${v.delivery_order_batch_id}">${v.delivery_order_batch_kode}</option>`
                    );
                })
            }
        }
    })
})

function getDataPickingList() {
    Swal.showLoading()
    $.ajax({
        type: 'GET',
        url: "<?= base_url('WMS/Distribusi/PermintaanBarang/getDataPickingList') ?>",
        data: {

        },
        dataType: "JSON",
        success: function(response) {

            let no = 1;
            let data = response;

            if ($.fn.DataTable.isDataTable('#tablePickOrder')) {
                $('#tablePickOrder').DataTable().destroy();
            }

            $("#tablePickOrder tbody").empty();
            $("#tablePickOrder tbody").html('');
            if (data.length > 0) {

                $.each(data, function() {
                    var packed = 'Yes'
                    if (this.ispacking == 0) {
                        var packed = 'No'
                    }
                    $('#tablePickOrder tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; ' >${no}</td>
                                    <td style='vertical-align:middle; ' >${this.tgl_picking}</td>
                                    <td style='vertical-align:middle; ' >${this.picking_list_kode}</td>
                                    <td style='vertical-align:middle; ' >${this.do_batch_kode}</td>
                                    <td style='vertical-align:middle; ' >${this.karyawan_nama}</td>
                                    <td style='vertical-align:middle; ' >${this.tipe_delivery_order_alias}</td>
                                    <td style='vertical-align:middle; ' >${this.nama_layanan}</td>
                                    <td style='vertical-align:middle; ' >${getStringOfArrayString(this.area)}</td>
                                    <td style='vertical-align:middle; ' >${this.picking_list_status}</td>
                                    <td style='vertical-align:middle; ' ><a class="btn btn-info" target="_blank" href="<?php echo site_url('WMS/Distribusi/PermintaanBarang/PickingDetail/') ?>${this.picking_list_id}">Detail</button></td>
                                </tr>
                            `);

                    no++;
                });
            } else {
                $("#tablePickOrder tbody").html('');
            }

            $('#tablePickOrder').DataTable();
            swal.close();
        }
    });
}

function getStringOfArrayString(data) {
    let string = "";

    data.forEach(datum => string += datum + ", ");

    return string.slice(0, -2);
}

function getDataPickingListSearch() {
    // var pick_id = $("#pick_id").val();
    // var status = $("#status").val();
    var tgl = $("#tgl").val();
    var driver = $("#driver").val();
    var fdjr = $("#fdjr").val();
    // var area = $("#area").val();
    // var tipe_delivery_order_id = $("#tipe_delivery_order_id").val();
    // var tipe_layanan = $("#tipe_layanan").val();
    // var tipe_pengiriman = $("#tipe_pengiriman").val();

    // Swal.fire({
    //     title: 'Auto close alert!',
    //     html: 'I will close in <b></b> milliseconds.',
    //     timer: 2000,
    //     timerProgressBar: true,
    //     didOpen: () => {
    //         Swal.showLoading()
    //         const b = Swal.getHtmlContainer().querySelector('b')
    //         timerInterval = setInterval(() => {
    //         b.textContent = Swal.getTimerLeft()
    //         }, 100)
    //     },
    //     willClose: () => {
    //         clearInterval(timerInterval)
    //     }
    // })
    Swal.showLoading()
    $.ajax({
        type: 'GET',
        url: "<?= base_url('WMS/Distribusi/PermintaanBarang/getDataPickingListSearch') ?>",
        data: {
            // pick_id: pick_id,
            // tgl_picking_list: tgl,
            // status: status,
            // area: area,
            // tipe_delivery_order_id: tipe_delivery_order_id,
            // tipe_pengiriman: tipe_pengiriman,
            // tipe_layanan: tipe_layanan
            tgl: tgl,
            driver: driver,
            fdjr: fdjr
        },
        dataType: "JSON",
        success: function(response) {

            let no = 1;
            let data = response;

            if ($.fn.DataTable.isDataTable('#tablePickOrder')) {
                $('#tablePickOrder').DataTable().destroy();
            }

            $("#tablePickOrder tbody").empty();
            $("#tablePickOrder tbody").html('');
            // $("#tablePickOrder tbody").html('');
            if (data.length > 0) {

                $.each(data, function() {
                    var packed = 'Yes'
                    if (this.ispacking == 0) {
                        var packed = 'No'
                    }
                    $('#tablePickOrder tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; ' >${no}</td>
                                    <td style='vertical-align:middle; ' >${this.tgl_picking}</td>
                                    <td style='vertical-align:middle; ' >${this.picking_list_kode}</td>
                                    <td style='vertical-align:middle; ' >${this.do_batch_kode}</td>
                                    <td style='vertical-align:middle; ' >${this.karyawan_nama}</td>
                                    <td style='vertical-align:middle; ' >${this.tipe_delivery_order_alias}</td>
                                    <td style='vertical-align:middle; ' >${this.nama_layanan}</td>
                                    <td style='vertical-align:middle; ' >${getStringOfArrayString(this.area)}</td>
                                    <td style='vertical-align:middle; ' >${this.picking_list_status}</td>
                                    <td style='vertical-align:middle; ' ><a class="btn btn-info" target="_blank" href="<?php echo site_url('WMS/Distribusi/PermintaanBarang/PickingDetail/') ?>${this.picking_list_id}">Detail</button></td>
                                </tr>
                            `);

                    no++;
                });
            } else {
                $("#tablePickOrder tbody").html('');
            }

            $('#tablePickOrder').DataTable();
            swal.close();
        }
    });
}

function viewDetail(id, kode) {
    window.location.href = "http://google.com";
    //  $.ajax({
    //         type: 'GET',
    //         url: "<?= base_url('WMS/Distribusi/PermintaanBarang/getDataPickingListDetail') ?>",
    //         data: {
    //            picking_list_id : id
    //         },
    //         success: function(response) {

    //             let no = 1;
    //             let data = response;

    //             if( $.fn.DataTable.isDataTable('#tablesobosnet') ) 
    //             {
    //                 $('#tablePickOrder').DataTable().destroy();
    //             }

    //             $("#tablePickOrder tbody").empty();
    //             //     $("#tablePickOrder tbody").html('');
    //             // if (data.length > 0) {

    //             //     $.each(data, function() {
    //             //         var packed = 'Yes'
    //             //         if (this.ispacking==0) {
    //             //             var packed = 'No'
    //             //         }
    //             //         $('#tablePickOrder tbody').append(`
    //             //             <tr>
    //             //                 <td style='vertical-align:middle; ' >${no}</td>
    //             //                 <td style='vertical-align:middle; ' >${this.tgl_picking}</td>
    //             //                 <td style='vertical-align:middle; ' >${this.picking_list_kode}</td>
    //             //                 <td style='vertical-align:middle; ' >${this.do_batch_kode}</td>
    //             //                 <td style='vertical-align:middle; ' >${this.tipe_delivery_order_nama}</td>
    //             //                 <td style='vertical-align:middle; ' >${this.nama_layanan}</td>
    //             //                 <td style='vertical-align:middle; ' >${this.tipe_pengiriman}</td>
    //             //                 <td style='vertical-align:middle; ' >${packed}</td>
    //             //                 <td style='vertical-align:middle; ' >${this.area_nama}</td>
    //             //                 <td style='vertical-align:middle; ' >-</td>
    //             //                 <td style='vertical-align:middle; ' ><button class="btn btn-info" onclick="viewDetail('${this.picking_list_id}','${this.picking_list_kode}')">Detail</button></td>
    //             //             </tr>
    //             //         `);

    //             //         // tableDO.row.add(tr[0]).draw()
    //             //     no++;
    //             //     // console.log(index);
    //             // });
    //             // } else {
    //             //     $("#tablePickOrder tbody").html('');
    //             // }

    //             $('#tablePickOrder').DataTable();
    //         }
    //     });
    // $('#viewDetail').modal('show');
}

function GetDoBatchById() {
    var DoBatchId = $("#no_batch_do").val();
    if (DoBatchId == '') {

    } else {
        $.ajax({
            type: 'GET',
            url: "<?= base_url('WMS/Distribusi/PermintaanBarang/GetDoBatchById') ?>",
            data: {
                DoBatchId: DoBatchId
            },
            success: function(response) {

                let data = response;
                $('#tipe_layanan option').remove()
                $('#tipe_pengiriman option').remove()
                // if (condition) {
                $('#tgl_fdjr').val(data.tgl_doBatch)
                $('#tgl_kirim').val(data.tgl_kirim)
                if (data.delivery_order_batch_is_need_packing == 0) {

                    $('#is_packing').val('No')
                } else {
                    $('#is_packing').val('Yes')
                }


                $('#tipe_layanan').append($("<option />").val(data
                        .delivery_order_batch_tipe_layanan_no)
                    .text(data.delivery_order_batch_tipe_layanan_nama));
                $('#tipe_pengiriman').append($("<option />").val(data.tipe_pengiriman_id).text(data
                    .nama_pengiriman));

            }
        });
    }
    // getDoByBatchId(DoBatchId);
}
</script>