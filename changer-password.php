<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Idefix : <?php echo _("Changer Mot de passe"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Changer Mot de passe">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

  <script src="$/js/jquery-3.3.1.min.js"></script>
  <script src="$/js/jquery-ui.min.js"></script>
	<script src="$/js/js.cookie.js"></script>
	<script src="$/js/script_accordion.js"></script>
  <script src="$/js/script_ready_nav6.js"></script>

	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen6.css" media="screen" />
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
        <span class="titre"><?php echo _("Changement du mot de passe"); ?></span><br />
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>

    <div class="colonne_1">

        <fieldset >
					<legend><?php echo _("Mot de passe"); ?></legend>
					
					<label class="label" for="mot_de_passe_1"><?php echo _("Nouveau"); ?></label> : 
					<input name="motdepasse1" type="password" id="motdepasse1" value="" /><br />

					<label class="label" for="mot_de_passe_2"><?php echo _("Confirmer"); ?></label> : 
					<input name="motdepasse2" type="password" id="motdepasse2" value="" /><br />

					<label class="label" for="mot_de_passe_3"><?php echo _("Ancien mot de passe"); ?></label> : 
					<input name="motdepasse3" type="password" id="motdepasse3" value="" /><br />

					<button type="button" id="validation">
						<img src="$/icones/accept.png" alt="Valider" /> <?php echo _("Valider"); ?>
					</button>
					
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



