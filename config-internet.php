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
	if(	$conf["ftp"]								== "" ) $conf["ftp"]			= "ftp.online.net";
	if(	$conf["login"]							== "" ) $conf["login"]		= "idefix@chartreux.org";
	//if(	$conf["password"]						== "" ) $conf["password"]					= "";
	
	//if(	$conf["dns_filtering"]			== "" ) $conf["dns_filtering"]		= "";
	//if(	$conf["dns_nameserver1"]		== "" ) $conf["dns_nameserver1"]		= "195.46.39.39";
	//if(	$conf["dns_nameserver2"]		== "" ) $conf["dns_nameserver2"]		= "195.46.39.40";
	
	
	if(	$conf["dns_filtering"]			== "None" ) {
		if(	$conf["dns_nameserver1"]		== "" ) $conf["dns_nameserver1"]		= "8.8.8.8";
		if(	$conf["dns_nameserver2"]		== "" ) $conf["dns_nameserver2"]		= "8.8.4.4";
	} else {
		if(	$conf["dns_nameserver1"]		== "" ) $conf["dns_nameserver1"]		= "195.46.39.39";
		if(	$conf["dns_nameserver2"]		== "" ) $conf["dns_nameserver2"]		= "195.46.39.40";
	}
	
	if(	$conf["ip_type"]						== "" ) $conf["ip_type"]		= "dynamic";
	//if(	$conf["dyn_ip_handler"]			== "" ) $conf["dyn_ip_handler"]		= "";
	//if(	$conf["ddclient_login"]			== "" ) $conf["ddclient_login"]		= "";
	//if(	$conf["ddclient_password"]	== "" ) $conf["ddclient_password"]= "";
	//if(	$conf["ddclient_domain"]		== "" ) $conf["ddclient_domain"]	= "";
	//if(	$conf["ddclient_server"]		== "" ) $conf["ddclient_server"]	= "";
	//if(	$conf["ddclient_web"]				== "" ) $conf["ddclient_web"]			= "";

} else {		// Fichier $idefix_conf n'existe pas
	$conf["idefix_id"]						= "";
	$conf["lan_ip"]								= "";
	$conf["lan_netmask"]					= "";
	$conf["lan_subnet"]						= "";
	$conf["proxy_http_port"]			= "";
	
	$conf["lan_network"]					= "";
	$conf["dhcp_begin"]						= "";
	$conf["dhcp_end"]							= "";
	$conf["lan_broadcast"]				= "";

	if(!isset($conf["ftp"]))								$conf["ftp"]								= "ftp.online.net";
	if(!isset($conf["login"]))							$conf["login"]							= "idefix@chartreux.org";
	if(!isset($conf["password"]))						$conf["password"]						= "";

	if(!isset($conf["dns_filtering"]))			$conf["dns_filtering"]			= "";
	if(!isset($conf["dns_nameserver1"]))		$conf["dns_nameserver1"]		= "8.8.8.8";
	if(!isset($conf["dns_nameserver2"]))		$conf["dns_nameserver2"]		= "8.8.4.4";

	if(!isset($conf["ip_type"]))						$conf["ip_type"]						= "dynamic";
	if(!isset($conf["dyn_ip_handler"]))			$conf["dyn_ip_handler"]			= "";
	if(!isset($conf["ddclient_login"]))			$conf["ddclient_login"]			= "";
	if(!isset($conf["ddclient_password"]))	$conf["ddclient_password"]	= "";
	if(!isset($conf["ddclient_domain"]))		$conf["ddclient_domain"]		= "";
	if(!isset($conf["ddclient_server"]))		$conf["ddclient_server"]		= "";
	if(!isset($conf["ddclient_web"]))				$conf["ddclient_web"]				= "";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Idefix : <?php echo _("Configuration internet"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Configuration Internet">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

	<script>
		<?php 
			echo "var idefix_conf = '".$idefix_conf."';".PHP_EOL;
      include_once "$/includes/chaines7.php";
      include_once "$/includes/alert.php";
		?>
  </script>
	<script src="$/js/jquery-3.3.1.min.js"></script>
	<script src="$/js/jquery-ui.min.js"></script>
	<script src="$/js/js.cookie.js"></script>
	<script src="$/js/script_accordion.js"></script>
	<script src="$/js/script_ready_nav7.js"></script>

	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen7.css" media="screen" />
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
				<span class="titre"><?php echo _("Configuration internet"); ?></span>
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>

    <div class="colonne_1">

				<fieldset class="fieldset_FTP">
					<legend>FTP</legend>

					<label class="label" for="ftp_ftp">ftp</label> : 
					<input type="text" id="ftp_ftp" value="<?php echo $conf["ftp"]?>" /><br />
					<input class="cacher" type="text" id="ftp_ftp_actuel" value="<?php echo $conf["ftp"]?>" readonly />

					<label class="label" for="ftp_login"><?php echo _("Identifiant"); ?></label> : 
					<input type="text" id="ftp_login" value="<?php echo $conf["login"]?>" /><br />
					<input class="cacher" type="text" id="ftp_login_actuel" value="<?php echo $conf["login"]?>" readonly />

					<label class="label" for="ftp_password"><?php echo _("Mot de passe"); ?></label> : 
					<input type="password" id="ftp_password" value="<?php echo dechiffrer( $conf["password"])?>" /><br />
				</fieldset>

				<?php if(PHP_OS == 'WINNT') { ?>
				<fieldset class="fieldset_sites">
					<legend><?php echo _("Tester un site"); ?></legend>

					<label class="label" for="urlSite"><?php echo _("Sélection"); ?></label> : 
					<!-- <input type="text" id="urlSite_choix" name="urlSite_choix" list="urlList" placeholder="Saisir ou flèche vers le bas &#9660;"> -->
					<input type="text" id="urlSite_choix" name="urlSite_choix" list="urlList" placeholder="<?php echo _("Saisir ou double-click"); ?>">
					<datalist id="urlList">
							<?php
								$handle = @fopen("$/settings/datalist_domains.txt", "a+");
								if ($handle) {
											$optionsDatalist = "";
									while (($d = fgets($handle)) !== false) {
										$optionsDatalist .= "<option value='".trim($d)."'>\n";
									}
									fclose ($handle);
									echo $optionsDatalist;
								}
							?>
					</datalist>

					<div id="blocageDNS">
						<span id="info_blocageDNS"></span>
						
						<span id="info_blocageDNS2">
						
							<button type="button" id="verifier_filtrage_DNS">
								<img src="$/icones/accept.png" alt="" /> <?php echo _("Vérifier filtrage DNS"); ?>
							</button> (1)<br />
							
							 <span id="info"><?php echo _("La liste des sites est mise automatiquement à jour<br />si le site saisi n'est pas dans cette liste."); ?></span>
						 </span>
					</div>
				</fieldset>
				
				<ol>
					<li> <?php echo _("À vérifier avec un VRAI safeDNS ou autre..."); ?></li>
				</ol>
				<?php } ?>
							
		</div>
		
		<?php //---------------------------------------------------- colonne 2 ?>
            
		<div class="colonne_2">
		
				<fieldset class="fieldset_filtreDNS">
					<legend><?php echo _("Filtrage DNS"); ?></legend>

					<label class="label" for="filtreDNS"><?php echo _("Gestion par"); ?></label> : 
					<select id="filtreDNS" name="filtreDNS" size="1">

            <?php include_once "$/includes/ips_dynamiques.php";

              foreach ($filtreDNS as $key) {
                $option = "<option value='";
                foreach ($key as $key2 => $value2) {
                  if($key2 == 'optionValue') {
                    $option .= $value2."'";
										if($value2 == $conf["dns_filtering"]) $option .= " selected";
										$option .= ">";
                  }
                  if($key2 == 'libelle') {
                    $option .= $value2."</option>\n";
                    echo $option;
                    continue;
                  }
                }
              }
              // Nécessaire pour : script_ready_nav7.js
              echo "<script>var filtreDNS = ".json_encode($filtreDNS)."</script>";
            ?>
					</select><br />

					<div id="divcacher_1">
            <label class="label" for="dns_nameserver1"><?php echo _("Serveur"); ?> DNS 1</label> : 
            <input type="text" id="dns_nameserver1" value="<?php echo $conf["dns_nameserver1"]?>" /><br />

            <label class="label" for="dns_nameserver2"><?php echo _("Serveur"); ?> DNS 2</label> : 
            <input type="text" id="dns_nameserver2" value="<?php echo $conf["dns_nameserver2"]?>" /><br />
          </div>
        </fieldset>
			

				<fieldset class="fieldset_DynDNS">
					<legend><?php echo _("IP dynamique ou fixe"); ?></legend>
          
					<label><?php echo _("Adresse IP"); ?></label> : 
					
					<input type="radio" id="dynamic" name="ip_dyn_fixe" value="dynamic" <?php if($conf["ip_type"] == "dynamic" ) echo 'checked'; ?>>
					<label for="dynamic" id="label_dynamic"><?php echo _("dynamique"); ?></label>
					
					<input type="radio" id="static" name="ip_dyn_fixe" value="static" <?php if($conf["ip_type"] == "static" ) echo 'checked'; ?>>
					<label for="static" id="label_static"><?php echo _("fixe"); ?></label><br />
		
					<div id="divcacher_2a" <?php if($conf["ip_type"] == "static" ) echo "style='visibility:hidden;'"; ?>>
							<label class="label" for="typeDNS"><?php echo _("Gestion par"); ?></label> : 
							<select id="typeDNS" name="typeDNS" size="1">
								<?php 
									foreach ($choixDNS as $key) {
										$option = "<option value='";
										foreach ($key as $key2 => $value2) {
											if($key2 == 'optionValue') {
												$option .= $value2."'";
												if($value2 == $conf["dyn_ip_handler"]) $option .= " selected";
												$option .= ">";
											}
											if($key2 == 'libelle') {
												$option .= $value2."</option>\n";
												echo $option;
												continue;
											}
										}
									}
									// Nécessaire pour : script_ready_nav7.js
									echo "<script>var choixDNS = ".json_encode($choixDNS)."</script>";
								?>
							</select><br />
          </div>

          <div id="divcacher_2b" <?php if($conf["ip_type"] == "static" ) echo "style='visibility:hidden;'"; ?>>
							<label class="label" for="ddclient_login" id="ddclient_login_libelle"><?php echo _("Identifiant"); ?></label> : 
							<input type="text" id="ddclient_login" value="<?php echo $conf["ddclient_login"] ?>" autofocus placeholder="(ddclient_login)" /><br />

							<label class="label" for="ddclient_password" id="dclient_password_libelle"><?php echo _("Mot de passe"); ?></label> : 
							<input type="password" id="ddclient_password" value="<?php echo dechiffrer( $conf["ddclient_password"]) ?>" /><br />

							<label class="label" for="ddclient_domain"><?php echo _("Domaine enregistré"); ?></label> : 
							<input type="text" id="ddclient_domain" value="<?php echo $conf["ddclient_domain"] ?>"  placeholder="votre_maison.ddns.net (ddclient_domain)" /><br />
							
							<?php // Voir init.php ?>
							<div <?php echo $style; ?>>
							<label class="label" for="ddclient_server">ddclient_server</label> : 
							<input type="text" id="ddclient_server" readonly /><br />

							<label class="label" for="ddclient_web">ddclient_web</label> : 
							<input type="text" id="ddclient_web" readonly /><br />
							</div>
          </div>
				</fieldset>
			
				<button type="button" id="enregistrer_parametres">
					<img src="$/icones/accept.png" alt="" /> <?php echo _("Enregistrer"); ?>
				</button><br />
			
				<?php //------------------ Cachés ?>
				<input class="cacher" type="text" id="systeme_idefix_id"				value="<?php echo $conf["idefix_id"]?>" readonly />
				<input class="cacher" type="text" id="systeme_lan_ip"						value="<?php echo $conf["lan_ip"]?>" readonly />
				<input class="cacher" type="text" id="systeme_lan_netmask"			value="<?php echo $conf["lan_netmask"]?>" readonly />
				<input class="cacher" type="text" id="systeme_lan_subnet"				value="<?php echo $conf["lan_subnet"]?>" readonly />
				<input class="cacher" type="text" id="systeme_proxy_http_port"	value="<?php echo $conf["proxy_http_port"]?>" readonly />

				<input class="cacher" type="text" id="dhcp_lan_network"					value="<?php echo $conf["lan_network"]?>" readonly />
				<input class="cacher" type="text" id="dhcp_begin"								value="<?php echo $conf["dhcp_begin"]?>" readonly />
				<input class="cacher" type="text" id="dhcp_end"									value="<?php echo $conf["dhcp_end"]?>" readonly />
				<input class="cacher" type="text" id="dhcp_lan_broadcast" 			value="<?php echo $conf["lan_broadcast"]?>" readonly />

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
		Les lignes ne comportant pas de signe '"' sont ignorées
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
    Déchiffrage d'un mot de passe chiffré
		
		substr ($chaine , 0 , 1) != '%') :
		Mot de passe considéré comme non chiffré
\* ----------------------------------------------------------------- */

function dechiffrer($chaine) {
  $chaine = trim($chaine);
	$lg = strlen($chaine);
  if($lg == 0  || substr ($chaine , 0 , 1) != '%') return $chaine;
  $mdp = '';
  for ($i=1; $i<=$lg; $i += 2) {
    $mdp .= substr ($chaine , $i , 1);
  }
  return $mdp;
}

/* ----------------------------------------------------------------- *\

\* ----------------------------------------------------------------- */
?>

</html>



