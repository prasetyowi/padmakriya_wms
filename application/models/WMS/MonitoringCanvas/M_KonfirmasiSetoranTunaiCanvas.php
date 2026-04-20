<?php

class M_KonfirmasiSetoranTunaiCanvas extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function Get_tipe_pembayaran()
	{
		$query = $this->db->query("SELECT * FROM tipe_pembayaran ORDER BY tipe_pembayaran_id ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_list_delivery_order($delivery_order_batch_id, $data)
	{
		$union = " UNION ALL ";
		$table_sementara = "";

		if ($data != NULL) {
			foreach ($data as $key => $value) {
				$table_sementara .= "SELECT '" . $value['delivery_order_id'] . "' AS delivery_order_id, '" . $value['delivery_order_reff_no'] . "' AS delivery_order_reff_no, '" . $value['tipe_pembayaran_id'] . "' AS tipe_pembayaran_id";
				if ($key < count($data) - 1) {
					$table_sementara .= $union;
				}
			}
		} else {
			$table_sementara .= "SELECT NULL AS delivery_order_id, NULL AS delivery_order_reff_no, NULL AS tipe_pembayaran_id";
		}

		$query = $this->db->query("SELECT 
									do.delivery_order_id,
									do.delivery_order_kode,
									do.sales_order_id,
									ISNULL(so.sales_order_kode,'') AS sales_order_kode,
									ISNULL(so.sales_order_no_po,'') AS sales_order_no_po,
									do.client_pt_id,
									client_pt.client_pt_nama,
									ISNULL(do.delivery_order_tipe_pembayaran,'0') AS delivery_order_tipe_pembayaran,
									tipe_pembayaran.tipe_pembayaran_nama,
									do_pembayaran.tipe_pembayaran_id,
									do_pembayaran.delivery_order_reff_no,
									ISNULL(do_pembayaran.delivery_order_id,'0') AS checked
									FROM delivery_order_canvas do
									LEFT JOIN FAS.dbo.sales_order so
									ON so.sales_order_id = do.sales_order_id
									LEFT JOIN (" . $table_sementara . ") do_pembayaran
									ON do_pembayaran.delivery_order_id = do.delivery_order_id
									LEFT JOIN client_pt
									ON client_pt.client_pt_id = do.client_pt_id
									LEFT JOIN tipe_pembayaran
									ON tipe_pembayaran.tipe_pembayaran_id = ISNULL(do.delivery_order_tipe_pembayaran,'0')
									WHERE do.delivery_order_batch_id = '$delivery_order_batch_id' 
									ORDER BY format(delivery_order_tgl_buat_do, 'yyyy-MM-dd') ASC");

		if ($query->num_rows() == 0) {
			$query = array();
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function Get_Area($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
									area.*
									FROM delivery_order_area
									LEFT JOIN area
									ON area.area_id = delivery_order_area.area_id
									WHERE delivery_order_area.delivery_order_batch_id = '$delivery_order_batch_id'
									ORDER BY area.area_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_LastUpdateDOBatch($delivery_order_batch_id)
	{
		$query = $this->db->query("SELECT
								delivery_order_batch_update_tgl AS tglUpd
								FROM delivery_order_batch
								WHERE delivery_order_batch_id = '$delivery_order_batch_id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function Get_Driver()
	{
		$query = $this->db->query("SELECT * FROM karyawan WHERE karyawan_level_id = '339d8ac2-c6ce-4b47-9bfc-e372592af521' AND depo_id = '" . $this->session->userdata('depo_id') . "' ORDER BY karyawan_nama ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_StatusFDJR()
	{
		$query = $this->db->query("SELECT delivery_order_batch_status FROM delivery_order_batch WHERE depo_id = '" . $this->session->userdata('depo_id') . "' GROUP BY delivery_order_batch_status ORDER BY delivery_order_batch_status ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_SuratTugasPengiriman($Tgl_FDJR, $Tgl_FDJR2, $No_FDJR, $karyawan_id, $Status_FDJR)
	{

		if ($No_FDJR == "") {
			$No_FDJR = "";
		} else {
			$No_FDJR = " AND delivery_order_batch.delivery_order_batch_kode = '$No_FDJR' ";
		}
		if ($karyawan_id == "") {
			$karyawan_id = "";
		} else {
			$karyawan_id = " AND delivery_order_batch.karyawan_id = '$karyawan_id' ";
		}
		if ($Status_FDJR == "") {
			$Status_FDJR = "";
		} else {
			$Status_FDJR = " AND delivery_order_batch.delivery_order_batch_status = '$Status_FDJR' ";
		}

		$query = $this->db->query("SELECT
										delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										picking_list.picking_list_id,
										ISNULL(picking_list.picking_list_kode, '') AS picking_list_kode,
										picking_order.picking_order_id,
										ISNULL(picking_order.picking_order_kode, '') AS picking_order_kode,
										FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim,
										delivery_order_batch.delivery_order_batch_status,
										delivery_order_batch.karyawan_id,
										driver.karyawan_nama,
										serah_terima_kirim.serah_terima_kirim_id,
										ISNULL(serah_terima_kirim.serah_terima_kirim_kode, '') AS serah_terima_kirim_kode,
										delivery_order_batch.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										CASE WHEN delivery_order_settlement.delivery_order_id IS NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement has not Started' WHEN delivery_order_settlement.delivery_order_id IS NOT NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement Success' ELSE '' END AS settlement_status
									FROM delivery_order_batch
									LEFT JOIN delivery_order ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN picking_list ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
									LEFT JOIN picking_order ON picking_list.picking_list_id = picking_order.picking_list_id
									LEFT JOIN serah_terima_kirim ON serah_terima_kirim.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id AND serah_terima_kirim.picking_order_id = picking_order.picking_order_id
									LEFT JOIN delivery_order_settlement ON delivery_order_settlement.delivery_order_id = delivery_order.delivery_order_id
									LEFT JOIN tipe_delivery_order ON delivery_order_batch.tipe_delivery_order_id = tipe_delivery_order.tipe_delivery_order_id
									LEFT JOIN karyawan driver ON delivery_order_batch.karyawan_id = driver.karyawan_id
									WHERE FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim,'yyyy-MM-dd') BETWEEN '$Tgl_FDJR' AND '$Tgl_FDJR2' AND delivery_order_batch.depo_id = '" . $this->session->userdata('depo_id') . "' " . $No_FDJR . " " . $karyawan_id . " " . $Status_FDJR . " 
									AND tipe_delivery_order.tipe_delivery_order_alias = 'Canvas'
									GROUP BY delivery_order_batch.delivery_order_batch_id,
										delivery_order_batch.delivery_order_batch_kode,
										picking_list.picking_list_id,
										ISNULL(picking_list.picking_list_kode, ''),
										picking_order.picking_order_id,
										ISNULL(picking_order.picking_order_kode, ''),
										FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy'),
										delivery_order_batch.delivery_order_batch_status,
										delivery_order_batch.karyawan_id,
										driver.karyawan_nama,
										serah_terima_kirim.serah_terima_kirim_id,
										ISNULL(serah_terima_kirim.serah_terima_kirim_kode, ''),
										delivery_order_batch.tipe_delivery_order_id,
										tipe_delivery_order.tipe_delivery_order_alias,
										CASE WHEN delivery_order_settlement.delivery_order_id IS NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement has not Started' WHEN delivery_order_settlement.delivery_order_id IS NOT NULL AND delivery_order_batch.delivery_order_batch_status = 'completed' THEN 'Settlement Success' ELSE '' END
									ORDER BY delivery_order_batch.delivery_order_batch_kode ASC
									");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		// return $this->db->last_query();
		return $query;
	}

	public function GetDeliveryOrderBatchByID($id)
	{
		$query = $this->db->query("SELECT 
		dob.delivery_order_batch_id,
		dob.delivery_order_batch_kode,
		format(dob.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') as delivery_order_batch_tanggal_kirim,
		dob.delivery_order_batch_status,
		dob.delivery_order_batch_nominal_tunai,
		area.area_nama,
		karyawan.karyawan_nama,
		kendaraan.kendaraan_model,
		tdo.tipe_delivery_order_alias,
		te.tipe_ekspedisi_nama
		FROM delivery_order_batch dob
		LEFT JOIN tipe_delivery_order tdo ON dob.tipe_delivery_order_id = tdo.tipe_delivery_order_id
		LEFT JOIN area ON dob.area_id = area.area_id
		LEFT JOIN tipe_ekspedisi te ON dob.tipe_ekspedisi_id = te.tipe_ekspedisi_id
		LEFT JOIN karyawan ON dob.karyawan_id = karyawan.karyawan_id
		LEFT JOIN kendaraan ON dob.kendaraan_id = kendaraan.kendaraan_id
		WHERE delivery_order_batch_id = '" . $id . "'
		");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetListDOByID($id)
	{
		$query = $this->db->query("SELECT 
										delivery_order_id, 
										format(delivery_order_tgl_buat_do, 'dd-MM-yyyy') as delivery_order_tgl_buat_do, 
										delivery_order_kode, 
										delivery_order_kirim_nama, 
										delivery_order_kirim_alamat, 
										ISNULL(tipe_pembayaran_nama, 'TUNAI') as delivery_order_tipe_pembayaran, 
										delivery_order_nominal_tunai,
										delivery_order_nominal_terima_tunai,
										ISNULL(delivery_order_jumlah_bayar, 0) as delivery_order_jumlah_bayar
									FROM delivery_order_canvas
									LEFT JOIN tipe_pembayaran ON delivery_order_canvas.delivery_order_tipe_pembayaran = tipe_pembayaran.tipe_pembayaran_id
									WHERE delivery_order_batch_id = '" . $id . "'
									ORDER BY format(delivery_order_tgl_buat_do, 'yyyy-MM-dd') ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetListDOTunaiByID($id)
	{
		$query = $this->db->query("SELECT SUM(delivery_order_nominal_tunai) AS delivery_order_batch_nominal_invoice FROM delivery_order
		WHERE delivery_order_batch_id = '" . $id . "' AND ISNULL(delivery_order_tipe_pembayaran,0) = '0' ");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row(0)->delivery_order_batch_nominal_invoice;
		}

		return $query;
	}

	public function GetListDOPembayaranByID($id)
	{
		$data = array();

		$query_header = $this->db->query("SELECT 
												delivery_order_payment_id,
												delivery_order_batch_id,
												tipe_pembayaran_id,
												delivery_order_payment_value,
												ISNULL(delivery_order_reff_no,'') delivery_order_reff_no,
												delivery_order_payment_tgl_jatuh_tempo,
												delivery_order_payment_tgl,
												delivery_order_payment_who 
											FROM delivery_order_payment
											WHERE delivery_order_batch_id = '$id' ORDER BY tipe_pembayaran_id ASC");

		if ($query_header->num_rows() == 0) {
			$data = array();
		} else {
			// $query = $query->result_array();

			foreach ($query_header->result_array() as $key => $value) {
				if ($value['delivery_order_payment_tgl_jatuh_tempo'] == '') {
					$delivery_order_payment_tgl_jatuh_tempo = '';
				} else {
					$delivery_order_payment_tgl_jatuh_tempo = date('Y-m-d', strtotime($value['delivery_order_payment_tgl_jatuh_tempo']));
				}

				$query_detail = $this->db->query("SELECT '" . $value['tipe_pembayaran_id'] . "' AS tipe_pembayaran_id, '" . $value['delivery_order_reff_no'] . "' AS delivery_order_reff_no, delivery_order_id FROM delivery_order_payment_detail WHERE delivery_order_payment_id = '" . $value['delivery_order_payment_id'] . "' ");

				array_push($data, array(
					'delivery_order_payment_id' => $value['delivery_order_payment_id'],
					'delivery_order_batch_id' => $value['delivery_order_batch_id'],
					'tipe_pembayaran_id' => $value['tipe_pembayaran_id'],
					'delivery_order_payment_value' => round($value['delivery_order_payment_value']),
					'delivery_order_reff_no' => $value['delivery_order_reff_no'],
					'delivery_order_payment_tgl_jatuh_tempo' => $delivery_order_payment_tgl_jatuh_tempo,
					'detail' => $query_detail->result_array()
				));
			}
		}

		return $data;
	}

	public function GetDODetail3($id)
	{
		$query = $this->db->query("SELECT
		dod3.delivery_order_detail3_id, 
		dod3.delivery_order_id, 
		tb.tipe_biaya_id, 
		tb.tipe_biaya_nama,
		dod3.delivery_order_detail3_nilai, 
  		ISNULL(dod3.delivery_order_detail3_keterangan, '') AS delivery_order_detail3_keterangan
		FROM delivery_order_detail3 AS dod3 
		JOIN tipe_biaya AS tb ON tb.tipe_biaya_id = dod3.tipe_biaya_id 
		WHERE dod3.delivery_order_id = '" . $id . "'
		");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function GetTipeBiaya()
	{
		$query = $this->db->query("SELECT 
		tipe_biaya_id, 
		tipe_biaya_nama 
		FROM tipe_biaya 
		WHERE tipe_biaya_is_aktif = 1");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function UpdateDO($delivery_order_id, $delivery_order_nominal_terima_tunai)
	{
		$this->db->set("delivery_order_nominal_terima_tunai", $delivery_order_nominal_terima_tunai);
		$this->db->where("delivery_order_id", $delivery_order_id);
		$queryupdate = $this->db->update("delivery_order");

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {
		// 	$queryupdate = 1;
		// } else {
		// 	$queryupdate = 0;
		// }

		return $queryupdate;
	}

	public function UpdateDOBatch($id_do_batch, $jumlahTerima, $delivery_order_batch_nominal_invoice, $delivery_order_batch_nominal_selisih)
	{
		$this->db->set("delivery_order_batch_nominal_tunai", $jumlahTerima);
		$this->db->set("delivery_order_batch_nominal_invoice", $delivery_order_batch_nominal_invoice);
		$this->db->set("delivery_order_batch_nominal_selisih", $delivery_order_batch_nominal_selisih);
		$this->db->set("delivery_order_batch_update_who", $this->session->userdata('pengguna_username'));
		$this->db->set("delivery_order_batch_update_tgl", 'GETDATE()', FALSE);
		$this->db->where("delivery_order_batch_id", $id_do_batch);
		$queryupdate = $this->db->update("delivery_order_batch");

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {
		// 	$queryupdate = 1;
		// } else {
		// 	$queryupdate = 0;
		// }

		return $queryupdate;
	}

	public function insertDODetail3($delivery_order_detail3_id, $delivery_order_id, $tipe_biaya_id, $delivery_order_detail3_nilai, $delivery_order_detail3_keterangan)
	{
		$delivery_order_detail3_keterangan_ = $delivery_order_detail3_keterangan == null ? null : $delivery_order_detail3_keterangan;

		$this->db->set("delivery_order_detail3_id", $delivery_order_detail3_id);
		$this->db->set("delivery_order_id", $delivery_order_id);
		$this->db->set("tipe_biaya_id", $tipe_biaya_id);
		$this->db->set("delivery_order_detail3_nilai", $delivery_order_detail3_nilai);
		$this->db->set("delivery_order_detail3_keterangan", $delivery_order_detail3_keterangan_);
		$queryinsert = $this->db->insert("delivery_order_detail3");

		// $affectedrows = $this->db->affected_rows();
		// if ($affectedrows > 0) {
		// 	$queryinsert = 1;
		// } else {
		// 	$queryinsert = 0;
		// }

		return $queryinsert;
	}

	public function deleteDODetail3($id)
	{
		$this->db->where('delivery_order_id', $id);
		return $this->db->delete('delivery_order_detail3');
	}

	public function Insert_delivery_order_payment($delivery_order_payment_id, $delivery_order_batch_id, $tipe_pembayaran_id, $delivery_order_payment_value, $delivery_order_reff_no, $delivery_order_payment_tgl_jatuh_tempo)
	{
		$delivery_order_batch_id = $delivery_order_batch_id == '' ? null : $delivery_order_batch_id;
		$delivery_order_reff_no = $delivery_order_reff_no == '' ? null : $delivery_order_reff_no;
		$delivery_order_payment_tgl_jatuh_tempo = $delivery_order_payment_tgl_jatuh_tempo == '' ? null :  $delivery_order_payment_tgl_jatuh_tempo;
		if ($delivery_order_payment_tgl_jatuh_tempo != '') {
			$this->db->set("delivery_order_payment_tgl_jatuh_tempo", date('Y-m-d', strtotime($delivery_order_payment_tgl_jatuh_tempo)));
		}

		$this->db->set("delivery_order_payment_id", $delivery_order_payment_id);
		$this->db->set("delivery_order_batch_id", $delivery_order_batch_id);
		$this->db->set("tipe_pembayaran_id", $tipe_pembayaran_id);
		$this->db->set("delivery_order_payment_value", $delivery_order_payment_value);
		$this->db->set("delivery_order_reff_no", $delivery_order_reff_no);
		// $this->db->set("delivery_order_payment_tgl_jatuh_tempo", $delivery_order_payment_tgl_jatuh_tempo);
		$this->db->set("delivery_order_payment_tgl", "GETDATE()", FALSE);
		$this->db->set("delivery_order_payment_who", $this->session->userdata('pengguna_username'));

		$queryinsert = $this->db->insert("delivery_order_payment");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Insert_delivery_order_payment_detail($delivery_order_payment_detail_id, $delivery_order_payment_id, $delivery_order_id)
	{
		$delivery_order_id = $delivery_order_id == '' ? null : $delivery_order_id;

		$this->db->set("delivery_order_payment_detail_id", $delivery_order_payment_detail_id);
		$this->db->set("delivery_order_payment_id", $delivery_order_payment_id);
		$this->db->set("delivery_order_id", $delivery_order_id);

		$queryinsert = $this->db->insert("delivery_order_payment_detail");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function delete_delivery_order_payment($id)
	{
		$querydelete = 0;

		$this->db->query("DELETE FROM delivery_order_payment_detail WHERE delivery_order_payment_id IN (SELECT delivery_order_payment_id FROM delivery_order_payment WHERE delivery_order_batch_id = '$id')");

		$this->db->query("DELETE FROM delivery_order_payment WHERE delivery_order_batch_id = '$id' ");


		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$querydelete = 1;
		} else {
			$querydelete = 0;
		}

		return $querydelete;
	}
}
