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

function init()
{
}

addListener(window,"load",init,true);

function desinscrire(eval,etudiant,periode)
{
	ajx("edition_contrats","desinscrire","id:"+eval+",etudiant:"+etudiant+",periode:"+periode,true);
}

function inscrire(etudiant,periode)
{
	var session=ajx_get_value("session");
	ajx("edition_contrats","inscrire","id:"+session+",etudiant:"+etudiant+",periode:"+periode,true);
}