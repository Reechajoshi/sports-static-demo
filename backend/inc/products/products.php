<?php
	
	$t = $_GET['t'];
	$parent_tab = 'TAB_GROUPS';
	$pgnum = 1;
	$frm_submit = "$me?b=prdall&t=".$t;
	$comboHTML = false;
	$srctxt = false;
	$frmname = "allfld";
		
	if( isset( $_GET[ 'ac' ] ) )
	{
		$pcode = base64_decode( $_GET[ 'pcode' ] );
		if( $_GET[ 'ac' ] == 'd' )
		{
			$q =" select pimgName from products where pcode='$pcode';";
			
			$res = $hlp->_db->db_query( $q );
			
			if( $res !== false )
			{
				if(( $row = $hlp->_db->db_get( $res ) ) !== false )
				{
					$imgname = $row['pimgName'];
					
					$ret = 0;
					
					//if( $imgname != '' )
					//{
					//	exec("rm -rf data/$imgname",$outa,$ret);
					//}
					
					if( true )
					{
						$q = "delete from products where pcode ='$pcode';";

						if( $hlp->_db->db_query( $q ) )
						{
							$hlp->echo_ok( "Product removed." );
						}
						else
							$hlp->echo_err( "Sorry, unable to remove product." );
					}
					else
						$hlp->echo_err( "Sorry, unable to remove product." );
				}
			}		
		}
	}
	
	echo( '<div class="gencon icheight txt buttonmenuwithbg" >' );
	echo( $hlp->getLinkAncHtml('anewc',100,'asb rviewdash','#','addDynTabDirect("'.$parent_tab.'","New Product","org","New Product in '.$t.'","'.$me.'?b=prodnew&t='.$t.'")',20,'images/ic/newc.png','New') );
	
	echo( '</div>' );
	
	if( isset( $_GET['cbo'] ) )
	{
		$pgnum = $_POST['pageCombo'];
		$srctxt = trim( $_POST[ 'cbosrctxt' ] );
	}	
	
	if( isset( $_POST[ 'srctxt' ] ) )
		$srctxt = trim( $_POST[ 'srctxt' ] );
	
	$allx = $hlp->_db->db_return( "select count(*) cnt from products ;", array( 'cnt' ) );
	$allcnt = intval( $allx[0] );
	if( $allcnt > 0 )
	{
		$q = "select count(*) cnt from products  where pname like '%$srctxt%' and pproduct='$t';";
		
		$cntx = $hlp->_db->db_return( $q, array( "cnt" ) );
		$cnt = intval( $cntx[0] );
		
		if( $cnt > 0 )
		{ 
			$q="select pcode , pproduct ,pname , pbrand , pcatgry , pdesc , pprice , pitem ,pimgName from products where pname like '%$srctxt%' and pproduct='$t' order by pdate  desc "; 
			//echo("query  is $q ::");
			
			
			$startIndex = ( ($pgnum-1)*$PROD_SHOW_PER_PAGE );	
			
			if( $cnt > $PROD_SHOW_PER_PAGE )
			{
				$comboHTML = $hlp->getDisplayPageComboHTML( $parent_tab,$cnt,$frm_submit."&cbo",$frmname,$pgnum,$PROD_SHOW_PER_PAGE);
				
				$q .= " LIMIT $startIndex,$PROD_SHOW_PER_PAGE ;";
			}
			
			
			$res = $hlp->_db->db_query( $q );	
			if( $res !== false)
			{
				$showNumRow = intval( $hlp->_db->db_num_rows( $res ) );
						
				if( ( $startIndex + 1 ) === ( $showNumRow + $startIndex ) && $pgnum == 1 )
					echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 1 of 1.</div></div>' );
				else
					echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing '.( $startIndex + 1 )." to ".( $showNumRow + $startIndex ).' of '.$cnt.'.</div></div>' );
				
				$hlp->searchBox( $parent_tab, $frm_submit, $srctxt, $comboHTML, $frmname, false, false );
				
				while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
				{
					$pcode = $row[ 'pcode' ]; 
					$pproduct = $row[ 'pproduct' ]; 
					$pname = $row[ 'pname' ];
					$pbrand = $row[ 'pbrand' ];
					$pcatgry = $row[ 'pcatgry' ];
					$pdesc = $row[ 'pdesc' ];
					$pprice = $row[ 'pprice' ];
					$pitem = $row[ 'pitem' ];
					$pimgName = $row['pimgName'];	
					//echo("pcode , pproduct ,pname , pbrand , pcatgry , pdesc , pprice , pitem ");
					//echo($pcode.'-'.$pproduct.'-'.$pname.'-'.$pbrand.'-'.$pcatgry.'-'.$pdesc.'-'.$pprice.'-'.$pitem);
					$thumbPath = "data/$pimgName/thumb.png";
					
					$caption_style = "color:#8e8e8e;float:left;width:80px;";
					$val_style = "color:#3f95b1;white-space:normal;width:610px;padding-left:5px;";
					echo( '<div name=entrydiv class="gencon bviewdash" style="white-space:nowrap;width:100%;padding-top:10px;" ><table name=tbl class=txt  style="background-color:#f8f8f8;" border=0 width=100%>' );
					echo( '<tr valign=top><td align=left valign=top style="width:400px;">
						<div ><table class=txt valign=top><tr valign=top><td valign=top>' );
					if( file_exists( $thumbPath ) )	
						echo( '<div style="float:left" ><img width=150 height=150 src="'.$thumbPath.'" />&#160;&#160;&#160;</div></td><td>' );
					echo( '<div id="txt" style = color:#10647e;><b>'.$pname.'</b></div>' );
					echo( '<div style="padding-top:7px;" >
							<table class=txt style="width:300px;" valign=top>
							
								<tr valign=top>
									<td valign=top style="'.$caption_style.'" ><b>Item:</b></td><td style="'.$val_style.'" >'.$hlp->trimText($pitem).'&#160;</td>
								</tr>
								<tr valign=top>
									<td valign=top style="'.$caption_style.'" ><b>Price:</b></td><td style="'.$val_style.'" >'.$pprice.'&#160;</td>
								</tr>
								<tr valign=top>	
									<td valign=top style="'.$caption_style.'" ><b>Desc:</b></td><td style="'.$val_style.'" >'.$hlp->trimText( $pdesc ).'&#160;</td>
								</tr>
			
							</table>		
						<div></td></tr></table></td><td valign=top><div>' );
				
					echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ','#','tt="Edit :'.addslashes( $pname ).'";addDynTabMain("'.$parent_tab.'","Edit ","'.addslashes( $pname ).'",tt,"'.$me.'?b=prodedt&t='.$t.'&pcode='.base64_encode( $pcode ).'",true, true);',20,'images/ic/itick.gif','Edit',$parent_tab ) );
					
					
					echo( $hlp->getLinkAncHtml( 'aeditm',60,'asb ',$me.'?b=prdall&t='.$t.'&ac=d&pcode='.base64_encode( $pcode ),'confirm( "Are you sure, you want to delete \"'.addslashes( $pname ).'\"?" )',20,'images/ic/itick.gif','Delete',$parent_tab,true ) );
					
					
					echo( '</div></td></tr>' );
						
					echo( '</table></div>' );
				}	
			}
			else
				echo( "<div style='padding:20px;' >No products to show for search text \"$srctxt\".</div>" );	
		}
		else
		{
			echo( '<div style="padding-top:15px;"><div class="txtheadwithbg" >Showing 0 of 0.</div></div>' );
			$hlp->searchBox( $parent_tab, $frm_submit, $srctxt, $comboHTML, $frmname, false, false );
			echo( "No products to show." );	
		}	
	}
	else
		echo( "<div style='padding:20px;' >No products to show.</div>" );


?>