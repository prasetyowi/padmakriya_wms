<?php
class M_DataTable extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	function dtTableGetList($sql, $db = null, $addt_filter = array())
	{
		$data = $this->input->post();
		$this->db_selected = (is_null($db)) ? $this->db : $db;
		// echo json_encode(($data)); exit();

		$dt_draw = $this->input->post("draw");
		$dt_start = $this->input->post("start");
		$dt_length = $this->input->post("length");

		$dt_columns_arr = $this->input->post("columns");
		$dt_order_arr = $this->input->post("order");
		$dt_search = $this->input->post("search")['value'];

		// TotalRecords -----------------------------------------------------------
		// $this->db_selected->from('('.$sql.') t1');
		// $TotalRecords = $this->db_selected->count_all_results();
		$this->db_selected->from('( SELECT count(*) res_c FROM (' . $sql . ') t0 ) t1', false);
		$sql_n = $this->db_selected->get_compiled_select();
		$sql_n = str_replace('"', "", $sql_n);
		$query = $this->db_selected->query($sql_n);
		$TotalRecords = $query->result_array()[0]['res_c'];

		// TotalRecordsWithFilter -----------------------------------------------------------
		$this->dtTableGetList_query($sql, $dt_columns_arr, $dt_search, $addt_filter);
		// $TotalRecordsWithFilter = $this->db_selected->count_all_results();
		$sql_n = $this->db_selected->get_compiled_select();
		$sql_n = str_replace('"', "", $sql_n);
		$this->db_selected->from('( SELECT count(*) res_c FROM (' . $sql_n . ') t0 ) t1', false);
		$sql_n = $this->db_selected->get_compiled_select();
		$sql_n = str_replace('"', "", $sql_n);
		$query = $this->db_selected->query($sql_n);
		// echo json_encode($this->db_selected->last_query());
		// exit();
		$TotalRecordsWithFilter = $query->result_array()[0]['res_c'];

		// AllRecords -----------------------------------------------------------
		$this->dtTableGetList_query($sql, $dt_columns_arr, $dt_search, $addt_filter);
		if ($dt_length > 0) {
			$this->db_selected->limit($dt_length, $dt_start);
		}
		if ($dt_order_arr) {
			foreach ($dt_order_arr as $key => $o_value) {
				$this->db_selected->order_by($dt_columns_arr[$o_value['column']]['data'], $o_value['dir']);
			}
		}
		// $AllRecords = $this->db_selected->get()->result_array();
		$sql_n = $this->db_selected->get_compiled_select();
		$sql_n = str_replace('"', "", $sql_n);
		$query = $this->db_selected->query($sql_n);
		$AllRecords = $query->result_array();
		// echo json_encode($this->db_selected->last_query());

		// -----------------------------------------------------------
		$output = array(
			"draw" => $dt_draw,
			"recordsTotal" => $TotalRecords,
			"recordsFiltered" => $TotalRecordsWithFilter,
			"data" => $AllRecords,
		);
		return $output;
	}
	function dtTableGetList_query($sql,  $dt_columns_arr, $dt_search, $addt_filter)
	{
		$this->db_selected->from('(' . $sql . ') t1');
		if ($dt_search != '') {
			$this->db_selected->group_start();
			foreach ($dt_columns_arr as $key => $column) {
				if ($column['searchable'] == 'true') {
					$searchValueArr = explode(' ', $dt_search);
					if (count($searchValueArr) > 1) {
						$x = 0;
						foreach ($searchValueArr as $key => $s_value) {
							if ($x == 0) {
								$x++;
								$this->db_selected->or_like($column['data'], $s_value);
							} else {
								$this->db_selected->like($column['data'], $s_value);
							}
						}
					} else {
						$this->db_selected->or_like($column['data'], $dt_search);
					}
				}
			}
			$this->db_selected->group_end();
		}

		foreach ($addt_filter as $key => $value) {
			if (!empty($value)) {
				$this->db_selected->group_start();
				$this->db_selected->where($value);
				$this->db_selected->group_end();
			}
		}
	}
}
