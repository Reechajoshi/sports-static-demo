<?php
	
	header("Expires: Mon, 26 Jul 1990 05:00:00 GMT");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");

	require( 'conf/vars.php' );
	require( 'helper/class.helper.php' );
	
	$me = $_SERVER[ "PHP_SELF" ];
	$hlp = new chlp();
	$arr=$hlp->getProduct_tabs(); //group names from db for supertab names
	
	echo('<html><head>');	
	if( $_GET['a']=='t' )
	{
		echo( '</head>' );
		require( 'inc/frmtop.php' );
	}
	else if( $_GET['a']=='r' )
	{
		require( 'inc/head.php' );//only required for TAB
		require( 'inc/menu_main.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
	}
	else if($_GET['a'] == 'adm')
	{
		$adm = $_GET['a'] ;
		require( 'inc/head.php' );
		require( 'inc/admin/menu.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
	}
	else if( $_GET['a'] == 'img' )
	{
		require( 'inc/head.php' );
		require( 'inc/img/menu.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' );
	}
	else if( $_GET['b'] == 'showimg' )
	{
		require( 'inc/head.php' );
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo('</head>');
		require( 'inc/img/img.php' );
	}
	else if( $_GET['b'] == 'prdall' )
	{
		require( 'inc/head.php' );
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo('</head>');
		if( in_array($_GET['t'],$arr))
		require( 'inc/products/products.php' );
	}
	else if( $_GET['b'] == 'prodnew')
	{
		require( 'inc/head.php' );
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo( "</head>" );
		if( in_array($_GET['t'],$arr))
		require( 'inc/products/new.php' );
	}	
	else if( $_GET['b'] == 'prodedt')
	{
		require( 'inc/head.php' );
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo( "</head>" );
		if( in_array($_GET['t'],$arr))
		require( 'inc/products/edit.php' );
	}
	else if( $_GET['b'] == 'showadm' )
	{	
		require( 'inc/head.php' );
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo('</head>');
		require( 'inc/admin.php' );
	}
	else if( $_GET[ 'b' ] == 'deladmin' )
	{
		require( 'inc/head.php' );
		echo( '<link rel="stylesheet" type="text/css" href="styles/ui.css.x">' );
		echo('</head>');
		require( 'inc/delete_admin.php' );
	}
	else if( $arr !== false && (in_array( $_GET[ 'a' ], $arr )) )
	{	
		require( 'inc/head.php' ); 
		require( 'inc/products/menu.php' );
		echo( '</head>' );
		echo( '<body onload=menuInit();></body>' ); 
	}
	else
	{
		echo( "<head></head><frameset rows='40,*'>
			<frame src='$me?a=t' frameborder=0 marginheight=0 marginwidth=0 name=ft noresize=noresize scrolling=no />
			<frame src='$me?a=r' frameborder=0 marginheight=0 marginwidth=0 name=fb noresize=noresize scrolling=auto />
		</frameset>" );
	}
	echo('</html>');
?>