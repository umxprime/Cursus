<?php
require_once "../include/classes/BoiteAOutils.php";

$eval = new Evaluation("E","","-","-",3);
echo $eval->estCrediteeDe();
var_dump($eval->couleursPDF());
echo Tutorat::creditsTutorats(5);