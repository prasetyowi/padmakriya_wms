<?php

class M_Principle extends CI_Model
{
	private $table_name = "principle";

	private $primary_key = "principle_id";

	private $cols = [
		[
			'name' => 'principle_nama',
			'filterType' => 'similar',
		],
		[
			'name' => 'principle_alamat',
			'filterType' => 'similar',
		],
		[
			'name' => 'principle_telepon',
			'filterType' => 'similar',
		],
		[
			'name' => 'area_id',
			'filterType' => 'specific',
		],
	];

	public function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function countAll()
	{
		$this->db->where('principle_is_deleted', '0');
		$this->db->from($this->table_name);
		return $this->db->count_all_results();
	}

	public function findAll()
	{
		$this->db->where('principle_is_deleted', '0');

		$objects = $this->db->get($this->table_name);
		return $objects->result();
	}

	public function findOneByPK($pkValue)
	{
		$this->db->where($this->primary_key, $pkValue);

		$objects = $this->db->get($this->table_name);
		$results = $objects->result();
		return $results[0];
	}


	public function Get_Principle()
	{
		$this->db->select("principle_id, principle_kode, principle_nama, principle_alamat, principle_telepon, principle_nama_contact_person, principle_telepon_contact_person, principle_email_contact_person, principle_is_aktif")
			->from($this->table_name)
			->where("principle_is_deleted", 0)
			->order_by("principle_nama");
		$query = $this->db->get();

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

    public function Get_Principle_Brand()
	{
		$this->db	->select("principle_brand_id, principle_brand_nama")
					->from("principle_brand")
					->order_by("principle_brand_nama");
		$query = $this->db->get();
		
		if($query->num_rows() == 0)	{	$query = 0;							}
		else						{	$query = $query->result_array(); 	}
			
		return $query;
	}

	public function Getprinciple_by_sku_induk_id( $sku_induk_id )
	{
		$query = $this->db->query("	select 
										p.principle_id, 
										p.principle_kode, 
										p.principle_nama
									from sku_induk as si
										inner join principle as p on p.principle_id = si.principle_id
									where si.sku_induk_id = '$sku_induk_id'");
		
		return $query->row_array();
	}

	public function Getprinciple_brand_by_sku_induk_id( $sku_induk_id )
	{
		$query = $this->db->query("	select 
										p.principle_brand_id, 
										p.principle_brand_nama 
									from sku_induk as si
										inner join principle_brand as p on p.principle_brand_id = si.principle_brand_id
									where si.sku_induk_id = '$sku_induk_id'");
		
		return $query->row_array();
	}

	public function Get_Principle_By_Id($PrincipleId)
	{
		$query = $this->db->query("select 
							principle_propinsi as provinsi, 
							principle_kota as kota, 
							principle_kecamatan as kecamatan, 
							principle_kelurahan as kelurahan, 
							principle_kodepos as kodepos,
							kelas_jalan_id as kelas_jalan,
                            area_id as area,
                            kelas_jalan2_id as kelas_jalan2
							from principle 
							where principle_id = '$PrincipleId'");

		return $query->row_array();
	}

	public function Get_Kecamatan_Id($res)
	{
		$arr = array();
		$provinsi = $res['provinsi'] != null ? $res['provinsi'] : '';
		$kota = $res['kota'] != null ? $res['kota'] : '';
		$kecamatan = $res['kecamatan'] != null ? $res['kecamatan'] : '';
		$kelurahan = $res['kelurahan'] != null ? $res['kelurahan'] : '';
		$kodepos = $res['kodepos'] != null ? $res['kodepos'] : '';
		$kelas_jalan = $res['kelas_jalan'] != null ? $res['kelas_jalan'] : '';
		$area = $res['area'] != null ? $res['area'] : '';
		$kelas_jalan2 = $res['kelas_jalan2'] != null ? $res['kelas_jalan2'] : '';
		$query = $this->db->select("kode_pos_id, kode_pos_kecamatan")
			->from("kode_pos")
			->where("kode_pos_propinsi", $provinsi)
			->where("kode_pos_kabupaten", $kota)
			->where("kode_pos_kecamatan", $kecamatan)
			->get()->row_array();
		if ($query != null) {
			$arr[] = ['provinsi' => $provinsi, 'kota' => $kota, 'kecamatan' => $kecamatan, 'kelurahan' => $kelurahan, 'kodepos' => $kodepos, 'kelas_jalan' => $kelas_jalan, 'area' => $area, 'kelas_jalan2' => $kelas_jalan2, 'kode_pos_id' => $query['kode_pos_id'], 'nama_kode_pos_kecamatan' => $query['kode_pos_kecamatan']];
		} else {
			$arr[] = ['provinsi' => $provinsi, 'kota' => $kota, 'kecamatan' => $kecamatan, 'kelurahan' => $kelurahan, 'kodepos' => $kodepos, 'kelas_jalan' => $kelas_jalan, 'area' => $area, 'kelas_jalan2' => $kelas_jalan2, 'kode_pos_id' => null, 'nama_kode_pos_kecamatan' => null];
		}
		return $arr;
	}

	public function Get_All_Id($val)
	{
		$arr = array();
		$kode_pos_id = $val[0]['kode_pos_id'] != null ? $val[0]['kode_pos_id'] : null;
		$query = $this->db->select("kode_pos_kelurahan_nama, kode_pos_kode")
			->from("kode_pos_detail")
			->where("kode_pos_id", $kode_pos_id)
			->where("kode_pos_kelurahan_nama", $val[0]['kelurahan'])
			->get()->row_array();
		if ($query != null) {
			$arr[] = ['provinsi' => $val[0]['provinsi'], 'kota' => $val[0]['kota'], 'kecamatan' => $val[0]['kecamatan'], 'kelurahan' => $val[0]['kelurahan'], 'kodepos' => $val[0]['kodepos'], 'kelas_jalan' => $val[0]['kelas_jalan'], 'area' => $val[0]['area'], 'kelas_jalan2' => $val[0]['kelas_jalan2'], 'kode_pos_id' => $val[0]['kode_pos_id'], 'kode_pos_kelurahan_nama' => $query['kode_pos_kelurahan_nama']];
		} else {
			$arr[] = ['provinsi' => $val[0]['provinsi'], 'kota' => $val[0]['kota'], 'kecamatan' => $val[0]['kecamatan'], 'kelurahan' => $val[0]['kelurahan'], 'kodepos' => $val[0]['kodepos'], 'kelas_jalan' => $val[0]['kelas_jalan'], 'area' => $val[0]['area'], 'kelas_jalan2' => $val[0]['kelas_jalan2'], 'kode_pos_id' => $val[0]['kode_pos_id']];
		}
		return $arr;
	}

	public function Get_Data_Provinsi()
	{
		$this->db->select("reffregion_tier, reffregion_nama")
			->from("region")
			->distinct()
			->where("reffregion_tier", 2)
			->order_by("reffregion_nama");
		$query = $this->db->get()->result_array();

		return $query;
	}

	public function Get_Data_Kota($provinsi)
	{
		$query = $this->db->select("region_tier, region_tipe, region_nama")
			->from("region")
			->where("reffregion_nama", $provinsi)
			->order_by("region_nama", "ASC")
			->get()->result_array();

		return $query;
	}

	public function Get_Data_Kecamatan($provinsi, $kota)
	{
		$query = $this->db->select("kode_pos_id, kode_pos_kecamatan")
			->from("kode_pos")
			->where("kode_pos_propinsi", $provinsi)
			->where("kode_pos_kabupaten", $kota)
			->order_by("kode_pos_kecamatan", "ASC")
			->get()->result_array();

		return $query;
	}

	public function Get_Data_Kelurahan($kecamatan)
	{
		$query = $this->db->select("kode_pos_kelurahan_nama, kode_pos_kode")
			->from("kode_pos_detail")
			->where("kode_pos_id", $kecamatan)
			->order_by("kode_pos_kelurahan_nama", "ASC")
			->get()->result_array();


		return $query;
	}

	public function Get_Data_KelasJalan()
	{
		$query = $this->db->select("kelas_jalan_id as id, kelas_jalan_nama as nama")
			->from("kelas_jalan")
			->where("kelas_jalan_klasifikasi", "Beban Muatan")
			->order_by("kelas_jalan_nama", "ASC")
			->get()->result_array();
		return $query;
	}

	public function Get_Data_KelasJalan2()
	{
		$query = $this->db->select("kelas_jalan_id as id, kelas_jalan_nama as nama")
			->from("kelas_jalan")
			->where("kelas_jalan_klasifikasi", "Fungsi Jalan")
			->order_by("kelas_jalan_nama", "ASC")
			->get()->result_array();
		return $query;
	}

	public function Get_Data_Area()
	{
		$query = $this->db->select("area_id as id, area_nama as nama")
			->from("area")
			->distinct()
			->order_by("nama", "ASC")
			->get()->result_array();
		return $query;
	}

	public function Insert_Principle($principle_id, $kode_corporate, $name_corporate, $address_corporate, $phone_corporate, $lattitude_corporate, $longitude_corporate, $name_contact_person, $phone_contact_person, $kreditlimit_contact_person, $stretclass_corporate, $stretclass2_corporate, $area_corporate, $province, $city, $districts, $ward, $kodepos_corporate, $isDeleted, $IsAktif)
	{

		$this->db->set("principle_id", $principle_id);
		$this->db->set("principle_kode", $kode_corporate);
		$this->db->set("principle_nama", $name_corporate);
		$this->db->set("principle_alamat", $address_corporate);
		$this->db->set("principle_telepon", $phone_corporate);
		// $this->db->set("principle_corporate_id", $corporate_group);
		$this->db->set("principle_latitude", $lattitude_corporate);
		$this->db->set("principle_longitude", $longitude_corporate);
		$this->db->set("principle_propinsi", $province);
		$this->db->set("principle_kota", $city);
		$this->db->set("principle_kecamatan", $districts);
		$this->db->set("principle_kelurahan", $ward);
		$this->db->set("principle_kodepos", $kodepos_corporate);
		$this->db->set("principle_nama_contact_person", $name_contact_person);
		$this->db->set("principle_telepon_contact_person", $phone_contact_person);
		$this->db->set("principle_kredit_limit", $kreditlimit_contact_person);
		$this->db->set("kelas_jalan_id", $stretclass_corporate);
		$this->db->set("kelas_jalan2_id", $stretclass2_corporate);
		$this->db->set("area_id", $area_corporate);
		$this->db->set("principle_is_deleted", $isDeleted);
		$this->db->set("principle_is_aktif", $IsAktif);


		$this->db->insert("principle");

		// $insert_id = $this->db->insert_id();

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Insert_Principle_detail($principle_id, $status, $no_urut, $hari, $buka, $tutup)
	{
		$this->db->set("principle_detail_id", "NewID()", FALSE);
		$this->db->set("principle_id", $principle_id);
		$this->db->set("principle_detail_is_open", $status);
		$this->db->set("principle_detail_hari_urut", $no_urut);
		$this->db->set("principle_detail_hari", $hari);
		$this->db->set("principle_detail_jam_buka", $buka);
		$this->db->set("principle_detail_jam_tutup", $tutup);
		$this->db->set("principle_detail_is_deleted", 0);
		$this->db->set("principle_detail_is_aktif", 1);

		$this->db->insert("principle_detail");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $affectedrows;
	}

	public function Insert_Principle_brand($principle_id, $nama)
	{
		$this->db->set("principle_brand_id", "NewID()", FALSE);
		$this->db->set("principle_id", $principle_id);
		$this->db->set("principle_brand_nama", $nama);
		$this->db->set("principle_brand_is_deleted", 0);
		$this->db->set("principle_brand_is_aktif", 1);

		$this->db->insert("principle_brand");

		$affectedrows = $this->db->affected_rows();

		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $affectedrows;
	}

	public function Update_Principle($principle_id, $kode_corporate, $name_corporate, $address_corporate, $phone_corporate, $lattitude_corporate, $longitude_corporate, $name_contact_person, $phone_contact_person, $kreditlimit_contact_person, $stretclass_corporate, $stretclass2_corporate, $area_corporate, $province, $city, $districts, $ward, $kodepos_corporate, $status)
	{
		$this->db->set("principle_kode", $kode_corporate);
		$this->db->set("principle_nama", $name_corporate);
		$this->db->set("principle_alamat", $address_corporate);
		$this->db->set("principle_telepon", $phone_corporate);
		// $this->db->set("principle_corporate_id", $corporate_group);
		$this->db->set("principle_latitude", $lattitude_corporate);
		$this->db->set("principle_longitude", $longitude_corporate);
		$this->db->set("principle_propinsi", $province);
		$this->db->set("principle_kota", $city);
		$this->db->set("principle_kecamatan", $districts);
		$this->db->set("principle_kelurahan", $ward);
		$this->db->set("principle_kodepos", $kodepos_corporate);
		$this->db->set("principle_nama_contact_person", $name_contact_person);
		$this->db->set("principle_telepon_contact_person", $phone_contact_person);
		$this->db->set("principle_kredit_limit", $kreditlimit_contact_person);
		$this->db->set("kelas_jalan_id", $stretclass_corporate);
		$this->db->set("kelas_jalan2_id", $stretclass2_corporate);
		$this->db->set("area_id", $area_corporate);
		$this->db->set("principle_is_aktif", $status);

		$this->db->where("principle_id", $principle_id);

		$this->db->update("principle");

		$affectedrows = $this->db->affected_rows();
		if ($affectedrows > 0) {
			$queryinsert = 1;
		} else {
			$queryinsert = 0;
		}

		return $queryinsert;
	}

	public function Get_NewID()
	{
		$querybatuid = $this->db->query("select newid() as NEW_ID");

		return $querybatuid->result_array();
	}

	public function Get_Data_Principle_By_Id($PrincipleID)
	{
		$query = $this->db->query("select 
                                        a.principle_id as id,
										a.principle_kode as kode,
                                        a.principle_nama as nama,
                                        a.principle_alamat as alamat,
                                        a.principle_telepon as telepon,
                                        a.principle_propinsi as provinsi,
                                        a.principle_kota as kota,
                                        a.principle_kecamatan as kecamatan,
                                        a.principle_kelurahan as kelurahan,
                                        a.principle_kodepos as kodepos,
                                        a.principle_latitude as lattitude,
                                        a.principle_longitude as longitude,
                                        a.principle_nama_contact_person as nama_cp,
                                        a.principle_telepon_contact_person as telepon_cp,
                                        a.principle_kredit_limit as kredit_limit_cp,
                                        a.kelas_jalan_id as kelas_jalan,
                                        a.kelas_jalan2_id as kelas_jalan2,
                                        a.area_id as area,
                                        a.principle_is_aktif as aktif,
                                        b.principle_detail_id as id_detail,
                                        b.principle_detail_is_open as status,
                                        b.principle_detail_hari_urut as no_urut,
                                        b.principle_detail_hari as hari,
                                        b.principle_detail_jam_buka as buka,
                                        b.principle_detail_jam_tutup as tutup
                                    from principle as a
									left join principle_detail as b 
										on b.principle_id = a.principle_id
                                    where a.principle_id = '$PrincipleID' order by b.principle_detail_hari_urut ASC");

		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Get_Data_Principle_Brand_By_Id($PrincipleID)
	{
		$query = $this->db->select("principle_brand_id as id, principle_brand_nama as nama")
			->from("principle_brand")
			->where("principle_id", $PrincipleID)
			->order_by("principle_brand_nama", "ASC")
			->get();
		if ($query->num_rows() == 0) {
			$query = 0;
		} else {
			$query = $query->result_array();
		}

		return $query;
	}

	public function Delete_Principle($PrincipleID)
	{

		$this->db->trans_begin();

		$this->db->set('principle_is_deleted', 1);
		$this->db->where("principle_id", $PrincipleID);
		$this->db->update("principle");

		// $this->db->where("principle_id", $PrincipleID);
		// $this->db->delete("principle_detail");

		// $this->db->where("principle_id", $PrincipleID);
		// $this->db->delete("principle_brand");

		$error = $this->db->error();

		if ($error['message'] != '' || $error['message'] != null)
		//if( $error['message'] != '' && $error['message'] != null )
		{
			$res = $error['message']; // Error

			$this->db->trans_rollback();
		} else {
			$this->db->trans_commit();

			$affectedrows = $this->db->affected_rows();
			if (abs($affectedrows) > 0) {
				$res = 1; // Success
			} else {
				$res = 0; // Success
			}
		}

		return $res;
	}

	public function getAttributeLabels($label = '')
	{
		$labels = [
			'principle_nama' => 'Nama',
			'principle_alamat' => 'Alamat',
			'principle_telepon' => 'Telepon',
			'area_id' => 'Area'
		];
		return $label === '' ? $labels : (isset($labels[$label]) ? $labels[$label] : '');
	}

	/*
	 * search data to be used spesifically for datatable
	 * 
	 * Params:
	 * $criteria - array of criteria for filtering. It's key is the column name.
	 * $limit - int
	 * $offset - int
	 * $orders - array of orders, column key is the column name and dir key is the direction asc or desc
	 * $asLookup - false is for list page while true is for lookup modal
	 * 
	 * return array of result for datatable
	 */
	public function search($criteria = array(), $limit = '', $offset = '', $orders = array(), $asLookup = false)
	{
		$records = array();

		$this->db->join('area', 'area.area_id = ' . $this->table_name . '.area_id');

		//limit and offset
		if ($limit != "" && $limit != "-1") {
			$this->db->limit($limit);
			if ($offset != "") {
				$this->db->limit($limit, $offset);
			}
		}

		//filters
		foreach ($this->cols as $col) {
			if (isset($criteria[$col['name']])) {
				if ($col['filterType'] == 'similar') {
					$this->db->like($col['name'], $criteria[$col['name']]);
				} else if ($col['filterType'] == 'specific' && $criteria[$col['name']] != "") {
					$this->db->where($col['name'], $criteria[$col['name']]);
				} else if ($col['filterType'] == 'range' && $criteria[$col['name']][$col['rangeField'][0]] != "" && $criteria[$col['name']][$col['rangeField'][1]] != "") {
					$this->db->where($col['name'] . ' BETWEEN \'' . $criteria[$col['name']][$col['rangeField'][0]] . '\' AND \'' . $criteria[$col['name']][$col['rangeField'][1]] . '\'');
				}
			}
		}

		//sorting
		if (!empty($orders)) {
			foreach ($orders as $order) {
				$this->db->order_by($this->cols[$order['column']]['name'], $order['dir']);
			}
		}

		$this->db->where('principle_is_deleted', '0');

		$objects = $this->db->get($this->table_name);

		$records = [];
		if ($objects != false) {
			foreach ($objects->result() as $object) {
				$row = [];
				$row[] = !empty($object->principle_nama) ? $object->principle_nama : '';
				$row[] = !empty($object->principle_alamat) ? strip_tags($object->principle_alamat) : '';
				$row[] = !empty($object->principle_telepon) ? $object->principle_telepon : '';
				$row[] = !empty($object->area_nama) ? $object->area_nama : '';
				if (!$asLookup) {
					$row[] = '<div style="white-space: nowrap">'
						. getViewButton('DeliveryOrderDraft', 'view', $object->principle_id)
						. getUpdateButton('DeliveryOrderDraft', 'update', $object->principle_id)
						. '</div>';
				} else {
					$row[] = getLookupPickButton('btn-choose-factory', ['id' => $object->principle_id]);
				}

				$records[] = $row;
			}
		}

		return $records;
	}
}
