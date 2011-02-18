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
			//echo "alert('$message')";
			//echo "document.body.innerHTML+=\"$message<br/>\\n\";";
			break;
		case "desinscrire" :
			$req = "DELETE FROM evaluations WHERE id='$eval'";
			$res = mysql_query($req);
			echo "window.location = '?session=$session&nPeriode=$semestre_courant';";
			break;
		case "tout_desinscrire" :
			$req = "DELETE FROM evaluations WHERE session='$session'";
			$res = mysql_query($req);
			echo "window.location = '?session=$session&nPeriode=$semestre_courant';";
			break;
		case "inscrire" :
			if(substr($etudiant,0,1)=="s")
			{
				$niveau = substr($etudiant,1);
				$req = "SELECT etudiants.id FROM niveaux,etudiants,cycles WHERE niveaux.periode='$semestre_courant' AND niveaux.niveau='$niveau' AND etudiants.id=niveaux.etudiant AND niveaux.cycle=cycles.id AND cycles.ecole='".$_SESSION["ecole"]."';";
				//echo "alert(\"$req\")";
				//break;
				$res = mysql_query($req);
				while($etu=mysql_fetch_array($res))
				{
					$req = "SELECT evaluations.id FROM evaluations WHERE evaluations.etudiant='".$etu["id"]."' AND evaluations.session='$session';";
					if(mysql_num_rows(mysql_query($req))!=0)continue;
					$req = "INSERT INTO evaluations (";
					$req .= "`session`,`etudiant`";
					$req .= ") VALUES(";
					$req .= "'$session','".$etu["id"]."'";
					$req .= ");";
					mysql_query($req);
				}
			}else{
				$req = "INSERT INTO evaluations (";
				$req .= "`session`,`etudiant`";
				$req .= ") VALUES(";
				$req .= "'$session','$etudiant'";
				$req .= ");";
				$res = mysql_query($req);
			}
			echo "window.location = '?session=$session&nPeriode=$semestre_courant';";
			break;
	}
?>