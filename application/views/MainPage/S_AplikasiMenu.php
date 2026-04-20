<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
	$(document).ready
	(
		function() 
		{
			GetProjectMenu();
		}
	);
	
	function GetProjectMenu()
	{
		$.ajax(
		{
			type: 'POST',    
			url: "<?= base_url('Main/MainAplikasi/GetAplikasiMenu') ?>",
			//data: "Location="+ Location,
			success: function( response )
			{
				if(response)
				{
					ChProjectMenu( response );
				}
			}
		});	
	}
	
	function ChProjectMenu( JSONProject )
	{
		var Project = JSON.parse( JSONProject );
		
		if( Project.AplikasiMenu != 0 )
		{
			for( i=0; i< Project.AplikasiMenu.length ; i++)
			{
				
				var aplikasi_kode 		= Project.AplikasiMenu[i].aplikasi_kode;
				var aplikasi_sign 		= Project.AplikasiMenu[i].aplikasi_sign;
				var aplikasi_keterangan = Project.AplikasiMenu[i].aplikasi_keterangan;
				
				var aplikasi_warna_bg 	= Project.AplikasiMenu[i].aplikasi_warna_bg;
				var aplikasi_warna_font = Project.AplikasiMenu[i].aplikasi_warna_font;
				
				var aplikasi_url		= Project.AplikasiMenu[i].aplikasi_url;
				
				var str = '';
				/*
				str += '<div class="outletrows col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4" style="padding: 0;" onclick="GoToMenu(\''+ aplikasi_kode +'\',\''+ aplikasi_keterangan +'\')">';
				str += '	<img src="<?= base_url('assets/images/aplikasi') ?>/'+ aplikasi_kode +'.jpg" class="img-thumbnail" style="width: 100%; height: 200px;">';

				str += '	<div class="topleft">'+ aplikasi_keterangan +'</div>';
				str += '</div>';
				*/
				
				str += '<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 col-xl-4">';
				str += '	<button type="button" style="height: 200px; margin-bottom:4px; white-space: normal; background-color: '+ aplikasi_warna_bg +'; color: '+ aplikasi_warna_font +';" class="form-control btn btn-primary" onclick="GoToMenu(\''+ aplikasi_kode +'\',\''+ aplikasi_keterangan +'\',\''+ aplikasi_url +'\')">';
				//str += '		<img src="<?= base_url(); ?>"/assets/images/aplikasi/'+ aplikasi_kode +'.jpg" />';
				str += '		<label style="font-size: 20pt; cursor: pointer;"><i class="'+ aplikasi_sign +' fa-lg"></i><br />';
				str += 			aplikasi_keterangan +'</label>';
				str += '	</button>';
				str += '</div>';
				
				$("#divProject").append( str );
			}
		}
	}

	$("#btnlogout").click
	(
		function()
		{
			window.location = '<?= base_url('MainPage/Logout'); ?>';
		}
	);

	function GoToMenu( Mode, ModeKet, ModeUrl )
	{
		var str = ''; 
		str += 	'<form method="POST" action="<?= base_url('Main/MainAplikasi/VerifyAplikasi') ?>">';
		str +=	'	<input type="hidden" id="hdmode" name="hdmode" value="'+ Mode +'" />';
		str +=	'	<input type="hidden" id="hdmodeket" name="hdmodeket" value="'+ ModeKet +'" />';
		str +=	'	<input type="hidden" id="hdmodeurl" name="hdmodeurl" value="'+ ModeUrl +'" />';
		
		str +=	'	<input type="submit" id="btnsubmit" />';
		str +=	'</form>';
		
		$("#submit").append( str );
		
		$("#btnsubmit").trigger('click');
	}
	
</script>