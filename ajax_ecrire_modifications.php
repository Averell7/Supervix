<?php
if (!isset($_POST['INIs'])) { // Pour bloquer l'accès direct à ce script
	header('Location: index.php');
	exit;
}
include_once "$/includes/init.php";
include_once "$/i18n/localization.php";
$jsonINI = $_POST['INIs'];
$nomFichier = $_POST['nomFichier'];

$tableau = json_decode($jsonINI, true);

// Chemin vers le fichier
$nomFichierOut = $nomFichier;

// Ouverture en mode écriture
$fileopen = fopen($nomFichierOut,'w');
$nbSections = count( $tableau[$nomFichier]['-'] );
$contenu = "";

$msg = "";
for ($i=0; $i < $nbSections; $i++) { 
  $section = $tableau[$nomFichier]['-'][$i];
  if( $section != "[--- "._("Hors section")." ---]" ) $contenu .= $section."\n";    // Explication (3) ci-dessous

  $nbLignes = count( $tableau[$nomFichier][$section] );
  if($nbLignes > 0 ) {
    for ($k=0; $k < $nbLignes ; $k++) { 
      if( isset($tableau[$nomFichier][$section][$k]) ) {          // Explication (1) ci-dessous
        $contenu .= $tableau[$nomFichier][$section][$k]."\n";     // \n pour un retour LF (Linux)
      }
    }
  }

}

// Ajout du BOM
$contenu = "\xEF\xBB\xBF".$contenu;     // Explication (2) ci-dessous
// Ecriture
if (fwrite($fileopen, $contenu) === FALSE) {
  echo "NON : Impossible d'écrire dans le fichier ($nomFichierOut)";
} else {
  echo "OUI : effectué.\n\r";
}

fclose($fileopen);

	/* --------------------------------------------------------- *\
      Explications
      
      (1) Car lors de la sauvegarde d'une nouvelle section,
          il n'y a acune ligne saisiee
          bien qu'il y ait un nombre de ligne = 1 (par défaut ?)

      (2) Ajout impératif du BOM pour les fichiers INI d'Idefix

      (3) \n pour un retour LF (Linux)
          nécessaire ensuite pour la détection des sections
          par une expression régulière dans config-filtres.php

	\* --------------------------------------------------------- */
?>
