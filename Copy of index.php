<?php
	$me = $_SERVER["SCRIPT_NAME"];
	$ruri = $_SERVER["REQUEST_URI"];
	
	$isNOJS = isset($_GET[ 'nojs' ]);
	
	if( $isNOJS )
	{
		$me .= '?nojs&';
	}
	else
	{
		if(substr($ruri, -1 * strlen($me)) == $me) // TRUE = no request URI params
			$ruri_nojs = $me.'?nojs&';
		else
			$ruri_nojs = $ruri.'&nojs&';
		
		$me .= '?';		
	}
?>

<html>
	<head>
		<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
		
		<link rel="shortcut icon" href="favicon.ico" />
		
		<meta http-equiv="Content-type" value="text/html; charset=utf-8">
		<meta name="HandheldFriendly" content="false" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8; " />
					
		<meta name="Robots" content="index, follow">
		<meta name="Distribution" content="Global">
		<meta name="Revisit-After" content="1 day">
		<meta name="Reply-to" content="info@mgtech.in">
		<meta name="Author" content="Macgregor Techknowlogy Pvt Ltd">
		
		<title>Bombay Sports</title>
		<link rel="stylesheet" type="text/css" href="styles/main.ui.css.x">
		
		<script type='text/javascript'>
			var _CONTENT_DEF_TEXT_SIZE = 12;
<?php
			echo( 'var requestURI = "'.$_SERVER["REQUEST_URI"]."\";\n" );
			echo( 'var requestFile = "'.$_SERVER["SCRIPT_NAME"]."\";\n" );
?>
			var ixHash = document.URL.indexOf( requestURI + '#');
			if(ixHash != -1)
			{
				window.location.replace( requestFile + '?fl=' + document.URL.substr( ixHash + requestURI.length + 1 ) );				
			}
			else
			{
				var ixFL = document.URL.indexOf( requestFile + '?fl=');
				if( ixFL != -1 )
				{
					location.hash = "#" + document.URL.substr( ixFL + requestFile.length + 4 );
 				}
			}			
		</script>
		
		<script type='text/javascript' src='frontend/src/helper.js'></script>
		<script type='text/javascript' src='frontend/src/talk.js'></script>
		<script type='text/javascript' src='frontend/src/ui.js'></script>
		<script type='text/javascript' src='frontend/src/util.js'></script>
	</head>
	
	<body onload='CHelp.init(document);'>
<?php
	if(!$isNOJS)
	{
		echo("<noscript><table border=0 width=100% style='border-bottom: 1px solid #ccc;'><tr><td align=center ><div class='gtxt btxt' style='font-size:14pt'>Your JavaScript is disabled or you are using a browser which doesn't support JavaScript. Please <b><a href='${ruri_nojs}' target=_self>click here</a></b> to browse Akola Chemicals without JavaScript.</div></td></tr></table></noscript>");
	}
?>
		<table width=100% height=100% cellpadding=0 cellspacing=0>
			<tr><td align=center valign=top height=135px style='height:135px'>				
				<table border=0 cellpadding=0 cellspacing=0 width=100%><tr>
					<td align=left valign=top width=520px>						
						<div class=gc><img src="frontend/img/aklogo.png" ></div>
						<div class=gc style="margin-left:70px;">
<?php
						//require('frontend/inc/menu_buttons.php');
?>					
						</div>
					</td><td align=right valign=top style="background-color:#fff">					
						<table border=0 cellpadding=0 cellspacing=0 width=100%><tr><td align=right valign=top>
							<iframe src='frontend/slider.html' frameborder="0"  style="width:742px; border:0; background-color:#000000; height:172px;" scrolling="no">
						</iframe>
						</td></tr></table>
					</td></tr>					
				</table>
			</td></tr>
			<tr><td align=center valign=top style="background-image: url('frontend/img/bbar.png');background-repeat: repeat-x; background-attachment: scroll; background-position: left top;height:12px;" >
				<div class=gc style='overflow:hidden;height:12px;'>&#160;</div>
			</td></tr>
			<tr><td align=left valign=top style='padding-left:10px;'>
				<table width=100% cellpadding=3 cellspacing=3>
					<tr><td align=left valign=top width=400px>
<?php
						//require('frontend/inc/menu_products.php');
?>
					</td><td align=center valign=top>
						<div class=gc-wrap id=maincon style="margin-top:15px;width:90%">
<?php
						//require('frontend/content.php');
?>							
						</div>
					</td></tr>
				</table>
			</td></tr>
			<tr><td align=left valign=bottom style='padding-top:10px;'>
				<div class='gc bar-slim-top' style='text-align:right'>&#169; Akola Chemicals (India) Ltd.. All Rights Reserved</div>
			</td></tr>
		</table>
	</body>
</html>