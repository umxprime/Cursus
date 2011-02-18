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
	switch ($action)
	{
		case "submit" :
			$intitule = ajx_restore($params["intitule"]);
			$description = ajx_restore($params["description"]);
			$code = $params["code"];
			$ecole = $params["ecole"];
			$jour = $params["jour"];
			$debut = $params["debut"];
			$fin = $params["fin"];
			$seances = $params["seances"];
			$credits = $params["credits"];
			$obligatoire = $params["obligatoire"];
			$desuetude = $params["desuetude"];
			$pre_requis = ajx_restore($params["pre_requis"]);
			$enseignants = ajx_restore($params["enseignants"]);
			$evaluation = ajx_restore($params["evaluation"]);
			$id = $params["id"];
			
			$fields = Array(	"`intitule`",
								"`description`",
								"`code`",
								"`ecole`",
								"`jour`",
								"`debut`",
								"`fin`",
								"`seances`",
								"`credits`",
								"`obligatoire`",
								"`desuetude`",
								"`pre_requis`",
								"`enseignants`",
								"`evaluation`",
								);
			$values = Array(	"'$intitule'",
								"'$description'",
								"'$code'",
								"'$ecole'",
								"'$jour'",
								"'$debut'",
								"'$fin'",
								"'$seances'",
								"'$credits'",
								"'$obligatoire'",
								"'$desuetude'",
								"'$pre_requis'",
								"'$enseignants'",
								"'$evaluation'",
								);
			
			if($id>0)
			{
				$req = "UPDATE modules SET ";
				$varval = Array();
				for($i=0;$i<count($fields);$i++)
				{
					array_push($varval,$fields[$i]."=".$values[$i]);
				}
				$req .= implode(",",$varval);
				$req .= " WHERE id='$id';";
			} else {
				$req = "INSERT INTO modules (";
				$req .= implode(",",$fields);
				//echo "alert('ok')";break;
				$req .= ") VALUES(";
				$req .= implode(",",$values);
				$req .= ")";
			}
			
			//echo "alert('$req');";break;
			$res = mysql_query($req);
			if($id<0) $id = mysql_insert_id();
			
			$periode = $params["periode"];
			echo "window.location=\"?id=$id&nPeriode=$periode\"";
			break;
		case "inscrire_session" :
			$id = $params["id"];
			$periode = $params["periode"];
			$semestre_courant = $params["semestre_courant"];
			$req = "INSERT INTO session (";
			$req .= "`module`,`periode`";
			$req .= ") VALUES(";
			$req .= "'$id','$periode'";
			$req .= ");";
			$res = mysql_query($req);
			echo "window.location=\"?id=$id&nPeriode=$semestre_courant\"";
			break;
		case "supprimer_session" :
			$id = $params["id"];
			$session = $params["session"];
			$semestre_courant = $params["semestre_courant"];
			$req = "DELETE FROM session WHERE id='$session';";
			$res = mysql_query($req);
			echo "window.location=\"?id=$id&nPeriode=$semestre_courant\"";
			break;
	}
?>