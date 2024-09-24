<?php

class M_PersiapanMutasiAntarUnit extends CI_Model
{
	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		$this->load->model(['M_AutoGen', 'M_Vrbl', 'M_Function']);

		date_default_timezone_set('Asia/Jakarta');
	}

	function getDepoPrefix($depoId): object
	{
		return $this->db->select("*")->from('depo')->where('depo_id', $depoId)->get()->row();
	}

	function getDataByFilter($dataPost): array
	{
		$exlodeTanggal = explode(' - ', $dataPost->tanggal);

		$this->db->select("tmd.tr_mutasi_depo_id,
											 tmd.tr_mutasi_depo_kode,
											 ekspedisi.ekspedisi_nama,
											 karyawan.karyawan_nama,
											 kendaraan.kendaraan_model,
											 kendaraan.kendaraan_nopol,
											 tmd.tr_mutasi_depo_status")
			->from('tr_mutasi_depo tmd')
			->join('ekspedisi', 'tmd.ekspedisi_id = ekspedisi.ekspedisi_id', 'left')
			->join('karyawan', 'tmd.karyawan_id = karyawan.karyawan_id', 'left')
			->join('kendaraan', 'tmd.kendaraan_id = kendaraan.kendaraan_id', 'left')
			->where('tmd.depo_id', $this->session->userdata('depo_id'))
			->where("FORMAT(tmd.tr_mutasi_depo_tgl_create, 'yyyy-MM-dd') >=", date('Y-m-d', strtotime(str_replace("/", "-", $exlodeTanggal[0]))))
			->where("FORMAT(tmd.tr_mutasi_depo_tgl_create, 'yyyy-MM-dd') <=", date('Y-m-d', strtotime(str_replace("/", "-", $exlodeTanggal[1]))));
		if ($dataPost->ekspedisi !== 'all') $this->db->where('tmd.ekspedisi_id', $dataPost->ekspedisi);
		if ($dataPost->pengemudi !== 'all') $this->db->where('tmd.karyawan_id', $dataPost->pengemudi);
		if ($dataPost->kendaraan !== 'all') $this->db->where('tmd.kendaraan_id', $dataPost->kendaraan);
		if ($dataPost->status !== 'all') $this->db->where('tmd.tr_mutasi_depo_status', $dataPost->status);
		return $this->db->order_by('tmd.tr_mutasi_depo_kode', 'ASC')->get()->result();
	}

	function getKodeAutoComplete($valueParams): array
	{
		return $this->db->select("CONCAT(REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 2)), '/', REVERSE(PARSENAME(REPLACE(REVERSE(pallet_kode), '/', '.'), 3))) as kode")
			->from("pallet")
			->where("depo_id", $this->session->userdata('depo_id'))
			->like("pallet_kode", $valueParams)->get()->result();
	}

	function getDataDepos(): array
	{
		return [
			'depoWithoutItSelf' => $this->db->get_where('depo', [
				'depo_status' => 1
			])->result(),

			'depoWithItSelft' => $this->db->get_where('depo', [
				'depo_status' => 1,
				'depo_id !=' => $this->session->userdata('depo_id')
			])->result(),
		];
	}

	function getDataWarehouses(): array
	{
		return $this->db->get_where('depo_detail', [
			'depo_detail_flag_jual' => 1,
			'depo_id' => $this->session->userdata('depo_id')
		])->result();
	}

	function getDataEkspedisis(): array
	{
		return $this->db->get_where('ekspedisi', [
			'ekspedisi_is_aktif' => 1,
			'ekspedisi_is_deleted' => 0
		])->result();
	}

	function getDataDrivers(): array
	{
		return $this->db->get_where('karyawan', [
			'karyawan_is_aktif' => 1,
			'karyawan_is_deleted' => 0,
			'karyawan_level_id' => '339D8AC2-C6CE-4B47-9BFC-E372592AF521',
		])->result();
	}

	function getDataVehicles(): array
	{
		return $this->db->get_where('kendaraan', [
			'kendaraan_is_aktif' => 1,
			'kendaraan_is_deleted' => 0,
		])->result();
	}

	function getParamsForSkuData($dataPost): array
	{
		$depo = $this->db->select("depo_nama")->from("depo")->where("depo_id", $dataPost->depoAsal)->get()->row()->depo_nama;
		$depoDetail = $this->db->select("depo_detail_nama")->from("depo_detail")->where("depo_detail_id", $dataPost->gudangAsal)->get()->row()->depo_detail_nama;

		$skuInduk = $this->db->query("SELECT 
																		DISTINCT
																		sku_induk.sku_induk_id,
																		sku_induk.sku_induk_nama
																	FROM sku_stock
																	LEFT JOIN sku ON sku_stock.sku_id = sku.sku_id
																	LEFT JOIN sku_induk ON sku.sku_induk_id = sku_induk.sku_induk_id
																	WHERE sku_stock.depo_id = '$dataPost->depoAsal'
																		AND sku_stock.depo_detail_id = '$dataPost->gudangAsal'")->result();

		$principle = $this->db->query("SELECT 
																			DISTINCT
																			principle.principle_id,
																			principle.principle_nama
																			FROM sku_stock
																		LEFT JOIN sku on sku_stock.sku_id = sku.sku_id
																		LEFT JOIN principle on sku.principle_id = principle.principle_id
																		WHERE sku_stock.depo_id = '$dataPost->depoAsal'
																			AND sku_stock.depo_detail_id = '$dataPost->gudangAsal'")->result();

		$princpleBrand = $this->db->query("SELECT 
																				DISTINCT
																				principle_brand.principle_brand_id,
																				principle_brand.principle_brand_nama
																			FROM sku_stock
																			LEFT JOIN sku on sku_stock.sku_id = sku.sku_id
																			LEFT JOIN principle_brand on sku.principle_brand_id = principle_brand.principle_brand_id
																			WHERE sku_stock.depo_id = '$dataPost->depoAsal'
																				AND sku_stock.depo_detail_id = '$dataPost->gudangAsal'")->result();

		return [
			'depoId' => $dataPost->depoAsal,
			'depo' => $depo,
			'depoDetailId' => $dataPost->gudangAsal,
			'depoDetail' => $depoDetail,
			'skuInduk' => $skuInduk,
			'principle' => $principle,
			'princpleBrand' => $princpleBrand,
		];
	}

	function getDataSKUByParams()
	{

		$dataPost = $this->input->post();

		$draw = $dataPost['draw'];
		$offset = $dataPost['start'];
		$num_rows = $dataPost['length'];
		$order_index = $_POST['order'][0]['column'];
		$order_by = $_POST['columns'][$order_index]['data'];
		$order_direction = $_POST['order'][0]['dir'];
		$keyword = $_POST['search']['value'];


		$whereFieldPrinciple = $dataPost['principleId'] !== 'all' ? " and s.principle_id = '" . $dataPost['principleId'] . "'" : '';
		$whereFieldPrincipleBrand = $dataPost['principleBrandId'] !== 'all' ? " and s.principle_brand_id = '" . $dataPost['principleBrandId'] . "'" : '';
		$whereFieldSkuInduk = $dataPost['skuIndukId'] !== 'all' ? " and sk.sku_induk_id = '" . $dataPost['skuIndukId'] . "'" : '';
		$whereFieldNamaSku = $dataPost['namaSku'] !== '' ? " and s.sku_nama_produk = '" . $dataPost['namaSku'] . "'" : '';
		$whereFieldKodeSkuWms = $dataPost['kodeSkuWMS'] !== '' ? " and s.sku_kode = '" . $dataPost['kodeSkuWMS'] . "'" : '';
		$whereFieldKodeSkuPabrik = $dataPost['kodeSkuPabrik'] !== '' ? " and s.sku_kode_sku_principle = '" . $dataPost['kodeSkuPabrik'] . "'" : '';
		$whereFieldLike = $keyword !== '' ? " and (si.sku_induk_nama like '%{$keyword}%' or s.sku_kode like '%{$keyword}%' or s.sku_nama_produk like '%{$keyword}%' or s.sku_kemasan like '%{$keyword}%' or s.sku_satuan like '%{$keyword}%' )" : '';

		$base_sql = "
			from sku_stock sk
			LEFT JOIN sku s on sk.sku_id = s.sku_id
			LEFT JOIN sku_induk si on sk.sku_induk_id = si.sku_induk_id
			LEFT JOIN principle p on s.principle_id = p.principle_id
			LEFT JOIN principle_brand pb on s.principle_brand_id = pb.principle_brand_id
			where
				sk.depo_id = '" . $dataPost['depoId'] . "'
					and sk.depo_detail_id = '" . $dataPost['depoDetailId'] . "'
					$whereFieldPrinciple $whereFieldPrincipleBrand $whereFieldSkuInduk $whereFieldNamaSku $whereFieldKodeSkuWms $whereFieldKodeSkuPabrik
					and (isnull(sk.sku_stock_awal,0) + isnull(sku_stock_masuk, 0)) - (isnull(sk.sku_stock_saldo_alokasi, 0) - isnull(sk.sku_stock_keluar, 0)) > 0
					$whereFieldLike
		";

		$data_sql = "
			select
				sk.sku_stock_id,
				si.sku_induk_nama,
				s.sku_id,
				s.sku_kode,
				s.sku_nama_produk,
				s.sku_kemasan,
				s.sku_satuan,
				format(sk.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
				(isnull(sk.sku_stock_awal,0) + isnull(sku_stock_masuk, 0)) - (isnull(sk.sku_stock_saldo_alokasi, 0) - isnull(sk.sku_stock_keluar, 0)) as qtySistem
				, row_number() over (
					order by {$order_by} {$order_direction}
				  ) as nomor
			{$base_sql}
			order by
				{$order_by} {$order_direction}
			OFFSET {$offset} ROWS
			FETCH FIRST {$num_rows} ROWS ONLY
		";

		$src = $this->db->query($data_sql);

		$count_sql = "
			select count(*) AS total
			{$base_sql}
		";

		$total_records = $this->db->query($count_sql)->row()->total;

		$response = array(
			'draw' => intval($draw),
			'iTotalRecords' => $src->num_rows(),
			'iTotalDisplayRecords' => $total_records,
			'aaData' => $src->result(),
		);

		return $response;

		$this->db->select(" sk.sku_stock_id,
                        si.sku_induk_nama,
												s.sku_id,
												s.sku_kode,
                        s.sku_nama_produk,
                        s.sku_kemasan,
                        s.sku_satuan,
                        format(sk.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
                        (isnull(sk.sku_stock_awal,0) + isnull(sku_stock_masuk, 0)) - (isnull(sk.sku_stock_saldo_alokasi, 0) - isnull(sk.sku_stock_keluar, 0)) as qtySistem")
			->from("sku_stock sk")
			->join("sku s", "sk.sku_id = s.sku_id", "left")
			->join("sku_induk si", "sk.sku_induk_id = si.sku_induk_id", "left")
			->join("principle p", "s.principle_id = p.principle_id", "left")
			->join("principle_brand pb", "s.principle_brand_id = pb.principle_brand_id", "left")
			->where("sk.depo_id", $dataPost->depoId)
			->where("sk.depo_detail_id", $dataPost->depoDetailId);
		if ($dataPost->principleId !== 'all') $this->db->where("s.principle_id", $dataPost->principleId);
		if ($dataPost->principleBrandId !== 'all') $this->db->where("s.principle_brand_id", $dataPost->principleBrandId);
		if ($dataPost->skuIndukId !== 'all') $this->db->where("sk.sku_induk_id", $dataPost->skuIndukId);
		if ($dataPost->namaSku !== '') $this->db->where("s.sku_nama_produk", $dataPost->namaSku);
		if ($dataPost->kodeSkuWMS !== '') $this->db->where("s.sku_kode", $dataPost->kodeSkuWMS);
		if ($dataPost->kodeSkuPabrik !== '') $this->db->where("s.sku_kode_sku_principle", $dataPost->kodeSkuPabrik);
		$query = $this->db->get();
		return $query->result();
	}

	function checkScan($dataPost): array
	{
		$unit = $this->getDepoPrefix($this->session->userdata('depo_id'))->depo_kode_preffix;

		$kode = preg_replace('/\s+/', '', $unit  . "/" . $dataPost->kode);

		$cekPallet = $this->db->select("*")
			->from("pallet")
			->where("depo_id", $this->session->userdata('depo_id'))
			->where("pallet_kode", $kode)
			->get()->row();;
		if (!$cekPallet) {
			$response = [
				'status' => 401,
				'title' => 'Error!',
				'icon' => 'error',
				'message' => "Kode pallet <strong>" . $kode . "</strong> tidak ditemukan",
				'kode' => $kode
			];
		} else {
			if ($cekPallet->pallet_is_lock == 1) {
				$response = [
					'status' => 402,
					'title' => 'Info!',
					'icon' => 'info',
					'message' => "Kode pallet <strong>" . $kode . "</strong> dalam tahapan opname",
					'kode' => $kode
				];
			} else {
				$dataDetailPallet = $this->db->select("b.pallet_id, 
																							 b.pallet_kode,
																							 a.sku_stock_expired_date as ed,
																							 (isnull(a.sku_stock_qty, 0) + isnull(a.sku_stock_in, 0) + isnull(a.sku_stock_terima, 0)) - (isnull(a.sku_stock_ambil, 0) + isnull(a.sku_stock_out, 0)) as qtySistem,
																							 c.sku_id,
																							 c.sku_kode,
																							 c.sku_nama_produk,
																							 c.sku_satuan,
																							 f.depo_detail_nama as gudang")
					->from("pallet_detail a")
					->join("pallet b", "a.pallet_id = b.pallet_id", "left")
					->join("sku c", "a.sku_id = c.sku_id", "left")
					->join("rak_lajur_detail d", "b.rak_lajur_detail_id = d.rak_lajur_detail_id", "left")
					->join("rak e", "d.rak_id = e.rak_id", "left")
					->join("depo_detail f", "e.depo_detail_id = f.depo_detail_id", "left")
					->where("b.pallet_id", $cekPallet->pallet_id)
					->order_by('c.sku_kode', 'asc')
					->get()->result();

				$response = [
					'status' => 200,
					'title' => 'Success!',
					'icon' => 'success',
					'message' => "Kode pallet <strong>" . $kode . "</strong> valid",
					'kode' => $kode,
					'data' => $dataDetailPallet
				];
			}
		}

		return $response;
	}

	function saveData($dataPost): array
	{
		$this->db->trans_begin();

		//generate kode
		$vrbl = $this->M_Vrbl->Get_Kode('KODE_DMPAU');
		// get prefik depo
		$depoPrefix = $this->getDepoPrefix($this->session->userdata('depo_id'));

		if ($dataPost->trMutasiDepoId === "") {

			$generateKode = $this->M_AutoGen->Exec_CodeGenGeneralTanggal(date('Y-m-d h:i:s'),  $vrbl->vrbl_kode, $depoPrefix->depo_kode_preffix);

			$trMutasiDepoId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

			$this->db->insert('tr_mutasi_depo', [
				'tr_mutasi_depo_id' => $trMutasiDepoId,
				'tr_mutasi_depo_kode' => $generateKode,
				'depo_id' => $this->session->userdata('depo_id'),
				'ekspedisi_id' => $dataPost->ekspedisi,
				'karyawan_id' => $dataPost->pengemudi,
				'kendaraan_id' => $dataPost->kendaraan,
				'tr_mutasi_depo_status' => $dataPost->status,
				'tr_mutasi_depo_keterangan' => $dataPost->keterangan === "" ? NULL : $dataPost->keterangan,
				'tr_mutasi_depo_tgl_create' => date('Y-m-d H:i:s'),
				'tr_mutasi_depo_who_create' => $this->session->userdata('pengguna_username'),
				'tr_mutasi_depo_tgl_update' => date('Y-m-d H:i:s'),
				'tr_mutasi_depo_who_update' => $this->session->userdata('pengguna_username'),
				'tr_mutasi_depo_info' => "Belum diterima"
			]);

			$trMutasiDepoHeaderId = $trMutasiDepoId;
			$dokumenKode = $generateKode;
		} else {

			$lastUpdatedChecked = checkLastUpdatedData((object) [
				'table' => "tr_mutasi_depo",
				'whereField' => "tr_mutasi_depo_id",
				'whereValue' => $dataPost->trMutasiDepoId,
				'fieldDateUpdate' => "tr_mutasi_depo_tgl_update",
				'fieldWhoUpdate' => "tr_mutasi_depo_who_update",
				'lastUpdated' => $dataPost->lastUpdated
			]);

			$this->db->update('tr_mutasi_depo', [
				'ekspedisi_id' => $dataPost->ekspedisi,
				'karyawan_id' => $dataPost->pengemudi,
				'kendaraan_id' => $dataPost->kendaraan,
				'tr_mutasi_depo_status' => $dataPost->status,
				'tr_mutasi_depo_keterangan' => $dataPost->keterangan === "" ? NULL : $dataPost->keterangan,
			], ['tr_mutasi_depo_id' => $dataPost->trMutasiDepoId]);

			$this->db->delete('tr_mutasi_depo_detail', ['tr_mutasi_depo_id' => $dataPost->trMutasiDepoId]);
			$this->db->delete('tr_mutasi_depo_detail_2', ['tr_mutasi_depo_id' => $dataPost->trMutasiDepoId]);

			$trMutasiDepoHeaderId = $dataPost->trMutasiDepoId;
			$dokumenKode = $dataPost->kodeDokumen;
		}

		if ($dataPost->mutasiAntarDepo) {
			foreach ($dataPost->mutasiAntarDepo as $key => $mutasiAntarDepo) {

				$trMutasiDepoDetailId = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

				$this->db->insert('tr_mutasi_depo_detail', [
					'tr_mutasi_depo_detail_id' => $trMutasiDepoDetailId,
					'tr_mutasi_depo_id' => $trMutasiDepoHeaderId,
					'depo_id_asal' => $mutasiAntarDepo->depoAsal,
					'depo_detail_id_asal' => $mutasiAntarDepo->gudangAsal,
					'depo_id_tujuan' => $mutasiAntarDepo->depoTujuan
				]);



				if ($dataPost->mutasiAntarDepoDetail) {
					foreach ($dataPost->mutasiAntarDepoDetail as $key => $mutasiAntarDepoDetail) {
						if ($mutasiAntarDepo->counter === $mutasiAntarDepoDetail->counter) {
							$trMutasiDepoDetail2Id = $this->M_Vrbl->Get_NewID()[0]['NEW_ID'];

							$this->db->insert('tr_mutasi_depo_detail_2', [
								'tr_mutasi_depo_detail_2_id' => $trMutasiDepoDetail2Id,
								'tr_mutasi_depo_detail_id' => $trMutasiDepoDetailId,
								'tr_mutasi_depo_id' => $trMutasiDepoHeaderId,
								'sku_id' => $mutasiAntarDepoDetail->sku_id,
								'sku_stock_id' => $mutasiAntarDepoDetail->sku_stock_id,
								'qty_plan' => $mutasiAntarDepoDetail->qtyAmbil
							]);
						}
					}
				}
			}
		}

		if ($dataPost->status == "In Progress Approval") $this->db->query("exec approval_pengajuan '" . $this->session->userdata('depo_id') . "', '" . $this->session->userdata('pengguna_id') . "', 'APPRV_DMPAU_01', '$trMutasiDepoHeaderId', '$dokumenKode', 0, 0");

		if (isset($lastUpdatedChecked) && $lastUpdatedChecked['status'] === 400) {
			$this->db->trans_rollback();
			$response = [
				'status' => 400,
				'message' => 'Data Gagal Disimpan'
			];
		} else if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => 'Data Gagal Disimpan',
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Data Berhasil Disimpan',
			];
		}

		return $response;
	}

	function handlerDeleteDataMutasiDepo($dataPost): array
	{
		$this->db->trans_begin();

		$this->db->delete('tr_mutasi_depo', ['tr_mutasi_depo_id' => $dataPost->trMutasiDepo]);
		$this->db->delete('tr_mutasi_depo_detail', ['tr_mutasi_depo_id' => $dataPost->trMutasiDepo]);
		$this->db->delete('tr_mutasi_depo_detail_2', ['tr_mutasi_depo_id' => $dataPost->trMutasiDepo]);

		if ($this->db->trans_status() === FALSE) {
			$this->db->trans_rollback();
			$response = [
				'status' => 401,
				'message' => 'Data Gagal Dihapus',
			];
		} else {
			$this->db->trans_commit();
			$response = [
				'status' => 200,
				'message' => 'Data Berhasil Dihapus',
			];
		}

		return $response;
	}

	function getDataMutasiDepo($dataPost): array
	{
		$mutasiDepo = $this->db->get_where('tr_mutasi_depo', ['tr_mutasi_depo_id' => $dataPost->trMutasiDepoId])->row();

		$mutasiDepoDetail = $this->db->get_where('tr_mutasi_depo_detail', ['tr_mutasi_depo_id' => $dataPost->trMutasiDepoId])->result();

		$mutasiDepoDetail2 = $this->db->select("sk.sku_stock_id,
																						si.sku_induk_nama,
																						s.sku_id,
																						s.sku_kode,
																						s.sku_nama_produk,
																						s.sku_kemasan,
																						s.sku_satuan,
																						format(sk.sku_stock_expired_date, 'dd-MM-yyyy') as ed,
																						(isnull(sk.sku_stock_awal,0) + isnull(sku_stock_masuk, 0)) - (isnull(sk.sku_stock_saldo_alokasi, 0) - isnull(sk.sku_stock_keluar, 0)) as qtySistem,
																						tmd.qty_plan as qtyAmbil,
																						tmd.tr_mutasi_depo_detail_id")
			->from("tr_mutasi_depo_detail_2 tmd")
			->join("sku_stock sk", "tmd.sku_stock_id = sk.sku_stock_id", "left")
			->join("sku s", "tmd.sku_id = s.sku_id", "left")
			->join("sku_induk si", "sk.sku_induk_id = si.sku_induk_id", "left")
			->join("principle p", "s.principle_id = p.principle_id", "left")
			->join("principle_brand pb", "s.principle_brand_id = pb.principle_brand_id", "left")
			->where("tmd.tr_mutasi_depo_id", $dataPost->trMutasiDepoId)->get()->result();


		return [
			'mutasiDepo' => $mutasiDepo,
			'mutasiDepoDetail' => $mutasiDepoDetail,
			'mutasiDepoDetail2' => $mutasiDepoDetail2,
		];
	}

	function getDataCetak($trMutasiDepo, $type, $trMutasiDepoDetail)
	{
		require 'vendor/autoload.php';

		$this->db->select("FORMAT(b.tr_mutasi_depo_tgl_create, 'dd-MM-yyyy') as tanggal,
												c.depo_nama as depoAsal,
												e.depo_detail_nama as gudangAsal,
												c.depo_alamat as alamatDepoAsal,
												d.depo_nama as depoTujuan,
												d.depo_alamat as alamatDepoTujuan,
												f.ekspedisi_nama as ekspedisi,
												g.karyawan_nama as pengemudi,
												h.kendaraan_model as kendaraan,
												h.kendaraan_nopol as nopol,
												ISNULL(b.tr_mutasi_depo_keterangan, '') as keterangan,
												b.tr_mutasi_depo_kode as kodeMutasi,
												b.tr_mutasi_depo_id as trMutasiDepoId,
												a.tr_mutasi_depo_detail_id as trMutasiDepoDetailId")
			->from("tr_mutasi_depo_detail a")
			->join('tr_mutasi_depo b', 'a.tr_mutasi_depo_id = b.tr_mutasi_depo_id', 'left')
			->join('depo c', 'a.depo_id_asal = c.depo_id', 'left')
			->join('depo d', 'a.depo_id_tujuan = d.depo_id', 'left')
			->join('depo_detail e', 'a.depo_detail_id_asal = e.depo_detail_id', 'left')
			->join('ekspedisi f', 'b.ekspedisi_id = f.ekspedisi_id', 'left')
			->join('karyawan g', 'b.karyawan_id = g.karyawan_id', 'left')
			->join('kendaraan h', 'b.kendaraan_id = h.kendaraan_id', 'left')
			->where("b.tr_mutasi_depo_id", $trMutasiDepo);
		if ($type === 'single') $this->db->where('a.tr_mutasi_depo_detail_id', $trMutasiDepoDetail);
		$dataHeader = $this->db->get()->result();

		$dataCetak = [];

		if ($dataHeader) {
			foreach ($dataHeader as $key => $value) {
				$getDataDetail = $this->db->select("sku.sku_kode,
																						sku.sku_nama_produk,
																						sku.sku_satuan,
																						a.qty_plan,
																						FORMAT(c.sku_stock_expired_date, 'dd-MM-yyyy') as expired_date")
					->from("tr_mutasi_depo_detail_2 a")
					->join("sku", "a.sku_id = sku.sku_id", "left")
					->join("sku_stock c", "a.sku_stock_id = c.sku_stock_id", "left")
					->where("a.tr_mutasi_depo_id", $value->trMutasiDepoId)
					->where("a.tr_mutasi_depo_detail_id", $value->trMutasiDepoDetailId)
					->order_by("a.sku_id", "ASC")->get()->result();

				$dataCetak[] = [
					'tanggal' => $value->tanggal,
					'depoAsal' => $value->depoAsal,
					'gudangAsal' => $value->gudangAsal,
					'alamatDepoAsal' => $value->alamatDepoAsal,
					'depoTujuan' => $value->depoTujuan,
					'alamatDepoTujuan' => $value->alamatDepoTujuan,
					'ekspedisi' => $value->ekspedisi,
					'pengemudi' => $value->pengemudi,
					'kendaraan' => $value->kendaraan,
					'nopol' => $value->nopol,
					'keterangan' => $value->keterangan,
					'kodeMutasi' => $value->kodeMutasi,
					'data' => $getDataDetail
				];
			}
		}

		// $alamatArray = "";

		// if ($dataHeader) {
		// 	foreach ($dataHeader as $key => $value) {
		// 		//cek key dari data yang mengandung kata yang ditentukan
		// 		foreach ($value as $key2 => $data) {
		// 			$keyValue = [];
		// 			if (strpos($key2, 'notGenerated') === false && strpos($key2, 'textarea') === false) {
		// 				$alamatArray .= generateFieldMutasiDepo($key2, "<input type='text' class='form-control' value='{$data}' disabled>");
		// 			}

		// 			if (strpos($key2, 'textarea') !== false) {
		// 				$alamatArray .= generateFieldMutasiDepo($key2, "<textarea cols='10' rows='4' class='form-control' disabled>{$data}</textarea>");
		// 			};
		// 		}
		// 	}
		// }


		$datas = (object) [
			'title' => 'Mutasi Stock Antar Depo',
			'barcodeGenerate' => new Picqer\Barcode\BarcodeGeneratorPNG(),
			'datas' => $dataCetak
		];

		return $datas;
	}
}
