<?php

class M_LaporanAktivitasPengguna extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		$this->load->model(['M_DataTable', 'M_Vrbl']);
	}

	public function Get_Principle()
	{
		$query = $this->db->query("SELECT
										*
									FROM principle
									ORDER BY principle_kode ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Gudang()
	{
		$query = $this->db->query("SELECT
										*
									FROM depo_detail
									WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY depo_detail_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_karyawan_divisi()
	{
		$query = $this->db->query("SELECT
										karyawan_divisi_id,
										karyawan_divisi_nama
									FROM karyawan_divisi
									WHERE karyawan_divisi_is_aktif = '1'
									ORDER BY karyawan_divisi_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_ClientWms()
	{
		$query = $this->db->query("SELECT
										*
									FROM client_wms
									-- WHERE depo_id = '" . $this->session->userdata('depo_id') . "'
									ORDER BY client_wms_nama ASC");


		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_report_aktivitas_karyawan()
	{

		$tgl = explode(" - ", $this->input->post('filter_tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));
		$divisi = $this->input->post('filter_divisi');

		$depo_id = $this->session->userdata('depo_id');

		$query = $this->db->query("exec report_aktivitas_karyawan '$depo_id','$tgl1','$tgl2','$divisi'");


		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_report_aktivitas_karyawan_detail()
	{

		$tgl = explode(" - ", $this->input->post('filter_tanggal'));

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$karyawan_id = $this->input->post('karyawan_id');
		$tipe = $this->input->post('tipe');
		$depo_id = $this->session->userdata('depo_id');

		$sql = "";

		if ($tipe == 'FDJR') {
			$sql = "SELECT
                   fdjr.delivery_order_batch_kode,
				   fdjr.delivery_order_batch_id,
                   format(fdjr.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') as delivery_order_batch_tanggal_kirim,
                   DO.delivery_order_id,
                   DO.delivery_order_kode,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   ISNULL(dod.sku_qty, 0) AS Qty,
                   ISNULL(dod.sku_qty_kirim, 0) AS QtyKirim,
                   ISNULL(dod.sku_qty, 0) - ISNULL(dod.sku_qty_kirim, 0) AS QtyGagalKirim,
                   (ISNULL(dod.sku_qty, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPcs,
                   (ISNULL(dod.sku_qty_kirim, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyKirimPcs,
                   CAST(ISNULL(dod.sku_qty, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyCtn,
                   CAST(ISNULL(dod.sku_qty_kirim, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyKirimCtn,
                   fdjr.delivery_order_batch_status AS [Status]
            FROM delivery_order_batch fdjr
            LEFT JOIN delivery_order DO ON do.delivery_order_batch_id = fdjr.delivery_order_batch_id
            LEFT JOIN delivery_order_detail dod ON dod.delivery_order_id = do.delivery_order_id
            LEFT JOIN sku ON sku.sku_id = dod.sku_id
            WHERE fdjr.karyawan_id = '$karyawan_id' 
              AND fdjr.depo_id = '$depo_id'
              AND fdjr.delivery_order_batch_tanggal_kirim >= '$tgl1' 
              AND fdjr.delivery_order_batch_tanggal_kirim < '$tgl2'
              AND fdjr.delivery_order_batch_status <> 'Draft'";
		} else if ($tipe == 'BKB') {
			$sql = "SELECT
                   poah.picking_order_aktual_kode AS NoDokumen,
				   poah.picking_order_aktual_h_id,
                   format(poah.picking_order_aktual_tgl, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   poad.sku_stock_id,
                   format(poad.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
                   isnull(pop.sku_stock_qty_ambil, 0) AS QtyPlan,
                   isnull(poad.sku_stock_qty_ambil, 0) AS QtyAmbil,
                   (isnull(pop.sku_stock_qty_ambil, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPlanPcs,
                   (isnull(poad.sku_stock_qty_ambil, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyAmbilPcs,
                   CAST(isnull(pop.sku_stock_qty_ambil, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyPlanCtn,
                   CAST(isnull(poad.sku_stock_qty_ambil, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyAmbilCtn
            FROM picking_order_aktual_h poah
            LEFT JOIN picking_order_aktual_d poad ON poad.picking_order_aktual_h_id = poah.picking_order_aktual_h_id
            LEFT JOIN picking_order po ON po.picking_order_id = poah.picking_order_id
            LEFT JOIN picking_order_plan pop ON pop.picking_order_plan_id = poad.picking_order_plan_id
            LEFT JOIN sku ON sku.sku_id = poad.sku_id
            WHERE poah.karyawan_id = '$karyawan_id'
              AND po.depo_id = '$depo_id'
              AND poah.picking_order_aktual_tgl >= '$tgl1' 
              AND poah.picking_order_aktual_tgl < '$tgl2'";
		} else if ($tipe == 'PB') {
			$sql = "SELECT
                   pb.penerimaan_pembelian_kode AS NoDokumen,
				   pb.penerimaan_pembelian_id,
                   format(pb.penerimaan_pembelian_tgl, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   format(pb1.sku_exp_date, 'dd-MM-yyyy') AS sku_exp_date,
                   ISNULL(pb1.sku_jumlah_barang / NULLIF((SELECT COUNT(DISTINCT karyawan_id) FROM penerimaan_pembelian_detail4 WHERE penerimaan_pembelian_id = pb.penerimaan_pembelian_id), 0), 0) AS Qty,
                   ISNULL(ISNULL(pb1.sku_jumlah_terima, 0) / NULLIF((SELECT COUNT(DISTINCT karyawan_id) FROM penerimaan_pembelian_detail4 WHERE penerimaan_pembelian_id = pb.penerimaan_pembelian_id), 0), 0) AS QtyTerima,
                   (ISNULL(pb1.sku_jumlah_barang / NULLIF((SELECT COUNT(DISTINCT karyawan_id) FROM penerimaan_pembelian_detail4 WHERE penerimaan_pembelian_id = pb.penerimaan_pembelian_id), 0), 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPcs,
                   (ISNULL(pb1.sku_jumlah_terima / NULLIF((SELECT COUNT(DISTINCT karyawan_id) FROM penerimaan_pembelian_detail4 WHERE penerimaan_pembelian_id = pb.penerimaan_pembelian_id), 0), 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyTerimaPcs,
                   CAST(isnull(pb1.sku_jumlah_barang, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyCtn,
                   CAST(isnull(pb1.sku_jumlah_terima, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyTerimaCtn,
                   pb.penerimaan_pembelian_status AS [Status]
            FROM penerimaan_pembelian_detail4 pb4
            LEFT JOIN penerimaan_pembelian pb ON pb.penerimaan_pembelian_id = pb4.penerimaan_pembelian_id
            LEFT JOIN penerimaan_pembelian_detail pb1 ON pb1.penerimaan_pembelian_id = pb.penerimaan_pembelian_id
            LEFT JOIN sku ON sku.sku_id = pb1.sku_id
            WHERE pb4.karyawan_id = '$karyawan_id'
              AND pb.depo_id = '$depo_id'
              AND pb.penerimaan_pembelian_tgl >= '$tgl1' 
              AND pb.penerimaan_pembelian_tgl < '$tgl2'
              AND pb.penerimaan_pembelian_status <> 'Draft'";
		} else if ($tipe == 'BTB RETUR') {
			$sql = "SELECT
                   btb.penerimaan_penjualan_kode AS NoDokumen,
				   btb.penerimaan_penjualan_id,
                   FORMAT(btb.penerimaan_penjualan_tgl, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   FORMAT(btbd.sku_expired_date, 'dd-MM-yyyy') as sku_expired_date,
                   ISNULL(btbd.sku_jumlah_barang, 0) AS Qty,
                   ISNULL(btbd.sku_jumlah_terima, 0) AS QtyTerima,
                   (ISNULL(btbd.sku_jumlah_barang, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPcs,
                   (ISNULL(btbd.sku_jumlah_terima, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyTerimaPcs,
                   CAST(isnull(btbd.sku_jumlah_barang, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyCtn,
                   CAST(isnull(btbd.sku_jumlah_terima, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyTerimaCtn,
                   'Posted' AS [Status]
            FROM penerimaan_penjualan btb
            LEFT JOIN penerimaan_penjualan_detail btbd ON btbd.penerimaan_penjualan_id = btb.penerimaan_penjualan_id
            LEFT JOIN sku ON sku.sku_id = btbd.sku_id
            WHERE btb.karyawan_id = '$karyawan_id'
              AND btb.depo_id = '$depo_id'
              AND btb.penerimaan_penjualan_tgl >= '$tgl1' 
              AND btb.penerimaan_penjualan_tgl < '$tgl2'";
		} else if ($tipe == 'STOK OPNAM') {
			$sql = "SELECT
                   trop.tr_opname_plan_kode AS NoDokumen,
				   trop.tr_opname_plan_id,
                   format(trop.tr_opname_plan_tgl_start, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   trop3.sku_stock_id,
                   format(trop3.sku_expired_date, 'dd-MM-yyyy') AS sku_expired_date,
                   isnull(trop3.sku_qty_sistem, 0) as QtySistem,
                   isnull(trop3.sku_actual_qty_opname, 0) AS QtyAktual,
                   (isnull(trop3.sku_qty_sistem, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtySistemPcs,
                   (isnull(trop3.sku_actual_qty_opname, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyAktualPcs,
                   CAST(isnull(trop3.sku_qty_sistem, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtySistemCtn,
                   CAST(isnull(trop3.sku_actual_qty_opname, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyAktualCtn,
                   trop.tr_opname_plan_status AS [Status]
            FROM tr_opname_plan trop
            LEFT JOIN tr_opname_plan_detail3 trop3 ON trop3.tr_opname_plan_id = trop.tr_opname_plan_id
            LEFT JOIN sku ON sku.sku_id = trop3.sku_id
            WHERE trop.karyawan_id_penanggungjawab = '$karyawan_id'
              AND trop.depo_id = '$depo_id'
              AND trop.tr_opname_plan_tgl_start >= '$tgl1' 
              AND trop.tr_opname_plan_tgl_start < '$tgl2'
              AND trop.tr_opname_plan_status <> 'Draft'";
		} else if ($tipe == 'KOREKSI STOK') {
			$sql = "SELECT
                   tks.tr_koreksi_stok_kode AS NoDokumen,
				   tks.tr_koreksi_stok_id,
                   format(tks.tr_koreksi_stok_tanggal, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   tksd.sku_stock_id,
                   format(ss.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
                   isnull(tksd.sku_qty_plan_koreksi, 0) AS QtyPlan,
                   isnull(tksd.sku_qty_aktual_koreksi, 0) AS QtyAktual,
                   (isnull(tksd.sku_qty_plan_koreksi, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPlanPcs,
                   (isnull(tksd.sku_qty_aktual_koreksi, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyAktualPcs,
                   CAST(isnull(tksd.sku_qty_plan_koreksi, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyPlanCtn,
                   CAST(isnull(tksd.sku_qty_aktual_koreksi, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyAktualCtn,
                   tks.tr_koreksi_stok_status AS [Status]
            FROM tr_koreksi_stok tks
            LEFT JOIN tr_koreksi_stok_detail tksd ON tksd.tr_koreksi_stok_id = tks.tr_koreksi_stok_id
            LEFT JOIN karyawan k ON k.karyawan_nama = tks.tr_koreksi_stok_nama_checker
            LEFT JOIN sku ON sku.sku_id = tksd.sku_id
            LEFT JOIN sku_stock ss ON ss.sku_stock_id = tksd.sku_stock_id
            WHERE k.karyawan_id = '$karyawan_id'
              AND tks.depo_id_asal = '$depo_id'
              AND tks.tr_koreksi_stok_tanggal >= '$tgl1' 
              AND tks.tr_koreksi_stok_tanggal < '$tgl2'
              AND tks.tr_koreksi_stok_status <> 'Draft'";
		} else if ($tipe == 'MUTASI STOK') {
			$sql = "SELECT
                   tms.tr_mutasi_stok_kode AS NoDokumen,
				   tms.tr_mutasi_stok_id,
                   format(tms.tr_mutasi_stok_tanggal, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   tms1.sku_stock_id,
                   format(tms1.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
                   isnull(tms1.qty, 0) AS Qty,
                   (isnull(tms1.qty, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPcs,
                   CAST(isnull(tms1.qty, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyCtn,
                   tms.tr_mutasi_stok_status AS [Status]
            FROM tr_mutasi_stok tms
            LEFT JOIN tr_mutasi_stok_detail tms1 ON tms1.tr_mutasi_stok_id = tms.tr_mutasi_stok_id
            LEFT JOIN karyawan k ON k.karyawan_nama = tms.tr_mutasi_stok_nama_checker
            LEFT JOIN sku ON sku.sku_id = tms1.sku_id
            WHERE k.karyawan_id = '$karyawan_id'
              AND tms.depo_id_asal = '$depo_id'
              AND tms.tr_mutasi_stok_tanggal >= '$tgl1' 
              AND tms.tr_mutasi_stok_tanggal < '$tgl2'
              AND tms.tr_mutasi_stok_status <> 'Draft'";
		} else if ($tipe == 'MUTASI PALLET') {
			$sql = "SELECT
                   tmp.tr_mutasi_pallet_kode AS NoDokumen,
				   tmp.tr_mutasi_pallet_id,
                   format(tmp.tr_mutasi_pallet_tanggal, 'dd-MM-yyyy') AS Tanggal,
                   tmp1.pallet_id_tujuan,
                   isnull(p.pallet_kode, '') as pallet_kode,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   pd.sku_stock_id,
                   format(pd.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
                   (ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_in, 0) + ISNULL(pd.sku_stock_terima, 0)) AS Qty,
                   ((ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_in, 0) + ISNULL(pd.sku_stock_terima, 0)) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPcs,
                   CAST((ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_in, 0) + ISNULL(pd.sku_stock_terima, 0)) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyCtn,
                   tmp.tr_mutasi_pallet_status AS [Status]
            FROM tr_mutasi_pallet tmp
            LEFT JOIN tr_mutasi_pallet_detail tmp1 ON tmp1.tr_mutasi_pallet_id = tmp.tr_mutasi_pallet_id
            LEFT JOIN pallet p ON p.pallet_id = tmp1.pallet_id_tujuan
            LEFT JOIN pallet_detail pd ON pd.pallet_id = p.pallet_id
            LEFT JOIN karyawan k ON k.karyawan_nama = tmp.tr_mutasi_pallet_nama_checker
            LEFT JOIN sku ON sku.sku_id = pd.sku_id
            WHERE k.karyawan_id = '$karyawan_id'
              AND tmp.depo_id_asal = '$depo_id'
              AND tmp.tr_mutasi_pallet_tanggal >= '$tgl1' 
              AND tmp.tr_mutasi_pallet_tanggal < '$tgl2'
              AND tmp.tr_mutasi_pallet_status <> 'Draft'";
		}

		$response = $this->M_DataTable->dtTableGetList($sql);

		$output = array(
			"draw" => $response['draw'],
			"recordsTotal" => $response['recordsTotal'],
			"recordsFiltered" => $response['recordsFiltered'],
			"data" => $response['data'],
		);

		return $output;
	}

	public function get_detail_datatable($karyawan_id, $tipe, $filter_tanggal)
	{

		$tgl = explode(" - ", $filter_tanggal);

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$depo_id = $this->session->userdata('depo_id');

		$sql = "";

		if ($tipe == 'FDJR') {
			$sql = "SELECT
                   fdjr.delivery_order_batch_kode,
				   fdjr.delivery_order_batch_id,
                   format(fdjr.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') as delivery_order_batch_tanggal_kirim,
                   DO.delivery_order_id,
                   DO.delivery_order_kode,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   ISNULL(dod.sku_qty, 0) AS Qty,
                   ISNULL(dod.sku_qty_kirim, 0) AS QtyKirim,
                   ISNULL(dod.sku_qty, 0) - ISNULL(dod.sku_qty_kirim, 0) AS QtyGagalKirim,
                   (ISNULL(dod.sku_qty, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPcs,
                   (ISNULL(dod.sku_qty_kirim, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyKirimPcs,
                   CAST(ISNULL(dod.sku_qty, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyCtn,
                   CAST(ISNULL(dod.sku_qty_kirim, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyKirimCtn,
                   fdjr.delivery_order_batch_status AS [Status]
            FROM delivery_order_batch fdjr
            LEFT JOIN delivery_order DO ON do.delivery_order_batch_id = fdjr.delivery_order_batch_id
            LEFT JOIN delivery_order_detail dod ON dod.delivery_order_id = do.delivery_order_id
            LEFT JOIN sku ON sku.sku_id = dod.sku_id
            WHERE fdjr.karyawan_id = '$karyawan_id' 
              AND fdjr.depo_id = '$depo_id'
              AND fdjr.delivery_order_batch_tanggal_kirim >= '$tgl1' 
              AND fdjr.delivery_order_batch_tanggal_kirim < '$tgl2'
              AND fdjr.delivery_order_batch_status <> 'Draft'";
		} else if ($tipe == 'BKB') {
			$sql = "SELECT
                   poah.picking_order_aktual_kode AS NoDokumen,
				   poah.picking_order_aktual_h_id,
                   format(poah.picking_order_aktual_tgl, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   poad.sku_stock_id,
                   format(poad.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
                   isnull(pop.sku_stock_qty_ambil, 0) AS QtyPlan,
                   isnull(poad.sku_stock_qty_ambil, 0) AS QtyAmbil,
                   (isnull(pop.sku_stock_qty_ambil, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPlanPcs,
                   (isnull(poad.sku_stock_qty_ambil, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyAmbilPcs,
                   CAST(isnull(pop.sku_stock_qty_ambil, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyPlanCtn,
                   CAST(isnull(poad.sku_stock_qty_ambil, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyAmbilCtn
            FROM picking_order_aktual_h poah
            LEFT JOIN picking_order_aktual_d poad ON poad.picking_order_aktual_h_id = poah.picking_order_aktual_h_id
            LEFT JOIN picking_order po ON po.picking_order_id = poah.picking_order_id
            LEFT JOIN picking_order_plan pop ON pop.picking_order_plan_id = poad.picking_order_plan_id
            LEFT JOIN sku ON sku.sku_id = poad.sku_id
            WHERE poah.karyawan_id = '$karyawan_id'
              AND po.depo_id = '$depo_id'
              AND poah.picking_order_aktual_tgl >= '$tgl1' 
              AND poah.picking_order_aktual_tgl < '$tgl2'";
		} else if ($tipe == 'PB') {
			$sql = "SELECT
                   pb.penerimaan_pembelian_kode AS NoDokumen,
				   pb.penerimaan_pembelian_id,
                   format(pb.penerimaan_pembelian_tgl, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   format(pb1.sku_exp_date, 'dd-MM-yyyy') AS sku_exp_date,
                   ISNULL(pb1.sku_jumlah_barang / NULLIF((SELECT COUNT(DISTINCT karyawan_id) FROM penerimaan_pembelian_detail4 WHERE penerimaan_pembelian_id = pb.penerimaan_pembelian_id), 0), 0) AS Qty,
                   ISNULL(ISNULL(pb1.sku_jumlah_terima, 0) / NULLIF((SELECT COUNT(DISTINCT karyawan_id) FROM penerimaan_pembelian_detail4 WHERE penerimaan_pembelian_id = pb.penerimaan_pembelian_id), 0), 0) AS QtyTerima,
                   (ISNULL(pb1.sku_jumlah_barang / NULLIF((SELECT COUNT(DISTINCT karyawan_id) FROM penerimaan_pembelian_detail4 WHERE penerimaan_pembelian_id = pb.penerimaan_pembelian_id), 0), 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPcs,
                   (ISNULL(pb1.sku_jumlah_terima / NULLIF((SELECT COUNT(DISTINCT karyawan_id) FROM penerimaan_pembelian_detail4 WHERE penerimaan_pembelian_id = pb.penerimaan_pembelian_id), 0), 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyTerimaPcs,
                   CAST(isnull(pb1.sku_jumlah_barang, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyCtn,
                   CAST(isnull(pb1.sku_jumlah_terima, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyTerimaCtn,
                   pb.penerimaan_pembelian_status AS [Status]
            FROM penerimaan_pembelian_detail4 pb4
            LEFT JOIN penerimaan_pembelian pb ON pb.penerimaan_pembelian_id = pb4.penerimaan_pembelian_id
            LEFT JOIN penerimaan_pembelian_detail pb1 ON pb1.penerimaan_pembelian_id = pb.penerimaan_pembelian_id
            LEFT JOIN sku ON sku.sku_id = pb1.sku_id
            WHERE pb4.karyawan_id = '$karyawan_id'
              AND pb.depo_id = '$depo_id'
              AND pb.penerimaan_pembelian_tgl >= '$tgl1' 
              AND pb.penerimaan_pembelian_tgl < '$tgl2'
              AND pb.penerimaan_pembelian_status <> 'Draft'";
		} else if ($tipe == 'BTB RETUR') {
			$sql = "SELECT
                   btb.penerimaan_penjualan_kode AS NoDokumen,
				   btb.penerimaan_penjualan_id,
                   FORMAT(btb.penerimaan_penjualan_tgl, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   FORMAT(btbd.sku_expired_date, 'dd-MM-yyyy') as sku_expired_date,
                   ISNULL(btbd.sku_jumlah_barang, 0) AS Qty,
                   ISNULL(btbd.sku_jumlah_terima, 0) AS QtyTerima,
                   (ISNULL(btbd.sku_jumlah_barang, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPcs,
                   (ISNULL(btbd.sku_jumlah_terima, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyTerimaPcs,
                   CAST(isnull(btbd.sku_jumlah_barang, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyCtn,
                   CAST(isnull(btbd.sku_jumlah_terima, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyTerimaCtn,
                   'Posted' AS [Status]
            FROM penerimaan_penjualan btb
            LEFT JOIN penerimaan_penjualan_detail btbd ON btbd.penerimaan_penjualan_id = btb.penerimaan_penjualan_id
            LEFT JOIN sku ON sku.sku_id = btbd.sku_id
            WHERE btb.karyawan_id = '$karyawan_id'
              AND btb.depo_id = '$depo_id'
              AND btb.penerimaan_penjualan_tgl >= '$tgl1' 
              AND btb.penerimaan_penjualan_tgl < '$tgl2'";
		} else if ($tipe == 'STOK OPNAM') {
			$sql = "SELECT
                   trop.tr_opname_plan_kode AS NoDokumen,
				   trop.tr_opname_plan_id,
                   format(trop.tr_opname_plan_tgl_start, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   trop3.sku_stock_id,
                   format(trop3.sku_expired_date, 'dd-MM-yyyy') AS sku_expired_date,
                   isnull(trop3.sku_qty_sistem, 0) as QtySistem,
                   isnull(trop3.sku_actual_qty_opname, 0) AS QtyAktual,
                   (isnull(trop3.sku_qty_sistem, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtySistemPcs,
                   (isnull(trop3.sku_actual_qty_opname, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyAktualPcs,
                   CAST(isnull(trop3.sku_qty_sistem, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtySistemCtn,
                   CAST(isnull(trop3.sku_actual_qty_opname, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyAktualCtn,
                   trop.tr_opname_plan_status AS [Status]
            FROM tr_opname_plan trop
            LEFT JOIN tr_opname_plan_detail3 trop3 ON trop3.tr_opname_plan_id = trop.tr_opname_plan_id
            LEFT JOIN sku ON sku.sku_id = trop3.sku_id
            WHERE trop.karyawan_id_penanggungjawab = '$karyawan_id'
              AND trop.depo_id = '$depo_id'
              AND trop.tr_opname_plan_tgl_start >= '$tgl1' 
              AND trop.tr_opname_plan_tgl_start < '$tgl2'
              AND trop.tr_opname_plan_status <> 'Draft'";
		} else if ($tipe == 'KOREKSI STOK') {
			$sql = "SELECT
                   tks.tr_koreksi_stok_kode AS NoDokumen,
				   tks.tr_koreksi_stok_id,
                   format(tks.tr_koreksi_stok_tanggal, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   tksd.sku_stock_id,
                   format(ss.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
                   isnull(tksd.sku_qty_plan_koreksi, 0) AS QtyPlan,
                   isnull(tksd.sku_qty_aktual_koreksi, 0) AS QtyAktual,
                   (isnull(tksd.sku_qty_plan_koreksi, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPlanPcs,
                   (isnull(tksd.sku_qty_aktual_koreksi, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyAktualPcs,
                   CAST(isnull(tksd.sku_qty_plan_koreksi, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyPlanCtn,
                   CAST(isnull(tksd.sku_qty_aktual_koreksi, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyAktualCtn,
                   tks.tr_koreksi_stok_status AS [Status]
            FROM tr_koreksi_stok tks
            LEFT JOIN tr_koreksi_stok_detail tksd ON tksd.tr_koreksi_stok_id = tks.tr_koreksi_stok_id
            LEFT JOIN karyawan k ON k.karyawan_nama = tks.tr_koreksi_stok_nama_checker
            LEFT JOIN sku ON sku.sku_id = tksd.sku_id
            LEFT JOIN sku_stock ss ON ss.sku_stock_id = tksd.sku_stock_id
            WHERE k.karyawan_id = '$karyawan_id'
              AND tks.depo_id_asal = '$depo_id'
              AND tks.tr_koreksi_stok_tanggal >= '$tgl1' 
              AND tks.tr_koreksi_stok_tanggal < '$tgl2'
              AND tks.tr_koreksi_stok_status <> 'Draft'";
		} else if ($tipe == 'MUTASI STOK') {
			$sql = "SELECT
                   tms.tr_mutasi_stok_kode AS NoDokumen,
				   tms.tr_mutasi_stok_id,
                   format(tms.tr_mutasi_stok_tanggal, 'dd-MM-yyyy') AS Tanggal,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   tms1.sku_stock_id,
                   format(tms1.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
                   isnull(tms1.qty, 0) AS Qty,
                   (isnull(tms1.qty, 0) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPcs,
                   CAST(isnull(tms1.qty, 0) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyCtn,
                   tms.tr_mutasi_stok_status AS [Status]
            FROM tr_mutasi_stok tms
            LEFT JOIN tr_mutasi_stok_detail tms1 ON tms1.tr_mutasi_stok_id = tms.tr_mutasi_stok_id
            LEFT JOIN karyawan k ON k.karyawan_nama = tms.tr_mutasi_stok_nama_checker
            LEFT JOIN sku ON sku.sku_id = tms1.sku_id
            WHERE k.karyawan_id = '$karyawan_id'
              AND tms.depo_id_asal = '$depo_id'
              AND tms.tr_mutasi_stok_tanggal >= '$tgl1' 
              AND tms.tr_mutasi_stok_tanggal < '$tgl2'
              AND tms.tr_mutasi_stok_status <> 'Draft'";
		} else if ($tipe == 'MUTASI PALLET') {
			$sql = "SELECT
                   tmp.tr_mutasi_pallet_kode AS NoDokumen,
				   tmp.tr_mutasi_pallet_id,
                   format(tmp.tr_mutasi_pallet_tanggal, 'dd-MM-yyyy') AS Tanggal,
                   tmp1.pallet_id_tujuan,
                   isnull(p.pallet_kode, '') as pallet_kode,
                   sku.sku_kode,
                   sku.sku_nama_produk,
                   pd.sku_stock_id,
                   format(pd.sku_stock_expired_date, 'dd-MM-yyyy') AS sku_stock_expired_date,
                   (ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_in, 0) + ISNULL(pd.sku_stock_terima, 0)) AS Qty,
                   ((ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_in, 0) + ISNULL(pd.sku_stock_terima, 0)) * ISNULL(sku.sku_konversi_faktor, 0)) AS QtyPcs,
                   CAST((ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_in, 0) + ISNULL(pd.sku_stock_terima, 0)) * ISNULL(sku.sku_konversi_faktor, 0) / isnull((SELECT max(sku_konversi_faktor) FROM sku WHERE sku_konversi_group = sku.sku_konversi_group),0) AS decimal(18, 4)) AS QtyCtn,
                   tmp.tr_mutasi_pallet_status AS [Status]
            FROM tr_mutasi_pallet tmp
            LEFT JOIN tr_mutasi_pallet_detail tmp1 ON tmp1.tr_mutasi_pallet_id = tmp.tr_mutasi_pallet_id
            LEFT JOIN pallet p ON p.pallet_id = tmp1.pallet_id_tujuan
            LEFT JOIN pallet_detail pd ON pd.pallet_id = p.pallet_id
            LEFT JOIN karyawan k ON k.karyawan_nama = tmp.tr_mutasi_pallet_nama_checker
            LEFT JOIN sku ON sku.sku_id = pd.sku_id
            WHERE k.karyawan_id = '$karyawan_id'
              AND tmp.depo_id_asal = '$depo_id'
              AND tmp.tr_mutasi_pallet_tanggal >= '$tgl1' 
              AND tmp.tr_mutasi_pallet_tanggal < '$tgl2'
              AND tmp.tr_mutasi_pallet_status <> 'Draft'";
		}

		$query = $this->db->query($sql);

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result();
		}

		return $query;
	}

	public function get_jml_detail_datatable($karyawan_id, $tipe, $filter_tanggal)
	{

		$tgl = explode(" - ", $filter_tanggal);

		$tgl1 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[0])));
		$tgl2 = date('Y-m-d', strtotime(str_replace("/", "-", $tgl[1])));

		$depo_id = $this->session->userdata('depo_id');

		$sql = "";

		if ($tipe == 'FDJR') {
			$sql = "SELECT
                   count(*) as jml
            FROM delivery_order_batch fdjr
            LEFT JOIN delivery_order DO ON do.delivery_order_batch_id = fdjr.delivery_order_batch_id
            LEFT JOIN delivery_order_detail dod ON dod.delivery_order_id = do.delivery_order_id
            LEFT JOIN sku ON sku.sku_id = dod.sku_id
            WHERE fdjr.karyawan_id = '$karyawan_id' 
              AND fdjr.depo_id = '$depo_id'
              AND fdjr.delivery_order_batch_tanggal_kirim >= '$tgl1' 
              AND fdjr.delivery_order_batch_tanggal_kirim < '$tgl2'
              AND fdjr.delivery_order_batch_status <> 'Draft'";
		} else if ($tipe == 'BKB') {
			$sql = "SELECT
                   count(*) as jml
            FROM picking_order_aktual_h poah
            LEFT JOIN picking_order_aktual_d poad ON poad.picking_order_aktual_h_id = poah.picking_order_aktual_h_id
            LEFT JOIN picking_order po ON po.picking_order_id = poah.picking_order_id
            LEFT JOIN picking_order_plan pop ON pop.picking_order_plan_id = poad.picking_order_plan_id
            LEFT JOIN sku ON sku.sku_id = poad.sku_id
            WHERE poah.karyawan_id = '$karyawan_id'
              AND po.depo_id = '$depo_id'
              AND poah.picking_order_aktual_tgl >= '$tgl1' 
              AND poah.picking_order_aktual_tgl < '$tgl2'";
		} else if ($tipe == 'PB') {
			$sql = "SELECT
                   count(*) as jml
            FROM penerimaan_pembelian_detail4 pb4
            LEFT JOIN penerimaan_pembelian pb ON pb.penerimaan_pembelian_id = pb4.penerimaan_pembelian_id
            LEFT JOIN penerimaan_pembelian_detail pb1 ON pb1.penerimaan_pembelian_id = pb.penerimaan_pembelian_id
            LEFT JOIN sku ON sku.sku_id = pb1.sku_id
            WHERE pb4.karyawan_id = '$karyawan_id'
              AND pb.depo_id = '$depo_id'
              AND pb.penerimaan_pembelian_tgl >= '$tgl1' 
              AND pb.penerimaan_pembelian_tgl < '$tgl2'
              AND pb.penerimaan_pembelian_status <> 'Draft'";
		} else if ($tipe == 'BTB RETUR') {
			$sql = "SELECT
                   count(*) as jml
            FROM penerimaan_penjualan btb
            LEFT JOIN penerimaan_penjualan_detail btbd ON btbd.penerimaan_penjualan_id = btb.penerimaan_penjualan_id
            LEFT JOIN sku ON sku.sku_id = btbd.sku_id
            WHERE btb.karyawan_id = '$karyawan_id'
              AND btb.depo_id = '$depo_id'
              AND btb.penerimaan_penjualan_tgl >= '$tgl1' 
              AND btb.penerimaan_penjualan_tgl < '$tgl2'";
		} else if ($tipe == 'STOK OPNAM') {
			$sql = "SELECT
                   count(*) as jml
            FROM tr_opname_plan trop
            LEFT JOIN tr_opname_plan_detail3 trop3 ON trop3.tr_opname_plan_id = trop.tr_opname_plan_id
            LEFT JOIN sku ON sku.sku_id = trop3.sku_id
            WHERE trop.karyawan_id_penanggungjawab = '$karyawan_id'
              AND trop.depo_id = '$depo_id'
              AND trop.tr_opname_plan_tgl_start >= '$tgl1' 
              AND trop.tr_opname_plan_tgl_start < '$tgl2'
              AND trop.tr_opname_plan_status <> 'Draft'";
		} else if ($tipe == 'KOREKSI STOK') {
			$sql = "SELECT
                   count(*) as jml
            FROM tr_koreksi_stok tks
            LEFT JOIN tr_koreksi_stok_detail tksd ON tksd.tr_koreksi_stok_id = tks.tr_koreksi_stok_id
            LEFT JOIN karyawan k ON k.karyawan_nama = tks.tr_koreksi_stok_nama_checker
            LEFT JOIN sku ON sku.sku_id = tksd.sku_id
            LEFT JOIN sku_stock ss ON ss.sku_stock_id = tksd.sku_stock_id
            WHERE k.karyawan_id = '$karyawan_id'
              AND tks.depo_id_asal = '$depo_id'
              AND tks.tr_koreksi_stok_tanggal >= '$tgl1' 
              AND tks.tr_koreksi_stok_tanggal < '$tgl2'
              AND tks.tr_koreksi_stok_status <> 'Draft'";
		} else if ($tipe == 'MUTASI STOK') {
			$sql = "SELECT
                   count(*) as jml
            FROM tr_mutasi_stok tms
            LEFT JOIN tr_mutasi_stok_detail tms1 ON tms1.tr_mutasi_stok_id = tms.tr_mutasi_stok_id
            LEFT JOIN karyawan k ON k.karyawan_nama = tms.tr_mutasi_stok_nama_checker
            LEFT JOIN sku ON sku.sku_id = tms1.sku_id
            WHERE k.karyawan_id = '$karyawan_id'
              AND tms.depo_id_asal = '$depo_id'
              AND tms.tr_mutasi_stok_tanggal >= '$tgl1' 
              AND tms.tr_mutasi_stok_tanggal < '$tgl2'
              AND tms.tr_mutasi_stok_status <> 'Draft'";
		} else if ($tipe == 'MUTASI PALLET') {
			$sql = "SELECT
                   count(*) as jml
            FROM tr_mutasi_pallet tmp
            LEFT JOIN tr_mutasi_pallet_detail tmp1 ON tmp1.tr_mutasi_pallet_id = tmp.tr_mutasi_pallet_id
            LEFT JOIN pallet p ON p.pallet_id = tmp1.pallet_id_tujuan
            LEFT JOIN pallet_detail pd ON pd.pallet_id = p.pallet_id
            LEFT JOIN karyawan k ON k.karyawan_nama = tmp.tr_mutasi_pallet_nama_checker
            LEFT JOIN sku ON sku.sku_id = pd.sku_id
            WHERE k.karyawan_id = '$karyawan_id'
              AND tmp.depo_id_asal = '$depo_id'
              AND tmp.tr_mutasi_pallet_tanggal >= '$tgl1' 
              AND tmp.tr_mutasi_pallet_tanggal < '$tgl2'
              AND tmp.tr_mutasi_pallet_status <> 'Draft'";
		}

		$query = $this->db->query($sql);

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->jml;
		}

		return $query;
	}

	//
}
