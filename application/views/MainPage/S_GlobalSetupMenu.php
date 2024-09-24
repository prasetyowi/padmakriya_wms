<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
	$(document).ready
	(
		function() 
		{
			GetGlobalSetupMenu();
		}
	);
	
	function GetGlobalSetupMenu()
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Main/MainGlobalSetup/GetGlobalSetupMenu') ?>",
			//data: "Location="+ Location,
			success: function( response )
			{
				if(response)
				{
					ChGlobalSetupMenu( response );
				}
			}
		});	
	}
	
	function ChGlobalSetupMenu( JSONGlobalSetup )
	{
		$("#divGlobalSetup").html('');
		
		var GlobalSetup = JSON.parse( JSONGlobalSetup );
		
		if( GlobalSetup.GlobalSetupMenu != 0 )
		{
			var idx = 0;
			
			for( i=0; i< GlobalSetup.GlobalSetupMenu.length ; i++)
			{
				
				var menu_kode 	= GlobalSetup.GlobalSetupMenu[i].menu_kode;
				var menu_c 		= GlobalSetup.GlobalSetupMenu[i].menu_c;
				var menu_r 		= GlobalSetup.GlobalSetupMenu[i].menu_r;
				var menu_u 		= GlobalSetup.GlobalSetupMenu[i].menu_u;
				var menu_d 		= GlobalSetup.GlobalSetupMenu[i].menu_d;
				var menu_name	= GlobalSetup.GlobalSetupMenu[i].menu_name;
				var menu_link	= GlobalSetup.GlobalSetupMenu[i].menu_link;
				var menu_parent = GlobalSetup.GlobalSetupMenu[i].menu_parent;
				var menu_order	= GlobalSetup.GlobalSetupMenu[i].menu_order;
				
				var str = '';
				
				if( menu_kode != '030001000' && menu_kode != '030002000' )
				{
					str += '<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6" style="padding: 0;" onclick="GoToMenu(\''+ menu_kode +'\',\''+ menu_name +'\',\''+ menu_link +'\')">';
					str += '	<img src="<?= base_url('assets/images/globalsetup') ?>/'+ menu_kode +'.jpg" class="img-thumbnail" style="width: 100%; height: 240px;">';

					str += '	<div class="topleft">'+ menu_name +'</div>';
					str += '</div>';
				}
				else
				{
					str += '<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3 col-xl-3" style="padding: 0;" onclick="GoToMenu(\''+ menu_kode +'\',\''+ menu_name +'\',\''+ menu_link +'\')">'; 
					str += '	<img src="<?= base_url('assets/images/globalsetup') ?>/'+ menu_kode +'.jpg" class="img-thumbnail" style="width: 100%; height: 240px;">';
					str += '	<div class="topleft">'+ menu_name +'</div>';
					str += '</div>';
				}
					
				$("#divGlobalSetup").append( str );
			}
		}
	}

	$("#btnback").click
	(
		function()
		{
			window.location = '<?= base_url('Main/MainAplikasi/MainAplikasiMenu'); ?>';
		}
	);

	$("#btnlogout").click
	(
		function()
		{
			window.location = '<?= base_url('MainPage/Logout'); ?>';
		}
	);

	function GoToMenu( Mode, ModeKet, MenuLink )
	{
		window.location = '<?= base_url(); ?>'+ MenuLink;
	}
	
</script>