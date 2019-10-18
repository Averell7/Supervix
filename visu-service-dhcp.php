<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
include_once "$/includes/ctrl_acces.php";
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
  <title>Idefix : <?php echo _("Service DHCP"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Service DHCP">
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
  <script src="$/js/script_ready_nav8.js"></script>

	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen8.css" media="screen" />
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
        <span class="titre"><?php echo _("Service DHCP"); ?></span><br />
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>

    <div class="colonne_1">
		
			<?php
				if(PHP_OS == 'Linux') {
					echo "<fieldset style='width:900px;'>";
				} else {
					echo "<fieldset>";
				}
			?>
          <div>
              <?php
 								$commande = 'sudo service isc-dhcp-server status';
                //echo "<p>"._("Commande exécutée")." :<br />";
                //echo "<b>shell_exec('".$commande."')</b></p>";
								echo "<div id='result'>";
								if(PHP_OS == 'Linux') {
									$retour = shell_exec($commande);
									$retour = str_replace(array("\r\n","\r","\n","\\r","\\n","\\r\\n"),"<br />",$retour);
									echo "<p class='vert'>".$retour."</p>";
								} else {
									echo "<p class='rouge'>"._("Cette commande ne peut pas être exécutée car vous n'êtes pas sous Linux.")."</p>";
								}
								echo "</div>";
              ?>
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



