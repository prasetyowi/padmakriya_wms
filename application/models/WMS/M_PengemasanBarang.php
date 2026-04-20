<?php

class M_PengemasanBarang extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function Get_Packing($date_create, $no_picking_list, $no_batch_do, $tipe_layanan, $tipe_pengiriman, $tipe_picking_list, $status, $area)
	{
		$depo_id = $this->session->userdata('depo_id');
		$no_pick = $no_picking_list != "" ? "AND pl.picking_list_kode = '$no_picking_list'" : "";
		$no_batch = $no_batch_do != "" ? "AND dob.delivery_order_batch_kode = '$no_batch_do'" : "";
		$layanan = $tipe_layanan != "" ? "AND dob.delivery_order_batch_tipe_layanan_nama = '$tipe_layanan'" : "";
		$pengiriman = $tipe_pengiriman != "" ? "AND dob.tipe_pengiriman_id = '$tipe_pengiriman'" : "";
		$picking_list = $tipe_picking_list != "" ? "AND tdo.tipe_delivery_order_nama = '$tipe_picking_list'" : "";
		$stts = $status != "" ? "AND dob.delivery_order_batch_status = '$status'" : "";
		$are = $area != "" ? "AND dob.area_id = '$area'" : "";

		$query = $this->db->query("select
                                        pl.picking_list_id,
                                        max(pl.area_id) as area_id,
                                        max(pl.delivery_order_batch_id) as delivery_order_batch_id,
                                        max(pl.picking_list_kode) as picking_list_kode,
                                        max(tdo.tipe_delivery_order_alias) as picking_list_tipe,
                                        max(FORMAT(pl.picking_list_tgl_kirim, 'dd-MM-yyyy')) as picking_list_tgl_kirim,
                                        max(FORMAT(pl.picking_list_create_tgl, 'dd-MM-yyyy')) as picking_list_create_tgl,
                                        max(dob.delivery_order_batch_status) as picking_list_status,
                                        max(dob.delivery_order_batch_id) as delivery_order_batch_id,
                                        max(dob.delivery_order_batch_kode) as delivery_order_batch_kode,
                                        max(dob.delivery_order_batch_tipe_layanan_nama) as delivery_order_batch_tipe_layanan_nama,
                                        max(dob.delivery_order_batch_is_need_packing) as delivery_order_batch_is_need_packing,
                                        max(dob.tipe_pengiriman_id) as tipe_pengiriman_id,
                                        max(png.tipe_pengiriman_nama_tipe) as tipe_pengiriman,
                                        max(area.area_nama) as area_nama
                                    from picking_list as pl
                                        left join picking_list_detail pld on pl.picking_list_id = pld.picking_list_id
                                        left join delivery_order do on pld.delivery_order_id = do.delivery_order_id
                                        left join delivery_order_batch as dob on pl.delivery_order_batch_id = dob.delivery_order_batch_id
                                        left join tipe_pengiriman as png on png.tipe_pengiriman_id = dob.tipe_pengiriman_id
                                        left join area on area.area_id = pl.area_id
                                        left join tipe_delivery_order as tdo on dob.tipe_delivery_order_id = tdo.tipe_delivery_order_id and pl.tipe_delivery_order_id = tdo.tipe_delivery_order_id
                                        left join picking_order as po on dob.picking_order_id = po.picking_order_id
                                        where FORMAT(pl.picking_list_create_tgl, 'yyyy-MM-dd') = '$date_create' AND pl.depo_id = '$depo_id' " . $no_pick . " " . $no_batch . " " . $layanan . " " . $pengiriman . " " . $picking_list . " " . $stts . " " . $are . " AND po.picking_order_id is not null
                                        group by pl.picking_list_id");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetDataDetailPickingList($id)
	{
		$query = $this->db->select("pld.picking_list_detail_id as id_pld,
                                    do.delivery_order_id as id,
                                    dob.delivery_order_batch_id as DoBatchBatcId,
                                    FORMAT(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') as tgl_buat,
                                    do.delivery_order_kode as kode,
                                    do.delivery_order_kirim_nama as nama_customer,
                                    do.delivery_order_kirim_alamat as alamat_customer,
                                    do.delivery_order_kirim_telp as telp_customer,
                                    do.delivery_order_tipe_pembayaran as tipe_pembayaran,
                                    do.delivery_order_tipe_layanan as tipe_layanan,
                                    do.delivery_order_prioritas_stock as prioritas,
                                    do.delivery_order_status as status,
                                    tdo.tipe_delivery_order_alias as tipe")
			->from("picking_list_detail as pld")
			->join("delivery_order as do", "pld.delivery_order_id = do.delivery_order_id", "left")
			->join("delivery_order_batch as dob", "do.delivery_order_batch_id = dob.delivery_order_batch_id", "left")
			->join("tipe_delivery_order as tdo", "do.tipe_delivery_order_id = tdo.tipe_delivery_order_id", "left")
			->where("pld.picking_list_id", $id)
			->where("tdo.tipe_delivery_order_nama", 'Standar')
			->order_by("do.delivery_order_prioritas_stock", "ASC")
			// ->where("do.delivery_order_status", "pick up item confirmed")
			// ->order_by("do.delivery_order_prioritas_stock", "asc")
			->get()->result_array();

		return $query;
	}

	public function GetDataCetakPacking($id)
	{
		$query = $this->db->query("select 
                                        ph.delivery_order_id as do_id,
                                        max(FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd')) as tgl_do,
                                        max(do.delivery_order_kode) as kode_do,
                                        max(do.delivery_order_kirim_nama) as customer,
                                        max(do.delivery_order_kirim_alamat) as alamat_customer,
                                        max(do.delivery_order_kirim_telp) as telp_customer,
                                        max(do.delivery_order_tipe_pembayaran) as tipe_pembayaran,
                                        max(do.delivery_order_tipe_layanan) as tipe_layanan,
                                        max(do.delivery_order_prioritas_stock) as prioritas,
                                        max(ph.packing_h_who_cetak) as nama_pencetak,
                                        max(FORMAT(ph.packing_h_tgl_cetak, 'yyyy-MM-dd')) as tgl_cetak,
                                        max(ph.packing_h_count_cetak) as jml_cetak,
                                        max(ph.packing_h_approved) as approve,
                                        max(area.area_nama) as area,
                                        COUNT(ph.delivery_order_id) as koli
                                    from picking_list pl
                                        inner join picking_list_detail pld
                                            on pl.picking_list_id =  pld.picking_list_id
                                        inner join delivery_order do 
                                            on pld.delivery_order_id = do.delivery_order_id
                                        inner join delivery_order_batch dob 
                                            on do.delivery_order_batch_id = dob.delivery_order_batch_id
                                        inner join packing_h ph 
                                            on do.delivery_order_id = ph.delivery_order_id
                                        inner join area 
                                            on pl.area_id = area.area_id
                                    where pld.picking_list_id = '$id'
                                    group by ph.delivery_order_id");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function CetakLabel($data)
	{
		$query = $this->db->select("do.delivery_order_id as do_id,
                                    do.delivery_order_kode as do_kode,
                                    ph.packing_h_keterangan as intruksi,
                                    do.delivery_order_tipe_layanan as tipe_layanan,
                                    FORMAT(do.delivery_order_tgl_buat_do, 'yyyy-MM-dd') as tgl_do,
                                    do.delivery_order_tipe_pembayaran as tipe_pembayaran,
                                    do.delivery_order_kirim_nama as nama_customer,
                                    do.delivery_order_kirim_alamat as alamat_customer,
                                    do.delivery_order_kirim_telp as telp_customer,
                                    ph.packing_h_weight_total as berat,
                                    ph.packing_h_id as ph_id,
                                    depo.depo_nama as nama_pengirim,
                                    depo.depo_alamat as alamat_pengirim,
                                    depo.depo_no_telp as telp_pengirim,
                                    tp.tipe_pengiriman_nama_tipe as tipe_pengiriman,
                                    ROW_NUMBER() OVER ( PARTITION BY do.delivery_order_id ORDER BY do.delivery_order_id ) as koli")
			->from("delivery_order do")
			->join("packing_h ph", "do.delivery_order_id = ph.delivery_order_id", "left")
			->join("delivery_order_batch dob", "do.delivery_order_batch_id = dob.delivery_order_batch_id", "left")
			->join("tipe_pengiriman tp", "dob.tipe_pengiriman_id = tp.tipe_pengiriman_id", "left")
			->join("depo", "do.depo_id = depo.depo_id", "left")
			->where_in("do.delivery_order_id", $data)->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}
		return $query;
	}

	public function CetakLabelDetail($data)
	{
		$query = $this->db->select("ph.packing_h_id as ph_id,
                                    sku.sku_nama_produk as nama_sku,
                                    pd.packing_d_qty as qty")
			->from("packing_h ph")
			->join("packing_d pd", "ph.packing_h_id = pd.packing_h_id", "left")
			->join("sku", "pd.sku_id = sku.sku_id", "left")
			->where_in("pd.packing_h_id", $data)->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}
		return $query;
	}

	public function getJmlKoli($data)
	{
		$query = $this->db->select("do.delivery_order_id as do_id,
                                    COUNT(ph.delivery_order_id) as jml_koli")
			->from("delivery_order do")
			->join("packing_h ph", "do.delivery_order_id = ph.delivery_order_id", "left")
			->where_in("do.delivery_order_id", $data)
			->group_by("do.delivery_order_id")
			->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}
		return $query;
	}

	public function GetDataDetailPackingDO($id)
	{
		$query = $this->db->select("pld2.picking_list_detail_2_id as id_pld2,
                                    pld2.picking_list_detail_id as id_pld,
                                    pld2.sku_qty_order as qty,
                                    sku.sku_id as sku_id,
                                    sku.sku_kode as kode,
                                    sku.sku_satuan as satuan,
                                    sku.sku_kemasan as kemasan,
                                    isnull(sku.sku_weight_product, 0) as berat,
                                    isnull(sku.sku_volume, 0) as volume,
                                    sku.sku_nama_produk as nama_produk,
                                    FORMAT(do.delivery_order_tgl_expired_do, 'yyyy-MM-dd') as ed_do")
			->from("picking_List_detail_2 as pld2")
			->join("delivery_order do", "pld2.delivery_order_id = do.delivery_order_id", "inner")
			->join("sku", "pld2.sku_id = sku.sku_id", "inner")
			->where("pld2.picking_list_detail_id", $id)
			->order_by("sku.sku_kode", "ASC")
			->get()->result_array();
		return $query;
	}

	public function UpdateCetakLabeToPackingH($data)
	{
		$ph_id = implode(",", $data);
		$username = $this->session->userdata('pengguna_username');

		foreach ($data as $key => $val) {
			$getCountCetak = $this->db->select("packing_h_id as ph_id, packing_h_count_cetak as count_cetak")
				->from("packing_h")
				->where_in("packing_h_id", $val)->get()->result();
			foreach ($getCountCetak as $value) {
				if ($value->ph_id == $val) {
					if ($value->count_cetak != null) {
						$count = $value->count_cetak + 1;
						$query = $this->db->query("UPDATE packing_h SET packing_h_who_cetak = '$username', packing_h_tgl_cetak = GETDATE(), packing_h_count_cetak = $count, packing_h_approved = 1 WHERE packing_h_id = '$val'");
					} else {
						$count = 1;
						$query = $this->db->query("UPDATE packing_h SET packing_h_who_cetak = '$username', packing_h_tgl_cetak = GETDATE(), packing_h_count_cetak = $count, packing_h_approved = 1 WHERE packing_h_id = '$val'");
					}
				}
			}
		}
		return $query;
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function InsertToPackingH($_1, $_2, $_3, $_4, $_5, $_6, $_7, $_8, $_9)
	{
		$this->db->set("packing_h_id", $_1);
		$this->db->set("delivery_order_id", $_2);
		$this->db->set("packing_h_kode", $_3);
		$this->db->set("packing_h_tgl", $_4);
		$this->db->set("packing_h_weight_total", $_5);
		$this->db->set("packing_h_weight_total_unit", "GR");
		$this->db->set("packing_h_volume_total", $_6);
		$this->db->set("packing_h_volume_total_unit", "cm3");
		$this->db->set("packing_h_keterangan", $_7);
		$this->db->set("karyawan_id", $_8);
		$this->db->set("karyawan_nama", $_9);
		$this->db->set("packing_h_approved", 0);

		$queryinsert = $this->db->insert("packing_h");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function InsertToPackingD($packing_h_id, $row)
	{
		$this->db->set("packing_d_id", "NewID()", FALSE);
		$this->db->set("packing_h_id", $packing_h_id);
		$this->db->set("sku_id", $row['sku_id']);
		$this->db->set("packing_d_weight", $row['berat_tot']);
		$this->db->set("packing_d_weight_unit", "GR");
		$this->db->set("packing_d_volume", $row['volume_tot']);
		$this->db->set("packing_d_volume_unit", "cm3");
		$this->db->set("packing_d_qty", $row['qty_packed']);

		$queryinsert = $this->db->insert("packing_d");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function UpdateStatus3Table($do_id, $id_pld, $DoBatchId)
	{
		$this->db->trans_start();

		$getStatusProgress = $this->db->select("status_progress_id, status_progress_nama")->from("status_progress")->where("status_progress_nama", "packing item confirmed")->get()->row();

		//update table do
		$this->db->set('delivery_order_status', $getStatusProgress->status_progress_nama);
		$this->db->where('delivery_order_id', $do_id);
		$this->db->update('delivery_order');

		//insert table do_progress
		$this->db->set("delivery_order_progress_id", "NewID()", FALSE);
		$this->db->set("delivery_order_id", $do_id);
		$this->db->set("status_progress_id", $getStatusProgress->status_progress_id);
		$this->db->set('status_progress_nama', $getStatusProgress->status_progress_nama);
		$this->db->set("delivery_order_progress_create_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_progress_create_tgl", "GetDate()", FALSE);
		$this->db->insert("delivery_order_progress");

		$getStatusDO = $this->db->select("COUNT(delivery_order_batch_id) as DoBacth,
                                        COUNT(case 
                                                when delivery_order_status = 'packing item confirmed' then delivery_order_batch_id 
                                                else null 
                                            end) as status")
			->from("delivery_order")->where("delivery_order_batch_id", $DoBatchId)->get()->result();

		// //update table do batch
		if ($getStatusDO[0]->DoBacth === $getStatusDO[0]->status) {
			$sql = "UPDATE dob
                SET dob.delivery_order_batch_status = '$getStatusProgress->status_progress_nama'
                FROM delivery_order_batch dob
                INNER JOIN picking_list_detail pld
                    ON dob.picking_list_id = pld.picking_list_id 
                INNER JOIN delivery_order do
                    ON pld.delivery_order_id = do.delivery_order_id
                WHERE pld.picking_list_detail_id = '$id_pld'
                    AND do.delivery_order_id = '$do_id'";
			$this->db->query($sql);
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			# Something went wrong.
			return 0;
		}


		return 1;

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {

		//     $queryinsert = 1;
		// } else {
		//     $queryinsert = 0;
		// }

		// return $queryinsert;
	}

	public function getListNoPickingList()
	{
		return $this->db->select("pl.picking_list_kode")
			->from("picking_list pl")
			->join("picking_list_detail pld", "pl.picking_list_id = pld.picking_list_id", "inner")
			->join("delivery_order do", "pld.delivery_order_id = do.delivery_order_id", "inner")
			->join("tipe_delivery_order tdo", "do.tipe_delivery_order_id = tdo.tipe_delivery_order_id", "inner")
			->where("tdo.tipe_delivery_order_nama", "Standar")
			->where('pl.depo_id', $this->session->userdata('depo_id'))
			->distinct()
			->order_by("pl.picking_list_kode", "ASC")
			->get()->result();
	}

	public function getListDoBatch()
	{
		$listDoBatch = $this->db->select("*")->from('delivery_order_batch')->where('depo_id', $this->session->userdata('depo_id'))->get();
		return $listDoBatch->result_array();
	}

	public function getListStatusDoBatch()
	{
		$status = $this->db->select("delivery_order_batch_status")->from("delivery_order_batch")->distinct()->order_by("delivery_order_batch_status", "ASC")->get()->result_array();

		return $status;
	}

	public function getListLayanan()
	{
		$dataListLayanan = $this->db->select("*")->from('tipe_layanan')->order_by("tipe_layanan_nama", "ASC")->get();
		return $dataListLayanan->result_array();
	}

	public function getListPengiriman()
	{
		$dataListArmada = $this->db->select("*")->from('tipe_pengiriman')->get();
		return $dataListArmada->result_array();
	}

	public function getArea()
	{
		$dataListArmada = $this->db->select("*")->from('area')->order_by('area_nama')->get();
		return $dataListArmada->result_array();
	}

	public function getKaryawan()
	{
		$dataListKaryawan = $this->db->select("k.karyawan_id, k.karyawan_nama")
			->from('karyawan as k')
			->join("karyawan_level as kl", "k.karyawan_level_id = kl.karyawan_level_id", "inner")
			->where("kl.karyawan_level_kode", "PCK")
			->where("kl.karyawan_level_nama", "Packer")
			->where("k.depo_id", $this->session->userdata("depo_id"))
			->order_by('k.karyawan_nama')
			->get()->result();
		return $dataListKaryawan;
	}

	public function GetDataDetailPackingDOWhenStatusConfirmed($id_pl, $id_pld)
	{
		$data = $this->db->select("pld.picking_list_detail_id as id_pld,
                                    do.delivery_order_id as id_do,
                                    FORMAT(do.delivery_order_tgl_expired_do, 'yyyy-MM-dd') as ed_do,
                                    pld2.sku_qty_order as qty,
                                    sku.sku_kode as kode,
                                    sku.sku_satuan as satuan,
                                    sku.sku_kemasan as kemasan,
                                    sku.sku_nama_produk as nama_produk")
			->from('picking_list as pl')
			->join('picking_list_detail as pld', 'pl.picking_list_id = pld.picking_list_id', 'inner')
			->join('delivery_order as do', 'pld.delivery_order_id = do.delivery_order_id', 'inner')
			->join('picking_list_detail_2 as pld2', 'pld.picking_list_detail_id = pld2.picking_list_detail_id', 'inner')
			->join('sku', 'pld2.sku_id = sku.sku_id', 'inner')
			->where('pld.picking_list_id', $id_pl)
			->where('pld2.picking_list_detail_id', $id_pld)
			->get()->result_array();

		return $data;
	}

	public function GetDataDetailIntablePackingStatusConfirmed($do_id)
	{
		$data = $this->db->select("ph.karyawan_nama as oleh,
                                    ph.packing_h_kode as kode,
                                    FORMAT(ph.packing_h_tgl, 'dd-MM-yyyy') as tgl_packing,
                                    pd.packing_d_weight as berat,
                                    pd.packing_d_volume as volume,
                                    pd.packing_d_qty as qty,
                                    sku.sku_kode as sku_kode,
		                            sku.sku_nama_produk as sku_produk")
			->from("delivery_order as do")
			->join("packing_h as ph", "do.delivery_order_id = ph.delivery_order_id", "inner")
			->join("packing_d as pd", "ph.packing_h_id = pd.packing_h_id", "inner")
			->join("sku", "pd.sku_id = sku.sku_id", "inner")
			->where("ph.delivery_order_id", $do_id)
			->order_by("ph.packing_h_tgl", "ASC")
			->get()->result_array();
		return $data;
	}
}
