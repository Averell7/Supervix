<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php"; 
//include_once "$/includes/supprime_temp.php";		// Localisation provisoire ?
include_once "$/i18n/localization.php";
 

$fichiersCONF = [
  PATH_MUNIN.'acpi-week.png',
  PATH_MUNIN.'cpu-week.png',
  PATH_MUNIN.'memory-week.png',  
];

$table  = "<table><tbody><tr>";
$k = 0;
foreach($fichiersCONF as $filename) {
	//$filename = "../../".$filename;
	$table .= "<td><div>";
	$k++;
		$table .= '<img src="' . $filename . '"/>';
	$table .= "</div></td>";
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
	<link rel="stylesheet" type="text/css" href="$/css/screen8.css" media="screen" />
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
			
		    <fieldset class="fieldset_Password">
			<legend><?php echo _("Mot de passe du compte"); ?></legend>
			<form action="subuser2.php" method="POST" name="Formulaire">
			<p> Entrez le Mot de passe de votre compte : 
			<input name="MotDePasse" type="password" size="30"></p>
			<input type="submit" value="Envoyer">
			</form>

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

