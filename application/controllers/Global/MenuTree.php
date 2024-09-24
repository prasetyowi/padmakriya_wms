<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenuTree extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	private $MenuKode;
	
	public function __construct()
	{
		parent::__construct();
	}

	public function GetMenuByMenuKode()
	{		
		$this->load->model('M_Menu');

		$menu_kode = $this->input->post('menu_kode');
		
		$data['MenuTreeMenu'] = $this->M_Menu->Exec_Proc_GetMenuByMenuKode( $menu_kode );
		
		echo json_encode( $data );
	}
}
