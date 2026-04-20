<section id="list-table-section-perintah-kerja">
	<h5 class="text-center">
		<strong><label name="CAPTION-DAFTARSURATPERINTAHKERJA">Daftar Surat Perintah Kerja</label></strong>
	</h5>
	<div class="parrent-table-surat-kerja">
		<table class="table table-xs table-striped" width="100%" id="listTableDaftarSuratKerja" style="font-size:11px;">
			<thead>
				<tr class="text-center">
					<td width="10%"><strong><label name="CAPTION-JENISSPK">Jenis SPK</label></strong></td>
					<td width="10%"><strong><label name="CAPTION-KODESPK">Kode SPK</label></strong></td>
					<td width="10%"><strong><label name="CAPTION-MULAI">Mulai</label></strong></td>
					<td width="10%"><strong><label name="CAPTION-AKHIR">Selesai</label></strong></td>
					<td width="10%"><strong><label name="CAPTION-PROGRESS">Progress</label></strong></td>
					<td width="15%"><strong><label name="CAPTION-STATUS">Status</label></strong></td>
					<td width="10%" class="actionHeader"><strong><label name="CAPTION-ACTION">Action</label></strong></td>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($dataOpname)) {; ?>
					<?php foreach ($dataOpname as $key => $data) { ?>
						<tr class="text-center">
							<td><?= $data['tipe_stok'] ?></td>
							<td><?= $data['kode'] ?></td>
							<td><?= $data['tgl_start'] ?></td>
							<td><?= $data['tgl_end'] ?></td>
							<td>

								<?php
								if ($data['persent'] >= 0 && $data['persent'] <= 15) {
									$pgr = "progress-bar-danger";
								} else if ($data['persent'] >= 16 && $data['persent'] <= 50) {
									$pgr = "progress-bar-warning";
								} else if ($data['persent'] >= 51 && $data['persent'] <= 99) {
									$pgr = "";
								} else {
									$pgr = "progress-bar-success";
								}
								?>

								<div class="progress-cuy progress-striped-cuy active">
									<div role="progressbar progress-striped-cuy" style="width: <?= $data['persent'] ?>%;" class="progress-bar-cuy <?= $pgr ?>"><span><?= $data['persent'] ?>%</span></div>
								</div>
							</td>
							<td><?= $data['status'] ?></td>
							<td class="actionBody">
								<?php if ($data['status'] == "In Progress" || $data['status'] == "In Progress Revision") { ?>
									<button class="btn btn-primary btn-xs" onclick="handlerProsesDataOpname('<?= $data['id'] ?>', '<?= $data['status'] ?>', '<?= $data['depo_detail_id'] ?>')"><i class="fas fa-refresh"></i> &nbsp;<label name="CAPTION-PROSES">Proses</label></button>

								<?php } else { ?>
									<button class="btn btn-primary btn-xs" disabled><i class="fas fa-refresh"></i> &nbsp;<label name="CAPTION-PROSES">Proses</label></button>
								<?php } ?>
								<?php if ($data['status'] == "Completed") { ?>
									<button class="btn btn-success btn-xs" onclick="cetakOpname('<?= $data['id'] ?>', '<?= $data['status'] ?>', '<?= $data['depo_detail_id'] ?>')"><i class="fas fa-print"></i> &nbsp;<label name="">Cetak</label></button>
								<?php } ?>

							</td>
						</tr>

					<?php } ?>

				<?php } ?>

			</tbody>
		</table>
	</div>
</section>