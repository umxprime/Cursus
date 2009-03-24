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

	/* identification principale
	 * et implémentation des variables de session : username, userid, ecole, auto
	 * redirections en fonction du type de log (etudiants et autres)
	 * à terme sans doute un accés différencié administrateurs/coordinateurs/profs
	 * Dans la gestion des autorisations (lesotho.php) incluse dans les pages de cursus
	 * si la session n'est pas ouverte, les utilisateurs sont redirigés ici (log)
	 * la redirection joint en POST l'url de la page initialement demandée (origine)
	 */

	if ($PHPSESSID){
		session_start($PHPSESSID);}
	else{
		session_start();
	}
	
	//requises les variables de connexion puis la connexion à mysql;
	require("connect_info.php");
	require("connexion.php");
	include ("inc_sem_courant.php");
	
	//si une personne déjà identifiée revient sur cette page, la déconnexion est automatique et enregistrée;
	if($_SESSION['userid']>0){
		//mémorisation de la déconnexion dans la base mouche;
		$req = "INSERT INTO mouche (id,date, userid, type, statut) values('','".date("Y-m-d H:i:s")."','".$_SESSION['userid']."','deconnexion','".$_SESSION['auto']."');";
		
		//echo $req;
		$res = mysql_query($req);
		session_destroy();
	}
	
	//si une action est spécifiée (identification en provenance de cette même page);
if($_POST["action"] == "Login")
{
	//vérification que les champs ont été remplis;
	
	if(empty($_POST['username']))	$error["username"] = "Indiquez un nom d'utilisateur!";
	if(empty($_POST['password']))	$error["password"] = "Indiquez un nom mot de passe!";
	
	if(!is_array($error))
	{	
		//le nom d'utilisateur est passé en bas de casse;
		$username = trim(strtolower($_POST["username"]));
		//requete pour l'identification, objectif d'une requete unique, mais pas trouv� la m�thode encore
		$sql = "select etudiants.id from etudiants where log like '".$username."';";
		$res = mysql_query ($sql);
		if(mysql_num_rows($res)<=0){
			//echo "recherche chez les profs<br />\n";
			$sql = "select professeurs.id from professeurs where log like '".$username."';";
			$res = mysql_query ($sql);
			$eid=0;
			//echo mysql_error();
			$tp=mysql_fetch_array($res);
			$pid=$tp['id'];
		}else{
			//echo "recherche positive chez les �tudiants.<br />\n";
			$pid=0;
			$tp=mysql_fetch_array($res);
			$eid=$tp['id'];
		}
		
		//si l'identifiant est pr�sent dans les tables etudiants  ou professeurs
		
		if($eid)
		{//c'est un etudiants
			$q = "SELECT * FROM etudiants WHERE id='".$eid."';";
			$res = mysql_query($q);
			//particularit�s : un mode d'autorisation;
			$autos="e";
			//particularit�s : l'ecole d�pend du cycle en cours;
			$q = "SELECT etudiants.*, cycles.nom as nom_cyc, cycles.ecole, niveaux.niveau as niv, ecoles.nom as nom_ecole from etudiants, cycles, niveaux, ecoles ";
			$q .= "WHERE etudiants.id='".$eid."' AND niveaux.periode='".$semestre_courant."' AND niveaux.etudiant=etudiants.id";
			$q .= " AND cycles.id=niveaux.cycle AND ecoles.id=cycles.ecole";
		}else {//c'est un prof
			$q="SELECT * FROM professeurs WHERE id='".$pid."';";
		}
		//echo $q."<br />\n";
		$res= mysql_query($q);
		$usr = mysql_fetch_array($res);
		
		
		$password = trim(strtolower($_POST["password"]));
		
		//echo $password."<br />\n";
		//echo $usr['passw']."<br />\n";
		if($usr['passw'] == $password)
		{
			if(!isset($autos)) $autos=$usr['autos'];
			//echo $usr['passw']."===".$password;
			session_register("auto");
			$_SESSION['auto']= $autos;
			session_register('userid');
			$_SESSION['userid'] = $usr['id'];
			session_register('ecole');
			$_SESSION['ecole'] = $usr['ecole'];
			session_register('username');
			$_SESSION['username'] = $usr['prenom']." ".$usr['nom'];
			$req = "INSERT INTO mouche (id, date, userid, type, statut) values('','".date("Y-m-d H:i:s")."','".$usr['id']."','connexion', $autos);";
			$res = mysql_query($req);

			if($autos=="e")
			{//redirection des log �tudiants vers la page des etudiants.php
			header("Location: etudiants.php");
			//header("Location: etudiants.php?session_name()=".session_id());
			}
			else if(!$_GET['origine'])
			{//page par d�faut pour les non �tudiants (log non �tudiant et pas d'adresse de page pr�alable au log)
			//header("Location: sessions.php?session_name()=".session_id());
			header("Location: sessions.php?nPeriode=".$semestre_courant);
			}
			else
			{
			//redirection vers la page d'origine si elle est sp�cifi�e
			header("Location:".$_GET['origine']);
			}
		}		
		else
		{
		$error["login"] = "Echec de l'identification.";
		}
	}
}

//echo "include";
include("login_form.php");

if(is_array($error))
{
	//�num�ration des erreurs, m�thode issue du bout de code dont est issu cette page de log;
	while(list($key, $val) = each($error))
	{
		echo $val;
		echo "<br>\n";
	}
}

?>
