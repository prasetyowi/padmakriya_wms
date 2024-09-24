<style type="text/css">
    .modal-body {
        max-height: calc(100vh - 210px);
        overflow-x: auto;
        overflow-y: auto;
    }

    .invalid-feedback{
        color: red;
    }

    .principle-brand-btn{
        position: relative;
        height: 30px;
        background: #333;
        border-radius: 4px;
        display: flex;
        justify-content: center;
        align-items: center;
        transition: 0.5s;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        overflow: hidden;
        text-decoration: none;
    }

    .principle-brand-btn.active{
        background: green;
    }

    .principle-brand-btn span{
        position: absolute;
        right: 20px;
        width: 15px;
        height: 15px;
        display: inline-block;
        background: #fff;
        border-bottom-left-radius: 3px;
        border-bottom-right-radius: 3px;
        transition: 0.5s;
    }

    .principle-brand-btn:hover span{
        transform: scale(1.5) rotate(-60deg) translateY(10px);
    }

    .principle-brand-btn.active span{
        left: 50%;
        transform: translateX(-50%) rotate(-45deg);
        border-radius: 0;
        width: 20px;
        height: 10px;
        background: transparent;
        border-left: 2px solid #fff;
        border-bottom: 2px solid #fff;
    }

    .principle-brand-btn span::before{
        content: '';
        position: absolute;
        top: -3px;
        right: 0;
        width: 100%;
        height: 2px;
        background: #fff;
        box-shadow: 12px -2px 0 #333, 12px -3px 0 #333,15px -1px 0 #333, 3px -2px 0 #fff;
        transition: 0.5s

    }

    .principle-brand-btn.active:hover span::before,
    .principle-brand-btn.active span::before{
        transform: scale(0);
    }

    .principle-brand-btn:hover span::before{
        transform: rotate(90deg) translateX(-50%) translateY(-10px);
    }

    .principle-brand-btn text{
        position: absolute;
        left: 20px;
        color: #fff;
        font-size: 12px;
        transition: 0.5s;
    }

    .principle-brand-btn:hover text,
    .principle-brand-btn.active text
    {
        transform: translateX(120px) translateY(-5px) scale(0);
    }

    #list_principle_brand,
    #list_principle_brand_update{
        display: flex;
        flex-wrap: wrap;
        justify-content: start;
    }
</style>

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Data Principle</h3>
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
                        <button type="button" id="btnaddnewprinciple" class="btn btn-primary" style="display: none;"><i class="fa fa-plus"></i> Tambah Principle</button>
                    </div>

                    <div class="row">
                        <!--div class="x_content" style="overflow-x:auto"-->
                        <table id="tableprinciplemenu" width="100%" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Principle</th>
                                    <th>Nama Principle</th>
                                    <th>Alamat Principle</th>
                                    <th>Telepon Principle</th>
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


                    <!-- Modal untuk menampilkan Add Principle !-->
                    <?php $this->load->view("master/Principle/component/Modal_Add")?>

                    <!-- Modal untuk menampilkan Delete Principle !-->
                    <?php $this->load->view("master/Principle/component/Modal_Delete")?>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->