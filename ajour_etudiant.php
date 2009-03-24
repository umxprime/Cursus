<?php
	/**
	 * 
	 * Copyright © 2007,2008,2009 Roland DECAUDIN (roland@xcvbn.net)
	 * Copyright © 2008,2009 Maxime CHAPELET (umxprime@umxprime.com)
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
	
	include("lesotho.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="cursus.css" type="text/css">
		<title><?php echo $ligne['prenom']." ".$ligne['nom']; ?></title>
	</head>
<body>
	<?php
		include("fonctions.php");
		//on requiert les variables de connexion;
		require("connect_info.php");
		//puis la connexion standard;
		require("connexion.php");
		include("inc_sem_courant.php");
		$rq = "SELECT * FROM transit_etu";
		$rs = mysql_query($rq);
		while ($et = mysql_fetch_array($rs)){
					$prenomFormat = clean_chaine($et['prenom']);
				$nomFormat = clean_chaine($et['nom']);
				$log = $prenomFormat{0}.$nomFormat;
			$rq = "SELECT * FROM etudiants WHERE log LIKE '".$log."';";
			$rtemp = mysql_query($rq);
			if (mysql_num_rows($rtemp)<1){
				$rq = "INSERT INTO etudiants (id, nom, prenom, passw, mail, log, adresse, cp, ville, naissance, ville_naiss, pays) ";
				$rq .= "VALUES ('', ";
				$rq .= "'".UTF8_encode($et['nom'])."', ";
				$rq .= "'".UTF8_encode($et['prenom'])."', ";
				$rq .= "'".generatePassword()."', ";
				$rq .= "'".$log."@esa-cambrai.net', ";
				$rq .= "'".$log."', ";
				$rq .= "\"".UTF8_encode($et['adresse'])."\", ";
				$rq .= "'".$et['cp']."', ";
				$rq .= "'".UTF8_encode($et['ville'])."', ";
				$rq .= "'".$et['naissance']."', ";
				$rq .= "'".UTF8_encode($et['ville_naiss'])."', ";
				$rq .= "'".UTF8_encode($et['pays_origine'])."')";		
				$rinsert = mysql_query($rq);
				echo mysql_error().'|'.$rq;
				$id_insert = mysql_insert_id();
				$rq= "INSERT INTO niveaux (id, niveau, etudiant, periode) VALUES (";
				$rq .="'','".$et['niveau']."','".$id_insert."','".$periode['id']."') ;";
				$rniv = mysql_query($rq);
				echo mysql_error().'|'.$rq;
				echo "<p>ajout&eacute; : ".$et['log']."|".$et['mail']."|".$et['passw']."</p>\n";
			}else{
				$deja = mysql_fetch_array($rtemp);
				$rq = "UPDATE etudiants ";
				$rq .= "SET nom='".UTF8_encode(ucwords(strtolower(utf8_decode($et['nom']))))."', ";
				$rq .= "prenom ='".UTF8_encode(ucwords(strtolower(utf8_decode($et['prenom']))))."', ";
				$rq .= "adresse='".UTF8_encode($et['adresse'])."', ";
				$rq .= "cp='".$et['cp']."', ";
				$rq .= "ville='".UTF8_encode(ucfirst(strtolower($et['ville'])))."', ";
				$rq .= "naissance='".$et['naissance']."', ";
				$rq .= "ville_naiss = '".UTF8_encode(ucfirst(strtolower($et['ville_naiss'])))."', ";
				$rq .= "pays='".UTF8_encode(ucwords(strtolower(rtrim($et['pays_origine']))))."'";	
				$rq .= " WHERE id='".$deja['id']."'";
				$res = mysql_query($rq);
				
				echo "<p>modifi&eacute; : ".mysql_error($res)." | ".$rq.$log."</p>\n";
			}
		}
	
	?>
</body>
</html>
