<script type="text/javascript">
    $('#btn_laporan_pengiriman_cari').click(function() {
        var tgl = $('#filter_pengiriman_tgl').val();

        $('#loadingview').show();

        $.ajax({
            type: "POST",
            url: "<?= base_url("WMS/Laporan/LaporanPengiriman/GetDataFilter") ?>",
            data: {
                tgl: tgl
            },
            dataType: "JSON",
            success: function(response) {
                $("#table_laporan_pengiriman > tbody").empty();

                if ($.fn.DataTable.isDataTable('#table_laporan_pengiriman')) {
                    $('#table_laporan_pengiriman').DataTable().clear();
                    $('#table_laporan_pengiriman').DataTable().destroy();
                }

                if (response != 0) {
                    $.each(response, function(i, v) {
                        $('#table_laporan_pengiriman > tbody').append(`
                            <tr>
                                <td class="text-center">${v.tgl}</td>
                                <td class="text-center">${v.status}</td>
                                <td class="text-center"><a href="<?= base_url('WMS/Laporan/LaporanPengiriman/DownloadExcel?tgl=') ?>${v.tgl}&status=${v.status}" title="Download Konfirm Pengiriman" class="btn btn-success"><i class="fa-solid fa-download"></i></a></td>
                            </tr>
                        `);
                    })

                    $('#table_laporan_pengiriman').DataTable({
                        lengthMenu: [
                            [-1],
                            ['All']
                        ]
                    });
                } else {
                    $("#table_laporan_pengiriman > tbody").append(`
                        <tr>
                            <td colspan="3" class="text-danger text-center">Data Kosong</td>
                        </tr>
                    `);
                }

                $('#loadingview').hide();
            }
        })
    });
</script>