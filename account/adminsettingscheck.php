<?php
//Pobranie danych
if(!isset($_POST['zmienna']) || !isset($_POST['wartosc']) || !isset($_POST['wartoscMin']) || !isset($_POST['wartoscMax'])) {
	echo'Nie przesłano zmiennych "zmienna", "wartosc", "wartoscMin" i "wartoscMax"<br />';
}
else {
	$zmienna = $_POST['zmienna'];
	$wartosc = $_POST['wartosc'];
	$wartoscMin = $_POST['wartoscMin'];
	$wartoscMax = $_POST['wartoscMax'];
	
	//Sprawdzanie danych
	if($wartosc >= $wartoscMin && $wartosc <= $wartoscMax) {
		${$zmienna} = $wartosc;
		echo 'Nowa wartość => '.${$zmienna};
	}
	else {
		echo 'Uwaga, nieprawidłowa wartość => '.$wartosc.' Dopuszczalny zakres wartości od '.$wartoscMin.' do '.$wartoscMax;
	}
}
?>
