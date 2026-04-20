<div class="modal fade" id="previewdeleteperusahaan" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
                <h4 class="modal-title"><label>Hapus Data Perusahaan</label></h4>
                <input type="hidden" id="hddeleteperusahaanid" />
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <h4><label>Apakah yakin untuk menghapus Data Perusahaan </label> <strong><label id="lbdeleteperusahaanname"></label></strong> ?</h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
            <span id="loadingdeleteperusahaan" style="display:none;"><i class="fa fa-spinner fa-spin"></i> Loading...</span>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="btnyesdeleteperusahaan">Iya</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="btnnodeleteperusahaan">Tidak</button>
            </div>
        </div>
    </div>
</div>