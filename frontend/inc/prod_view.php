<html>
<head>	
	<script src="js/jquery-1.6.js" type="text/javascript"></script>
	<script src="../../js/jquery.jqzoom-core-pack.js" type="text/javascript"></script>

	<link rel="stylesheet" href="../../styles/jquery.jqzoom.css" type="text/css">
	
	<style type="text/css">

		body{margin:0px;padding:0px;font-family:Arial;}
		a img,:link img,:visited img { border: none; }
		table { border-collapse: collapse; border-spacing: 0; }
		:focus { outline: none; }
		*{margin:0;padding:0;}
		p, blockquote, dd, dt{margin:0 0 8px 0;line-height:1.5em;}
		fieldset {padding:0px;padding-left:7px;padding-right:7px;padding-bottom:7px;}
		fieldset legend{margin-left:15px;padding-left:3px;padding-right:3px;color:#333;}
		dl dd{margin:0px;}
		dl dt{}

		.clearfix:after{clear:both;content:".";display:block;font-size:0;height:0;line-height:0;visibility:hidden;}
		.clearfix{display:block;zoom:1}


		ul#thumblist{display:block;}
		ul#thumblist li{float:left;margin-right:2px;list-style:none;}
		ul#thumblist li a{display:block;border:1px solid #CCC;}
		ul#thumblist li a.zoomThumbActive{
			border:1px solid red;
		}

		.jqzoom{

			text-decoration:none;
			float:left;
		}
		
	</style>
	
	<script type="text/javascript">

		$(document).ready(function() {
		$('.jqzoom').jqzoom({
            zoomType: 'standard',
            lens:true,
            preloadImages: false,
            alwaysOn:false
        });
	
		});

	</script>
</head>
</html>