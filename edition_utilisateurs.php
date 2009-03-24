<?php
    include("lesotho.php");
	include("fonctions.php");
	//on requiert les variables de connexion;
	require("connect_info.php");
	//puis la connexion standard;
	require("connexion.php");
	$outil="utilisateurs";
	include("inc_sem_courant.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<?php 
			include("inc_css_thing.php");
		?>
		<title><?php echo $periode['nom']?></title>
		<script type="text/javascript" src="potajx/potajx.js"></script>
		<script type="text/javascript" src="potajx/modules/ajx_edition_utilisateurs.js"></script>
	</head>
	<body>
		<div id="global">
			<?php include("barre_outils.php"); ?>
			<?php include("inc_nav_sem.php"); ?>
			<input type="hidden" id="semestre_courant" value="<?php echo $semestre_courant;?>"/>
			<table>
				<tr><td>
				<span id="ajx_liste_types">Liste des types d'utilisateurs</span>
				<span id="ajx_liste_utilisateurs">Liste des utilisateurs</span>
				</td></tr>
				<tr><td>
				Nom <span id="ajx_nom">Nom de l'utilisateur</span>
				Prénom <span id="ajx_prenom">Prénom de l'utilisateur</span>
				</td></tr>
				<tr><td>
				Mot de passe <span id="ajx_passw">Mot de passe de l'utilisateur</span>
				</td></tr>
				<tr><td>
				<span id="ajx_liste_ecoles">Ecole de l'utilisateur</span>
				<span id="ajx_liste_semestres">Semestre de l'utilisateur</span>
				<span id="ajx_liste_cycles">Cycle de l'utilisateur</span>
				</td></tr>
			</table>
			<script type="text/javascript">
				init();
			</script>
		</div>
	</body>
</html>