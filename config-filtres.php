<?php
session_start();
include_once "$/includes/referer.php";
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
include_once "$/includes/ctrl_acces.php";
//include_once "$/includes/ctrl3.php";
/*
function _normaliseEOL($filename) {
  $contenuFichier = file_get_contents ( $filename );
  // Convertit toutes les fins de ligne au format UNIX
  $contenuFichier = str_replace(CRLF, LF, $contenuFichier);
  $contenuFichier = str_replace(CR, LF, $contenuFichier);
  // Don't allow out-of-control blank lines
  $contenuFichier = preg_replace("/\n{2,}/", LF . LF, $contenuFichier);
  file_put_contents($filename, $contenuFichier);
}*/
function normaliseEOL($contenuFichier) {
  // Convertit toutes les fins de ligne au format UNIX
  $contenuFichier = str_replace(CRLF, LF, $contenuFichier);
  $contenuFichier = str_replace(CR, LF, $contenuFichier);
  // Don't allow out-of-control blank lines
  $contenuFichier = preg_replace("/\n{2,}/", LF . LF, $contenuFichier);
	// Retirer un éventuel BOM
	$contenuFichier = str_replace("\xEF\xBB\xBF",'',$contenuFichier);
	return $contenuFichier;
}

switch (PHP_OS) {
	case 'Linux' :	
	case 'WINNT' : 
        $fichiersINI = [
          PATH_HOME_ROCK64_IDEFIX.'firewall-users.ini',
          PATH_HOME_ROCK64_IDEFIX.'proxy-users.ini',
          PATH_HOME_ROCK64_IDEFIX.'users.ini',
          PATH_HOME_ROCK64_IDEFIX.'proxy-groups.ini',
          PATH_HOME_ROCK64_IDEFIX.'firewall-ports.ini'
        ];

        // On vérifie que chaque fichier existe et est accessible en écriture
        // Pour qu'on puisse enregistrer les modifications
        $msg = "";
        foreach($fichiersINI as $fichier) {
          if(!file_exists($fichier)) {
            $msg .= "Le fichier <b>".$fichier."</b> n'existe pas.<br />";
          } else {
            if(!is_writable($fichier)) {
              $msg .= "Le fichier <b>".$fichier."</b> doit être accessible en écriture.<br />";
            }
          }

        }
        if($msg != "") {
          echo $msg;
          exit;
        }

        // On charge dans un tableau les différentes paramètres de chaque fichier INI
        foreach($fichiersINI as $fichier) {
          $fichier_lecture = file($fichier);
          // Normaliser les caractères de fin de ligne au format Unix
//          normaliseEOL($fichier);
          $fichier_lecture = normaliseEOL($fichier_lecture);
					
          $section = "";
          foreach($fichier_lecture as $ligne) {

            $ligne = trim($ligne);    // Pour supprimer les éventuels blancs après le nom de section
            //if($ligne == '') $ligne = "# " . $ligne;  // Pour contrer les lignes vides sans #

            // Retirer un éventuel BOM en début de ligne
//							$ligne = str_replace("\xEF\xBB\xBF",'',$ligne); 

            if(preg_match("#^\[.+\]$#", $ligne)) {
              // Des crochets au début et à la fin, alors c'est une section
              // Mais attention, ne fonctionne que si les fins de ligne sont au format Unix : LF
              // ( Voir ensuite pour écrire ? http://php.net/manual/fr/function.fputs.php )
              $section = $ligne;
              $ini[$fichier]['-'][] = $section;
            } else {
              if ($section == "") {
                $section = "[--- "._("Hors section")." ---]";
                $ini[$fichier]['-'][] = $section;
              }
              if(preg_match("#^\##", $ligne)) {								// ligne commençant par #
                $ini[$fichier][$section][] = $ligne;
              } else if(preg_match("#=#", $ligne)) {					// ligne contient un =
                $ini[$fichier][$section][] = $ligne;
              } else if($ligne == '') {
                $ini[$fichier][$section][] = $ligne;
              } else {
                $ini[$fichier][$section][] = "!!!# " . $ligne;	// on rajoute un # qui est en fait un commentaire
              }
            }
          }
        }
        break;
										
	default			: echo "OS Serveur : ", PHP_OS;
								exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Idefix : <?php echo _("Configuration des filtres"); ?></title>
  <meta name="author" lang="fr" content="O. Cart.">
  <meta name="keywords" content="">
	<meta name="description" content="Configuration des filtres">
	<meta name="robots" content="noINDEX, noFOLLOW">

	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="icon"      		type="image/png"		href="$/icones/favicon.png" />

	<script>
    <?php
        // Transmission des données pour leur traitement JQuery
				echo "var path_home_rock64_idefix = '".PATH_HOME_ROCK64_IDEFIX."';".PHP_EOL;
        echo "var fichiersINI = ".json_encode ($fichiersINI).";".PHP_EOL;
        echo "var INI = ".json_encode ($ini).";".PHP_EOL;
				/* Ne pas déplacer : $/js/script_fonctions3.js qui doit être obligatoirement ci-dessous */

        include_once "$/includes/aide3.php";
        include_once "$/includes/alert.php";
		?>
  </script>
  <script src="$/js/jquery-3.3.1.min.js"></script>
	<script src="$/js/jquery-ui.min.js"></script>
	<script src="$/js/js.cookie.js"></script>
	<script src="$/js/script_accordion.js"></script>
	<script src="$/js/script_ready_nav3.js"></script>
  <script src="$/js/script_fonctions3.js"></script>

	<link rel="stylesheet" type="text/css" href="$/css/commun.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/jquery-ui.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/menu.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="$/css/screen3.css" media="screen" />
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
				<span class="titre"><?php echo _("Configuration des filtres"); ?></span>
			</p>
		</div>
		
		<div class="logo"></div>

		<?php //---------------------------------------------------- menu ?>
		
		<div class="menu"><?php include_once "$/includes/menu.php"; ?></div>
		
		<?php //---------------------------------------------------- colonne 1 ?>

    <div class="colonne_1">

			<fieldset class="fieldset_fichiers">
				<legend><?php echo _("Fichiers"); ?></legend>
				<ul id="fichiersINI">
				<?php
				foreach($fichiersINI as $fichier) {
            $path_parts = pathinfo($fichier);
            $path_parts['basename'];
            //echo "<li>{$fichier}</li>".PHP_EOL;
            echo "<li>".$path_parts['basename']."</li>".PHP_EOL;
        }
				?>
				</ul>
			</fieldset>

			<div class="aide0" id="aff_aide">
				<input type="checkbox" id="afficher_aide" checked /> <?php echo _("Afficher aide"); ?>
			</div>
			<div class="aide" id="aide"></div>
							
		</div>
		
		<?php //---------------------------------------------------- colonne 2 ?>

    <div class="colonne_2">

			<fieldset class="fieldset_sections">
				<legend><?php echo _("Sections"); ?></legend>
				<ul id="sections"></ul>
			</fieldset>

      <?php //--------------------------------- Boutons ?>

			<div style="text-align:center;margin-top:1.3em;">
				<b>[</b> <input type="text" id="section" value="" /> <b>]</b><br />&nbsp;<br />

				<button type="button" id="section_nouvelle">
					<img src="$/icones/add_jaune.png" alt="" /> <?php echo _("Nouvelle"); ?>
				</button>
				<button type="button" id="section_valider">
					<img src="$/icones/accept.png" alt="" /> <?php echo _("Appliquer"); ?>
				</button><br />
				<button type="button" id="section_supprimer">
					<img src="$/icones/delete.png" alt="" /> <?php echo _("Supprimer"); ?>
				</button><br />
				
				<button type="button" id="section_deplacer_haut">
					<img src="$/icones/arrow_up.png" alt="vers le haut" /> <?php echo _("Déplacer"); ?>
				</button>
				<button type="button" id="section_deplacer_bas">
					<img src="$/icones/arrow_down.png" alt="vers le bas" /> <?php echo _("Déplacer"); ?>
				</button><br />

				<div style="text-align:center;margin:1em auto;">
					<button type="button" id="section_doublons">
						<img src="$/icones/arrow_divide.png" alt="" /> <?php echo _("Recherche doublons"); ?>
					</button>
				</div>
			</div>
							
		</div>
		
		<?php //---------------------------------------------------- colonne 3 ?>

    <div class="colonne_3">

			<fieldset class="fieldset_fichiers">
        <legend><?php echo _("Clés - Valeurs"); ?></legend>
        <ul id='vue' class="vue"></ul>
      </fieldset>
        
      <div style="display:inline-block;margin:10px auto;">
        <span id="index" style="font-weight:bold;display:inline-block;width:3em;color:blue;text-align:right;"></span>
      </div>

      <div style="display:inline-block;margin-right:10px;font-weight:bold;">
      <label class="label"><?php echo _("Préfix"); ?></label><br />
        <select name="flag_comment" id="flag_comment">
          <option value="" selected></option>
          <option value="#">#</option>
        </select>
      </div>

      <?php //------------------------- ligne_normale ?>

			<div id="ligne_normale" style="font-weight:bold;">

      <div style="display:inline-block;font-weight:bold;">
        <label class="label" for="cle" id="labelCle"><?php echo _("Clé"); ?></label><br />
        <input type="text" id="cle" value="" />
      </div>
      =
      <div style="display:inline-block;font-weight:bold;">
        <label class="label" for="valeur" id="labelValeur"><?php echo _("Valeur"); ?></label><br />
        <input type="text" id="valeur" value="" />
      </div>
      #
      <div style="display:inline-block;font-weight:bold;">
        <label class="label" for="commentaire" id="labelCommentaire"><?php echo _("Commentaire"); ?></label><br />
        <input type="text" id="commentaire" value="" />
      </div>

      </div>

      <?php //------------------------- ligne_commentee ?>

      <div id="ligne_commentee" style="font-weight:bold;">
        <label class="label" for="ligne_comment"><?php echo _("Ligne commentée"); ?></label><br />
        <input type="text" id="ligne_comment" value="" style="width:450px;" />
      </div>

      <?php //--------------------------------- Boutons ?>

      <div style="text-align:center;margin-top:1em;">
          <button type="button" id="entree_nouvelle">
            <img src="$/icones/add_jaune.png" alt="" /> <?php echo _("Nouvelle"); ?>
          </button>
          <button type="button" id="entree_valider">
            <img src="$/icones/accept.png" alt="" /> <?php echo _("Appliquer"); ?>
          </button>
          <span class="ecarteur2"></span>
          <button type="button" id="entree_supprimer">
            <img src="$/icones/delete.png" alt="" /> <?php echo _("Supprimer"); ?>
          </button>
      </div>

      <div style="text-align:center;margin:1em auto;">
          <button type="button" id="entree_deplacer_haut">
            <img src="$/icones/arrow_up.png" alt="vers le haut" /> <?php echo _("Déplacer"); ?>
          </button>
          <button type="button" id="entree_deplacer_bas">
            <img src="$/icones/arrow_down.png" alt="vers le bas" /> <?php echo _("Déplacer"); ?>
          </button>
          <button type="button" id="entree_doublons">
            <img src="$/icones/arrow_divide.png" alt="" /> <?php echo _("Recherche doublons"); ?>
          </button>
      </div>
							
		</div>
		
    <?php //---------------------------------------------------- bas ?>

		<div class="bas">
			<div id="cache">
				<div id="resultats"></div>
				<button type="button" id="fermer_doublons_globaux">
					<img src="$/icones/cancel.png" alt="" /> <?php echo _("Fermer"); ?>
				</button>
			</div>

			<button type="button" id="recherche_globale_doublons">
				<img src="$/icones/arrow_divide.png" alt="" /> <?php echo _("Recherche globale des doublons"); ?>
			</button>
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


