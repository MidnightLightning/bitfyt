<?php
use \BitFyt\RandomSource;

class RandomSourceTest extends \PHPUnit_Framework_TestCase {

	function testBlockRange() {
		$n = new RandomSource('','', 2);
		for ($i=0; $i<1000; $i++) {
			$t = $n->get();
			$this->assertGreaterThanOrEqual(0, $t);
			$this->assertLessThanOrEqual(255, $t);
		}
	}
	
	function testDeterministic() {
		$n1 = new RandomSource('', '');
		$r1 = $n1->get();
		$n2 = new RandomSource('', '');
		$r2 = $n2->get();
		$this->assertEquals($r1, $r2);
	}
	
	function testLarge() {
		$n = new RandomSource('', '', 128); // Largest possible block size
		$r = $n->get();
		$this->assertTrue(is_string($r));
		$this->assertTrue(strlen($r) > 0);
	}
	
	function testRangeOutOfBounds() {
		$n = new RandomSource('', '', 2); // Max output value is 255
		$this->setExpectedException('InvalidArgumentException');
		$r = $n->getRange(0, 256);
	}
	
	function testFractionalOutput() {
		$n = new RandomSource('', '', 2);
		$r = floatval($n->getRange(0.1, 0.9));
		$this->assertGreaterThan(0, $r);
		$this->assertLessThan(1, $r);
	}
}