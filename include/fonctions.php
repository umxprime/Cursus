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

function revision()
{
	$file = fopen("revisions","r");
	$data = fread($file,filesize("revisions"));
	$data = explode("==\n",$data);
	$data = substr($data[0],2,strlen($data[0]));
	echo "version ".$data;
}

function array_orderby() // source : jimpoz @ http://www.php.net/manual/fr/function.array-multisort.php
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

function revision_infos()
{
	$file = fopen("revisions","r");
	$data = fread($file,filesize("revisions"));
	$data = explode("==\n",$data);
	$rev = array();
	for ($i=0;$i<count($data);$i++)
	{
		$rev = substr($data[0],2,strlen($data[0]));
	}
	echo "version ".$data;
}

function affiche_options($liste, $coche, $nouveau){
	if($nouveau){
		$c_select = "\t\t<option value=\"-1\" ";
		$c_select .= ($coche < 0) ? "selected >"  : ">";
		$c_select .= "Nouvel Enregistrement</option> <br />\n";
	}
	$n = 0;
	while($data = $liste[$n]) {
		$c_select .= "\t\t<option value=\"".utf8_encode($data['val'])."\"";
		if ($data['val']==$coche){
			$c_select.= " selected ";
		}
		$c_select .= ">".utf8_encode($data['aff'])."</option> <br />\n";
		$n++;
	}
	return $c_select;
}

function affiche_ligne($nom,$valeur, $taille=64){
	//$c_ligne = $nom." : ";
	$c_ligne = "<input type=\"text\" id=\"$nom\" name=\"".$nom."\" size=\"".$taille."\" ";
	$c_ligne .= "value=\"".utf8_encode($valeur)."\" >";
	$c_ligne .= "<br />\n";
	return $c_ligne;
}
function affiche_pass($nom,$valeur){
	//$c_ligne = $nom." : ";
	$c_ligne = "<input type=\"password\" name=\"".$nom."\" size=\"16\" ";
	$c_ligne .= "value=\"".$valeur."\" >";
	$c_ligne .= "<br />\n";
	return $c_ligne;
}
function affiche_ligne_courte($nom,$valeur){
	$c_ligne = "<input type=\"text\" id=\"".$nom."\" name=\"".$nom."\" size=\"8\" ";
	$c_ligne .= "value=\"".$valeur."\" >";
	$c_ligne .= "<br />\n";
	return $c_ligne;
}

function affiche_champs($nom, $valeur,$large=false, $haut=false){
	if(!$large){$large=64;}
	if(!($haut)){$haut=8;}
	//$c_champs = $nom." : ";
	$c_champs = "<textarea name=\"".$nom."\" cols=\"".$large."\" rows=\"".$haut."\">";
	$c_champs .= htmlentities($valeur);
	$c_champs .= "</textarea><br />\n";
	return $c_champs;
}

function affiche_date($page,$nom,$valeur) {
	//$valeur = ltrim($valeur);
	$date =  substr($valeur, 0,10);
	$arr_date = explode("-",$date);
	$a=$arr_date[0];
	$m=$arr_date[1];
	$j=$arr_date[2];

	//$c_date = $valeur."***".$a."-".$m."-".$j."---";
	$c_date="";
	$c_date .= $nom." : ";
	$c_date .= selecteur_date($page, $nom, $a , $m, $j);
	return $c_date;
}

function affiche_date_heure($page,$nom,$valeur) {
	//$valeur = ltrim($valeur);
	$date =  substr($valeur, 0, 10);
	$adate = explode("-", $date);
	//echo "<h2>date : ".$date."</h2>";
	$heure = substr($valeur, 10);
	//echo "<h2>heure : ".$heure."</h2>";
	$aheure = explode(":",$heure);
	$c_date = $nom." : ";
	$c_date .= selecteur_date($page, $nom, $adate[0], $adate[1], $adate[2]);
	$c_date .= selecteur_heure($page, $nom, $aheure[0], $aheure[1], $aheure[2]);
	return $c_date;
}

function donne_clef($table, $conn){
	$sql = "SHOW COLUMNS FROM ".$table;
	$res = mysql_query($sql,$conn);
	$arres = mysql_fetch_array($res);
	return $arres[0];
	mysql_free_result($res);
}

function liste_colonnes($table){
	$sql = "SHOW columns FROM ".$table;
	$result = mysql_query($sql);
	//analyser les r�sultats et ne garder que les noms et types des colonnes;
	$ncol=0;
	while ($row = mysql_fetch_row($result)) {
		// foreach($row as $key => $val){
		// echo "clef : ".$key." valeur : ".$val."<br />";
		// }
		$cols[$ncol]['nom']=$row[0];
		$cols[$ncol]['type']=substr($row[1], 0, strpos($row[1],' ')?strpos($row[1],' '):strlen($row[1]));
		$ncol++;
	}
	mysql_free_result($result);
	return $cols;
}
//selecteur g�n�rique;

function liste_table($table, $col_affs, $col_vals, $conn, $order){
	$sql = "SELECT ".$col_vals.",".$col_affs." FROM ".$table;
	if($order){
		$sql .= " ORDER BY ".$order;
	}
	if ($conn){
		$res = mysql_query($sql,$conn);
		//echo $sql;
		$n=0;
		if (!empty($res)){
			while($lres = mysql_fetch_array($res)){
				$caff ="";
				$naff=0;
				$l_aff=explode(",",$col_affs);
				//$caff .= implode("|",$l_aff).count($l_aff);
				while($naff< count($l_aff) ){
					$caff .= $lres[$l_aff[$naff]];
					//					$caff .= $naff." ";
					$naff++;
					$caff .= ($naff<count($l_aff))?" ":"";
				}
				if(strlen($caff)){
					$liste[$n]['aff'] = $caff;
					$liste[$n]['val']=$lres[$col_vals];
					$n++;
				}
			}
			return $liste;
		}
	}
}

function liste_modules($table, $col_affs, $col_vals, $conn, $order, $semestre){
	$req = "SELECT debut,fin FROM periodes WHERE id='$semestre'";
	$res = mysql_query($req);
	$periode = mysql_fetch_array($res);
	$sql = "SELECT ".$col_vals.",".$col_affs.",code FROM ".$table;
	$sql .= " WHERE (desuetude='0000-00-00' OR (desuetude>'".$periode["debut"]."'))";
	if($order){
		$sql .= " ORDER BY ".$order;
	}
	if ($conn){
		$res = mysql_query($sql,$conn);
		//echo $sql;
		$n=0;
		if (!empty($res)){
			while($l = mysql_fetch_array($res)){
				$liste[$n]['aff']=$l["code"]." / ".$l[$col_affs];
				$liste[$n]['val']=$l[$col_vals];
				$n++;
			}
			return $liste;
		}
	}
}

function selecteur_objets($page, $table, $col_affs, $col_vals, $conn, $coche, $liste, $nouveau){
	$c_sel = "<select class=\"select_".$table."\" id=\"".$col_vals."\" name=\"".$col_vals."\" ";
	if(strlen($page)){
		$c_sel .= "onchange = \"javascript:document.formulaire.action='".$page."';document.formulaire.submit()\"";
	}
	$c_sel .= ">\n";
	if (!is_array($liste)){
		$c_sel .= affiche_options(liste_table($table, $col_affs, $col_vals, $conn,$order),$coche,$nouveau, $order);
	}
	else
	{
		$c_sel .= affiche_options($liste,$coche, $nouveau);
	}
	$c_sel .= "\t</select>\n";
	return $c_sel;
}
function selecteurObjets($page, $table,$nom, $col_affs, $col_vals, $conn, $coche, $liste, $nouveau, $order){
	$c_sel = "<select class=\"select_".$table."\" id=\"$nom\" name=\"$nom\" ";
	if(strlen($page)){
		$c_sel .= "onchange = \"javascript:document.getElementById('formulaire').action='".$page."';document.getElementById('formulaire').submit()\"";
	}
	$c_sel .= ">\n";
	if (!is_array($liste)){
		$c_sel .= affiche_options(liste_table($table, $col_affs, $col_vals, $conn,$order),$coche,$nouveau);
	}
	else
	{
		$c_sel .= affiche_options($liste,$coche, $nouveau);
	}
	$c_sel .= "\t</select>\n";
	return $c_sel;
}
function selecteurModules($page, $table,$nom, $col_affs, $col_vals, $conn, $coche, $liste, $nouveau, $order, $semestre){
	$c_sel = "<select class=\"select_".$table."\" name=\"".$nom."\" ";
	if(strlen($page)){
		$c_sel .= "onchange = \"javascript:document.getElementById('formulaire').action='".$page."';document.getElementById('formulaire').submit()\"";
	}
	$c_sel .= ">\n";
	if (!is_array($liste)){
		$c_sel .= affiche_options(liste_modules($table, $col_affs, $col_vals, $conn,$order,$semestre),$coche,$nouveau);
	}
	else
	{
		$c_sel .= affiche_options($liste,$coche, $nouveau);
	}
	$c_sel .= "\t</select>\n";
	return $c_sel;
}

//fonctions pour les listes de dates;
function liste_annees($debut, $nombre){
	for($a = 0; $a < $nombre; $a++){
		$liste[$a]['aff']= "".$debut+$a;
		$liste[$a]['val']= "".$debut+$a;
	}
	return $liste;
}

function liste_mois($nombre=12){
	setlocale(LC_TIME, "fr");
	$date0 = 1;
	for($m = 0; $m < $nombre; $m++){
		$liste[$m]['aff']= htmlentities(strftime("%B",mktime(0,0,0,$date0+$m,1,2000)));
		$liste[$m]['val']= a_n($date0+$m,2);
	}
	return $liste;
}

function liste_jours($mois, $annee){
	if(!$annee){$annee=date("Y");}
	if(!$mois){$mois = date("m");}
	$j=0;
	$time0 = mktime(0,0,0,$mois,1,$annee);
	$daytime = $time0;
	setlocale(LC_TIME, "fr");
	while(date("m",$daytime) == $mois){
		$liste[$j]['aff']= strftime("%A %d",$daytime);
		$liste[$j]['val']= a_n($j+1,2);
		$j++;
		$daytime = $time0+$j*24*60*60;
	}
	return $liste;
}

function liste_numero($deb, $nombre, $pas, $avt, $apres){
	if(!$deb){$deb=1;}
	if(!$nombre){$nombre=1;}
	if(!$pas){$pas=1;}
	if(!$avt){$avt="";}
	if(!$apres){$apres="";}
	
	for($iter = 0; $iter <=$nombre; $iter++){
		$num = $deb+($pas*$iter);
		$liste[$iter]['aff']=$avt.$num.$apres;
		$liste[$iter]['val']= a_n($num,2);
	}
	return $liste;
}

function selecteur_date($page, $nom_gen, $annee , $mois, $jour){
	//$mois=$mois?$mois:date("m");
	//$annee=$annee?$annee:date("Y");
	
	$c_sel .= selecteur_objets($page, "dates", "", "jour_".$nom_gen, 0, $jour, liste_jours($mois, $annee),0);
	$c_sel .= selecteur_objets($page, "dates", "", "mois_".$nom_gen, 0, $mois, liste_mois(12),0);
	$c_sel .= selecteur_objets($page, "dates", "", "annee_".$nom_gen, 0, $annee, liste_annees(date("Y"),6),0);
	$c_sel .= "\n";

	return $c_sel;
}
function selecteurDate($page, $nom_gen, $mois , $annee, $jour){
	//$mois=$mois?$mois:date("m");
	//$annee=$annee?$annee:date("Y");
	
	$c_sel .= selecteur_objets($page, "dates", "", $nom_gen."[jour]", 0, $jour, liste_jours($mois, $annee),0);
	$c_sel .= selecteur_objets($page, "dates", "", $nom_gen."[mois]", 0, $mois, liste_mois(12),0);
	$c_sel .= selecteur_objets($page, "dates", "", $nom_gen."[annee]", 0, $annee, liste_annees(date("Y")-2,6),0);
	$c_sel .= "\n";

	return $c_sel;
}
function selecteur_heure($page, $nom_gen, $h , $m, $s){
	$h=$h?$h:date("H");
	$m=$m?$m:date("i");

	$c_sel .= selecteur_objets($page, "heures", "", "heures_".$nom_gen, 0, $h, liste_numero(0,23,1,""," h."),0);
	$c_sel .= selecteur_objets($page, "heures", "", "minutes_".$nom_gen, 0, $m, liste_numero(0,11,5,""," min."),0);
	//$c_sel .= selecteur_objets($page, "heures", "", "secondes_".$nom_gen, 0, $s, liste_numero(0,60,20,""," sec."),0);
	$c_sel .= "\n";
	return $c_sel;
}
function selecteurHeure($page, $nom_gen, $h , $m, $s){
	$h=$h?$h:date("H");
	$m=$m?$m:date("i");

	$c_sel .= selecteur_objets($page, "heures", "", $nom_gen."[heure]", 0, $h, liste_numero(8,12,1,""," h."),0);
	$c_sel .= selecteur_objets($page, "heures", "", $nom_gen."[minutes]", 0, $m, liste_numero(0,11,5,""," min."),0);
	//$c_sel .= selecteur_objets($page, "heures", "", "secondes_".$nom_gen, 0, $s, liste_numero(0,60,20,""," sec."),0);
	$c_sel .= "\n";
	return $c_sel;
}
function a_n($chose, $n){
	$c = "".$chose."";
	while(strlen($c)<$n){
		$c = "0".$c;
	}
	return $c;
}
function en_humain($valeur){
	//attend une var de type datetime séparée par des tirets(2000-01-01 00:01:01)comme celle de mysql;
	$date =  substr($valeur, 0, 10);
	$adate = explode("-", $date);
	//echo "<h2>date : ".$date."</h2>";
	$heure = substr($valeur, 10);
	//echo "<h2>heure : ".$heure."</h2>";
	$aheure = explode(":",$heure);
	$t = mktime($aheure[0], $aheure[1] , $aheure[2],$adate[1] , $adate[2] , $adate[0] , 0 );
	setlocale(LC_TIME, "fr");
	$cdate_humain = strftime("%A %d %B %G %Hh%M",$t);
	return $cdate_humain;
}

function htmlspecialchars_decode_php4($str, $quote_style = ENT_COMPAT) {
	return strtr($str, array_flip(get_html_translation_table(HTML_SPECIALCHARS, $quote_style)));
}

function unhtmlentities($string)
{
	// replace numeric entities
	$string = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
	$string = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
	// replace literal entities
	$trans_tbl = get_html_translation_table(HTML_ENTITIES);
	$trans_tbl = array_flip($trans_tbl);
	return strtr($string, $trans_tbl);
}
function dateBaseVersTime($date){
	//passe d'une date mysql (2000-01-02) à une date fr (02-01-2000) pour le 2 janvier 2000; 
	$explDate = explode("-",$date);
	$reorder = $explDate[1]."/".$explDate[2]."/".$explDate[0];
	return strtotime($reorder);
}
function make_seed()
	{
		list($usec, $sec) = explode(' ', microtime());
		return (float) $sec + ((float) $usec * 100000);
	}
function generatePassword ($length = 8)
{

	// start with a blank password
	$password = "";

	// define possible characters
	$possible = "123456789bcdfghjkmnpqrstvwxyz";
	$v = "aeiou" ;
	$c = "bcdfghjkmnpqrstvwxyz";
	$alterv = "@&!#%:1346";
	$alterc = "()=$[].891234567";
	$num = "123456789";
	$spe = "!@#$%&*()-+";
	// set up a counter
	$i = 0;
	$chs = Array("cvcvcvcv", "cvvcvvcv","cvcvvcvv","cvvcvcvv");
	mt_srand(make_seed());
	$pch = $chs[mt_rand(0,count($chs)-1)];
	//echo $pch;
	// add random characters to $password until $length is reached
	while ($i < strlen($pch)) {

		$var = substr($pch,$i,1);
		//echo $var." : ".$i."<br />\n";
		if (mt_rand(0,100)<95){
				
			$pioche = $$var;
		}else{
			$var ="alter".$var;
			$pioche = $$var;
		}
		//echo $var." : ".$$var."<br />\n";
		// pick a random character from the possible ones
		$char = substr($pioche, mt_rand(0, strlen($pioche)-1), 1);

		// we don't want this character if it's already in the password
		if (!strstr($password, $char)) {
			$password .= $char;
			$i++;
		}

	}

	// done!
	return $password;

}
function liste_etudiants($sauf=array(), $conn, $perid,$ecole=0,$all=false,$semestres=false,$group=true){
	$req = "SELECT etudiants.*, niveaux.niveau, niveaux.cycle as cycle, niveaux.id as id_niveau, cycles.id as cycle_id, cycles.ecole as ecole FROM etudiants, niveaux, cycles WHERE ";
	$req .="niveaux.periode='".$perid."' AND niveaux.niveau>0";
	$req .=" AND niveaux.niveau <11 AND etudiants.id =niveaux.etudiant AND niveaux.cycle=cycles.id ";
	if ($ecole && !$all)
	{
		$req .= "AND cycles.ecole= $ecole ";
	}
	if (!is_array($sauf))
	{
		
	}else{
		foreach($sauf as $et){
			$req .="AND etudiants.id!='$et' ";
		}
	}
	if($group)$req .= " ORDER BY niveaux.niveau, etudiants.nom;";
	else $req .= " ORDER BY etudiants.nom;";
	//echo $req;
	$c_select = "";
	//$c_select .= $req;
	$res = mysql_query($req, $conn);
	
	//$c_select .= mysql_error();
	$n=0;
	$label_sem=0;
	//$c_select="";
	if($semestres)
	{
		$c_select .= "<optgroup label=\"Semestres\">";
		for($i=1;$i<=10;$i++)
		{
			$c_select .= "\t\t<option value=\"s$i\">Étudiants en semestre $i</option>\n";
		}
		$c_select .= "</optgroup>";
	}
	while($etu = mysql_fetch_array($res))
	{
		//echo $resteModule['code']."\n";
		if ($label_sem != $etu['niveau'] && $group)
		{
			if($label_sem!=0){$c_select .= "\t<\optgroup>";}
			$c_select .="\t<optgroup label='semestre ".$etu['niveau']."'>";
			$label_sem=$etu['niveau'];
		}
		$c_select .= "\t\t<option value=\"".$etu["id"]."\"";
		$c_select .= ">".utf8_encode($etu["nom"])." ".utf8_encode($etu['prenom'])."</option> <br />\n";
	}
	if($group)$c_select .= "\t<\optgroup>";
	return $c_select;
}
function clean_chaine($ch){
	$from = array(utf8_encode("é"),utf8_encode("ë"),utf8_encode("ï"));
		$to = array("e","e","i");
	$c =  strtolower(str_replace($from,$to,$ch));
	$elim = array("'"," ");
	$c = str_replace($elim,'',$c);
	return $c;
}
function selecteur_semestres($conn,$coche,$nom,$page){
	$req = "SELECT periodes.* FROM periodes, activites WHERE activites.nom='semestre'";
	$req .= " AND periodes.activite=activites.id ORDER BY periodes.annee DESC;";
	$res = mysql_query($req);
	$n=0;
	while($sem = mysql_fetch_array($res)){
		$liste[$n]['aff'] = $sem['annee'].", ".$sem['nom'];
				$liste[$n]['val']=$sem['id'];
				$n++;
	}
	//return implode(";",$liste);
	$sel = selecteurObjets($page,0,$nom,0,0,0,$coche,$liste,0,0);
	return $sel;
}
function selecteur_site($conn,$coche,$nom,$page){
	$req = "SELECT ecoles.* FROM ecoles;";
	$res = mysql_query($req);
	$n=0;
	while($ecole = mysql_fetch_array($res)){
		$liste[$n]['aff'] = $ecole['nom'];
				$liste[$n]['val']=$ecole['id'];
				$n++;
	}
	//return implode(";",$liste);
	$sel = selecteurObjets($page,0,$nom,0,0,0,$coche,$liste,0,0);
	return $sel;
}
function selecteur_cycle($conn,$coche,$nom,$page,$ecole){
	if ($ecole>0){
		$req = "SELECT cycles.* FROM cycles where ecole=".$ecole.";";
	}else{
		$req = "SELECT cycles.* FROM cycles WHERE 1";
	}
	$res = mysql_query($req);
	$n=0;
	while($cycle = mysql_fetch_array($res)){
		$liste[$n]['aff'] = $cycle['nom'];
				$liste[$n]['val']=$cycle['id'];
				$n++;
	}
	//return implode(";",$liste);
	$sel = selecteurObjets($page,0,$nom,0,0,0,$coche,$liste,0,0);
	return $sel;
}
function re_root($dest){
	header("Location: ".$dest);
}
?>
