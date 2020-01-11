<?php

/** 
 * Det här programmet räknar antalet
 * besökare på sidan.
 */

$counter_name = 'counter.txt';

// Om filen inte existerar skapa ny med startvärde 0
if (!file_exists($counter_name)) {
	$f = fopen($counter_name, 'w');
	if (flock($f, LOCK_EX)) {
		fwrite($f, '0');
		flock($f, LOCK_UN);
	}
	fclose($f);
}

// Läs filen
$f = fopen($counter_name, 'r');
	
if (flock($f, LOCK_SH)) {
	$counterVal = fread($f, filesize($counter_name));
flock($f, LOCK_UN);
}
fclose($f);

// Öka värdet i filen med 1
$counterVal++;
$f = fopen($counter_name, 'w');

if (flock($f, LOCK_EX)) {
	fwrite($f, $counterVal);
	flock($f, LOCK_UN);
}
fclose($f);

$html = file_get_contents("home.html");
$html = str_replace('---$hits---', $counterVal, $html);

echo $html;


?>
