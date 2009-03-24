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
$requete= "select * from modules where pre_requis like 'Module obligatoire du semestre 1'";

?>
<html>
<head></head>
<body>
<form method="post" action="ooo_traite.php">
<?php 
$resreq = mysql_query($requete);
echo "erreur : ".mysql_error();
$nligne = 1;
while($module = mysql_fetch_array($resreq)){
	$var1 = "intitule_module_".$nligne;
	$var2 = "code_module_".$nligne;
	$$var1 = $module["intitule"];
	$$var2 = $module["code"];
	$nligne++;
	?>
	<input type="hidden" name="<?php echo $var1; ?>" value="<?php echo $$var1; ?>">
	<input type="hidden" name="<?php echo $var2; ?>" value="<?php echo $$var2; ?>">
	<?php
}
mysql_free_result($resreq);
?>
<input type="submit">
</form>