<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
include_once "$/includes/ctrl_acces.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Idefix : <?php echo _("Mise à jour immédiate du système"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Mise à jour immédiate du système">
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
        <span class="titre"><?php echo _("Mises à jour"); ?></span><br />
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>
<style>
.div1 {
	 border:1px solid gray; background-color:white;border-radius:5px;
	 margin-bottom:1rem;
}
.div2 {
	 border:1px solid gray; background-color:white;border-radius:5px;
}
.div1 > p:first-child, .div2 > p:first-child {
	color:white;background-color:#008000;font-size:120%;margin-top:0;padding:0.1rem 0 0.2rem 0;
}

</style>
    <div class="colonne_1">

			<fieldset style="width:450px;margin-top:2rem;">
          <div>
              <div class="div1">
							<p>Mise à jour du système</p>
							<?php
 								$commande = 'sudo /usr/lib/idefix/idefix-update.py 2>&1';
 								//echo "<p>"._("Cette commande va être exécutée")." :<br />";
                //echo "<b>shell_exec('".$commande."')</b></p>";
								$cmd = str_replace(' ', '_', $commande);
								
								echo "<button type='button' id='update_system' cmd=\"".$cmd."\">";
								echo "<img src='$/icones/cog_edit.png' alt='' /> "._("Mise à jour du système");
								echo "</button>";
              ?>
							</div>

              <div class="div2">
							<p>Mise à jour de l'interface graphique</p>
							<?php
 								$commande = 'sudo /usr/lib/idefix/idefix-update.py -g 2>&1';
 								//echo "<p>"._("Cette commande va être exécutée")." :<br />";
                //echo "<b>shell_exec('".$commande."')</b></p>";
								$cmd = str_replace(' ', '_', $commande);
								
								echo "<button type='button' id='update_system' cmd=\"".$cmd."\">";
								echo "<img src='$/icones/cog_edit.png' alt='' /> "._("Mise à jour de l'interface graphique");
								echo "</button>";
              ?>
							</div>
					<div id="result"></div>
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



