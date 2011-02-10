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
		case "modifierUnite" :
			$nom = utf8_encode($params["nom"]);
			$id = $params["id"];
			$req = "UPDATE unites SET `nom`='$nom' WHERE id=$id;";
			mysql_query($req);
			$nom = utf8_decode($nom);
			echo "uniteModifiee($id,'$nom');";
			break;
		case "creerUnite" :
			$nom = utf8_encode($params["nom"]);
			$req = "INSERT INTO unites (`nom`) VALUES('$nom');";
			mysql_query($req);
			$id=mysql_insert_id();
			echo "uniteCree($id);";
			break;
		case "consulterUnite" :
			$id = $params["id"];
			$req = "SELECT * FROM unites WHERE unites.id='$id';";
			$res = mysql_query($req);
			$unite = mysql_fetch_array($res);
			echo "uniteConsultee('".utf8_decode($unite["nom"])."');";
			break;
	}
?>