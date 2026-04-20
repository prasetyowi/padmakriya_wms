<style>
    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-x: auto;
        overflow-y: auto;
    }

    .invalid-feedback{
        color: red;
    }
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Data Outlet</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <button type="button" id="btnaddnewoutlet" class="btn btn-primary" style="display: none;"><i class="fa fa-plus"></i> Tambah Outlet</button>
                    </div>

                    <div class="row">
                        <!--div class="x_content" style="overflow-x:auto"-->
                        <table id="tableoutletmenu" width="100%" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Outlet</th>
                                    <th>Alamat Outlet</th>
                                    <th>Telepon Outlet</th>
                                    <th>Nama Contact Person</th>
                                    <th>Telepon Contact Person</th>
                                    <th>Status</th>
                                    <th>Opsi</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                        <!--/div-->
                    </div>

                    <!-- Modal untuk menampilkan Add Channel !-->
                    <?php $this->load->view("master/Outlet/component/Modal_Add")?>

                    <!-- Modal untuk menampilkan Delete Channel !-->
                    <?php $this->load->view("master/Outlet/component/Modal_Delete")?>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->