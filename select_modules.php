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
	
//on requiert les variables de connexion;
require("connect_info.php");
//puis la connexion standard;
require("connexion.php");
// quel etudiant? : entrer ici avec un select ou une recherche ou valeur du post
// si session pas ouverte : formulaire d'identification -> 
// ouverture de session et formulaire de d�connexion
$sql = "select * from listes_modules where id_etudiant ='".$id_connecte."' and (valide_1 = 1 or valide_2=1)";
$reqEval = mysql_query($sql);
$resEval = mysql_fetch_array($reqEval);
//$listeMods = new array();
while($eval = $resVal[]){
 $req = mysql_query("select code,id from modules where id = ".$eval["module"]);
 $res = mysql_fetch_array($req);
 $listeMods[]=$res[0]["code"];
}
$sql = "select * from modules";
$res = mysql_query($sql);
$modules = mysql_fetch_array($res);
while($module=$modules[]){
	$ok = 1;
	$prereqs = explode(",",$module["pre_requis"]);
	while($prereq=$prereqs[]){
		if (!in_array($prereq, $listeMods)){
			$ok = 0;
		}
	}
	if ($ok){
		$modulesOk[] = $module;
	}
}

//
//recherche du semestre le plus r�cemment acquis de l'�tudiant

//recherche du semestre le plus r�cemment entam� de l'�tudiant

// quel semestre v�rifier les semestres acquis et proposer les possibilit�s
// quels modules obligatoires pour ce semestre (evaluations, tutorat...)
// placer les obligatoires et bloquer les horaires si besoin
// additonner les cr�dits pour limiter

// liste de selects 18 modules avec valeur vide par d�faut 
// et ne proposer que les modules possibles
// il faut savoir si les modules sont valid�s
// pour d�terminer quels modules sont accessibles
// ? possibilit� de voir les possibilit�s offertes en fonction des choix op�r�s ?
// chaque �tudiant doit disposer � l'avance de la visualisation possbile 
// de ses 10 semestres de scolatit�
// d'ou la n�cessit� de pouvoir placer des semstres externes, des modules externes

?>