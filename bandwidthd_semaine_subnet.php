<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
include_once "$/includes/ctrl_acces.php";
include_once "$/includes/fonctions5.php";

$urlBase = getBaseUrl();
$urlBandwidthd = $urlBase.PATH_RACINE_BANDWIDTHD;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Idefix : bandwidthd</title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Statistiques de la semaine - Sous-réseau">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

	<script>
  	<?php 
      echo "var urlBase = '".$urlBase."';".PHP_EOL;
      echo "var urlBandwidthd = '".$urlBandwidthd."';".PHP_EOL;
		?>
	</script>
	<script src="$/js/jquery-3.3.1.min.js"></script>
	<script src="$/js/jquery-ui.min.js"></script>
	<script src="$/js/js.cookie.js"></script>
	<script src="$/js/script_accordion.js"></script>
	<script src="$/js/script_ready_nav5.js"></script>

	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen5.css" media="screen" />
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
				<span class="titre2">Bandwidth<span>d</span></span>
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>

		<?php //---------------------------------------------------- sous-menu ?>
		
			<?php include_once "$/includes/bandwidthd_menu.php"; ?>

		<?php //---------------------------------------------------- colonne 1 ?>

    <div class="colonne_1">
			<h4><?php echo _("Statistiques de la semaine"); ?> &bull; <span style="color:yellow;"> <?php echo _("sous-réseau"); ?> <?php echo $subnet; ?></span></h4>

			<div id='tableau'>
			<?php
				echo getTable("Subnet-2-".$subnet.".html");
			?>
			</div>
			<div id="graphiques"></div>
			
			
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



