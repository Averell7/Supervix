<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
include_once "$/includes/ctrl_acces.php";
//include_once "$/includes/ctrl7.php";
//$idefix_conf = PATH_ETC."idefix/idefix.conf";
$idefix_conf = PATH_ETC_IDEFIX."idefix.conf";

if(file_exists($idefix_conf)) {
	$conf = lire_ini_fichier_ini($idefix_conf);
	if(		$conf["idefix_id"]						== ""
		 || $conf["lan_ip"]								== ""
		 || $conf["lan_netmask"]					== ""
		 || $conf["lan_subnet"]						== ""
		 || $conf["proxy_http_port"]			== ""
		 || $conf["lan_network"]					== ""
		 || $conf["dhcp_begin"]						== ""
		 || $conf["dhcp_end"]							== ""
		 || $conf["lan_broadcast"]				== "") {
		$confNouvelle = true;
		/*$conf["idefix_id"]				= "";*/
		$conf["lan_ip"]						= "192.168.184.184";
		$conf["lan_netmask"]			= "255.255.255.0";
		/*$conf["lan_subnet"]				= "";*/
		$conf["proxy_http_port"]	= "8080";
	} else {
		$confNouvelle = false;
	}
} else {																// Fichier $idefix_conf n'existe pas
	$confNouvelle = true;
	$conf["ftp"]							= "";
	$conf["login"]						= "";
	$conf["password"]					= "";

	$conf["ip_type"]= "";
	$conf["dyn_ip_handler"]	= "";
	$conf["ddclient_login"]		= "";
	$conf["ddclient_password"]= "";
	$conf["ddclient_domain"]	= "";
	$conf["ddclient_server"]	= "";
	$conf["ddclient_web"]			= "";

	$conf["dns_filtering"]		= "";
	$conf["dns_nameserver1"]	= "";
	$conf["dns_nameserver2"]	= "";

	// par défaut
	if(!isset($conf["idefix_id"]))				$conf["idefix_id"]				= "";
	if(!isset($conf["lan_ip"]))						$conf["lan_ip"]						= "192.168.184.184";
	if(!isset($conf["lan_netmask"]))			$conf["lan_netmask"]			= "255.255.255.0";
	if(!isset($conf["lan_subnet"]))				$conf["lan_subnet"]				= "";
	if(!isset($conf["proxy_http_port"]))	$conf["proxy_http_port"]	= "8080";

	if(!isset($conf["lan_network"]))			$conf["lan_network"]			= "";
	if(!isset($conf["dhcp_begin"]))				$conf["dhcp_begin"]				= "";
	if(!isset($conf["dhcp_end"]))					$conf["dhcp_end"]					= "";
	if(!isset($conf["lan_broadcast"]))		$conf["lan_broadcast"]		= "";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Idefix : <?php echo _("Configuration du réseau"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Configuration du réseau">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

	<script>
		<?php 
      echo "var confNouvelle = '".$confNouvelle."';".PHP_EOL;
			echo "var lan_netmask_cidr = '".get_CIDR($conf["lan_subnet"])."';".PHP_EOL;
      echo "var idefix_conf = '".$idefix_conf."';".PHP_EOL;
      //echo "var path_racine = '".PATH_RACINE."';".PHP_EOL;
      include_once "$/includes/alert.php";
		?>
  </script>
	<script src="$/js/jquery-3.3.1.min.js"></script>
	<script src="$/js/jquery-ui.min.js"></script>
	<script src="$/js/jquery.mask.min.js"></script>
	<script src="$/js/js.cookie.js"></script>
	<script src="$/js/script_accordion.js"></script>
	<script src="$/js/script_ready_nav4.js"></script>

	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen4.css" media="screen" />
</head>

<body>
<div class="container">

		<?php //---------------------------------------------------- header ?>
		
		<div class="menu0" title="<?php echo _("Page d'accueil"); ?>">
			<span class="titre0">Idéfix</span><br />
			<span class="sous_titre0"><?php echo MAISON; ?></span>
		</div>
	 <div class="header0"></div>

		<div class="header">
			<p>
				<span class="titre"><?php echo _("Configuration du réseau"); ?></span>
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>

    <div class="colonne_1">

				<fieldset class="fieldset_Systeme">
					<legend><?php echo _("Système"); ?></legend>

					<label class="label" for="systeme_idefix_id">idefix-id</label> : 
					<input type="text" id="systeme_idefix_id" value="<?php echo $conf["idefix_id"]?>" autofocus autocomplete='off' placeholder="GC" /><br />

                    <label class="label" for="systeme_lan_ip">lan_ip</label> : 
					<input type="text" id="systeme_lan_ip" value="<?php echo $conf["lan_ip"]?>" /><br />

					<label class="label" for="systeme_lan_netmask_cidr">lan_netmask</label> : 
					<select id="systeme_lan_netmask_cidr" name="systeme_lan_netmask_cidr" size="1" style="width:17em">
						<option disabled="" class="classe">CLASS B</option>
            <option value="22">22 &bull; 255.255.252.0 &nbsp; &nbsp;(1022 hosts)</option>
						<option value="23">23 &bull; 255.255.254.0 &nbsp; &nbsp;( 510 hosts)</option>
						<option disabled="" class="classe">CLASS C</option>
						<option value="24">24 &bull; 255.255.255.0 &nbsp; &nbsp;( 254 hosts)</option>
            <option value="25">25 &bull; 255.255.255.128 ( 126 hosts)</option>
						<option value="26">26 &bull; 255.255.255.192 ( &nbsp; 62 hosts)</option>
						<option value="27">27 &bull; 255.255.255.224 ( &nbsp; 30 hosts)</option>
						<option value="28">28 &bull; 255.255.255.240 ( &nbsp; 14 hosts)</option>
					</select><br />
					
					<input type="hidden" id="systeme_lan_netmask" value="<?php echo $conf["lan_netmask"]?>" />

					<label class="label cacher" for="systeme_lan_subnet">lan_subnet :</label> 
					<input class="cacher" type="text" id="systeme_lan_subnet" value="<?php echo $conf["lan_subnet"]?>" readonly />

					<label class="label" for="systeme_proxy_http_port">proxy_http_port</label> : 
					<input type="text" id="systeme_proxy_http_port" value="<?php echo $conf["proxy_http_port"]?>" />
					
					<input type="button" id="calcul" value="Calcul" /><br />
				</fieldset>

				<fieldset class="fieldset_DHCP">
					<legend>DHCP</legend>

					<label class="label cacher" for="dhcp_lan_network">lan_network :</label> 
					<input class="cacher" type="text" id="dhcp_lan_network" value="<?php echo $conf["lan_network"]?>" readonly />

					<label class="label" for="dhcp_begin">dhcp_begin</label> : 
					<input type="text" id="dhcp_begin" value="<?php echo $conf["dhcp_begin"]?>" /><br />
					<label class="label" for="dhcp_end">dhcp_end</label> : 
					<input type="text" id="dhcp_end" value="<?php echo $conf["dhcp_end"]?>" /><br />

					<label class="label cacher" for="dhcp_lan_broadcast">lan_broadcast :</label> 
					<input class="cacher" type="text" id="dhcp_lan_broadcast" value="<?php echo $conf["lan_broadcast"]?>" readonly />
				</fieldset>
							
		</div>
		
		<?php //---------------------------------------------------- colonne 2 ?>
            
		<div class="colonne_2">
		
        <button type="button" id="enregistrer_parametres">
					<img src="$/icones/accept.png" alt="" /> <?php echo _("Enregistrer"); ?>
				</button><br />
			
				<?php //------------------ Cachés ?>
				<input class="cacher" type="text" id="ftp_ftp"						value="<?php echo $conf["ftp"]?>" readonly />
				<input class="cacher" type="text" id="ftp_login"					value="<?php echo $conf["login"]?>" readonly />
				<input class="cacher" type="text" id="ftp_password"				value="<?php echo $conf["password"] ?>" readonly />
				
				<input class="cacher" type="text" id="ip_type"						value="<?php echo $conf["ip_type"]?>" readonly />
				<input class="cacher" type="text" id="dyn_ip_handler"			value="<?php echo $conf["dyn_ip_handler"]?>" readonly />
				<input class="cacher" type="text" id="ddclient_login"			value="<?php echo $conf["ddclient_login"]?>" readonly />
				<input class="cacher" type="text" id="ddclient_password"	value="<?php echo $conf["ddclient_password"]?>" readonly />
				<input class="cacher" type="text" id="ddclient_domain"		value="<?php echo $conf["ddclient_domain"]?>" readonly />
				<input class="cacher" type="text" id="ddclient_server"		value="<?php echo $conf["ddclient_server"]?>" readonly />
				<input class="cacher" type="text" id="ddclient_web"				value="<?php echo $conf["ddclient_web"]?>" readonly />
				
				<input class="cacher" type="text" id="dns_filtering"			value="<?php echo $conf["dns_filtering"]?>" readonly />
				<input class="cacher" type="text" id="dns_nameserver1"		value="<?php echo $conf["dns_nameserver1"]?>" readonly />
				<input class="cacher" type="text" id="dns_nameserver2"		value="<?php echo $conf["dns_nameserver2"]?>" readonly />
			
		</div>
            
    <?php //---------------------------------------------------- footer ?>

    <div class="footer">
      <?php include "$/includes/version.php"; ?>
    </div>
    
    <div class="notes">
     <?php include "$/includes/deconnexion.php"; ?>
    </div>

    <?php //----------------------------------------------------------- ?>

</div>
</body>
				
<?php
/* ----------------------------------------------------------------- *\
    Lecture d'un fichier : "clé = valeur"
		Les lignes ne comportant pas de signe '=' sont ignorées
\* ----------------------------------------------------------------- */

function lire_ini_fichier_ini($fichier) {
	$conf1 = file($fichier);
	$conf = array();
	foreach($conf1 as $line) {
		if (strpos($line, '=')) {
			$line1 = explode("=", $line);
			$conf[trim($line1[0])] = trim($line1[1]);
		}
	}
	return $conf;
}

/* ----------------------------------------------------------------- *\
    Obtenir le cidr depuis le champ lan_subnet
\* ----------------------------------------------------------------- */

function get_CIDR($chaine) {
	if(trim($chaine) == '') return '24';
	$ch = explode("/", $chaine);
	if(isset($ch[1])) return $ch[1]; else return '24';
}
/* ----------------------------------------------------------------- *\

\* ----------------------------------------------------------------- */
?>

</html>



