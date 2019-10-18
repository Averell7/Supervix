<?php
/** @defgroup ClassesPHP Idéfix
    On trouve ici les classes pour créer et paramétrer
@{ */

/*!
 * @file      elimineZipContenusIdentiques.php
 * @brief     Classe pour éliminer les backups qui sont identiques\n
 *						soit en local : DIR_TEMP : /tmp/\n
 *						soit sur ftp : ftp.online.net\n
 *
 * @author     Idéfix - SP
 * @version    1.0
 * @date       2019-02-19
 *
 * @warning		
 *
 * @note			Extension :\n
 *						- Comparer N dossiers\n
 *						- Stocker dans un tableau la liste des dossiers qui ont un contenu différent\n
 *						- Éliminer de ce tableau les éventuels doublons qui peuvent être encore présent,\n
 *							suite l'algorithme utilisé par le script original : findFilesRecursive()\n
 *
 * @note			À la base cette classe permet de comparer si TOUS les fichiers de 2 dossiers\n
 *						et de leurs sous-dossiers sont identiques\n
 *						[Source inspirante]: https://github.com/sureshdotariya/folder-compare/blob/master/svncompare.php\n
 *
 ***********************************************************************************/

class elimineZipContenusIdentiques
	{
	/*-------------------------------------------------------------- Propriétés */
	private $dirTemp; /**< Dossier temporaire, selon Linux ou Windows, défini dans init.php */ 
	private $dossiers_DEZIP;
	private $dossiers_a_comparer = array();
	private $fichiers_exclus = array();
	private $extensions_exclues = array();

	private $bool;
	private $backupRetenu = array();

	private $debug = false;
	/*---------------------------------------------------------- FIN Propriétés */

	/*------------------------------------------------------------ Constructeur */
  /*! @fn                   public function __construct($dirTemp, $dossiers_DEZIP, $dossiers_a_comparer, $fichiers_exclus, $extensions_exclues)
   *  @brief                Constructeur de la classe
   *
   *  @param $dirTemp								Chemin du dossier temporaire
   *  @param $dossiers_DEZIP  	  	Dossiers...
   *  @param $dossiers_a_comparer		Array des dossiers à comparer
   *  @param $fichiers_exclus       Array des fichiers à exclure
   *  @param $extensions_exclues    Array des extensions à exclure
   */
	
 	public function __construct($dirTemp, $dossiers_DEZIP, $dossiers_a_comparer, $fichiers_exclus, $extensions_exclues)
		{
		$this->dirTemp							= $dirTemp;
		$this->dossiers_DEZIP				= $dossiers_DEZIP;
		$this->dossiers_a_comparer	= $dossiers_a_comparer;
		$this->fichiers_exclus			= $fichiers_exclus;
		$this->extensions_exclues		= $extensions_exclues;
		}
	/*-------------------------------------------------------- FIN Constructeur */

	/*------------------------------------------------------------- Destructeur */
	/*! @fn    Public function __destruct()
	 *  @brief Destructeur de la classe\n
	 */
	public function __destruct()
		{
		}
	/*--------------------------------------------------------- FIN Destructeur */
		
	/*------------------------------------------------- getBackupsZipDifferents */
	/*! @fn     public function getBackupsZipDifferents()
	 *  @brief   
	 *  @note		Note provisoire de renommage :\n
	 *  				$sourceDir 			: $fichier_1
	 *  				$destinationDir : $fichier_2
   */
	public function getBackupsZipDifferents()
	{
		/*
		echo "<pre>";
		print_r($this->fichiers_exclus);
		print_r($this->extensions_exclues);
		echo "</pre>";
		exit;		
	*/	
		$k = 0;
		$fichier_1 = $this->dirTemp.$this->dossiers_DEZIP[$k];
		
		if($this->debug) echo "\$k = ".$k." --- ".$this->dossiers_DEZIP[$k]."<br />";
	
		for($j = $k+1; $j < count($this->dossiers_DEZIP); $j++) {
			$fichier_2 = $this->dirTemp.$this->dossiers_DEZIP[$j];
			if($this->debug) echo "<span style='display:inline-block;width:30px;'></span>\$j = ".$j." --- ".$this->dossiers_DEZIP[$j]." : ";
			
			$this->bool = true;
			$this->findFilesRecursive($fichier_1, $fichier_2);
			
			if($this->bool) {																							// Identiques
				if($this->debug) echo "-- identiques ---<br />";
			}
			else {																												// Différents
				if($this->debug) echo "-- <span style='color:red'>différents</span> --- ";

				if( !in_array($this->dossiers_DEZIP[$k], $this->backupRetenu) ) {
						$this->backupRetenu[] = $this->dossiers_DEZIP[$k];
						if($this->debug) echo " Stocké : <span style='color:blue'>".$this->dossiers_DEZIP[$k]."</span> --- ";
				}
				if( !in_array($this->dossiers_DEZIP[$j], $this->backupRetenu) ) {
						$this->backupRetenu[] = $this->dossiers_DEZIP[$j];
						if($this->debug) echo " Stocké : <span style='color:blue'>".$this->dossiers_DEZIP[$j]."</span>";
				}
				if($this->debug) echo "<br />";
				
			}
		
		}
		//return $this->backupRetenu;
		if($this->debug) {
			echo "<pre>";
			print_r($this->backupRetenu);
			echo "</pre>";
			//echo "nb = ".count($this->backupRetenu);
		}
		
		// On essaie de réduire le nombre de sauvegardes à analyser
		
		/*
		$nb = count($this->backupRetenu);
		if( $nb > 0 ) {
			$backupRetenu2 = $this->reduire();
			$nb2 = count($backupRetenu2);
			//echo "nb = $nb --- nb2 = $nb2";
			return $this->reduire();
		}
		else {
			return $this->backupRetenu;
		}
		*/
		
		// Reste à valider...
		$this->reduct(false);
		return $this->backupRetenu;
	}
	
	/*--------------------------------------------- FIN getBackupsZipDifferents */

	/*------------------------------------------------------------------ reduct */
  /*! @fn     private function reduct()
   * @brief   Fonction récursive pour éliminer complètement les backups\n
	 *					dont le contenu serait identiques\n
	 * 
	 *  @note		Reste à valider dans le temps
   */

	private	function reduct($retour) {
		if( $retour ) return true;
		$nb = count($this->backupRetenu);
		//echo "nb = $nb<br />";
		if( $nb == 0 ) return true;
		
		$backupRetenu2 = $this->reduire();
		$nb2 = count($backupRetenu2);
		//echo "nb2 = $nb2<br />";
		if ( $nb2 != $nb ) {
			$this->backupRetenu = $backupRetenu2;
			$this->reduct(false);
		}
		return true;
	}
	
	/*-------------------------------------------------------------- FIN reduct */

	/*----------------------------------------------------------------- reduire */
  /*! @fn     private function reduire()
   * @brief   Réduction aux backups fifférents
   */
	private function reduire()
		{
		if($this->debug) {
			echo "<hr style='color:green;' size=5/>";
			echo "<span style='color:green;'>private function reduire()</span><br />";
			echo "<hr style='color:green;' size=5 />";
		}
		$backupRetenu2 = $this->backupRetenu;
		for($k = 0; $k < count($this->backupRetenu) - 1; $k++) {
			$fichier_1 = $this->dirTemp.$this->backupRetenu[$k];
			if($this->debug) echo "\$k = ".$k." --- ".$this->backupRetenu[$k]."<br />";
			
					for($j = $k+1; $j < count($this->backupRetenu); $j++) {
						$fichier_2 = $this->dirTemp.$this->backupRetenu[$j];
						//echo "\$k = $k --- \$j = $j<br />";
						if($this->debug) echo "<span style='display:inline-block;width:30px;'></span>\$j = ".$j." --- ".$this->backupRetenu[$j]." : ";
						$this->bool = true;
						
		if($this->debug) {
			echo "<hr style='color:red;' size=1 />";
			echo "<span style='color:red;'>Appel de findFilesRecursive(\$fichier_1, \$fichier_2)</span><br />";
			echo "<hr style='color:red;' size=1 />";
			
			echo "\$fichier_1 =  $fichier_1<br />";
			echo "\$fichier_2 =  $fichier_2<br />";
		}

						$this->findFilesRecursive($fichier_1, $fichier_2);
						
		if($this->debug) {
			if ($this->bool)
				echo "2 bool =  TRUE<br />";
			else
				echo "2 bool =  FALSE<br />";
		}						
					
						if($this->bool) {																	// Identiques
							if($this->debug) echo "-- identiques ---<br />";
							// On retire un élément du tableau ayant une certaine valeur
							/*
							$del_val = $backupRetenu[$k];
							if (($key = array_search($del_val, $backupRetenu)) !== false) {
									unset($backupRetenu[$key]);
							}*/
							$del_val = $this->backupRetenu[$j];
							if (($key = array_search($del_val, $backupRetenu2)) !== false) {
									unset($backupRetenu2[$key]);
							}
						}
						else {																						// Différents
							if($this->debug) echo "<br />";																				
						}

					}
		
		}
		
		if($this->debug) {
			echo "<pre>";
			print_r($backupRetenu2);
			echo "</pre>";
		}

		return $backupRetenu2;
		}
	
	/*-------------------------------------------------- FIN reduire */	

	/*------------------------------------------------------ findFilesRecursive */
  /*! @fn     private function findFilesRecursive())
   * @brief   
							Ce code PHP est utilisé pour comparer deux dossiers de manière récursive afin de vérifier si les fichiers dans les deux répertoires sont identiques ou non. La sortie finale sera affichée dans le format de tableau avec du texte coloré. Ce fichier php est utilisé pour les cas ci-dessous.

							Comparez deux dossiers de branche SVN locaux
							Comparez deux fichiers locaux quelconques
	*
	* @note			Source :\n
	*						https://github.com/sureshdotariya/folder-compare/blob/master/svncompare.php
	*
	* @note			Modifié par SP le 12-02-2019
	*/
	private function findFilesRecursive($fichier_1, $fichier_2)
		{
			
		if($this->debug) {
			echo "<hr style='color:blue;' size=1 />";
			echo "<span style='color:blue;'>findFilesRecursive(\$fichier_1, \$fichier_2)</span><br />";
			echo "<hr style='color:blue;' size=1 />";
			
			echo "\$fichier_1 =  $fichier_1<br />";
			echo "\$fichier_2 =  $fichier_2<br />";
			if ($this->bool)
				echo "1 bool =  TRUE<br />";
			else
				echo "1 bool =  FALSE<br />";
		}
			
			$handle = opendir($fichier_1);
			while(($file = readdir($handle))!==false)
			{
				if(in_array($file, $this->fichiers_exclus)) continue;
				$x = explode('.', $file);
				if( isset($x[1]) ) {
					if (in_array($x[1], $this->extensions_exclues)) continue;
					//echo "<br />------------------\$ext = ".$x[1];
				}
				
				$path_fichier_1 = $fichier_1."/".$file;
				$path_fichier_2 = $fichier_2."/".$file;
				//path that need to shown in final output
				//$finalPath = DIRECTORY_SEPARATOR.$file;
				$isFile = is_file($path_fichier_1);
				
				if($isFile){
/*					
					if(is_file($path_fichier_1)) {
								if($this->debug) echo "111 A - $path_fichier_1 : est un fichier<br />";					
					}
					if(file_exists($path_fichier_1)) {
								if($this->debug) echo "111 B - $path_fichier_1 : existe<br />";					
					}
				
					if(is_file($path_fichier_2)) {
								if($this->debug) echo "222 A - $path_fichier_2 : est un fichier<br />";					
					}
					if(file_exists($path_fichier_2)) {
								if($this->debug) echo "CCC B - $path_fichier_2 : existe<br />";					
					}
*/				
					
					if(is_file($path_fichier_2) && file_exists($path_fichier_2)){
						
						if(md5_file($path_fichier_1) === md5_file($path_fichier_2)){
//if($this->debug) echo "A - $path_fichier_1 : ".md5_file($path_fichier_1)."<br />";
						}else{
//if($this->debug) echo "B - $path_fichier_1 : ".md5_file($path_fichier_1)."<br />";
							$this->bool = false;
				//echo "AAAAAAAAAAAAAAAAAAA<br />";
				//echo "\$path_fichier_1 = ".$path_fichier_1."<br />";					
				//echo "\$path_fichier_2 = ".$path_fichier_2."<br />";					
						}
					}else{
//if($this->debug) echo "<b>C - $path_fichier_1 | $path_fichier_2 : dossier</b><br />";
						$this->bool = false;
				//echo "BBBBBBBBBBBBBBBBBBBBBBB<br />";
				//echo "\$path_fichier_1 = ".$path_fichier_1."<br />";					
				//echo "\$path_fichier_2 = ".$path_fichier_2."<br />";					
					}
					
			//if(!$this->bool) {
			//}
					
					
					
				}else{
//if($this->debug) echo "<b>D - $path_fichier_1 (dossier) | $path_fichier_2</b><br />";
					//array_merge($list,findFilesRecursive($path_fichier_1, $path_fichier_2, $finalPath));
//					if ($this->bool) {echo "A - FALSE !"; exit;}
					$this->findFilesRecursive($path_fichier_1, $path_fichier_2);
//					if ($this->bool) {echo "B - FALSE !"; exit;}
				}
			}
			closedir($handle);
		}
	/*-------------------------------------------------- FIN findFilesRecursive */


/* -------------------------------------------------- *\
		Vérifie que le fichier n'est pas dans :
		- une liste de fichiers exclus
		- une liste d'extensions exclues
		- une liste de fichiers déjà visités (optionnel)
\* -------------------------------------------------- */

function fichierRetenu($d, $fichier, $fichiers_visites='') {
	
	if( !is_dir( $d.$fichier ) ) {
			if( !in_array($fichier, $GLOBALS['fichiers_exclus'] ) ) {
				$fileinfo = pathinfo($fichier);
		/*
		if(is_dir($fichier)) echo "------- Dossier -------";		
				
		echo "<pre>";
		print_r($fileinfo);
		echo "</pre>";
		exit;
		*/
				$ext = strtolower($fileinfo["extension"]);
				
				if (!in_array($ext, $GLOBALS['extensions_exclues'])) {
					if ($fichiers_visites == '')
						return true;
					else
						if (!in_array($fichier, $fichiers_visites) ) return true;
				}
			}
	}
	return false;
}

	}// Fin de la classe
	
/** @} */
?>
