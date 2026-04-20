<script>
    $(document).ready(function() {
        $('.select2').select2();

        if ($('#filter_tgl_kirim').length > 0) {
            $('#filter_tgl_kirim').daterangepicker({
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

    $('#btncari').click(function() {
        var perusahaan = $('#filter_perusahaan').val();
        var principle = $('#filter_principle').val();
        var tgl_kirim = $('#filter_tgl_kirim').val();
        var status = $('#filter_status').val();

        $('#loadingview').show();

        $.ajax({
            url: "<?= base_url('WMS/MonitoringDeliveryOrder/getDataFilter') ?>",
            type: "POST",
            data: {
                perusahaan: perusahaan,
                principle: principle,
                tgl_kirim: tgl_kirim,
                status: status,
            },
            dataType: "json",
            success: function(response) {
                $('#tableviewdo tbody').html(``)
                $('#tableviewdo tfoot').html(``)

                if (response != 0) {
                    var inProgressCount = 0;
                    var canceledCount = 0;
                    var exceptionCount = 0;
                    var deliveredCount = 0;
                    var notDeliveredCount = 0;
                    var partiallyDeliveredCount = 0;
                    var allTotalDeliveryOrder = 0;
                    var allPartiallyDeliveredCount = 0;

                    $(response).each(function(i, v) {

                        inProgressCount += parseFloat(v.in_progress_count);
                        canceledCount += parseFloat(v.canceled_count);
                        exceptionCount += parseFloat(v.exception_count);
                        deliveredCount += parseFloat(v.delivered_count);
                        notDeliveredCount += parseFloat(v.not_delivered_count);
                        partiallyDeliveredCount += parseFloat(v.partially_delivered_count);

                        var totalDeliveryOrder = 0;
                        var totalPresentase = 0;
                        totalDeliveryOrder = v.in_progress_count + v.canceled_count + v.exception_count + v.delivered_count + v.not_delivered_count + v.partially_delivered_count;
                        // totalPresentase = (v.delivered_count + v.not_delivered_count + v.partially_delivered_count) / totalDeliveryOrder * 100;
                        totalPresentase = v.delivered_count / totalDeliveryOrder * 100;


                        allTotalDeliveryOrder += parseFloat(totalDeliveryOrder);
                        allPartiallyDeliveredCount += parseFloat(totalPresentase);

                        if (Number.isInteger(totalPresentase)) {
                            totalPresentase = totalPresentase;
                        } else {
                            totalPresentase = parseFloat(totalPresentase).toFixed(2);
                        }

                        $('#tableviewdo tbody').append(`
                            <tr class="text-center" style="font-weight: bold">
                                <td>${v.client_wms_nama}</td>
                                <td>${v.principle_kode}</td>
                                <td onclick="showDO(this, 'do draft', '${v.client_wms_id}', '${v.principle_id}')">${v.in_progress_count}</td>
                                <td onclick="showDO(this, 'canceled', '${v.client_wms_id}', '${v.principle_id}')">${v.canceled_count}</td>
                                <td onclick="showDO(this, 'exception', '${v.client_wms_id}', '${v.principle_id}')">${v.exception_count}</td>
                                <td onclick="showDO(this, 'delivered', '${v.client_wms_id}', '${v.principle_id}')">${v.delivered_count}</td>
                                <td onclick="showDO(this, 'not delivered', '${v.client_wms_id}', '${v.principle_id}')">${v.not_delivered_count}</td>
                                <td onclick="showDO(this, 'partially delivered', '${v.client_wms_id}', '${v.principle_id}')">${v.partially_delivered_count}</td>
                                <td>${totalDeliveryOrder}</td>
                                <td>${totalPresentase}%</td>
                            </tr>
                        `)
                    })

                    $('#tableviewdo tfoot').append(`
                        <tr class="text-center" style="font-weight: bold">
                            <td colspan="2">GRAND TOTAL</td>
                            <td>${inProgressCount}</td>
                            <td>${canceledCount}</td>
                            <td>${exceptionCount}</td>
                            <td>${deliveredCount}</td>
                            <td>${notDeliveredCount}</td>
                            <td>${partiallyDeliveredCount}</td>
                            <td>${allTotalDeliveryOrder}</td>
                            <td>${(allPartiallyDeliveredCount/response.length).toFixed(2)}%</td>
                        </tr>
                    `)
                } else {
                    $('#tableviewdo tbody').html(`
                        <tr>
                        <td class="text-center" colspan="9">Data Kosong</td>
                        </tr>
                    `)

                    $('#tableviewdo tfoot').html(``)
                }

                $('#loadingview').hide();

            }
        })
    })

    function showDO(element, status, client_wms_id, principle_id) {
        $(element).css('background-color', '#A5DD9B');
        $('td').not(element).removeAttr('style');

        var tgl_kirim = $('#filter_tgl_kirim').val();

        if ($.fn.DataTable.isDataTable('#table-dodetail')) {
            $('#table-dodetail').DataTable().clear();
            $('#table-dodetail').DataTable().destroy();
        }

        table_dt = $('#table-dodetail').DataTable({
            // "scrollX": true,
            'paging': true,
            'searching': true,
            'ordering': true,
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
                url: "<?= base_url('WMS/MonitoringDeliveryOrder/getDetailDO') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    perusahaan: client_wms_id,
                    principle: principle_id,
                    tgl_kirim: tgl_kirim,
                    status: status
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
                data: 'delivery_order_tgl_aktual_kirim',
                className: 'text-center'
            }, {
                data: 'principle_kode',
                className: 'text-center'
            }, {
                data: 'tipe_delivery_order_nama',
                className: 'text-center'
            }, {
                data: 'sales_order_no_po',
                className: 'text-center'
            }, {
                data: 'delivery_order_kode',
                className: 'text-center'
            }, {
                data: 'delivery_order_batch_kode',
                className: 'text-center',
                // visible: status !== 'do draft'
            }, {
                data: 'karyawan_nama',
                className: 'text-center',
                // visible: status !== 'do draft'
            }, {
                data: 'delivery_order_kirim_nama',
                className: 'text-center'
            }, {
                data: 'delivery_order_kirim_alamat',
                className: 'text-center'
            }],
            initComplete: function() {
                parent_dt = $('#table-dodetail').closest('.dataTables_wrapper')
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

        $('#modal-dodetail').modal('show');
    }
</script>