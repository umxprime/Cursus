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
	
function verif($n){
	$r = (strlen($n)>0)?$n:"-";
	return $r;
}

function valide_eval($n1, $n2, $creds)
{//fonction qui g�re la validation ou non d'un module, renvoi une chaine correspondant � un style
//mise � 0 des cr�dits valid�s
	$cval=0;
	if (verif($n1)=="-")
	{
		$classe = "noneval";
	}else if(strpos("_ABCDabcd",verif($n1)) or strpos("_ABCDabcd",verif($n2)))
	{
		$classe = "ok";
		$cval=$creds;
		//$total_acquis += $eval['credits'];
	}else{
		$classe = "pasok";
	}
	$ret=array();
	$ret['classe']=$classe;
	$ret['creds']=$cval;
	return $ret;
}

function validerEvaluationPDF($n1, $n2, $a1, $a2, $cr)
{
	$credits=0;
	$acquis=false;
	if (verif($n1)=="-")
	{
		$credits = "-";
		$couleurTitreFond = "#D8D8D8";
		$couleurTitreTexte = "#404040";
		$couleurECTSTexte = "#404040";
		$couleurECTSFond = "white";
	}else if(strpos("_ABCDabcd",verif($n1)) || strpos("_ABCDabcd",verif($n2)))
	{
		$acquis=true;
		$couleurTitreTexte = "white";
		$couleurTitreFond = "#80B711";
		$couleurECTSTexte = "#80B711";
		$couleurECTSFond = "white";
		$credits=$cr;
	}else{
		$couleurTitreTexte = "white";
		$couleurTitreFond = "#FF8500";
		$couleurECTSTexte = "#FF8500";
		$couleurECTSFond = "white";
	}
	if(strpos("_ABCDabcd",verif($n1)))
	{
		$couleurNote1Texte = "white";
		$couleurNote1Fond = "#80B711";
	} else if (verif($n1)=="-"){
		$couleurNote1Texte = "white";
		$couleurNote1Fond = "#D8D8D8";
	}else{
		$couleurNote1Texte = "white";
		$couleurNote1Fond = "#FF8500";
	}
	if(strpos("_ABCDabcd",verif($n2)))
	{
		$couleurNote2Texte = "white";
		$couleurNote2Fond = "#80B711";
	} else {
		$couleurNote2Texte = "white";
		$couleurNote2Fond = "#FF8500";
	}
	return array("acquis"=>$acquis,"credits"=>$credits,
	"couleurTitreFond"=>$couleurTitreFond,"couleurTitreTexte"=>$couleurTitreTexte,
	"couleurECTSFond"=>$couleurECTSFond,"couleurECTSTexte"=>$couleurECTSTexte,
	"couleurNote1Fond"=>$couleurNote1Fond,"couleurNote1Texte"=>$couleurNote1Texte,
	"couleurNote2Fond"=>$couleurNote2Fond,"couleurNote2Texte"=>$couleurNote2Texte
	);
	/*
	$unRattrapage = false;
	$note1Ok = false;
	$appreciationOk = false;
	if ($evaluation["appreciation_1"]!='') $appreciationOk=true;
	if (verif($evaluation["note_1"])!='-') $noteOk=true;
	if ($noteOk && $evaluation["valide_1"]=='0')
	{
		$unRattrapage = true;
		$noteOk = false;
		$appreciationOk = false;
		if ($evaluation["appreciation_2"]!='') $appreciationOk=true;
		if (verif($evaluation["note_2"])!='-') $noteOk=true;
	}*/
}

function credits_tutorat($n)
{
	if($n>8)
	{
		$plus = 6;
	}else{
		$plus = floor(($n-1)/2+1);
	}
	//echo "ctrl-n:".$n."-plus:".$plus."<br />\n";
	return $plus;
}
?>
