<?php

date_default_timezone_set('Asia/Jakarta');

class M_ProsesOpname extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();

		$this->load->model('M_Vrbl');
	}

	public function getDataUserLogin()
	{
		return $this->db->select('karyawan.karyawan_nama,
                              kl.karyawan_level_nama,
                              depo.depo_nama')
			->from("karyawan")
			->join("karyawan_level kl", "karyawan.karyawan_level_id = kl.karyawan_level_id", "left")
			->join("depo", "karyawan.depo_id = depo.depo_id", "left")
			->where("karyawan.karyawan_id", $this->session->userdata('karyawan_id'))->get()->row();
	}

	public function getDepoPrefix($depo_id)
	{
		$listDoBatch = $this->db->select("*")->from('depo')->where('depo_id', $depo_id)->get();
		return $listDoBatch->row();
	}

	public function getDataOpnameByStatus()
	{
		$this->db->select("topp.tr_opname_plan_id as id, 
                              topp.tr_opname_plan_kode as kode,
                              FORMAT(topp.tr_opname_plan_tgl_start, 'yyyy-MM-dd HH:mm') as tgl_start,
                              ISNULL(FORMAT(topp.tr_opname_plan_tgl_end, 'yyyy-MM-dd HH:mm'), '-') as tgl_end,
                              too.tipe_opname_nama as tipe_stok,
                              topp.tr_opname_plan_status as status,
                              topp.depo_detail_id,
							  COUNT(CASE WHEN topd.tr_opname_plan_detail_status = 'Completed' THEN topd.tr_opname_plan_detail_status ELSE NULL END) AS tot_status,
							  COUNT(DISTINCT topd.tr_opname_plan_detail_id) AS tot")
			->from("tr_opname_plan topp")
			->join("tipe_opname too", "topp.tipe_opname_id = too.tipe_opname_id", "left")
			->join("tr_opname_plan_detail topd", "topd.tr_opname_plan_id = topp.tr_opname_plan_id", "left");
		if ($this->session->userdata('karyawan_id') != "AD030BE3-7D9E-4A66-B985-D6085F623DA2") {
			$this->db->where("topp.karyawan_id_penanggungjawab", $this->session->userdata('karyawan_id'));
		}
		$this->db->where_in("topp.tr_opname_plan_status", ["In Progress", "Completed", "Waiting For Final Approve", "In Progress Revision"]);
		$this->db->group_by("topp.tr_opname_plan_id, 
		topp.tr_opname_plan_kode,
		FORMAT(topp.tr_opname_plan_tgl_start, 'yyyy-MM-dd HH:mm'),
		ISNULL(FORMAT(topp.tr_opname_plan_tgl_end, 'yyyy-MM-dd HH:mm'), '-'),
		too.tipe_opname_nama,
		topp.tr_opname_plan_status,
		topp.depo_detail_id");
		$this->db->order_by("topp.tr_opname_plan_kode", "DESC");
		$data =  $this->db->get()->result();

		$result = [];
		if ($data) {
			foreach ($data as $key => $value) {
				$persent = $value->tot == 0 ? 0 : ($value->tot_status / $value->tot) * 100;
				array_push($result, [
					'id' => $value->id,
					'kode' => $value->kode,
					'tgl_start' => $value->tgl_start,
					'tgl_end' => $value->tgl_end,
					'tipe_stok' => $value->tipe_stok,
					'status' => $value->status,
					'depo_detail_id' => $value->depo_detail_id,
					'persent' => (int)$persent
				]);
			}
		}

		// if (empty($data)) {
		// 	return $data;
		// } else {
		// 	$result = [];
		// 	$params = array();


		// 	for ($i = 0; $i < count($data); $i++) {
		// 		$params[$i] = "'" . $data[$i]->id . "'";
		// 	}

		// 	$res1 = $this->db->query("SELECT
		//                   tr_opname_plan_id as opnameId,
		//                   COUNT(CASE WHEN tr_opname_plan_detail_status = 'Completed' THEN tr_opname_plan_detail_status ELSE NULL END) AS tot_status,
		//                   COUNT(DISTINCT tr_opname_plan_detail_id) AS tot
		//                 from tr_opname_plan_detail
		//                 where tr_opname_plan_id IN (" . implode(',', $params) . ")
		//                 group by tr_opname_plan_id")->result();



		// 	foreach ($res1 as $key => $value) {
		// 		$persent = ($value->tot_status / $value->tot) * 100;
		// 		array_push($result, ['opnameId' => $value->opnameId, 'persent' => (int)$persent]);
		// 	}

		// 	$response = [
		// 		'dataFix' => $data,
		// 		'dataPersent' => $result
		// 	];
		// 	return $response;
		// }

		return $result;
	}

	public function getDaftarSuratKerjaDetail($type)
	{
		if ($type == "Stock Opname") {
			return $this->db->select("topp.tr_opname_plan_id as id,
                                topp.tr_opname_plan_kode as kode,
                                too.tipe_opname_nama as tipe_stock,
                                isnull(cw.client_wms_nama, 'All') as perusahaan,
                                isnull(p.principle_nama, 'All') as principle,
                                topp.tipe_stok as jenis_stok,
                                topp.tr_opname_plan_status as status")
				->from("tr_opname_plan topp")
				->join("client_wms cw", "topp.client_wms_id = cw.client_wms_id", "left")
				->join("principle p", "topp.principle_id = p.principle_id", "left")
				->join("tipe_opname too", "topp.tipe_opname_id = too.tipe_opname_id", "left")
				->where("topp.depo_id", $this->session->userdata('depo_id'))
				->where("topp.tr_opname_plan_status", "Approved")
				->where("topp.tr_opname_plan_tgl_start", NULL)->get()->result();
		}
	}

	public function updateLaksanakanOpname($id)
	{

		$this->db->set("tr_opname_plan_detail_status", "In Progress");
		$this->db->where("tr_opname_plan_id", $id);
		$this->db->update("tr_opname_plan_detail");


		$this->db->set("tr_opname_plan_status", "In Progress");
		$this->db->set("tr_opname_plan_tgl_start", "GETDATE()", FALSE);
		$this->db->where("tr_opname_plan_id", $id);
		return $this->db->update("tr_opname_plan");
	}

	public function getDataDetailOpnameBySuratKerjaHeader($id)
	{
		/** get header **/

		return $this->db->select("topp.tr_opname_plan_id as id,
                                topp.tr_opname_plan_kode as kode,
                                too.tipe_opname_nama as tipe_stock,
                                isnull(cw.client_wms_nama, 'All') as perusahaan,
                                isnull(p.principle_nama, 'All') as principle,
                                topp.tipe_stok as jenis_stok,
                                topp.tr_opname_plan_status as status,
                                dd.depo_detail_id,
                                dd.depo_detail_nama as area")
			->from("tr_opname_plan topp")
			->join("client_wms cw", "topp.client_wms_id = cw.client_wms_id", "left")
			->join("principle p", "topp.principle_id = p.principle_id", "left")
			->join("tipe_opname too", "topp.tipe_opname_id = too.tipe_opname_id", "left")
			->join("depo_detail dd", "topp.depo_detail_id = dd.depo_detail_id", "left")
			->where("topp.tr_opname_plan_id", $id)->get()->row();
	}

	public function getDataDetailOpnameBySuratKerjaDetail($id)
	{
		/** get header **/

		return $this->db->query("SELECT
                              topp.tr_opname_plan_kode,
                              rld.rak_lajur_detail_nama,
                              topd.tr_opname_plan_detail_status as status,
							  topd.tr_opname_plan_detail_id
                            FROM tr_opname_plan_detail topd 
                            LEFT JOIN tr_opname_plan topp ON topd.tr_opname_plan_id = topp.tr_opname_plan_id
                            LEFT JOIN rak_lajur_detail rld ON topd.rak_lajur_detail_id = rld.rak_lajur_detail_id
                            where topd.tr_opname_plan_id = '$id'
                            order by rld.rak_lajur_detail_nama")->result_array();
	}

	public function checkKodeLokasi($dataPost, $kodeLokasi)
	{
		return $this->db->select("rld.rak_lajur_detail_id, topd.tr_opname_plan_detail_id, topd.tr_opname_plan_detail_status")
			->from("tr_opname_plan_detail topd")
			->join("rak_lajur_detail rld", "topd.rak_lajur_detail_id = rld.rak_lajur_detail_id", "left")
			->join("rak", "rld.rak_id = rak.rak_id", "left")
			->where("topd.tr_opname_plan_id", $dataPost->opnameId)
			->where("rak.depo_id", $this->session->userdata('depo_id'))
			// ->where("rak.depo_detail_id", $dataPost->depo_detail_id)
			->where("rld.rak_lajur_detail_nama", $kodeLokasi)->get()->row();
	}

	public function insertOpnamePlanDetail($dataPost, $kodeLokasi, $status)
	{
		$rak_lajur_detail_id = $this->db->select("rak_lajur_detail_id")->from("rak_lajur_detail")->where("rak_lajur_detail_nama", $kodeLokasi)->get()->row()->rak_lajur_detail_id;
		$tr_opname_plan = $this->db->select("client_wms_id, principle_id")->from("tr_opname_plan")->where("tr_opname_plan_id", $dataPost->opnameId)->get()->row();

		$this->db->set("tr_opname_plan_detail_id", "NEWID()", FALSE);
		$this->db->set("tr_opname_plan_id", $dataPost->opnameId);
		$this->db->set("rak_lajur_detail_id", $rak_lajur_detail_id);
		$this->db->set("tr_opname_plan_detail_status", $status);
		$this->db->set("principle_id", $tr_opname_plan->principle_id);
		$this->db->set("client_wms_id", $tr_opname_plan->client_wms_id);

		return $this->db->insert("tr_opname_plan_detail");
	}

	public function checkKodePallet($dataPost, $kodePallet, $typeOpname, $status)
	{
		$getOpnameDetailId = $this->getOpnameDetailId($dataPost);

		$type = ($typeOpname == "revisi") ? "tr_opname_plan_detail2" : "tr_opname_plan_detail2_temp";
		$type2 = ($typeOpname == "revisi") ? "tr_opname_plan_detail3" : "tr_opname_plan_detail3_temp";

		$table = $getOpnameDetailId->tr_opname_plan_detail_status == "Completed" ? "tr_opname_plan_detail2" : $type;
		$table2 = $getOpnameDetailId->tr_opname_plan_detail_status == "Completed" ? "tr_opname_plan_detail3" : $type2;

		if ($dataPost->typeScan === "pallet") {

			// $chkData =  $this->db->select("topd.tr_opname_plan_detail2_id, pallet.pallet_id, ")->from($table . " topd")
			// 	->join("pallet", "topd.pallet_id = pallet.pallet_id", "left")
			// 	->where("topd.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
			// 	->where("topd.tr_opname_plan_id", $dataPost->opnameId)
			// 	->where("pallet.pallet_kode", $kodePallet)->get()->row();

			$chkData = $this->db->select("pallet_kode as kode, pallet_id")
				->from("pallet")
				->where("depo_id", $this->session->userdata('depo_id'))
				->where("pallet_is_aktif", 1)
				->where("pallet_kode", $kodePallet)->get()->row();

			// $chkData = $this->db->select("pal.pallet_kode as kode, pal.pallet_id")
			// 	->from("rak_lajur_detail rld")
			// 	->join("rak_lajur_detail_pallet rldp", "rld.rak_lajur_detail_id = rldp.rak_lajur_detail_id", "left")
			// 	->join("pallet pal", "pal.pallet_id = rldp.pallet_id", "left")
			// 	->where("rld.rak_lajur_detail_nama", $dataPost->lokasi)->get()->row();

			if (empty($chkData)) {
				$response = [
					'type' => 201,
					'message' => "Pallet <strong>" . $kodePallet . "</strong> tidak ditemukan",
					'kode' => $kodePallet,
					'statusOpname' => $status,
					'data' => []
				];
			} else {
				if ($status == "In Progress") {
					$chkExist =  $this->db->select("*")->from("tr_opname_plan_detail2_temp")
						->where("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
						->where("tr_opname_plan_id", $dataPost->opnameId)
						->where("pallet_id", $chkData->pallet_id)->get()->row();

					if (empty($chkExist)) {
						$this->db->set("tr_opname_plan_detail2_id", "NewID()", FALSE);
						$this->db->set("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id);
						$this->db->set("tr_opname_plan_id", $dataPost->opnameId);
						$this->db->set("pallet_id", $chkData->pallet_id);
						$this->db->set("tr_opname_plan_detail2_status", "In Progress");

						$this->db->insert("tr_opname_plan_detail2_temp");
					}
				}

				if ($getOpnameDetailId->tr_opname_plan_detail_status != "Completed") {
					$row =  $this->db->select("topd.tr_opname_plan_detail2_id, pallet.pallet_id, ")->from($table . " topd")
						->join("pallet", "topd.pallet_id = pallet.pallet_id", "left")
						->where("topd.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
						->where("topd.tr_opname_plan_id", $dataPost->opnameId)
						->where("pallet.pallet_kode", $kodePallet)->get()->row();

					//update is_valid jadi 1
					$this->db->set("is_valid", 1);
					$this->db->where("tr_opname_plan_detail2_id", $row->tr_opname_plan_detail2_id);

					$this->db->update($type);
				}

				$data =  $this->db->select("opnameTemp.tr_opname_plan_detail2_id,
															pallet.pallet_id, 
															pallet.pallet_kode,
															CASE 
																WHEN opnameTemp.is_valid = 1 THEN 'Valid'
																WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
																WHEN opnameTemp.is_valid is NULL THEN ''
															END As status,
															opnameTemp.tr_opname_plan_detail2_status as status_opname,
															opnameTemp.is_new_pallet,
															count(opnameDetail3.tr_opname_plan_detail3_id) as totalSKU")
					->from($table . " opnameTemp")
					->join($table2 . " opnameDetail3", "opnameTemp.tr_opname_plan_detail2_id = opnameDetail3.tr_opname_plan_detail2_id", "left")
					->join("pallet", "opnameTemp.pallet_id = pallet.pallet_id", "left")
					->where("opnameTemp.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
					->where("opnameTemp.tr_opname_plan_id", $dataPost->opnameId)
					->group_by("opnameTemp.tr_opname_plan_detail2_id,
											pallet.pallet_id, 
											pallet.pallet_kode,
											CASE 
												WHEN opnameTemp.is_valid = 1 THEN 'Valid'
												WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
												WHEN opnameTemp.is_valid is NULL THEN ''
											END,
											opnameTemp.tr_opname_plan_detail2_status,
											opnameTemp.is_new_pallet")
					->get()->result();

				$response =  [
					'type' => 200,
					'message' => "Berhasil scan pallet",
					'kode' => $kodePallet,
					'statusRak' => $getOpnameDetailId->tr_opname_plan_detail_status,
					'statusOpname' => $status,
					'data' => $data
				];
			}

			return $response;
		}

		if ($dataPost->typeScan === "newPallet") {
			// $chkData =  $this->db->select("*")->from("pallet")
			//   ->where("pallet_kode", $kodePallet)->get()->row();

			$chkData = $this->db->query("select pallet_id, pallet_kode, pallet_jenis_id, pallet_is_aktif
                                  from(
                                      select pallet_id, pallet_kode, pallet_jenis_id, pallet_is_aktif
                                      from pallet
                                      where pallet_is_aktif = 1
                                      union
                                      select pallet_generate_detail2_id as pallet_id, pallet_generate_detail2_kode as pallet_kode, pallet_jenis_id, 
									  pallet_generate_detail2_is_aktif as pallet_is_aktif
                                      from pallet_generate_detail2 a
                                  inner join pallet_generate b on a.pallet_generate_id = b.pallet_generate_id
                                      where pallet_generate_detail2_is_aktif = 0
                                      ) as a
                                  WHERE pallet_kode = '$kodePallet'")->row();

			return $this->addNewKodePallet($chkData, $type, $type2, $dataPost, $getOpnameDetailId, $kodePallet, $status, $table, $table2);
		}
	}

	public function addNewKodePallet($chkData, $type, $type2, $dataPost, $getOpnameDetailId, $kodePallet, $status, $table, $table2)
	{
		//kurang bagian sini, check dan insert ke opname pallet detail 2

		if (empty($chkData)) {
			$response = [
				'type' => 201,
				'message' => "Pallet <strong>" . $kodePallet . "</strong> tidak ditemukan",
				'kode' => $kodePallet,
				'statusOpname' => $status,
				'data' => []
			];
		} else {
			if ($chkData->pallet_is_aktif == 1) {
				$chkDetail2 =  $this->db->select("opnameDetail2.tr_opname_plan_detail2_id,
				                pallet.pallet_id, 
				                pallet.pallet_kode,
				                CASE 
				                  WHEN opnameDetail2.is_valid = 1 THEN 'Valid'
				                  WHEN opnameDetail2.is_valid = 0 THEN 'Invalid'
				                  WHEN opnameDetail2.is_valid is NULL THEN ''
				                END As status,
				                opnameDetail2.tr_opname_plan_detail2_status as status_opname,
				                opnameDetail2.is_new_pallet,
								count(opnameDetail3.tr_opname_plan_detail3_id) as totalSKU,
								rakDetail.rak_lajur_detail_nama")
					->from("tr_opname_plan_detail2 opnameDetail2")
					->join("tr_opname_plan_detail3 opnameDetail3", "opnameDetail2.tr_opname_plan_detail2_id = opnameDetail3.tr_opname_plan_detail2_id", "left")
					->join("tr_opname_plan_detail opnameDetail", "opnameDetail2.tr_opname_plan_detail_id = opnameDetail.tr_opname_plan_detail_id", "left")
					->join("rak_lajur_detail rakDetail", "opnameDetail.rak_lajur_detail_id = rakDetail.rak_lajur_detail_id", "left")
					->join("pallet", "opnameDetail2.pallet_id = pallet.pallet_id", "left")
					->where_not_in("opnameDetail2.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
					->where("opnameDetail2.tr_opname_plan_id", $dataPost->opnameId)
					->where("opnameDetail2.pallet_id", $chkData->pallet_id)
					->group_by("opnameDetail2.tr_opname_plan_detail2_id,
											pallet.pallet_id, 
											pallet.pallet_kode,
											CASE 
												WHEN opnameDetail2.is_valid = 1 THEN 'Valid'
												WHEN opnameDetail2.is_valid = 0 THEN 'Invalid'
												WHEN opnameDetail2.is_valid is NULL THEN ''
											END,
											opnameDetail2.tr_opname_plan_detail2_status,
											opnameDetail2.is_new_pallet,
											rakDetail.rak_lajur_detail_nama")
					->get()->row();

				if (empty($chkDetail2)) {
					$chkDetail2TempIN =  $this->db->select("opnameTemp.tr_opname_plan_detail2_id,
															pallet.pallet_id, 
															pallet.pallet_kode,
															CASE 
																WHEN opnameTemp.is_valid = 1 THEN 'Valid'
																WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
																WHEN opnameTemp.is_valid is NULL THEN ''
															END As status,
															opnameTemp.tr_opname_plan_detail2_status as status_opname,
															opnameTemp.is_new_pallet,
															count(opnameDetail3.tr_opname_plan_detail3_id) as totalSKU,
															rakDetail.rak_lajur_detail_nama")
						->from($table . " opnameTemp")
						->join($table2 . " opnameDetail3", "opnameTemp.tr_opname_plan_detail2_id = opnameDetail3.tr_opname_plan_detail2_id", "left")
						->join("tr_opname_plan_detail opnameDetail", "opnameTemp.tr_opname_plan_detail_id = opnameDetail.tr_opname_plan_detail_id", "left")
						->join("rak_lajur_detail rakDetail", "opnameDetail.rak_lajur_detail_id = rakDetail.rak_lajur_detail_id", "left")
						->join("pallet", "opnameTemp.pallet_id = pallet.pallet_id", "left")
						->where_in("opnameTemp.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
						->where("opnameTemp.tr_opname_plan_id", $dataPost->opnameId)
						->where("opnameTemp.pallet_id", $chkData->pallet_id)
						->group_by("opnameTemp.tr_opname_plan_detail2_id,
											pallet.pallet_id, 
											pallet.pallet_kode,
											CASE 
												WHEN opnameTemp.is_valid = 1 THEN 'Valid'
												WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
												WHEN opnameTemp.is_valid is NULL THEN ''
											END,
											opnameTemp.tr_opname_plan_detail2_status,
											opnameTemp.is_new_pallet,
											rakDetail.rak_lajur_detail_nama")
						->get()->row();

					if (empty($chkDetail2TempIN)) {


						$chkDetail2Temp =  $this->db->select("opnameTemp.tr_opname_plan_detail2_id,
					pallet.pallet_id, 
					pallet.pallet_kode,
					CASE 
						WHEN opnameTemp.is_valid = 1 THEN 'Valid'
						WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
						WHEN opnameTemp.is_valid is NULL THEN ''
					END As status,
					opnameTemp.tr_opname_plan_detail2_status as status_opname,
					opnameTemp.is_new_pallet,
					count(opnameDetail3.tr_opname_plan_detail3_id) as totalSKU,
					rakDetail.rak_lajur_detail_nama")
							->from($table . " opnameTemp")
							->join($table2 . " opnameDetail3", "opnameTemp.tr_opname_plan_detail2_id = opnameDetail3.tr_opname_plan_detail2_id", "left")
							->join("tr_opname_plan_detail opnameDetail", "opnameTemp.tr_opname_plan_detail_id = opnameDetail.tr_opname_plan_detail_id", "left")
							->join("rak_lajur_detail rakDetail", "opnameDetail.rak_lajur_detail_id = rakDetail.rak_lajur_detail_id", "left")
							->join("pallet", "opnameTemp.pallet_id = pallet.pallet_id", "left")
							->where_not_in("opnameTemp.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
							->where("opnameTemp.tr_opname_plan_id", $dataPost->opnameId)
							->where("opnameTemp.pallet_id", $chkData->pallet_id)
							->group_by("opnameTemp.tr_opname_plan_detail2_id,
							pallet.pallet_id, 
							pallet.pallet_kode,
							CASE 
								WHEN opnameTemp.is_valid = 1 THEN 'Valid'
								WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
								WHEN opnameTemp.is_valid is NULL THEN ''
							END,
							opnameTemp.tr_opname_plan_detail2_status,
							opnameTemp.is_new_pallet,
							rakDetail.rak_lajur_detail_nama")
							->get()->row();

						if (empty($chkDetail2Temp)) {
							if ($status == "In Progress") {
								$chkExist =  $this->db->select("*")->from("tr_opname_plan_detail2_temp")
									->where("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
									->where("tr_opname_plan_id", $dataPost->opnameId)
									->where("pallet_id", $chkData->pallet_id)->get()->row();

								if (empty($chkExist)) {
									$this->db->set("tr_opname_plan_detail2_id", "NewID()", FALSE);
									$this->db->set("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id);
									$this->db->set("tr_opname_plan_id", $dataPost->opnameId);
									$this->db->set("pallet_id", $chkData->pallet_id);
									$this->db->set("tr_opname_plan_detail2_status", "In Progress");

									$this->db->insert("tr_opname_plan_detail2_temp");
								}
							}

							if ($getOpnameDetailId->tr_opname_plan_detail_status != "Completed") {
								$row =  $this->db->select("topd.tr_opname_plan_detail2_id, pallet.pallet_id, ")->from($table . " topd")
									->join("pallet", "topd.pallet_id = pallet.pallet_id", "left")
									->where("topd.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
									->where("topd.tr_opname_plan_id", $dataPost->opnameId)
									->where("pallet.pallet_kode", $kodePallet)->get()->row();

								//update is_valid jadi 1
								$this->db->set("is_valid", 1);
								$this->db->where("tr_opname_plan_detail2_id", $row->tr_opname_plan_detail2_id);

								$this->db->update($type);
							}

							$data =  $this->db->select("opnameTemp.tr_opname_plan_detail2_id,
						pallet.pallet_id, 
						pallet.pallet_kode,
						CASE 
							WHEN opnameTemp.is_valid = 1 THEN 'Valid'
							WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
							WHEN opnameTemp.is_valid is NULL THEN ''
						END As status,
						opnameTemp.tr_opname_plan_detail2_status as status_opname,
						opnameTemp.is_new_pallet,
						count(opnameDetail3.tr_opname_plan_detail3_id) as totalSKU")
								->from($table . " opnameTemp")
								->join($table2 . " opnameDetail3", "opnameTemp.tr_opname_plan_detail2_id = opnameDetail3.tr_opname_plan_detail2_id", "left")
								->join("pallet", "opnameTemp.pallet_id = pallet.pallet_id", "left")
								->where("opnameTemp.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
								->where("opnameTemp.tr_opname_plan_id", $dataPost->opnameId)
								->where("opnameTemp.pallet_id", $chkData->pallet_id)
								->group_by("opnameTemp.tr_opname_plan_detail2_id,
									pallet.pallet_id, 
									pallet.pallet_kode,
									CASE 
										WHEN opnameTemp.is_valid = 1 THEN 'Valid'
										WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
										WHEN opnameTemp.is_valid is NULL THEN ''
									END,
									opnameTemp.tr_opname_plan_detail2_status,
									opnameTemp.is_new_pallet")
								->get()->row();

							$countData1 = $this->db->select("COUNT(*) as count1")->from("tr_opname_plan_detail2_temp a")->where("a.tr_opname_plan_detail2_status = 'Completed'")->where("a.tr_opname_plan_detail_id = '$getOpnameDetailId->tr_opname_plan_detail_id'")->get()->row()->count1;
							$countData2 = $this->db->select("COUNT(*) as count2")->from("tr_opname_plan_detail2_temp a")->where("a.tr_opname_plan_detail_id = '$getOpnameDetailId->tr_opname_plan_detail_id'")->get()->row()->count2;

							// 1 = data sama, 0 = data tidak sama
							$checkCount = $countData1 == $countData2 ? 1 : 0;

							$response =  [
								'type' => 200,
								'message' => "Berhasil scan pallet",
								'kode' => $kodePallet,
								// 'statusRak' => $getOpnameDetailId->tr_opname_plan_detail_status,
								'statusOpname' => $status,
								'data' => $data,
								'tr_opname_plan_detail_id' => $getOpnameDetailId->tr_opname_plan_detail_id,
								'count' => $checkCount
							];
							// $response = [
							// 	'type' => 201,
							// 	'message' => "Pallet <strong>" . $kodePallet . "</strong> sudah digunakan",
							// 	'kode' => $kodePallet,
							// 	'statusOpname' => $status,
							// 	'data' => []
							// ];
						} else {
							$response = [
								'type' => 202,
								'message' => "Pallet <strong>" . $kodePallet . "</strong> sudah terdaftar di lokasi <strong>" . $chkDetail2Temp->rak_lajur_detail_nama . "</strong>",
								'kode' => $kodePallet,
								'statusOpname' => $status,
								'data' => []
							];
						}
					} else {
						$response = [
							'type' => 202,
							'message' => "Pallet <strong>" . $kodePallet . "</strong> sudah ada",
							'kode' => $kodePallet,
							'statusOpname' => $status,
							'data' => []
						];
					}
				} else {
					$response = [
						'type' => 202,
						'message' => "Pallet <strong>" . $kodePallet . "</strong> sudah terdaftar di lokasi <strong>" . $chkDetail2->rak_lajur_detail_nama . "</strong>",
						'kode' => $kodePallet,
						'statusOpname' => $status,
						'data' => []
					];
				}
			} else {

				$raklajurDetailId =  $this->db->select("rld.rak_lajur_detail_id, rak.depo_id, rak.depo_detail_id")
					->from("rak_lajur_detail rld")
					->join('rak', 'rld.rak_id = rak.rak_id', 'left')
					->where("rld.rak_lajur_detail_nama", $dataPost->rakNama)->get()->row();

				$chkDataInTemp = $this->db->select("*")->from($type)->where("tr_opname_plan_id", $dataPost->opnameId)->where("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)->where("pallet_id", $chkData->pallet_id)->get()->num_rows();
				if ($chkDataInTemp == 0) {
					$this->db->set("tr_opname_plan_detail2_id", "NewID()", FALSE);
					$this->db->set("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id);
					$this->db->set("tr_opname_plan_id", $dataPost->opnameId);
					$this->db->set("pallet_id", $chkData->pallet_id);
					$this->db->set("tr_opname_plan_detail2_status", "In Progress");
					$this->db->set("is_valid", 1);
					$this->db->set("is_new_pallet", 1);

					$this->db->insert($type);

					$this->db->update("pallet_generate_detail2", ['pallet_generate_detail2_is_aktif' => 1], ['pallet_generate_detail2_id' => $chkData->pallet_id]);
					$this->db->insert('pallet', [
						'pallet_id' => $chkData->pallet_id,
						'pallet_jenis_id' => $chkData->pallet_jenis_id,
						'depo_id' => $this->session->userdata('depo_id'),
						'rak_lajur_detail_id' => $raklajurDetailId->rak_lajur_detail_id,
						'pallet_kode' => $chkData->pallet_kode,
						'pallet_tanggal_create' => date('Y-m-d H:i:s'),
						'pallet_who_create' => $this->session->userdata('pengguna_username'),
						'pallet_is_aktif' => 1,
						'pallet_is_lock' => 1,
						'pallet_is_lock_reason' => 'Locked for Stock Opname'
					]);

					$data =  $this->db->select("opnameTemp.tr_opname_plan_detail2_id,
                              pallet.pallet_id, 
                              pallet.pallet_kode,
                              CASE 
                                WHEN opnameTemp.is_valid = 1 THEN 'Valid'
                                WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
                                WHEN opnameTemp.is_valid is NULL THEN ''
                              END As status,
                              opnameTemp.tr_opname_plan_detail2_status as status_opname,
                              opnameTemp.is_new_pallet,
															count(opnameDetail3.tr_opname_plan_detail3_id) as totalSKU")
						->from($type . " opnameTemp")
						->join($type2 . " opnameDetail3", "opnameTemp.tr_opname_plan_detail2_id = opnameDetail3.tr_opname_plan_detail2_id", "left")
						->join("pallet", "opnameTemp.pallet_id = pallet.pallet_id", "left")
						->where("opnameTemp.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
						->where("opnameTemp.tr_opname_plan_id", $dataPost->opnameId)
						->where("opnameTemp.pallet_id", $chkData->pallet_id)
						->group_by("opnameTemp.tr_opname_plan_detail2_id,
												pallet.pallet_id, 
												pallet.pallet_kode,
												CASE 
													WHEN opnameTemp.is_valid = 1 THEN 'Valid'
													WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
													WHEN opnameTemp.is_valid is NULL THEN ''
												END,
												opnameTemp.tr_opname_plan_detail2_status,
												opnameTemp.is_new_pallet")
						->get()->row();

					$countData1 = $this->db->select("COUNT(*) as count1")->from("tr_opname_plan_detail2_temp a")->where("a.tr_opname_plan_detail2_status = 'Completed'")->where("a.tr_opname_plan_detail_id = '$getOpnameDetailId->tr_opname_plan_detail_id'")->get()->row()->count1;
					$countData2 = $this->db->select("COUNT(*) as count2")->from("tr_opname_plan_detail2_temp a")->where("a.tr_opname_plan_detail_id = '$getOpnameDetailId->tr_opname_plan_detail_id'")->get()->row()->count2;

					// 1 = data sama, 0 = data tidak sama
					$checkCount = $countData1 == $countData2 ? 1 : 0;

					$response =  [
						'type' => 200,
						'message' => "Berhasil scan pallet",
						'kode' => $kodePallet,
						'statusOpname' => $status,
						'data' => $data,
						'tr_opname_plan_detail_id' => $getOpnameDetailId->tr_opname_plan_detail_id,
						'count' => $checkCount
					];
				} else {
					$response = [
						'type' => 202,
						'message' => "Pallet <strong>" . $kodePallet . "</strong> sudah ada",
						'kode' => $kodePallet,
						'statusOpname' => $status,
						'data' => []
					];
				}
			}
		}

		return $response;
	}

	// public function updatePalletProsesOpnameByRak($dataPost)
	// {
	// 	$getOpnameDetailId = $this->getOpnameDetailId($dataPost);

	// 	$this->db->set("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id);
	// 	$this->db->where("tr_opname_plan_detail2_id", $dataPost->tr_opname_plan_detail2_id);
	// 	$this->db->update("tr_opname_plan_detail2_temp");

	// 	$data =  $this->db->select("opnameTemp.tr_opname_plan_detail2_id,
	// 															pallet.pallet_id, 
	// 															pallet.pallet_kode,
	// 															CASE 
	// 																WHEN opnameTemp.is_valid = 1 THEN 'Valid'
	// 																WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
	// 																WHEN opnameTemp.is_valid is NULL THEN ''
	// 															END As status,
	// 															opnameTemp.tr_opname_plan_detail2_status as status_opname,
	// 															opnameTemp.is_new_pallet,
	// 															count(opnameDetail3.tr_opname_plan_detail3_id) as totalSKU")
	// 		->from("tr_opname_plan_detail2_temp opnameTemp")
	// 		->join("tr_opname_plan_detail3_temp opnameDetail3", "opnameTemp.tr_opname_plan_detail2_id = opnameDetail3.tr_opname_plan_detail2_id", "left")
	// 		->join("pallet", "opnameTemp.pallet_id = pallet.pallet_id", "left")
	// 		->where("opnameTemp.tr_opname_plan_detail2_id", $dataPost->tr_opname_plan_detail2_id)
	// 		// ->where("opnameTemp.tr_opname_plan_id", $dataPost->opnameId)
	// 		// ->where("opnameTemp.pallet_id", $chkData->pallet_id)
	// 		->group_by("opnameTemp.tr_opname_plan_detail2_id,
	// 											pallet.pallet_id, 
	// 											pallet.pallet_kode,
	// 											CASE 
	// 												WHEN opnameTemp.is_valid = 1 THEN 'Valid'
	// 												WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
	// 												WHEN opnameTemp.is_valid is NULL THEN ''
	// 											END,
	// 											opnameTemp.tr_opname_plan_detail2_status,
	// 											opnameTemp.is_new_pallet")
	// 		->get()->row();

	// 	$response =  [
	// 		'type' => 200,
	// 		'message' => "Berhasil menggunakan pallet",
	// 		// 'kode' => $kodePallet,
	// 		// 'statusRak' => $getOpnameDetailId->tr_opname_plan_detail_status,
	// 		// 'statusOpname' => $status,
	// 		'data' => $data
	// 	];

	// 	return $response;
	// }

	public function getDataPalletDetailById($dataPost)
	{

		$status = $this->db->select("tr_opname_plan_status as status")->from('tr_opname_plan')->where('tr_opname_plan_id', $dataPost->opnameId)->get()->row()->status;

		$getOpnameDetailId = $this->getOpnameDetailId($dataPost);

		if ($getOpnameDetailId->tr_opname_plan_detail_status == "Completed") {

			$dataOpnamePlan = $this->db->select("principle_id")->from("tr_opname_plan")->where("tr_opname_plan_id", $dataPost->opnameId)->get()->row();

			$getIsSimpan = $this->db->select("isnull(is_simpan, 0) as is_simpan")->from("tr_opname_plan_detail2")->where('tr_opname_plan_detail2_id', $dataPost->opnameDetailId2)->get()->row()->is_simpan;

			$data =  $this->db->distinct()->select("opnameDetailTemp.tr_opname_plan_detail3_id as id,
															opnameDetailTemp.sku_stock_id,
															isnull(opnameDetailTemp.sku_actual_qty_opname, 0) as aktual_qty,
															opnameDetailTemp.sku_expired_date as ed,
															opnameDetailTemp.sku_qty_sistem,
															opnameDetailTemp.sku_batch_no,
															sku.sku_id,
															sku.sku_nama_produk,
															sku.sku_kemasan,
															sku.sku_satuan,
															sku.sku_kode_sku_principle,
															sku.principle_id")
				->from("tr_opname_plan_detail3 opnameDetailTemp")
				->join('sku', 'opnameDetailTemp.sku_id = sku.sku_id', 'left')
				->where("opnameDetailTemp.tr_opname_plan_detail2_id", $dataPost->opnameDetailId2)
				->where("opnameDetailTemp.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
				->where("opnameDetailTemp.tr_opname_plan_id", $dataPost->opnameId)->get()->result();

			$response = [
				'statusRak' => $getOpnameDetailId->tr_opname_plan_detail_status,
				'isSimpan' => $getIsSimpan,
				'data' => $data,
				'dataOpnamePlan' => $dataOpnamePlan
			];

			return $response;
		} else {
			if ($status == "In Progress Revision") {
				return $this->getDataPalletDetailInTemp($dataPost, 'revisi', $getOpnameDetailId);
			} else {
				return $this->getDataPalletDetailInTemp($dataPost, 'non revisi', $getOpnameDetailId);
			}
		}
	}

	public function getOpnameDetailId($dataPost)
	{
		return $this->db->select("topd.tr_opname_plan_detail_id, topd.tr_opname_plan_detail_status")
			->from("tr_opname_plan_detail topd")
			->join("rak_lajur_detail rld", "topd.rak_lajur_detail_id = rld.rak_lajur_detail_id", "left")
			->join("rak", "rld.rak_id = rak.rak_id", "left")
			->where("topd.tr_opname_plan_id", $dataPost->opnameId)
			// ->where("rak.depo_detail_id", $dataPost->depo_detail_id)
			->where("rld.rak_lajur_detail_nama", $dataPost->rakNama)->get()->row();
	}

	public function getDataSku($dataPost)
	{

		return $this->db->select("sku_id, sku_kode, sku_nama_produk, sku_kemasan, sku_satuan")
			->from("sku")
			->where("principle_id", $dataPost->principleId)
			->where("sku_is_aktif", 1)
			->where("sku_is_deleted", 0)
			->order_by("sku_id", "ASC")->get()->result();
	}

	public function getDataSkuById($dataPost)
	{
		$arrSkuId = [];
		$arrSkuStockId = [];
		foreach ($dataPost->data as $key => $value) {

			$arrSkuId[] = preg_replace('/\s+/', '', $value->sku_id);
			$arrSkuStockId[] = preg_replace('/\s+/', '', $value->sku_stock_id);
		}

		return $this->db->distinct()->select("sku_id, sku_nama_produk, sku_kemasan, sku_satuan,
        FORMAT(DATEADD(MONTH, sku_minimum_expired_date, FORMAT(GETDATE(), 'yyyy-MM-01')), 'yyyy-MM-dd') as exp_date, sku_kode_sku_principle
      ")->from('sku')
			->where_in("sku_id", $arrSkuId)->get()->result();

		// return $this->db->distinct()->select("sku.sku_id, sku.sku_nama_produk, sku.sku_kemasan, sku.sku_satuan, ss.sku_stock_id,
		//     FORMAT(DATEADD(MONTH, sku_minimum_expired_date, FORMAT(GETDATE(), 'yyyy-MM-01')), 'yyyy-MM-dd') as exp_date, sku.sku_kode_sku_principle
		//   ")->from('sku')
		// 	->join("sku_stock ss", "sku.sku_id = ss.sku_id", "left")
		// 	->where_in("sku.sku_id", $arrSkuId)
		// 	->where_in("ss.sku_stock_id", $arrSkuStockId)->get()->result();
	}

	public function getDataSkuByScan($skuID)
	{

		$query = $this->db->distinct()->select("sku_id, sku_nama_produk, sku_kemasan, sku_satuan,
        FORMAT(DATEADD(MONTH, sku_minimum_expired_date, FORMAT(GETDATE(), 'yyyy-MM-01')), 'yyyy-MM-dd') as exp_date, sku_kode_sku_principle
      ")->from('sku')
			->where_in("sku_id", $skuID)->get()->result();

		if (count($query) == 0) {
			$response =  [
				// 'type' => 201,
				// 'message' => "Gagal scan SKU",
				// 'kode' => $skuID

				'type' => 201,
				'message' => "SKU tidak ditemukan",
				'kode' => $skuID,
				'statusOpname' => null,
				'data' => $query
			];
		} else {
			$response =  [
				// 'type' => 200,
				// 'message' => "Berhasil scan SKU",
				// 'kode' => $skuID

				'type' => 200,
				'message' => "Berhasil scan SKU",
				'kode' => $skuID,
				'statusOpname' => null,
				'data' => $query
			];
		}


		return $response;
	}

	public function checkMinimunExpiredDate($dataPost)
	{
		$arrSkuId = [];
		$arrSkuStockId = [];
		foreach ($dataPost->data as $key => $value) {

			$arrSkuId[] = preg_replace('/\s+/', '', $value->sku_id);
			$arrSkuStockId[] = preg_replace('/\s+/', '', $value->sku_stock_id);
		}
		$result = [];

		$getMinimumED = $this->db->select("sku.sku_id, sku.sku_minimum_expired_date, ss.sku_stock_id")->from("sku")
			->join("sku_stock ss", "sku.sku_id = ss.sku_id", "left")
			->where_in('ss.sku_id', $arrSkuId)
			->where_in("ss.sku_stock_id", $arrSkuStockId)->get()->result();

		foreach ($getMinimumED as $key => $value) {
			$tgl = date('Y-m', strtotime('+' . $value->sku_minimum_expired_date . ' month'));
			array_push($result, ['sku_id' => $value->sku_id, 'sku_stock_id' => $value->sku_stock_id, 'date' => $tgl . '-01']);
		}

		return $result;
	}

	public function checkDataPalletIntemp($dataPost, $result, $kodeLokasi, $status)
	{

		$type = ($status == "In Progress Revision") ? "tr_opname_plan_detail2" : "tr_opname_plan_detail2_temp";

		$tr_opname_plan = $this->db->query("SELECT
								topp.tr_opname_plan_kode,
								rld.rak_lajur_detail_nama,
								topd.tr_opname_plan_detail_status as status,
								topd.tr_opname_plan_detail_id
							FROM tr_opname_plan_detail topd 
							LEFT JOIN tr_opname_plan topp ON topd.tr_opname_plan_id = topp.tr_opname_plan_id
							LEFT JOIN rak_lajur_detail rld ON topd.rak_lajur_detail_id = rld.rak_lajur_detail_id
							where topd.tr_opname_plan_id = '$dataPost->opnameId'
							order by rld.rak_lajur_detail_nama")->result_array();

		if ($result->tr_opname_plan_detail_status == "Completed") {
			$data =  $this->db->select("opnameDetail2.tr_opname_plan_detail2_id,
                                pallet.pallet_id, 
                                pallet.pallet_kode,
                                CASE 
                                  WHEN opnameDetail2.is_valid = 1 THEN 'Valid'
                                  WHEN opnameDetail2.is_valid = 0 THEN 'Invalid'
                                  WHEN opnameDetail2.is_valid is NULL THEN ''
                                END As status,
                                opnameDetail2.tr_opname_plan_detail2_status as status_opname,
                                opnameDetail2.is_new_pallet,
																count(opnameDetail3.tr_opname_plan_detail3_id) as totalSKU")
				->from("tr_opname_plan_detail2 opnameDetail2")
				->join("tr_opname_plan_detail3 opnameDetail3", "opnameDetail2.tr_opname_plan_detail2_id = opnameDetail3.tr_opname_plan_detail2_id", "left")
				->join("pallet", "opnameDetail2.pallet_id = pallet.pallet_id", "left")
				->where("opnameDetail2.tr_opname_plan_detail_id", $result->tr_opname_plan_detail_id)
				->group_by("opnameDetail2.tr_opname_plan_detail2_id,
											pallet.pallet_id, 
											pallet.pallet_kode,
											CASE 
												WHEN opnameDetail2.is_valid = 1 THEN 'Valid'
												WHEN opnameDetail2.is_valid = 0 THEN 'Invalid'
												WHEN opnameDetail2.is_valid is NULL THEN ''
											END,
											opnameDetail2.tr_opname_plan_detail2_status,
											opnameDetail2.is_new_pallet")
				->get()->result();

			$countData1 = $this->db->select("COUNT(*) as count1")->from("tr_opname_plan_detail2_temp a")->where("a.tr_opname_plan_detail2_status = 'Completed'")->where("a.tr_opname_plan_detail_id = '$result->tr_opname_plan_detail_id'")->get()->row()->count1;
			$countData2 = $this->db->select("COUNT(*) as count2")->from("tr_opname_plan_detail2_temp a")->where("a.tr_opname_plan_detail_id = '$result->tr_opname_plan_detail_id'")->get()->row()->count2;

			// 1 = data sama, 0 = data tidak sama
			$checkCount = $countData1 == $countData2 ? 1 : 0;

			$arr = [
				'type' => 200,
				'message' => "Berhasil scan lokasi",
				'kode' => $kodeLokasi,
				'statusRak' => $result->tr_opname_plan_detail_status,
				'statusOpname' => $status,
				'data' => $data,
				'dataTrOpnamePlan' => $tr_opname_plan,
				'count' => $checkCount
			];

			return $arr;
		} else {
			//get data pallet
			$datas = $this->db->select("pallet_id")
				->from($type)
				->where("tr_opname_plan_detail_id", $result->tr_opname_plan_detail_id)->get()->result();

			if (empty($datas)) {
				$datas = $this->db->select("rldp.pallet_id")
					->from("rak_lajur_detail rld")
					->join("rak_lajur_detail_pallet rldp", "rld.rak_lajur_detail_id = rldp.rak_lajur_detail_id", "left")
					->where("rld.rak_lajur_detail_id", $result->rak_lajur_detail_id)->get()->result();
			}

			//get data pallet

			$pallet_id = [];
			foreach ($datas as $key => $value) {
				if ($value->pallet_id !== null) {
					$pallet_id[] = $value->pallet_id;
				}
			}

			// if ($status == "In Progress") {
			// 	foreach ($datas as $key => $value) {

			// 		if ($value->pallet_id !== null) {
			// 			$pallet_id[] = $value->pallet_id;
			// 			$chkData =  $this->db->select("*")->from("tr_opname_plan_detail2_temp")
			// 				->where("tr_opname_plan_detail_id", $result->tr_opname_plan_detail_id)
			// 				->where("tr_opname_plan_id", $dataPost->opnameId)
			// 				->where("pallet_id", $value->pallet_id)->get()->row();


			// 			if (empty($chkData)) {
			// 				$this->db->set("tr_opname_plan_detail2_id", "NewID()", FALSE);
			// 				$this->db->set("tr_opname_plan_detail_id", $result->tr_opname_plan_detail_id);
			// 				$this->db->set("tr_opname_plan_id", $dataPost->opnameId);
			// 				$this->db->set("pallet_id", $value->pallet_id);
			// 				$this->db->set("tr_opname_plan_detail2_status", "In Progress");

			// 				$this->db->insert("tr_opname_plan_detail2_temp");
			// 			}
			// 		}
			// 	}
			// }

			return $this->getDataPalletInTemp($dataPost, $pallet_id, $kodeLokasi, $status, $result, $result->tr_opname_plan_detail_status, $tr_opname_plan);
		}
	}

	private function getDataPalletInTemp($dataPost, $pallet_id, $kodeLokasi, $status, $result, $statusRak, $tr_opname_plan)
	{

		if ($status == "In Progress Revision") {
			$data =  $this->db->select("opnameDetail2.tr_opname_plan_detail2_id,
                                pallet.pallet_id, 
                                pallet.pallet_kode,
                                CASE 
                                  WHEN opnameDetail2.is_valid = 1 THEN 'Valid'
                                  WHEN opnameDetail2.is_valid = 0 THEN 'Invalid'
                                  WHEN opnameDetail2.is_valid is NULL THEN ''
                                END As status,
                                opnameDetail2.tr_opname_plan_detail2_status as status_opname,
                                opnameDetail2.is_new_pallet,
																count(opnameDetail3.tr_opname_plan_detail3_id) as totalSKU")
				->from("tr_opname_plan_detail2 opnameDetail2")
				->join("tr_opname_plan_detail3 opnameDetail3", "opnameDetail2.tr_opname_plan_detail2_id = opnameDetail3.tr_opname_plan_detail2_id", "left")
				->join("pallet", "opnameDetail2.pallet_id = pallet.pallet_id", "left")
				->where("opnameDetail2.tr_opname_plan_detail_id", $result->tr_opname_plan_detail_id)
				->where("opnameDetail2.tr_opname_plan_id", $dataPost->opnameId)
				->group_by("opnameDetail2.tr_opname_plan_detail2_id,
										pallet.pallet_id, 
										pallet.pallet_kode,
										CASE 
											WHEN opnameDetail2.is_valid = 1 THEN 'Valid'
											WHEN opnameDetail2.is_valid = 0 THEN 'Invalid'
											WHEN opnameDetail2.is_valid is NULL THEN ''
										END
										opnameDetail2.tr_opname_plan_detail2_status,
										opnameDetail2.is_new_pallet")
				->get()->result();
		} else {
			$params = array();
			if (!empty($pallet_id)) {
				for ($i = 0; $i < count($pallet_id); $i++) {
					$params[$i] = "'" . $pallet_id[$i] . "'";
				}
			}

			$wherePallet = !empty($pallet_id) ? "and opnameTemp.pallet_id IN (" . implode(',', $params) . ")" : "";

			$data = $this->db->query("SELECT
														opnameTemp.tr_opname_plan_detail2_id,
														pallet.pallet_id, 
														pallet.pallet_kode,
														CASE 
															WHEN opnameTemp.is_valid = 1 THEN 'Valid'
															WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
															WHEN opnameTemp.is_valid is NULL THEN ''
														END As status,
														opnameTemp.tr_opname_plan_detail2_status as status_opname,
														opnameTemp.is_new_pallet,
														count(opnameDetailTemp.tr_opname_plan_detail3_id) as totalSKU
														FROM tr_opname_plan_detail2_temp opnameTemp
														left join tr_opname_plan_detail3_temp opnameDetailTemp on opnameTemp.tr_opname_plan_detail2_id = opnameDetailTemp.tr_opname_plan_detail2_id
														left join pallet on opnameTemp.pallet_id = pallet.pallet_id
														where opnameTemp.tr_opname_plan_detail_id = '$result->tr_opname_plan_detail_id'
															and opnameTemp.tr_opname_plan_id = '$dataPost->opnameId' $wherePallet
														group by 
																opnameTemp.tr_opname_plan_detail2_id,
																pallet.pallet_id, 
																pallet.pallet_kode,
																CASE 
																	WHEN opnameTemp.is_valid = 1 THEN 'Valid'
																	WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
																	WHEN opnameTemp.is_valid is NULL THEN ''
																END,
																opnameTemp.tr_opname_plan_detail2_status,
																opnameTemp.is_new_pallet
			")->result();

			// $this->db->select("opnameTemp.tr_opname_plan_detail2_id,
			//               pallet.pallet_id, 
			//               pallet.pallet_kode,
			//               CASE 
			//                 WHEN opnameTemp.is_valid = 1 THEN 'Valid'
			//                 WHEN opnameTemp.is_valid = 0 THEN 'Invalid'
			//                 WHEN opnameTemp.is_valid is NULL THEN ''
			//               END As status,
			//               opnameTemp.tr_opname_plan_detail2_status as status_opname,
			//               opnameTemp.is_new_pallet")
			// 	->from("tr_opname_plan_detail2_temp opnameTemp")
			// 	->join("pallet", "opnameTemp.pallet_id = pallet.pallet_id", "left")
			// 	->where("opnameTemp.tr_opname_plan_detail_id", $result->tr_opname_plan_detail_id)
			// 	->where("opnameTemp.tr_opname_plan_id", $dataPost->opnameId);

			// 	if (!empty($pallet_id)) {
			// 		$this->db->where_in("opnameTemp.pallet_id", $pallet_id);
			// 	}
			// 	$this->db->get();
			// 	$data = $this->db->result();
		}

		$countData1 = $this->db->select("COUNT(*) as count1")->from("tr_opname_plan_detail2_temp a")->where("a.tr_opname_plan_detail2_status = 'Completed'")->where("a.tr_opname_plan_detail_id = '$result->tr_opname_plan_detail_id'")->get()->row()->count1;
		$countData2 = $this->db->select("COUNT(*) as count2")->from("tr_opname_plan_detail2_temp a")->where("a.tr_opname_plan_detail_id = '$result->tr_opname_plan_detail_id'")->get()->row()->count2;

		// 1 = data sama, 0 = data tidak sama
		$checkCount = $countData1 == $countData2 ? 1 : 0;

		$arr = [
			'type' => 200,
			'message' => "Berhasil scan lokasi",
			'kode' => $kodeLokasi,
			'statusRak' => $statusRak,
			'statusOpname' => $status,
			'data' => $data,
			'dataTrOpnamePlan' => $tr_opname_plan,
			'count' => $checkCount
		];

		return $arr;
	}

	public function deleteOpnameDetail2Temp($dataPost)
	{

		//check status header opname
		$status = $this->db->select("tr_opname_plan_status as status")->from('tr_opname_plan')->where('tr_opname_plan_id', $dataPost->opnameId)->get()->row()->status;

		$opnameDetail2 = ($status == "In Progress Revision") ? "tr_opname_plan_detail2" : "tr_opname_plan_detail2_temp";
		$opnameDetail3 = ($status == "In Progress Revision") ? "tr_opname_plan_detail3" : "tr_opname_plan_detail3_temp";

		$this->db->trans_begin();

		$this->db->update("pallet_generate_detail2", ['pallet_generate_detail2_is_aktif' => 0], ['pallet_generate_detail2_id' => $dataPost->palletId]);

		// $this->db->where("pallet_id", $dataPost->palletId);
		// $this->db->delete('pallet');

		// $this->db->where("pallet_id", $dataPost->palletId);
		// $this->db->delete('pallet_detail');

		$this->db->where("tr_opname_plan_id", $dataPost->opnameId);
		$this->db->where("tr_opname_plan_detail2_id", $dataPost->opnameDetail2Id);
		$this->db->delete($opnameDetail2);

		$this->db->where("tr_opname_plan_id", $dataPost->opnameId);
		$this->db->where("tr_opname_plan_detail2_id", $dataPost->opnameDetail2Id);
		$this->db->delete($opnameDetail3);

		if ($this->db->trans_status() == FALSE) {
			$this->db->trans_rollback();
			return 201;
		} else {
			$this->db->trans_commit();

			$countData1 = $this->db->select("COUNT(*) as count1")->from("tr_opname_plan_detail2_temp a")->where("a.tr_opname_plan_detail2_status = 'Completed'")->where("a.tr_opname_plan_detail_id = '$dataPost->tr_opname_plan_detail_id'")->get()->row()->count1;
			$countData2 = $this->db->select("COUNT(*) as count2")->from("tr_opname_plan_detail2_temp a")->where("a.tr_opname_plan_detail_id = '$dataPost->tr_opname_plan_detail_id'")->get()->row()->count2;

			// 1 = data sama, 0 = data tidak sama
			$checkCount = $countData1 == $countData2 ? 1 : 0;

			return $checkCount;
		}
	}

	public function deleteOpnameDetail3Temp($dataPost)
	{
		//check status header opname
		$status = $this->db->select("tr_opname_plan_status as status")->from('tr_opname_plan')->where('tr_opname_plan_id', $dataPost->opnameId)->get()->row()->status;

		$opnameDetail3 = ($status == "In Progress Revision") ? "tr_opname_plan_detail3" : "tr_opname_plan_detail3_temp";

		$this->db->where("tr_opname_plan_id", $dataPost->opnameId);
		$this->db->where("tr_opname_plan_detail3_id", $dataPost->opnameDetail3ID);
		$this->db->delete($opnameDetail3);
	}

	public function deleteOpnameDetailRow($dataPost)
	{
		$this->db->where("tr_opname_plan_detail_id", $dataPost->tr_opname_plan_detail_id);
		$this->db->delete('tr_opname_plan_detail');
	}

	private function getDataPalletDetailInTemp($dataPost, $typeOpname, $getOpnameDetailId)
	{

		$type = ($typeOpname == "revisi") ? "tr_opname_plan_detail3" : "tr_opname_plan_detail3_temp";
		$type2 = ($typeOpname == "revisi") ? "tr_opname_plan_detail2" : "tr_opname_plan_detail2_temp";

		$getIsSimpan = $this->db->select("isnull(is_simpan, 0) as is_simpan")->from($type2)->where('tr_opname_plan_detail2_id', $dataPost->opnameDetailId2)->get()->row()->is_simpan;

		$chkData = $this->db->select("*")->from($type)
			->where("tr_opname_plan_detail2_id", $dataPost->opnameDetailId2)
			->where("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
			->where("tr_opname_plan_id", $dataPost->opnameId)->get()->row();

		if (empty($chkData)) {

			$dataPalletDetail = $this->db->select("pd.pallet_detail_id,
                                pd.pallet_id,
                                pd.sku_stock_expired_date as ed,
                                pd.sku_stock_id,
																sku_stock.sku_stock_batch_no,
                                ISNULL(pd.sku_stock_qty, 0) - ISNULL(pd.sku_stock_ambil, 0) + ISNULL(pd.sku_stock_in, 0) - ISNULL(pd.sku_stock_out, 0) + ISNULL(pd.sku_stock_terima, 0) as qty,
                                sku.sku_id,
                                sku.sku_nama_produk,
                                sku.sku_kemasan,
                                sku.sku_satuan")
				->from('pallet_detail pd')
				->join('sku', 'pd.sku_id = sku.sku_id', 'left')
				->join('sku_stock', 'pd.sku_stock_id = sku_stock.sku_stock_id', 'left')
				->where('pd.pallet_id', $dataPost->pallet_id)->get()->result();

			foreach ($dataPalletDetail as $key => $value) {
				$this->db->set("tr_opname_plan_detail3_id", "NewID()", FALSE);
				$this->db->set("tr_opname_plan_detail2_id", $dataPost->opnameDetailId2);
				$this->db->set("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id);
				$this->db->set("tr_opname_plan_id", $dataPost->opnameId);
				$this->db->set("sku_id", $value->sku_id);
				$this->db->set("sku_stock_id", $value->sku_stock_id);
				$this->db->set("sku_expired_date", $value->ed);
				$this->db->set("sku_qty_sistem", $value->qty);
				$this->db->set("sku_batch_no", $value->sku_stock_batch_no == null ? NULL : $value->sku_stock_batch_no);

				$this->db->insert($type);
			}
		}

		$dataOpnamePlan = $this->db->select("principle_id")->from("tr_opname_plan")->where("tr_opname_plan_id", $dataPost->opnameId)->get()->row();

		$data = $this->db->distinct()->select("opnameDetailTemp.tr_opname_plan_detail3_id as id,
                                opnameDetailTemp.sku_stock_id,
                                isnull(opnameDetailTemp.sku_actual_qty_opname, 0) as aktual_qty,
                                opnameDetailTemp.sku_expired_date as ed,
                                opnameDetailTemp.sku_qty_sistem,
                                opnameDetailTemp.sku_batch_no,
                                sku.sku_id,
                                sku.sku_nama_produk,
                                sku.sku_kemasan,
                                sku.sku_satuan,
                                sku.sku_kode_sku_principle,
								sku.principle_id")
			->from($type . " opnameDetailTemp")
			->join('sku', 'opnameDetailTemp.sku_id = sku.sku_id', 'left')
			->where("opnameDetailTemp.tr_opname_plan_detail2_id", $dataPost->opnameDetailId2)
			->where("opnameDetailTemp.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
			->where("opnameDetailTemp.tr_opname_plan_id", $dataPost->opnameId)->get()->result();

		$response = [
			'statusRak' => $getOpnameDetailId->tr_opname_plan_detail_status,
			'isSimpan' => $getIsSimpan,
			'data' => $data,
			'dataOpnamePlan' => $dataOpnamePlan
		];

		return $response;
	}

	public function saveDataProsesOpnameByRak($dataPost)
	{
		$this->db->trans_begin();

		//check status header opname
		$status = $this->db->select("tr_opname_plan_status as status")->from('tr_opname_plan')->where('tr_opname_plan_id', $dataPost->opnameId)->get()->row()->status;

		$opnameDetail2 = ($status == "In Progress Revision") ? "tr_opname_plan_detail2" : "tr_opname_plan_detail2_temp";
		$opnameDetail3 = ($status == "In Progress Revision") ? "tr_opname_plan_detail3" : "tr_opname_plan_detail3_temp";

		$getOpnameDetailId = $this->getOpnameDetailId($dataPost);

		//update status di detail
		$this->db->set("tr_opname_plan_detail2_status", "Completed");
		$this->db->set("is_simpan", 1);
		$this->db->where("tr_opname_plan_detail2_id", $dataPost->opnameDetailId2);
		$this->db->update($opnameDetail2);

		$this->db->where("tr_opname_plan_detail2_id", $dataPost->opnameDetailId2);
		$this->db->where("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id);
		$this->db->where("tr_opname_plan_id", $dataPost->opnameId);
		$this->db->delete($opnameDetail3);

		foreach ($dataPost->dataDetail as $key => $value) {
			$this->db->set("tr_opname_plan_detail3_id", "NewID()", FALSE);
			$this->db->set("tr_opname_plan_detail2_id", $dataPost->opnameDetailId2);
			$this->db->set("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id);
			$this->db->set("tr_opname_plan_id", $dataPost->opnameId);
			$this->db->set("sku_id", $value->sku_id);
			if ($value->sku_qty_sistem != "null" || $value->sku_qty_sistem != "0") {
				$this->db->set("sku_stock_id", $value->sku_stock_id == "null" ? NULL : $value->sku_stock_id);
			}
			$this->db->set("sku_expired_date", $value->ed);
			$this->db->set("sku_actual_qty_opname", $value->aktual_qty);
			$this->db->set("sku_qty_sistem", $value->sku_qty_sistem == "null" || $value->sku_qty_sistem == "0" ? 0 : $value->sku_qty_sistem);
			$this->db->set("is_new_sku", $value->sku_qty_sistem == "null" || $value->sku_qty_sistem == "0" ? 1 : 0);
			$this->db->set("sku_batch_no", $value->batchNo == "" ? NULL : $value->batchNo);


			$this->db->insert($opnameDetail3);
		}

		$dataDetail2 = $this->db->query("SELECT a.pallet_id, rak.depo_id, rak.depo_detail_id, b.rak_lajur_detail_id 
																			from $opnameDetail2 a
																			left join tr_opname_plan_detail b on a.tr_opname_plan_detail_id = b.tr_opname_plan_detail_id
																			left join rak_lajur_detail c on b.rak_lajur_detail_id = c.rak_lajur_detail_id
																			left join rak on c.rak_id = rak.rak_id
																			where a.tr_opname_plan_id = '$dataPost->opnameId'")->result();

		foreach ($dataDetail2 as $key => $value) {

			$rak_lajur_detail_pallet_id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];
			$rak_lajur_detail_pallet_his_id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

			$this->db->update('pallet', ['rak_lajur_detail_id' => $value->rak_lajur_detail_id], ['pallet_id' => $value->pallet_id]);
			$this->db->delete('rak_lajur_detail_pallet', ['pallet_id' => $value->pallet_id]);

			$this->db->insert('rak_lajur_detail_pallet', [
				'rak_lajur_detail_pallet_id' => $rak_lajur_detail_pallet_id,
				'rak_lajur_detail_id' => $value->rak_lajur_detail_id,
				'pallet_id' => $value->pallet_id
			]);

			$this->db->insert('rak_lajur_detail_pallet_his', [
				'rak_lajur_detail_pallet_id' => $rak_lajur_detail_pallet_his_id,
				'depo_id' => $value->depo_id,
				'depo_detail_id' => $value->depo_detail_id,
				'rak_lajur_detail_id' => $value->rak_lajur_detail_id,
				'pallet_id' => $value->pallet_id,
				'tanggal_create' => date('Y-m-d H:i:s'),
				'who_create' => $this->session->userdata('pengguna_username'),
			]);
		}

		$dataDetail3Temp = $this->db->select("opnameDetail3.*, opnameDetail2.pallet_id")
			->from($opnameDetail3 . " opnameDetail3")
			->join($opnameDetail2 . " opnameDetail2", 'opnameDetail3.tr_opname_plan_detail2_id = opnameDetail2.tr_opname_plan_detail2_id', 'left')
			->where("opnameDetail3.tr_opname_plan_detail2_id", $dataPost->opnameDetailId2)
			->where("opnameDetail3.tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id)
			->where("opnameDetail3.tr_opname_plan_id", $dataPost->opnameId)->get()->result();

		foreach ($dataDetail3Temp as $key => $value) {
			if ($value->is_new_sku == 1) {
				$this->db->set("pallet_detail_id", "NewID()", FALSE);
				$this->db->set("pallet_id", $value->pallet_id);
				$this->db->set("sku_id", $value->sku_id);
				$this->db->set("sku_stock_id", $value->sku_stock_id);
				$this->db->set("sku_stock_expired_date", $value->sku_expired_date);
				$this->db->set("sku_stock_qty", $value->sku_qty_sistem);

				$this->db->insert('pallet_detail');
			}
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$data = [
				'type' => 201,
				'status' => ""
			];
			return $data;
		} else {
			$this->db->trans_commit();
			$data = [
				'type' => 200,
				'status' => $status
			];
			return $data;
		}
	}

	public function konfirmasiDataProsesOpnameByRak($dataPost)
	{

		$this->db->trans_begin();

		//check status header opname
		$status = $this->db->select("tr_opname_plan_status as status")->from('tr_opname_plan')->where('tr_opname_plan_id', $dataPost->opnameId)->get()->row()->status;

		$getOpnameDetailId = $this->getOpnameDetailId($dataPost);

		// $chkCompletedData = $this->db->query("SELECT pallet_id
		//                                       from tr_opname_plan_detail2_temp
		//                                       where tr_opname_plan_id = '$dataPost->opnameId' 
		//                                         and tr_opname_plan_detail_id = '$dataPost->opnameDetailId'")->result();

		// foreach ($chkCompletedData as $key => $value) {
		//   $this->db->set("pallet_is_lock", 0);
		//   $this->db->set("pallet_is_lock_reason", NULL);
		//   $this->db->where("pallet_id", $value->pallet_id);

		//   $this->db->update("pallet");
		// }

		//update status di detail
		$this->db->set("tr_opname_plan_detail_status", "Completed");
		$this->db->where("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id);

		$this->db->update("tr_opname_plan_detail");

		if ($status != "In Progress Revision") {
			//get data temp detail 2 
			$dataTemp2 = $this->db->get_where("tr_opname_plan_detail2_temp", ["tr_opname_plan_id" => $dataPost->opnameId, "tr_opname_plan_detail_id" => $getOpnameDetailId->tr_opname_plan_detail_id])->result();

			//get data temp detail 3 
			// $dataTemp3 = $this->db->get_where("tr_opname_plan_detail3_temp", ["tr_opname_plan_id" => $dataPost->opnameId, "tr_opname_plan_detail_id" => $getOpnameDetailId->tr_opname_plan_detail_id])->result();

			$this->db->select('tr_opname_plan_detail3_temp.*, tr_opname_plan_detail2_temp.pallet_id');
			$this->db->from('tr_opname_plan_detail3_temp');
			$this->db->join('tr_opname_plan_detail2_temp', 'tr_opname_plan_detail2_temp.tr_opname_plan_detail2_id = tr_opname_plan_detail3_temp.tr_opname_plan_detail2_id', 'left');
			$this->db->where('tr_opname_plan_detail3_temp.tr_opname_plan_id', $dataPost->opnameId);
			$this->db->where('tr_opname_plan_detail3_temp.tr_opname_plan_detail_id', $getOpnameDetailId->tr_opname_plan_detail_id);
			$dataTemp3 = $this->db->get()->result();

			//insert ke detail 2
			foreach ($dataTemp2 as $key => $value) {
				$this->db->set("tr_opname_plan_detail2_id", $value->tr_opname_plan_detail2_id);
				$this->db->set("tr_opname_plan_detail_id", $value->tr_opname_plan_detail_id);
				$this->db->set("tr_opname_plan_id", $value->tr_opname_plan_id);
				$this->db->set("pallet_id", $value->pallet_id);
				$this->db->set("tr_opname_plan_detail2_status", "Completed");
				$this->db->set("is_valid", $value->is_valid);
				$this->db->set("is_new_pallet", $value->is_new_pallet);
				$this->db->set("is_simpan", $value->is_simpan == NULL ? NULL : $value->is_simpan);

				$this->db->insert("tr_opname_plan_detail2");
			}

			//insert ke detail 3
			foreach ($dataTemp3 as $key => $value) {
				$this->db->set("tr_opname_plan_detail3_id", "NewID()", FALSE);
				$this->db->set("tr_opname_plan_detail2_id", $value->tr_opname_plan_detail2_id);
				$this->db->set("tr_opname_plan_detail_id", $value->tr_opname_plan_detail_id);
				$this->db->set("tr_opname_plan_id", $value->tr_opname_plan_id);
				$this->db->set("sku_id", $value->sku_id);
				if (($value->sku_qty_sistem != NULL || $value->sku_qty_sistem != 0)) {
					$this->db->set("sku_stock_id", $value->sku_stock_id);
				}
				$this->db->set("sku_expired_date", $value->sku_expired_date);
				$this->db->set("sku_actual_qty_opname", $value->sku_actual_qty_opname);
				$this->db->set("sku_qty_sistem", ($value->sku_qty_sistem == NULL || $value->sku_qty_sistem == 0) ? 0 : $value->sku_qty_sistem);
				$this->db->set("is_new_sku", ($value->sku_qty_sistem == NULL || $value->sku_qty_sistem == 0) ? 1 : 0);
				$this->db->set("sku_batch_no", $value->sku_batch_no == NULL ? NULL : $value->sku_batch_no);

				$this->db->insert("tr_opname_plan_detail3");

				// update pallet detail
				// $this->db->set("sku_stock_in", $value->sku_actual_qty_opname);
				// $this->db->where("pallet_id", $value->pallet_id);
				// $this->db->where("sku_id", $value->sku_id);

				// $this->db->update('pallet_detail');
			}

			//delete detail2_temp dan detail3_temp
			$this->db->where("tr_opname_plan_id", $dataPost->opnameId);
			$this->db->where("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id);
			$this->db->delete("tr_opname_plan_detail2_temp");

			$this->db->where("tr_opname_plan_id", $dataPost->opnameId);
			$this->db->where("tr_opname_plan_detail_id", $getOpnameDetailId->tr_opname_plan_detail_id);
			$this->db->delete("tr_opname_plan_detail3_temp");
		}

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			return false;
		} else {
			$this->db->trans_commit();
			return true;
		}
	}

	public function konfirmasiDataProsesOpname($dataPost)
	{
		// //get status di detail data
		// $status = $this->db->select("tr_opname_plan_detail_status as status,
		//                               COUNT(tr_opname_plan_detail_status) As total")
		// 	->from("tr_opname_plan_detail")
		// 	->where("tr_opname_plan_id", $dataPost->opnameId)->group_by('tr_opname_plan_detail_status')->get()->result();

		// $result = 0;

		// foreach ($status as $key => $value) {
		// 	if ($value->status == "In Progress") {
		// 		if ($value->total > 0) {
		// 			$result = 1;
		// 		} else {
		// 			$result = 0;
		// 		}
		// 	}
		// }
		// if ($result == 0) {

		// } else {
		// 	$response = [
		// 		'type' => 201,
		// 		'message' => "Gagal Konfirmasi, Data di list opname detail sepenuhnya belum Completed",
		// 	];
		// }

		$this->db->trans_begin();

		//check status header opname
		$statusOpname = $this->db->select("tr_opname_plan_status as status")->from('tr_opname_plan')->where('tr_opname_plan_id', $dataPost->opnameId)->get()->row()->status;

		if ($statusOpname == "In Progress Revision") {
			//get karyawan id by opname id
			$karyawanId = $this->db->select("notification_from_who_id as id")->from('notification')
				->where("notification_data_id", $dataPost->opnameId)
				->where("notification_to_who_id", $this->session->userdata('karyawan_id'))->get()->row();

			$this->db->set("notification_id", "NewID()", FALSE);
			$this->db->set("notification_from_modul", "WMS/ProsesOpname");
			$this->db->set("notification_to_modul", "WMS/PersetujuanHasilStockOpname");
			$this->db->set("notification_judul", "Revisi Opname Done");
			$this->db->set("notification_keterangan", "Revisi Opname selesai, menunggu final approval dari");
			$this->db->set("notification_from_who_id", $this->session->userdata('karyawan_id'));
			$this->db->set("notification_to_who_id", empty($karyawanId) ? "AD030BE3-7D9E-4A66-B985-D6085F623DA2" : $karyawanId->id);
			$this->db->set("notification_tgl", "GETDATE()", FALSE);
			$this->db->set("notification_is_read", 0);
			$this->db->set("notification_data_id", $dataPost->opnameId);
			$this->db->set("depo_id", $this->session->userdata('depo_id'));

			$this->db->insert("notification");

			senderPusher([
				'message' => 'update status revision opname'
			]);
		}

		//update status opname
		$this->db->set("tr_opname_plan_status", "Waiting For Final Approve");
		$this->db->set("tr_opname_plan_tgl_end", "GETDATE()", FALSE);
		$this->db->where("tr_opname_plan_id", $dataPost->opnameId);

		$this->db->update("tr_opname_plan");

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'type' => 201,
				'message' => "Gagal konfirmasi data",
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'type' => 200,
				'message' => "Berhasil konfirmasi data",
			];
		}

		return $response;
	}

	public function getKodeAutoComplete($value, $type, $prosesId, $principleId)
	{
		if ($type == 'lokasi') {
			return $this->db->select("rjd.rak_lajur_detail_nama as kode")
				->from("rak")
				->join("rak_lajur_detail rjd", "rak.rak_id = rjd.rak_id")
				->where("rak.depo_id", $this->session->userdata('depo_id'))
				->like("rjd.rak_lajur_detail_nama", $value)
				->order_by("rjd.rak_lajur_detail_nama", "ASC")->get()->result();

			// return $this->db->select("rld.rak_lajur_detail_nama as kode")
			// 	->from("tr_opname_plan_detail topd")
			// 	->join("rak_lajur_detail rld", "topd.rak_lajur_detail_id = rld.rak_lajur_detail_id", "left")
			// 	->where("topd.tr_opname_plan_id", $prosesId)
			// 	->like("rld.rak_lajur_detail_nama", $value)
			// 	->order_by("rld.rak_lajur_detail_nama", "ASC")->get()->result();
		}

		if ($type == 'pallet') {
			return $this->db->select("pallet_kode as kode")
				->from("pallet")
				->where("depo_id", $this->session->userdata('depo_id'))
				->like("pallet_kode", $value)->get()->result();
		}

		if ($type == 'newPallet') {
			$depo_id = $this->session->userdata('depo_id');

			// return $this->db->select("a.pallet_generate_detail2_kode as kode")
			// 	->from("pallet_generate_detail2 a")
			// 	->join("pallet_generate b", "a.pallet_generate_id = b.pallet_generate_id", 'left')
			// 	->where("b.depo_id", $depo_id)
			// 	->where("a.pallet_generate_detail2_is_aktif", 0)
			// 	->like("a.pallet_generate_detail2_kode", $value)->get()->result();

			return $this->db->query("SELECT
				kode, is_aktif
			  FROM (SELECT
				a.pallet_generate_detail2_kode AS kode,
				a.pallet_generate_detail2_is_aktif as is_aktif
			  FROM pallet_generate_detail2 a
			  LEFT JOIN pallet_generate b
				ON a.pallet_generate_id = b.pallet_generate_id
			  WHERE b.depo_id = '$depo_id'
			  AND a.pallet_generate_detail2_is_aktif = 0
			  AND a.pallet_generate_detail2_kode LIKE '%$value%' ESCAPE '!'
			  UNION
			  SELECT
				pallet_kode AS kode,
				pallet_is_aktif as is_aktif
			  FROM pallet
			  WHERE depo_id = '$depo_id'
			  AND pallet_kode LIKE '%$value%' ESCAPE '!') AS combined_result")->result();
		}

		if ($type == 'addScanSku') {
			// return $this->db->select("sku_id as id, sku_kode as kode, sku_nama_produk as nama")
			// 	->from("sku")
			// 	->where("principle_id", $principleId)
			// 	->like("sku_kode", $value)
			// 	->or_like("sku_nama_produk", $value)
			// 	->get()->result();

			return $this->db->query("SELECT sku_id as id, sku_kode as kode, sku_nama_produk as nama, principle_id
			FROM sku
			WHERE principle_id = '$principleId' 
			AND  (sku_kode LIKE '%$value%' ESCAPE '!' OR sku_nama_produk LIKE '%$value%' ESCAPE '!')
			ORDER BY sku_kode")->result();
		}
	}

	public function GetKodeOpname($id)
	{
		return $this->db->query("select tr_opname_plan_kode from tr_opname_plan where tr_opname_plan_id = '$id'")->row();
	}
	public function GetDetailOpnameCetak($id)
	{
		return $this->db->query("
		select dp.depo_nama,tr.tipe_stok,d.rak_lajur_detail_nama as nama_detail_rak, c.pallet_kode, f.sku_kode, f.sku_nama_produk, isnull(b.sku_batch_no, '') as sku_batch_no,format(b.sku_expired_date,'dd-MM-yyyy') as sku_expired_date, b.sku_actual_qty_opname,f.sku_nama_produk
		from tr_opname_plan_detail2 a
			left join tr_opname_plan_detail3 b on a.tr_opname_plan_detail2_id = b.tr_opname_plan_detail2_id
			left join tr_opname_plan tr on tr.tr_opname_plan_id = a.tr_opname_plan_id
			left join pallet c on a.pallet_id = c.pallet_id
			left join depo dp on dp.depo_id = c.depo_id
			left join rak_lajur_detail d on c.rak_lajur_detail_id = d.rak_lajur_detail_id
			left join rak_lajur e on e.rak_lajur_id = d.rak_lajur_id
			left join sku f on b.sku_id = f.sku_id
		where a.tr_opname_plan_id = '$id' order by e.rak_lajur_nama, d.rak_lajur_detail_kolom")->result_array();
	}
}
