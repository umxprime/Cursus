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
 * Cursus uses FPDF released by Olivier PLATHEY
 *
 * Cursus uses the Limelight Framework
 * released under the GPL <http://www.gnu.org/licenses/>
 * by Maxime CHAPELET (umxprime@umxprime.com)
 * 
 **/

	switch ($action)
	{
		case "init" :
			//echo "alert('$message');";
			//echo "document.body.innerHTML+=\"$message<br/>\\n\";";
			session_destroy();
			break;
		case "connexion" :
			//echo "alert('connexion $username');";
			$req = "SELECT * FROM etudiants WHERE log='$username' AND passw='$password';";
			$res = mysql_query ($req);
			if(mysql_num_rows($res)==0)
			{
				$req = "SELECT * FROM professeurs WHERE log='$username' AND passw='$password';";
				$res = mysql_query ($req);
				if(mysql_num_rows($res)==0)
				{
					echo "alert('identifiant ou mot de passe invalide');";
					echo "gEBI('username').value='';";
					echo "gEBI('password').value='';";
					echo "gEBI('username').focus();";
					break;
				}
				$professeur = mysql_fetch_array($res);
				$_SESSION['auto']= $professeur["autos"];
				$_SESSION['userid'] = $professeur['id'];
				$_SESSION['username'] = $professeur['prenom']." ".$professeur['nom'];
				$_SESSION['ecole'] = $professeur['ecole'];
				$req = "INSERT INTO mouche (";
				$req .= "`date`,`userid`,`username`,`type`,`statut`";
				$req .= ") VALUES(";
				$req .= "NOW(),'".$_SESSION['userid']."','".$_SESSION['username']."','".utf8_decode("connexion")."','".$_SESSION['auto']."'";
				$req .= ");";
				mysql_query($req);
				echo "window.location = \"sessions.php?nPeriode=".$semestre_courant."\"";
				break;
			} else {
				$etudiant = mysql_fetch_array($res);
				$_SESSION['auto']= "e";
				$_SESSION['userid'] = $etudiant['id'];
				$_SESSION['username'] = $etudiant['prenom']." ".$etudiant['nom'];
				$req = "SELECT cycles.ecole FROM niveaux,cycles WHERE niveaux.etudiant='".$etudiant['id']."' AND niveaux.cycle=cycles.id ORDER BY niveaux.periode DESC;";
				$res = mysql_query($req);
				$_SESSION['ecole']=mysql_result($res,0,"ecole");
				$req = "INSERT INTO mouche (";
				$req .= "`date`,`userid`,`username`,`type`,`statut`";
				$req .= ") VALUES(";
				$req .= "NOW(),'".$_SESSION['userid']."','".$_SESSION['username']."','".utf8_decode("connexion")."','".$_SESSION['auto']."'";
				$req .= ");";
				mysql_query($req);
				echo "window.location = \"etudiants.php\"";
				break;
			}
			break;
	}
?>