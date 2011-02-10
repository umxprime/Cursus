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

var ajx_module = "edition_unites";

function init()
{
	addListener(ajx_get_id("unites"),"change",consulterUnite,true);
	consulterUnite();
}

function consulterUnite()
{
	var id = ajx_get_value("unites");
	ajx(ajx_module,"consulterUnite","id:"+id,AJX_ASYNC,0);
}

function uniteConsultee(nom)
{
	ajx_get_id("nomUnite").value=nom;
}

function modifierUnite()
{
	
	var id = ajx_get_value("unites");
	var nom = ajx_get_value("nomUnite");
	ajx(ajx_module,"modifierUnite","id:"+id+",nom:"+nom,AJX_ASYNC,0);
}

function uniteModifiee(id,nom)
{
	var select = ajx_get_id("unites");
	for(var i =0; i<select.length;i++)
	{
		var option = select.options[i];
		if(option.value==id)
		{
			select.selectedIndex=option.index;
			option.text=nom;
		};
	}
}

function creerUnite()
{
	var nom = ajx_get_value("nomNouvelleUnite");
	ajx(ajx_module,"creerUnite","nom:"+nom,AJX_ASYNC,0);
}

function uniteCree(id)
{
	window.location=window.location;
}

addListener(window,"load",init,true);