<?php
	require( 'backend/conf/vars.php' );
	require( 'backend/helper/class.helper.php' );
	
	$hlp = new chlp();
	
	if(isset($_GET['c']))
		$content_code = $_GET['c'];
	else
	{
		$content_code = 'home';
	}
	
	$M_VIEW_ITEMS =( isset($_GET['view']) || isset($_GET['subview']) );
	$M_VIEW_PROD =( isset($_GET['prod']) );
	
	$lnk_b = "index.php?c=${content_code}"; //&view=$pcatgry&prod=".base64_encode($pcode);
	
	$pprod_style_class = 'navhead-prod-field';
	
	if($content_code == 'fld')
	{
		$content_title = 'Field - Bombay Sports';
		$pprod = "FIELD SPORT";
		$pprod_style_class = 'navhead-prod-field';
	}
	else if($content_code == 'rct')
	{
		$content_title='Racket - Bombay Sports';
		$pprod = "RACKET SPORT";
	}
	else if($content_code == 'rcr')
	{
		$content_title='Recreation - Bombay Sports';
		$pprod = "RECREATION";
	}
	else if($content_code == 'fit')
	{
		$content_title='Fitness - Bombay Sports';
		$pprod = "FITNESS";
	}	
	else if($content_code == 'cot')
	{
		$content_title='Court - Bombay Sports';
		$pprod = "COURT SPORT";
	}
	else if($content_code == 'xtm')
	{
		$content_title='Miscellaneous - Bombay Sports';
		//$pprod = "EXTREME";
		$pprod = "Miscellaneous";
	}
	else if($content_code == 'tri')
	{
		$content_title='CARDIO - Bombay Sports';
		$pprod = "CARDIO";
	}
	else if($content_code == 'cke')
	{
		$content_title='Cricket - Bombay Sports';
		$pprod = "CRICKET";
		//$pprod = " CRICKET";
	}
	else if($content_code == 'abt')
	{
		$content_title='About Us - Bombay Sports';
	}
	else if($content_code == 'cnt')
	{
		$content_title='Contact Us - Bombay Sports';
	}
	else if($content_code == 'home')
	{
		$content_title='Home - Bombay Sports';
	}
	
	$hlght_link = '';
	$nav_loc = array();
	if( isset($pprod) )
	{
	
		/* $nav_loc []= array($hlp->properCase($pprod),$lnk_b);
	
		if( isset($_GET['view'] ) )
		{
			$hlght_link =  $_GET['view'];
			if($nav_loc[0][0] != $hlp->properCase($hlght_link))
				$nav_loc []= array($hlp->properCase($hlght_link), $lnk_b."&view=${hlght_link}");
			
			if( isset($_GET['subview']) )
			{
				$sub_view = $_GET['subview'];
				$nav_loc []= array($hlp->properCase($sub_view), $lnk_b."&view=${hlght_link}&subview=${sub_view}");
			}
		} */
		
		$nav_loc []= array($hlp->titleCase($pprod),$lnk_b);
		if( isset($_GET['view'] ) ) // when category is clicked, in left menu bar
		{
			$hlght_link =  $_GET['view'];
			// commented because , for cricket, nav_loc00 is same as highlight_link
			/* if($nav_loc[0][0] != $hlp->titleCase($hlght_link))
			{
				$nav_loc []= array($hlp->titleCase($hlght_link), $lnk_b."&view=${hlght_link}");
			} */
			$nav_loc []= array($hlp->titleCase($hlght_link), $lnk_b."&view=${hlght_link}");
			
			if( isset($_GET['subview']) )
			{
				$sub_view = $_GET['subview'];
				$nav_loc []= array($hlp->titleCase($sub_view), $lnk_b."&view=${hlght_link}&subview=${sub_view}");
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title><?php echo("$content_title"); ?></title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="description" content="Bombay sports" />
	<meta name="keywords" content="Bombay sports" />
	<meta name="robots" content="index, follow" />
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="styles/screen.css" media="screen" />
	
	<script type="text/javascript" src="js/jquery-1.6.js"></script>
	
<?php
	//if($M_VIEW_ITEMS)
		echo('<script type="text/javascript" src="js/jquery.lazyload.min.js"></script>');
	//else if($M_VIEW_PROD)
		echo('<script src="js/jquery.jqzoom-core.js" type="text/javascript"></script><link rel="stylesheet" href="styles/jquery.jqzoom.css" type="text/css" />');
?>

	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<script type="text/javascript" src="js/infobox.js"></script>
		
		<script type="text/javascript">
			
			function initialize() {
				try{
					/* var latlng = new google.maps.LatLng(18.943452,72.829037);
					var settings = {
						zoom: 15,
						center: latlng,
						mapTypeControl: true,
						mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
						navigationControl: true,
						navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					var map = new google.maps.Map(document.getElementById("map_canvas"), settings);
					
					var contentString = '<div id="content">'+
					'<div id="siteNotice">'+
					'</div>'+
					'<h1 id="firstHeading" class="firstHeading"><img src="img/cropped_277x.png"></h1>'+
					'<div id="bodyContent">'+
					'<p>102/A,Omega Paradise,<br/>Hutatma chowk,Fort,Mumbai(W),Pin:400001</p>'+
					'</div>'+
					'</div>';
		 
					var infowindow = new google.maps.InfoWindow({
					content: contentString
					});

					var companyPos = new google.maps.LatLng(18.943452,72.829037);
					var companyMarker = new google.maps.Marker({
					  position: companyPos,
					  map: map,
					  title:"Bombay Sports"
					});
					
					google.maps.event.addListener(companyMarker, 'click', function() {
					infowindow.open(map,companyMarker);
					}); 
					18.94406
					72.82893
					*/
					var secheltLoc = new google.maps.LatLng(18.943972, 72.828996);

		var myMapOptions = {
			 zoom: 17
			,center: secheltLoc
			,mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		var theMap = new google.maps.Map(document.getElementById("map_canvas"), myMapOptions);


		var marker = new google.maps.Marker({
			map: theMap,
			draggable: false,
			position: new google.maps.LatLng(18.943972, 72.828996),
			visible: true
		});

		var boxText = document.createElement("div");
		boxText.style.cssText = "border: 15px solid white; margin-top: -140px; background: red; color:white; padding: 5px;box-shadow: -10px 10px 5px #888888;";
		boxText.innerHTML = "<div style='text-align:center;padding:0px;font-size:35px;'>BOMBAY SPORTS</div>";

		var myOptions = {
			 content: boxText
			,disableAutoPan: false
			,maxWidth: 0
			,pixelOffset: new google.maps.Size(-140, 0)
			,zIndex: null
			,boxStyle: { 
			  opacity: 0.75
			  ,width: "350px"
			 }
			,closeBoxMargin: "-140px 0px 2px 2px"
			,closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
			,infoBoxClearance: new google.maps.Size(1, 1)
			,isHidden: false
			,pane: "floatPane"
			,enableEventPropagation: false
		};

		google.maps.event.addListener(marker, "click", function (e) {
			ib.open(theMap, this);
		});

		var ib = new InfoBox(myOptions);

		ib.open(theMap, marker);
			}
			catch(err) {console.log("map div not initialized");}
			
		}
		</script>	

	
	<style type="text/css">	.clearfix:after{clear:both;content:".";display:block;font-size:0;height:0;line-height:0;visibility:hidden;}
	.clearfix{display:block;zoom:1}
	.jqzoom{
		text-decoration:none;
		float:left;
	}
	</style>

	<script type="text/javascript">
		window.displayGCS = function() {
			var cse_parent = document.getElementById('gcsparent');
			
			if(cse_parent)
			{
				if(cse_parent.style.display=='none')
					cse_parent.style.display='block';
				else
					cse_parent.style.display='none';
			}
			
			var cse_ob = document.getElementById('cse');
			if(cse_ob)
			{
				cse_ob.style.padding='0px';
				window._cse_inter_id = setInterval( function() {
					if(cse_ob.childNodes && cse_ob.childNodes.length>0)
					{
						cse_ob.childNodes[0].style.padding = '0px';
						//cse_ob.childNodes[0].childNodes[0].style.width='300px';
						clearInterval(window._cse_inter_id);
					}
				}, 100 );
			}
		};
		
	$(document).ready(function() {
		
		if($("img").lazyload)
		{
			$("img").lazyload();
			
			try {
				for( ix=1; ix<=20; ix++)
				{
					if($("#lz"+ix))
						$("#lz"+ix).trigger("appear");
				}
			} catch(e) {}
		}	
		
		if($('.jqzoom').jqzoom)
		{
			$('.jqzoom').jqzoom({
					zoomType: 'standard',
					lens:true,
					preloadImages: false,
					alwaysOn:false
				});
		}
	});

	</script>

</head>

<body onload="initialize();">
	
<?php
	require('top_sports.php');
?>
	
<div class="colmask leftmenu">
	<div class="colleft">
		<div class="col1"> <!-- This is right side column to show products -->
			<?php
				require('gcs.html');
				if (($content_code == 'abt') || ($content_code == 'cnt') || ($content_code == 'home')) // if contact us, acout or home is clicked, require the files
				{
					if( $content_code == 'abt')
						require ('frontend/inc/abt_us.php');
					else if ($content_code == 'cnt')
						require ('frontend/inc/cnt_us.php');
					else if ($content_code == 'home')
						require ('frontend/inc/home.php');
				}
				else // to show products
				{
					if( isset( $_GET['view'] ) ) // to see specific category products
					{
						$view_prod = $_GET['view']; 
						
						if(isset($_GET ['prod'] )) // to see single product with zoom
						{
							$pcode = base64_decode( $_GET ['prod'] );
							
							$q = "select pcode , pname , pbrand , pcatgry , pdesc , pprice , pitem , pimgName from products where pcode ='$pcode';";
							
							$res = $hlp->_db->db_query( $q );
						
							if( $res !== false)
							{
								while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
								{
									// pcode , pname , pbrand , pcatgry , pdesc , pprice , pitem , pthumbId
									$pcode = $row[ 'pcode' ]; 
									$pname = $row[ 'pname' ];
									$pbrand = $row[ 'pbrand' ];
									$pcatgry = $row[ 'pcatgry' ];
									$pdesc = $row[ 'pdesc' ];
									$pprice = number_format( (int)$row[ 'pprice' ], 2, '.', ',' );
									$pitem = $row[ 'pitem' ];
									
									$pimgName = $row [ 'pimgName' ] ;
									
									$dirPath = "backend/data/$pimgName";
									
									if(strlen($pimgName) == 0)
									{
										//$dirPath = "img/dummy"; 
										$dirPath = false;
									}
									
									echo("<div class='clearfix' id='content' style='margin-top:10px;margin-left:10px;width:90%;' >
												<div class=top-head>${pname}</div>");
												
									if($dirPath === false)
									{
										echo("<div class='clearfix'><img src='img/dummy/thumb.png' style='border: 2px solid #ccc;'></div>");
									}
									else
									{
										$s = @getimagesize( "$dirPath/original.jpeg" );
										
										$pimgWidth = $s[ 0 ];
										$highres_img = (intval($pimgWidth ) > 600 );
										
										echo("<div class='clearfix'>".(($highres_img)? ("<a href='$dirPath/original.jpeg' class='jqzoom' rel='gal1' title='${pname}' ><img src='$dirPath/thumbBig.png' title='${pname}' style='border: 2px solid #ccc;'></a>"):((file_exists("$dirPath/thumbBig.png"))?("<img src='$dirPath/thumbBig.png' title='${pname}' style='border: 2px solid #ccc;'>"):("")))."</div>");
									}
									
									echo("<div id='prod_desc'>		
											<b>Price</b> : Rs. ${pprice} /-<br>
											<b>Brand</b> : ${pbrand}<br>
											${pdesc}
										</div>
									</div>");
								}	
							}
							
								
						}// ends pcode set
						
						else  // show all products of a category( when a category is clicked )
						{
							/*
							echo("<div class=gsc style='text-align:center;'><a href='index.php?c=$content_code&view=$view_prod&subview=$pitem' target=_self class=sub-menu-a>".(($pitem == $p_current_item)?("<b>$pitem<b>"):("$pitem"))."</a>");
							
							*/
							//echo("<div class=top-head>${hlght_link}</div>");
							$pcitems = $hlp->getproduct_cat_items($pprod,$hlght_link);
							
							if(isset($_GET['subview'])) // when sub category select box is selected
							{
								$p_current_item = $_GET['subview'] ;
							}
							
							if(sizeof($pcitems)>0)
							{
								echo("<div class=gc-wrap>");
								echo('<select onChange="if(this.value!=\'\') { document.location.replace(this.value);}">');
								echo('<option value="">Filter '.$hlp->properCase($hlght_link).' by...</option>');
								
								for($ipcx=0; $ipcx < sizeof($pcitems); $ipcx++) // as $pitem)
								{
									$pitem = $pcitems[$ipcx];
									echo("<option value='index.php?c=${content_code}&view=${view_prod}&subview=${pitem}'>${pitem}</option>");
									//echo("<div class=gsc style='text-align:center;'>".(($pitem == $p_current_item)?("<span class=sub-menu-no-a>${pitem}</span>"):("<a href='index.php?c=$content_code&view=$view_prod&subview=${pitem}' target=_self class=sub-menu-a>${pitem}</a>")));
									
									//if($ipcx+1 == sizeof($pcitems))
									//	echo("</div>");
									//else
									//	echo("<span class=some-sep>|</span></div>");
								}
								echo('</select></div>');
							}
							
							//isset( $_GET['view'] )
							if(isset($_GET['subview']))
							{
								$pitem = $_GET['subview'] ;
								
								$q = "select pcode , pname , pbrand , pcatgry , pdesc , pprice , pitem ,pimgName from products where pproduct='$pprod' and pcatgry ='$view_prod' and pitem='$pitem' order by pname;";
								
							}
							else
							{
								$q = "select pcode , pname , pbrand , pcatgry , pdesc , pprice , pitem ,pimgName from products where pproduct='$pprod'  and pcatgry ='$view_prod' order by pitem, pname;";
							}
							
							$res = $hlp->_db->db_query( $q );
							
							$cnt = mysql_num_rows($res);
							
							if($cnt > 0)
							{
								if( $res !== false)
								{
									$cur_pitem = false;
									//echo('<div id=thumbnailBlock>');
									while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
									{
										$pcode = $row[ 'pcode' ]; // pcode , pname , pbrand , pcatgry , pdesc , pprice , pitem , pthumbId
										$pname = $row[ 'pname' ];
										$pbrand = $row[ 'pbrand' ];
										$pcatgry = $row[ 'pcatgry' ];
										$pdesc = $row[ 'pdesc' ];
										$pprice = number_format( (int)$row[ 'pprice' ], 2, '.', ',' );
										$pitem = $row[ 'pitem' ];
										
										$pimgName = $row [ 'pimgName' ] ;
										
										$dirPath = "backend/data/$pimgName";
										
										if($cur_pitem === false || $cur_pitem != $pitem)
										{
											if($cur_pitem !== false)
												echo('</div>');
												
											$cur_pitem = $pitem;
											echo("<div class='item-head'>${pitem}</div><div id=thumbnailBlock>");
										}
											
										if(strlen($pimgName) == 0)
										{
											$dirPath = "img/dummy"; 
										}
										
										$prod_link = "index.php?c=$content_code&view=$view_prod&subview=${pitem}&prod=".base64_encode($pcode);
										echo("
											<div class='thumbAll'>
												<a target='_self' href='$prod_link' class='thumbnailBlockImage'>
													<img src='img/dum_load.png' data-original='$dirPath/thumb.png' alt='' width=200px height=200px>
												</a>
												<div class='captionThumb'>
													<table width=100% height=100%><tr><td align=left valign=middle>
														<div><a class=caption-thumb-namea href='$prod_link'>${pname}</a><span style='font-size:0.8em;color: #004B91;'> - $pbrand</span></div>
														<div>Rs. ${pprice} /-</div>		
													</td></tr></table>
												</div>
											</div>");	
									}
									
									echo("</div>");
								}
								else
									echo("Sorry , something went wrong");
							}
							else
								echo("Currently there are no products in this category");	
						} // pcode not set
					} // end view set	
					else // Show all the products of the group like COURT,FIELD( on click of image buttons )
					{
						//echo("<br>");
						$q = "select pcode , pname , pbrand , pproduct , pcatgry , pprice , pitem , pimgName , pdesc from products where pproduct='$pprod' order by pitem, pname;";
						
						$res = $hlp->_db->db_query( $q );
						
						$cnt = mysql_num_rows($res);
						
						if($cnt > 0 )
						{
							if( $res !== false)
							{
								$cur_pitem = false;
								
								while( ( $row = $hlp->_db->db_get( $res ) ) !== false )
								{
									$pcode = $row[ 'pcode' ]; // pcode , pname , pbrand , pcatgry , pdesc , pprice , pitem , pthumbId
									$pname = $row[ 'pname' ];
									$pbrand = $row[ 'pbrand' ];
									$pcatgry = $row[ 'pcatgry' ];
									$pdesc = $row[ 'pdesc' ];
									$pprice = number_format( (int)$row[ 'pprice' ], 2, '.', ',' ); 
									// $pprice = 500; 
									$pitem = $row[ 'pitem' ];
									$pproduct = $row [ 'pproduct' ];
									$pimgName = $row [ 'pimgName' ] ;
									
									$dirPath = "backend/data/$pimgName";
									if(strlen($pimgName) == 0)
									{
										$dirPath = "img/dummy"; 
									}
									
									if($cur_pitem === false || $cur_pitem != $pitem)
									{
										if($cur_pitem !== false)
											echo('</div>');
											
										$cur_pitem = $pitem;
										echo("<div class='item-head'>${pitem}</div><div id=thumbnailBlock>");
									}
									
									$prod_link = "index.php?c=$content_code&view=$pcatgry&subview=${pitem}&prod=".base64_encode($pcode);
									
									echo("<div class='thumbAll'>
											<a target='_self' href='$prod_link' class='thumbnailBlockImage'><img src='img/dum_load.png' data-original='$dirPath/thumb.png' alt='' width=200px height=200px ></a>
												<div class='captionThumb'>
													<table width=100% height=100%><tr><td align=left valign=middle>
														<div><a class=caption-thumb-namea href='$prod_link'>${pname}</a><span style='font-size:0.8em;color: #004B91;'> - $pbrand</span></div>
														<div>Rs. ${pprice} /-</div>		
													</td></tr></table>													
												</div>
										</div>");	
								}
								
								echo("</div>");
							} 
							else
								echo("");
						}
						else
							echo("");
							
					} 
				}//else of abt us		
			?>	
		</div>
		<div class="col2" style='height:100%;'> <!-- This is the left side column for cat list-->
		<?php
			if(sizeof($prod_list)>1)
				echo ("<b><p class=${pprod_style_class} >${pprod}</p></b>"); 
		?>
			<div id=navside>
				<?php			
					$prod_list = $hlp->getproduct_cat($pprod,false);
	
					if(is_array($prod_list))
					{ 
						foreach($prod_list as $pcat => $pitem)
						{
							if( $hlght_link != $pcat )
								echo("<a class=navside-tosel href='index.php?c=$content_code&view=$pcat'>$pcat</a>");
							else if (isset($_GET['prod'])) 
							{
								echo("<a class=navside-tosel href='index.php?c=$content_code&view=$pcat'>$pcat</a>");
							}	
						}
					}
					else if($content_code == 'home' || $content_code == 'abt' || $content_code == 'cnt')
					{
						echo("<a class=navside-tosel href='index.php?c=cke&view=CRICKET'>CRICKET</a>");
						
						echo("<a class=navside-tosel href='index.php?c=rct&view=BADMINTON'>BADMINTON</a>");
						
						echo("<a class=navside-tosel href='index.php?c=fld&view=FOOTBALL'>FOOTBALL</a>");
						
						echo("<a class=navside-tosel href='index.php?c=rcr&view=CARROM'>CARROM</a>");
						
						echo("<a class=navside-tosel href='index.php?c=fit&view=EXERCISE%20MACHINES'>EXERCISE MACHINES</a>");
					}
					else 
						echo("");
				?>
			</ul>			
	   </div>
	</div>
</div>
<div id="footer">
	<table width=100% ><tr><td align=right><span class=declmain>Bombay Sports</span> <span class=declsec>All rights reserved</span></td></tr></table>
</div>

</body>
</html>

