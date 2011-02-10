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
	
include("lesotho.php");
require("connect_info.php");
require("connexion.php");
if($_POST['id_rdv']>0){
	$id_rdv=$_POST['id_rdv'];
	header("Location:edit_rdv.php?rdv=".$id_rdv);
	$req = "UPDATE rdv SET cr='".utf8_decode($_POST['cr'])."', ";
	$date = $_POST['date'];
	$req .= "date='".$date['annee']."-".$date['mois']."-".$date['jour']." ".implode(":",$_POST['heure']).":00'";
	$req .= " WHERE id='".$id_rdv."';";
	$res = mysql_query($req);
	header("Location:edit_rdv.php?rdv=".$id_rdv);
}
?>