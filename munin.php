<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php"; 
//include_once "$/includes/supprime_temp.php";		// Localisation provisoire ?
include_once "$/i18n/localization.php";
require_once "$/classesphp/magiques.php";

$d = new IPs();
$infosIPs = $d->getIPs();
$ip = $infosIPs['eth1_IPv4_idefix']; 
$png_path = 'http://' . $ip . ':10443/munin/localdomain/localhost.localdomain/';

$fichiersCONF = [
  $png_path.'acpi-week.png',
  $png_path.'cpu-week.png',
  $png_path.'memory-week.png',  
];

$table  = "<table><tbody><tr>";
$k = 0;
foreach($fichiersCONF as $filename) {
	//$filename = "../../".$filename;
	$table .= '<td align="center">';
	$k++;
		$table .= '<img src="' . $filename . '"/>';
	$table .= "</td>";
	$table .= "</tr><tr>";
}
$table .= "</tr></tbody></table>";


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo _("Page d'accueil d'Idéfix"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Accueil Idefix">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

  <script src="$/js/jquery-3.3.1.min.js"></script>
	<script src="$/js/jquery-ui.min.js"></script>
	<script src="$/js/js.cookie.js"></script>
	<script src="$/js/script_accordion.js"></script>
	<script src="$/js/script_ready_nav1.js"></script>

	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen15.css" media="screen" />
</head>

<body>
<div class="container">

		<?php //---------------------------------------------------- header ?>
		
		<div class="menu0" title="<?php echo _("Page d accueil"); ?>">
			<span class="titre0">Idéfix</span><br />
			<span class="sous_titre0"><?php echo MAISON; ?></span>
		</div>
	 <div class="header0"><?php include_once "$/includes/i18n.php"; ?></div>

		<div class="header">
			<p>
				<span class="titre"><?php echo _("Graphiques"); ?></span>
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>

		<div class="colonne_1">
			<fieldset>

			<button type="button" id="daily">
					<img src="$/icones/calendar_day.png" alt="" /> <?php echo _("Journée"); ?>
			</button>
			
			<button type="button" id="weekly">
					<img src="$/icones/calendar_week.png" alt="" /> <?php echo _("Semaine"); ?>
			</button>
			<button type="button" id="monthly">
					<img src="$/icones/calendar_month.png" alt="" /> <?php echo _("Mois"); ?>
			</button>

			<button type="button" id="yearly">
					<img src="$/icones/calendar_month.png" alt="" /> <?php echo _("Année"); ?>
			</button>
			    <h1>Semaine</h1>
			    <?php echo $table; ?>            
			</fieldset>
		</div>
		
    <?php //---------------------------------------------------- footer ?>
             
    <div class="footer">
      <?php include "$/includes/version.php"; ?>
    </div>
    
    <div class="notes">
			<?php
//				if( isset($_SESSION['lastActivity']) && isset($_SESSION['Password']) ) {
	
				if( isset($_SESSION['lastActivity'])) {
					$diff = TPSMAXSESSION - abs( time() - $_SESSION['lastActivity'] );
					if($diff > 0 ) {
						//echo "Reste ".$diff . " secondes<br />";
						include "$/includes/deconnexion.php";
					} else {
						session_unset();
					}
				} else {
					//echo "Session expirée ?<br />";
					session_unset();
				}
				/*
				if( ($_SESSION['lastActivity'] + TPSMAXSESSION) < time()) {
					include "$/includes/deconnexion.php";
				}
				*/
			?>
    </div>

    <?php //----------------------------------------------------------- ?>

</div>
</body>
</html>

