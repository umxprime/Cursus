<?php
	//include("lesotho.php");
	require("connect_info.php");
	require("connexion.php");
	include("fonctions.php");
	include("fonctions_eval.php");
	include("inc_sem_courant.php");
	include("regles_utilisateurs.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Insert title here</title>
	</head>
	<body>
		<?php
		$req = "ALTER TABLE  `evaluations` ADD  `niveau` SMALLINT NOT NULL DEFAULT  '0' AFTER  `etudiant` ;";
		$res = mysql_query($req);
		$req = "SELECT niveaux.niveau,evaluations.id ";
		$req .= "FROM niveaux, evaluations, session ";
		$req .= "WHERE ";
		$req .= "evaluations.session=session.id AND ";
		$req .= "session.periode=niveaux.periode AND ";
		$req .= "niveaux.etudiant=evaluations.etudiant AND ";
		$req .= "1; ";
		//$req .= "ORDER BY evaluations.id ASC;";
		$res=mysql_query($req);
		while($eval=mysql_fetch_array($res))
		{
			$req = "UPDATE evaluations SET niveau=".$eval["niveau"]." WHERE id=".$eval["id"].";";
			mysql_query($req);
		}
		echo "done";
		?>
	</body>
</html>