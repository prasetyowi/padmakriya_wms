<script type="text/javascript">
    loadingBeforeReadyPage()
    $(document).ready(function() {
        $('.select2').select2();

        // filter_date_do
        $('.filter_date').daterangepicker({
            'applyClass': 'btn-sm btn-success',
            'cancelClass': 'btn-sm btn-default',
            locale: {
                "format": "DD/MM/YYYY",
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
            },
            // 'startDate': '<?= date("01-m-Y") ?>',
            // 'endDate': '<?= date("t-m-Y") ?>'
        });

        var do_id = '<?= $this->input->get('data') ?>'

        if (do_id != '') {
            console.log(do_id);
            GetDOByUrl(do_id);
        }
    })

    $("#mode_filter").on('change', function() {
        var mode = $("#mode_filter").val();

        if (mode == 1) {
            $("#mode_driver").show('slow');
            $("#mode_sales").hide('slow');
        } else {
            $("#mode_sales").show('slow');
            $("#mode_driver").hide('slow');
        }
    })

    $("#filter_date_so").on('change', function() {
        var date = $("#filter_date_so").val();

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/StatusDO/getSalesByDate') ?>",
            data: {
                date: date
            },
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {
                    $("#sales").html('');
                    $("#sales").append('<option value="">-- Pilih semua sales --</option>');
                    $.each(response, function(i, v) {
                        $("#sales").append(`
							<option value="${v.sales_id}">${v.karyawan_nama}</option>
						`);
                    });
                }
            }
        });
    });

    $("#filter_date_do").on('change', function() {
        var date = $("#filter_date_do").val();

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/StatusDO/getDriverByDate') ?>",
            data: {
                date: date
            },
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {
                    $("#driver").html('');
                    $("#driver").append('<option value="">-- Pilih semua driver --</option>');
                    $.each(response, function(i, v) {
                        $("#driver").append(`
							<option value="${v.karyawan_id}">${v.karyawan_nama}</option>
						`);
                    });
                }
            }
        });
    });

    $("#sales").on('change', function() {
        var sales = $("#sales").val();
        var date = $("#filter_date_so").val();

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/StatusDO/getNoSOBySales') ?>",
            data: {
                sales: sales,
                date: date
            },
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {
                    $("#noso").html('');
                    $("#noso").append('<option value="">-- Pilih semua no SO --</option>');
                    $.each(response, function(i, v) {
                        $("#noso").append(`
							<option value="${v.sales_order_id}">${v.sales_order_no_po}</option>
						`);
                    });
                }
            }
        });
    });

    $("#driver").on('change', function() {
        var driver = $("#driver").val();
        var date = $("#filter_date_do").val();

        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/StatusDO/getNoFDJRBySales') ?>",
            data: {
                driver: driver,
                date: date
            },
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {
                    $("#nofdjr").html('');
                    $("#nofdjr").append('<option value="">-- Pilih semua no FDJR --</option>');
                    $.each(response, function(i, v) {
                        $("#nofdjr").append(`
							<option value="${v.delivery_order_batch_id}">${v.delivery_order_batch_kode}</option>
						`);
                    });
                }
            }
        });
    });

    function GetDOByUrl(do_id) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/StatusDO/FilterGetDO') ?>",
            data: {
                // tgl: tgl,
                do_kode: do_id
            },
            dataType: "JSON",
            success: function(response) {
                $('#loadingview').hide();

                if ($.fn.DataTable.isDataTable('#tableviewdo')) {
                    $('#tableviewdo').DataTable().clear();
                    $('#tableviewdo').DataTable().destroy();
                }

                $('#tableviewdo > tbody').empty();

                if (response.length > 0) {

                    $.each(response, function(i, v) {
                        $('#tableviewdo > tbody').append(`
                    <tr>
                    <td class="text-center">${v.delivery_order_tgl_buat_do}</td>
                    <td class="text-center">${v.delivery_order_tgl_rencana_kirim}</td>
                    <td class="text-center">${v.delivery_order_tgl_aktual_kirim}</td>
                    <td class="text-center">${v.delivery_order_kode}</td>
                    <td class="text-center">${v.delivery_order_kirim_nama}</td>
                    <td class="text-center">${v.delivery_order_kirim_alamat}</td>
                    <td class="text-center">${v.delivery_order_status}</td>
                    <td class="text-center"><button type="button" class="btn btn-primary" title="Status DO" id="btndodetail" onclick="modaldodetail('${row.delivery_order_id}')"><i class="fas fa-eye"></i></button>
                        <a href="<?= base_url('WMS/StatusDO/detail/?id=') ?>${row.delivery_order_id}" class="btn btn-success" title="Detail DO" id="btndodetail2"><i class="fa-solid fa-paper-plane"></i></a></td>
                    </tr>
                    `)
                    });

                    $('#tableviewdo').DataTable({
                        lengthMenu: [
                            [50, 100, -1],
                            [50, 100, 'All']
                        ]
                    })
                } else {
                    $("#tableviewdo > tbody").html(`
                    <tr><td colspan="5" class="text-center text-danger">Data Kosong</td></tr>`);
                }
            }
        })
    }

    $("#btncari").on('click', function() {
        var mode = $('#mode_filter').val();
        var filter_date_so = $('#filter_date_so').val();
        var sales = $('#sales').val();
        var noso = $('#noso').val();
        var filter_date_do = $('#filter_date_do').val();
        var driver = $('#driver').val();
        var nofdjr = $('#nofdjr').val();

        $('#loadingview').show();

        $('#tableviewdo > tbody').empty();

        if ($.fn.dataTable.isDataTable('#tableviewdo')) {
            $('#tableviewdo').DataTable().clear();
            $('#tableviewdo').DataTable().destroy();
        }

        $('#tableviewdo').DataTable({
            'serverSide': true,
            'processing': true,
            'ajax': {
                'url': '<?= base_url('WMS/StatusDO/FilterGetDO') ?>',
                'data': {
                    dateSO: filter_date_so,
                    dateDO: filter_date_do,
                    noso: noso,
                    nofdjr: nofdjr,
                    mode: mode,
                    sales: sales,
                    driver: driver
                },
                'type': 'POST',
                'dataType': "JSON"
            },
            'columns': [{
                data: null,
                // untuk menghitung nomor urut berdasarkan halaman saat ini dan indeks baris dalam halaman tersebut
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                },
                orderable: false
            }, {
                data: 'delivery_order_tgl_buat_do'
            }, {
                data: 'delivery_order_tgl_rencana_kirim'
            }, {
                data: 'delivery_order_tgl_aktual_kirim'
            }, {
                data: 'delivery_order_kode'
            }, {
                data: 'sales_order_kode'
            }, {
                data: 'sales_order_no_po'
            }, {
                data: 'delivery_order_kirim_nama'
            }, {
                data: 'delivery_order_kirim_alamat'
            }, {
                data: 'delivery_order_status'
            }, {
                data: 'flag'
            }, {
                render: function(data, type, row) {
                    var button = '';
                    button +=
                        `<button type="button" class="btn btn-primary" title="Status DO" id="btndodetail" onclick="modaldodetail('${row.delivery_order_id}')"><i class="fas fa-eye"></i></button>
                        <a target="_blank" href="<?= base_url('WMS/StatusDO/detail/?id=') ?>${row.delivery_order_id}" class="btn btn-success" title="Detail DO" id="btndodetail2"><i class="fa-solid fa-paper-plane"></i></a>`;

                    return button;
                },
                orderable: false
            }]
        });
        $('#loadingview').hide();
    })

    function cetakStatusDO() {
        var mode = $('#mode_filter').val();
        var filter_date_so = $('#filter_date_so').val();
        var sales = $('#sales').val();
        var noso = $('#noso').val();
        var filter_date_do = $('#filter_date_do').val();
        var driver = $('#driver').val();
        var nofdjr = $('#nofdjr').val();

        var baseUrl = "<?= base_url('WMS/StatusDO/cetakStatusDO/') ?>";

        if (mode == 0) {
            var url = baseUrl + "?mode=" + mode + "&filter_date_so=" + filter_date_so + "&sales=" + sales + "&noso=" + noso;
        } else {
            var url = baseUrl + "?mode=" + mode + "&filter_date_do=" + filter_date_do + "&driver=" + driver + "&nofdjr=" +
                nofdjr;
        }

        window.open(url);
    }


    function modaldodetail(do_id) {
        $.ajax({
            type: "POST",
            url: "<?= base_url('WMS/StatusDO/GetDODetail') ?>",
            data: {
                do_id: do_id
            },
            dataType: "JSON",
            success: function(response) {
                if ($.fn.DataTable.isDataTable('#table-dodetail')) {
                    $('#table-dodetail').DataTable().clear();
                    $('#table-dodetail').DataTable().destroy();
                }

                $('#table-dodetail > tbody').empty();

                if (response.length > 0) {
                    $.each(response, function(i, v) {
                        $('#table-dodetail > tbody').append(`
                        <tr>
                        <td class="text-center">${v.tgl}</td>
                        <td class="text-center">${v.status}</td>
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

                console.log(response);

            }
        })

        $("#modal-dodetail").modal("show");
    }
</script>