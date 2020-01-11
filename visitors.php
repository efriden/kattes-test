<?php

/** 
 * Det här programmet håller koll på och skriver ut hur
 * många gånger hemsidan har besökts. Besökarens datum,
 * tid, IP-adress och webbläsare loggas i en separat fil.
 */

$counter_name = 'counter.txt';
$visitors_name = 'visitors.txt';
$date = date('Y-m-d, H:i:s');
$visitorIP = $_SERVER['REMOTE_ADDR'];

// Webbklientens webbläsare
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
	$visitorBrowser = 'Internet explorer';
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false) {
	$visitorBrowser = 'Internet explorer';
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Edge') !== false) {
	$visitorBrowser = 'Microsoft Edge';
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== false) {
	$visitorBrowser = 'Mozilla Firefox';
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
	$visitorBrowser = 'Google Chrome';
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false) {
	$visitorBrowser = 'Opera Mini';
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== false) {
	$visitorBrowser = 'Opera';
} elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false) {
	$visitorBrowser = 'Safari';
} else {
	$visitorBrowser = 'Other';
}

$visitorData = $date . ', ' . $visitorBrowser . ', ' . $visitorIP;

// Logga besökare på fil
$f = fopen($visitors_name, 'a+');
	if (flock($f, LOCK_EX)) {
		fwrite($f, $visitorData . "\n");
		flock($f, LOCK_UN);
	}
	fclose($f);

//--------Räkna antalet besökare------------------

// Om filen inte existerar: skapa med startvärde 0
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

// Byt ut värdet i html-sidan
$html = file_get_contents("home.html");
$html = str_replace('---$hits---', $counterVal, $html);

echo $html;

?>
