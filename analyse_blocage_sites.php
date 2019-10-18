<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
//include_once "$/includes/ctrl_acces.php";
require_once("$/classesphp/magiques.php");

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo _("Analyse des blocages de sites"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Non implémenté">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

	<script>
		<?php 
      include_once "$/includes/alert.php";
		?>
  </script>
  <script src="$/js/jquery-3.3.1.min.js"></script>
  <script src="$/js/jquery-ui.min.js"></script>
	<script src="$/js/js.cookie.js"></script>
	<script src="$/js/script_accordion.js"></script>
	<script src="$/js/script_ready_nav13.js"></script>
	
	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen13.css" media="screen" />
	
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
        <span class="titre"><?php echo _("Analyse des blocages de sites"); ?></span><br />
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>

    <div class="colonne_1">

			<fieldset>
					<!--<p>
            <img src="$/icones/chantier.png" alt="" /><br />
          </p> -->
					<?php $d = new IPs();
								$infosIPs = $d->getIPs();
					?>
          <div style="display:table;table-layout:auto;width:100%; /* border-spacing: 10px;*/">
						<div style="display:table-cell;width:25%;">
							<label class="label" for="ip"><?php echo _("Votre adresse IP"); ?></label> :
							<input type="texte" id="ip" value="<?php echo $infosIPs['IPv4_client']; ?>" readonly />
						</div>
					
						<div style="display:table-cell;width:30%;margin:20px;">
							<p>
								<input class="label" type="button" id="start" value="<?php echo _("Heure de début"); ?>" /> :
								<input type="texte" id="time_start" value="" placeholder="hh:mm:ss" />
							</p>
							<p>
								<input class="label" type="button" id="stop" value="<?php echo _("Heure de fin"); ?>" /> :
								<input type="texte" id="time_stop" value="" placeholder="hh:mm:ss" />
							</p>
						</div>
					
						<div style="display:table-cell;width:25%;">
						<p>
							<span class="label">
								<input type="checkbox" id="proxy" checked />
								<label for="proxy">Proxy</label>
							</span>
							<span class="label">
								<input type="checkbox" id="firewall" />
								<label for="firewall">Firewall</label>
							</span>
						</p>
						<!--
						<p>
							<label for="actual">Réactualisation</label> :
							<select id="actual">
								<option value="2">2 s</option>
								<option value="5" selected>5 s</option>
								<option value="10">10 s</option>
								<option value="15">15 s</option>
								<option value="30">30 s</option>
								<option value="60">1 mn</option>
							</select>
						</p>
						-->
						
						</div>
					
						<div style="display:table-cell;width:20%;vertical-align:middle;">
						
							<input type="button" id="analyser" value="<?php echo _("Analyser"); ?>" />
						</div>

					</div>
					
						<!--
						<p style="font-size:125%;color:red;background-color:yellow;border:1px solid red;padding:0.3rem;">
						En cours de développement
						</p>
						-->
						
						<div class="cadrevisu">
							<table id="visu" class="display"></table>
						</div>

			</fieldset>
							
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
</html>



