<?php

class M_DeliveryOrder extends CI_Model
{
	private $table_name = "delivery_order";

	private $primary_key = "delivery_order_id";

	private $cols = [
		'delivery_order_batch_id' => [
			'name' => 'delivery_order_batch_id',
			'rules' => 'required',
			'label' => 'DO Batch ID'
		],
		'delivery_order_tgl_buat_do' => [
			'name' => 'delivery_order_tgl_buat_do',
			'filterType' => 'range',
			'rangeField' => [
				'start_date',
				'end_date',
			],
			'label' => 'Tanggal Entry DO',
			'rules' => 'required',
		],
		'delivery_order_kode' => [
			'name' => 'delivery_order_kode',
			'filterType' => 'similar',
			'label' => 'DO No.',
			'rules' => 'required',
		],
		'client_pt_id' => [
			'name' => 'client_pt_id',
			'label' => 'Customer',
			// 'customRules' => [
			// 	'callableName' => 'required_if_delivery',
			// 	'callableMessage' => 'Customer is required if you choose Delivery',
			// 	'callableFunction' => 'requiredDelivery'
			// ],
		],
		'principle_id' => [
			'name' => 'principle_id',
			'label' => 'Pabrik',
			// 'customRules' => [
			// 	'callableName' => 'required_if_pickup',
			// 	'callableMessage' => 'Pabrik is required if you choose Pickup',
			// 	'callableFunction' => 'requiredPickup'
			// ],
		],
		'delivery_order_kirim_nama' => [
			'name' => 'delivery_order_kirim_nama',
			'filterType' => 'similar',
			'label' => 'Nama Customer',
		],
		'delivery_order_kirim_alamat' => [
			'name' => 'delivery_order_kirim_alamat',
			'filterType' => 'similar',
			'label' => 'Alamat Kirim Customer',
		],
		'delivery_order_kirim_telp' => [
			'name' => 'delivery_order_kirim_telp',
			'filterType' => 'similar',
			'label' => 'Telp',
		],
		'delivery_order_tipe_pembayaran' => [
			'name' => 'delivery_order_tipe_pembayaran',
			'filterType' => 'specific',
			'label' => 'Tipe Pembayaran',
			'rules' => 'required',
		],
		'delivery_order_tipe_layanan' => [
			'name' => 'delivery_order_tipe_layanan',
			'filterType' => 'specific',
			'label' => 'Tipe Layanan',
			'rules' => 'required',
		],
		'delivery_order_status' => [
			'name' => 'delivery_order_status',
			'filterType' => 'specific',
			'label' => 'Status',
			'rules' => 'required',
		],
		'delivery_order_tgl_expired_do' => [
			'name' => 'delivery_order_tgl_expired_do',
			'label' => 'Tanggal Expired',
			'rules' => 'required',
		],
		'delivery_order_tgl_surat_jalan' => [
			'name' => 'delivery_order_tgl_surat_jalan',
			'label' => 'Tanggal Surat Jalan',
			'rules' => 'required',
		],
		'delivery_order_tgl_rencana_kirim' => [
			'name' => 'delivery_order_tgl_rencana_kirim',
			'label' => 'Tanggal Rencana Kirim',
			'rules' => 'required',
		],
		'delivery_order_keterangan' => [
			'name' => 'delivery_order_keterangan',
			'label' => 'Catatan',
		],
		'tipe_delivery_order_id' => [
			'name' => 'tipe_delivery_order_id',
			'rules' => 'required',
			'label' => 'Tipe',
		],
		'depo_id' => [
			'name' => 'depo_id',
			'filterType' => 'specific'
		],
		'delivery_order_draft_id' => [
			'name' => 'delivery_order_draft_id',
			'label' => 'DO Draft ID',
			'rules' => 'required',
		],
	];

	const DO_CODE = 'KODE_DO';

	const SERVICE_TYPE_DELIVERY = 'Delivery Only';
	const SERVICE_TYPE_PICKUP = 'Pickup Only';
	const SERVICE_TYPE_DELIVERY_PICKUP = 'Delivery and Pickup';

	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function getPrimaryKey()
	{
		return $this->primary_key;
	}

	public function getTableName()
	{
		return $this->table_name;
	}

	public function getAttributeLabels($label)
	{
		return isset($this->cols[$label]) ? $this->cols[$label]['label'] : "";
	}

	public function getAttributes()
	{
		return [
			'delivery_order_id' => $this->delivery_order_id,
			'delivery_order_batch_id' => $this->delivery_order_batch_id,
			'sales_order_id' => $this->sales_order_id,
			'delivery_order_kode' => $this->delivery_order_kode,
			'delivery_order_yourref' => $this->delivery_order_yourref,
			'client_wms_id' => $this->client_wms_id,
			'delivery_order_tgl_buat_do' => $this->delivery_order_tgl_buat_do,
			'delivery_order_tgl_expired_do' => $this->delivery_order_tgl_expired_do,
			'delivery_order_tgl_surat_jalan' => $this->delivery_order_tgl_surat_jalan,
			'delivery_order_tgl_rencana_kirim' => $this->delivery_order_tgl_rencana_kirim,
			'delivery_order_tgl_aktual_kirim' => $this->delivery_order_tgl_aktual_kirim,
			'delivery_order_keterangan' => $this->delivery_order_keterangan,
			'delivery_order_status' => $this->delivery_order_status,
			'delivery_order_is_prioritas' => $this->delivery_order_is_prioritas,
			'delivery_order_is_need_packing' => $this->delivery_order_is_need_packing,
			'delivery_order_tipe_layanan' => $this->delivery_order_tipe_layanan,
			'delivery_order_tipe_pembayaran' => $this->delivery_order_tipe_pembayaran,
			'delivery_order_sesi_pengiriman' => $this->delivery_order_sesi_pengiriman,
			'delivery_order_request_tgl_kirim' => $this->delivery_order_request_tgl_kirim,
			'delivery_order_request_jam_kirim' => $this->delivery_order_request_jam_kirim,
			'tipe_pengiriman_id' => $this->tipe_pengiriman_id,
			'nama_tipe' => $this->nama_tipe,
			'confirm_rate' => $this->confirm_rate,
			'delivery_order_reff_id' => $this->delivery_order_reff_id,
			'delivery_order_reff_no' => $this->delivery_order_reff_no,
			'delivery_order_total' => $this->delivery_order_total,
			'unit_mandiri_id' => $this->unit_mandiri_id,
			'depo_id' => $this->depo_id,
			'client_pt_id' => $this->client_pt_id,
			'delivery_order_kirim_nama' => $this->delivery_order_kirim_nama,
			'delivery_order_kirim_alamat' => $this->delivery_order_kirim_alamat,
			'delivery_order_kirim_provinsi' => $this->delivery_order_kirim_provinsi,
			'delivery_order_kirim_kota' => $this->delivery_order_kirim_kota,
			'delivery_order_kirim_kecamatan' => $this->delivery_order_kirim_kecamatan,
			'delivery_order_kirim_kelurahan' => $this->delivery_order_kirim_kelurahan,
			'delivery_order_kirim_kodepose' => $this->delivery_order_kirim_kodepose,
			'delivery_order_kirim_area' => $this->delivery_order_kirim_area,
			'delivery_order_kirim_invoice_pdf' => $this->delivery_order_kirim_invoice_pdf,
			'delivery_order_kirim_invoice_dir' => $this->delivery_order_kirim_invoice_dir,
			'principle_id' => $this->principle_id,
			'delivery_order_ambil_nama' => $this->delivery_order_ambil_nama,
			'delivery_order_ambil_alamat' => $this->delivery_order_ambil_alamat,
			'delivery_order_ambil_provinsi' => $this->delivery_order_ambil_provinsi,
			'delivery_order_ambil_kota' => $this->delivery_order_ambil_kota,
			'delivery_order_ambil_kecamatan' => $this->delivery_order_ambil_kecamatan,
			'delivery_order_ambil_kelurahan' => $this->delivery_order_ambil_kelurahan,
			'delivery_order_ambil_kodepos' => $this->delivery_order_ambil_kodepos,
			'delivery_order_ambil_area' => $this->delivery_order_ambil_area,
			'delivery_order_update_who' => $this->delivery_order_update_who,
			'delivery_order_update_tgl' => $this->delivery_order_update_tgl,
			'delivery_order_approve_who' => $this->delivery_order_approve_who,
			'delivery_order_approve_tgl' => $this->delivery_order_approve_tgl,
			'delivery_order_reject_who' => $this->delivery_order_reject_who,
			'delivery_order_reject_tgl' => $this->delivery_order_reject_tgl,
			'delivery_order_reject_reason' => $this->delivery_order_reject_reason,
			'delivery_order_no_urut_rute' => $this->delivery_order_no_urut_rute,
			'delivery_order_prioritas_stock' => $this->delivery_order_prioritas_stock,
			'tipe_delivery_order_id' => $this->tipe_delivery_order_id,
			'delivery_order_draft_id' => $this->delivery_order_draft_id,
			'delivery_order_draft_kode' => $this->delivery_order_draft_kode,
		];
	}

	public function setAttributes($data)
	{
		$this->delivery_order_id = isset($data['delivery_order_id']) ? $data['delivery_order_id'] : NULL;
		$this->delivery_order_batch_id = isset($data['delivery_order_batch_id']) ? $data['delivery_order_batch_id'] : NULL;
		$this->sales_order_id = isset($data['sales_order_id']) ? $data['sales_order_id'] : NULL;
		$this->delivery_order_kode = isset($data['delivery_order_kode']) ? $data['delivery_order_kode'] : NULL;
		$this->delivery_order_yourref = isset($data['delivery_order_yourref']) ? $data['delivery_order_yourref'] : NULL;
		$this->client_wms_id = isset($data['client_wms_id']) ? $data['client_wms_id'] : NULL;
		$this->delivery_order_tgl_buat_do = isset($data['delivery_order_tgl_buat_do']) ? date("Y-m-d", strtotime(str_replace("/", "-", $data['delivery_order_tgl_buat_do']))) : NULL;
		$this->delivery_order_tgl_expired_do = isset($data['delivery_order_tgl_expired_do']) ? date("Y-m-d", strtotime(str_replace("/", "-", $data['delivery_order_tgl_expired_do']))) : NULL;
		$this->delivery_order_tgl_surat_jalan = isset($data['delivery_order_tgl_surat_jalan']) ? date("Y-m-d", strtotime(str_replace("/", "-", $data['delivery_order_tgl_surat_jalan']))) : NULL;
		$this->delivery_order_tgl_rencana_kirim = isset($data['delivery_order_tgl_rencana_kirim']) ? date("Y-m-d", strtotime(str_replace("/", "-", $data['delivery_order_tgl_rencana_kirim']))) : NULL;
		$this->delivery_order_tgl_aktual_kirim = isset($data['delivery_order_tgl_aktual_kirim']) ? date("Y-m-d", strtotime(str_replace("/", "-", $data['delivery_order_tgl_aktual_kirim']))) : NULL;
		$this->delivery_order_keterangan = isset($data['delivery_order_keterangan']) ? $data['delivery_order_keterangan'] : NULL;
		$this->delivery_order_status = isset($data['delivery_order_status']) ? $data['delivery_order_status'] : NULL;
		$this->delivery_order_is_prioritas = isset($data['delivery_order_is_prioritas']) ? $data['delivery_order_is_prioritas'] : NULL;
		$this->delivery_order_is_need_packing = isset($data['delivery_order_is_need_packing']) ? $data['delivery_order_is_need_packing'] : NULL;
		$this->delivery_order_tipe_layanan = isset($data['delivery_order_tipe_layanan']) ? $data['delivery_order_tipe_layanan'] : NULL;
		$this->delivery_order_tipe_pembayaran = isset($data['delivery_order_tipe_pembayaran']) ? $data['delivery_order_tipe_pembayaran'] : NULL;
		$this->delivery_order_sesi_pengiriman = isset($data['delivery_order_sesi_pengiriman']) ? $data['delivery_order_sesi_pengiriman'] : NULL;
		$this->delivery_order_request_tgl_kirim = isset($data['delivery_order_request_tgl_kirim']) ? date("Y-m-d", strtotime(str_replace("/", "-", $data['delivery_order_request_tgl_kirim']))) : NULL;
		$this->delivery_order_request_jam_kirim = isset($data['delivery_order_request_jam_kirim']) ? $data['delivery_order_request_jam_kirim'] : NULL;
		$this->tipe_pengiriman_id = isset($data['tipe_pengiriman_id']) ? $data['tipe_pengiriman_id'] : NULL;
		$this->nama_tipe = isset($data['nama_tipe']) ? $data['nama_tipe'] : NULL;
		$this->confirm_rate = isset($data['confirm_rate']) ? $data['confirm_rate'] : NULL;
		$this->delivery_order_reff_id = isset($data['delivery_order_reff_id']) ? $data['delivery_order_reff_id'] : NULL;
		$this->delivery_order_reff_no = isset($data['delivery_order_reff_no']) ? $data['delivery_order_reff_no'] : NULL;
		$this->delivery_order_total = isset($data['delivery_order_total']) ? $data['delivery_order_total'] : NULL;
		$this->unit_mandiri_id = isset($data['unit_mandiri_id']) && $data['unit_mandiri_id'] != 'null' ? $data['unit_mandiri_id'] : NULL;
		$this->depo_id = isset($data['depo_id']) ? $data['depo_id'] : NULL;
		$this->client_pt_id = isset($data['client_pt_id']) ? $data['client_pt_id'] : NULL;
		$this->delivery_order_kirim_nama = isset($data['delivery_order_kirim_nama']) ? $data['delivery_order_kirim_nama'] : NULL;
		$this->delivery_order_kirim_alamat = isset($data['delivery_order_kirim_alamat']) ? $data['delivery_order_kirim_alamat'] : NULL;
		$this->delivery_order_kirim_provinsi = isset($data['delivery_order_kirim_provinsi']) ? $data['delivery_order_kirim_provinsi'] : NULL;
		$this->delivery_order_kirim_kota = isset($data['delivery_order_kirim_kota']) ? $data['delivery_order_kirim_kota'] : NULL;
		$this->delivery_order_kirim_kecamatan = isset($data['delivery_order_kirim_kecamatan']) ? $data['delivery_order_kirim_kecamatan'] : NULL;
		$this->delivery_order_kirim_kelurahan = isset($data['delivery_order_kirim_kelurahan']) ? $data['delivery_order_kirim_kelurahan'] : NULL;
		$this->delivery_order_kirim_kodepose = isset($data['delivery_order_kirim_kodepose']) ? $data['delivery_order_kirim_kodepose'] : NULL;
		$this->delivery_order_kirim_area = isset($data['delivery_order_kirim_area']) ? $data['delivery_order_kirim_area'] : NULL;
		$this->delivery_order_kirim_invoice_pdf = isset($data['delivery_order_kirim_invoice_pdf']) ? $data['delivery_order_kirim_invoice_pdf'] : NULL;
		$this->delivery_order_kirim_invoice_dir = isset($data['delivery_order_kirim_invoice_dir']) ? $data['delivery_order_kirim_invoice_dir'] : NULL;
		$this->principle_id = isset($data['principle_id']) ? $data['principle_id'] : NULL;
		$this->delivery_order_ambil_nama = isset($data['delivery_order_ambil_nama']) ? $data['delivery_order_ambil_nama'] : NULL;
		$this->delivery_order_ambil_alamat = isset($data['delivery_order_ambil_alamat']) ? $data['delivery_order_ambil_alamat'] : NULL;
		$this->delivery_order_ambil_provinsi = isset($data['delivery_order_ambil_provinsi']) ? $data['delivery_order_ambil_provinsi'] : NULL;
		$this->delivery_order_ambil_kota = isset($data['delivery_order_ambil_kota']) ? $data['delivery_order_ambil_kota'] : NULL;
		$this->delivery_order_ambil_kecamatan = isset($data['delivery_order_ambil_kecamatan']) ? $data['delivery_order_ambil_kecamatan'] : NULL;
		$this->delivery_order_ambil_kelurahan = isset($data['delivery_order_ambil_kelurahan']) ? $data['delivery_order_ambil_kelurahan'] : NULL;
		$this->delivery_order_ambil_kodepos = isset($data['delivery_order_ambil_kodepos']) ? $data['delivery_order_ambil_kodepos'] : NULL;
		$this->delivery_order_ambil_area = isset($data['delivery_order_ambil_area']) ? $data['delivery_order_ambil_area'] : NULL;
		$this->delivery_order_update_who = isset($data['delivery_order_update_who']) ? $data['delivery_order_update_who'] : NULL;
		$this->delivery_order_update_tgl = isset($data['delivery_order_update_tgl']) ? $data['delivery_order_update_tgl'] : NULL;
		$this->delivery_order_approve_who = isset($data['delivery_order_approve_who']) ? $data['delivery_order_approve_who'] : NULL;
		$this->delivery_order_approve_tgl = isset($data['delivery_order_approve_tgl']) ? $data['delivery_order_approve_tgl'] : NULL;
		$this->delivery_order_reject_who = isset($data['delivery_order_reject_who']) ? $data['delivery_order_reject_who'] : NULL;
		$this->delivery_order_reject_tgl = isset($data['delivery_order_reject_tgl']) ? $data['delivery_order_reject_tgl'] : NULL;
		$this->delivery_order_reject_reason = isset($data['delivery_order_reject_reason']) ? $data['delivery_order_reject_reason'] : NULL;
		$this->delivery_order_no_urut_rute = isset($data['delivery_order_no_urut_rute']) ? $data['delivery_order_no_urut_rute'] : NULL;
		$this->delivery_order_prioritas_stock = isset($data['delivery_order_prioritas_stock']) ? $data['delivery_order_prioritas_stock'] : NULL;
		$this->tipe_delivery_order_id = isset($data['tipe_delivery_order_id']) ? $data['tipe_delivery_order_id'] : NULL;
		$this->delivery_order_draft_id = isset($data['delivery_order_draft_id']) ? $data['delivery_order_draft_id'] : NULL;
		$this->delivery_order_draft_kode = isset($data['delivery_order_draft_kode']) ? $data['delivery_order_draft_kode'] : NULL;
	}

	public function countAll()
	{
		return $this->db->count_all($this->table_name);
	}

	public function countAllByCriteria($criterias)
	{
		foreach ($criterias as $colName => $colValue) {
			$this->db->where($colName, $colValue);
		}
		return $this->db->count_all_results($this->table_name);
	}

	public function findManyByCriteria($criterias, $asObject = true)
	{
		foreach ($criterias as $criteria) {
			if ($criteria['type'] == 'specific') {
				$this->db->where($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'similar') {
				$this->db->like($criteria['colName'], $criteria['colValue']);
			} else if ($criteria['type'] == 'in') {
				$this->db->where_in($criteria['colName'], $criteria['colValue']);
			}
		}

		$objects = $this->db->get($this->table_name);
		return $asObject ? $objects->result() : $objects->result_array();
	}

	public function findOneByPK($pkValue, $asObject = true)
	{
		$this->db->where($this->primary_key, $pkValue);

		$objects = $this->db->get($this->table_name);
		$results = $asObject ? $objects->result() : $objects->result_array();
		return isset($results[0]) ? $results[0] : NULL;
	}

	public function generateDoNumber($date)
	{
		$this->load->model("M_Depo");
		$depo = $this->M_Depo->Get_Depo_By_Depo_ID($this->session->userdata('depo_id'));

		$this->load->model("M_Vrbl");
		$vrbl = $this->M_Vrbl->Get_Vrbl_By_Param(self::DO_CODE);

		$sql = "
			DECLARE	@ResultKode varchar(50)

			EXEC	[dbo].[GenCodeGeneral]
					@TglTrans = '" . $date . "',
					@Prefik = '" . $vrbl[0]['vrbl_kode'] . "',
					@unit = '" . $depo[0]['depo_kode_preffix'] . "',
					@ResultKode = @ResultKode OUTPUT
		
			SELECT	@ResultKode as 'ResultKode'
		";
		$query = $this->db->query($sql);
		$result = $query->result();

		return $result[0]->ResultKode;
	}

	public function setValidation()
	{
		foreach ($this->cols as $col) {
			if (isset($col['rules']) && $col['rules'] != "" && !isset($col['customRules'])) {
				$this->form_validation->set_rules($col['name'], $col['label'], $col['rules']);
			} else if (isset($col['customRules'])) {
				$customRules = [];
				$customRules[] = array($col['customRules']['callableName'], array($this->M_DeliveryOrderBatch, $col['customRules']['callableFunction']));
				if (isset($col['rules']) && $col['rules'] != "") {
					$rules = $col['rules'];
					if (strpos('|', $col['rules']) !== false) {
						$rules = explode('|', $col['rules']);
					}
					foreach ($rules as $rule) {
						$customRules[] = $rule;
					}
				}
				$this->form_validation->set_rules($col['name'], $col['label'], $customRules);
				$this->form_validation->set_message($col['customRules']['callableName'], $col['customRules']['callableMessage']);
			}
		}
	}

	public function requiredDelivery($str)
	{
		if ($str == "" && ($_POST['delivery_order_tipe_layanan'] == self::SERVICE_TYPE_DELIVERY || $_POST['delivery_order_tipe_layanan'] == self::SERVICE_TYPE_DELIVERY_PICKUP))
			return false;
		return true;
	}

	public function requiredPickup($str)
	{
		if ($str == "" && ($_POST['delivery_order_tipe_layanan'] == self::SERVICE_TYPE_PICKUP || $_POST['delivery_order_tipe_layanan'] == self::SERVICE_TYPE_DELIVERY_PICKUP))
			return false;
		return true;
	}

	public function save()
	{
		$data = $this->getAttributes();

		// We have to add/override the values to the
		// $_POST vars so that form_validation library
		// can work with them.
		foreach ($data as $key => $val) {
			$_POST[$key] = $val;
		}

		$this->setValidation();

		if ($this->form_validation->run() == true) {
			if (empty($data[$this->primary_key])) {
				$this->load->model("M_Function");
				$newId = $this->M_Function->Get_NewID();
				$data[$this->primary_key] = $newId[0]['kode'];

				foreach ($data as $colName => $colValue) {
					if ($colValue != NULL && $colValue != '') {
						$this->db->set($colName, $colValue);
					}
				}

				$this->db->insert($this->table_name);

				if ($this->db->affected_rows() > 0) {
					return $newId[0]['kode'];
				} else {
					return false;
				}
			} else {
				foreach ($data as $colName => $colValue) {
					if ($colValue != NULL && $colValue != '' && $colName != $this->primary_key) {
						$this->db->set($colName, $colValue);
					}
				}

				$this->db->where($this->primary_key, $data[$this->primary_key]);
				$this->db->update($this->table_name);

				if ($this->db->affected_rows() > 0) {
					return $data[$this->primary_key];
				} else {
					return false;
				}
			}
		} else {
			return false;
		}
	}

	public function GetDeliveryOrderHeaderById($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_id,
									do.delivery_order_kode,
									do.sales_order_id,
									FORMAT(do.delivery_order_tgl_buat_do, 'dd/MM/yyyy') AS delivery_order_tgl_buat_do,
									FORMAT(do.delivery_order_tgl_expired_do, 'dd/MM/yyyy') AS delivery_order_tgl_expired_do,
									FORMAT(do.delivery_order_tgl_surat_jalan, 'dd/MM/yyyy') AS delivery_order_tgl_surat_jalan,
									FORMAT(do.delivery_order_tgl_rencana_kirim, 'dd/MM/yyyy') AS delivery_order_tgl_rencana_kirim,
									do.client_wms_id,
									client_wms.client_wms_nama,
									client_wms.client_wms_alamat,
									do.client_pt_id,
									ISNULL(do.delivery_order_kirim_nama, '') AS delivery_order_kirim_nama,
									ISNULL(do.delivery_order_kirim_alamat, '') AS delivery_order_kirim_alamat,
									ISNULL(do.delivery_order_kirim_telp, '') AS delivery_order_kirim_telp,
									ISNULL(do.delivery_order_kirim_provinsi, '') AS delivery_order_kirim_provinsi,
									ISNULL(do.delivery_order_kirim_kota, '') AS delivery_order_kirim_kota,
									ISNULL(do.delivery_order_kirim_kecamatan, '') AS delivery_order_kirim_kecamatan,
									ISNULL(do.delivery_order_kirim_kelurahan, '') AS delivery_order_kirim_kelurahan,
									ISNULL(do.delivery_order_kirim_kodepos, '') AS delivery_order_kirim_kodepos,
									ISNULL(do.delivery_order_kirim_area, '') AS delivery_order_kirim_area,
									do.principle_id,
									ISNULL(do.delivery_order_ambil_nama, '') AS delivery_order_ambil_nama,
									ISNULL(do.delivery_order_ambil_alamat, '') AS delivery_order_ambil_alamat,
									ISNULL(do.delivery_order_ambil_telp, '') AS delivery_order_ambil_telp,
									ISNULL(do.delivery_order_ambil_provinsi, '') AS delivery_order_ambil_provinsi,
									ISNULL(do.delivery_order_ambil_kota, '') AS delivery_order_ambil_kota,
									ISNULL(do.delivery_order_ambil_kecamatan, '') AS delivery_order_ambil_kecamatan,
									ISNULL(do.delivery_order_ambil_kelurahan, '') AS delivery_order_ambil_kelurahan,
									ISNULL(do.delivery_order_ambil_kodepos, '') AS delivery_order_ambil_kodepos,
									ISNULL(do.delivery_order_ambil_area, '') AS delivery_order_ambil_area,
									do.delivery_order_tipe_pembayaran,
									do.delivery_order_tipe_layanan,
									do.tipe_delivery_order_id,
									ISNULL(do.delivery_order_keterangan, '') AS delivery_order_keterangan,
									do.delivery_order_status,
									CAST(delivery_order_nominal_tunai AS numeric(10, 2)) AS delivery_order_nominal_tunai,
									ISNULL(do.delivery_order_attachment, '') AS delivery_order_attachment
									FROM delivery_order do
									LEFT JOIN client_wms
									ON do.client_wms_id = client_wms.client_wms_id
									WHERE do.delivery_order_id = '$id'");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}

	public function GetDeliveryOrderDetailById($id)
	{
		$query = $this->db->query("SELECT
									do.delivery_order_detail_id,
									do.delivery_order_id,
									do.sku_id,
									do.depo_id AS gudang_id,
									do.depo_detail_id AS gudang_detail_id,
									do.sku_kode,
									do.sku_nama_produk,
									do.sku_harga_satuan,
									sku.sku_satuan,
									sku.sku_kemasan,
									ISNULL(do.sku_disc_percent, '0') AS sku_disc_percent,
									ISNULL(do.sku_disc_rp, '0') AS sku_disc_rp,
									ISNULL(do.sku_harga_nett, '0') AS sku_harga_nett,
									ISNULL(do.sku_request_expdate, '0') AS sku_request_expdate,
									ISNULL(do.sku_filter_expdate, '0') AS sku_filter_expdate,
									ISNULL(do.sku_filter_expdatebulan, '0') AS sku_filter_expdatebulan,
									ISNULL(do.sku_filter_expdatetahun, '0') AS sku_filter_expdatetahun,
									ISNULL(do.sku_weight, '0') AS sku_weight,
									ISNULL(do.sku_weight_unit, '') AS sku_weight_unit,
									ISNULL(do.sku_length, '0') AS sku_length,
									ISNULL(do.sku_length_unit, '') AS sku_length_unit,
									ISNULL(do.sku_width, '0') AS sku_width,
									ISNULL(do.sku_width_unit, '') AS sku_width_unit,
									ISNULL(do.sku_height, '0') AS sku_height,
									ISNULL(do.sku_height_unit, '') AS sku_height_unit,
									ISNULL(do.sku_volume, '0') AS sku_volume,
									ISNULL(do.sku_volume_unit, '') AS sku_volume_unit,
									ISNULL(do.sku_qty, '0') AS sku_qty,
									ISNULL(do.sku_keterangan, '') AS sku_keterangan,
									ISNULL(do.tipe_stock_nama, '') AS tipe_stock
									FROM delivery_order_detail do
									LEFT JOIN sku
									ON sku.sku_id = do.sku_id
									WHERE delivery_order_id = '$id'
									ORDER BY sku_kode ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
		// return $this->db->last_query();
	}
}
