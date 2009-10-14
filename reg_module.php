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
require("connect_info.php");
require("connexion.php");
$reg=0;
//echo $_POST['action'];
if(strlen($_POST['action'])>1){
	if ($_POST['action']=="ajouter un enseignant"){
		$profs="";
		if(strlen($_POST['enseignants'])>5){$profs .= $_POST['enseignants'].", ";}
		$profs .= ($_POST['ajout_prof']);
		$reg=1;
	}
	if ($_POST['action']=="ajouter un pre-requis"){
		$pre_requis="";
		if(strlen($_POST['pre_requis'])>2){$pre_requis .= $_POST['pre_requis'].", ";}
		$pre_requis .= $_POST['ajout_pre_requis'];
		$reg=1;
	}
	if ($_POST['action']=="valider les modifications" or $reg){
		if($_POST['id']<0){
			$req= "INSERT INTO modules (id,code,intitule, description, enseignants,";
			$req .= "temps_encadre,temps_autonome, pre_requis, evaluation, credits, obligatoire,";
			$req .= "jour, seances, debut, fin, desuetude, ecole) ";
			$req .= "VALUES ('',";
			$req .= "'".$_POST['code']."'";
			$req .= ",'".utf8_decode($_POST['intitule'])."'";
			$req .=",'".utf8_decode($_POST['description'])."'";
			$req .=",'".(($profs)?utf8_decode($profs):utf8_decode($_POST['enseignants']))."'";
			$req .= ",'".$_POST['temps_encadre']."'";
			$req .= ",'".($_POST['fin']-$_POST['debut'])." h.'";
			$req .=",'".(($pre_requis)?$pre_requis:$_POST['pre_requis']);
			$req .="'";
			$req .= ",'".utf8_decode($_POST['evaluation'])."'";
			$req .= ",'".$_POST['credits']."'";
			$req .= ",'".$_POST['obligatoire']."'";
			$req .= ",'".$_POST['jour']."'";
			$req .= ",'".$_POST['seances']."'";
			$req .= ",'".$_POST['debut']."'";
			$req .= ",'".$_POST['fin']."'";
			$req .= ",'".$_POST['desuetude']."'";
			$req .= ",'".$_POST['ecole']."'";
			$req .= ");";
		}else {
			$req= "UPDATE modules SET ";
			$req .= "code='".$_POST['code']."',";
			$req .= "intitule='".utf8_decode($_POST['intitule'])."',";
			$req .= "description='".utf8_decode($_POST['description'])."',";
			$req .="enseignants='".(($profs)?utf8_decode($profs):utf8_decode($_POST['enseignants']));
			$req .="',";
			$req .="temps_encadre='".$_POST['temps_encadre']."',";
			$req .="temps_autonome='".($_POST['fin']-$_POST['debut'])." h.',";
			$req .="pre_requis='".(($pre_requis)?$pre_requis:$_POST['pre_requis'])."',";
			$req .= "evaluation='".$_POST['evaluation']."',";
			$req .= "credits='".$_POST['credits']."',";
			$req .= "obligatoire='".$_POST['obligatoire']."',";
			$req .= "jour='".$_POST['jour']."',";
			$req .= "seances='".$_POST['seances']."',";
			$req .= "debut='".$_POST['debut']."',";
			$req .= "fin='".$_POST['fin']."', ";
			$req .= "evaluation='".utf8_decode($_POST['evaluation'])."', ";
			$req .= "desuetude='".$_POST['desuetude']."', ";
			$req .= "ecole='".$_POST['ecole']."'";
			$req .= " WHERE id='".$_POST['id']."';";
		}
	}
	//echo $req;
	$res = mysql_query($req);
	//echo mysql_error($res);
	
	header("Location: edition_modules.php?id=".$_POST["id"]."&nPeriode=".$_GET["nPeriode"]);
}
?>
