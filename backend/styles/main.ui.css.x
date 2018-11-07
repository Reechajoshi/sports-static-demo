<?php
	include_once('../conf/vars.php');
	include_once('../helper/class.helper.php');
	
	$hlp = new chlp( false );
	
	$pthand = "cursor: ".(($hlp->_isIE)?('hand;'):('pointer;'));
	echo('#acur {'.$pthand.'} ');

	$cssFontType = " font-family: 'Lucida Sans Unicode', Verdana, Tahoma, 'Arial';";
	$cssFontDefSize = "font-size:12pt;";	
	$cssFontBigSize = "font-size:14pt;";	
	$cssFontMidSize = "font-size:11pt;";	
	$cssHeadHeight = (($hlp->_isIE)?('26px'):('22px')); //same source as where php will take in other scrips
	$cssGridHeight = $cssHeadHeight; //same source as where php will take in other scrips
	$cssTabHeight=20;
	$cssHighCol='#0C248D';
	$cssOpCol='#0C2EB6';
?>

body
{
	padding: 0px;
	margin: 0px;
	background-color: #fff;
	<?php echo($cssFontType); ?>
	<?php echo($cssFontDefSize); ?>
}

div.gc
{
	overflow: hidden;
	text-align: left;
	white-space: nowrap;
	clear: right;
	z-index:6;
}

div.gc-wrap
{
	text-align: left;
	white-space: normal;
}

div.txt-big-round
{
	text-align:justify;
	padding:10px;
	border-bottom: 1px solid #ccc;
	-moz-border-radius: 12px;
	-webkit-border-radius: 12px;
	border-radius: 12px;	
}

div.gsc, div.asb
{
	float: left;
	text-align: left;
	white-space: nowrap;
	margin: 0px;
	padding: 0px;
	z-index:11;
}

table
{
	border-collapse: collapse;
	border-spacing: 0px;
	margin: 0px;
	padding: 0px;
}

a.acur
{
	color: #000;
	text-align: left;
	white-space: nowrap;
	text-decoration: none;
	<? echo($pthand.$cssFontType.$cssFontDefSize); ?>	
}

.txt
{
	color: #000;
	text-align: left;
	white-space: nowrap;	
	<? echo($cssFontType.$cssFontDefSize); ?>
}

#txt
{
	color: #000;
	text-align: left;
	white-space: nowrap;	
	<?php echo($cssFontType.$cssFontDefSize); ?>
}

.ibd
{
	position:relative;
	top:-3px;
    font-family: 'Times New Roman','Lucida Sans Unicode',Verdana,'Arial';
    font-size: 11pt;
    font-weight: 700;
	color:#000;
    white-space: nowrap;
}

.ipd
{
	position:relative;
	top:-2px;
    font-family: 'Times New Roman','Lucida Sans Unicode',Verdana,'Arial';
    font-size: 11pt;
    font-weight: 700;
	color:#000;
    white-space: nowrap;
}

.txt-centre
{
	text-align:center:
}

.btxt {
    font-family: 'Times New Roman','Lucida Sans Unicode',Verdana,'Arial';
    font-size: 12pt;
    font-weight: 700;
    white-space: nowrap;
}

.gdtxt
{
	color: #2C412C;	
}

.gtxt {
	color: #486C48;	
}


a:visited.a-high, a:link.a-high, a:active.a-high
{
	text-decoration: none;
	cursor:hand;
}

a:hover.a-high
{
	text-decoration: underline;
	cursor:hand;
}

.ltxt {
    font-size: 14pt;
}

.gprod
{
	padding-left:10px;
}

.gbox
{
	margin: 5px;
	vertical-align: middle;
	border: 1px solid #9BBF9B;
	-moz-border-radius: 6px;
	-webkit-border-radius: 6px;
	border-radius: 6px;	
}

.gbox-shadow
{
	-webkit-box-shadow: 1px 1px 4px 1px rgba(88, 137, 88, 1);
	-moz-box-shadow: 1px 1px 4px 1px rgba(88, 137, 88, 1);
	box-shadow: 1px 1px 4px 1px rgba(88, 137, 88, 1);
}

.gbox-sub
{
	padding: 3px;
	margin-left:40px;
	margin-top:3px;
	margin-bottom:3px;
	border-left: 1px solid #D2E3D2;
	border-right: 1px solid #D2E3D2;
	border-bottom: 1px solid #D2E3D2;
}

.box-title-right
{
	border-right: 1px solid #9BBF9B;
}

.gtxt-box-title
{
	border-bottom: 1px solid #9BBF9B;
	margin: 2px;
	padding-top: 3px;
	padding-bottom: 3px;
	color: #436543;
	background-color: #fff;
	
	font-family: 'Times New Roman','Lucida Sans Unicode',Verdana,'Arial';
    font-size: 14pt;
    font-weight: 400;
    white-space: nowrap;

}

.prod-check
{
	width:10px;
	height:10px;
	overflow:hidden;
	float: left;
	text-align: left;
	white-space: nowrap;
	padding: 0px;
	margin-left: 2px;
	margin-right: 10px;
	margin-top: 0px;
	margin-bottom: 0px;
	background-color: #9BBF9B;
	position:relative;
	top:6px;
}

.prod-side-bar
{
	margin: 2px;
	padding-right: 10px;
	float: left;
	text-align: left;
	white-space: nowrap;
	z-index:11;
}

.bar-slim-top
{
	border-top: 1px solid #ccc;
	padding-top: 3px;
	padding-right: 20px;
	margin-top: 3px;
	font-family: 'Lucida Sans Unicode', Verdana, Tahoma, 'Arial';
	color: #666;   
	font-size: 10pt;
    font-weight: 400;
    white-space: nowrap;
}

.round-box-shadow
{
	-moz-box-shadow:    3px 3px 5px 6px #ccc;
	-webkit-box-shadow: 3px 3px 5px 6px #ccc;
	box-shadow:         3px 3px 5px 6px #ccc;
	-moz-border-radius: 12px;
	-webkit-border-radius: 12px;
	border-radius: 12px;
	
	<?php
		if( !$hlp->_isIE )
		{
			echo( "padding: 20px;");
			echo( "margin: 5px;" );
		}
		else
		{
			echo( "padding-bottom:5px;" );
			echo( "padding-right:5px;" );
		}		
	?>
}

.xcover
{
	position: absolute;
	left:0px;
	top:0px;
	width:50%;
	height:50%;
	z-index:999;
	background-color: #f00;
	opacity: 1; filter:Alpha(Opacity=100);
}
.button {
    border: 1px solid #9BBF9B;
    background: #ccf;
}
.button:hover {
    /* border: 1px solid #9BBF9B;
    background: #eef; */
}