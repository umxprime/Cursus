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
$r = (strlen($n)>=1)?$n:"-";
//stupid comment;
return utf8_encode($r);
}

function valide_eval($n1, $n2, $creds)
{//fonction qui g�re la validation ou non d'un module, renvoi une chaine correspondant � un style
//mise � 0 des cr�dits valid�s
$cval=0;
if (verif($n1)=="-"){
$classe = "noneval";
}else if(strpos("_ABCDabcd",verif($n1)) or strpos("_ABCDabcd",verif($n2))){
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
function credits_tutorat($n){
if($n>8){
$plus = 6;
}else{
$plus = floor(($n-1)/2+1);
}
//echo "ctrl-n:".$n."-plus:".$plus."<br />\n";
return $plus;
}
?>
