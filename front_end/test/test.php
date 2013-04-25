<?php
require(__DIR__.'/../vendor/autoload.php'); // Composer autoload

$n = new BitFyt\RandomSource('','', 2);

$min = 9999999999999999999999;
$max = 0;
$distribution = array();
for ($i=0; $i<100000; $i++) {
	$t = $n->getRange(1,100);
	$min = min($t, $min);
	$max = max($t, $max);
	//$key = intval($t);
	$key = strval($t);
	if (!isset($distribution[$key])) {
		$distribution[$key] = 1;
	} else {
		$distribution[$key]++;
	}
	echo "$i: $t\n";
}

echo "MAX: $max, MIN: $min\n";
ksort($distribution);
echo count($distribution)." total output values\n";
print_r($distribution);

