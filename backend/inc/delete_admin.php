<?php
	
	$uri = $me."?b=deladmin&ac=del";
	
	if( isset( $_GET[ 'ac' ] ) )
	{
		$continue = false;
		if( $_GET[ 'ac' ] == 'del' )
		{
			$all_prod_code = $_POST[ 'pcode' ];
			$product_code = explode( ',', $all_prod_code );
			foreach( $product_code as $p_code )
			{
				$_q = "delete from products where pcode='".trim( $p_code )."';";
				if( $hlp->_db->db_query( $_q ) !== false )
				{
					$continue = true;
				}
			}
			if( $continue )
				$hlp->echo_ok( "Record deleted Successfully." );
			else
				$hlp->echo_err( "Error occured while Deleting Product." );
		}
	}
	
	echo('<form name=frmWrt method="post" action="'.$uri.'" enctype="multipart/form-data">
			<div style="padding-top:10px" >
				<div style="float:left;padding-left:10px;padding-right:10px;font-weight:bold;" >Product Code: </div>
				<div><textarea name="pcode" rows="4" style="width:200px;"></textarea></div> 
			</div> 
			<div style="clear:both"></div>
			<div style="margin-left:100px;padding-top:10px;" ><button type=submit class=roundbutton style="width:110px;" >Delete Products</button></div>
		</form>');

?>