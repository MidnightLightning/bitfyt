<?php
class RandomSourceTest extends \PHPUnit_Framework_TestCase {

	function testBlockRange() {
		$n = new BitWarrior\RandomSource('','', 2);
		for ($i=0; $i<100000; $i++) {
			$t = $n->get();
			$this->assertGreaterThanOrEqual(0, $t);
			$this->assertLessThanOrEqual(255, $t);
		}
	}
}