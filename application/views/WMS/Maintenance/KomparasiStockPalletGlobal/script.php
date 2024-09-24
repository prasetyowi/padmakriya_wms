<script type="text/javascript">
var ChannelCode = '';
var arr_list_sku_stock = [];
var last_num = 0;
var cek_error = 0;

function message_custom(titleType, iconType, htmlType) {
    Swal.fire({
        title: titleType,
        icon: iconType,
        html: htmlType,
    });
}

$(document).ready(function() {
    $(".select2").select2();
    // Get_list_komparasi_lokasi_stock_pallet();
});

$("#btn_search_komparasi_lokasi_stock_pallet").click(function() {
    Get_list_komparasi_lokasi_stock_pallet();
});

$("#btn_generate_komparasi_lokasi_stock_pallet").on("click", function() {
    var count = $("#table_list_komparasi_lokasi_stock_pallet > tbody > tr").length;

    if ($("#filter_hasil").val() == "") {
        message_custom("Peringatan!", "warning", "Harap pilih hasil terlebih dahulu.");
        return false;
    }

    if ($("#filter_alokasi").val() == "") {
        message_custom("Peringatan!", "warning", "Harap pilih alokasi terlebih dahulu.");
        return false;
    }

    if (count == 0) {
        message_custom("Gagal generate!", "error", "Gagal melakukan generate karena tidak ada data.");
        return false;
    }

    Swal.fire({
        title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
        cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('WMS/Maintenance/KomparasiStockPalletGlobal/proses_maintenance_stock_pallet_global') ?>",
                dataType: "JSON",
                data: {
                    depo_detail_id: $("#filter_depo_detail_id").val(),
                    principle_id: $("#filter_principle").val(),
                    hasil: $("#filter_hasil").val(),
                    alokasi: $("#filter_alokasi").val()
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Loading ...',
                        html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                        timerProgressBar: false,
                        showConfirmButton: false
                    });

                    $("#btn_generate_komparasi_lokasi_stock_pallet").prop("disabled", true);
                },
                success: function(response) {
                    if (response == 1) {
                        let alert_tes = GetLanguageByKode(
                            "CAPTION-ALERT-DATABERHASILDISIMPAN");
                        message_custom("Success", "success", alert_tes);

                        setTimeout(() => {
                            Get_list_komparasi_lokasi_stock_pallet();
                            $("#modal_edit_komparasi_lokasi_stock_pallet").modal(
                                'hide');
                        }, 500);
                    } else {
                        let alert_tes = GetLanguageByKode(
                            "CAPTION-ALERT-DATAGAGALDISIMPAN");
                        message_custom("Error", "error", alert_tes);
                    }

                    $("#btn_generate_komparasi_lokasi_stock_pallet").prop("disabled",
                        false);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    message("Error", "Error 500 Internal Server Connection Failure",
                        "error");
                    $("#btn_generate_komparasi_lokasi_stock_pallet").prop("disabled",
                        false);
                },
                complete: function() {
                    $("#btn_generate_komparasi_lokasi_stock_pallet").prop("disabled",
                        false);
                    Swal.close();
                }
            });
        }
    });
});

function Get_list_komparasi_lokasi_stock_pallet() {
    if ($("#filter_hasil").val() == "") {
        message_custom("Peringatan!", "warning", "Harap pilih hasil terlebih dahulu.");
        return false;
    }
    if ($("#filter_alokasi").val() == "") {
        message_custom("Peringatan!", "warning", "Harap pilih alokasi terlebih dahulu.");
        return false;
    }
    $.ajax({
        type: 'POST',
        url: "<?= base_url('WMS/Maintenance/KomparasiStockPalletGlobal/Get_list_komparasi_lokasi_stock_pallet') ?>",
        data: {
            depo_detail_id: $("#filter_depo_detail_id").val(),
            principle_id: $("#filter_principle").val(),
            hasil: $("#filter_hasil").val(),
            alokasi: $("#filter_alokasi").val()
        },
        beforeSend: function() {
            Swal.fire({
                title: 'Loading ...',
                html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                timerProgressBar: false,
                showConfirmButton: false
            });
        },
        dataType: "JSON",
        success: function(response) {

            $('#table_list_komparasi_lokasi_stock_pallet > tbody').empty('');

            if ($.fn.DataTable.isDataTable('#table_list_komparasi_lokasi_stock_pallet')) {
                $('#table_list_komparasi_lokasi_stock_pallet').DataTable().clear();
                $('#table_list_komparasi_lokasi_stock_pallet').DataTable().destroy();
            }

            if (response.length != 0) {
                $.each(response, function(i, v) {
                    $("#table_list_komparasi_lokasi_stock_pallet > tbody").append(`
							<tr>
								<td class="text-center">${i+1}</td>
								<td class="text-left">${v.depo_detail_nama}</td>
								<td class="text-left">${v.principle_kode}</td>
								<td class="text-left">${v.sku_kode}</td>
								<td class="text-left">${v.sku_nama_produk}</td>
								<td class="text-left">${v.sku_stock_expired_date}</td>
								<td class="text-right">${v.qtysummary}</td>
								<td class="text-right">${v.sku_stock_alokasi}</td>
								<td class="text-right">${v.sku_stock_saldo_alokasi}</td>
								<td class="text-right">${v.qtypallet}</td>
								<td class="text-left">${v.hasil}</td>
								<td class="text-center">
									<button type="button" id="btn_edit_komparasi_lokasi_stock_pallet" class="btn btn-sm btn-primary" onclick="EditListKomparasiStockPallet('${v.sku_stock_id}')"><i class="fa fa-eye"></i></button>
								</td>
							</tr>
						`);
                });

                $("#table_list_komparasi_lokasi_stock_pallet").DataTable({
                    lengthMenu: [
                        [50, 100, 200, -1],
                        [50, 100, 200, 'All'],
                    ],
                });
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

function UpdateListSKUStock(index, input) {

    let total = 0;
    total = parseInt($("#item-" + index + "-KomparasiStockPalletGlobalDetail-sku_stock_qty").val()) - parseInt($(
        "#item-" + index + "-KomparasiStockPalletGlobalDetail-sku_stock_ambil").val()) + parseInt($("#item-" +
        index + "-KomparasiStockPalletGlobalDetail-sku_stock_in").val()) - parseInt($("#item-" + index +
        "-KomparasiStockPalletGlobalDetail-sku_stock_out").val()) + parseInt($("#item-" + index +
        "-KomparasiStockPalletGlobalDetail-sku_stock_terima").val());

    $("#item-" + index + "-KomparasiStockPalletGlobalDetail-qty").val(total);

    arr_list_sku_stock[index] = ({
        'pallet_detail_id': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-pallet_detail_id").val(),
        'pallet_id': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-pallet_id").val(),
        'pallet_kode': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-pallet_kode").val(),
        'sku_stock_id': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-sku_stock_id").val(),
        'sku_stock_qty': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-sku_stock_qty").val(),
        'sku_stock_ambil': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-sku_stock_ambil").val(),
        'sku_stock_in': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-sku_stock_in").val(),
        'sku_stock_out': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-sku_stock_out").val(),
        'sku_stock_terima': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-sku_stock_terima").val(),
        'is_lock': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-status_pallet").val(),
        'lokasi': $("#item-" + index + "-KomparasiStockPalletGlobalDetail-lokasi_pallet").val(),
        'qty': total
    });


}

function DeleteListSKUStock(tipe, index) {
    var arr_list_sku_stock_temp = [];

    arr_list_sku_stock[index] = "";
    arr_list_sku_stock_temp = arr_list_sku_stock;

    arr_list_sku_stock = [];

    $.each(arr_list_sku_stock_temp, function(i, v) {
        if (v != "") {
            arr_list_sku_stock.push(v);
        }
    });

    // console.log(arr_list_sku_stock);

    GetListSKUStock(tipe);
}

function GetListSKUStock() {

    $('#table_detail_komparasi_lokasi_stock_pallet_edit > tbody').empty('');

    if ($.fn.DataTable.isDataTable('#table_detail_komparasi_lokasi_stock_pallet_edit')) {
        $('#table_detail_komparasi_lokasi_stock_pallet_edit').DataTable().clear();
        $('#table_detail_komparasi_lokasi_stock_pallet_edit').DataTable().destroy();
    }

    if (arr_list_sku_stock.length != 0) {
        $.each(arr_list_sku_stock, function(i, v) {

            $("#table_detail_komparasi_lokasi_stock_pallet_edit > tbody").append(`
					<tr class="text-center">
						<td class="text-left">
							${i+1}
							<input type="hidden" id="item-${i}-KomparasiStockPalletGlobalDetail-pallet_detail_id" class="form-control" autocomplete="off" value="${v.pallet_detail_id}">
							<input type="hidden" id="item-${i}-KomparasiStockPalletGlobalDetail-pallet_id" class="form-control" autocomplete="off" value="${v.pallet_id}">
							<input type="hidden" id="item-${i}-KomparasiStockPalletGlobalDetail-sku_stock_id" class="form-control" autocomplete="off" value="${v.sku_stock_id}">
						</td>
						<td class="text-left" style="width:25%;">
							<span>${v.lokasi}<input type="hidden" id="item-${i}-KomparasiStockPalletGlobalDetail-lokasi_pallet" value="${v.lokasi}" /></span>
						</td>
						<td class="text-left" style="width:25%;">
							<span>${v.is_lock == 0 ? 'Unlocked' : 'Locked'}<input type="hidden" id="item-${i}-KomparasiStockPalletGlobalDetail-status_pallet" value="${v.is_lock}" /></span>
						</td>
						<td class="text-left" style="width:25%;">
							<span id="item-${i}-KomparasiStockPalletGlobalDetail-pallet_kode">${v.pallet_kode}</span>
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiStockPalletGlobalDetail-sku_stock_qty" class="form-control text-right" autocomplete="off" value="${v.sku_stock_qty}" onchange="UpdateListSKUStock('${i}',this)" disabled>
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiStockPalletGlobalDetail-sku_stock_ambil" class="form-control text-right" autocomplete="off" value="${v.sku_stock_ambil}" onchange="UpdateListSKUStock('${i}',this)" disabled>
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiStockPalletGlobalDetail-sku_stock_in" class="form-control text-right" autocomplete="off" value="${v.sku_stock_in}" onchange="UpdateListSKUStock('${i}',this)" disabled>
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiStockPalletGlobalDetail-sku_stock_out" class="form-control text-right" autocomplete="off" value="${v.sku_stock_out}" onchange="UpdateListSKUStock('${i}',this)" disabled>
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiStockPalletGlobalDetail-sku_stock_terima" class="form-control text-right" autocomplete="off" value="${v.sku_stock_terima}" onchange="UpdateListSKUStock('${i}',this)" disabled>
						</td>
						<td class="text-left" style="width:12%;">
							<input type="text" id="item-${i}-KomparasiStockPalletGlobalDetail-qty" class="form-control text-right" autocomplete="off" value="${v.qty}" disabled />
						</td>
					</tr>
				`);
        });

        $("#table_detail_komparasi_lokasi_stock_pallet_edit").DataTable({
            lengthMenu: [
                [50, 100, 200, -1],
                [50, 100, 200, 'All'],
            ],
        });
    }
}

function EditListKomparasiStockPallet(sku_stock_id) {
    $("#modal_edit_komparasi_lokasi_stock_pallet").modal('show');

    arr_list_sku_stock = [];

    $.ajax({
        type: 'GET',
        url: "<?= base_url('WMS/Maintenance/KomparasiStockPalletGlobal/Get_list_komparasi_lokasi_stock_pallet_by_sku_stock_id') ?>",
        data: {
            sku_stock_id: sku_stock_id
        },
        beforeSend: function() {
            Swal.fire({
                title: 'Loading ...',
                html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                timerProgressBar: false,
                showConfirmButton: false
            });
        },
        dataType: "JSON",
        success: function(response) {
            console.log(response.header.length);
            if (response.header.length != 0) {

                $.each(response.detail, function(i, v) {
                    var debet = v.debet > 0 ? v.debet : 0;
                    var kredit = v.kredit > 0 ? v.kredit : 0;

                    arr_list_sku_stock.push({
                        'pallet_detail_id': v.pallet_detail_id,
                        'pallet_id': v.pallet_id,
                        'pallet_kode': v.pallet_kode,
                        'sku_stock_id': v.sku_stock_id,
                        'sku_stock_qty': v.sku_stock_qty,
                        'sku_stock_ambil': v.sku_stock_ambil,
                        'sku_stock_in': v.sku_stock_in,
                        'sku_stock_out': v.sku_stock_out,
                        'sku_stock_terima': v.sku_stock_terima,
                        'qty': v.qty,
                        'is_lock': v.is_lock,
                        'lokasi': v.lokasi
                    });
                });

                $.each(response.header, function(i, v) {

                    $("#KomparasiStockPalletGlobal-sku_stock_id").val(v.sku_stock_id);
                    $("#KomparasiStockPalletGlobal-principle").val(v.principle_kode);
                    $("#KomparasiStockPalletGlobal-sku_id").val(v.sku_id);
                    $("#KomparasiStockPalletGlobal-sku_kode").val(v.sku_kode);
                    $("#KomparasiStockPalletGlobal-sku_nama_produk").val(v.sku_nama_produk);
                    $("#KomparasiStockPalletGlobal-sku_stock_expired_date").val(v
                        .sku_stock_expired_date);
                    $("#KomparasiStockPalletGlobal-sku_stock_qty").val(v.qtysummary);
                    $("#KomparasiStockPalletGlobal-sku_stock_qty_pallet").val(v.qtypallet);
                    $("#KomparasiStockPalletGlobal-hasil").val(v.hasil);
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            message("Error", "Error 500 Internal Server Connection Failure", "error");
        },
        complete: function() {
            Swal.close();
        }
    });

    setTimeout(() => {
        GetListSKUStock();
    }, 500);
}

$("#btn_update_pallet_detail").on("click", function() {
    let total_rupiah_detail = 0;
    cek_error = 0;

    if ($("#KomparasiStockPalletGlobal-sku_stock_id").val() == "") {

        let alert = "SKU Stock Tidak Boleh Kosong";
        message_custom("Error", "error", alert);

        return false;
    }

    if (arr_list_sku_stock.length == 0) {

        let alert = "SKU Stock Tidak Boleh Kosong";
        message_custom("Error", "error", alert);

        return false;
    }

    setTimeout(() => {

        Swal.fire({
            title: GetLanguageByKode('CAPTION-APAANDAYAKIN'),
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: GetLanguageByKode('CAPTION-LANJUT'),
            cancelButtonText: GetLanguageByKode('CAPTION-CLOSE')
        }).then((result) => {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('WMS/Maintenance/KomparasiStockPalletGlobal/update_pallet_detail') ?>",
                dataType: "JSON",
                data: {
                    sku_stock_id: $("#KomparasiStockPalletGlobal-sku_stock_id").val(),
                    detail: arr_list_sku_stock
                },
                beforeSend: function() {
                    Swal.fire({
                        title: 'Loading ...',
                        html: '<span><i class="fa fa-spinner fa-spin" style="font-size:60px"></i></span>',
                        timerProgressBar: false,
                        showConfirmButton: false
                    });

                    $("#btn_update_pallet_detail").prop("disabled", true);
                },
                success: function(response) {
                    if (response == 1) {
                        let alert_tes = GetLanguageByKode(
                            "CAPTION-ALERT-DATABERHASILDISIMPAN");
                        message_custom("Success", "success", alert_tes);

                        setTimeout(() => {
                            Get_list_komparasi_lokasi_stock_pallet();
                            $("#modal_edit_komparasi_lokasi_stock_pallet")
                                .modal('hide');
                        }, 500);
                    } else {
                        let alert_tes = GetLanguageByKode(
                            "CAPTION-ALERT-DATAGAGALDISIMPAN");
                        message_custom("Error", "error", alert_tes);
                    }

                    $("#btn_update_pallet_detail").prop("disabled", false);
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    message("Error", "Error 500 Internal Server Connection Failure",
                        "error");
                    $("#btn_update_pallet_detail").prop("disabled", false);
                },
                complete: function() {
                    $("#btn_update_pallet_detail").prop("disabled", false);
                    Swal.close();
                }
            });
        });

    }, 500);
});

<?php
    if ($Menu_Access["D"] == 1) {
    ?>
<?php
    }
    ?>

<?php
    if ($Menu_Access["U"] == 1) {
    ?>

<?php
    }
    ?>

<?php
    if ($Menu_Access["C"] == 1) {
    ?>

<?php
    }
    ?>

function ResetForm() {

    $('#KomparasiStockPalletGlobal-trap_id').val('');
    $('#KomparasiStockPalletGlobal-tanggal').val('<?= date('Y-m-d') ?>');
    $('#KomparasiStockPalletGlobal-tanggal_jatuh_tempo').val('<?= date('Y-m-d') ?>');
    $('#KomparasiStockPalletGlobal-jumlah').val(0);
    $('#KomparasiStockPalletGlobal-keterangan').val('');
    $('#KomparasiStockPalletGlobal-updtgl').val('');
    $('#KomparasiStockPalletGlobal-updwho').val('');

    $('#KomparasiStockPalletGlobal-supplier').val("").trigger('change');

    $('#KomparasiStockPalletGlobal-trap_id').val('');
    $('#KomparasiStockPalletGlobal-tanggal').val('');
    $('#KomparasiStockPalletGlobal-periode').val('');
    $('#KomparasiStockPalletGlobal-jenis_jurnal').val('');
    $('#KomparasiStockPalletGlobal-keterangan').val('');
    $('#KomparasiStockPalletGlobal-updtgl').val('');
    $('#KomparasiStockPalletGlobal-updwho').val('');
    $('#KomparasiStockPalletGlobal-msglseg').html('');

    $('#KomparasiStockPalletGlobal-client_wms_id').val("").trigger('change');

    $('#KomparasiStockPalletGlobal-trap_id-detail').val('');
    $('#KomparasiStockPalletGlobal-tanggal-detail').val('');
    $('#KomparasiStockPalletGlobal-periode-detail').val('');
    $('#KomparasiStockPalletGlobal-jenis_jurnal-detail').val('');
    $('#KomparasiStockPalletGlobal-keterangan-detail').val('');
    $('#KomparasiStockPalletGlobal-updtgl-detail').val('');
    $('#KomparasiStockPalletGlobal-updwho-detail').val('');
    $('#KomparasiStockPalletGlobal-msglseg-detail').val('');

    $('#KomparasiStockPalletGlobal-client_wms_id-detail').val("").trigger('change');

    $('#KomparasiStockPalletGlobal-trap_id-copy').val('');
    $('#KomparasiStockPalletGlobal-tanggal-copy').val('');
    $('#KomparasiStockPalletGlobal-periode-copy').val('');
    $('#KomparasiStockPalletGlobal-jenis_jurnal-copy').val('');
    $('#KomparasiStockPalletGlobal-keterangan-copy').val('');
    $('#KomparasiStockPalletGlobal-updtgl-copy').val('');
    $('#KomparasiStockPalletGlobal-updwho-copy').val('');
    $('#KomparasiStockPalletGlobal-msglseg-copy').val('');

    $('#KomparasiStockPalletGlobal-client_wms_id-copy').val("").trigger('change');

    $("#table_detail_komparasi_lokasi_stock_pallet_tambah > tbody").html('');
    $("#table_detail_komparasi_lokasi_stock_pallet_tambah > tbody").empty();

    $("#table_detail_komparasi_lokasi_stock_pallet_edit > tbody").html('');
    $("#table_detail_komparasi_lokasi_stock_pallet_edit > tbody").empty();

    $("#table_detail_komparasi_lokasi_stock_pallet_detail > tbody").html('');
    $("#table_detail_komparasi_lokasi_stock_pallet_detail > tbody").empty();

    $("#table_detail_komparasi_lokasi_stock_pallet_copy > tbody").html('');
    $("#table_detail_komparasi_lokasi_stock_pallet_copy > tbody").empty();

    $("#total_debet_tambah").html('');
    $("#total_kredit_tambah").html('');

    $("#total_debet_edit").html('');
    $("#total_kredit_edit").html('');

    $("#total_debet_copy").html('');
    $("#total_kredit_copy").html('');

    $("#total_debet_detail").html('');
    $("#total_kredit_detail").html('');

    $("#total_debet").html('');
    $("#total_kredit").html('');
    $("#total_selisih").html('');

    $("#total_debet_edit").html('');
    $("#total_kredit_edit").html('');
    $("#total_selisih_edit").html('');

    $("#total_debet_copy").html('');
    $("#total_kredit_copy").html('');
    $("#total_selisih_copy").html('');

    $("#total_debet_detail").html('');
    $("#total_kredit_detail").html('');
    $("#total_selisih_detail").html('');
}
</script>