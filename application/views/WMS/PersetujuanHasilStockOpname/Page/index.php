<?php $this->load->view("WMS/PersetujuanHasilStockOpname/Component/Script/Style/index") ?>

<div class="right_col" role="main">
	<div class="">
		<div class="page-title">
			<div class="title_left">
				<h3 id="titleMenu" name="CAPTION-123007000">Hasil Stock Opname</h3>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="wrapper">
			<input type="radio" name="selectSplit" id="option-1" value="0" class="chkCompareOrApproval" onchange="chkCompareOrApproval(event)" checked>
			<input type="radio" name="selectSplit" id="option-2" value="1" class="chkCompareOrApproval" onchange="chkCompareOrApproval(event)">
			<input type="radio" name="selectSplit" id="option-3" value="2" class="chkCompareOrApproval" onchange="chkCompareOrApproval(event)">
			<label for="option-1" class="option option-1">
				<span name="CAPTION-ADJUSMENTSTOCK">Stok Penyesuaian</span>
			</label>
			<label for="option-2" class="option option-2">
				<span name="CAPTION-COMPARE">Membandingkan</span>
			</label>
			<label for="option-3" class="option option-3">
				<span name="CAPTION-123007000">Hasil Stock Opname</span>
			</label>
		</div>
		<hr>

		<div id="showApproval" style="margin-top: 20px; width: 100%;position:relative;max-height:100%; display: none">
			<!-- list table surat kerja -->
			<?php $this->load->view("WMS/PersetujuanHasilStockOpname/Component/Section/headerHasilOpname") ?>

			<!-- list table surat kerja -->
			<?php $this->load->view("WMS/PersetujuanHasilStockOpname/Component/Section/detailHasilOpname") ?>

			<!-- load modal -->
			<?php $this->load->view("WMS/PersetujuanHasilStockOpname/Component/Modal/sku") ?>
		</div>
	</div>

	<div id="showCompare" style="margin-top: 20px; width: 100%;position:relative;max-height:100%; display: none">
		<?php $this->load->view("WMS/PersetujuanHasilStockOpname/Component/Section/compareOpname") ?>
	</div>


</div>
</div>

<!-- /page content -->