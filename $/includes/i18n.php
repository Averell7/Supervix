<?php
/* ------------------------------------------------- *\
		Lire la langue souhaitée prédemment sauvegardée
		dans un fichier ou dans un cookie
\* ------------------------------------------------- */

if(isset($_COOKIE["langue"])) {
	$langue = $_COOKIE["langue"];
} else {
	if (file_exists(PATH_FILE_LANGUAGE)) {		// Le fichier existe : on lit la langue
		$fileopen = fopen(PATH_FILE_LANGUAGE,'r');
		$langue = fgets($fileopen, 6);
		fclose($fileopen);
	} else {																	// On crée le fichier et on stocke la langue
		$langue = LANGUE_DEFAULT;
		$fileopen = fopen(PATH_FILE_LANGUAGE,'w');
		fwrite($fileopen, $langue);
		fclose($fileopen);
		setcookie( "langue", LANGUE_DEFAULT, strtotime( '+1 years' ), "/" );
	}
}

echo "<img src='$/icones/globe.png' class='globe' alt='' />\n<select id='i18n'>\n";
echo parcourirLangues($langues, PATH_RACINE_LOCALE, $langue);
echo "</select>\n";

/* ---------------------------------------------------------------------- *\
	function parcourirLangues($langues, $dir, $langue = "fr_FR")
	
	Pour qu'une langue soit référencée, il faut que :
	- le dossier langue dans "$/i18n/locale" existe
	- le fichier messages.mo existe pour cette langue
	- le libellé de la langue soit défini dans le tableau ci-dessus
\* ---------------------------------------------------------------------- */

function parcourirLangues($langues, $dir, $langue) {
   if (is_dir($dir)) {
			// ouvre le dossier 
			if ($handle = opendir($dir)) {
				$options = "";
				$lg = "fr_FR";
				if( $langue == $lg)
					$options .= "<option value='fr_FR' selected='selected'>".$langues[$lg]."</option>\n";
				else
					$options .= "<option value='fr_FR'>".$langues[$lg]."</option>\n";				
				// récupère la liste des fichiers/dossiers
				while (false !== ($lg = readdir($handle))) {
					if ($langue != "." && $lg != "..") {
						if( is_dir($dir."/".$lg) ) {
								
							if( file_exists($dir."/".$lg."/LC_MESSAGES/messages.mo") && isset($langues[$lg]) ) {
								if( $lg == $langue)
									$options .= "<option value='".$lg."' selected='selected'>".$langues[$lg]."</option>\n";
								else
									$options .= "<option value='".$lg."'>".$langues[$lg]."</option>\n";
							}
						}
					}
				}
			closedir($handle);	
			}
		}
		return $options;
}
?>

