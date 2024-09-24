<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MenuAndSub extends CI_Controller {

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
		
		if( $this->session->has_userdata('pengguna_id') == 0):		
			redirect( base_url('MainPage') );
		endif;
		
		$this->MenuKode = "990001000";
	}

	public function MenuAndSubMenu()
	{		
		$this->load->model('M_Menu');
		
		$data = array();		
		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $this->MenuKode);

		if($data['Menu_Access']['R'] != 1){		
			redirect( base_url('MainPage') );
			exit();
		}
		
		$data['Title'] = Get_Title_Name();
		$data['Copyright'] = Get_Copyright_Name();

		if( $this->session->userdata('Mode') == 'UTILITY' )
		{
			$data['sidemenu'] = $this->M_Menu->GetMenu_Sidebar_GlobalSetup('',$this->session->userdata('pengguna_grup_id'));
		}
		else
		{
			$data['sidemenu'] = $this->M_Menu->GetMenu_Sidebar('',$this->session->userdata('pengguna_grup_id'));
		}
		$data['Ses_UserName'] = $this->session->userdata('pengguna_username');
		
		$data['css_files'] = array( 	Get_Assets_Url() . 'vendors/datatables.net-bs/css/dataTables.bootstrap.min.css',
										Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css',
										
										Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.css',
										Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.css',
										Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.css',
										
										Get_Assets_Url() . 'vendors/jquery-ui/jquery-ui.min.css',										
										
										Get_Assets_Url() . 'Global/gojs-customshape.css'
									);
		
		$data['js_files'] 	= array(	Get_Assets_Url() . 'vendors/datatables.net-bs/js/dataTables.bootstrap.min.js',
										Get_Assets_Url() . 'vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js',

										Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.js',
										Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.buttons.js',
										Get_Assets_Url() . 'vendors/pnotify/dist/pnotify.nonblock.js',
										
										Get_Assets_Url() . 'vendors/jquery-ui/jquery-ui.min.js',
										
										Get_Assets_Url() . 'vendors/GoJS/release/go.js',
										Get_Assets_Url() . 'vendors/GoJS/extensions/Figures.js',
										Get_Assets_Url() . 'Global/gojs-draggablelink.js'
																			
									);
		
		// Kebutuhan Authority Menu 
		$this->session->set_userdata('MenuLink', ltrim( current_url(), base_url() ) );
		
		$this->load->view('layouts/sidebar_header', $data);
		$this->load->view('master/HakAkses/MenuAndSub', $data);
		$this->load->view('layouts/sidebar_footer', $data);	
		
		$this->load->view('master/S_GlobalVariable', $data);
		$this->load->view('master/HakAkses/S_MenuAndSub', $data);	
	}
	
	public function GetMenuAndSubMenu()
	{
		$this->load->model('M_MenuAccess');
		$this->load->model('M_Menu');	
		$this->load->model('M_Function');	
		
		if(!$this->M_Menu->CheckMenu($this->session->userdata('pengguna_grup_id'), $this->MenuKode, "R")){		
			echo 0;
			exit();
		}
		
		$data['ApplicationMenu'] = $this->M_Function->Get_Function_GetApplicationName_Except_Utility();
		//$data['MenuAndSubMenu'] = $this->M_Menu->Getmenu_and_sub();
		
		// Mendapatkan url yang ngarah ke sini :		
		$MenuLink = $this->session->userdata('MenuLink');
		$UserGroupID = $this->session->userdata('pengguna_grup_id');
		
		$data['UserGroupID'] = $UserGroupID;
		$data['AuthorityMenu'] = $this->M_MenuAccess->Get_MenuAccess_By_UserGroupID_MenuLink( $UserGroupID, $MenuLink );
		
		echo json_encode( $data );
	}
	
	public function GoToLink( $menu_kode )
	{
		$this->load->library('user_agent');
		$this->load->model('M_Menu');
		$this->load->model('Global/M_Bahasa','M_Bahasa');
		$this->load->model('M_Menu_Node');
		
		$data['Menu_Access'] = $this->M_Menu->Getmenu_access_web($this->session->userdata('pengguna_grup_id'), $menu_kode );

		if($data['Menu_Access']['R'] != 1){	
			
			$bhs = $this->M_Bahasa->Getbahasa_by_bahasa_kode( 'CAPTION-ALERT-ANDATIDAKPUNYAAKSESUNTUKMENUINI', $this->session->userdata('Bahasa'));

			$this->session->set_flashdata('error_redirect', $bhs[0]['bahasa']);
			
			redirect( $this->agent->referrer() );
			exit();
		}
		
		$res = $this->M_Menu_Node->Getmenu_node_detail_menu_link_by_menu_kode( $menu_kode );
		
		$menu_link = $res[0]['menu_link'];
		//print_r ( $menu_link );
		//exit;
		redirect( base_url( $menu_link ) );
	}
	
	public function GetMenuNodeDetailByMenuKode()
	{
		$this->load->model('M_Menu_Node');
		
		$menu_kode = $this->input->post('menu_kode');
		
		$data['MenuNodeDetail'] = $this->M_Menu_Node->Getmenu_node_detail_by_menu_kode( $menu_kode );
		
		echo json_encode( $data );
	}
	
	public function GetMenuRootByMenuApplication()
	{
		$this->load->model('M_Menu');
		
		$aplikasi_kode = $this->input->post('aplikasi_kode');
		
		$data['MenuListMenu'] = $this->M_Menu->Get_Menu_Root_By_Menu_Application( $aplikasi_kode );

		echo json_encode( $data );
	}

	public function ReloadAllSubMenu()
	{
		$this->load->model('M_Menu_Node');
		
		$menu_root = $this->input->post('menu_root');
		$arrMenuKode = implode( ',', $this->input->post('arrMenuKode') );
		
		$data['SubMenu'] = $this->M_Menu_Node->Getmenu_not_in_menu_diagram_by_menu_kode( $menu_root, $arrMenuKode );

		echo json_encode( $data );
	}
	
	public function SaveNodeLayout()
	{
		$this->load->model('M_Menu_Node');
		$this->load->model('M_Vrbl');
		
		$header_id			= $this->input->post('header_id');
		$menu_aplikasi		= $this->input->post('menu_aplikasi');
		$menu_root			= $this->input->post('menu_root');
		$position			= $this->input->post('position');
		$arrMenuNode		= $this->input->post('arrMenuNode');
		$arrMenuNodeDetail	= $this->input->post('arrMenuNodeDetail');
		$arrMenuLink		= $this->input->post('arrMenuLink');
		$arrMenuLinkDetail	= $this->input->post('arrMenuLinkDetail');

		// hapus diagram yang lama 
		$data = $this->M_Menu_Node->Getmenu_diagram_by_aplikasi_kode_menu_kode( $menu_aplikasi, $menu_root );
		if( $data != 0 )
		{
			$menu_diagram_id = $data[0]['menu_diagram_id'];
			
			$resdeletelinkdetail 	= $this->M_Menu_Node->Deletemenu_link_detail_by_menu_diagram_id( $menu_diagram_id );
			$resdeletelink 			= $this->M_Menu_Node->Deletemenu_link_by_menu_diagram_id( $menu_diagram_id );
			$resdeletenodedetail 	= $this->M_Menu_Node->Deletemenu_node_detail_by_menu_diagram_id( $menu_diagram_id );
			$resdeletenode 			= $this->M_Menu_Node->Deletemenu_node_by_menu_diagram_id( $menu_diagram_id );
			$resdeletediagram		= $this->M_Menu_Node->Deletemenu_diagram_by_menu_diagram_id( $menu_diagram_id );
			
		}
		
		$res = $this->M_Vrbl->Get_NewID();
		$menu_diagram_id = $res[0]['NEW_ID'];
		
		
		// menu_node_header
		
		$res = $this->M_Menu_Node->Insertmenu_diagram( $menu_diagram_id, $menu_aplikasi, $menu_root, $position );
		
		if( $arrMenuNode != '' )
		{
			foreach( $arrMenuNode as $valnode )
			{
				$res = $this->M_Vrbl->Get_NewID();
				$menu_node_id = $res[0]['NEW_ID'];
				
				$menu_node_icon = null;
				if( isset( $valnode['menu_node_icon'] ) )
				{
					$menu_node_icon			= $valnode['menu_node_icon'];
				}
				$menu_node_menu_kode	= $valnode['menu_node_menu_kode'];
				$menu_node_menu_name	= $valnode['menu_node_menu_name'];
				$menu_node_location		= $valnode['menu_node_location'];
				
				$menu_node_size = null;
				if( isset( $valnode['menu_node_size'] ) )
				{
					$menu_node_size			= $valnode['menu_node_size'];
				}
				
				$menu_node_shape		= $valnode['menu_node_shape'];
				$menu_node_custom_id	= $valnode['menu_node_custom_id'];
				
				$res = $this->M_Menu_Node->Insertmenu_node( $menu_node_id, $menu_diagram_id, $menu_node_menu_kode, $menu_node_menu_name, 
															$menu_node_location, $menu_node_icon, $menu_node_size, $menu_node_shape, $menu_node_custom_id );
			
				if( $arrMenuNodeDetail != '' )
				{
					foreach( $arrMenuNodeDetail as $valnodedetail )
					{
						$res = $this->M_Vrbl->Get_NewID();
						$menu_node_detail_id = $res[0]['NEW_ID'];
					
						$menu_node_menu_kode			= $valnodedetail['menu_node_menu_kode'];
						
						if( $menu_node_menu_kode == $menu_kode )
						{
							$menu_node_detail_keterangan_1	= null;
							$menu_node_detail_link_1		= null;
							$menu_node_detail_keterangan_2	= null;
							$menu_node_detail_link_2		= null;
							$menu_node_detail_keterangan_3	= null;
							$menu_node_detail_link_3		= null;
							
							if( isset( $valnodedetail['menu_node_detail_keterangan_1'] ) )
							{
								$menu_node_detail_keterangan_1	= $valnodedetail['menu_node_detail_keterangan_1'];
							}
							if( isset( $valnodedetail['menu_node_detail_link_1'] ) )
							{
								$menu_node_detail_link_1		= $valnodedetail['menu_node_detail_link_1'];
							}
							
							if( isset( $valnodedetail['menu_node_detail_keterangan_2'] ) )
							{
								$menu_node_detail_keterangan_2	= $valnodedetail['menu_node_detail_keterangan_2'];
							}
							if( isset( $valnodedetail['menu_node_detail_link_2'] ) )
							{
								$menu_node_detail_link_2		= $valnodedetail['menu_node_detail_link_2'];
							}
							
							if( isset( $valnodedetail['menu_node_detail_keterangan_3'] ) )
							{
								$menu_node_detail_keterangan_3	= $valnodedetail['menu_node_detail_keterangan_3'];
							}
							if( isset( $valnodedetail['menu_node_detail_link_3'] ) )
							{
								$menu_node_detail_link_3		= $valnodedetail['menu_node_detail_link_3'];
							}
						
							$res = $this->M_Menu_Node->Insertmenu_node_detail( 	$menu_node_detail_id, $menu_node_id, 
																				$menu_node_detail_keterangan_1, $menu_node_detail_link_1,
																				$menu_node_detail_keterangan_2, $menu_node_detail_link_2,
																				$menu_node_detail_keterangan_3, $menu_node_detail_link_3 );
						}
					}
				}
			}
		}
		
		if( $arrMenuLink != '' )
		{
			foreach( $arrMenuLink as $vallink )
			{
				$res = $this->M_Vrbl->Get_NewID();
				$menu_link_id = $res[0]['NEW_ID'];
				
				$menu_idx					= $vallink['menu_idx'];
				$menu_link_menu_kode_from	= $vallink['menu_detail_from'];
				$menu_link_menu_kode_to		= $vallink['menu_detail_to'];
				
				$res = $this->M_Menu_Node->Insertmenu_link( $menu_link_id, $menu_diagram_id, $menu_link_menu_kode_from, $menu_link_menu_kode_to );
				
				if( $arrMenuLinkDetail != '' )
				{
					foreach( $arrMenuLinkDetail as $vallinkdetail )
					{
						$res = $this->M_Vrbl->Get_NewID();
						$menu_link_detail_id = $res[0]['NEW_ID'];
					
						$menu_detail_idx		= $vallinkdetail['menu_idx'];
						$menu_link_detail_point	= $vallinkdetail['point'];
						$menu_link_detail_urut	= $vallinkdetail['urut'];
						
						if( $menu_idx == $menu_detail_idx )
						{
							$res = $this->M_Menu_Node->Insertmenu_link_detail( $menu_link_detail_id, $menu_link_id, $menu_link_detail_point, $menu_link_detail_urut );
						}
					}
				}
			}
		}
		
		echo 1;
	}
	
	public function GetMenuChildByMenuKode()
	{
		$this->load->model('M_Menu');
		$this->load->model('M_Menu_Node');
		
		$aplikasi_kode 	= $this->input->post('aplikasi_kode');
		$menu_kode		= $this->input->post('menu_kode');
		
		$data['MenuDiagram'] = $this->M_Menu_Node->Getmenu_diagram_by_aplikasi_kode_menu_kode( $aplikasi_kode, $menu_kode );
		$data['MenuListMenu'] = $this->M_Menu_Node->Getmenu_by_menu_parent( $menu_kode );
		$data['MenuName'] = $this->M_Menu_Node->Getmenu_by_menu_kode( $menu_kode );
		if( $data['MenuDiagram'] == 0 )
		{
			$data['MenuNode'] = 0;
			$data['MenuNodeDetail'] = 0;
			$data['MenuLink'] = 0;
			$data['MenuLinkDetail'] = 0;
		}
		else
		{
			$menu_diagram_id = $data['MenuDiagram'][0]['menu_diagram_id'];
			$data['MenuNode'] 		= $this->M_Menu_Node->Getmenu_node_by_menu_diagram_id( $menu_diagram_id );
			$data['MenuNodeDetail'] = $this->M_Menu_Node->Getmenu_node_detail_by_menu_diagram_id( $menu_diagram_id );
			$data['MenuLink'] 		= $this->M_Menu_Node->Getmenu_link_by_menu_diagram_id( $menu_diagram_id );
			$data['MenuLinkDetail'] = $this->M_Menu_Node->Getmenu_link_detail_by_menu_diagram_id( $menu_diagram_id );
		}
		
		echo json_encode( $data );
	}
	
	public function GetMenuChildByMenuKodeView()
	{
		$this->load->model('M_Menu');
		$this->load->model('M_Menu_Node');
		
		$aplikasi_kode 	= $this->session->userdata('aplikasi_kode');
		$menu_kode		= $this->input->post('menu_kode');
		
		$data['MenuDiagram'] = $this->M_Menu_Node->Getmenu_diagram_by_aplikasi_kode_menu_kode( $aplikasi_kode, $menu_kode );
		$data['MenuListMenu'] = $this->M_Menu_Node->Getmenu_by_menu_parent( $menu_kode );
		
		if( $data['MenuDiagram'] == 0 )
		{
			$data['MenuNode'] = 0;
			$data['MenuNodeDetail'] = 0;
			$data['MenuLink'] = 0;
			$data['MenuLinkDetail'] = 0;
		}
		else
		{
			$menu_diagram_id = $data['MenuDiagram'][0]['menu_diagram_id'];
			$data['MenuNode'] 		= $this->M_Menu_Node->Getmenu_node_by_menu_diagram_id( $menu_diagram_id );
			$data['MenuNodeDetail'] = $this->M_Menu_Node->Getmenu_node_detail_by_menu_diagram_id( $menu_diagram_id );
			$data['MenuLink'] 		= $this->M_Menu_Node->Getmenu_link_by_menu_diagram_id( $menu_diagram_id );
			$data['MenuLinkDetail'] = $this->M_Menu_Node->Getmenu_link_detail_by_menu_diagram_id( $menu_diagram_id );
		}
		
		echo json_encode( $data );
	}
	
	public function GetMenuLinkByMenuKode()
	{
		$this->load->model('M_Menu');
		
		$menu_kode = $this->input->post('menu_kode');
		
		$data['MenuLink']	= $this->M_Menu->Get_Menu_Menu_Link_By_Menu_Kode( $menu_kode );
		//$data['MenuAndSubMenu'] = $this->M_Menu->Getmenu_and_sub();

		echo json_encode( $data );
	}
	
	public function GetMenuStepDetail()
	{
		$this->load->model('M_Menu_Step');
		
		$menu_child = $this->input->post('menu_child');
		
		$data['MenuStepDetailMenu'] = $this->M_Menu_Step->Get_Menu_Step_Detail_By_Menu_Child( $menu_child );
		//$data['MenuAndSubMenu'] = $this->M_Menu->Getmenu_and_sub();

		echo json_encode( $data );
	}
	
}
