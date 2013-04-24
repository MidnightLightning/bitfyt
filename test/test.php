<?php
require(__DIR__.'/../vendor/autoload.php'); // Composer autoload

$n = new BitWarrior\RandomSource('','', 2);

$min = 9999999999999999999999;
$max = 0;
for ($i=0; $i<100000; $i++) {
	$t = $n->get();
	$min = min($t, $min);
	$max = max($t, $max);
	echo "$i: $t\n";
}

echo "MAX: $max, MIN: $min\n";

