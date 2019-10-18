<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
//include_once "$/includes/ctrl_acces.php";
$locale = 'fr_FR.UTF-8';
setlocale(LC_ALL, $locale);
putenv('LC_ALL='.$locale);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Idefix : <?php echo _("Données système"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Données système">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

	<script>
		<?php 
      include_once "$/includes/alert.php";
			echo "var rafraichir = '$rafraichissement_etat_systeme';";
			echo "var raff_time = $raff_time;";
		?>
  </script>
  <script src="$/js/jquery-3.3.1.min.js"></script>
  <script src="$/js/jquery-ui.min.js"></script>
	<script src="$/js/js.cookie.js"></script>
	<script src="$/js/script_accordion.js"></script>
  <script src="$/js/script_ready_nav2.js"></script>

	<script src="$/js/d3.js"></script>
	<script src="$/js/gauge.js"></script>
	<script src="$/js/jauge.js"></script>

	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen2.css" media="screen" />
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
        <span class="titre"><?php echo _("État du système"); ?></span><br />
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>
    <div class="colonne_1">

			<fieldset>
          <div id="t0">
						<table id="t1">
						<tr>
							<th colspan="2" class="rubrique">Configurations</th>
						</tr>
						<tr>
							<td>nftables</td><td id="cfg_nftables"></td>
						</tr>
						<tr style="background-color:#E7E7E7;">
							<td>Squid</td><td id="cfg_squid"></td>
						</tr>
						<tr>
							<th colspan="2" class="rubrique">Services</th>
						</tr>
						<tr>
							<td>Squid</td><td id="srv_squid"></td>
						</tr>
						<tr style="background-color:#E7E7E7;">
							<td>DHCP server</td><td id="srv_dhcp_server"></td>
						</tr>
						<tr>
							<td>DHCP client</td><td id="srv_dhcp_client"></td>
						</tr>
						<tr style="background-color:#E7E7E7;">
							<td>ddclient</td><td id="srv_ddclient"></td>
						</tr>
						<tr>
							<td>Bandwithd</td><td id="srv_bandwidthd"></td>
						</tr>
						<tr style="background-color:#E7E7E7;">
							<td>FTP server</td><td id="srv_ftp_server"></td>
						</tr>
						
						<tr>
							<th colspan="2" class="rubrique">FTP connexion to</th>
						</tr>
						<tr>
							<?php							
									$fichier_lecture = file(PATH_IDEFIX_CONF);
									$login = "";
									foreach($fichier_lecture as $ligne) {
										$ligne = trim($ligne);    // Pour supprimer les éventuels blancs après le nom de section
										// Retirer un éventuel BOM en début de ligne
										$ligne = str_replace("\xEF\xBB\xBF",'',$ligne);
										$pieces = explode("=", $ligne);
										if( trim($pieces[0]) == 'login' ) {
											$login = trim($pieces[1]);
											echo "<td>".$login."</td>";
											break;
										}
									}							
							?>							
							<td id="ftp_connexion"></td>
						</tr>
						<tr>
							<th colspan="2" class="rubrique">DNS Filtering : website</th>
						</tr>
						<tr>
							<td id="ftp_website"></td><td id="ftp_filtering"></td>
						</tr>
						</table>
						
						<!-- -------------------------------------------------------------- -->

						<table id="t2">
						<tr><th class="rubrique">Informations</th></tr>
						<tr>
							<td>
								<span class="label1">Date :</span> <span id="inf_date"></span><br />
								<span class="label1">Heure :</span> <span id="inf_heure"></span><br />
								<span class="label1">Up :</span> <span id="inf_up"></span><br />
								<span class="label1">user :</span> <span id="inf_user"></span>
							</td>
						</tr>
						<tr><th class="rubrique">CPU</th></tr>
						<tr>
							<td>
								<span id="cpuGaugeContainer"></span>
								<span class="label3">Température :</span> <span id="cpu_t"></span><br />
								<span class="label3">CPU load :</span><span class="label4a">1' :</span> <span id="cpu_1"></span><br />
																											<span class="label4b">5' :</span> <span id="cpu_5"></span><br />
																											<span class="label4b">15' :</span> <span id="cpu_15"></span>
							</td>
						</tr>
						<tr><th class="rubrique">Mémoire</th></tr>
						<tr>
							<td>
								<span id="memoryGaugeContainer"></span>
								<span class="label3">Taille totale :</span> <span id="mem_totale"></span><br />
								<span class="label3">Taille disponible :</span> <span id="mem_dispo"></span><br />
								<span class="label3">Taille utilisée :</span> <span id="mem_utilisee"></span>
							</td>
						</tr>
						<tr><th class="rubrique">Disque dur</th></tr>
						<tr>
							<td>
								<span id="hdGaugeContainer"></span>
								<span class="label3">Taille totale :</span> <span id="hd_totale"></span><br />
								<span class="label3">Taille occupée :</span> <span id="hd_occup"></span><br />
								<span class="label3">Taille disponible :</span> <span id="hd_libre"></span><br />
								<span class="label3">Pourcentage utilisé :</span> <span id="hd_pourcent_utilise"></span>
							</td>
						</tr>
						</table>			
						
						<!-- -------------------------------------------------------------- -->
						
						<div id="t3">
							<?php
								$commande = 'sudo /sbin/shutdown -r now';
								$cmd = str_replace(' ', '_', $commande);
								
								echo "<button type='button' id='reboot_idefix' cmd=\"".$cmd."\">";
								echo "<img src='$/icones/arrow_refresh.png' alt='' /> "._("Redémarrer Idefix");
								echo "</button>";
							 ?>
							 
							<?php
								if(	$rafraichissement_etat_systeme == "oui" ) {	
									echo "<p class='note'>(*) Rafraîchissement ";
									if( $raff_time <= 1 ) 
										echo "chaque seconde</p>";
									else
										echo"toutes les ". $raff_time . " secondes</p>";
								}
							?>							
						</div>

						<!-- -------------------------------------------------------------- -->

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
