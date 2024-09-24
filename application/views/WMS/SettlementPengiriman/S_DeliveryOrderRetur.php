<script type="text/javascript">
    var DeliveryOrderReturCode = '';
	loadingBeforeReadyPage()
    $('#select-sku').click(function(event) {
        if (this.checked) {
            // Iterate each checkbox
            $('[name="CheckboxSKU"]:checkbox').each(function() {
                this.checked = true;
            });
        } else {
            $('[name="CheckboxSKU"]:checkbox').each(function() {
                this.checked = false;
            });
        }
    });

    $(document).ready(
        function() {
            GetDeliveryOrderReturMenu();
            initDataTableSKU();
            DeleteDeliveryOrderReturDetailTemp();
        }
    );

    function DeleteDeliveryOrderReturDetailTemp() {
        var delivery_order_batch_id = $("#delivery_order_batch_id").val();
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/DeleteDeliveryOrderReturDetailTemp') ?>",
            data: "delivery_order_batch_id=" + delivery_order_batch_id,
            success: function(response) {
                console.log("sku kosong");
            }
        });
    }

    function GetDeliveryOrderReturMenu() {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/GetDeliveryOrderReturMenu') ?>",
            //data: "Location="+ Location,
            success: function(response) {
                if (response) {
                    ChDeliveryOrderReturMenu(response);
                }
            }
        });
    }

    //var DTABLE;

    function ChDeliveryOrderReturMenu(JSONDeliveryOrderRetur) {

        var DeliveryOrderRetur = JSON.parse(JSONDeliveryOrderRetur);

        var StatusC = DeliveryOrderRetur.AuthorityMenu[0].status_c;
        var StatusU = DeliveryOrderRetur.AuthorityMenu[0].status_u;
        var StatusD = DeliveryOrderRetur.AuthorityMenu[0].status_d;

        if (StatusC == 0) {
            $("#btnsavedoretur").prop("disabled", true);
        }

        $("#client_wms_id").html('');

        if (DeliveryOrderRetur.Perusahaan != 0) {
            $("#client_wms_id").append('<option value="">** Pilih Perusahaan **</option>');
            for (i = 0; i < DeliveryOrderRetur.Perusahaan.length; i++) {
                client_wms_id = DeliveryOrderRetur.Perusahaan[i].client_wms_id;
                client_wms_nama = DeliveryOrderRetur.Perusahaan[i].client_wms_nama;

                $("#client_wms_id").append('<option value="' + client_wms_id + '">' + client_wms_nama + '</option>');
            }
        }
    }

    function getDataPerusahaan(client_wms_id) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/GetDataPerusahaan') ?>",
            dataType: "json",
            data: {
                client_wms_id: client_wms_id
            },
            success: function(response) {
                var client_wms_alamat = response.Perusahaan[0].client_wms_alamat;

                $("#client_wms_alamat").val(client_wms_alamat);
            }
        });
    }

    function initDataTableCustomer() {
        if ($("#client_wms_id").val() != "") {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('WMS/SuratTugasPengiriman/GetCustomer') ?>",
                data: {
                    client_pt_name: $("#filter-client-name").val(),
                    client_pt_alamat: $("#filter-client-address").val(),
                    client_pt_telepon: $("#filter-client-phone").val(),
                    area_id: $("#filter-area").val(),
                    client_wms_id: $("#client_wms_id").val()
                },
                success: function(response) {
                    if (response) {
                        ChCustomerTable(response);
                    }
                }
            });
        } else {

            var msg = 'Pilih Perusahaan!';

            Swal.fire({
                icon: 'error',
                title: 'Pilih Perusahaan!',
                text: ''
            });
        }
    }

    function initDataTableSKUDelivery(delivery_order_batch_id) {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/GetSKUDelivery') ?>",
            data: {
                delivery_order_batch_id: delivery_order_batch_id
            },
            success: function(response) {
                if (response) {
                    ChDataTableSKUDelivery(response);
                }
            }
        });
    }

    function ChDataTableSKUDelivery(JSONDeliveryOrderRetur) {
        $("#table-sku-delivery > tbody").html('');

        var DeliveryOrderRetur = JSON.parse(JSONDeliveryOrderRetur);
        var no = 1;

        if (DeliveryOrderRetur.sku != 0) {
            if ($.fn.DataTable.isDataTable('#table-sku-delivery')) {
                $('#table-sku-delivery').DataTable().destroy();
            }

            $('#table-sku-delivery tbody').empty();
            $("#jml_sku").val(DeliveryOrderRetur.sku.length);

            for (i = 0; i < DeliveryOrderRetur.sku.length; i++) {

                var delivery_order_detail_id = DeliveryOrderRetur.sku[i].delivery_order_detail_id;
                var sku_id = DeliveryOrderRetur.sku[i].sku_id;
                var sku_kode = DeliveryOrderRetur.sku[i].sku_kode;
                var sku_induk_nama = DeliveryOrderRetur.sku[i].sku_induk_nama;
                var sku_nama_produk = DeliveryOrderRetur.sku[i].sku_nama_produk;
                var sku_kemasan = DeliveryOrderRetur.sku[i].sku_kemasan;
                var sku_satuan = DeliveryOrderRetur.sku[i].sku_satuan;
                var sku_harga_jual = DeliveryOrderRetur.sku[i].sku_harga_jual;
                var sku_weight_unit = DeliveryOrderRetur.sku[i].sku_weight_unit;
                var sku_weight = DeliveryOrderRetur.sku[i].sku_weight;
                var sku_length_unit = DeliveryOrderRetur.sku[i].sku_length_unit;
                var sku_length = DeliveryOrderRetur.sku[i].sku_length;
                var sku_width_unit = DeliveryOrderRetur.sku[i].sku_width_unit;
                var sku_width = DeliveryOrderRetur.sku[i].sku_width;
                var sku_height_unit = DeliveryOrderRetur.sku[i].sku_height_unit;
                var sku_height = DeliveryOrderRetur.sku[i].sku_height;
                var sku_volume_unit = DeliveryOrderRetur.sku[i].sku_volume_unit;
                var sku_volume = DeliveryOrderRetur.sku[i].sku_volume;

                var strtblsku = '';
                strtblsku = strtblsku + '<tr>';
                strtblsku = strtblsku + '	<td class="text-center">';
                strtblsku = strtblsku + '   <input type="hidden" id="sku_id' + i + '" value="' + sku_id + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_id' + i + '" value="' + sku_id + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_kode' + i + '" value="' + sku_kode + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_nama_produk' + i + '" value="' + sku_nama_produk + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_harga_satuan' + i + '" value="0">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_disc_percent' + i + '" value="0">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_disc_rp' + i + '" value="0">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_harga_nett' + i + '" value="0">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_weight' + i + '" value="' + sku_nama_produk + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_weight_unit' + i + '" value="' + sku_nama_produk + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_length' + i + '" value="' + sku_nama_produk + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_length_unit' + i + '" value="' + sku_nama_produk + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_width' + i + '" value="' + sku_nama_produk + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_width_unit' + i + '" value="' + sku_nama_produk + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_height' + i + '" value="' + sku_nama_produk + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_height_unit' + i + '" value="' + sku_nama_produk + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_volume' + i + '" value="' + sku_nama_produk + '">';
                // strtblsku = strtblsku + '   <input type="hidden" id="sku_volume_unit' + i + '" value="' + sku_nama_produk + '">';
                strtblsku = strtblsku + sku_kode + '</td>'
                strtblsku = strtblsku + '	<td class="text-center"></td>';
                strtblsku = strtblsku + '	<td class="text-center" style="width:20%;">' + sku_nama_produk + '</td>';
                strtblsku = strtblsku + '	<td class="text-center">' + sku_kemasan + '</td>';
                strtblsku = strtblsku + '	<td class="text-center">' + sku_satuan + '</td>';
                strtblsku = strtblsku + '	<td class="text-center"><select class="form-control" id="slc_req_exp_date' + i + '" onchange="UpdateReqExpDate(\'' + delivery_order_detail_id + '\',\'' + sku_id + '\', this.value,\'' + i + '\')"><option value = "0"> Tidak </option><option value="1">Ya</option > < /select></td > ';
                strtblsku = strtblsku + '	<td class="text-center"><input type="text" class="form-control" id="txt_sku_keterangan' + i + '" value="" onchange="UpdateSKUKeterangan(\'' + delivery_order_detail_id + '\',\'' + sku_id + '\', this.value,\'' + i + '\')"></td>';
                strtblsku = strtblsku + '	<td class="text-center" style="width:10%;"><input type="text" class="form-control" id="txt_sku_qty' + i + '" value="0" onchange="UpdateSKUQty(\'' + delivery_order_detail_id + '\',\'' + sku_id + '\', this.value,\'' + i + '\')"></td>';
                strtblsku = strtblsku + '	<td class="text-center"><button type="button" class="btn btn-danger" title="hapus sku" onclick="deleteRowSKU(\'' + delivery_order_detail_id + '\')"><i class="fa fa-trash"></i></button></td>';
                strtblsku = strtblsku + '</tr>';

                $("#table-sku-delivery > tbody").append(strtblsku);
            }

            // $('#data-table-sku').DataTable({
            // 	"lengthMenu": [
            // 		[-1],
            // 		["All"]
            // 	],
            // 	"ordering": false,
            // 	"searching": false
            // });
        } else {
            $('#table-sku-delivery').DataTable().clear().draw();
            // ResetForm();
        }

    }

    function initDataTableSKU() {
        $.ajax({
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/GetSKU') ?>",
            data: {
                sku_induk: $("#filter-sku-induk").val(),
                sku: $("#filter-sku-nama-produk").val(),
                kemasan: $("#filter-sku-kemasan").val(),
                satuan: $("#filter-sku-satuan").val(),
                principle: $("#filter-principle").val(),
                brand: $("#filter-brand").val()
            },
            success: function(response) {
                if (response) {
                    ChDataTableSKU(response);
                }
            }
        });
    }

    function ChCustomerTable(JSONDeliveryOrderRetur) {

        var DeliveryOrderRetur = JSON.parse(JSONDeliveryOrderRetur);

        $("#data-table-customer > tbody").html('');

        if (DeliveryOrderRetur.Customer != 0) {
            if ($.fn.DataTable.isDataTable('#data-table-customer')) {
                $('#data-table-customer').DataTable().destroy();
            }

            $('#data-table-customer tbody').empty();

            for (i = 0; i < DeliveryOrderRetur.Customer.length; i++) {
                client_pt_id = DeliveryOrderRetur.Customer[i].client_pt_id;
                client_pt_nama = DeliveryOrderRetur.Customer[i].client_pt_nama;
                client_pt_telepon = DeliveryOrderRetur.Customer[i].client_pt_telepon;
                client_pt_alamat = DeliveryOrderRetur.Customer[i].client_pt_alamat;
                client_pt_propinsi = DeliveryOrderRetur.Customer[i].client_pt_propinsi;
                client_pt_kota = DeliveryOrderRetur.Customer[i].client_pt_kota;
                client_pt_kecamatan = DeliveryOrderRetur.Customer[i].client_pt_kecamatan;
                client_pt_kelurahan = DeliveryOrderRetur.Customer[i].client_pt_kelurahan;
                client_pt_kodepos = DeliveryOrderRetur.Customer[i].client_pt_kodepos;
                area_id = DeliveryOrderRetur.Customer[i].area_id;
                area_nama = DeliveryOrderRetur.Customer[i].area_nama;
                client_pt_latitude = DeliveryOrderRetur.Customer[i].client_pt_latitude;
                client_pt_longitude = DeliveryOrderRetur.Customer[i].client_pt_longitude;

                var strmenu = ''

                strmenu = strmenu + '<tr class="text-center">';
                strmenu = strmenu + '	<td><input type="hidden" id="client_pt_nama' + i + '" value="' + client_pt_nama + '">' + client_pt_nama + '</td>';
                strmenu = strmenu + '	<td><input type="hidden" id="client_pt_alamat' + i + '" value="' + client_pt_alamat + '">' + client_pt_alamat + '</td>';
                strmenu = strmenu + '	<td><input type="hidden" id="client_pt_telepon' + i + '" value="' + client_pt_telepon + '">' + client_pt_telepon + '</td>';
                strmenu = strmenu + '	<td><input type="hidden" id="area_id' + i + '" value="' + area_id + '"><input type="hidden" id="area_nama' + i + '" value="' + area_nama + '">' + area_nama + '</td>';
                strmenu = strmenu + '	<td><button type="button" class="btn btn-sm btn-primary" onclick="PilihCustomer(\'' + client_pt_id + '\',\'' + client_pt_nama + '\',\'' + client_pt_alamat + '\',\'' + client_pt_telepon + '\',\'' + client_pt_propinsi + '\',\'' + client_pt_kota + '\',\'' + client_pt_kecamatan + '\',\'' + client_pt_kelurahan + '\',\'' + client_pt_kodepos + '\',\'' + area_id + '\',\'' + area_nama + '\',\'' + client_pt_telepon + '\',\'' + client_pt_latitude + '\',\'' + client_pt_longitude + '\')"><i class="fa fa-arrow-down"></i></button></td>';
                strmenu = strmenu + '</tr>';

                $("#data-table-customer > tbody").append(strmenu);
            }
        } else {
            $('#data-table-customer').DataTable().clear().draw();
        }
    }

    function ChDataTableSKU(JSONDeliveryOrderRetur) {
        $("#data-table-sku > tbody").html('');

        var DeliveryOrderRetur = JSON.parse(JSONDeliveryOrderRetur);
        var no = 1;

        if (DeliveryOrderRetur.sku != 0) {
            if ($.fn.DataTable.isDataTable('#data-table-sku')) {
                $('#data-table-sku').DataTable().destroy();
            }

            $('#data-table-sku tbody').empty();

            for (i = 0; i < DeliveryOrderRetur.sku.length; i++) {
                var principle = DeliveryOrderRetur.sku[i].principle;
                var brand = DeliveryOrderRetur.sku[i].brand;
                var sku_id = DeliveryOrderRetur.sku[i].sku_id;
                var sku_kode = DeliveryOrderRetur.sku[i].sku_kode;
                var sku_induk_nama = DeliveryOrderRetur.sku[i].sku_induk_nama;
                var sku_nama_produk = DeliveryOrderRetur.sku[i].sku_nama_produk;
                var sku_kemasan = DeliveryOrderRetur.sku[i].sku_kemasan;
                var sku_satuan = DeliveryOrderRetur.sku[i].sku_satuan;
                var sku_harga_jual = DeliveryOrderRetur.sku[i].sku_harga_jual;
                var sku_weight_unit = DeliveryOrderRetur.sku[i].sku_weight_unit;
                var sku_weight = DeliveryOrderRetur.sku[i].sku_weight;
                var sku_length_unit = DeliveryOrderRetur.sku[i].sku_length_unit;
                var sku_length = DeliveryOrderRetur.sku[i].sku_length;
                var sku_width_unit = DeliveryOrderRetur.sku[i].sku_width_unit;
                var sku_width = DeliveryOrderRetur.sku[i].sku_width;
                var sku_height_unit = DeliveryOrderRetur.sku[i].sku_height_unit;
                var sku_height = DeliveryOrderRetur.sku[i].sku_height;
                var sku_volume_unit = DeliveryOrderRetur.sku[i].sku_volume_unit;
                var sku_volume = DeliveryOrderRetur.sku[i].sku_volume;

                var strmenu = '';

                strmenu = strmenu + '<tr>';
                strmenu = strmenu + '	<td class="text-center">';
                strmenu = strmenu + '   <input type="checkbox" name="CheckboxSKU" id="chk_sku_' + i + '" value="' + sku_id + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_id' + i + '" value="' + sku_id + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_kode' + i + '" value="' + sku_kode + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_nama_produk' + i + '" value="' + sku_nama_produk + '">';
                strmenu = strmenu + '   <input type="hidden" id="principle' + i + '" value="' + principle + '">';
                strmenu = strmenu + '   <input type="hidden" id="brand' + i + '" value="' + brand + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_harga_satuan' + i + '" value="' + sku_harga_jual + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_disc_percent' + i + '" value="0">';
                strmenu = strmenu + '   <input type="hidden" id="sku_disc_rp' + i + '" value="0">';
                strmenu = strmenu + '   <input type="hidden" id="sku_harga_nett' + i + '" value="' + sku_harga_jual + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_weight' + i + '" value="' + sku_weight + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_weight_unit' + i + '" value="' + sku_weight_unit + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_length' + i + '" value="' + sku_length + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_length_unit' + i + '" value="' + sku_length_unit + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_width' + i + '" value="' + sku_width + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_width_unit' + i + '" value="' + sku_width_unit + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_height' + i + '" value="' + sku_height + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_height_unit' + i + '" value="' + sku_height_unit + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_volume' + i + '" value="' + sku_volume + '">';
                strmenu = strmenu + '   <input type="hidden" id="sku_volume_unit' + i + '" value="' + sku_volume_unit + '">';
                strmenu = strmenu + '   </td>'
                strmenu = strmenu + '	<td>' + principle + '</td>';
                strmenu = strmenu + '	<td>' + brand + '</td>';
                strmenu = strmenu + '	<td>' + sku_induk_nama + '</td>';
                strmenu = strmenu + '	<td>' + sku_kode + '</td>';
                strmenu = strmenu + '	<td style="width:20%;">' + sku_nama_produk + '</td>';
                strmenu = strmenu + '	<td>' + sku_kemasan + '</td>';
                strmenu = strmenu + '	<td>' + sku_satuan + '</td>';
                strmenu = strmenu + '</tr>';

                no++;

                $("#data-table-sku > tbody").append(strmenu);
            }

            // $('#data-table-sku').DataTable({
            // 	"lengthMenu": [
            // 		[-1],
            // 		["All"]
            // 	],
            // 	"ordering": false,
            // 	"searching": false
            // });
        } else {
            $('#data-table-sku').DataTable().clear().draw();
            // ResetForm();
        }

    }

    function PilihCustomer(client_pt_id, client_pt_nama, client_pt_alamat, client_pt_telepon, client_pt_propinsi, client_pt_kota, client_pt_kecamatan, client_pt_kelurahan, client_pt_kodepos, area_id, area_nama, client_pt_latitude, client_pt_longitude) {
        $("#client_pt_id").val('');
        $("#delivery_order_kirim_nama").val('');
        $("#delivery_order_kirim_alamat").val('');
        $("#delivery_order_kirim_provinsi").val('');
        $("#client_pt_propinsi").val('');
        $("#delivery_order_kirim_kota").val('');
        $("#delivery_order_kirim_kecamatan").val('');
        $("#delivery_order_kirim_kelurahan").val('');
        $("#delivery_order_kirim_kodepose").val('');
        $("#delivery_order_kirim_area").val('');

        $(".customer-name").html('');
        $(".customer-address").html('');
        $(".customer-area").html('');

        $("#client_pt_id").val(client_pt_id);
        $("#delivery_order_kirim_nama").val(client_pt_nama);
        $("#delivery_order_kirim_alamat").val(client_pt_alamat);
        $("#delivery_order_kirim_provinsi").val(client_pt_telepon);
        $("#client_pt_propinsi").val(client_pt_propinsi);
        $("#delivery_order_kirim_kota").val(client_pt_kota);
        $("#delivery_order_kirim_kecamatan").val(client_pt_kecamatan);
        $("#delivery_order_kirim_kelurahan").val(client_pt_kelurahan);
        $("#delivery_order_kirim_kodepose").val(client_pt_kodepos);
        $("#delivery_order_kirim_area").val(area_id);
        $("#delivery_order_kirim_latitude").val(client_pt_latitude);
        $("#delivery_order_kirim_longitude").val(client_pt_longitude);

        $(".customer-name").append(client_pt_nama);
        $(".customer-address").append(client_pt_alamat);
        $(".customer-area").append(area_nama);

        $("#modal-customer").modal('hide');
    }

    $("#btnback").click(
        function() {
            ResetForm();
            GetDeliveryOrderReturMenu();
        }
    );

    $("#btn-search-customer").click(
        function() {
            initDataTableCustomer();
        }
    );

    $("#btn-choose-customer").click(
        function() {
            initDataTableCustomer();
        }
    );

    $("#btn-pilih-customer").click(
        function() {
            initDataTableCustomer();
        }
    );

    // $("#btn-choose-prod-delivery").click(
    //     function() {
    //         initDataTableSKU();
    //     }
    // );

    $("#btn-search-sku").click(
        function() {
            initDataTableSKU();
        }
    );

    $("#btn-choose-sku-multi").click(function() {
        var jumlah = $('input[name="CheckboxSKU"]').length;
        var numberOfChecked = $('input[name="CheckboxSKU"]:checked').length;
        var delivery_order_batch_id = $("#delivery_order_batch_id").val();

        $("#table-sku-delivery > tbody").empty('');

        if (numberOfChecked > 0) {
            for (var i = 0; i < jumlah; i++) {
                var checked = $('[id="chk_sku_' + i + '"]:checked').length;
                var sku_id = $("#chk_sku_" + i).val();
                var sku_nama_produk = $("#sku_nama_produk" + i).val();
                var sku_kode = $("#sku_kode" + i).val();
                var sku_kemasan = $("#sku_kemasan" + i).val();
                var sku_satuan = $("#sku_satuan" + i).val();
                var sku_harga_jual = $("#sku_harga_jual" + i).val();
                var sku_weight_unit = $("#sku_weight_unit" + i).val();
                var sku_weight = $("#sku_weight" + i).val();
                var sku_length_unit = $("#sku_length_unit" + i).val();
                var sku_length = $("#sku_length" + i).val();
                var sku_width_unit = $("#sku_width_unit" + i).val();
                var sku_width = $("#sku_width" + i).val();
                var sku_height_unit = $("#sku_height_unit" + i).val();
                var sku_height = $("#sku_height" + i).val();
                var sku_volume_unit = $("#sku_volume_unit" + i).val();
                var sku_volume = $("#sku_volume" + i).val();

                if (checked > 0) {
                    $.ajax({
                        async: false,
                        type: 'POST',
                        url: "<?= base_url('WMS/SuratTugasPengiriman/InsertDeliveryOrderDetailTemp') ?>",
                        data: {
                            delivery_order_batch_id: delivery_order_batch_id,
                            sku_id: sku_id,
                            depo_id: null,
                            depo_detail_id: null,
                            sku_kode: sku_kode,
                            sku_nama_produk: sku_nama_produk,
                            sku_harga_satuan: sku_harga_jual,
                            sku_disc_percent: 0,
                            sku_disc_rp: 0,
                            sku_harga_nett: sku_harga_jual,
                            sku_request_expdate: null,
                            sku_filter_expdate: null,
                            sku_filter_expdatebulan: null,
                            sku_filter_expdatetahun: null,
                            sku_weight: sku_weight,
                            sku_weight_unit: sku_weight_unit,
                            sku_length: sku_length,
                            sku_length_unit: sku_length_unit,
                            sku_width: sku_width,
                            sku_width_unit: sku_width_unit,
                            sku_height: sku_height,
                            sku_height_unit: sku_height_unit,
                            sku_volume: sku_volume,
                            sku_volume_unit: sku_volume_unit,
                            sku_qty: 0,
                            sku_keterangan: null,
                            sku_qty_kirim: null,
                            reason_id: null
                        },
                        success: function(response) {
                            // console.log(response);
                            if (response == 1) {
                                console.log('success');
                                initDataTableSKUDelivery(delivery_order_batch_id);
                            } else {
                                if (response == 0) {
                                    var msg = 'Data Gagal Disimpan';
                                } else if (response == 2) {
                                    var msg = 'SKU ' + sku_nama_produk + ' Sudah Ada';
                                } else {
                                    var msg = response;
                                }
                                var msgtype = 'error';

                                // if (!window.__cfRLUnblockHandlers) return false;
                                new PNotify
                                    ({
                                        title: 'Error',
                                        text: msg,
                                        type: msgtype,
                                        styling: 'bootstrap3',
                                        delay: 3000,
                                        stack: stack_center
                                    });

                                initDataTableSKUDelivery(delivery_order_batch_id);

                                // console.log(msg);
                            }
                        }
                    });
                }
            }

            $("#modal-sku").modal('hide');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Pilih SKU!'
            });
        }
    });

    function UpdateReqExpDate(delivery_order_detail_id, sku_id, req_exp_date, i) {
        $.ajax({
            async: false,
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateReqExpDate') ?>",
            data: {
                delivery_order_detail_id: delivery_order_detail_id,
                sku_id: sku_id,
                req_exp_date: req_exp_date
            },
            success: function(response) {
                console.log(response);
            }
        });
    }

    function UpdateSKUKeterangan(delivery_order_detail_id, sku_id, sku_keterangan, i) {
        $.ajax({
            async: false,
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateSKUKeterangan') ?>",
            data: {
                delivery_order_detail_id: delivery_order_detail_id,
                sku_id: sku_id,
                sku_keterangan: sku_keterangan
            },
            success: function(response) {
                console.log(response);
            }
        });
    }

    function UpdateSKUQty(delivery_order_detail_id, sku_id, sku_qty, i) {
        if (sku_qty > 0) {
            $.ajax({
                async: false,
                type: 'POST',
                url: "<?= base_url('WMS/SuratTugasPengiriman/UpdateSKUQty') ?>",
                data: {
                    delivery_order_detail_id: delivery_order_detail_id,
                    sku_id: sku_id,
                    sku_qty: sku_qty
                },
                success: function(response) {
                    console.log(response);
                }
            });
        } else {
            var msg = 'Qty diisi diatas 0';

            Swal.fire({
                icon: 'error',
                title: 'Warning!',
                text: msg
            });
        }
    }

    function deleteRowSKU(delivery_order_detail_id) {
        var delivery_order_batch_id = $("#delivery_order_batch_id").val();

        $.ajax({
            async: false,
            type: 'POST',
            url: "<?= base_url('WMS/SuratTugasPengiriman/DeleteSKUDelivery') ?>",
            data: {
                delivery_order_detail_id: delivery_order_detail_id
            },
            success: function(response) {
                // console.log(response);
                if (response == 1) {
                    initDataTableSKUDelivery(delivery_order_batch_id);
                }
            }
        });
    }

    <?php
    if ($Menu_Access["D"] == 1) {
    ?> $("#btnnodeleteDeliveryOrderRetur").click(
            function() {
                GetDeliveryOrderReturMenu();
            }
        );

        // Delete DeliveryOrderRetur
        function DeleteDeliveryOrderReturMenu(DeliveryOrderReturID, DeliveryOrderReturName) {
            $("#lbdeleteDeliveryOrderReturname").html(DeliveryOrderReturName);
            $("#hddeleteDeliveryOrderReturid").val(DeliveryOrderReturID);

            $("#previewdeleteDeliveryOrderRetur").modal('show');
        }

        $("#btnyesdeleteDeliveryOrderRetur").click(
            function() {
                var DeliveryOrderReturID = $("#hddeleteDeliveryOrderReturid").val();


                $.ajax({
                    type: 'POST',
                    url: "<?= base_url('WMS/DeliveryOrderRetur/DeleteDeliveryOrderReturMenu') ?>",
                    data: {
                        DeliveryOrderReturID: DeliveryOrderReturID
                    },
                    success: function(response) {
                        if (response == 1) {
                            var msg = 'DeliveryOrderRetur berhasil dihapus.';
                            var msgtype = 'success';

                            //if (!window.__cfRLUnblockHandlers) return false;
                            new PNotify
                                ({
                                    title: 'Info',
                                    text: msg,
                                    type: msgtype,
                                    styling: 'bootstrap3',
                                    delay: 3000,
                                    stack: stack_center
                                });
                        } else {
                            var ErrMsg = response.split('$$$');
                            ErrMsg = ErrMsg[1];

                            var msg = ErrMsg;
                            var msgtype = 'error';

                            //if (!window.__cfRLUnblockHandlers) return false;
                            new PNotify
                                ({
                                    title: 'error',
                                    text: msg,
                                    type: msgtype,
                                    styling: 'bootstrap3',
                                    delay: 3000,
                                    stack: stack_center
                                });
                        }

                        GetDeliveryOrderReturMenu();
                    }
                });
            }
        );
    <?php
    }
    ?>

    <?php
    if ($Menu_Access["C"] == 1) {
    ?>

        $("#btnsavedoretur").click(
            function() {
                var delivery_order_batch_id = $("#delivery_order_batch_id").val();
                var client_wms_id = $("#client_wms_id").val();
                var delivery_order_tgl_buat_do = $("#delivery_order_tgl_buat_do").val();
                var delivery_order_tgl_expired_do = $("#delivery_order_tgl_expired_do").val();
                var delivery_order_tgl_surat_jalan = $("#delivery_order_tgl_surat_jalan").val();
                var delivery_order_tgl_rencana_kirim = $("#delivery_order_tgl_rencana_kirim").val();
                var delivery_order_tgl_aktual_kirim = $("#delivery_order_tgl_rencana_kirim").val();
                var delivery_order_keterangan = $("#delivery_order_keterangan").val();
                var delivery_order_status = $("#delivery_order_status").val();
                var delivery_order_is_prioritas = 0;
                var delivery_order_is_need_packing = 0;
                var delivery_order_tipe_layanan = $('input[id="delivery_order_tipe_layanan"]:checked').val();
                var delivery_order_tipe_pembayaran = $('input[id="delivery_order_tipe_pembayaran"]:checked').val();
                var delivery_order_sesi_pengiriman = null;
                var delivery_order_request_tgl_kirim = null;
                var delivery_order_request_jam_kirim = null;
                var tipe_pengiriman_id = null;
                var nama_tipe = null;
                var confirm_rate = null;
                var delivery_order_reff_id = null;
                var delivery_order_reff_no = null;
                var delivery_order_total = null;
                var unit_mandiri_id = "<?= $this->session->userdata('unit_mandiri_id') ?>";
                var depo_id = "<?= $this->session->userdata('depo_id') ?>";
                var client_pt_id = $("#client_pt_id").val();
                var delivery_order_kirim_nama = $("#delivery_order_kirim_nama").val();
                var delivery_order_kirim_alamat = $("#delivery_order_kirim_alamat").val();
                var delivery_order_kirim_telp = $("#delivery_order_kirim_telp").val();
                var delivery_order_kirim_provinsi = $("#delivery_order_kirim_provinsi").val();
                var delivery_order_kirim_kota = $("#delivery_order_kirim_kota").val();
                var delivery_order_kirim_kecamatan = $("#delivery_order_kirim_kecamatan").val();
                var delivery_order_kirim_kelurahan = $("#delivery_order_kirim_kelurahan").val();
                var delivery_order_kirim_latitude = $("#delivery_order_kirim_latitude").val();
                var delivery_order_kirim_longitude = $("#delivery_order_kirim_longitude").val();
                var delivery_order_kirim_kodepos = $("#delivery_order_kirim_kodepos").val();
                var delivery_order_kirim_area = $("#delivery_order_kirim_area").val();
                var delivery_order_kirim_invoice_pdf = null;
                var delivery_order_kirim_invoice_dir = null;
                var principle_id = null;
                var delivery_order_ambil_nama = null;
                var delivery_order_ambil_alamat = null;
                var delivery_order_ambil_telp = null;
                var delivery_order_ambil_provinsi = null;
                var delivery_order_ambil_kota = null;
                var delivery_order_ambil_kecamatan = null;
                var delivery_order_ambil_kelurahan = null;
                var delivery_order_ambil_latitude = null;
                var delivery_order_ambil_longitude = null;
                var delivery_order_ambil_kodepos = null;
                var delivery_order_ambil_area = null;
                var delivery_order_update_who = "<?= $this->session->userdata('pengguna_username') ?>";
                var delivery_order_update_tgl = "<?= date('Y-m-d H:i:s') ?>";
                var delivery_order_approve_who = "<?= $this->session->userdata('pengguna_username') ?>";
                var delivery_order_approve_tgl = "<?= date('Y-m-d H:i:s') ?>";
                var delivery_order_reject_who = null;
                var delivery_order_reject_tgl = null;
                var delivery_order_reject_reason = null;
                var delivery_order_no_urut_rute = 0;
                var delivery_order_prioritas_stock = 0;
                var tipe_delivery_order_id = $("#tipe_delivery_order_id").val();
                var delivery_order_draft_id = null;
                var delivery_order_draft_kode = null;
                var jml_sku = $("#jml_sku").val();

                $("#loadingview").show();
                $("#btnsavedoretur").prop("disabled", true);

                if (jml_sku > 0) {
                    $.ajax({
                        type: 'POST',
                        url: "<?= base_url('WMS/SuratTugasPengiriman/InsertDeliveryOrderRetur') ?>",
                        data: {
                            delivery_order_batch_id: delivery_order_batch_id,
                            client_wms_id: client_wms_id,
                            delivery_order_tgl_buat_do: delivery_order_tgl_buat_do,
                            delivery_order_tgl_expired_do: delivery_order_tgl_expired_do,
                            delivery_order_tgl_surat_jalan: delivery_order_tgl_surat_jalan,
                            delivery_order_tgl_rencana_kirim: delivery_order_tgl_rencana_kirim,
                            delivery_order_tgl_aktual_kirim: delivery_order_tgl_aktual_kirim,
                            delivery_order_keterangan: delivery_order_keterangan,
                            delivery_order_status: delivery_order_status,
                            delivery_order_is_prioritas: delivery_order_is_prioritas,
                            delivery_order_is_need_packing: delivery_order_is_need_packing,
                            delivery_order_tipe_layanan: delivery_order_tipe_layanan,
                            delivery_order_tipe_pembayaran: delivery_order_tipe_pembayaran,
                            delivery_order_sesi_pengiriman: delivery_order_sesi_pengiriman,
                            delivery_order_request_tgl_kirim: delivery_order_request_tgl_kirim,
                            delivery_order_request_jam_kirim: delivery_order_request_jam_kirim,
                            tipe_pengiriman_id: tipe_pengiriman_id,
                            nama_tipe: nama_tipe,
                            confirm_rate: confirm_rate,
                            delivery_order_reff_id: delivery_order_reff_id,
                            delivery_order_reff_no: delivery_order_reff_no,
                            delivery_order_total: delivery_order_total,
                            unit_mandiri_id: unit_mandiri_id,
                            depo_id: depo_id,
                            client_pt_id: client_pt_id,
                            delivery_order_kirim_nama: delivery_order_kirim_nama,
                            delivery_order_kirim_alamat: delivery_order_kirim_alamat,
                            delivery_order_kirim_telp: delivery_order_kirim_telp,
                            delivery_order_kirim_provinsi: delivery_order_kirim_provinsi,
                            delivery_order_kirim_kota: delivery_order_kirim_kota,
                            delivery_order_kirim_kecamatan: delivery_order_kirim_kecamatan,
                            delivery_order_kirim_kelurahan: delivery_order_kirim_kelurahan,
                            delivery_order_kirim_latitude: delivery_order_kirim_latitude,
                            delivery_order_kirim_longitude: delivery_order_kirim_longitude,
                            delivery_order_kirim_kodepos: delivery_order_kirim_kodepos,
                            delivery_order_kirim_area: delivery_order_kirim_area,
                            delivery_order_kirim_invoice_pdf: delivery_order_kirim_invoice_pdf,
                            delivery_order_kirim_invoice_dir: delivery_order_kirim_invoice_dir,
                            principle_id: principle_id,
                            delivery_order_ambil_nama: delivery_order_ambil_nama,
                            delivery_order_ambil_alamat: delivery_order_ambil_alamat,
                            delivery_order_ambil_telp: delivery_order_ambil_telp,
                            delivery_order_ambil_provinsi: delivery_order_ambil_provinsi,
                            delivery_order_ambil_kota: delivery_order_ambil_kota,
                            delivery_order_ambil_kecamatan: delivery_order_ambil_kecamatan,
                            delivery_order_ambil_kelurahan: delivery_order_ambil_kelurahan,
                            delivery_order_ambil_latitude: delivery_order_ambil_latitude,
                            delivery_order_ambil_longitude: delivery_order_ambil_longitude,
                            delivery_order_ambil_kodepos: delivery_order_ambil_kodepos,
                            delivery_order_ambil_area: delivery_order_ambil_area,
                            delivery_order_update_who: delivery_order_update_who,
                            delivery_order_update_tgl: delivery_order_update_tgl,
                            delivery_order_approve_who: delivery_order_approve_who,
                            delivery_order_approve_tgl: delivery_order_approve_tgl,
                            delivery_order_reject_who: delivery_order_reject_who,
                            delivery_order_reject_tgl: delivery_order_reject_tgl,
                            delivery_order_reject_reason: delivery_order_reject_reason,
                            delivery_order_no_urut_rute: delivery_order_no_urut_rute,
                            delivery_order_prioritas_stock: delivery_order_prioritas_stock,
                            tipe_delivery_order_id: tipe_delivery_order_id,
                            delivery_order_draft_id: delivery_order_draft_id,
                            delivery_order_draft_kode: delivery_order_draft_kode

                        },
                        success: function(response) {
                            $("#loadingview").hide();
                            $("#btnsavedoretur").prop("disabled", false);
                            if (response == 1) {
                                var msg = 'Delivery order berhasil ditambah';
                                var msgtype = 'success';

                                //if (!window.__cfRLUnblockHandlers) return false;
                                Swal.fire({
                                    position: 'center',
                                    icon: msgtype,
                                    title: msg,
                                    timer: 1000
                                });

                                location.href = "<?= base_url() ?>WMS/SuratTugasPengiriman/SettlementMenu/?delivery_order_batch_id=" + delivery_order_batch_id;
                                // GetDeliveryOrderReturMenu();
                            } else {
                                if (response == 2) {
                                    var msg = 'Delivery order sudah ada';
                                } else if (response == 3) {
                                    var msg = 'Nama DeliveryOrderRetur sudah ada';
                                } else {
                                    var msg = response;
                                }
                                var msgtype = 'error';

                                //if (!window.__cfRLUnblockHandlers) return false;
                                new PNotify
                                    ({
                                        title: 'Error',
                                        text: msg,
                                        type: msgtype,
                                        styling: 'bootstrap3',
                                        delay: 3000,
                                        stack: stack_center
                                    });
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {

                            $("#loadingadd").hide();
                            $("#btnsaveaddnewDeliveryOrderRetur").prop("disabled", false);
                        }
                    });

                } else {
                    var msg = 'Pilih Perusahaan!';

                    Swal.fire({
                        icon: 'error',
                        title: 'SKU belum dipilih!',
                        text: ''
                    });

                    $("#loadingview").hide();
                    $("#btnsavedoretur").prop("disabled", false);
                }
            }
        );
    <?php
    }
    ?>

    function ResetForm() {
        <?php
        if ($Menu_Access["U"] == 1) {
        ?>
            $("#txtupdateDeliveryOrderReturnama").val('');
            $("#cbupdateDeliveryOrderReturjenis").prop('selectedIndex', 0);
            $("#chupdateDeliveryOrderRetursppbr").prop('checked', false);
            $("#chupdateDeliveryOrderReturtipeecomm").prop('checked', false);
            $("#chupdateDeliveryOrderReturisactive").prop('checked', false);

            $("#loadingupdate").hide();
            $("#btnsaveupdatenewDeliveryOrderRetur").prop("disabled", false);
        <?php
        }
        ?>

        <?php
        if ($Menu_Access["C"] == 1) {
        ?>
            $("#txtDeliveryOrderReturnama").val('');
            $("#cbjenis").prop('selectedIndex', 0);
            $("#chsppbr").prop('checked', false);
            $("#chtipeecomm").prop('checked', false);
            $("#chisactive").prop('checked', false);

            $("#loadingadd").hide();
            $("#btnsaveaddnewDeliveryOrderRetur").prop("disabled", false);
        <?php
        }
        ?>

    }
</script>
