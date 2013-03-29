<?php
/**
 * A Fisher-Yates shuffle algorithm
 */
function fy_shuffle($in) {
	$out = array();
	$out[0] = $in[0];
	for($i = 1; $i < count($in); $i++) {
		$j = intval(rand(0, $i));
		if ($j !== $i) $out[$i] = $out[$j];
		$out[$j] = $in[$i];
	}
	return $out;
}