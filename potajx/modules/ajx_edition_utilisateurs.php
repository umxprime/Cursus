<?php
	/**
	 * Copyright © 2009 Maxime CHAPELET (umxprime@umxprime.com)
	 * 
	 * This file is a part of Potajx and Cursus
	 * 
	 * Potajx and Cursus are free softwares: you can redistribute them and/or modify
	 * them under the terms of the GNU General Public License as published by
	 * the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * Potajx and Cursus are distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with Potajx and Cursus.  If not, see <http://www.gnu.org/licenses/>.
	 * 
	 */
	 
	switch ($action){
		case "get_utilisateurs" :
			$base = $params["base"];
			mysql_select_db(BASE);
			$req = "SELECT * FROM $base";
			$users = mysql_query($req);
			$out = array();
			while($user=mysql_fetch_array($users)){
				array_push($out, $user["id"].":".strtoupper(utf8_encode($user["nom"]))." ".utf8_encode($user["prenom"]));
			}
			echo implode(",",$out);
			break;
		case "get_valeurs" :
			$base = $params["base"];
			$id = $params["id"];
			$fields = explode(";", $params["fields"]);
			$fields = implode(",", $fields);
			mysql_select_db(BASE);
			$req = "SELECT $base.$fields FROM $base WHERE id='$id'";
			$fields = mysql_fetch_array(mysql_query($req));
			$out = array();
			for($i=0;$i<count($fields);$i++)
			{
				if ($fields[$i]) array_push($out, $fields[$i]);
			}
			echo utf8_encode(implode(",",$out));
			break;
		case "get_ecoles_selon_prof" :
			$prof = $params["prof"];
			mysql_select_db(BASE);
			$req = "SELECT ecole FROM professeurs WHERE id=$prof;";
			$res = mysql_fetch_array(mysql_query($req));
			$selected = $res["ecole"];
			$req = "SELECT id,nom FROM ecoles;";
			$schools = mysql_query($req);
			$out = array();
			while($school=mysql_fetch_array($schools)){
				$str = $school["id"].":".utf8_encode($school["nom"]);
				if ($school["id"]==$selected) $str.=":selected";
				array_push($out, $str);
			}
			echo implode(",",$out);
			break;
		case "get_semestres_selon_etudiant" :
			$etudiant = $params["etudiant"];
			$semestre = $params["semestre"];
			mysql_select_db(BASE);
			$req = "SELECT niveau FROM niveaux WHERE etudiant=$etudiant AND periode='$semestre' ORDER BY id DESC;";
			$res = mysql_fetch_array(mysql_query($req));
			$selected = $res["niveau"];
			$out = array();
			for($i=0;$i<11;$i++)
			{
				$str = $i.":Semestre ".$i;
				if ($i==$selected) $str.=":selected";
				array_push($out, $str);
			}
			echo implode(",",$out);
			break;
		case "get_cycles_selon_etudiant" :
			$etudiant = $params["etudiant"];
			$semestre = $params["semestre"];
			if ($params["niveau"]) $niveau = $params["niveau"];
			mysql_select_db(BASE);
			$req = "SELECT cycle,niveau FROM niveaux WHERE etudiant=$etudiant AND periode='$semestre' ORDER BY id DESC;";
			$res = mysql_fetch_array(mysql_query($req));
			$selected = $res["cycle"];
			if (!$params["niveau"]) $niveau = $res["niveau"];
			if ($niveau==0)
			{
				echo " : ";
				break;
			}
			$req = "SELECT id,nom FROM cycles WHERE semestre_debut<='$niveau' AND semestre_fin>='$niveau';";
			$cycles = mysql_query($req);
			$out = array();
			while($cycle=mysql_fetch_array($cycles))
			{
				$str = $cycle["id"].":".utf8_encode($cycle["nom"]);
				if ($cycle["id"]==$selected) $str.=":selected";
				array_push($out, $str);
			}
			echo implode(",",$out);
			break;
		case "set_etudiants" :
			$base = $params["base"];
			$id = $params["id"];
			$nom = utf8_decode($params["nom"]);
			$prenom = utf8_decode($params["prenom"]);
			$passw = $params["passw"];
			$cycle = $params["cycle"];
			$niveau = $params["niveau"];
			$periode = $params["periode"];
			if ($id == "new")
			{
				$req = "INSERT INTO $base (`nom`,`prenom`,`passw`) VALUES";
				$req .= "('$nom','$prenom','$passw')";
				mysql_query($req);
				$req = "SELECT `id` FROM $base ORDER BY `id` DESC;";
				$newuser = mysql_fetch_array(mysql_query($req));
				$newid = $newuser["id"];
			} else {
				$req = "UPDATE $base SET ";
				$req .= "nom='$nom'";
				$req .= ",prenom='$prenom'";
				$req .= ",passw='$passw'";
				$req .= " WHERE id='$id';";
				mysql_query($req);
				$req = "DELETE FROM `niveaux` WHERE `cycle`='$cycle' AND `etudiant`='$id' AND `periode`='$periode';";
				mysql_query($req);
				$newid=$id;
			}
			$req = "INSERT INTO niveaux (`cycle`,`niveau`,`etudiant`,`periode`) VALUES('$cycle','$niveau','$newid','$periode');";
			mysql_query($req);
			echo $newid;
			break;
	}
?>