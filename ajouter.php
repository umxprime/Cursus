<?php
	/**
	 * 
	 * Copyright © 2007,2008,2009 Roland DECAUDIN (roland@xcvbn.net)
	 * Copyright © 2008,2009,2010,2011 Maxime CHAPELET (umxprime@umxprime.com)
	 *
	 * This file is a part of Cursus
	 *
	 * Cursus is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * Cursus is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with Cursus.  If not, see <http://www.gnu.org/licenses/>.
	 *
	 * Cursus uses a modified version of TinyButStrong and TinyButStrongOOo
	 * originally released under the LGPL <http://www.gnu.org/licenses/>
	 * by Olivier LOYNET (tbsooo@free.fr)
	 *
	 * Cursus uses Potajx
	 * released under the GPL <http://www.gnu.org/licenses/>
	 * by Maxime CHAPELET (umxprime@umxprime.com)
	 * 
	 **/
	
require("connect_info.php");
require("connexion.php");
include("fonctions.php");
include("rss_a_jour.php");
$table = $_POST["table"];
if(!$table){$table="etudiants";}
$id_clef = donne_clef($table, $connexion); 
$$id_clef = ($_POST[$id_clef])?$_POST[$id_clef]:$_GET[$id_clef];
$ajour_rss = false;

$cols = liste_colonnes($table);
		$clefs= "";
		$vals = "";
		$upchaine ="";
		$sep = "";
		$requete = "";
		foreach ($cols as $col) {
			//echo $clef." : ".$valeur;
			if(($col['type'] == "date") or ($col['type']=="datetime")){
			$val = $_POST["annee_".$col['nom']];
			$val .="-".$_POST["mois_".$col['nom']];
			$val .="-".$_POST["jour_".$col['nom']];
				if($col['type']=="datetime"){
					$val .=" ".$_POST["heures_".$col['nom']];
					$val .=":";
					$val .= ($_POST["minutes_".$col['nom']])?$_POST["minutes_".$col['nom']]:"00";
					$val .=":";
					$val .=($_POST["secondes_".$col['nom']])?$_POST["secondes_".$col['nom']]:"00";
				}
			}
			else
			{
				$val = $_POST[$col['nom']];
				//$val = stristr($col['type'], "text")?nl2br(htmlentities($_POST[$col['nom']],ENT_QUOTES, 'UTF-8')):$_POST[$col['nom']];
			}
			if($col['nom'] != $id_clef){
				$clef = $col['nom'];
				$clefs .= $sep.$clef;
				$vals .= $sep."'".$val."'";
				$upchaine .= $sep.$clef." = '".$val."'";
				$sep = ", ";
			}
		}
		$action = $_POST['action'];
		if ($action){
		if ($action == "ajouter"){
		$requete = "INSERT INTO ".$table." (".$id_clef.", ".$clefs.") VALUES ('', ".$vals.");";
		if($id_clef=="id_article")
		$ajour_rss= true;
		}
		else {
			if ($action == "supprimer"){
				if ($table=="blorg_images"){
					unlink($col['url_image']);
					unlink($col['url_poucet']);
				}
				$requete = "DELETE FROM ".$table."  WHERE ".$id_clef."='".$$id_clef."';";
			}
			else
			{
				if ($action == "modifier"){
					$requete = "UPDATE ".$table." SET ".$upchaine." WHERE ".$id_clef."='".$$id_clef."';";
				}
			}
		}
		
		//echo $requete;
		if ($requete != ""){
			$res = mysql_query($requete);
			
			if ($action=="ajouter")
			$$id_clef=mysql_insert_id($res);
			
			$retour = $_POST['retour']."?".$id_clef."=".$$id_clef;
			$retour .= "&rubrique=".$_POST['rubrique'];
			if ($ajour_rss){
			ajour_rss($connexion);
			}
			header("Location: ".$retour);
			echo mysql_error();
			
		}
		}
		
		?>
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Ajout d'un &eacute;l&eacute;ment dans la base "<?php echo $table; ?>"</title>

	</head>
	<body>
	<?php echo $clefs."<br />";
		echo $vals."<br />";
		?>
	</body>
</html>