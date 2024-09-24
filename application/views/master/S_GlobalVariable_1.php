
<script type="text/javascript">

	var stack_center = {"dir1": "down", "dir2": "right", "firstpos1": 25, "firstpos2": ($(window).width() / 2) - (Number(PNotify.prototype.options.width.replace(/\D/g, '')) / 2)};

	window.location.back = '';
	
	$(document).ready(function() 
	{
		
		
		window.history.pushState(null, "", window.location.href);        
	
		window.onpopstate = function() 
		{
			window.history.pushState(null, "", window.location.href);
		};
		
		var iserror = '<?= $_SESSION['temp_error']; ?>';
		
		if( iserror != '' )
		{
			ShowPNotifyMessage('error', iserror, 'Error' );
			
		}

		Translate();
    });
	
	$(window).resize(function(){
		stack_center.firstpos2 = ($(window).width() / 2) - (Number(PNotify.prototype.options.width.replace(/\D/g, '')) / 2);
	});
	
	function hexToRGB(h) 
	{
		let r = 0, g = 0, b = 0;

		// 3 digits
		if (h.length == 4) 
		{
			r = "0x" + h[1] + h[1];
			g = "0x" + h[2] + h[2];
			b = "0x" + h[3] + h[3];

		// 6 digits
		} 
		else if (h.length == 7) 
		{
			r = "0x" + h[1] + h[2];
			g = "0x" + h[3] + h[4];
			b = "0x" + h[5] + h[6];
		}
	  
		var arrColor = [];
		
		r = +r;
		g = +g;
		b = +b;
		
		arrColor.push( { r, g, b } );
		
		return arrColor;
		
		//return "rgb("+ +r + "," + +g + "," + +b + ")";
	}

	function ShowPNotifyMessage( tipe, Message, Tt )
	{
		var msg = Message;
		var msgtype = tipe;
				
			//if (!window.__cfRLUnblockHandlers) return false;
		new PNotify
		({
			title: Tt,
			text: msg,
			type: msgtype,
			styling: 'bootstrap3',
			delay: 3000,
			stack: stack_center
		});
	}
	
	
	function Translate()
	{
		<?php
			$Bahasa = json_encode( GetBahasa() );
		?>
		
		var Bahasa = JSON.parse('<?= $Bahasa; ?>');

		for( i=0 ; i< Bahasa.length ; i++ )
		{
			var bahasa_id 	= Bahasa[i].bahasa_id;
			var bahasa_kode = Bahasa[i].bahasa_kode;
			var bahasa_nama = Bahasa[i].bahasa_nama;
			var bahasa 		= Bahasa[i].bahasa;
			
			$("label[name="+ bahasa_kode +"]").html( bahasa );
			$("span[name="+ bahasa_kode +"]").html( bahasa );
			$("td[name="+ bahasa_kode +"]").html( bahasa );
			$("th[name="+ bahasa_kode +"]").html( bahasa );
			$("h1[name="+ bahasa_kode +"]").html( bahasa );
			$("h2[name="+ bahasa_kode +"]").html( bahasa );
			$("h3[name="+ bahasa_kode +"]").html( bahasa );
			$("h4[name="+ bahasa_kode +"]").html( bahasa );
			$("h5[name="+ bahasa_kode +"]").html( bahasa );
			$("h6[name="+ bahasa_kode +"]").html( bahasa );
			$("h7[name="+ bahasa_kode +"]").html( bahasa );
			$("a[name="+ bahasa_kode +"]").html( bahasa );
		}
	}
	
	function ShowLanguage()
	{
		$.ajax(
		{
			type: 'POST',
			url: "<?= base_url('Global/Language/GetLanguage') ?>",
			success: function( response )
			{	
				ChShowLanguageMenu( response );
			}
		});
	}
	
	function ChShowLanguageMenu( JSONLanguage )
	{
		var Language = JSON.parse( JSONLanguage );
		
		$("#tbFlag").html('');
		for( i=0 ; i<Language.LanguageMenu.length ; i++ )
		{
			var flag_kode = Language.LanguageMenu[i].flag_kode;
			var flag_nama = Language.LanguageMenu[i].flag_nama;
			
			var ispilihflag = '<?= $_SESSION['Bahasa']; ?>';
			
			var ischecked = '';
			if( ispilihflag == flag_kode )
			{
				ischecked = 'checked="checked"';
			}
			
			var str = '';
			
			str += '<tr>';
			str += '	<td width="10%"><input type="radio" name="rbpilihbahasa" class="checkbox_form chpilihbahasa" data-bahasa="'+ flag_kode +'" '+ ischecked +' /></td>';
			str += '	<td width="20%"><img src="<?= $_SESSION['backend_url'] . 'assets/images/Flag'; ?>/'+ flag_kode +'.png" class="img-responsive" /></td>';
			str += '	<td width="70%"><strong>'+ flag_nama +'</strong></td>';
			str += '</tr>';
			
			$("#tbFlag").append( str );
		}
		
		$("#previewshowlanguage").modal('show');
	}
	
	$("#btnyespilihbahasa").click
	(
		function()
		{
			var lbahasa = $(".chpilihbahasa").length;
			
			var flag_kode = '';
			
			for( i=0 ; i<lbahasa ; i++ )
			{
				if( $(".chpilihbahasa").eq(i).prop('checked') == true )
				{
					flag_kode = $(".chpilihbahasa").eq(i).attr('data-bahasa');
				}
			}
			
			window.location = '<?= base_url('Global/Language/SelectLanguage'); ?>/'+ flag_kode;
		}
	);
	
	/*$('input').on('input', function()
	{
		var c = this.selectionStart,
			r = /[^a-z0-9 ]/gi,
			v = $(this).val();
		if(r.test(v)) 
		{
			$(this).val(v.replace(r, ''));
		c--;
	  }
	  this.setSelectionRange(c, c);
	});*/
</script>
<?php
	$this->load->view('master/S_Message');
?>
</body>
</html>

<?php
	$_SESSION['temp_error'] = '';
?>