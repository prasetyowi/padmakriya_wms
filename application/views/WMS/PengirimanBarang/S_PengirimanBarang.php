<script type="text/javascript">
loadingBeforeReadyPage()
$(document).ready(function() {
    // alert('aa')
    $('.select2').select2({
        width: '100%'
    });
    // getDataPickingList();
    // $(".date").datepicker('update', '<?= date('Y-m-d ') ?>');
    if ($('#filter_tglkirim_date').length > 0) {
        $('#filter_tglkirim_date').daterangepicker({
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

function getDataPengirimanSearch() {
    var picking_order_id = $("#picking_order_id").val();
    var tgl = $("#filter_tglkirim_date").val();

    Swal.showLoading()
    $.ajax({
        type: 'GET',
        url: "<?= base_url('WMS/Distribusi/PengirimanBarang/getDataPengirimanSearch') ?>",
        data: {
            picking_order_id: picking_order_id,
            tgl: tgl
        },
        success: function(response) {

            let data = response;
            let no = 1;
            if ($.fn.DataTable.isDataTable('#tablePengiriman')) {
                $('#tablePengiriman').DataTable().destroy();
            }
            $("#tablePengiriman tbody").empty();
            $("#tablePengiriman tbody").html('');
            // $("#tablePickOrder tbody").html('');
            if (data.length > 0) {

                $.each(data, function() {
                    var edit = "";
                    if (this.serah_terima_kirim_status != 'Closed') {
                        var edit =
                            '<a class="btn btn-warning" target="_blank" href="<?php echo site_url('WMS/Distribusi/PengirimanBarang/PengirimanBarangEdit/') ?>' +
                            this.serah_terima_kirim_id + '">Edit</a>'
                    }
                    var cetak =
                        '<a class="btn btn-success" target="_blank" href="<?php echo site_url('WMS/Distribusi/PengirimanBarang/print_serah_terima?id=') ?>' +
                        this.serah_terima_kirim_id + '">Cetak</a>'

                    $('#tablePengiriman tbody').append(`
                                <tr>
                                    <td style='vertical-align:middle; ' >${no}</td>
                                    <td style='vertical-align:middle; ' >${this.tgl_create}</td>
                                    <td style='vertical-align:middle; ' >${this.tgl_kirim}</td>
                                    <td style='vertical-align:middle; ' >${this.serah_terima_kirim_kode}</td>
                                    <td style='vertical-align:middle; ' >${this.picking_order_kode}</td>
                                    <td style='vertical-align:middle; ' >${this.delivery_order_batch_kode}</td>
                                    <td style='vertical-align:middle; ' >${this.karyawan_nama}</td>
                                    <td style='vertical-align:middle; ' >${this.serah_terima_kirim_status}</td>
                                    <td style='vertical-align:middle; ' >${this.serah_terima_kirim_keterangan}</td>
                                    <td style='vertical-align:middle; ' >
                                        <a class="btn btn-info" target="_blank" href="<?php echo site_url('WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/') ?>${this.serah_terima_kirim_id}">Detail</a>
                                        ${edit}
                                        ${cetak}
                                    </td>
                                </tr>
                            `);

                    no++;
                });
            } else {
                $("#tablePengiriman tbody").html('');
            }

            $('#tablePengiriman').DataTable();
            swal.close();

        }
    });
    // swal.close();

}
</script>