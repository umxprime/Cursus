/**
 * Copyright © 2010 Maxime CHAPELET (umxprime@umxprime.com)
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
	ajx_module = "edition_modules";
	addListener(ajx_get_id("module"),"change",change_module,true);
}

function from_list_to_field(liste,dest)
{
	var selected = liste.selectedIndex;
	var prlist = dest.value;
	var add = liste.options[selected].text;
	if(prlist.indexOf(add)==-1)
	{
		if (prlist.length>0)dest.value+=", ";
		dest.value+=add;
	}
}

function ajout_pre_requis()
{
	var dest = ajx_get_id("pre_requis");
	var liste = ajx_get_id("ajout_pre_requis");
	from_list_to_field(liste, dest);
}

function ajout_prof()
{
	var dest = ajx_get_id("enseignants");
	var liste = ajx_get_id("ajout_prof");
	from_list_to_field(liste, dest);
}

function submit()
{
	var intitule = ajx_instore(ajx_get_value("intitule"));
	var description = ajx_instore(ajx_get_value("description"));
	var params = "id:"+ajx_get_value("id");
	var i=1;
	var ecoles = Array();
	while(ajx_get_id("ecole_"+i))
	{
		if(ajx_get_id("ecole_"+i).checked) ecoles.push(i);
		i++;
	}
	ecoles = "-"+ecoles.join("--")+"-";
	params += ",periode:"+ajx_get_value("semestre_courant");
	params += ",intitule:"+intitule;
	params += ",description:"+description;
	params += ",code:"+ajx_get_value("code");
	params += ",ecole:"+ecoles;
	params += ",jour:"+ajx_get_value("jour");
	params += ",debut:"+ajx_get_value("debut");
	params += ",fin:"+ajx_get_value("fin");
	params += ",seances:"+ajx_get_value("seances");
	params += ",credits:"+ajx_get_value("credits");
	params += ",obligatoire:"+ajx_get_value("obligatoire");
	params += ",desuetude:"+ajx_get_value("desuetude");
	params += ",pre_requis:"+ajx_instore(ajx_get_value("pre_requis"));
	params += ",enseignants:"+ajx_instore(ajx_get_value("enseignants"));
	params += ",evaluation:"+ajx_instore(ajx_get_value("evaluation"));
	ajx(ajx_module,"submit",params,AJX_ASYNC,0);
}

function inscrire_session()
{
	var periode = ajx_get_value("periode");
	var id = ajx_get_value("id");
	var semestre_courant = ajx_get_value("semestre_courant");
	var params = "id:"+id;
	params += ",periode:"+periode;
	params += ",semestre_courant:"+semestre_courant;
	ajx(ajx_module,"inscrire_session",params,AJX_ASYNC,0);
}

function supprimer_session(session)
{
	var semestre_courant = ajx_get_value("semestre_courant");
	var id = ajx_get_value("id");
	var params = "id:"+id;
	params += ",session:"+session;
	params += ",semestre_courant:"+semestre_courant;
	if(window.confirm("Êtes vous certain de vouloir retirer le module à cette période ?"))ajx(ajx_module,"supprimer_session",params,AJX_ASYNC,0);
}

function change_module()
{
	var id = ajx_get_value("module");
	var semestre_courant = ajx_get_value("semestre_courant");
	window.location = ajx_module+".php?id="+id+"&nPeriode="+semestre_courant;
}
 
addListener(window,"load",init,true);