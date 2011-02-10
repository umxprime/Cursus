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
require 'connect_info.php';
require 'connexion.php';
include 'fonctions.php';
include 'inc_sem_courant.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Cursus <?php revision();?> / Identification</title>
		<!-- <link rel="stylesheet" href="cursusn.css" type="text/css" /> -->
		<style type="text/css">
			@import url("css/cursus.css");
			@import url("css/login.css");
		</style>
		<?php
		$_LIMELIGHT_PATH = "com/umxprime/limelight/";
		include_once $_LIMELIGHT_PATH."core/limelight.php";
		?>
	</head>
	<body>
		<div id="global">
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
			<div id="contenu">
				<div id="login">
					<ul>
						<li class="label">Identifiant</li>
						<li class="champs"><input type="text" id="username" size="10" /></li>
						<li class="label">Mot de passe</li>
						<li class="champs"><input type="password" id="password" size="10" /></li>
						<li class="bouton"><a href="javascript:connexion();">Connexion</a></li>
						<li><span id="ajxLoader"></span></li>
					</ul>
				</div>
				<div class="traitOmbre"></div>
			</div>
		</div>
	</body>
</html>