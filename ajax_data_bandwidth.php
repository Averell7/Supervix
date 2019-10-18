<?php
session_start();

	$int="eth0";

if(PHP_OS == 'WINNT') {
	$rx[] = file_get_contents("bw/rx_bytes");
	$tx[] = file_get_contents("bw/tx_bytes");
	sleep(1);
	$rx[] = file_get_contents("bw/rx_bytes");
	$tx[] = file_get_contents("bw/tx_bytes");
} else {			// PHP_OS == 'Linux'
	$rx[] = @file_get_contents("/sys/class/net/$int/statistics/rx_bytes");
	$tx[] = @file_get_contents("/sys/class/net/$int/statistics/tx_bytes");
	sleep(1);
	$rx[] = @file_get_contents("/sys/class/net/$int/statistics/rx_bytes");
	$tx[] = @file_get_contents("/sys/class/net/$int/statistics/tx_bytes");
}
   
	$tbps = $tx[1] - $tx[0];
	$rbps = $rx[1] - $rx[0];

	$round_rx=round($rbps/1024, 0);
	$round_tx=round($tbps/1024, 0);
	
	//$round_rx=round($rbps/1024/1024, 1);
	///$round_tx=round($tbps/1024/1024, 1);

	//$time=date("U")."000";					// Date et heure UTC ? du serveur... ?
	$time=strtotime("now UTC")."000";	// Date et heure du client... !SP!
	
	$_SESSION['rx'][] = "[$time, $round_rx]";
	$_SESSION['tx'][] = "[$time, $round_tx]";
	$data['label'] = $int;
	$data['data'] = $_SESSION['rx'];
	# to make sure that the graph shows only the
	# last minute (saves some bandwitch to)
	if (count($_SESSION['rx'])>120)	// 60  : Limite l'amplitude de l'affichage sur 1 minute
																	// 120 : Limite l'amplitude de l'affichage sur 2 minutes
	{
			$x = min(array_keys($_SESSION['rx']));
			unset($_SESSION['rx'][$x]);
			
			$x2 = min(array_keys($_SESSION['tx']));
			unset($_SESSION['tx'][$x2]);
	}

	# json_encode didnt work, if you found a workarround pls write me
	# echo json_encode($data, JSON_FORCE_OBJECT);
echo '[ { "data":['.implode($_SESSION['rx'], ",").'],"label": "Download"}, ';
echo '{ "data":['.implode($_SESSION['tx'], ",").'],"label": "Upload"} ';
echo ']';
/*
	echo '
	{
		"label":"eth0:Download",
		"data":['.implode($_SESSION['rx'], ",").']
	},
	{
		"label":"eth0:montante",
		"data":['.implode($_SESSION['tx'], ",").']
	}
	';
	
	echo '[
	{ "data":['.implode($_SESSION['rx'], ",").'],
		"label": "Download"}, ';
	echo '{ "data":['.implode($_SESSION['tx'], ",").'],"label": "Upload"} ';
	echo ']';
*/

?>
