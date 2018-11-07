<?php
	if(isset($_GET['c']))
		$content_code = $_GET['c'];
	else
		$content_code = 'am';
		
	if($content_code == 'fld')
	{
		$content_title='Field - Bombay Sports';
		$content_file = 'inc/field.php';
	}
	else if($content_code == 'rct')
	{
		$content_title='Racket - Bombay Sports';
		$content_file = 'inc/racket.php';
	}
	else if($content_code == 'rcr')
	{
		$content_title='Fitness - Bombay Sports';
		$content_file = 'inc/recreation.php';
	}
	else if($content_code == 'fit')
	{
		$content_title='Fitness - Bombay Sports';
		$content_file = 'inc/fitness.php';
	}
	else
	{
		header('Location: http://bs.testing.mgtech.in');
		die('');
	}	
?>
<!DOCTYPE HTML>
<html xmlns:fb="http://ogp.me/ns/fb#">
<head>

<?php
	echo("<title>${content_title}</title>");
?>
<?php
	require('inc/top.php');
?>
