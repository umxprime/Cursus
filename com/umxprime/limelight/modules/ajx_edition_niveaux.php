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
		case "chargeListeEtudiants":
			if($cycle==-1)
			{
				$req = "SELECT etudiants.nom, etudiants.prenom, etudiants.id, niveaux.niveau, niveaux.periode, niveaux.cycle, cycles.semestre_debut, cycles.semestre_fin, niveaux.periode FROM etudiants,niveaux,cycles WHERE cycles.ecole='$ecole' AND niveaux.cycle=cycles.id AND niveaux.etudiant=etudiants.id AND (niveaux.periode='$periode' OR (niveaux.periode='".($periode-1)."' AND niveaux.niveau!='0') AND niveaux.niveau) AND (etudiants.nom LIKE '%$filtre%' OR etudiants.prenom LIKE '%$filtre%') ORDER BY etudiants.nom ASC, etudiants.prenom ASC, niveaux.periode DESC, niveaux.niveau ASC;";
				//echo "alert(".json_encode($req).");";exit();
			}else{
				$req = "SELECT etudiants.nom, etudiants.prenom, etudiants.id, niveaux.niveau, niveaux.periode, niveaux.cycle, cycles.semestre_debut, cycles.semestre_fin, niveaux.periode FROM etudiants,niveaux,cycles WHERE cycles.id=niveaux.cycle AND niveaux.etudiant=etudiants.id AND niveaux.periode='$periode' AND niveaux.cycle='$cycle' AND (etudiants.nom LIKE '%$filtre%' OR etudiants.prenom LIKE '%$filtre%') ORDER BY etudiants.nom ASC,etudiants.prenom ASC,niveaux.periode DESC,niveaux.niveau ASC;";
			}
			$res = mysql_query($req);
			$users=array();
			$oldId=-1;
			$oldNiveau = 0;
			while($user = mysql_fetch_array($res))
			{
				if($oldId == $user["id"])
				{
					continue;
				}
				if($user["periode"]==$periode-1 && ($user["niveau"]>10 && $user["niveau"]!=33))continue;
				if($user["periode"]==$periode-1)$user["niveau"]=0;
				$user["nom"]=utf8_encode(strtoupper($user["nom"]));
				$user["prenom"]=utf8_encode($user["prenom"]);
				if($user["niveau"]==0)$user["statut"]=0;
				if($periode==$user["periode"] && $user["niveau"]<=10)$user["statut"]=1;
				array_push($users,$user);
				$oldId = $user["id"];
				$oldNiveau = $user["niveau"];
			}
			//$double = json_encode($double);
			//echo "alert($double);";
			$users = json_encode($users);
			$req = "SELECT cycles.nom, cycles.id FROM cycles WHERE 1 ";
			if(!$droits[$_SESSION["auto"]]["voir_tous_sites"]) $req.="AND cycles.ecole='".$_SESSION["ecole"]."' ";
			$req.= "ORDER BY cycles.semestre_debut ASC;";
			$res = mysql_query($req);
			$cycles = array();
			$cycles["text"] = array();
			$cycles["value"] = array();
			while($cycle = mysql_fetch_array($res))
			{
				array_push($cycles["value"],$cycle["id"]);
				array_push($cycles["text"],utf8_encode($cycle["nom"]));
			}
			$cycles = json_encode($cycles);
			echo "faitListeEtudiants($users,$cycles);";
			break;
		case "chargeCycles" :
			$req = "SELECT cycles.id, cycles.nom FROM cycles WHERE cycles.ecole='$ecole' ";
			$req.= "ORDER BY cycles.semestre_debut";
			$res = mysql_query($req);
			$cycles = array();
			$cycles["text"] = array();
			$cycles["value"] = array();
			while($cycle = mysql_fetch_array($res))
			{
				array_push($cycles["value"],$cycle["id"]);
				array_push($cycles["text"],utf8_encode($cycle["nom"]));
			}
			$cycles = json_encode($cycles);
			
			echo "var listeCycles = $cycles;";
			echo "var elt = gEBI('cycles');elt.clearOptions();elt.appendOption('Tous',-1);";
			echo "for(var i=0;i<listeCycles['text'].length;i++)elt.appendOption(listeCycles['text'][i],listeCycles['value'][i]);";
			echo "chargeListeEtudiants();";
			break;
		case "changeCycleEtudiant":
			$req = "SELECT cycles.semestre_debut, cycles.semestre_fin FROM cycles WHERE cycles.id='$cycle';";
			$res = mysql_query($req);
			$niveaux = mysql_fetch_array($res);
			$semestre_debut=$niveaux["semestre_debut"];
			$semestre_fin=$niveaux["semestre_fin"];
			echo "var elt=gEBI('niveau_$id');elt.clearOptions();elt.appendOption('-',0);elt.appendOption('Auditeur libre',33);";
			echo "for(var i=$semestre_debut;i<=$semestre_fin;i++)elt.appendOption('Annee '+Math.round(i/2)+' (semestre '+i+')',i);";
			echo "elt.appendOption('Parti(e) au semestre précédent',13);";
			break;
		case "changeNiveauEtudiant":
			//echo "alert([$niveau,$id,$cycle,$ecole,$periode]);";
			$req = "DELETE FROM niveaux WHERE niveaux.etudiant='$id' AND niveaux.periode='$periode'";
			$res = mysql_query($req);
			$req = "SELECT * FROM niveaux WHERE niveaux.etudiant='$id' AND niveaux.periode<'$periode' ORDER BY niveaux.periode DESC;";
			$res = mysql_query($req);
			if(mysql_numrows($res))
			if($niveau==0 && !(mysql_result($res,0,"niveau")>0 && mysql_result($res,0,"niveau")<=10)){echo "changeCycle();";break;}
			$req = "INSERT INTO niveaux (`etudiant`,`periode`,`cycle`,`niveau`) VALUES('$id','$periode','$cycle','$niveau');";
			$res = mysql_query($req);
			echo "changeCycle();";
			break;
		case "valider" :
			$nom = utf8_decode($nom);
			$prenom = utf8_decode($prenom);
			switch($base)
			{
				case "etudiants":
					switch($id){
						case -1:
							$req = "INSERT INTO $base (nom,prenom,log,logtype,passw) VALUES('$nom','$prenom','$log','$logtype','$passw');";
							$res = mysql_query($req);
							$id = mysql_insert_id();
							break;
						default :
							$req = "UPDATE $base SET `nom`='$nom', `prenom`='$prenom', `log`='$log', `logtype`='$logtype', `passw`='$passw' WHERE $base.id='$id';";
							$res = mysql_query($req);
					}					
					$req = "DELETE FROM niveaux WHERE niveaux.periode='$periode' AND niveaux.etudiant='$id';";
					$res = mysql_query($req);
					$req = "INSERT INTO niveaux (`periode`,`niveau`,`etudiant`,`cycle`) VALUES('$periode','$niveau','$id','$cycle');";
					$res = mysql_query($req);
					echo "chargeUtilisateur($id);";
					break;
				case "professeurs":
					$req = "UPDATE $base SET `nom`='$nom', `prenom`='$prenom', `log`='$log', `logtype`='$logtype', `passw`='$passw', `autos`='$auto', `ecole`='$ecole' WHERE $base.id='$id';";
					$res = mysql_query($req);
					echo "chargeUtilisateur($id);";
					break;
			}
			break;
	}
?>