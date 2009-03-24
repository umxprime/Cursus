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
<p class="sous-titre">Attacher une image :</p>
<form method="POST" enctype="multipart/form-data" name="image_upload_form" action="<?php $_SERVER["PHP_SELF"];?>">
<p>Emplacement de l'image &agrave; attacher : <input type="file" name="image_file" size="20"></p>
<p>TITRE : <input type="text" name="image_titre" size="40"></p>
<p><?php echo affiche_champs("image_commentaire", "Commentaire ici.", 50) ?></p>
<p>Credits : <input type="text" name="image_credits" size="60"></p>
<input type="hidden" name="<?php echo $id_clef; ?>" value="<?php echo $$id_clef; ?>">
<p><input type="submit" value="Envoyer Image" name="action"></p>
</form>
