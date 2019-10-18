<?php
$path_parts = pathinfo($_SERVER['PHP_SELF']);
$_SESSION['referer'] = $path_parts['basename'];
/*
echo $_SERVER['PHP_SELF']."<br />";			// 		/visu-conf.php
echo $_SESSION['referer']."<br />";			// 		visu-conf.php
//exit;
*/
?>
