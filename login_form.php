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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>Cursus <?php revision();?> / Identification</title>
		<link rel="stylesheet" href="cursusn.css" type="text/css" />
		<style type="text/css">
			.IdNom {
				font: 13px Georgia, serif;
				position: relative;
				top: 15px;
			}
			.IdNom li {
				display: inline;
				/*height: 21px;*/
				padding: 20px 10px 9px 10px;
			}
			.IdNom li.titre {
				text-transform: uppercase;
				position: relative;
				font-size: 10px;
				top: -6px;
				padding: 18px 10px 16px 13px;
				color: inherit;
			}
			.IdNom li.bouton {
				text-transform: uppercase;
				position: relative;
				font-size: 10px;
				top: -2px;
				padding: 18px 10px 12px 13px;
				color: #aaa;
				/*background-color: red;*/
			}
			.IdNom li.titreReponse {
			font: 13px Georgia, "Trebuchet MS", Verdana, sans-serif;
			text-transform: capitalize;
			background-color: #fff;
			color: #60acbf;
			margin: 0px 10px 0px -3px;
			border-right: 1px dotted #ddd;
			border-left: 1px dotted #ddd;
			border-top: 1px dotted #ddd;
			}
			#identifiant {
			position: relative;
			left: 0px;
			padding-left: 10px;
			width: 1002px;
			/*background-color: #fff;*/
			background: url(img/fond-cursus.gif) no-repeat;
			background-position-x:10px;
			color: inherit;
			/*height: 35px;*/
			height: 100%;
			padding-top: 232px;
			padding-bottom: 20px;
			border-bottom: 1px dotted #ddd;
			}
			.traitOmbre {
				/*padding-bottom: 20px;*/
				/*border-bottom: 1px dotted #ddd;*/
				padding-left: 10px;
				margin: 0 11px 0 11px;
				background: url(img/ombre.png) repeat-x;
				padding-top: 35px;
			}
			#logo {
				position: relative;
				width: 180px;
				height: 240px;
				left: 50%;
				margin-left: -90px;
				background: url(img/logo15.png);
			}
			#identifiant input {
				/*height: 20px;*/
				font-size: 25px;
				font-family: Georgia;
				color: #bbb;
				/*color: #60acbf;*/
				background-color: #fff;
				border: 1px solid #ddd;
			}
		</style>
	</head>
	<body>
		<div id="global">
			<div id="identifiant">
				<form method="post" enctype="multipart/form-data" action="<?php $_SERVER["PHP_SELF"]; ?>">
					<ul class="IdNom">
						<li class="titre">iDentifiant</li>
						<li class="titreReponse"><input type="text" name="username" size="10" /></li>
						<li class="titre">Mot de passe</li>
						<li class="titreReponse"><input type="password" name="password" size="10" /></li>
						<li class="bouton"><input type="submit" value="Login" name="action" /></li>
					</ul>
				</form>
			</div>
			<div class="traitOmbre">
			</div>
		</div>
	</body>
</html>