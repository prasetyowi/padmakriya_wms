<div class="row">
    <div class="col-md-4">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label name="CAPTION-NOPENGEMASAN">No. Package</label>
                    <input type="hidden" id="count_error" class="form-control">
                    <input type="text" class="form-control" id="no_package_buat_packing" placeholder="Auto" readonly>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label name="CAPTION-TANGGAL">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal_buat_packing" value="<?= Date('Y-m-d') ?>" readonly>
                </div>
            </div>
        </div>

        <div class="form-group nama_packer">
            <label name="CAPTION-NAMAPENGEMAS">Nama Packer</label>
            <select class="select2 form-control" id="nama_packer" name="nama_packer"></select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group berat_buat_packing">
            <label name="CAPTION-BERATGRAM">Berat (Gr)</label>
            <input type="text" class="form-control" id="berat_buat_packing" placeholder="berat">
        </div>

        <div class="form-group volume_buat_packing">
            <label>Volume (cm3)</label>
            <input type="text" class="form-control" id="volume_buat_packing" placeholder="volume">
        </div>

    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label name="CAPTION-KETERANGAN">Keterangan</label>
            <textarea name="keterangan_packing" id="keterangan_packing" cols="30" rows="4" class="form-control keterangan_customer" placeholder="keterangan buat packing" style="width: 100%"></textarea>
        </div>
    </div>
</div>