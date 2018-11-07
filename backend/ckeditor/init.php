<?php
	require("ckeditor/ckeditor.php");
?>
<script type="text/javascript">
	ask_newname = function(eid)
	{
		var el = document.getElementById(eid),
			askstr = ( ( eid == 'pname' )?( "please specify the name of product." ):( "please specify the name of category." ) )
			n = prompt( askstr, '' );
		if(typeof(n)== 'string')
		{
			if(n.length>0)
			{
				el.value=n;	
				var ptm = parent.window.CTabs.getTabObject('TAB_ARTWORKS');
				ptm.modifyTabText(ptm._curSelTabI,"New: " + n);
				return(true);
			}
			else
			{
				alert( "Sorry, name cant be empty." );
				return(ask_newname(eid)); //todo: stack will run out, but that is only if user is silly!!
			}
		}
		else
		{
			document.open;
			document.write('<div class=gencon id=bviewcell>Cancelled</div>');
			document.close();
			return(false);
		}
	}

	ind_change = function(inst)
	{
		parent.window.CTabs.regSafeClose('New Product','All un-saved changes to the product description will be lost, are you sure you want to continue?');
	}
</script>
