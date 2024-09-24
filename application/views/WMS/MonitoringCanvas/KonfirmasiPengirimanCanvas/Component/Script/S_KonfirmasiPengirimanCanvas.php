<script>
    let deliveryOrderCanvasIdArray = [];
    let skuSummaryData = [];
    var arr_list_summary_sku = [];
    var arr_list_do = [];

    loadingBeforeReadyPage();

    $(document).ready(function() {
        select2();
        if ($('#filter_tgl_request').length > 0) {
            $('#filter_tgl_request').daterangepicker({
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

        $(document).on("input", ".numeric", function(event) {
            this.value = this.value.replace(/[^\d.]+/g, '');
        });

        <?php if (isset($data)) { ?>
            arr_list_do.push("<?= $data->listPermintaan->delivery_order_id ?>")
        <?php } ?>

        requestGetDataListDO()

    })

    $('#check-all-sku').click(function(event) {
        if (this.checked) {
            // Iterate each checkbox
            $('[name="CheckboxSKU"]:checkbox').each(function() {
                this.checked = true;

                var sku_id = this.getAttribute('data-sku_id');
                var sku_kode = this.getAttribute('data-sku_kode');
                var sku_nama_produk = this.getAttribute('data-sku_nama_produk');
                var sku_satuan = this.getAttribute('data-sku_satuan');
                var sku_kemasan = this.getAttribute('data-sku_kemasan');
                var principle_id = this.getAttribute('data-principle_id');
                var principle_brand_id = this.getAttribute('data-principle_brand_id');

                arr_list_summary_sku.push({
                    'sku_id': sku_id,
                    'sku_kode': sku_kode,
                    'sku_nama_produk': sku_nama_produk,
                    'sku_satuan': sku_satuan,
                    'sku_kemasan': sku_kemasan,
                    'principle_id': principle_id,
                    'principle_brand_id': principle_brand_id
                });
            });
        } else {
            $('[name="CheckboxSKU"]:checkbox').each(function() {
                this.checked = false;
                arr_list_summary_sku = [];
            });
        }
    });

    /** --------------------------------------- Untuk Global ------------------------------------------- */

    const select2 = () => {
        $(".select2").select2({
            width: "100%"
        });
    }

    const initDatatable = (table) => {
        $(table).DataTable();
    }

    const handlerBackToHome = () => {
        location.href =
            "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/KonfirmasiPengirimanCanvasMenu') ?>";
    }

    function getStringOfArrayString(data) {
        let string = "";

        data.forEach(datum => string += datum + ", ");

        return string.slice(0, -2);
    }

    /** Halaman Depan */

    function getStringOfArrayString(data) {
        let string = "";

        data.forEach(datum => string += datum + ", ");

        return string.slice(0, -2);
    }

    const handlerGetKodeFDJR = (event) => {
        const stateValue = event.currentTarget.value;

        if (stateValue !== '') {
            fetch('<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/getKodeAutoComplete?params=') ?>' +
                    stateValue)
                .then(response => response.json())
                .then((results) => {
                    if (!results) {
                        $('#table-fixed').css('display', 'none');
                    } else {
                        $('#konten-table').empty();
                        results.map((value, index) => {
                            $('#konten-table').append(`
									<tr onclick="handlerFdjrIdByKode('${value.id}', '${value.kode}')" style="cursor:pointer">
												<td width="10%">${index + 1}.</td>
												<td width="90%">${value.kode}</td>
									</tr>`);
                        })
                        $('#table-fixed').css('display', 'block');
                    }
                });
        } else {
            $('#table-fixed').css('display', 'none');
        }
    }

    const handlerFdjrIdByKode = (fdjrId, fdjrKode) => {
        $("#kodeFdjr").attr('data-fdjrId', fdjrId);
        $("#kodeFdjr").val(fdjrKode);
        $('#table-fixed').css('display', 'none');
    }

    const handlerFilterData = () => {
        $('#listDataFilter > tbody').empty();
        postData("<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/getDataByFilter') ?>", {
            tanggal: $("#filter_tgl_request").val(),
            fdjrId: $("#kodeFdjr").attr('data-fdjrId'),
            sales: $("#sales option:selected").val(),
            status: $("#status option:selected").val()
        }, 'POST', function(response) {
            if (response.length > 0) {
                var arr = [];
                var cek_do_batch_kode = '';
                response.map((value, index) => {
                    if (arr[value.kode_fdjr]) {
                        arr[value.kode_fdjr] += 1;
                    } else {
                        arr[value.kode_fdjr] = 1;
                    }
                    var strmenu = '';
                    if (arr[value.kode_fdjr]) {
                        if (cek_do_batch_kode == value.kode_fdjr) {
                            strmenu = strmenu + '<tr>';
                            strmenu = strmenu + '<td><a href="<?php echo base_url(); ?>WMS/Distribusi/PermintaanBarang/PickingDetail/' + value.picking_list_id + '" class="btn btn-link" target="_blank">' + value.kode_pb + '</a></td>';
                            strmenu = strmenu + '<td><a href="<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=' + value.kode_ppb + '" class="btn btn-link" target="_blank">' + value.kode_ppb + '</a></td>';
                            strmenu = strmenu + '<td><a href="<?php echo base_url(); ?>WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/' + value.serah_terima_kirim_id + '" class="btn btn-link" target="_blank">' + value.kode_serah_terima + '</a></td>';
                            strmenu = strmenu + '</tr>';
                        } else {
                            cek_do_batch_kode = value.kode_fdjr;
                            strmenu = strmenu + '<tr>';
                            strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">' + (index + 1) + '</td>';
                            strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">' + value.tanggal_fdjr + '</td>';
                            strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">' + value.sales + '</td>';
                            strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">' + value.tipe_fdjr + '</td>';
                            strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">' + value.status_fdjr + '</td>';
                            strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '"><a href="<?php echo base_url(); ?>WMS/Distribusi/DeliveryOrderBatch/detail/?id=' + value.id_fdjr + '" class="btn btn-link" target="_blank">' + value.kode_fdjr + '</a></td>';
                            value.kode_pb !== null ? strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PermintaanBarang/PickingDetail/' + value.picking_list_id + '" class="btn btn-link" target="_blank">' + value.kode_pb + '</a></td>' : strmenu = strmenu + '<td></td>';
                            value.kode_ppb !== null ? strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PengeluaranBarang/DetailPengeluaranBarangMenu/?picking_order_kode=' + value.kode_ppb + '" class="btn btn-link" target="_blank">' + value.kode_ppb + '</a></td>' : strmenu = strmenu + '<td></td>';
                            value.kode_serah_terima !== null ? strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;"><a href="<?php echo base_url(); ?>WMS/Distribusi/PengirimanBarang/PengirimanBarangDetail/' + value.serah_terima_kirim_id + '" class="btn btn-link" target="_blank">' + value.kode_serah_terima + '</a></td>' : strmenu = strmenu + '<td></td>';
                            strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">' + value.status + '</td>';
                            // if (value.status_fdjr == "in transit" || value.status_fdjr == "In Process Closing" || value.status_fdjr == "In Process Closing Delivery") {
                            // 	strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '"><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' + value.id_fdjr + '" class="btn btn-link" target="_blank">Closing Pengiriman</a></td>';
                            // } else if (value.status_fdjr == "In Process Receiving Outlet") {
                            // 	strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '"><a href="<?php echo base_url(); ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=' + value.id_fdjr + '" class="btn btn-link" target="_blank">View BTB</a><a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' + value.id_fdjr + '" class="btn btn-link" target="_blank">View Closing Delivery</a></td>';
                            // } else if (value.status_fdjr == "Closing Delivery Confirm" ||
                            // 	value.status_fdjr == "Confirmed" || value.status_fdjr == "completed") {
                            // 	strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">';
                            // 	if (jml_btb > 0) {
                            // 		strmenu = strmenu + ' <a href = "<?php echo base_url(); ?>WMS/PenerimaanRetur/ProsesBTBMenu/?delivery_order_batch_id=' + value.id_fdjr + '" class="btn btn-link" target="_blank">View BTB</a>';
                            // 	}
                            // 	strmenu = strmenu + '<a href="<?php echo base_url(); ?>WMS/SuratTugasPengiriman/ClosingPengirimanMenu/?delivery_order_batch_id=' + value.id_fdjr + '" class="btn btn-link" target="_blank">View Closing Delivery</a>';
                            // 	strmenu = strmenu + '</td>';
                            // } else {
                            // 	strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '"></td>';
                            // }
                            if (value.status_fdjr == "In Process Closing" || value.status_fdjr == "In Process Closing Delivery") {
                                strmenu = strmenu + '<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">';
                                strmenu = strmenu + `	<button class='btn btn-warning btn-sm' style="margin-right: 5px" title='Closing' onclick="handlerClosingData('${value.id_fdjr}')">${value.jml_btb == 0 ? 'Closing' : 'View Closing'}</button>`;
                                strmenu = strmenu + '</td>';
                            } else if (value.status_fdjr == "In Process Receiving Outlet") {
                                strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">';
                                strmenu = strmenu + `		<button class='btn btn-primary btn-sm' title='View BTB' onclick="handlerViewBTB('${value.id_fdjr}', '${value.delivery_order_id}')">View BTB</button>`;
                                strmenu = strmenu + `		<button class='btn btn-warning btn-sm' style="margin-right: 5px" title='Closing' onclick="handlerClosingData('${value.id_fdjr}')">${value.jml_btb == 0 ? 'Closing' : 'View Closing'}</button>`;
                                strmenu = strmenu + ' </td>';
                            } else if (value.status_fdjr == "Confirmed" || value.status_fdjr == "completed" || value.status_fdjr == "in transit") {
                                strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">';
                                if (value.jml_btb > 0) {
                                    strmenu = strmenu + `	<button class='btn btn-primary btn-sm' title='View BTB' onclick="handlerViewBTB('${value.id_fdjr}', '${value.delivery_order_id}')">View BTB</button>`;
                                }
                                strmenu = strmenu + `<button class='btn btn-warning btn-sm' style="margin-right: 5px" title='Closing' onclick="handlerClosingData('${value.id_fdjr}')">${value.jml_btb == 0 ? 'Closing' : 'View Closing'}</button>`;
                                strmenu = strmenu + '</td>';
                            } else if (value.status_fdjr == "Closing Delivery Confirm") {
                                strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '">';
                                if (value.jml_btb > 0) {
                                    strmenu = strmenu + `	<button class='btn btn-primary btn-sm' title='View BTB' onclick="handlerViewBTB('${value.id_fdjr}', '${value.delivery_order_id}')">View BTB</button>`;
                                }
                                strmenu = strmenu + `<button class='btn btn-warning btn-sm' style="margin-right: 5px" title='Closing' onclick="handlerClosingData('${value.id_fdjr}')">View Closing</button>`;
                                strmenu = strmenu + '</td>';
                            } else {
                                strmenu = strmenu + '	<td style="vertical-align: middle; text-align: center;" rowspan="' + arr[value.kode_fdjr] + '"></td>';
                            }
                            strmenu = strmenu + '</tr>';
                        }
                    }
                    $("#listDataFilter > tbody").append(strmenu);
                    // $('#listDataFilter > tbody').append(`
                    //       <tr class="text-center">
                    //           <td>${index + 1}</td>
                    //           <td>${value.tanggal_fdjr}</td>
                    //           <td>${value.sales === null ? '-' : value.sales}</td>
                    //           <td>${value.tipe_fdjr}</td>
                    //           <td>${value.status_fdjr}</td>
                    //           <td>${value.kode_fdjr === null ? '-' : value.kode_fdjr}</td>
                    //           <td>${value.kode_pb === null ? '-' : value.kode_pb}</td>
                    //           <td>${value.kode_ppb === null ? '-' : value.kode_ppb}</td>
                    //           <td>${value.kode_serah_terima === null ? '-' : value.kode_serah_terima}</td>
                    //           <td>${value.status}</td>
                    //           <td>
                    // 							<div style="display:flex;align-items:center;justify-content:center">
                    // 								<button class='btn btn-warning btn-sm' style="margin-right: 5px" title='Closing' onclick="handlerClosingData('${value.id_fdjr}')">Closing</button>
                    // 								<button class='btn btn-primary btn-sm' title='View BTB' onclick="handlerViewBTB('${value.id_fdjr}')">View BTB</button>
                    // 							</div>
                    // 					</td>
                    //       </tr>
                    //   `)
                })
                initDatatable('#listDataFilter')
            } else {
                $('#listDataFilter > tbody').append(`<tr><td colspan=10"><h4 class="text-center text-danger">Data not found</h4></td></tr>`)
            }
        }, '#btnsearchdata')
    }

    const handlerClosingData = (fdjrId) => {
        window.open("<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/formClosing/') ?>" + fdjrId +
            `?mode=closing`, '_blank');
    }

    const handlerViewBTB = (deliveryOrderBatchId, deliveryOrderId) => {
        window.open("<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/formViewBTB/') ?>" +
            deliveryOrderBatchId + `/${deliveryOrderId}`, '_blank');
    }

    /** End Halaman Depan */

    /******************************************************* Form Closing *********************************************************/

    /** Handler Closing */

    const handlerDownloadCanvasSO = (canvasId, deliveryOrderBacthId) => {
        postData("<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/downloadCanvasSO') ?>", {
            canvasId,
            deliveryOrderBacthId
        }, 'POST', function(response) {
            if (response.status === 200) {
                message_topright('success', response.message);
                setTimeout(() => requestGetDataListDO(), 1000)
            }

            if (response.status === 401) return message('Error!', response.message, 'error')
        })
    }

    const handlerSave = () => {

        postData("<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/saveConfirmCanvas') ?>", {
            deliveryOrderBacthId: $("#delivery_order_batch_id").val(),
            type: 'save'
        }, 'POST', function(response) {
            if (response.status === 201) {
                message_topright('success', "Data Berhasil Disimpan");
                setTimeout(() => location.reload(), 1000)
            }

            if (response.status === 401) return message('Error!', "Data Gagal Disimpan", 'error')
        })
    }

    const handlerConfirmation = () => {
        postData("<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/saveConfirmCanvas') ?>", {
            deliveryOrderBacthId: $("#delivery_order_batch_id").val(),
            type: 'confirm'
        }, 'POST', function(response) {
            if (response.status === 201) {
                message_topright('success', "Data Berhasil Dikonfirmasi");
                setTimeout(() => location.reload(), 1000)
            }

            if (response.status === 401) return message('Error!', "Data Gagal Dikonfirmasi", 'error')
        })
    }

    const requestGetDataListDO = () => {
        $('#table-list-do > tbody').empty();

        if ($.fn.DataTable.isDataTable('#table-list-do')) {
            $('#table-list-do').DataTable().clear();
            $('#table-list-do').DataTable().destroy();
        }

        $.ajax({
            async: false,
            url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/requestGetDataListDO'); ?>",
            type: "POST",
            data: {
                deliveryOrderBatch: '<?= $this->uri->segment(5) ?>'
            },
            dataType: "JSON",
            success: function(response) {
                if (response.length > 0) {

                    response.map((value, index) => {

                        deliveryOrderCanvasIdArray.push(value.delivery_order_id)

                        $("#table-list-do > tbody").append(`
							<tr>
								<td width="5%" class="text-center">${index + 1}</td>
								<td width="10%" class="text-center">${value.tanggal_do}</td>
								<td width="10%" class="text-center">${value.kode_so}</td>
								<td width="10%" class="text-center">${value.kode_so_ext}</td>
								<td width="10%" class="text-center">${value.kode_do}</td>
								<td width="10%" class="text-center">${value.customer}</td>
								<td width="20%" class="text-center">${value.alamat}</td>
								<td width="15%" class="text-center">${value.tipe_pembayaran}</td>
								<td width="10%" class="text-center">${value.status}</td>
								<td width="10%" class="text-center">
									<button class="btn btn-info btn-sm" onclick="handlerViewDataDOById('${value.delivery_order_id}','${value.kode_do}','${value.canvas_id}')"><i class="fas fa-eye"></i> Detail</button>
								</td>
							</tr>
						`);
                    })

                    initDatatable('#table-list-do');
                    setTimeout(() => requestGetSummaryListDO(), 1000);
                } else {
                    $('#table-list-do > tbody').append(
                        `<tr><td colspan=10"><h4 class="text-center text-danger">Data not found</h4></td></tr>`
                    )
                    // initDataSKUSummaryNotHaveDO();
                    requestGetSummaryListDO();

                }
            }
        });
    }

    const handlerViewDataDOById = (deliveryOrderId, delivertOrderKode, canvasId) => {

        $("#modal-viewDetailDO").modal('show');
        $("#modal-viewDetailDO .modal-title").html(`Detail DO ${delivertOrderKode}`);

        $('#table-list-detail-so > tbody').empty();

        if ($.fn.DataTable.isDataTable('#table-list-detail-so')) {
            $('#table-list-detail-so').DataTable().clear();
            $('#table-list-detail-so').DataTable().destroy();
        }

        postData("<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/requestGetDataDOById') ?>", {
            deliveryOrderId,
            canvasId
        }, 'POST', function(response) {

            $("#kodeDoDetail").val(response.header.kode_do);
            $("#tipeDoDetail").val(response.header.tipe_delivery_order_alias);
            $("#statusDoDetail").val(response.header.status);
            $("#customerDetail").val(response.header.customer);
            $("#alamatDetail").val(response.header.alamat);
            $("#areaDetail").val(response.header.area.length > 0 ? getStringOfArrayString(response.header
                .area) : '');

            if (response.detail.length > 0) {

                response.detail.map((value, index) => {
                    $("#table-list-detail-so > tbody").append(`
							<tr>
									<td width="5%" class="text-center">${index + 1}</td>
									<td width="10%" class="text-center">${value.sku_kode}</td>
									<td width="10%" class="text-center">${value.sku_nama_produk}</td>
									<td width="10%" class="text-center">${value.sku_kemasan}</td>
									<td width="10%" class="text-center">${value.sku_satuan}</td>
									<td width="10%" class="text-center">${value.sku_qty}</td>
									<td width="20%" class="text-center">${value.sku_keterangan}</td>
							</tr>
					`);
                })

                initDatatable('#table-list-detail-so')
            } else {
                $('#table-list-detail-so > tbody').append(
                    `<tr><td colspan=7"><h4 class="text-center text-danger">Data not found</h4></td></tr>`)
            }
        })
    }

    const requestGetSummaryListDO = () => {

        $('#table-summary > tbody').empty();

        if ($.fn.DataTable.isDataTable('#table-summary')) {
            $('#table-summary').DataTable().clear();
            $('#table-summary').DataTable().destroy();
        }

        postData("<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/requestGetSummaryListDO') ?>", {
            // canvasId: $("#canvas_id").val(),
            // deliveryOrderCanvasIdArray
            deliveryOrderBatch: '<?= $this->uri->segment(5) ?>'
        }, 'POST', function(response) {

            if (response.length > 0) {

                skuSummaryData = [];

                response.map((value, index) => {

                    skuSummaryData.push(value.sku_kode);

                    $("#table-summary > tbody").append(`
						<tr>
							<td width="5%" class="text-center">${index + 1}</td>
							<td width="10%" class="text-center">${value.sku_kode}</td>
							<td width="10%" class="text-center">${value.sku_nama}</td>
							<td width="10%" class="text-center">${value.sku_satuan}</td>
							<td width="10%" class="text-center">${value.qtyorder}</td>
							<td width="20%" class="text-center">${value.qtyterkirim}</td>
							<td width="20%" class="text-center">${value.qtygagal}</td>
						</tr>
					`);
                })

                $('#table-summary').DataTable()
            } else {
                $('#table-summary > tbody').append(`<tr><td colspan="7"><h4 class="text-center text-danger">Data not found</h4></td></tr>`)
            }
        })

    }
    /** End Handler Closing */

    /** Handler Save */

    /** Handler End Save */

    /** Handler Confirmation */

    /** Handler End Confirmation */

    /** Handler View */

    /** End Handler View */

    /******************************************************* Form End Closing *********************************************************/

    /******************************************************* Form Create BTB *********************************************************/

    const handlerCreateBTB = () => {
        const deliveryOrderBatchId = $('#delivery_order_batch_id').val();
        const deliveryOrderId = $('#delivery_order_id').val();
        if (skuSummaryData.length === 0) return message('Error', 'Data Summary kosong!', 'error')

        window.open("<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/prosesCreateBTB?data=') ?>" +
            encodeURI(skuSummaryData) + `&batchId=${deliveryOrderBatchId}&doId=${deliveryOrderId}&mode=create`,
            '_blank');
    }

    function ViewModalSKU() {
        $("#modal-sku").modal('show');
        initDataSKU();
    }

    function initDataSKU() {
        var sku_nama_produk = $("#filter-sku-nama-produk").val();
        var sku_kemasan = $("#filter-sku-kemasan").val();
        var sku_satuan = $("#filter-sku-satuan").val();
        var principle = $("#filter-principle").val();
        var brand = $("#filter-brand").val();

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/search_filter_chosen_sku') ?>",
            data: {
                brand: brand,
                principle: principle,
                sku_nama_produk: sku_nama_produk,
                sku_kemasan: sku_kemasan,
                sku_satuan: sku_satuan,
                list_sku: arr_list_summary_sku
            },
            dataType: "JSON",
            async: false,
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false
                });
            },
            success: function(response) {
                $("#loadingsku").hide();
                if (response.length > 0) {
                    if ($.fn.DataTable.isDataTable('#table-sku')) {
                        $('#table-sku').DataTable().destroy();
                    }
                    $("#table-sku > tbody").empty();
                    $.each(response, function(i, v) {
                        $("#table-sku > tbody").append(`
							<tr>
								<td width="5%" class="text-center">
									<input type="checkbox" name="CheckboxSKU" id="check-sku-${i}" data-sku_id="${v.sku_id}" data-sku_kode="${v.sku_kode}" data-sku_nama_produk="${v.sku_nama_produk}" data-sku_satuan="${v.sku_satuan}" data-sku_kemasan="${v.sku_kemasan}" value="${v.sku_id}" data-principle_id="${v.principle_id}" data-principle_brand_id="${v.principle_brand_id}" onclick="PushArraySKUList('${i}','${v.sku_id}')">
								</td>
								<td width="10%" class="text-center">${v.principle}</td>
								<td width="10%" class="text-center">${v.brand}</td>
								<td width="15%" class="text-center">${v.sku_kode}</td>
								<td width="25%" class="text-center">${v.sku_nama_produk}</td>
								<td width="8%" class="text-center">${v.sku_kemasan}</td>
								<td width="8%" class="text-center">${v.sku_satuan}</td>
							</tr>
						`);
                    });

                    $('#table-sku').DataTable({
                        lengthMenu: [
                            [50, 100, 100, -1],
                            [50, 100, 100, 'All']
                        ],
                    });

                } else {
                    $("#table-sku > tbody").html(`<tr><td colspan="7" class="text-center text-danger">Data Kosong</td></tr>`);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                message("Error", "Error 500 Internal Server Connection Failure", "error");
            },
            complete: function() {
                Swal.close();
            }
        });
    }

    function PushArraySKUList(index, sku_id) {
        const checkbox = $("#check-sku-" + index);
        const isChecked = checkbox.prop('checked');
        const findIndexData = arr_list_summary_sku.findIndex((value) => value.sku_id == sku_id);

        if (isChecked) {
            var sku_id = checkbox.attr('data-sku_id');
            var sku_kode = checkbox.attr('data-sku_kode');
            var sku_nama_produk = checkbox.attr('data-sku_nama_produk');
            var sku_satuan = checkbox.attr('data-sku_satuan');
            var sku_kemasan = checkbox.attr('data-sku_kemasan');
            var principle_id = checkbox.attr('data-principle_id');
            var principle_brand_id = checkbox.attr('data-principle_brand_id');

            arr_list_summary_sku.push({
                'sku_id': sku_id,
                'sku_kode': sku_kode,
                'sku_nama_produk': sku_nama_produk,
                'sku_satuan': sku_satuan,
                'sku_kemasan': sku_kemasan,
                'principle_id': principle_id,
                'principle_brand_id': principle_brand_id
            });

        } else {
            arr_list_summary_sku.splice(findIndexData, 1);
        }
    }

    function DeleteArrFilterSKU(sku_konversi_group) {

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Get_data_sku_by_group') ?>",
            data: {
                sku_konversi_group: sku_konversi_group
            },
            dataType: "JSON",
            async: false,
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false
                });
            },
            success: function(response) {

                $.each(response, function(i, v) {
                    const findIndexData = arr_list_summary_sku.findIndex((value) => value.sku_id == v.sku_id);

                    if (findIndexData > -1) {
                        arr_list_summary_sku.splice(findIndexData, 1);
                    }
                });

                // initDataSKUSummaryNotHaveDO();
                requestGetSummaryListDO();

            },
            error: function(xhr, ajaxOptions, thrownError) {
                message("Error", "Error 500 Internal Server Connection Failure", "error");
            },
            complete: function() {
                Swal.close();
            }
        });
    }

    function initDataSKUSummaryNotHaveDO() {

        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/MonitoringCanvas/KonfirmasiPengirimanCanvas/Get_data_sku_summary_not_have_do') ?>",
            data: {
                list_do: arr_list_do
            },
            dataType: "JSON",
            async: false,
            beforeSend: function() {
                Swal.fire({
                    title: 'Loading ...',
                    html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                    timerProgressBar: false,
                    showConfirmButton: false
                });
            },
            success: function(response) {

                $('#table-summary > tbody').empty();

                if ($.fn.DataTable.isDataTable('#table-summary')) {
                    $('#table-summary').DataTable().clear();
                    $('#table-summary').DataTable().destroy();
                }

                if (response.length > 0) {

                    response.map((value, index) => {
                        skuSummaryData.push(value.sku_konversi_group);

                        $("#table-summary > tbody").append(`
                            <tr>
                                <td width="5%" class="text-center">${index + 1}</td>
                                <td width="10%" class="text-center">${value.sku_konversi_group}</td>
                                <td width="10%" class="text-center">${value.sku_nama_produk}</td>
                                <td width="10%" class="text-center">${value.composite_satuan}</td>
                                <td width="10%" class="text-center">${value.qty_canvas}</td>
                                <td width="20%" class="text-center">${value.qty_terjual}</td>
                            </tr>
                        `);
                    })

                    $('#table-summary').DataTable()
                } else {
                    $('#table-summary > tbody').append(`<tr><td colspan="6"><h4 class="text-center text-danger">Data not found</h4></td></tr>`)
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                message("Error", "Error 500 Internal Server Connection Failure", "error");
            },
            complete: function() {
                Swal.close();
            }
        });

    }

    /******************************************************* Form End Create BTB *********************************************************/
</script>