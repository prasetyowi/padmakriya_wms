<?php
class M_MainDashboard extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	public function getStatusDO($DepoID, $tgl1, $tgl2)
	{
		$query = $this->db->query("
		SELECT 'In Progress' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = '$DepoID'
		AND delivery_order_status = 'in progress'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Progress Item Request' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in progress item request'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Progress Pick Up Item' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in progress pick up item'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Pick Up Item Confirmed' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'pick up item confirmed'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Progress Packing Item' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in progress packing item'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Packing Item Confirmed' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'packing item confirmed'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Transit Validation' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in transit validation'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Transit Validation Completed' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in transit validation completed'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'In Transit' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'in transit'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Delivered' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'delivered'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Partially Delivered' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'partially delivered'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Not Delivered' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'not delivered'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'
		UNION
		SELECT 'Canceled' as delivery_order_status, count(delivery_order_status) as jumlah
		FROM delivery_order
		WHERE depo_id = 'A9DD73D2-B2EA-469E-B9CA-3196EDC1DF26'
		AND delivery_order_status = 'canceled'
		AND format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd') = '$tgl1'");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	// public function GetCountInProgress($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as inprogress");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where("depo_id", $DepoID);
	// 		$this->db->where("delivery_order_status", "in progress");
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as inprogress");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'in progress');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountInProgressItemRequest($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as inprogressitemrequest");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where("depo_id", $DepoID);
	// 		$this->db->where("delivery_order_status", "in progress item request");
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as inprogressitemrequest");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'in progress item request');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountInProgressPickUpItem($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as inprogresspickupitem");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where("depo_id", $DepoID);
	// 		$this->db->where("delivery_order_status", "in progress pick up item");
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as inprogresspickupitem");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'in progress pick up item');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountInProgressPackingItem($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as inprogresspackingitem");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where("depo_id", $DepoID);
	// 		$this->db->where("delivery_order_status", "in progress packing item");
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as inprogresspackingitem");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'in progress packing item');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountPickUpItemConfirmed($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as pickupitemconfirmed");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'pick up item confirmed');
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as pickupitemconfirmed");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'pick up item confirmed');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountPackingItemConfirmed($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as packingitemconfirmed");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'packing item confirmed');
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as packingitemconfirmed");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'packing item confirmed');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountInTransitValidation($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as intransitvalidation");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'in transit validation');
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as intransitvalidation");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'in transit validation');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountInTransitValidationCompleted($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as intransitvalidationcompleted");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'in transit validation completed');
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as intransitvalidationcompleted");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'in transit validation completed');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountInTransit($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as intransit");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'in transit');
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as intransit");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'in transit');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountDelivered($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as delivered");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'delivered');
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as delivered");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'delivered');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountNotDelivered($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as notdelivered");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'not delivered');
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as notdelivered");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'not delivered');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountCanceled($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as canceled");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'canceled');
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as canceled");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'canceled');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	// public function GetCountPartiallyDelivered($DepoID, $tgl1, $tgl2, $mode)
	// {
	// 	if ($mode == 0) {
	// 		$query = $this->db->select("count(delivery_order_status) as partiallydelivered");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'partially delivered');
	// 		$this->db->where("format(delivery_order_tgl_aktual_kirim,'yyyy-MM-dd')", $tgl1);
	// 	} else {
	// 		$query = $this->db->select("count(delivery_order_status) as partiallydelivered");
	// 		$this->db->from("delivery_order");
	// 		$this->db->where('depo_id', $DepoID);
	// 		$this->db->where('delivery_order_status', 'partially delivered');
	// 		$this->db->where("FORMAT(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') BETWEEN '$tgl1' and '$tgl2'");
	// 	}

	// 	$query = $this->db->get();
	// 	if ($query->num_rows() == 0) {
	// 		return 0;
	// 	} else {
	// 		return $query->result_array();
	// 	}
	// }

	public function GetListDOByStatus($status, $tgl1, $tgl2)
	{
		$query = $this->db->select('delivery_order_id as do_id, delivery_order_tgl_aktual_kirim as tgl, delivery_order_kode as kode, delivery_order_kirim_nama as nama, delivery_order_kirim_alamat as alamat');
		$this->db->from('delivery_order');
		$this->db->where('delivery_order_status', $status);
		$this->db->where("format(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') >=", $tgl1);
		$this->db->where("format(delivery_order_tgl_aktual_kirim, 'yyyy-MM-dd') <=", $tgl2);
		$this->db->order_by('tgl', 'ASC');

		$query = $this->db->get();
		if ($query->num_rows() == 0) {
			return 0;
		} else {
			return $query->result_array();
		}
	}

	public function generateCardDashboard($tgl1 = null, $tgl2 = null, $mode = null, $type = null)
	{

		$datePickingOrder = "";
		$dateMutasiPallet = "";
		$dateKoreksiBarang = "";
		$datePemusnahan = "";
		$datePermintaanBarang = "";
		$datePengemasanBarang = "";
		$dateKoreksiPallet = "";
		$dateStockOpname = "";
		$orderBy = "";

		if ($mode != null) {
			if ($mode == 0) {
				$datePickingOrder .= "AND FORMAT(po.picking_order_tanggal,'yyyy-MM-dd') = '$tgl1'";
			} else {
				$datePickingOrder .= "AND FORMAT(po.picking_order_tanggal,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$dateMutasiPallet .= "AND FORMAT(tmpd.tr_mutasi_pallet_draft_tgl_create,'yyyy-MM-dd') = '$tgl1'";
			} else {
				$dateMutasiPallet .= "AND FORMAT(tmpd.tr_mutasi_pallet_draft_tgl_create,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$dateKoreksiBarang .= "AND FORMAT(a.tr_koreksi_stok_draft_tgl_create,'yyyy-MM-dd') = '$tgl1'";
			} else {
				$dateKoreksiBarang .= "AND FORMAT(a.tr_koreksi_stok_draft_tgl_create,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$datePemusnahan .= "AND FORMAT(a.tr_koreksi_stok_draft_tgl_create,'yyyy-MM-dd') = '$tgl1'";
			} else {
				$datePemusnahan .= "AND FORMAT(a.tr_koreksi_stok_draft_tgl_create,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$datePermintaanBarang .= "AND FORMAT(picking_list_create_tgl,'yyyy-MM-dd') = '$tgl1'";
			} else {
				$datePermintaanBarang .= "AND FORMAT(picking_list_create_tgl,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$datePengemasanBarang .= "AND FORMAT(pl.picking_list_create_tgl,'yyyy-MM-dd') = '$tgl1'";
			} else {
				$datePengemasanBarang .= "AND FORMAT(pl.picking_list_create_tgl,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$dateKoreksiPallet .= "AND FORMAT(tksp.tr_koreksi_stok_pallet_tgl_create,'yyyy-MM-dd') = '$tgl1'";
			} else {
				$dateKoreksiPallet .= "AND FORMAT(tksp.tr_koreksi_stok_pallet_tgl_create,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'";
			}

			if ($mode == 0) {
				$dateStockOpname .= "AND FORMAT(tpo.tr_opname_plan_tgl_create,'yyyy-MM-dd') = '$tgl1'";
			} else {
				$dateStockOpname .= "AND FORMAT(tpo.tr_opname_plan_tgl_create,'yyyy-MM-dd') BETWEEN '$tgl1' AND '$tgl2'";
			}
		} else if ($type == "opname") {
			if ($this->session->userdata('karyawan_id') == "AD030BE3-7D9E-4A66-B985-D6085F623DA2") {
				$datePickingOrder .= "";
				$dateMutasiPallet .= "";
				$dateKoreksiBarang .= "";
				$datePemusnahan .= "";
				$datePermintaanBarang .= "";
				$datePengemasanBarang .= "";
				$dateKoreksiPallet .= "";
				$dateStockOpname .= "";
			} else {
				$datePickingOrder .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "'";
				$dateMutasiPallet .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "'";
				$dateKoreksiBarang .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "'";
				$datePemusnahan .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "'";
				$datePermintaanBarang .= "";
				$datePengemasanBarang .= "";
				$dateKoreksiPallet .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "'";
				$dateStockOpname .= "AND k.karyawan_id = '" . $this->session->userdata('karyawan_id') . "' AND tpo.depo_id = '" . $this->session->userdata('depo_id') . "'";
			}


			$orderBy .= "order by a.urut DESC";
		} else {
			$datePickingOrder .= "AND FORMAT(po.picking_order_tanggal,'yyyy-MM-dd') = '$tgl1'";
			$dateMutasiPallet .= "AND FORMAT(tmpd.tr_mutasi_pallet_draft_tgl_create,'yyyy-MM-dd') = '$tgl1'";
			$dateKoreksiBarang .= "AND FORMAT(a.tr_koreksi_stok_draft_tgl_create,'yyyy-MM-dd') = '$tgl1'";
			$datePemusnahan .= "AND FORMAT(a.tr_koreksi_stok_draft_tgl_create,'yyyy-MM-dd') = '$tgl1'";
			$datePermintaanBarang .= "AND FORMAT(picking_list_create_tgl,'yyyy-MM-dd') = '$tgl1'";
			$datePengemasanBarang .= "AND FORMAT(pl.picking_list_create_tgl,'yyyy-MM-dd') = '$tgl1'";
			$dateKoreksiPallet .= "AND FORMAT(tksp.tr_koreksi_stok_pallet_tgl_create,'yyyy-MM-dd') = '$tgl1'";
			$dateStockOpname .= "AND FORMAT(tpo.tr_opname_plan_tgl_create,'yyyy-MM-dd') = '$tgl1'";

			$orderBy .= "order by a.urut ASC";
		}

		return $this->db->query("SELECT * FROM 
														(
															SELECT
																1 AS urut,
																'CAPTION-130005007' AS bahasa,
																'PengeluaranBarang' AS modul,
																'fas fa-box-open' AS icon,
																COUNT(pop.picking_order_id) AS jum,
																'WMS/Distribusi/PengeluaranBarang/PengeluaranBarangMenu' AS url
															FROM picking_order_plan pop
															LEFT JOIN picking_order po ON pop.picking_order_id = po.picking_order_id
															LEFT JOIN karyawan k ON pop.karyawan_id = k.karyawan_id
															WHERE pop.picking_order_plan_status IN('Open','In Progress') $datePickingOrder

															UNION

															SELECT
																2 AS urut,
																'CAPTION-MUTASIPALLET' AS bahasa,
																'MutasiPallet' AS modul,
																'fas fa-boxes' AS icon,
																COUNT(tmpd.tr_mutasi_pallet_draft_id) AS jum,
																'WMS/MutasiPallet/MutasiPalletMenu' AS url
															FROM tr_mutasi_pallet_draft tmpd
															LEFT JOIN karyawan k  ON tmpd.tr_mutasi_pallet_draft_nama_checker = k.karyawan_nama
															WHERE tmpd.tr_mutasi_pallet_draft_status IN('Approved','In Progress') $dateMutasiPallet

															UNION

															SELECT
																3 AS urut,
																'CAPTION-120009000' AS bahasa,
																'KoreksiBarang' AS modul,
																'fas fa-people-carry' AS icon,
																COUNT(a.tr_koreksi_stok_draft_id) AS jum,
																'WMS/KoreksiStokBarang/KoreksiStokBarangMenu' AS url
															FROM  tr_koreksi_stok_draft a
															LEFT JOIN tipe_mutasi tm ON a.tipe_mutasi_id = tm.tipe_mutasi_id
															LEFT JOIN karyawan k ON a.tr_koreksi_stok_draft_nama_checker = k.karyawan_nama
															WHERE tm.tipe_mutasi_nama IN ('Koreksi Masuk', 'Koreksi Keluar')
																AND a.tr_koreksi_stok_draft_status IN('Approved','In Progress')
																$dateKoreksiBarang

															UNION

															SELECT
																4 AS urut,
																'CAPTION-120015000' AS bahasa,
																'Pemusnahan' AS modul,
																'fas fa-dolly' AS icon,
																COUNT(a.tr_koreksi_stok_draft_id) AS jum,
																'WMS/KoreksiStokBarang/KoreksiStokBarangMenu' AS url
															FROM tr_koreksi_stok_draft a
															LEFT JOIN tipe_mutasi tm ON a.tipe_mutasi_id = tm.tipe_mutasi_id 
															LEFT JOIN karyawan k ON a.tr_koreksi_stok_draft_nama_checker = k.karyawan_nama
															WHERE tm.tipe_mutasi_id IN ('9246A374-B798-442B-8EE0-631EE6ADA7C9', '86A1887B-28F2-4F81-86C1-84650B2F2FEC')
																AND a.tr_koreksi_stok_draft_status IN('Approved','In Progress')
																$datePemusnahan

															UNION

															SELECT
																5 AS urut,
																'CAPTION-130005006' AS bahasa,
																'PermintaanBarang' AS modul,
																'fa-solid fa-hands-holding-circle' AS icon,
																COUNT(picking_list_id) AS jum,
																'WMS/Distribusi/PermintaanBarang/PermintaanBarangMenu' AS url
															FROM picking_list
															WHERE picking_list_status IN('Open','In Progress') $datePermintaanBarang

															UNION

															SELECT
																6 AS urut,
																'CAPTION-130005009' AS bahasa,
																'PengemasanBarang' AS modul,
																'fa-solid fa-truck-ramp-box' AS icon,
																-- COUNT(pl.picking_list_id) AS jum,
																0 AS jum,
																'WMS/Distribusi/PengemasanBarang/PengemasanBarangMenu' AS url
															FROM picking_list pl
															LEFT JOIN delivery_order_batch as dob on pl.delivery_order_batch_id = dob.delivery_order_batch_id
															LEFT JOIN picking_order as po on dob.picking_order_id = po.picking_order_id
															WHERE pl.picking_list_status in('Open', 'In Process')
																AND po.picking_order_id is not null $datePengemasanBarang

															UNION

															SELECT
																7 AS urut,
																'CAPTION-120011000' AS bahasa,
																'KoreksiPallet' AS modul,
																'fa-solid fa-pallet' AS icon,
																COUNT(tksp.tr_koreksi_stok_pallet_id) AS jum,
																'WMS/KoreksiPallet/KoreksiPalletMenu' AS url
															FROM tr_koreksi_stok_pallet tksp
															LEFT JOIN karyawan k ON tksp.tr_koreksi_stok_pallet_nama_checker = k.karyawan_nama
															WHERE tksp.tr_koreksi_stok_pallet_status IN('Open','In Progress') $dateKoreksiPallet

															UNION

															SELECT
																8 AS urut,
																'CAPTION-123004000' AS bahasa,
																'StockOpname' AS modul,
																'fa-solid fa-magnifying-glass' AS icon,
																COUNT(tpo.tr_opname_plan_id) AS jum,
																'WMS/ProsesOpname/ProsesOpnameMenu' AS url
															FROM tr_opname_plan tpo
															LEFT JOIN karyawan k ON tpo.karyawan_id_penanggungjawab = k.karyawan_id
															WHERE tpo.tr_opname_plan_status IN('Approved', 'In Progress') $dateStockOpname
														) as a
														$orderBy")->result();
		// return $this->db->last_query();
	}

	public function getSKUNotInWMS()
	{
		return $this->db->query("SELECT DISTINCT principle_kode, sku_konversi_group, sku_nama_produk, datasource FROM
														(
																SELECT principle_kode as principle_kode, sku_konversi_group as sku_konversi_group, sku_nama_produk as sku_nama_produk, 'Bosnet' as datasource
																	FROM sku_import_temp
																	where sku_konversi_group not in (SELECT sku_konversi_group FROM sku)
																union
																SELECT b.principle_kode as principle_kode, a.sku_kode as sku_konversi_group, a.sku_nama_produk as sku_nama_produk, 'ASN' as datasource
																	FROM penerimaan_surat_jalan_temp a
														inner join principle b on a.principle_id = b.principle_id
																	where a.sku_kode not in (SELECT sku_konversi_group FROM sku)
																
														) as skubelumterdaftar
														order by principle_kode, sku_konversi_group")->result();
	}

	public function GetKonversiEditDetailById($id)
	{
		$query = $this->db->query("SELECT
                                    detail.tr_konversi_sku_detail_id,
                                    detail.tr_konversi_sku_id,
                                    sku.sku_id,
                                    sku.sku_kode,
                                    sku.sku_nama_produk,
                                    sku.principle_id,
                                    principle.principle_kode AS principle,
                                    sku.principle_brand_id,
                                    principle_brand.principle_brand_nama AS brand,
                                    sku.sku_kemasan,
                                    sku.sku_satuan,
                                    detail.sku_stock_id,
                                    detail_tmp.tr_konversi_sku_detail2_qty AS qty_aktual,
                                    FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') AS sku_stock_expired_date,
                                    ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0) AS tr_konversi_sku_detail_qty_plan,
                                    SUM(ISNULL(detail2.tr_konversi_sku_detail2_qty_result, 0)) AS tr_konversi_sku_detail2_qty_result
                                    FROM tr_konversi_sku_detail detail
                                    LEFT JOIN tr_konversi_sku_detail2_temp detail_tmp
                                    ON detail.tr_konversi_sku_detail_id = detail_tmp.tr_konversi_sku_detail_id
                                    LEFT JOIN tr_konversi_sku_detail2 detail2
                                    ON detail.tr_konversi_sku_detail_id = detail2.tr_konversi_sku_detail_id
                                    LEFT JOIN sku
                                    ON sku.sku_id = detail.sku_id
                                    LEFT JOIN principle
                                    ON principle.principle_id = sku.principle_id
                                    LEFT JOIN principle_brand
                                    ON principle_brand.principle_brand_id = sku.principle_brand_id
                                    WHERE detail.tr_konversi_sku_id = '$id'
                                    GROUP BY detail.tr_konversi_sku_detail_id,
                                            detail.tr_konversi_sku_id,
                                            sku.sku_id,
                                            sku.sku_kode,
                                            sku.sku_nama_produk,
                                            sku.principle_id,
                                            principle.principle_kode,
                                            sku.principle_brand_id,
                                            principle_brand.principle_brand_nama,
                                            sku.sku_kemasan,
                                            sku.sku_satuan,
                                            detail.sku_stock_id,
                                            detail_tmp.tr_konversi_sku_detail2_qty,
                                            FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd'),
                                            ISNULL(detail.tr_konversi_sku_detail_qty_plan, 0)
                                    ORDER BY sku.sku_kode, FORMAT(detail.sku_stock_expired_date, 'yyyy-MM-dd') ASC");

		if ($query->num_rows() == 0) {
			$query = [];
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}
}
