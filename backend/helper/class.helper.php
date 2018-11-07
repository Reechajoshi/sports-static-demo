<?php

	include_once( "class.db.php" );
	
	class chlp{
		var $_isIE = false;
		
		var $_db = false;
		var $_db_datastore = false;
		
		function chlp($db_connect = true)
		{
			GLOBAL $DB_NAME, $DB_USER, $DB_PASS;
			
			if( $db_connect )
				$this->_db = new cdb( $DB_NAME, $DB_USER, $DB_PASS );
			
			$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
			$this->_isIE = (strpos($ua,"msie")!==false);
			$this->_isGecko = (strpos($ua,"gecko")!==false);	
		}
		
		function getProduct_tabs() // get values to show as super tabs like COURT,FIELD,RACKET
		{
			$q = " select distinct pproduct from products; ";
			$res = $this->_db->db_query( $q );
			$arr = false;
			if( intval( $this->_db->db_num_rows( $res ) ) > 0 )
			{
				$arr = array();
				while( ( $row = $this->_db->db_get( $res ) ) !== false )
				{
					$arr[] = $row[ 'pproduct' ] ;
				}
			}
			return ($arr);
		}
		
		function getproduct_cat($prod, $getitems)
		{
			$res = $this->_db->db_query( "select distinct pcatgry from products where pproduct = '$prod' order by pcatgry;" );
			
			while( ( $row = $this->_db->db_get( $res ) ) !== false )
			{
				$pcatgry = $row[ 'pcatgry' ];
				$prodcat[$pcatgry] = array();
				
				if($getitems)
				{
					$res_sub = $this->_db->db_query( "select distinct pitem from products where pproduct='${prod}' and pcatgry='${pcatgry}' order by pitem;" );
					
					while( ( $row_sub = $this->_db->db_get( $res_sub ) ) !== false )
					{
						$prodcat[$pcatgry] []= $row_sub[ 'pitem' ];
					}
					
					$this->_db->db_free($res_sub);
				}
			}
			
			$this->_db->db_free($res);
			
			return ($prodcat);	
		}
		
		function getproduct_cat_items($prod, $pcat)
		{
			$res_sub = $this->_db->db_query( "select distinct pitem from products where pproduct='${prod}' and pcatgry='${pcat}' order by pitem;" );
			$prodcat = array();
			
			while( ( $row_sub = $this->_db->db_get( $res_sub ) ) !== false )
			{
				$prodcat[]= $row_sub[ 'pitem' ];
			}
			
			$this->_db->db_free($res_sub);
	
			return ($prodcat);	
		}
		
		function getcatvalues($prod ,$pcatgry) // get html for drop down of each subcat
		{	
			$str = "<select name=pcat>";
			if( is_array( $prod ) )
			{
				
				foreach ( $prod as $key=>$value ) 
				{ 
					
					$str .= "<option value='$key' ".( ( $pcatgry == $key )?( ' SELECTED ' ):( '' ) )." >$key</option>";
					//$str .= "<option value='$value' >$value</option>";
				}
			}
			$str .= "</select>";
			return( $str );
		}
		
		
		
		function getLinkAncHtml($aid,$w,$asb,$anc,$clCB,$imgh,$imgl,$txt,$parentTab = 'TAB_PRODUCTS',$direct = false )
		{
			$clEvnt = 'onclick';
			
			if($anc=='#')
				$clCB .= ';return(false);';
			else if(strpos($clCB,'direct')===0)
				$clCB = 'window.location.replace("'.$anc.'");return(false);';
			else if(strpos($clCB,'confirm')===0)
			{
				if( $direct )
					$clCB = 'if('.$clCB.') { window.location.replace("'.$anc.'"); } return(false);';
				else
					$clCB = 'if('.$clCB.') { CTabs.getTabObject("'.$parentTab.'").submitFormData("'.$anc.'"); } ;return(false);';			
			}	
			else if($clCB=='')
				$clCB = 'CTabs.getTabObject(window.menuid).submitFormData("'.$anc.'");return(false);';
				
			return("<div class='$asb'>
				<a id=$aid href=# $clEvnt='$clCB' target=_self class=acur>
					<table width=$w border=0><tr><td align=center valign=top><img height=$imgh border=0 src='$imgl' /></td></tr>
					<tr><td align=center valign=top><span id=txt>$txt</span></td></tr></table>
				</a>
			</div>");
		}
		

		function catoptions($prodcatno,$pcatgry)
		{
			GLOBAL $PRODCAT_LIST ;
			$prod_list = $PRODCAT_LIST[ $prodcatno ] ;
			$str = "<select name=pcat>";
			if( is_array( $prod_list ) )
			{
				foreach ( $prod_list as $key=>$value ) 
				{ 
					  $str .= "<option value='$key' ".( ( $pcatgry == $key )?( ' SELECTED ' ):( '' ) )." >$value</option>";
				}
			}
			$str .= "</select>";
			return( $str );
		}
	
		function echoCSVHeader()
		{
			$contenttype = "text/csv; charset:UTF-8";
					
			header( "Content-Type: $contenttype" );
			header( 'Content-Disposition: attachment; filename="'.date( 'd-m-Y' ).'_bombay_sports_web.csv"');
			header( 'Accept-Ranges: bytes' );
			header( 'Connection: keep-alive' );
			
			// echo( "\xEF\xBB\xBF" ); //for utf-8 file
		}	
		
		function generateCSV()
		{
			$q = "select * from products order by pproduct , pcatgry ;";
			if( ( $res = $this->_db->db_query( $q ) ) !== false )
			{
				$this->echoCSVHeader();
				echo( "CODE,GROUP,CATEGORY,ITEM,BRAND,NAME,DESCRIPTION,WSP \n" );
				while( ( $row = $this->_db->db_get( $res ) ) !== false )
				{
					// pcode pproduct pcatgry  pitem pbrand pname pdesc   pprice 
					$code = $row[ 'pcode' ];
					$group = $row[ 'pproduct' ];
					$category = $row[ 'pcatgry' ];
					$item = $row[ 'pitem' ];
					$brand = $row[ 'pbrand' ];
					$name = $row[ 'pname' ];
					$description = $row[ 'pdesc' ];
					$wsp = $row[ 'pprice' ];
					
					echo("$code,$group,$category,$item,$brand,$name,$description,$wsp \n");
				}
			}
		
		}
		
		
		function echo_err($m)
		{
			$c='txtheadwithbg';
			echo("<div class='$c gencon'> $m </div>");
		}
		
		function echo_ok($m)
		{
			$c='txtheadwithbg';
			echo("<div class='$c gencon'> $m </div>");
		}
		
		function getDisplayPageComboHTML($parent_tab,$cnt,$frm_submit,$frmname,$page_num,$page_display_sz)
		{
			$page_sz = ceil( $cnt/$page_display_sz );
			$page_combo = "<div id=pagingcombo style='text-align:right;padding-right:10px;'>
							<select name=pageCombo onChange='CUtil.getParentByName(this,\"$frmname\").action=\"$frm_submit\";CUtil.getParentByName(this,\"$frmname\").submit();'>";
			
			$cs = 1;$ce = $page_sz;
			$cbo_resize = false;
			
			if( $page_sz>12 )
			{
				$cbo_resize = true;
				if( $page_num>7 )
					$cs = $page_num-5;
				if( $page_sz>($page_num+5) )	
					$ce = $page_num+5;
			}
			
			if( $cbo_resize )
			{
				$page_combo .= "<option value=1".( ( $page_num==1 )?(' SELECTED '):('') ).">Page 1</option>";
				for( $c = $cs;$c<=$ce;$c++ )
				{
					if( !($c==1 || $c==$page_sz) )
						$page_combo .= "<option value=$c ".( ( $page_num==$c )?(' SELECTED '):('') )." >Page $c</option>";
				}	
				$page_combo .= "<option value=$page_sz ".( ( $page_num==$page_sz )?(' SELECTED '):('') )." >Page $page_sz</option>";	
			}
			else
			{
				for( $c = 1;$c<=$page_sz;$c++ )
					$page_combo .= "<option value=$c ".( ( $page_num==$c )?(' SELECTED '):('') )." >Page $c</option>";
			}
			$page_combo .= "</select>
							</div>";
			return($page_combo);
		}
		
		function searchBox($parent_tab,$frmsubmit,$srctxt,$comboHTML,$frmname='srccontent',$name_ext = false,$beforeCombo = false)
		{
			echo( "<div>
					<form method=post action='$frmsubmit' name=$frmname id=$frmname >
					<div >
						<input type=hidden value='$srctxt' name='cbosrctxt' />
						<div>
							<table class=txt style='width:96%;' >
								<tr>
									<td style='width:60px;' >
										Search :
									</td>
									<td id=srcinputcl >
										<div style='padding-bottom:2px;' >
											<input type=text name='srctxt".( ( $name_ext !== false )?( $name_ext ):( '' ) )."' id=searchclient style='width:500px;' value='$srctxt' onKeyPress='if( CUtil.isKeyEnterPressed(event)) { CUtil.getParentByName( this,\"$frmname\" ).submit(); }' >
										</div>
									</td>
									".( ( $beforeCombo )?( "<td style='text-align:right;' id=cmbtd >$beforeCombo</td>" ):( "" ) )."
									".( ( $comboHTML )?( "<td style='text-align:right;' id=cmbtd >$comboHTML</td>" ):( "" ) )."
								</tr>
							</table>
						</div>
					</form>
				</div>" );
		}  
		function saveproduct()
		{
		/*
			$q = "insert into field(fid , fname , fbrand , fcatgry , fdesc , fprice , fimgName ,  fthumbId  , fimgSize , fimgWidth , fimgHeight , fimgMime  ) values () ;";
		*/	
		
		//	$q ="insert into artworks( Aid , gid , Aname , Adate , Adesc , AimgName ,  ThumbId  , AimgSize , AimgWidth , AimgHeight , AimgMime , Acanvas , Ayear , Acollection ) values ( '$Aid', '$gid' , '$Aname' , now() , '$Adesc' , '$imgn' , '' , '$size' , ".$s[0].",".$s[1].", '$m' , '$Acansize' , '$Ayear' , $Acollection );";
		}
		
		function echoFileHeader($contenttype,$filename,$size,$asattachment = true)
		{
			header( "Content-Type: $contenttype" );
			header( "Content-Disposition: ".( ( $asattachment )?( "attachment" ):( "inline" ) )."; filename=\"".$filename."\"");
			header( "Accept-Ranges: bytes" );
			header( "Content-Length: $size" );
			header( "Connection: keep-alive" );
		}
		
		function format_space($sp)
		{
			if($sp<1024)
				return($sp.' B');
			else if($sp < 1048576)
				return(round($sp/1024,2).' KB');
			else if( $sp < 1073741824 )
				return(round($sp/1048576,2).' MB');
			else 
				return(round($sp/1073741824,2).' GB');
		}
		
		function convertToBytes($size,$form = 'GB')
		{
			if( $form == 'GB' )
				$size = $size*1073741824;
			else if( $form == 'MB' )
				$size = $size*1048576;
			else if( $form == 'KB' )
				$size = $size*1024;
			return( $size )	;
		}
		
		function trimText($str,$size=100)
		{
			if( strlen( $str ) > ($size - 3) )
				return( substr( $str,0,$size-3 )."..." );
			else
				return( $str );
		}
		
		function getunqid($s)
		{
			return(md5(uniqid(time(),true).$s));
		}

	

		function renameGroup($gid,$newname)
		{
			$rename_query = "update groups set gname ='$newname' where gid='$gid';";
			$res = $this->_db->db_query( $rename_query );
			if( $this->_db->db_affected() == 1 )
				return( true );
			else
				return( false );	
		}
				
		
		
		function getProductCategoryCombo($g)
		{
			$q = "select distinct pcatgry from products where pproduct='$prod'; ";
			$res = $this->_db->db_query( $q );
			
			
			$html = "<select name='pcategory' style='width:300px;' >";
			if( $res )
			{
				while( ( $row = $this->_db->db_get( $res ) ) !== false )
				{
					$cid = $row[ 'cid' ];
					$cname = $row[ 'cname' ];
					$html .= "<option value='$cid' ".( ( $g == $cid )?( ' SELECTED ' ):( '' ) )." >$cname</option>";
				}
			}	
			$html .= "</select>";
			return( $html );
		}


		function saveCategory($cname, $chtml, $cid, $ctitle, $cimg)
		{
			$q = "insert ignore into categories ( cid, cname, ctitle, chtml, cimg ) values ( '$cid', '$cname', '$ctitle', '$chtml', '$cimg' ) on duplicate key update cname='$cname', chtml='$chtml', cimg='$cimg', ctitle = '$ctitle'";
			
			return( $this->_db->db_query( $q ) );
		}

		function getMainContentHTML($fl)
		{		
			global $me, $DWN_FILE;
			
			$html = '';
			if($fl == '')
				$html = ( $this->getMainContentHTML( 't'.base64_encode('home') ) );
			else
			{
				$param = base64_decode( substr( $fl, 1 ) );
				
				if($fl[0] == 'c')
				{
					$q = "select categories.cid as cid, categories.cimg as cimg, categories.chtml as chtml, categories.cname as cname, products.pname as pname, products.pid as pid from products, categories where products.pgroup=categories.cid and categories.cid='${param}';";
					
					$res = $this->_db->db_query( $q );
					if( ( $res ) && ( ( $row = $this->_db->db_get( $res ) ) !== false ) )
					{			
						$ctitle = $row[ 'cname' ];
						$chtml = $row[ 'chtml' ];
						$cimg = $row[ 'cimg' ];
						
						if( strlen( $cimg ) > 0 )
							$chtml = "<table><tr><td valign=top ><img src='${DWN_FILE}?iid=".base64_encode( $cimg )."' /></td><td valign=top >$chtml</td></tr></table>";
						
						$html = "<div class='gc box-title-right gtxt-box-title'>${ctitle}</div>${chtml}<div class=gc-wrap>";
						$pid_ex = 'p'.base64_encode( $row[ 'pid' ] );
						$pname = htmlspecialchars( $row[ 'pname' ] );
						
						$html .= '<div class=gsc><div class=prod-check>&#160;</div><div class="prod-side-bar"><a class="a-high gtxt" href=\''.$me.'fl='.$pid_ex.'\' onclick="CHelp.clickMe(\''.$pid_ex.'\');return(false);" name="'.$pname.'">'.$pname.'</a></div></div>';
						while( ( $row = $this->_db->db_get( $res ) ) !== false ) {
							$pid_ex = 'p'.base64_encode( $row[ 'pid' ] );
							$pname = htmlspecialchars( $row[ 'pname' ] );
						
							$html .= '<div class=gsc><div class=prod-check>&#160;</div><div class="prod-side-bar"><a class="a-high gtxt" href=\''.$me.'fl='.$pid_ex.'\' onclick="CHelp.clickMe(\''.$pid_ex.'\');return(false);" name="'.$pname.'">'.$pname.'</a></div></div>';
							
						}
						
						$html .= '</div>';
					}
				}
				else if($fl[0] == 'p')
				{
					$retx = ( $this->_db->db_return( "select pname, phtml, pimg from products where pid='${param}';", array( 'pname', 'phtml', 'pimg' ) ) );
					$html = false;
					if( strlen( $retx[ 2 ] ) > 0 )
							$html = "<table><tr><td valign=top ><img src='${DWN_FILE}?iid=".base64_encode( $retx[ 2 ] )."' /></td><td valign=top >".$retx[ 1 ]."</td></tr></table>";
					else
						$html = $retx[ 1 ];
					$html = '<div class="gc box-title-right gtxt-box-title">'.$retx[ 0 ].'</div>'.$html;
				}
				else if($fl[0] == 't')
				{				
					if( file_exists( "frontend/inc/content_${param}.php" ) )
						$html = ( file_get_contents( "frontend/inc/content_${param}.php" ) );
				}
			}
			
			return( $html );
		}
	
		function properCase($s)
		{
		    $str = strtolower($s);
		    $cap = true;
		   
		    for($x = 0; $x < strlen($str); $x++){
		        $letter = substr($str, $x, 1);
		        if($letter == "." || $letter == "!" || $letter == "?"){
		            $cap = true;
		        }elseif($letter != " " && $cap == true){
		            $letter = strtoupper($letter);
		            $cap = false;
		        }
		       
		        $ret .= $letter;
		    }
		   
		    return $ret;
		}

		function titleCase( $s )
		{
			$cap = true;
			$ret = "";
			for( $i = 0; $i < strlen( $s ); $i++ )
			{
				$letter = ( $cap == true ) ? ( strtoupper( substr( $s, $i, 1 ) ) ) : ( strtolower( substr( $s, $i, 1 ) ) );
					
				if( $letter == ' ' )
				{
					$cap = true;
				}
				else
				{
					$cap = false;
				}	
				$ret .= $letter;
			}
			
			// return ( mb_convert_case( $s, MB_CASE_TITLE, "UTF-8") );
			return $ret;
		}
		
	}
?>