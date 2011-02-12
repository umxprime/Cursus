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
	
require "include/necessaire.php";

$reg=0;
//echo $_POST['action'];
if(isset($_POST['action'])){
	//	if ($_POST['action']=="ajouter un enseignant"){
	//		$profs="";
	//		if(strlen($_POST['enseignants'])>5){$profs .= $_POST['enseignants'].", ";}
	//		$profs .= $_POST['ajout_prof'];
	//		$reg=1;
	//	}
	//	if ($_POST['action']=="ajouter un pre-requis"){
	//		$pre_requis="";
	//		if(strlen($_POST['pre_requis'])>2){$pre_requis .= $_POST['pre_requis'].", ";}
	//		$pre_requis .= $_POST['ajout_pre_requis'];
	//		$reg=1;
	//	}
	if($_POST['action']=="inscrire"){
		$req = "select etudiants.*, niveaux.niveau from etudiants, niveaux where etudiants.id='".$_POST['etudiant'];
		$req .= "' and niveaux.etudiant = '".$_POST['etudiant']."' and niveaux.periode='".$semestre_courant."';";
		$res = mysql_query($req);
		$etu = mysql_fetch_array($res);
		$req = "INSERT INTO tutorats (id,semestre, etudiant, professeur, niveau, trash) ";
		$req .= "VALUES ('','".$_POST['periode']."','".$etu['id']."','".$_POST['tuteur']."','".$etu['niveau']."',0);";
		$res = mysql_query($req);
		$id_tutorat = mysql_insert_id($connexion);
		$sem = $etu['niveau'];
		$nrdv=($sem+1)/2;
		
		for($n=1;$n<=$nrdv;$n++){
			$req = "INSERT INTO rdv (id, date, cr, tutorat, id_prof, id_etudiant, ordre)";
			$req .= " VALUES('','".date("Y-m-d H:i:s")."','','".$id_tutorat."','".$_POST['tuteur']."','".$etu['id']."','".$n."');";
			$res = mysql_query($req);
		}
		$req = "INSERT INTO evaluations (session, etudiant, appreciation_1, appreciation_2, valide_1,";
		$req .= " valide_2, note_1, note_2,presences, tutorat)";
		$req .= "VALUES ('',".$etu["id"].",'','','','','','','',".$id_tutorat.");";
		$r = mysql_query($req);
	}else if($_POST['action']=="desinscrire"){
		$req = "UPDATE tutorats set trash=1 where id=".$_POST['tutorat'];
		$res = mysql_query($req);
	}
	//echo $req;

	//echo mysql_error($res);
	header("Location: tutorats.php?tuteur=".$_POST["tuteur"]."&nPeriode=$semestre_courant");
}
?>
