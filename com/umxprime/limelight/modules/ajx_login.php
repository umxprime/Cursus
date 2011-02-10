<?php
	/**
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
				echo "window.location = \"etudiants.php\"";
				break;
			}
			break;
		/*
		case "chargeListeUtilisateurs":
			switch ($base)
			{
				case "etudiants":
					$req = "SELECT $base.nom, $base.prenom, $base.id FROM $base WHERE ($base.nom LIKE '%$filtre%' OR $base.prenom LIKE '%$filtre%') ORDER BY $base.nom ASC,$base.prenom ASC";
					break;
				case "professeurs":
					$req = "SELECT nom,prenom,id FROM professeurs WHERE nom LIKE '%$filtre%' OR prenom LIKE '%$filtre%' ORDER BY nom;";
					break;
			}
			//echo "alert(".json_encode($req).");";
			$res = mysql_query($req);
			$users=array();
			$users["text"]=array();
			$users["value"]=array();
			$oldId=-1;
			$double=array();
			while($user = mysql_fetch_array($res))
			{
				if($oldId == $user["id"])
				{
					continue;
				}
				array_push($users["text"],utf8_encode(strtoupper($user["nom"])." ".$user["prenom"]));
				array_push($users["value"],$user["id"]);
				$oldId = $user["id"];
			}
			//$double = json_encode($double);
			//echo "alert($double);";
			$users = json_encode($users);
			echo "var listeUtilisateurs = $users;";
			echo "var elt = gEBI('utilisateurs');elt.clearOptions();elt.appendOption('Nouvel utilisateur','-1');";
			echo "for(var i=0;i<listeUtilisateurs['text'].length;i++)elt.appendOption(listeUtilisateurs['text'][i],listeUtilisateurs['value'][i]);";
			break;
		case "chargeInfosUtilisateurs":
			if($utilisateur==-1)
			{
				echo("nouvelleEntree();");
				exit();
			}
			switch($base)
			{
				case "etudiants" :
					$req = "SELECT $base.nom,$base.prenom,$base.log,$base.passw,$base.logtype FROM $base WHERE $base.id='$utilisateur';";
					$res = mysql_query($req);
					$user = mysql_fetch_array($res);
					$req = "SELECT niveaux.niveau,niveaux.cycle,niveaux.periode FROM $base,niveaux WHERE $base.id='$utilisateur' AND niveaux.etudiant=$base.id AND niveaux.periode='$periode' ORDER BY niveaux.niveau DESC;";
					$res = mysql_query($req);
					$user["niveau"]=-1;
					$user["cycle"]=-1;
					if(mysql_num_rows($res))
					{
						$niveau = mysql_fetch_array($res);
						$user["niveau"] = $niveau["niveau"];
						$user["cycle"] = $niveau["cycle"];
					}
					//if($user["periode"]!=$periode)$user["niveau"]=0;
					break;
				case "professeurs" :
					$req = "SELECT $base.nom,$base.prenom,$base.log,$base.passw,$base.logtype,$base.autos,$base.ecole FROM $base WHERE $base.id='$utilisateur'";
					$res = mysql_query($req);
					$user = mysql_fetch_array($res);
					break;
			}
			$user["nom"]=utf8_encode(strtoupper($user["nom"]));
			$user["prenom"]=utf8_encode($user["prenom"]);
			$user = json_encode($user);
			echo "var user=$user;";
			echo "gEBI('nom').value=user['nom'];";
			echo "gEBI('prenom').value=user['prenom'];";
			echo "gEBI('log').value=user['log'];";
			echo "gEBI('passw').value=user['passw'];";
			echo "gEBI('logtype').value=user['logtype'];";
			switch($base)
			{
				case "etudiants":
					echo "gEBI('niveau').value=user['niveau'];";
					echo "chargeCycleSelonSemestre(user['cycle']);";
					echo "gEBI('utilisateurs').updateSelectedOption(user['nom']+' '+user['prenom'],$utilisateur);";
					break;
				case "professeurs":
					echo "gEBI('auto').value=user['autos'];";
					echo "gEBI('ecole').value=user['ecole'];";
					echo "gEBI('utilisateurs').updateSelectedOption(user['nom']+' '+user['prenom'],$utilisateur);";
					echo "";
					break;
			}
			break;
		case "chargeCyclesSelonSemestre" :
			if($niveau==0)
			{
				echo "gEBI('cycle').clearOptions();";
				exit();
			}
			$req = "SELECT cycles.id, cycles.nom FROM cycles WHERE cycles.semestre_debut<='$niveau' AND cycles.semestre_fin>='$niveau' ORDER BY cycles.nom";
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
			echo "var elt = gEBI('cycle');elt.clearOptions();";
			echo "for(var i=0;i<listeCycles['text'].length;i++)elt.appendOption(listeCycles['text'][i],listeCycles['value'][i]);";
			if(isset($selectedcycle))echo "elt.value=$selectedcycle;";
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
					$req = "UPDATE $base SET `nom`='$nom', `prenom`='$prenom', `nom_complet`='$prenom $nom', `log`='$log', `logtype`='$logtype', `passw`='$passw', `autos`='$auto', `ecole`='$ecole' WHERE $base.id='$id';";
					$res = mysql_query($req);
					echo "chargeUtilisateur($id);";
					break;
			}
			break;
		*/
	}
?>