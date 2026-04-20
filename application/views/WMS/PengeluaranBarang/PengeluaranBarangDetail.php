<!-- Style -->
<?php $this->load->view("WMS/PengeluaranBarang/component/Style") ?>

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 name="CAPTION-MENUPERMINTAANPENGELUARANBARANG"></h3>
				<!-- <div style="float: right">
               <button onclick="fullHandler()">Full Screen</button>
               <button onclick="exitHandler()">Exit Screen</button>
            </div> -->
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
						<!-- Pencarian BKB -->
						<?php $this->load->view("WMS/PengeluaranBarang/component/PencarianBKB") ?>
					</div>
					<div class="row">
						<input type="hidden" id="txtcheckerpb" class="form-control" value="" />
						<span id="loadingview" style="display:none;"><i class="fa fa-spinner fa-spin"></i> &nbsp;<label name="CAPTION-LOADING"></label></span>

						<!-- bulk -->
						<?php $this->load->view("WMS/PengeluaranBarang/component/FormBulk/DetailBKBBulk") ?>

						<!-- standar -->
						<?php $this->load->view("WMS/PengeluaranBarang/component/FormStandar/DetailBKBStandar") ?>

						<!-- Kirim Ulang -->
						<?php $this->load->view("WMS/PengeluaranBarang/component/FormKirimUlang/DetailBKBKirimUlang") ?>

						<!-- Canvas -->
						<?php $this->load->view("WMS/PengeluaranBarang/component/FormCanvas/DetailBKBCanvas") ?>
					</div>

				</div>
			</div>
		</div>
		<div class="panel">
			<div class="panel-body form-horizontal form-label-left">
				<div class="row pull-right">
					<span id="loadingadd" style="display:none;"><i class="fa fa-spinner fa-spin"></i> &nbsp;<label name="CAPTION-LOADING"></label></span>
					<button type="button" id="btnkonfirmasibarang" class="btn btn-success"><label name="CAPTION-KONFIRMASIPENGELUARANBARANGSELESAI"></label></button>
					<button type="button" id="btnsavebkb" class="btn btn-success"><label name="CAPTION-SIMPAN"></label></button>
					<button type="button" id="btnbatalbkb" class="btn btn-danger"><label name="CAPTION-BATAL"></label></button>
					<a href="<?php echo base_url('WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu'); ?>" class="btn btn-danger" name="CAPTION-KEMBALI"></a>
				</div>
			</div>
		</div>
	</div>
</div>



<!-- Form BKB Bulk And BKB Canvas -->

<?php $this->load->view("WMS/PengeluaranBarang/component/DetailBKBAktualPlan") ?>

<!-- Form BKB Bulk And BKB Canvas End -->


<!-- Konfirmasi bkb start -->

<?php $this->load->view("WMS/PengeluaranBarang/component/KonfirmasiBKB") ?>

<?php $this->load->view("WMS/PengeluaranBarang/component/KonfirmasiBatalBKB") ?>

<?php $this->load->view("WMS/PengeluaranBarang/component/KonfirmasiSelesaiBKB") ?>

<!-- Konfirmasi bkb end -->

<!-- bulk start -->

<?php $this->load->view("WMS/PengeluaranBarang/component/FormBulk/DaftarBKBBulk") ?>

<?php $this->load->view("WMS/PengeluaranBarang/component/FormBulk/FormAddBKBBulk") ?>

<?php $this->load->view("WMS/PengeluaranBarang/component/FormBulk/FormUpdateBKBBulk") ?>

<!-- bulk end -->

<!-- standar start -->

<?php $this->load->view("WMS/PengeluaranBarang/component/FormStandar/DaftarBKBStandar") ?>

<?php $this->load->view("WMS/PengeluaranBarang/component/FormStandar/FormAddBKBStandar") ?>

<?php $this->load->view("WMS/PengeluaranBarang/component/FormStandar/FormUpdateBKBStandar") ?>

<!-- standar end -->

<!-- kirim ulang start -->

<?php $this->load->view("WMS/PengeluaranBarang/component/FormKirimUlang/DaftarBKBKirimUlang") ?>

<?php $this->load->view("WMS/PengeluaranBarang/component/FormKirimUlang/FormAddBKBKirimUlang") ?>

<?php $this->load->view("WMS/PengeluaranBarang/component/FormKirimUlang/FormUpdateBKBKirimUlang") ?>

<!-- kirim ulang end -->

<!-- bulk start -->

<?php $this->load->view("WMS/PengeluaranBarang/component/FormCanvas/DaftarBKBCanvas") ?>

<?php $this->load->view("WMS/PengeluaranBarang/component/FormCanvas/FormAddBKBCanvas") ?>

<?php $this->load->view("WMS/PengeluaranBarang/component/FormCanvas/FormUpdateBKBCanvas") ?>

<!-- bulk end -->

<!-- scan start -->

<?php $this->load->view("WMS/PengeluaranBarang/component/ModalScan") ?>

<!-- scan end -->