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
 * Cursus uses FPDF released by Olivier PLATHEY
 *
 * Cursus uses the Limelight Framework
 * released under the GPL <http://www.gnu.org/licenses/>
 * by Maxime CHAPELET (umxprime@umxprime.com)
 * 
 **/

require "include/necessaire.php";

//echo $_POST['eval'];
if($_POST['eval']>0){
	$reqavant = "select * from evaluations where id='".$_POST['eval']."';";
	$resavant = mysql_query($reqavant);
	$avant = mysql_fetch_array($resavant);
	$id_eval=$_POST['eval'];
	$req= "UPDATE evaluations SET note_1='".$_POST['note_1']."', ";
	$req .= "appreciation_1='".utf8_decode($_POST['appreciation_1'])."', ";
	$req .= "note_2='".$_POST['note_2']."', ";
	//echo $req;
	if(!empty($_POST['note_1'])){
		if(strpos("__ABCD",$_POST['note_1'])){
			$req .= "valide_1='1' ,";
		}else{
			$req .= "valide_1='0' ,";
		}
	}
	if(!empty($_POST['note_2'])){
		if(strpos("__ABCD",$_POST['note_2'])){
			$req .= "valide_2='1' ,";
		}else{
			$req .= "valide_2='0' ,";
		}
	}
	$req .= "appreciation_2='".utf8_decode($_POST['appreciation_2'])."' ";
	$req .= "WHERE id='".$id_eval."';";
	//echo $req;
	$res = mysql_query($req);
	//echo mysql_error();
	if ($avant['tutorat']!=''){
		$tuteur=$_GET["tuteur"];
		header("Location: edition_tutorats.php?nPeriode=$semestre_courant&tuteur=$tuteur");
	}else{
	header("Location: edition_session.php?session=".$_POST["session"]."&nPeriode=$semestre_courant");
	}
}
?>
