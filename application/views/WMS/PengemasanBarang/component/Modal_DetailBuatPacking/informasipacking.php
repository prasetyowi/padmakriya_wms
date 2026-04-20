<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label name="CAPTION-NOPENGEMASAN">No. Package</label>
            <input type="hidden" id="count_error" class="form-control" value="0">
            <input type="text" class="form-control" id="no_package_buat_packing" placeholder="Auto generate" readonly>
        </div>

        <div class="form-group">
            <label name="CAPTION-TANGGAL">Tanggal</label>
            <input type="date" class="form-control" style="width: 50%" id="tanggal_buat_packing_detail" readonly>
        </div>

        <div class="form-group nama_packer">
            <label name="CAPTION-NAMAPENGEMAS">Nama Packer</label>
            <input type="text" class="form-control" id="nama_packer_buat_packing_detail" placeholder="Nama Packer" readonly>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group berat_buat_packing">
            <label name="CAPTION-BERAT">Berat</label>
            <input type="text" class="form-control" id="berat_buat_packing_detail" placeholder="berat" readonly>
        </div>

        <div class="form-group volume_buat_packing">
            <label>Volume</label>
            <input type="text" class="form-control" id="volume_buat_packing_detail" placeholder="volume" readonly>
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label name="CAPTION-KETERANGAN">Keterangan</label>
            <textarea name="keterangan_packing" id="keterangan_packing_detail" cols="30" rows="3" class="form-control keterangan_customer" placeholder="keterangan buat packing" style="width: 100%" readonly></textarea>
        </div>
    </div>
</div>