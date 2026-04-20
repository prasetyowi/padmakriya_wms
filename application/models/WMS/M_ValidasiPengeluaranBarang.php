<?php

date_default_timezone_set('Asia/Jakarta');

class M_ValidasiPengeluaranBarang extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->load->model('M_Vrbl');
	}

	public function getDepoPrefix($depo_id)
	{
		$result = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $result->row();
	}

	public function getPickingOrderKode($picking_order_id)
	{
		$result = $this->db->select("picking_order_kode, picking_order_tgl_update")->from('picking_order')->where('picking_order_id', $picking_order_id)->get();

		if ($result->num_rows() == 0) {
			$result = "";
		} else {
			$result = $result->row();
		}

		return $result;
	}

	public function getFlterDataPickingValidation($dataPost)
	{

		$tglQuery = explode(" - ", $dataPost->tanggal);
		$tglStart = date('Y-m-d', strtotime(str_replace("/", "-", $tglQuery[0])));
		$tglEnd = date('Y-m-d', strtotime(str_replace("/", "-", $tglQuery[1])));

		$whereKode = $dataPost->kode == "" ? "" : "AND picking_validation.picking_validation_id = '$dataPost->kode'";
		$whereStatus = $dataPost->status == "all" ? "" : "AND picking_validation.picking_validation_status = '$dataPost->status'";

		$dataFixed = [];
		$statusArray = [];

		$dataNotFixed = $this->db->query("SELECT
                              CONVERT(varchar(50), picking_validation.picking_validation_id) AS picking_validation_id,
                              picking_validation.picking_validation_kode,
                              FORMAT(picking_validation.picking_validation_create_tgl, 'dd-MM-yyyy') AS tanggal,
                              picking_validation.picking_validation_status,
                              delivery_order_batch.delivery_order_batch_id,
                              delivery_order_batch.delivery_order_batch_kode,
                              picking_order.picking_order_id,
                              picking_order.picking_order_kode,
															picking_validation.picking_validation_tgl_update as lastUpdated,
                              serah_terima_kirim.serah_terima_kirim_kode,
															serah_terima_kirim.serah_terima_kirim_id
                            FROM picking_validation
                            LEFT JOIN delivery_order_batch
                              ON picking_validation.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
                            LEFT JOIN picking_order
                              ON picking_validation.picking_order_id = picking_order.picking_order_id
                            LEFT JOIN serah_terima_kirim
                              ON picking_validation.serah_terima_kirim_id = serah_terima_kirim.serah_terima_kirim_id
                            WHERE FORMAT(picking_validation.picking_validation_create_tgl, 'yyyy-MM-dd') BETWEEN '$tglStart' AND '$tglEnd'
                            AND picking_validation.depo_id = '" . $this->session->userdata('depo_id') . "'
                            $whereKode
														$whereStatus
                            UNION ALL
                            SELECT
                              '' AS picking_validation_id,
                              '' AS picking_validation_kode,
                              '' AS tanggal,
                              delivery_order_batch.delivery_order_batch_status AS picking_validation_status,
                              delivery_order_batch.delivery_order_batch_id,
                              delivery_order_batch.delivery_order_batch_kode,
                              picking_order.picking_order_id,
                              picking_order.picking_order_kode,
															picking_order.picking_order_tgl_update as lastUpdated,
                              serah_terima_kirim.serah_terima_kirim_kode,
															serah_terima_kirim.serah_terima_kirim_id
                            FROM picking_order
                            LEFT JOIN picking_list
                              ON picking_list.picking_list_id = picking_order.picking_list_id
                            LEFT JOIN delivery_order_batch
                              ON delivery_order_batch.delivery_order_batch_id = picking_list.delivery_order_batch_id
                            LEFT JOIN serah_terima_kirim
                              ON serah_terima_kirim.picking_order_id = picking_order.picking_order_id
                            WHERE FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd') BETWEEN '$tglStart' AND '$tglEnd'
                            AND delivery_order_batch.delivery_order_batch_status = 'in transit validation'
                            AND picking_order.depo_id = '" . $this->session->userdata('depo_id') . "'
                            AND picking_order.picking_order_id NOT IN (select picking_order_id from picking_validation)
                            ORDER BY tanggal ASC")->result();

		if ($dataNotFixed) {
			foreach ($dataNotFixed as $key => $value) {
				if ($value->serah_terima_kirim_kode != null) {
					$dataFixed[] = $value;

					$statusNotSync = 0;
					$generateNotDone = 0;

					$datas =  $this->handlerGetDataProsesValidasi((object)[
						'fdjr' => $value->delivery_order_batch_id,
						'noPPB' => $value->picking_order_id
					]);

					$getPickingListDetail = $this->db->query("SELECT picking_validation_detail.*, picking_validation.picking_order_id
																										FROM picking_validation_detail
																										LEFT JOIN picking_validation ON picking_validation_detail.picking_validation_id = picking_validation.picking_validation_id
																										WHERE picking_validation.picking_order_id = '$value->picking_order_id'")->result();

					if ($datas['dataDetailBarang']) {
						foreach ($datas['dataDetailBarang'] as $key2 => $item) {
							if (($value->delivery_order_batch_id === $item->delivery_order_batch_id) && ($value->picking_order_id === $item->picking_order_id)) {
								$qty_order = $item->qty_order == null ? 0 : (int)$item->qty_order;
								$qty_ambil = $item->qty_ambil == null ? 0 : (int)$item->qty_ambil;
								$serah_terima = $item->serah_terima == null ? 0 : (int)$item->serah_terima;
								$serah_terima_rusak = $item->serah_terima_rusak == null ? 0 : (int)$item->serah_terima_rusak;

								// if ($serah_terima_rusak > 0) $statusNotSync++;

								if ($qty_order !== $qty_ambil || $qty_order !== ($serah_terima + $serah_terima_rusak)) {
									$statusNotSync = 1;
								} else {
									$statusNotSync = 0;
								}

								if ($getPickingListDetail) {
									foreach ($getPickingListDetail as $key => $itemPicking) {
										$qtyValidasi = (int)$itemPicking->qty_validasi_bagus + (int)$itemPicking->qty_validasi_rusak;

										if ($item->picking_order_id === $itemPicking->picking_order_id && $item->sku_id === $itemPicking->sku_id) {
											if ((int)$itemPicking->qty_validasi_bagus !== 0) {
												if ($qtyValidasi !== $qty_ambil) {
													$statusNotSync = 1;
												} else {
													$statusNotSync = 0;
												}
											}
										}
									}
								}
							}
						}
					}

					// if ($datas['dataDetailDO']) {
					// 	foreach ($datas['dataDetailDO'] as $key => $item) {
					// 		if ($value->delivery_order_batch_id === $item->delivery_order_batch_id) {
					// 			$dataDetailDO[] = [
					// 				'noFDJR' => $item->delivery_order_batch_id,
					// 				'deliveryOrderId' => $item->delivery_order_id,
					// 				'prioritas' => $item->delivery_order_no_urut_rute
					// 			];
					// 		}
					// 	}
					// }

					$pickingValidationDetail3 = $this->db->query("select * from picking_validation_detail_3 where picking_validation_id in (select picking_validation_id from picking_validation where picking_order_id = '$value->picking_order_id')")->result();
					$pickingValidationDetail4 = $this->db->query("select * from picking_validation_detail_4 where picking_validation_id in (select picking_validation_id from picking_validation where picking_order_id = '$value->picking_order_id')")->result();
					$pickingValidationDetail5 = $this->db->query("select * from picking_validation_detail_5 where picking_validation_id in (select picking_validation_id from picking_validation where picking_order_id = '$value->picking_order_id')")->result();

					if ($pickingValidationDetail3) {
						foreach ($pickingValidationDetail3 as $key => $item) {
							if ($item->picking_validation_detail_3_status == null) $generateNotDone++;
						}
					}

					if ($pickingValidationDetail4) {
						foreach ($pickingValidationDetail4 as $key => $item) {
							if ($item->picking_validation_detail_4_status == null) $generateNotDone++;
						}
					}

					if ($pickingValidationDetail5) {
						foreach ($pickingValidationDetail5 as $key => $item) {
							if ($item->picking_validation_detail_5_status == null) $generateNotDone++;
						}
					}


					array_push($statusArray, [
						'picking_validation_id' => $value->picking_validation_id,
						'delivery_order_batch_id' => $value->delivery_order_batch_id,
						'picking_order_id' => $value->picking_order_id,
						'status' => (int)$statusNotSync,
						'generateNotDone' => (int)$generateNotDone
					]);
				}
			}
		}

		// $tmpgroup = [];
		// $group = [];

		// if ($dataDetailDO) {
		// 	foreach ($dataDetailDO as $key => $value) {
		// 		$tmpdata = $value;
		// 		unset($tmpdata['noFDJR']);
		// 		$tmpgroup[$value['noFDJR']]['noFDJR'] = $value['noFDJR'];
		// 		$tmpgroup[$value['noFDJR']]['data'][] = $tmpdata;
		// 	}
		// }

		// if ($tmpgroup) {
		// 	foreach ($tmpgroup as $key => $data) {
		// 		$group[] = $data;
		// 	}
		// }

		return [
			'data' => $dataFixed,
			'status' => $statusArray,
		];
	}

	public function getDataFdjrById($dataPost)
	{

		if ($dataPost->pickingValidationId == null) {
			return $this->db->query("SELECT picking_order.picking_order_id, picking_order.picking_order_kode
                             FROM picking_order
                             LEFT JOIN picking_list ON picking_order.picking_list_id = picking_list.picking_list_id
                             WHERE picking_list.delivery_order_batch_id = '$dataPost->fdjrId'
                              AND picking_order.picking_order_id NOT IN (SELECT picking_order_id FROM picking_validation)
                            ORDER BY picking_order_kode ASC")->result();
		} else {
			return $this->db->query("SELECT picking_order.picking_order_id, picking_order.picking_order_kode
                             FROM picking_validation
                             LEFT JOIN picking_order ON picking_validation.picking_order_id = picking_order.picking_order_id
                             WHERE picking_validation.picking_validation_id = '$dataPost->pickingValidationId'
                            ORDER BY picking_order_kode ASC")->result();
		}



		// return $this->db->select("picking_order_id, picking_order_kode")
		//   ->from("picking_order")
		//   ->join("picking_list", "picking_order.picking_list_id = picking_list.picking_list_id", "left")
		//   ->where("picking_list.delivery_order_batch_id", $dataPost->fdjrId)
		//   // ->where("picking_list.picking_list_is_from_validation", 0)
		//   ->order_by("picking_order_kode", "ASC")->get()->result();
	}

	public function handlerGetDataProsesValidasi($dataPost)
	{

		$getTypePickingList = $this->db->query("SELECT picking_list.picking_list_is_from_validation 
                                            FROM picking_list
                                            LEFT JOIN picking_order ON picking_list.picking_list_id = picking_order.picking_list_id
                                            WHERE picking_order.picking_order_id = '$dataPost->noPPB'")->row()->picking_list_is_from_validation;

		$serahTerimaDetail1 = $this->db->query("select * from serah_terima_kirim_d1 where serah_terima_kirim_id in (select serah_terima_kirim_id from serah_terima_kirim where picking_order_id = '$dataPost->noPPB')")->num_rows();
		$serahTerimaDetail2 = $this->db->query("select * from serah_terima_kirim_d2 where serah_terima_kirim_id in (select serah_terima_kirim_id from serah_terima_kirim where picking_order_id = '$dataPost->noPPB')")->num_rows();
		$serahTerimaDetail3 = $this->db->query("select * from serah_terima_kirim_d3 where serah_terima_kirim_id in (select serah_terima_kirim_id from serah_terima_kirim where picking_order_id = '$dataPost->noPPB')")->num_rows();
		$serahTerimaDetail4 = $this->db->query("select * from serah_terima_kirim_d4 where serah_terima_kirim_id in (select serah_terima_kirim_id from serah_terima_kirim where picking_order_id = '$dataPost->noPPB')")->num_rows();

		$joinTableWith = "";

		if ($serahTerimaDetail1 > 0 && $serahTerimaDetail3 > 0) {
			$joinTableWith .= " LEFT JOIN (SELECT
																			serah_terima_kirim_id,
																			sku_id,
																			jumlah_ambil_plan,
																			jumlah_ambil_aktual,
																			jumlah_serah_terima,
																			jumlah_serah_terima_rusak
																		FROM serah_terima_kirim_d1
																		WHERE serah_terima_kirim_id in (select serah_terima_kirim_id from serah_terima_kirim where picking_order_id = '$dataPost->noPPB')
																		UNION 
																		SELECT 
																			serah_terima_kirim_id,
																			sku_id,
																			jumlah_ambil_plan,
																			jumlah_ambil_aktual,
																			jumlah_serah_terima,
																			jumlah_serah_terima_rusak
																		FROM serah_terima_kirim_d3
																		WHERE serah_terima_kirim_id in (select serah_terima_kirim_id from serah_terima_kirim where picking_order_id = '$dataPost->noPPB')) stkd
															ON serah_terima_kirim.serah_terima_kirim_id = stkd.serah_terima_kirim_id AND picking_order_plan.sku_id = stkd.sku_id";
		} else {
			if ($serahTerimaDetail1 > 0 && $serahTerimaDetail3 > 0 && $serahTerimaDetail4 > 0) {
				$joinTableWith .= " LEFT JOIN (SELECT
																				serah_terima_kirim_id,
																				sku_id,
																				jumlah_ambil_plan,
																				jumlah_ambil_aktual,
																				jumlah_serah_terima,
																				jumlah_serah_terima_rusak
																			FROM serah_terima_kirim_d1
																			WHERE serah_terima_kirim_id in (select serah_terima_kirim_id from serah_terima_kirim where picking_order_id = '$dataPost->noPPB')
								
																			UNION 
								
																			SELECT 
																				serah_terima_kirim_id,
																				sku_id,
																				jumlah_ambil_plan,
																				jumlah_ambil_aktual,
																				jumlah_serah_terima,
																				jumlah_serah_terima_rusak
																			FROM serah_terima_kirim_d3
																			WHERE serah_terima_kirim_id in (select serah_terima_kirim_id from serah_terima_kirim where picking_order_id = '$dataPost->noPPB')
								
																			UNION 
								
																			SELECT 
																				serah_terima_kirim_id,
																				sku_id,
																				jumlah_ambil_plan,
																				jumlah_ambil_aktual,
																				jumlah_serah_terima,
																				jumlah_serah_terima_rusak
																			FROM serah_terima_kirim_d4
																			WHERE serah_terima_kirim_id in (select serah_terima_kirim_id from serah_terima_kirim where picking_order_id = '$dataPost->noPPB')) stkd
															ON serah_terima_kirim.serah_terima_kirim_id = stkd.serah_terima_kirim_id AND picking_order_plan.sku_id = stkd.sku_id";
			} else {
				if ($serahTerimaDetail1 > 0) $joinTableWith .= " LEFT JOIN serah_terima_kirim_d1 stkd ON serah_terima_kirim.serah_terima_kirim_id = stkd.serah_terima_kirim_id AND picking_order_plan.sku_id = stkd.sku_id";
				// if ($serahTerimaDetail2 > 0) $joinTableWith = "LEFT JOIN serah_terima_kirim_d2 stkd ON serah_terima_kirim.serah_terima_kirim_id = stkd.serah_terima_kirim_id AND picking_order_plan.sku_id = stkd.sku_id";
				if ($serahTerimaDetail3 > 0) $joinTableWith .= " LEFT JOIN serah_terima_kirim_d3 stkd ON serah_terima_kirim.serah_terima_kirim_id = stkd.serah_terima_kirim_id AND picking_order_plan.sku_id = stkd.sku_id";
				if ($serahTerimaDetail4 > 0) $joinTableWith .= " LEFT JOIN serah_terima_kirim_d4 stkd ON serah_terima_kirim.serah_terima_kirim_id = stkd.serah_terima_kirim_id AND picking_order_plan.sku_id = stkd.sku_id";
			}
		}

		if ($getTypePickingList == 0) {

			$dataDetailDO = $this->db->query("SELECT DISTINCT
                                      do.delivery_order_id,
                                      do.delivery_order_no_urut_rute,
                                      do.delivery_order_kode,
                                      CASE
                                        WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
                                        ELSE do.delivery_order_kirim_nama
                                      END AS delivery_order_kirim_nama,
                                      CASE
                                        WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
                                        ELSE do.delivery_order_kirim_alamat
                                      END AS delivery_order_kirim_alamat,
                                      isnull(segment1.client_pt_segmen_nama, '')  as segment1,
                                      isnull(segment2.client_pt_segmen_nama, '')  as segment2,
                                      isnull(segment3.client_pt_segmen_nama, '')  as segment3,
                                      depo_detail.depo_detail_nama,
																			do.delivery_order_batch_id
                                    FROM delivery_order do
                                    LEFT JOIN delivery_order_batch
                                        ON do.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
                                    LEFT JOIN picking_order
	                                      ON delivery_order_batch.picking_list_id = picking_order.picking_list_id
                                    LEFT JOIN depo_detail
                                      ON depo_detail.depo_detail_id = delivery_order_batch.depo_detail_id
                                    LEFT JOIN client_pt cp
                                      ON do.client_pt_id = cp.client_pt_id
                                    LEFT JOIN client_pt_segmen segment1
                                      ON cp.client_pt_segmen_id1 = segment1.client_pt_segmen_id
                                    LEFT JOIN client_pt_segmen segment2
                                      ON cp.client_pt_segmen_id2 = segment2.client_pt_segmen_id
                                    LEFT JOIN client_pt_segmen segment3
                                      ON cp.client_pt_segmen_id3 = segment3.client_pt_segmen_id
                                    WHERE delivery_order_batch.delivery_order_batch_id = '$dataPost->fdjr'
                                      AND picking_order.picking_order_id = '$dataPost->noPPB'
                                    ORDER BY do.delivery_order_no_urut_rute ASC")->result();
		}

		if ($getTypePickingList == 1) {
			$dataDetailDO = $this->db->query("SELECT DISTINCT
                                            do.delivery_order_id,
                                            do.delivery_order_no_urut_rute,
                                            do.delivery_order_kode,
                                            CASE
                                            WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
                                            ELSE do.delivery_order_kirim_nama
                                            END AS delivery_order_kirim_nama,
                                            CASE
                                            WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
                                            ELSE do.delivery_order_kirim_alamat
                                            END AS delivery_order_kirim_alamat,
                                            isnull(segment1.client_pt_segmen_nama, '')  as segment1,
                                            isnull(segment2.client_pt_segmen_nama, '')  as segment2,
                                            isnull(segment3.client_pt_segmen_nama, '')  as segment3,
                                            depo_detail.depo_detail_nama,
																						do.delivery_order_batch_id
                                        FROM picking_list pl
                                        LEFT JOIN picking_list_detail pld
                                            ON pl.picking_list_id = pld.picking_list_id
                                        LEFT JOIN picking_list_detail_2 pld2
                                            ON pld.picking_list_detail_id = pld2.picking_list_detail_id
                                        LEFT JOIN delivery_order do
                                          ON pld.delivery_order_id = do.delivery_order_id
                                        LEFT JOIN delivery_order_batch
                                            ON pl.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
                                        LEFT JOIN picking_order
                                              ON pl.picking_list_id = picking_order.picking_list_id
                                        LEFT JOIN depo_detail
                                            ON depo_detail.depo_detail_id = delivery_order_batch.depo_detail_id
                                        LEFT JOIN client_pt cp
                                            ON do.client_pt_id = cp.client_pt_id
                                        LEFT JOIN client_pt_segmen segment1
                                            ON cp.client_pt_segmen_id1 = segment1.client_pt_segmen_id
                                        LEFT JOIN client_pt_segmen segment2
                                            ON cp.client_pt_segmen_id2 = segment2.client_pt_segmen_id
                                        LEFT JOIN client_pt_segmen segment3
                                            ON cp.client_pt_segmen_id3 = segment3.client_pt_segmen_id
                                        WHERE delivery_order_batch.delivery_order_batch_id = '$dataPost->fdjr'
                                            AND picking_order.picking_order_id = '$dataPost->noPPB'
                                        ORDER BY do.delivery_order_no_urut_rute ASC")->result();
		}


		$dataDetailBarang =  $this->db->query("SELECT DISTINCT
																							delivery_order_batch.delivery_order_batch_id,
																							picking_order.picking_order_id,
																							picking_order_plan.picking_order_plan_id,
																							FORMAT(picking_order.picking_order_tanggal, 'yyyy-MM-dd') as tgl,
																							picking_order.picking_order_kode,
																							karyawan.karyawan_nama as driver,
																							principal.principle_kode AS principal,
																							sku.sku_id,
																							sku.sku_kode,
																							sku.sku_nama_produk,
																							sku.sku_satuan,
																							sku.sku_kemasan,
																							depo_detail.depo_detail_id,
																							depo_detail.depo_detail_nama,
																							serah_terima_kirim.serah_terima_kirim_id,
																							serah_terima_kirim.serah_terima_kirim_kode,
																							stkd.jumlah_ambil_plan as qty_order,
																							stkd.jumlah_ambil_aktual as qty_ambil,
																							stkd.jumlah_serah_terima as serah_terima,
																							isnull(stkd.jumlah_serah_terima_rusak, 0) as serah_terima_rusak,
																							FORMAT(picking_order_plan.sku_stock_expired_date, 'yyyy-MM-dd') as sku_expdate,
																							picking_order_plan.sku_stock_id
																					FROM picking_order
																					LEFT JOIN picking_order_plan
																							ON picking_order.picking_order_id = picking_order_plan.picking_order_id
																					LEFT JOIN picking_list
																							ON picking_order.picking_list_id = picking_list.picking_list_id
																					LEFT JOIN delivery_order_batch
																							ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
																					LEFT JOIN serah_terima_kirim
																						ON picking_order.picking_order_id = serah_terima_kirim.picking_order_id
																						AND delivery_order_batch.delivery_order_batch_id = serah_terima_kirim.delivery_order_batch_id
																					$joinTableWith
																					LEFT JOIN karyawan 
																							ON delivery_order_batch.karyawan_id = karyawan.karyawan_id
																					LEFT JOIN SKU
																							ON SKU.sku_id = picking_order_plan.sku_id
																					LEFT JOIN principle principal
																							ON sku.principle_id = principal.principle_id
																					LEFT JOIN depo_detail
																							ON depo_detail.depo_detail_id = delivery_order_batch.depo_detail_id
																					LEFT JOIN tipe_delivery_order
																							ON tipe_delivery_order.tipe_delivery_order_id = picking_order_plan.tipe_delivery_order_id
																					WHERE delivery_order_batch.delivery_order_batch_id = '$dataPost->fdjr'
																						AND picking_order.picking_order_id = '$dataPost->noPPB'
																					AND picking_order_plan.sku_stock_expired_date is not null
																					ORDER BY principal.principle_kode ASC")->result();

		$response = [
			'dataDetailBarang' => $dataDetailBarang,
			'dataDetailDO' => $dataDetailDO
		];

		return $response;
	}

	public function getDetailBarangDetailBySkuId($dataPost)
	{

		$getTypePickingList = $this->db->query("SELECT picking_list.picking_list_is_from_validation, picking_list.picking_list_id
                                            FROM picking_list
                                            LEFT JOIN picking_order ON picking_list.picking_list_id = picking_order.picking_list_id
                                            WHERE picking_order.picking_order_id = '$dataPost->noPPB'")->row();

		if ($getTypePickingList->picking_list_is_from_validation == 0) {
			return $this->db->query("SELECT DISTINCT
                                do.delivery_order_id,
                                do.delivery_order_no_urut_rute,
                                do.delivery_order_kode,
                                CASE
                                  WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
                                  ELSE do.delivery_order_kirim_nama
                                END AS delivery_order_kirim_nama,
                                CASE
                                  WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
                                  ELSE do.delivery_order_kirim_alamat
                                END AS delivery_order_kirim_alamat,
                                isnull(segment1.client_pt_segmen_nama, '')  as segment1,
                                isnull(segment2.client_pt_segmen_nama, '')  as segment2,
                                isnull(segment3.client_pt_segmen_nama, '')  as segment3,
                                dod.sku_qty as jumlah_order,
                                do.is_promo
                              FROM delivery_order_detail dod
                              LEFT JOIN delivery_order do
                                ON dod.delivery_order_id = do.delivery_order_id
                              LEFT JOIN picking_list_detail pld
                                ON do.delivery_order_id = pld.delivery_order_id
                              LEFT JOIN picking_list pl
                                ON pld.picking_list_id = pl.picking_list_id
                              LEFT JOIN client_pt cp
                                ON do.client_pt_id = cp.client_pt_id
                              LEFT JOIN client_pt_segmen segment1
                                ON cp.client_pt_segmen_id1 = segment1.client_pt_segmen_id
                              LEFT JOIN client_pt_segmen segment2
                                ON cp.client_pt_segmen_id2 = segment2.client_pt_segmen_id
                              LEFT JOIN client_pt_segmen segment3
                                ON cp.client_pt_segmen_id3 = segment3.client_pt_segmen_id
                              WHERE dod.delivery_order_batch_id = '$dataPost->fdjrId'
                                AND dod.sku_id = '$dataPost->skuId'
                                AND pl.picking_list_id = '$getTypePickingList->picking_list_id'
                                AND do.delivery_order_id is not null
                              ORDER BY do.delivery_order_no_urut_rute ASC")->result();
		}

		if ($getTypePickingList->picking_list_is_from_validation == 1) {
			return $this->db->query("SELECT DISTINCT
                                do.delivery_order_id,
                                do.delivery_order_no_urut_rute,
                                do.delivery_order_kode,
                                CASE
                                  WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
                                  ELSE do.delivery_order_kirim_nama
                                END AS delivery_order_kirim_nama,
                                CASE
                                  WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
                                  ELSE do.delivery_order_kirim_alamat
                                END AS delivery_order_kirim_alamat,
                                isnull(segment1.client_pt_segmen_nama, '')  as segment1,
                                isnull(segment2.client_pt_segmen_nama, '')  as segment2,
                                isnull(segment3.client_pt_segmen_nama, '')  as segment3,
                                pld2.sku_qty_order as jumlah_order,
                                do.is_promo
                              FROM picking_list pl
                              LEFT JOIN picking_list_detail pld
                                  ON pl.picking_list_id = pld.picking_list_id
                              LEFT JOIN picking_list_detail_2 pld2
                                  ON pld.picking_list_detail_id = pld2.picking_list_detail_id
                              LEFT JOIN delivery_order do
                                ON pld.delivery_order_id = do.delivery_order_id
                              LEFT JOIN client_pt cp
                                ON do.client_pt_id = cp.client_pt_id
                              LEFT JOIN client_pt_segmen segment1
                                ON cp.client_pt_segmen_id1 = segment1.client_pt_segmen_id
                              LEFT JOIN client_pt_segmen segment2
                                ON cp.client_pt_segmen_id2 = segment2.client_pt_segmen_id
                              LEFT JOIN client_pt_segmen segment3
                                ON cp.client_pt_segmen_id3 = segment3.client_pt_segmen_id
                              WHERE pl.delivery_order_batch_id = '$dataPost->fdjrId'
                                AND pl.picking_list_is_from_validation = '$getTypePickingList->picking_list_is_from_validation'
                                AND pld2.sku_id = '$dataPost->skuId'
                                AND pl.picking_list_id = '$getTypePickingList->picking_list_id'
                                AND do.delivery_order_id is not null
                              ORDER BY do.delivery_order_no_urut_rute ASC")->result();
		}
	}

	public function getDetailDropKoreksiDObyId($dataPost)
	{
		return $this->db->query("SELECT
                                delivery_order.delivery_order_id,
                                delivery_order_detail.delivery_order_detail_id,
                                principal.principle_kode AS principal,
                                sku.sku_id,
                                sku.sku_kode,
                                sku.sku_nama_produk,
                                sku.sku_satuan,
                                sku.sku_kemasan,
                                ISNULL(delivery_order_detail.sku_qty, 0) AS qty,
                                delivery_order_detail2.sku_expdate,
	                              delivery_order_detail2.sku_stock_id
                            FROM delivery_order
                            LEFT JOIN delivery_order_detail
                                ON delivery_order.delivery_order_id = delivery_order_detail.delivery_order_id
                            LEFT JOIN delivery_order_detail2
                                ON delivery_order.delivery_order_id = delivery_order_detail2.delivery_order_id
                                AND delivery_order_detail.sku_id = delivery_order_detail2.sku_id
                            LEFT JOIN SKU
                                ON SKU.sku_id = delivery_order_detail.sku_id
                            LEFT JOIN principle principal
                                ON sku.principle_id = principal.principle_id
                            WHERE delivery_order.delivery_order_id = '$dataPost->delivery_order_id'")->result();
	}

	public function saveDataProsesValidasi($dataPost)
	{

		$this->db->trans_begin();

		$pickingValidationId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];
		$dataPickingValidationDetail = [];
		$dataPickingValidationDetail2 = [];
		$dataPickingValidationDetail3 = [];

		//generate kode
		$vrbl = $this->M_Vrbl->Get_Kode('KODE_PV');
		// get prefik depo
		$depoPrefix = $this->getDepoPrefix($this->session->userdata('depo_id'));

		$generateKode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal(date('Y-m-d h:i:s'),  $vrbl->vrbl_kode, $depoPrefix->depo_kode_preffix);

		if ($dataPost->pickingValidationId == "") {

			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "picking_order",
				'whereField' => "picking_order_id",
				'whereValue' => $dataPost->pickingOrderId,
				'fieldDateUpdate' => "picking_order_tgl_update",
				'fieldWhoUpdate' => "picking_order_who_update",
				'lastUpdated' => $dataPost->lastUpdated
			]);

			$pickingHeaderId = $pickingValidationId;

			$dataPickingValidation = [
				'picking_validation_id' => $pickingValidationId,
				'depo_id' => $this->session->userdata('depo_id'),
				'delivery_order_batch_id' => $dataPost->deliveryOrderBatchId,
				'picking_order_id' => $dataPost->pickingOrderId,
				'serah_terima_kirim_id' => $dataPost->serahTerimaKirimId,
				'picking_validation_kode' => $generateKode,
				'picking_validation_create_who' => $this->session->userdata('pengguna_username'),
				'picking_validation_create_tgl' => date('Y-m-d h:i:s'),
				'picking_validation_keterangan' => $dataPost->keterangan === "" ? NULL : $dataPost->keterangan,
				'picking_validation_status' => $dataPost->status
			];

			$this->db->insert('picking_validation', $dataPickingValidation);
		} else {

			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "picking_validation",
				'whereField' => "picking_validation_id",
				'whereValue' => $dataPost->pickingValidationId,
				'fieldDateUpdate' => "picking_validation_tgl_update",
				'fieldWhoUpdate' => "picking_validation_who_update",
				'lastUpdated' => $dataPost->lastUpdated
			]);

			$pickingHeaderId = $dataPost->pickingValidationId;

			$dataPickingValidation = [
				'picking_validation_keterangan' => $dataPost->keterangan === "" ? NULL : $dataPost->keterangan
			];

			$this->db->delete('picking_validation_detail', ['picking_validation_id' => $dataPost->pickingValidationId]);
			$this->db->delete('picking_validation_detail_2', ['picking_validation_id' => $dataPost->pickingValidationId]);
			$getIdDetail3 = $this->db->select("picking_validation_detail_3_id")->from("picking_validation_detail_3")->where("picking_validation_id", $dataPost->pickingValidationId)->get()->result();
			if ($getIdDetail3) {
				foreach ($getIdDetail3 as $key => $value) {
					$this->db->delete('picking_validation_detail_3_do', ['picking_validation_detail_3_id' => $value->picking_validation_detail_3_id]);
				}
			}
			$this->db->delete('picking_validation_detail_3', ['picking_validation_id' => $dataPost->pickingValidationId]);
			$this->db->delete('picking_validation_detail_4', ['picking_validation_id' => $dataPost->pickingValidationId]);
			$this->db->delete('picking_validation_detail_5', ['picking_validation_id' => $dataPost->pickingValidationId]);
			$this->db->delete('picking_validation_detail_6', ['picking_validation_id' => $dataPost->pickingValidationId]);

			$this->db->update('picking_validation', $dataPickingValidation, ['picking_validation_id' => $dataPost->pickingValidationId]);
		}

		foreach ($dataPost->pickingValidationDetail as $key => $value) {
			$pickingValidationDetailId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

			$dataPickingValidationDetail[] = [
				'picking_validation_detail_id' => $pickingValidationDetailId,
				'picking_validation_id' => $pickingHeaderId,
				'sku_id' => $value->skuId,
				'qty_order' => $value->qtyOrder,
				'qty_ambil' => $value->qtyAmbil == null ? 0 : $value->qtyAmbil,
				'qty_terima_bagus' => $value->qtyTerimaBagus,
				'qty_terima_rusak' => $value->qtyTerimaRusak,
				'qty_validasi_bagus' => $value->qtyValidasiBagus,
				'qty_validasi_rusak' => $value->qtyValidasiRusak,
				'picking_validation_detail_status' => 'In Progress',
			];
		}

		if ($dataPost->pickingValidationDetail2) {
			foreach ($dataPost->pickingValidationDetail2 as $key => $value) {
				$pickingValidationDetail2Id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

				$this->db->insert('picking_validation_detail_2', [
					'picking_validation_detail_2_id' => $pickingValidationDetail2Id,
					'picking_validation_id' => $pickingHeaderId,
					'delivery_order_id' => $value->deliveryOrderId,
					'delivery_order_no_urut_rute' => $value->prioritas,
					'picking_validation_detail_2_status' => 'Drop',
				]);
			}
		}

		if ($dataPost->pickingValidationDetail3) {
			foreach ($dataPost->pickingValidationDetail3 as $key => $value) {
				$pickingValidationDetail3Id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

				if ($value->qty > 0) {
					$this->db->insert('picking_validation_detail_3', [
						'picking_validation_detail_3_id' => $pickingValidationDetail3Id,
						'picking_validation_id' => $pickingHeaderId,
						'sku_id' => $value->skuId,
						'sku_qty_plan_ambil' => $value->qty,
						'sku_stock_id' => $value->skuStockId,
						'sku_expdate' => $value->skuExpDate,
						// 'picking_validation_detail_3_status' => 'entah'
					]);
				}

				if ($dataPost->pickingValidationDetail3ForDO) {
					foreach ($dataPost->pickingValidationDetail3ForDO as $key2 => $val) {
						$pickingValidationDetail3IdDo = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];
						if (isset($val->akumulasi)) {
							if ($value->skuId == $val->skuId) {
								$this->db->insert('picking_validation_detail_3_do', [
									'picking_validation_detail_3_do_id' => $pickingValidationDetail3IdDo,
									'picking_validation_detail_3_id' => $pickingValidationDetail3Id,
									'delivery_order_id' => $val->doId,
									'sku_qty_plan_ambil' => $val->akumulasi,
									'sku_id' => $val->skuId,
								]);
							}
						}
					}
				}
			}
		}

		if ($dataPost->pickingValidationDetail4) {
			foreach ($dataPost->pickingValidationDetail4 as $key => $value) {
				$pickingValidationDetail4Id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

				if ($value->qty > 0) {
					$this->db->insert('picking_validation_detail_4', [
						'picking_validation_detail_4_id' => $pickingValidationDetail4Id,
						'picking_validation_id' => $pickingHeaderId,
						'sku_id' => $value->skuId,
						'sku_qty_plan_ambil' => $value->qty,
						'sku_stock_id' => $value->skuStockId,
						'sku_expdate' => $value->skuExpDate,
						'is_type_picking_validation' => $value->type,
						'delivery_oder_id' => $value->deliveryOrderId == "null" ? NULL : $value->deliveryOrderId,
						'is_type_drop_picking_validation' => $value->typeDrop == 'semua' || $value->typeDrop == 'sebagian' ? $value->typeDrop : NULL,
					]);
				}
			}
		}

		if ($dataPost->pickingValidationDetail5) {
			foreach ($dataPost->pickingValidationDetail5 as $key => $value) {
				$pickingValidationDetail5Id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

				if ($value->qty > 0) {
					$this->db->insert('picking_validation_detail_5', [
						'picking_validation_detail_5_id' => $pickingValidationDetail5Id,
						'picking_validation_id' => $pickingHeaderId,
						'sku_id' => $value->skuId,
						'sku_qty_plan_ambil' => $value->qty,
						'sku_stock_id' => $value->skuStockId,
						'sku_expdate' => $value->skuExpDate,
						// 'picking_validation_detail_5_status' => 'entah'
					]);
				}
			}
		}

		if ($dataPost->arrDropDOdiSKU) {
			foreach ($dataPost->arrDropDOdiSKU as $key => $value) {
				foreach ($value->data as $key2 => $val) {
					$pickingValidationDetail6Id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];
					if ($value->type == "sebagian") $this->insertToDropValidationDetail6($pickingValidationDetail6Id, $pickingHeaderId, $value, $val);
					if ($value->type == "semua") $this->insertToDropValidationDetail6($pickingValidationDetail6Id, $pickingHeaderId, $value, $val);
				}
			}
		}


		$this->db->insert_batch('picking_validation_detail', $dataPickingValidationDetail);
		// $this->db->insert_batch('picking_validation_detail_2', $dataPickingValidationDetail2);
		// if ($dataPost->pickingValidationDetail3) {
		//   $this->db->insert_batch('picking_validation_detail_3', $dataPickingValidationDetail3);
		// }

		if ($lastUpdatedChecked['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'pickingValidationId' => $pickingHeaderId,
				'message' => 'Data Gagal Disimpan',
				'lastUpdatedNew' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'pickingValidationId' => $pickingHeaderId,
				'message' => 'Data Gagal Disimpan',
				'lastUpdatedNew' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'pickingValidationId' => $pickingHeaderId,
				'message' => 'Data Berhasil Disimpan',
				'lastUpdatedNew' => $lastUpdatedChecked['lastUpdatedNew']
			];
		}

		return $response;
	}

	private function insertToDropValidationDetail6($pickingValidationDetail6Id, $pickingHeaderId, $dataPertama, $dataKedua)
	{
		return $this->db->insert('picking_validation_detail_6', [
			'picking_validation_detail_6_id' => $pickingValidationDetail6Id,
			'picking_validation_id' => $pickingHeaderId,
			'delivery_order_id' => $dataPertama->deliveryOrderId,
			'delivery_order_batch_id' => $dataPertama->noFDJR,
			'parent_sku_id_is_drop' => $dataPertama->skuId,
			'sku_id' => $dataKedua->skuId,
			'sku_stock_id' => $dataKedua->skuStockId,
			'sku_expdate' => $dataKedua->skuExpDate,
			'sku_kode' => $dataKedua->skuKode,
			'sku_nama_produk' => $dataKedua->skuNamaProduk,
			'sku_kemasan' => $dataKedua->skuKemasan,
			'sku_satuan' => $dataKedua->skuSatuan,
			'qty' => $dataKedua->qty,
			'type' => $dataPertama->type,
		]);
	}

	public function konfirmasiDataProsesValidasi($dataPost)
	{

		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "picking_validation",
			'whereField' => "picking_validation_id",
			'whereValue' => $dataPost->pickingValidationId,
			'fieldDateUpdate' => "picking_validation_tgl_update",
			'fieldWhoUpdate' => "picking_validation_who_update",
			'lastUpdated' => $dataPost->lastUpdated
		]);

		$this->db->update('picking_validation', ['picking_validation_status' => 'In Progress Confirmation'], ['picking_validation_id' => $dataPost->pickingValidationId]);

		$getDataDetail6 = $this->db->get_where('picking_validation_detail_6', ['picking_validation_id' => $dataPost->pickingValidationId])->result();

		if ($getDataDetail6) {
			foreach ($getDataDetail6 as $key => $value) {
				if ($value->type == 'sebagian') {
					$getCurrentQty = $this->db->select('sku_qty')->from('delivery_order_detail')
						->where('delivery_order_id', $value->delivery_order_id)
						->where('delivery_order_batch_id', $value->delivery_order_batch_id)
						->where('sku_id', $value->sku_id)->get()->row()->sku_qty;
					$this->db->update('delivery_order_detail', [
						'sku_qty' => (int) $getCurrentQty - (int) $value->qty,
					], [
						'delivery_order_id' => $value->delivery_order_id,
						'delivery_order_batch_id' => $value->delivery_order_batch_id,
						'sku_id' => $value->sku_id
					]);
				}

				if ($value->type == 'semua') {
					$this->db->update('delivery_order_detail', [
						'sku_qty' => 0,
					], [
						'delivery_order_id' => $value->delivery_order_id,
						'delivery_order_batch_id' => $value->delivery_order_batch_id
					]);
				}
			}
		}

		return responseJson((object)[
			'lastUpdatedChecked' => $lastUpdatedChecked,
			'status' => 'Dikonfirmasi'
		]);
	}

	public function konfirmasiSelesaiDataProsesValidasi($dataPost)
	{

		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "picking_validation",
			'whereField' => "picking_validation_id",
			'whereValue' => $dataPost->pickingValidationId,
			'fieldDateUpdate' => "picking_validation_tgl_update",
			'fieldWhoUpdate' => "picking_validation_who_update",
			'lastUpdated' => $dataPost->lastUpdated,
		]);

		// $this->db->update('picking_validation', ['picking_validation_status' => 'Completed'], ['picking_validation_id' => $dataPost->pickingValidationId]);
		$error = false;
		$errroMsg = "";

		$getStatusGenerate = $this->db->select("is_generate_picking_list, is_generate_koreksi_bkb, is_generate_back_location")->from("picking_validation")->where("picking_validation_id", $dataPost->pickingValidationId)->get()->row();
		$getDataDetail3ById = $this->db->select("*")->from("picking_validation_detail_3")->where("picking_validation_id", $dataPost->pickingValidationId)->get()->num_rows();
		$getDataDetail4ById = $this->db->select("*")->from("picking_validation_detail_4")->where("picking_validation_id", $dataPost->pickingValidationId)->get()->num_rows();
		$getDataDetail5ById = $this->db->select("*")->from("picking_validation_detail_5")->where("picking_validation_id", $dataPost->pickingValidationId)->get()->num_rows();

		if ($getDataDetail3ById > 0) {
			if ($getStatusGenerate->is_generate_picking_list != 1) {
				$error = true;
				$errroMsg = [
					'status' => 402,
					'message' => 'generate picking list masih belum dilaksanakan'
				];
			}
		}

		if ($getDataDetail4ById > 0) {
			if ($getStatusGenerate->is_generate_back_location != 1) {
				$error = true;
				$errroMsg = [
					'status' => 402,
					'message' => 'generate kembali ke lokasi masih belum dilaksanakan'
				];
			}
		}

		if ($getDataDetail5ById > 0) {
			if ($getStatusGenerate->is_generate_koreksi_bkb != 1) {
				$error = true;
				$errroMsg = [
					'status' => 402,
					'message' => 'generate dokumen koreksi bkb masih belum dilaksanakan'
				];
			}
		}

		$this->db->query("exec proses_konfirmasi_picking_validation '$dataPost->pickingValidationId'");

		if ($lastUpdatedChecked['status'] === 400) {
			$response = [
				'status' => 400,
				'message' => 'Konfirmasi selesai gagal dikonfirmasi',
			];
			$this->db->trans_rollback();
		} else if ($this->db->trans_status() === FALSE) {
			$response = [
				'status' => 401,
				'message' => $this->db->error()['message'],
			];
			$this->db->trans_rollback();
		} else if ($error === true) {
			$response = $errroMsg;
			$this->db->trans_rollback();
		} else {
			$response = [
				'status' => 200,
				'message' => 'Konfirmasi selesai berhasil',
			];
			$this->db->trans_commit();
		}

		return $response;
	}


	public function konfirmasiSelesaiDepan($dataPost)
	{

		$this->db->trans_begin();

		$pickingValidationId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

		//generate kode
		$vrbl = $this->M_Vrbl->Get_Kode('KODE_PV');
		// get prefik depo
		$depoPrefix = $this->getDepoPrefix($this->session->userdata('depo_id'));

		$generateKode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal(date('Y-m-d h:i:s'),  $vrbl->vrbl_kode, $depoPrefix->depo_kode_preffix);

		$pickingHeaderId = $dataPost->pickingValidationId;

		//jika picking validation kosong maka insert dlu ke picking validation, picking validation detail dan picking validation detail 2
		//jika ada langsung jalankan code konfirmasi
		if ($dataPost->pickingValidationId === "") {
			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "picking_order",
				'whereField' => "picking_order_id",
				'whereValue' => $dataPost->pickingOrderId,
				'fieldDateUpdate' => "picking_order_tgl_update",
				'fieldWhoUpdate' => "picking_order_who_update",
				'lastUpdated' => $dataPost->lastUpdated
			]);

			$pickingHeaderId = $pickingValidationId;

			$dataPickingValidation = [
				'picking_validation_id' => $pickingValidationId,
				'depo_id' => $this->session->userdata('depo_id'),
				'delivery_order_batch_id' => $dataPost->deliveryOrderBatchId,
				'picking_order_id' => $dataPost->pickingOrderId,
				'serah_terima_kirim_id' => $dataPost->serahTerimaKirimId,
				'picking_validation_kode' => $generateKode,
				'picking_validation_create_who' => $this->session->userdata('pengguna_username'),
				'picking_validation_create_tgl' => date('Y-m-d h:i:s'),
				'picking_validation_status' => 'Draft'
			];

			$this->db->insert('picking_validation', $dataPickingValidation);
		} else {
			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "picking_validation",
				'whereField' => "picking_validation_id",
				'whereValue' => $dataPost->pickingValidationId,
				'fieldDateUpdate' => "picking_validation_tgl_update",
				'fieldWhoUpdate' => "picking_validation_who_update",
				'lastUpdated' => $dataPost->lastUpdated,
			]);
		}

		$datas = $this->handlerGetDataProsesValidasi((object)[
			'fdjr' => $dataPost->deliveryOrderBatchId,
			'noPPB' => $dataPost->pickingOrderId
		]);

		foreach ($datas['dataDetailBarang'] as $key => $value) {
			$pickingValidationDetailId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

			$this->db->insert('picking_validation_detail', [
				'picking_validation_detail_id' => $pickingValidationDetailId,
				'picking_validation_id' => $pickingHeaderId,
				'sku_id' => $value->sku_id,
				'qty_order' => $value->qty_order,
				'qty_ambil' => $value->qty_ambil == null ? 0 : $value->qty_ambil,
				'qty_terima_bagus' => $value->serah_terima == null ? 0 : $value->serah_terima,
				'qty_terima_rusak' => $value->serah_terima_rusak == null ? 0 : $value->serah_terima_rusak,
				'qty_validasi_bagus' => $value->qty_ambil == null ? 0 : $value->qty_ambil,
				'qty_validasi_rusak' => 0,
				'picking_validation_detail_status' => 'In Progress',
			]);
		}

		if ($datas['dataDetailDO']) {
			foreach ($datas['dataDetailDO'] as $key => $value) {
				$pickingValidationDetail2Id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

				$this->db->insert('picking_validation_detail_2', [
					'picking_validation_detail_2_id' => $pickingValidationDetail2Id,
					'picking_validation_id' => $pickingHeaderId,
					'delivery_order_id' => $value->delivery_order_id,
					'delivery_order_no_urut_rute' => $value->delivery_order_no_urut_rute,
					'picking_validation_detail_2_status' => 'Drop',
				]);
			}
		}

		// $this->db->update('picking_validation', ['picking_validation_status' => 'Completed'], ['picking_validation_id' => $dataPost->pickingValidationId]);
		$error = false;
		$errroMsg = "";

		$getStatusGenerate = $this->db->select("is_generate_picking_list, is_generate_koreksi_bkb, is_generate_back_location")->from("picking_validation")->where("picking_validation_id", $pickingHeaderId)->get()->row();
		$getDataDetail3ById = $this->db->select("*")->from("picking_validation_detail_3")->where("picking_validation_id", $pickingHeaderId)->get()->num_rows();
		$getDataDetail4ById = $this->db->select("*")->from("picking_validation_detail_4")->where("picking_validation_id", $pickingHeaderId)->get()->num_rows();
		$getDataDetail5ById = $this->db->select("*")->from("picking_validation_detail_5")->where("picking_validation_id", $pickingHeaderId)->get()->num_rows();

		if ($getDataDetail3ById > 0) {
			if ($getStatusGenerate->is_generate_picking_list != 1) {
				$error = true;
				$errroMsg = [
					'status' => 402,
					'message' => 'generate picking list masih belum dilaksanakan'
				];
			}
		}

		if ($getDataDetail4ById > 0) {
			if ($getStatusGenerate->is_generate_back_location != 1) {
				$error = true;
				$errroMsg = [
					'status' => 402,
					'message' => 'generate kembali ke lokasi masih belum dilaksanakan'
				];
			}
		}

		if ($getDataDetail5ById > 0) {
			if ($getStatusGenerate->is_generate_koreksi_bkb != 1) {
				$error = true;
				$errroMsg = [
					'status' => 402,
					'message' => 'generate dokumen koreksi bkb masih belum dilaksanakan'
				];
			}
		}

		$this->db->query("exec proses_konfirmasi_picking_validation '$pickingHeaderId'");

		if ($lastUpdatedChecked['status'] === 400) {
			$response = [
				'status' => 400,
				'message' => 'Konfirmasi selesai gagal dikonfirmasi',
			];
			$this->db->trans_rollback();
		} else if ($this->db->trans_status() === FALSE) {
			$response = [
				'status' => 401,
				'message' => $this->db->error()['message'],
			];
			$this->db->trans_rollback();
		} else if ($error === true) {
			$response = $errroMsg;
			$this->db->trans_rollback();
		} else {
			$response = [
				'status' => 200,
				'message' => 'Konfirmasi selesai berhasil',
			];
			$this->db->trans_commit();
		}

		return $response;
	}

	public function generatePickingList($dataPost)
	{


		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "picking_validation",
			'whereField' => "picking_validation_id",
			'whereValue' => $dataPost->pickingValidationId,
			'fieldDateUpdate' => "picking_validation_tgl_update",
			'fieldWhoUpdate' => "picking_validation_who_update",
			'lastUpdated' => $dataPost->lastUpdated
		]);

		$pickingValidationId = $dataPost->pickingValidationId;

		$this->db->update('picking_validation', ['is_generate_picking_list' => 1], ['picking_validation_id' => $pickingValidationId]);
		$this->db->update('picking_validation_detail_3', ['picking_validation_detail_3_status' => 'Generate Done'], ['picking_validation_id' => $pickingValidationId]);

		$pickingTglKirim = date('Y-m-d H:i:s');
		$pickingId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

		$dataAwalDo = $this->db->select("b.depo_id, b.area_id, b.tipe_delivery_order_id")->from('picking_validation a')
			->join("delivery_order_batch b", "a.delivery_order_batch_id = b.delivery_order_batch_id", "left")
			->where("a.picking_validation_id", $pickingValidationId)->get()->row();

		$deliveryOrderBatchId = $this->db->select("delivery_order_batch_id")->from("picking_validation")->where("picking_validation_id", $pickingValidationId)->get()->row()->delivery_order_batch_id;

		$vrbl = $this->M_Vrbl->Get_Kode('KODE_PB');
		$depoPrefix = $this->getDepoPrefix($dataAwalDo->depo_id);

		$generateKodePickingList = $this->M_AutoGen->Exec_CodeGenGeneralTanggal(date('Y-m-d h:i:s'),  $vrbl->vrbl_kode, $depoPrefix->depo_kode_preffix);

		$getDataDetail = $this->db->select("
          picking_validation_detail_3.sku_id,
          picking_validation_detail_3.sku_stock_id,
          picking_validation_detail_3.sku_expdate,
          picking_validation_detail_3_do.delivery_order_id,
          picking_validation_detail_3_do.sku_qty_plan_ambil
      ")->from("picking_validation_detail_3")
			->join("picking_validation_detail_3_do", "picking_validation_detail_3.picking_validation_detail_3_id = picking_validation_detail_3_do.picking_validation_detail_3_id", "left")
			->where("picking_validation_detail_3.picking_validation_id", $pickingValidationId)
			->where("picking_validation_detail_3_do.sku_qty_plan_ambil !=", 0)
			->get()->result();

		// $getDataDetail2 = $this->db->select("sku_id, sku_qty_plan_ambil, sku_stock_id, sku_expdate")->from("picking_validation_detail_3")->where("picking_validation_id", $pickingValidationId)->get()->result();

		$this->db->insert('picking_list', [
			'picking_list_id' => $pickingId,
			'depo_id' => $dataAwalDo->depo_id,
			'area_id' => $dataAwalDo->area_id,
			'delivery_order_batch_id' => $deliveryOrderBatchId,
			'tipe_delivery_order_id' => $dataAwalDo->tipe_delivery_order_id,
			'picking_list_kode' => $generateKodePickingList,
			'picking_list_tgl_kirim' => $pickingTglKirim,
			'picking_list_create_who' => $this->session->userdata('pengguna_username'),
			'picking_list_create_tgl' => $pickingTglKirim,
			'picking_list_status' => 'Open',
			'picking_list_is_from_validation' => 1
		]);

		foreach ($getDataDetail as $key => $value) {
			$pickingDetailId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];
			$pickingDetail2Id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];
			$deliveryOrderProgressId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

			$getDataSkuStock = $this->db->select("ISNULL(sku_stock_alokasi, 0) as sku_stock_alokasi, ISNULL(sku_stock_saldo_alokasi, 0) as sku_stock_saldo_alokasi")->from("sku_stock")->where("sku_stock_id", $value->sku_stock_id)->get()->row();

			$skuStockSaldoAlokasi = $getDataSkuStock->sku_stock_saldo_alokasi + $value->sku_qty_plan_ambil;
			$this->db->query("exec insertupdate_sku_stock 'saldoalokasi_tambah', '$value->sku_stock_id', NULL,'$value->sku_qty_plan_ambil'");

			// $this->db->update('sku_stock', [
			// 	'sku_stock_alokasi' => $getDataSkuStock->sku_stock_alokasi + $value->sku_qty_plan_ambil,
			// 	// 'sku_stock_saldo_alokasi' => $getDataSkuStock->sku_stock_saldo_alokasi + $value->sku_qty_plan_ambil,
			// ], ['sku_stock_id' => $value->sku_stock_id]);

			$this->db->insert('picking_list_detail', [
				'picking_list_detail_id' => $pickingDetailId,
				'picking_list_id' => $pickingId,
				'delivery_order_id' => $value->delivery_order_id
			]);

			$this->db->insert('picking_list_detail_2', [
				'picking_list_detail_2_id' => $pickingDetail2Id,
				'picking_list_detail_id' => $pickingDetailId,
				'delivery_order_id' => $value->delivery_order_id,
				'sku_id' => $value->sku_id,
				'sku_stock_id' => $value->sku_stock_id,
				'sku_qty_order' => $value->sku_qty_plan_ambil,
				'sku_expdate' => $value->sku_expdate,
			]);

			$this->db->insert('delivery_order_progress', [
				'delivery_order_progress_id' => $deliveryOrderProgressId,
				'delivery_order_id' => $value->delivery_order_id,
				'status_progress_id' => '37E633E4-593C-4E05-8541-D121D974F167',
				'status_progress_nama' => 'in progress item request',
				'delivery_order_progress_create_who' => $this->session->userdata('pengguna_username'),
				'delivery_order_progress_create_tgl' => $pickingTglKirim,
			]);
		}

		//update status dan picking list id di delivery order batch
		// $this->db->query("UPDATE delivery_order_batch set delivery_order_batch_status  = 'in progress item request' , picking_list_id = $pickingId
		// where delivery_order_batch_id = $deliveryOrderBatchId");

		if ($lastUpdatedChecked['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Generate data picking list gagal',
				'lastUpdatedNew' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => $this->db->error()['message'],
				'lastUpdatedNew' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Generate data picking list berhasil',
				'lastUpdatedNew' => $lastUpdatedChecked['lastUpdatedNew']
			];
		}

		return $response;
	}

	public function generateKoreksiBKB($dataPost)
	{
		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "picking_validation",
			'whereField' => "picking_validation_id",
			'whereValue' => $dataPost->pickingValidationId,
			'fieldDateUpdate' => "picking_validation_tgl_update",
			'fieldWhoUpdate' => "picking_validation_who_update",
			'lastUpdated' => $dataPost->lastUpdated
		]);

		$this->db->update('picking_validation', ['is_generate_koreksi_bkb' => 1], ['picking_validation_id' => $dataPost->pickingValidationId]);
		$this->db->update('picking_validation_detail_5', ['picking_validation_detail_5_status' => 'Generate Done'], ['picking_validation_id' => $dataPost->pickingValidationId]);
		$this->db->query("exec proses_posting_stock_card 'PICKINGVALIDATION', '$dataPost->pickingValidationId', '" . $this->session->userdata('pengguna_username') . "'");

		if ($lastUpdatedChecked['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Generate data koreksi bkb salah input gagal',
				'lastUpdatedNew' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => 'Generate data koreksi bkb salah input gagal',
				'lastUpdatedNew' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Generate data koreksi bkb salah input berhasil',
				'lastUpdatedNew' => $lastUpdatedChecked['lastUpdatedNew']
			];
		}

		return $response;
	}

	public function generateKembaliLokasi($dataPost)
	{
		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "picking_validation",
			'whereField' => "picking_validation_id",
			'whereValue' => $dataPost->pickingValidationId,
			'fieldDateUpdate' => "picking_validation_tgl_update",
			'fieldWhoUpdate' => "picking_validation_who_update",
			'lastUpdated' => $dataPost->lastUpdated
		]);

		$this->db->update('picking_validation', ['is_generate_back_location' => 1], ['picking_validation_id' => $dataPost->pickingValidationId]);
		$this->db->update('picking_validation_detail_4', ['picking_validation_detail_4_status' => 'Generate Done'], ['picking_validation_id' => $dataPost->pickingValidationId]);
		// $this->db->query("exec proses_posting_stock_card 'PICKINGVALIDATION', '$dataPost->pickingValidationId', '" . $this->session->userdata('pengguna_username') . "'");

		if ($lastUpdatedChecked['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Generate data kembali ke lokasi gagal',
				'lastUpdatedNew' => null
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => 'Generate data kembali ke lokasi gagal',
				'lastUpdatedNew' => null
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Generate data kembali ke lokasi berhasil',
				'lastUpdatedNew' => $lastUpdatedChecked['lastUpdatedNew']
			];
		}

		return $response;
	}

	public function deleteDataPickingValidation($dataPost)
	{

		$this->db->trans_begin();

		$lastUpdatedChecked = checkLastUpdatedData((object) [
			'table' => "picking_validation",
			'whereField' => "picking_validation_id",
			'whereValue' => $dataPost->pickingValidationId,
			'fieldDateUpdate' => "picking_validation_tgl_update",
			'fieldWhoUpdate' => "picking_validation_who_update",
			'lastUpdated' => $dataPost->lastUpdated
		]);

		$this->db->delete('picking_validation', ['picking_validation_id' => $dataPost->pickingValidationId]);
		$this->db->delete('picking_validation_detail', ['picking_validation_id' => $dataPost->pickingValidationId]);
		$this->db->delete('picking_validation_detail_2', ['picking_validation_id' => $dataPost->pickingValidationId]);
		$getIdDetail3 = $this->db->select("picking_validation_detail_3_id")->from("picking_validation_detail_3")->where("picking_validation_id", $dataPost->pickingValidationId)->get()->result();
		if ($getIdDetail3) {
			foreach ($getIdDetail3 as $key => $value) {
				$this->db->delete('picking_validation_detail_3_do', ['picking_validation_detail_3_id' => $value->picking_validation_detail_3_id]);
			}
		}
		$this->db->delete('picking_validation_detail_3', ['picking_validation_id' => $dataPost->pickingValidationId]);
		$this->db->delete('picking_validation_detail_4', ['picking_validation_id' => $dataPost->pickingValidationId]);
		$this->db->delete('picking_validation_detail_5', ['picking_validation_id' => $dataPost->pickingValidationId]);

		return responseJson((object)[
			'lastUpdatedChecked' => $lastUpdatedChecked,
			'status' => 'Dihapus'
		]);

		// if ($this->db->trans_status() === FALSE) {
		// 	$this->db->trans_rollback();
		// 	return false;
		// } else {
		// 	$this->db->trans_commit();
		// 	return true;
		// }
	}

	public function getDataPickingValidationEdit($dataPost)
	{
		$header = $this->db->select("isnull(picking_validation_keterangan, '') as picking_validation_keterangan, picking_validation_status, is_generate_picking_list, is_generate_koreksi_bkb, is_generate_back_location, picking_order_id, picking_validation_tgl_update")->from("picking_validation")->where("picking_validation_id", $dataPost->pickingValidationId)->get()->row();

		$detail = $this->db->query("SELECT 
                                  picking_validation.delivery_order_batch_id,
                                  picking_order_plan.picking_order_plan_id,
                                  picking_validation_detail.sku_id,
                                  picking_validation_detail.qty_validasi_bagus,
                                  picking_validation_detail.qty_validasi_rusak
                                FROM picking_validation
                                LEFT JOIN picking_validation_detail ON picking_validation.picking_validation_id = picking_validation_detail.picking_validation_id
                                LEFT JOIN picking_order_plan ON picking_validation.picking_order_id = picking_order_plan.picking_order_id
                                  AND picking_validation_detail.sku_id = picking_order_plan.sku_id
                                WHERE picking_validation.picking_validation_id = '$dataPost->pickingValidationId'")->result();

		$detail2 = $this->db->query("SELECT 
                                  picking_validation.delivery_order_batch_id,
                                  picking_validation_detail_2.delivery_order_id,
                                  picking_validation_detail_2.delivery_order_no_urut_rute
                                FROM picking_validation_detail_2
                                LEFT JOIN picking_validation on picking_validation.picking_validation_id = picking_validation_detail_2.picking_validation_id
                                WHERE picking_validation.picking_validation_id = '$dataPost->pickingValidationId'
                                ORDER BY picking_validation_detail_2.delivery_order_no_urut_rute ASC")->result();

		$detail3 = $this->db->query("SELECT 
                                  picking_validation.delivery_order_batch_id,
                                  picking_validation_detail_3.sku_stock_id,
                                  picking_validation_detail_3.sku_expdate,
                                  sku.sku_id,
                                  sku.sku_kode,
                                  sku.sku_nama_produk,
                                  sku.sku_kemasan,
                                  sku.sku_satuan,
                                  picking_validation_detail_3.sku_qty_plan_ambil,
                                  picking_validation_detail_3.picking_validation_detail_3_status as status
                                FROM picking_validation_detail_3
                                LEFT JOIN picking_validation on picking_validation.picking_validation_id = picking_validation_detail_3.picking_validation_id
                                LEFT JOIN sku on picking_validation_detail_3.sku_id = sku.sku_id
                                WHERE picking_validation.picking_validation_id = '$dataPost->pickingValidationId'")->result();
		$detail3forDO = $this->db->query("SELECT 
                                    picking_validation.delivery_order_batch_id,
                                    picking_validation_detail_3.sku_stock_id,
                                    picking_validation_detail_3.sku_expdate,
                                    picking_validation_detail_3.sku_id,
                                    picking_validation_detail_3_do.delivery_order_id,
                                    picking_validation_detail_3_do.sku_qty_plan_ambil,
                                    delivery_order.delivery_order_no_urut_rute
                                  FROM picking_validation
                                  LEFT JOIN picking_validation_detail_3 on picking_validation.picking_validation_id = picking_validation_detail_3.picking_validation_id
                                  LEFT JOIN picking_validation_detail_3_do on picking_validation_detail_3.picking_validation_detail_3_id = picking_validation_detail_3_do.picking_validation_detail_3_id
                                  LEFT JOIN delivery_order on picking_validation_detail_3_do.delivery_order_id = delivery_order.delivery_order_id
                                  WHERE picking_validation.picking_validation_id = '$dataPost->pickingValidationId'")->result();
		$detail4 = $this->db->query("SELECT 
                                picking_validation.delivery_order_batch_id,
                                picking_validation_detail_4.sku_stock_id,
                                picking_validation_detail_4.sku_expdate,
                                sku.sku_id,
                                sku.sku_kode,
                                sku.sku_nama_produk,
                                sku.sku_kemasan,
                                sku.sku_satuan,
                                picking_validation_detail_4.sku_qty_plan_ambil,
                                picking_validation_detail_4.picking_validation_detail_4_status as status,
                                picking_validation_detail_4.is_type_picking_validation,
                                picking_validation_detail_4.delivery_oder_id,
                                picking_validation_detail_4.is_type_drop_picking_validation
                              FROM picking_validation_detail_4
                              LEFT JOIN picking_validation on picking_validation.picking_validation_id = picking_validation_detail_4.picking_validation_id
                              LEFT JOIN sku on picking_validation_detail_4.sku_id = sku.sku_id
                              WHERE picking_validation.picking_validation_id = '$dataPost->pickingValidationId'")->result();
		$detail5 = $this->db->query("SELECT 
                                picking_validation.delivery_order_batch_id,
                                picking_validation_detail_5.sku_stock_id,
                                picking_validation_detail_5.sku_expdate,
                                sku.sku_id,
                                sku.sku_kode,
                                sku.sku_nama_produk,
                                sku.sku_kemasan,
                                sku.sku_satuan,
                                picking_validation_detail_5.sku_qty_plan_ambil,
                                picking_validation_detail_5.picking_validation_detail_5_status as status
                              FROM picking_validation_detail_5
                              LEFT JOIN picking_validation on picking_validation.picking_validation_id = picking_validation_detail_5.picking_validation_id
                              LEFT JOIN sku on picking_validation_detail_5.sku_id = sku.sku_id
                              WHERE picking_validation.picking_validation_id = '$dataPost->pickingValidationId'")->result();

		$detail6 = $this->db->query("SELECT 
																picking_validation_detail_6.*
															FROM picking_validation_detail_6
															LEFT JOIN picking_validation on picking_validation.picking_validation_id = picking_validation_detail_6.picking_validation_id
															WHERE picking_validation.picking_validation_id = '$dataPost->pickingValidationId'")->result();
		$response = [
			'header' => $header,
			'detail' => $detail,
			'detail2' => $detail2,
			'detail3' => $detail3,
			'detail3forDO' => $detail3forDO,
			'detail4' => $detail4,
			'detail5' => $detail5,
			'detail6' => $detail6,
		];

		return $response;
	}

	public function getAkumulasiDoByProsedure($dataPost)
	{
		return $this->db->query("exec proses_picking_validation '$dataPost->fdjrId', '$dataPost->skuId', $dataPost->value, '$dataPost->noPPB'")->result_array();
	}

	public function getDataSKUBySkuId($dataPost)
	{
		return $this->db->query("SELECT
                                sku.sku_id,
                                sku.sku_kode,
                                sku.sku_nama_produk,
                                sku.sku_satuan,
                                sku.sku_kemasan,
                                FORMAT(picking_order_plan.sku_stock_expired_date, 'yyyy-MM-dd') as sku_expdate,
                                picking_order_plan.sku_stock_id
                            FROM picking_order
                            LEFT JOIN picking_order_plan
                                ON picking_order.picking_order_id = picking_order_plan.picking_order_id
                            LEFT JOIN picking_list
                                ON picking_order.picking_list_id = picking_list.picking_list_id
                            LEFT JOIN delivery_order_batch
                                ON picking_list.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
                            LEFT JOIN SKU
                                ON SKU.sku_id = picking_order_plan.sku_id
                            WHERE delivery_order_batch.delivery_order_batch_id = '$dataPost->fdjr'
                              AND picking_order.picking_order_id = '$dataPost->noPPB'
                              AND picking_order_plan.sku_id = '$dataPost->skuId'")->row();
	}

	public function getDataReport($fdjr, $ppb, $do_id)
	{
		$arr_fdjr = array();
		$arr_do = array();
		$arr_list_do = array();
		$do_id = explode(",", $do_id);

		$query_fdjr = $this->db->query("SELECT DISTINCT
                                    do.delivery_order_id,
                                    delivery_order_batch.delivery_order_batch_id,
                                    do.delivery_order_kode,
                                    do.delivery_order_status,
                                    delivery_order_batch.delivery_order_batch_kode,
                                    picking_order.picking_order_id,
                                    picking_order.picking_order_kode,
                                    serah_terima_kirim.serah_terima_kirim_id,
                                    serah_terima_kirim.serah_terima_kirim_kode,
                                    do.delivery_order_no_urut_rute,
                                    do.delivery_order_tipe_pembayaran,
                                    do.delivery_order_nominal_tunai,
                                    delivery_order_batch.karyawan_id,
																		picking_list.picking_list_kode,
                                    karyawan.karyawan_nama AS driver,
                                    FORMAT(delivery_order_batch.delivery_order_batch_tanggal, 'dd-MM-yyyy') AS delivery_order_batch_tanggal,
                                    FORMAT(delivery_order_batch.delivery_order_batch_tanggal_kirim, 'dd-MM-yyyy') AS delivery_order_batch_tanggal_kirim,
                                    FORMAT(do.delivery_order_tgl_buat_do, 'dd-MM-yyyy') AS delivery_order_tgl_buat_do,
                                    FORMAT(do.delivery_order_tgl_surat_jalan, 'dd-MM-yyyy') AS delivery_order_tgl_surat_jalan,
                                    FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_rencana_kirim,
                                    FORMAT(do.delivery_order_tgl_aktual_kirim, 'dd-MM-yyyy') AS delivery_order_tgl_aktual_kirim,
                                    picking_validation.picking_validation_keterangan,
                                    picking_validation.picking_validation_status,
                                    CASE
                                      WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_nama
                                      ELSE do.delivery_order_kirim_nama
                                    END AS delivery_order_kirim_nama,
                                    CASE
                                      WHEN do.delivery_order_tipe_layanan = 'Pickup Only' THEN do.delivery_order_ambil_alamat
                                      ELSE do.delivery_order_kirim_alamat
                                    END AS delivery_order_kirim_alamat,
                                    ISNULL(segment1.client_pt_segmen_nama, '') AS segment1,
                                    ISNULL(segment2.client_pt_segmen_nama, '') AS segment2,
                                    ISNULL(segment3.client_pt_segmen_nama, '') AS segment3,
                                    depo_detail.depo_detail_nama
                                  FROM delivery_order do
                                  LEFT JOIN delivery_order_batch
                                    ON do.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
                                  LEFT JOIN picking_list
                                    ON delivery_order_batch.picking_list_id = picking_list.picking_list_id
                                  LEFT JOIN picking_order
                                    ON delivery_order_batch.picking_list_id = picking_order.picking_list_id
                                  LEFT JOIN serah_terima_kirim
                                    ON picking_order.picking_order_id = serah_terima_kirim.picking_order_id
                                    AND delivery_order_batch.delivery_order_batch_id = serah_terima_kirim.delivery_order_batch_id
                                  LEFT JOIN picking_validation
                                    ON picking_validation.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
                                    AND picking_validation.picking_order_id = picking_order.picking_order_id
                                    AND picking_validation.serah_terima_kirim_id = serah_terima_kirim.serah_terima_kirim_id
                                  LEFT JOIN depo_detail
                                    ON depo_detail.depo_detail_id = delivery_order_batch.depo_detail_id
                                  LEFT JOIN karyawan
                                    ON karyawan.karyawan_id = delivery_order_batch.karyawan_id
                                  LEFT JOIN client_pt cp
                                    ON do.client_pt_id = cp.client_pt_id
                                  LEFT JOIN client_pt_segmen segment1
                                    ON cp.client_pt_segmen_id1 = segment1.client_pt_segmen_id
                                  LEFT JOIN client_pt_segmen segment2
                                    ON cp.client_pt_segmen_id2 = segment2.client_pt_segmen_id
                                  LEFT JOIN client_pt_segmen segment3
                                    ON cp.client_pt_segmen_id3 = segment3.client_pt_segmen_id
                                  WHERE delivery_order_batch.delivery_order_batch_id = '$fdjr'
                                  AND picking_order.picking_order_id = '$ppb'
                                  AND do.delivery_order_id IN (" . implode(",", $do_id) . ")
                                  ORDER BY do.delivery_order_no_urut_rute ASC");

		if ($query_fdjr->num_rows() > 0) {
			$arr_fdjr = $query_fdjr->result_array();
		}

		foreach ($arr_fdjr as $value) {
			$counter = 0;

			$jml_cetak = $this->db->query("SELECT ISNULL(delivery_order_jumlah_cetak, '0') AS delivery_order_jumlah_cetak FROM delivery_order WHERE delivery_order_id = '" . $value['delivery_order_id'] . "' ")->row(0)->delivery_order_jumlah_cetak;

			$counter = $jml_cetak + 1;

			$this->db->set("delivery_order_jumlah_cetak", $counter);
			$this->db->set("delivery_order_tgl_last_print", "GETDATE()", FALSE);
			$this->db->where("delivery_order_id", $value['delivery_order_id']);
			$this->db->update("delivery_order");

			$query_do_detail = $this->db->query("SELECT
                                      delivery_order.delivery_order_id,
                                      delivery_order.delivery_order_batch_id,
                                      delivery_order.delivery_order_kode,
                                      principal.principle_kode AS principal,
                                      ISNULL(CONVERT(varchar(50), sku.sku_id), '') AS sku_id,
                                      ISNULL(sku.sku_kode, '') AS sku_kode,
                                      ISNULL(sku.sku_nama_produk, '') AS sku_nama_produk,
                                      ISNULL(sku.sku_satuan, '') AS sku_satuan,
                                      ISNULL(sku.sku_kemasan, '') AS sku_kemasan,
                                      ISNULL(delivery_order_detail.sku_qty, '0') AS sku_qty,
                                      ISNULL(delivery_order_detail.sku_qty_kirim, '0') AS sku_qty_kirim
                                    FROM delivery_order_batch
                                    LEFT JOIN delivery_order
                                      ON delivery_order.delivery_order_batch_id = delivery_order_batch.delivery_order_batch_id
                                    LEFT JOIN delivery_order_detail
                                      ON delivery_order_detail.delivery_order_id = delivery_order.delivery_order_id
                                    LEFT JOIN SKU
                                      ON SKU.sku_id = delivery_order_detail.sku_id
                                    LEFT JOIN principle principal
                                      ON sku.principle_id = principal.principle_id
                                    LEFT JOIN depo_detail
                                      ON depo_detail.depo_detail_id = delivery_order_batch.depo_detail_id
                                    LEFT JOIN tipe_delivery_order
                                      ON tipe_delivery_order.tipe_delivery_order_id = delivery_order.tipe_delivery_order_id
                                    WHERE delivery_order_batch.delivery_order_batch_id = '$fdjr'
                                    AND delivery_order.delivery_order_id = '" . $value['delivery_order_id'] . "'
                                    ORDER BY ISNULL(sku.sku_kode, '') ASC");

			if ($query_do_detail->num_rows() > 0) {
				$query_do = $query_do_detail->result_array();

				$arr_do = [
					'delivery_order_id' => $value['delivery_order_id'],
					'delivery_order_kode' => $value['delivery_order_kode'],
					'delivery_order_batch_id' => $value['delivery_order_batch_id'],
					'delivery_order_batch_kode' => $value['delivery_order_batch_kode'],
					'delivery_order_tgl_buat_do' => $value['delivery_order_tgl_buat_do'],
					'delivery_order_tgl_surat_jalan' => $value['delivery_order_tgl_surat_jalan'],
					'delivery_order_tgl_rencana_kirim' => $value['delivery_order_tgl_rencana_kirim'],
					'delivery_order_tgl_aktual_kirim' => $value['delivery_order_tgl_aktual_kirim'],
					'delivery_order_kirim_nama' => $value['delivery_order_kirim_nama'],
					'delivery_order_kirim_alamat' => $value['delivery_order_kirim_alamat'],
					'delivery_order_status' => $value['delivery_order_status'],
					'detail' => $query_do
				];
				array_push($arr_list_do, $arr_do);
			}
		}

		$response = [
			'dataFDJR' => $arr_fdjr,
			'dataDO' => $arr_list_do
		];

		return $response;
	}



	public function GetKendaraanById($id)
	{
		$this->db->select("*")
			->from("kendaraan")
			->where("kendaraan_id", $id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}
	public function GetPengemudiById($id)
	{
		$this->db->select("*")
			->from("karyawan")
			->where("karyawan_id", $id);
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}
	public function GetAssigmentDriver($id)
	{

		$query = $this->db->query("SELECT dob_log.* from delivery_order_batch_log dob_log left join delivery_order_batch dob on dob.delivery_order_batch_id = dob_log.delivery_order_batch_id where dob_log.delivery_order_batch_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->row();
		}

		return $query;
	}
	public function GetHeaderFDJRById($id)
	{
		$this->db->select("*")
			->from("delivery_order_batch")
			->where("delivery_order_batch_id", $id)
			->order_by("delivery_order_batch_kode");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}
}
