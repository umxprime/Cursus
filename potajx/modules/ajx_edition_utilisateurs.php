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
		case "check_logs" :
			$base = $params["base"];
			$id = $params["id"];
			$log = $params["log"];
			if ($log=="") break;
			mysql_select_db(BASE);
			$found=false;
			$req = "SELECT id,log FROM etudiants WHERE log='$log' AND id!='$id';";
			$res = mysql_num_rows(mysql_query($req));
			if($res>0){
				$found=true;
			}
			$req = "SELECT log FROM professeurs WHERE log='$log' AND id !='$id';";
			$res = mysql_num_rows(mysql_query($req));
			if($res>0){
				$found=true;
			}
			echo $found;
			break;
		case "get_utilisateurs" :
			$base = $params["base"];
			$periode = $params["periode"];
			$selected = $params["selected"];
			mysql_select_db(BASE);
			if ($base=="etudiants")
			{
				$req = "SELECT etudiants.id, etudiants.nom, etudiants.prenom,niveaux.etudiant,niveaux.cycle,cycles.id as cycle_id, cycles.ecole, ecoles.id as ecole_id, ecoles.nom as ecole_nom FROM etudiants,niveaux,cycles,ecoles WHERE etudiants.periode_sortie<1 AND etudiants.id=niveaux.etudiant AND niveaux.cycle=cycles.id AND cycles.ecole=ecoles.id AND niveaux.periode>'".($periode-2)."' ORDER BY nom ASC";
			}
			if ($base=="professeurs") $req = "SELECT professeurs.nom,professeurs.prenom,professeurs.id,professeurs.ecole,ecoles.id as ecole_id,ecoles.nom as ecole_nom FROM professeurs,ecoles WHERE professeurs.ecole=ecoles.id ORDER BY nom ASC";
			$users = mysql_query($req);
			$out = array();
			$oldid = -1;
			while($user=mysql_fetch_array($users)){
				if ($user["id"]==$oldid) array_pop($out);
				$value = $user["id"].":".strtoupper(utf8_encode($user["nom"]))." ".utf8_encode($user["prenom"]." (".$user["ecole_nom"].")");
				if($selected==$user["id"]) $value .= ":selected";
				array_push($out, $value);
				$oldid = $user["id"];
			}
			$values = implode(",",$out);
			echo "ajx_select(\"liste_utilisateurs\",\"chg_utilisateur\",\"$values\",true,true)";
			break;
		case "get_logtype" :
			$base = $params["base"];
			$id = $params["id"];
			mysql_select_db(BASE);
			$req = "SELECT * FROM $base WHERE id='$id';";
			$user = mysql_fetch_array(mysql_query($req));
			$out = array();
			$logtypes = array("pnom","prenomnom","prenom.nom","custom");
			for ($i=0;$i<count($logtypes);$i++)
			{
				$option = $i.":".$logtypes[$i];
				if($user["logtype"]==$i) $option .= ":selected";
				array_push($out, $option);
			}
			$values = implode(",",$out);
			echo "ajx_select(\"logtype\",\"update_log\",\"$values\",false,true);";
			break;
		case "get_valeurs" :
			$base = $params["base"];
			$id = $params["id"];
			$fields = explode(";", $params["fields"]);
			$fields = implode(",", $fields);
			mysql_select_db(BASE);
			$req = "SELECT $fields FROM $base WHERE id='$id'";
			$fields = explode(",", $fields);
			$sqlfields = mysql_fetch_array(mysql_query($req));
			$out = array();
			for($i=0;$i<count($fields);$i++)
			{
				array_push($out, $fields[$i].":".$sqlfields[$i]);
			}
			$fields = utf8_encode(implode(",",$out));
			echo "ajx_inputTexts(\"$fields\");";
			break;
		case "get_ecoles_selon_prof" :
			$prof = $params["prof"];
			//echo "alert('ok')";
			//break;
			mysql_select_db(BASE);
			$req = "SELECT ecole FROM professeurs WHERE id=$prof;";
			$res = mysql_query($req);
			if($res) $res = mysql_fetch_array($res);
			$selected = $res["ecole"];
			$req = "SELECT id,nom FROM ecoles;";
			$schools = mysql_query($req);
			$out = array();
			while($school=mysql_fetch_array($schools)){
				$str = $school["id"].":".utf8_encode($school["nom"]);
				if ($school["id"]==$selected) $str.=":selected";
				array_push($out, $str);
			}
			$values = implode(",",$out);
			echo "ajx_select(\"liste_ecoles\",\"null\",\"$values\",false,true);";
			break;
		case "get_semestres_selon_etudiant" :
			$etudiant = $params["etudiant"];
			$semestre = $params["semestre"];
			mysql_select_db(BASE);
			$req = "SELECT niveau FROM niveaux WHERE etudiant=$etudiant AND periode='$semestre' ORDER BY id DESC;";
			$res = mysql_query($req);
			if($res) $res = mysql_fetch_array($res);
			$selected = $res["niveau"];
			$out = array();
			for($i=0;$i<11;$i++)
			{
				$str = $i.":Semestre ".$i;
				if ($i==$selected) $str.=":selected";
				array_push($out, $str);
			}
			$values = implode(",",$out);
			echo "ajx_select(\"liste_semestres\",\"chg_semestre\",\"$values\",false,true);";
			break;
		case "get_cycles_selon_etudiant" :
			$etudiant = $params["etudiant"];
			$semestre = $params["semestre"];
			$niveau = $params["niveau"];
			mysql_select_db(BASE);
			$req = "SELECT cycle,niveau FROM niveaux WHERE etudiant=$etudiant AND periode='$semestre' ORDER BY id DESC;";
			$res = mysql_query($req);
			if(!$res)
			{
				$req = "SELECT cycle,niveau FROM niveaux WHERE etudiant=$etudiant AND periode='".($semestre-1)."' ORDER BY id DESC;";
				$res = mysql_query($req);
			}
			if($res) $res = mysql_fetch_array($res);
			$selected = $res["cycle"];
			if (!isset($params["niveau"])) $niveau = $res["niveau"];
			if ($niveau==0)
			{
				echo "ajx_select(\"liste_cycles\",null,\" : \",false, true);";
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
			$values = implode(",",$out);
			//echo "alert('$values');";
			echo "ajx_select(\"liste_cycles\",null,\"$values\",false, true);";
			break;
		case "set_etudiants" :
			$base = $params["base"];
			$id = $params["id"];
			$nom = utf8_decode($params["nom"]);
			$prenom = utf8_decode($params["prenom"]);
			$log = $params["log"];
			$logtype = $params["logtype"];
			$mail = $log."@esa-npdc.net";
			$passw = $params["passw"];
			$cycle = $params["cycle"];
			$niveau = $params["niveau"];
			$periode = $params["periode"];
			if ($cycle=="")
			{
				$req = "DELETE FROM niveaux WHERE etudiant='$id' AND periode='$periode';";
				mysql_query($req);
				break;
			}
			if ($id == "new")
			{
				$req = "INSERT INTO $base (`nom`,`prenom`,`passw`,`log`,`logtype`,`mail`) VALUES";
				$req .= "('$nom','$prenom','$passw','$log','$logtype','$mail')";
				$res = mysql_query($req);
				//$req = "SELECT `id` FROM $base ORDER BY `id` DESC;";
				//$newuser = mysql_fetch_array(mysql_query($req));
				$newid = mysql_insert_id();
			} else {
				$req = "UPDATE $base SET ";
				$req .= "nom='$nom'";
				$req .= ",prenom='$prenom'";
				$req .= ",passw='$passw'";
				$req .= ",log='$log'";
				$req .= ",logtype='$logtype'";
				$req .= ",mail='$mail'";
				$req .= " WHERE id='$id';";
				mysql_query($req);
				$req = "DELETE FROM `niveaux` WHERE `etudiant`='$id' AND `periode`='$periode';";
				mysql_query($req);
				$newid=$id;
			}
			$req = "INSERT INTO niveaux (`cycle`,`niveau`,`etudiant`,`periode`) VALUES('$cycle','$niveau','$newid','$periode');";
			mysql_query($req);
			echo $newid;
			break;
		case "set_professeurs" :
			$base = $params["base"];
			$id = $params["id"];
			$nom = utf8_decode($params["nom"]);
			$prenom = utf8_decode($params["prenom"]);
			$log = $params["log"];
			$logtype = $params["logtype"];
			$passw = $params["passw"];
			$autos = $params["autos"];
			$ecole = $params["ecole"];
			if ($id == "new")
			{
				$req = "INSERT INTO $base (`nom`,`prenom`,`nom_complet`,`passw`,`log`,`logtype`,`autos`,`ecole`) VALUES";
				$req .= "('$nom','$prenom','$prenom $nom','$passw','$log','$logtype','$autos','$ecole')";
				$res = mysql_query($req);
				//$req = "SELECT `id` FROM $base ORDER BY `id` DESC;";
				//$newuser = mysql_fetch_array(mysql_query($req));
				$newid = mysql_insert_id();
			} else {
				$req = "UPDATE $base SET ";
				$req .= "nom='$nom'";
				$req .= ",prenom='$prenom'";
				$req .= ",nom_complet='$prenom $nom'";
				$req .= ",passw='$passw'";
				$req .= ",log='$log'";
				$req .= ",logtype='$logtype'";
				$req .= ",autos='$autos'";
				$req .= ",ecole='$ecole'";
				$req .= " WHERE id='$id';";
				mysql_query($req);
				$newid=$id;
			}
			echo $newid;
			break;
		case "del_utilisateur" :
			$base = $params["base"];
			$id = $params["id"];
			$req = "DELETE FROM $base WHERE id='$id';";
			mysql_query($req);
			echo "alert('user $id deleted');location.reload(true);";
			break;
	}
?>