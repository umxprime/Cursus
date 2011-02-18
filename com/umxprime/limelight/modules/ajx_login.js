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

function init()
{
	AJX.newRequest("init");
	AJX.setAction("init", "init", "message:Test d'initialisation");
	//AJX.debug("init");
	AJX.send("init");
	AJX.newRequest("connexion");
	addListener(gEBI("username"),"keypress",toucheClavier);
	addListener(gEBI("password"),"keypress",toucheClavier);
}

addListener(window,"load",init);

function toucheClavier(e)
{
	if(e.keyCode==13)
	{
		connexion();
	}
}

function connexion()
{
	if(!checkLogin())return
	AJX.setAction("connexion", "connexion", "username:"+gVBI("username")+",password:"+gVBI("password")+",semestre_courant:"+gVBI("semestre_courant"));
	//AJX.debug("connexion");
	AJX.send("connexion");
}

function checkLogin()
{
	if(gVBI("username").length==0)
	{
		alert("Veuillez entrer un identifiant");
		gEBI("username").focus();
		return false;
	}
	if(gVBI("password").length==0)
	{
		alert("Veuillez entrer un mot de passe");
		gEBI("password").focus();
		return false;
	}
	return true;
}