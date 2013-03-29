<?php
/**
 * A source of random numbers, that come from a deterministic source
 *
 * Given an input string/number/foo as seed, using SHA-512, a 512-bit number is created
 * Used in 32-bit chunks, that number can provide 16 random numbers between 0 and 4,294,967,295
 * If more than 16 numbers are required, the original SHA-512 output is hashed again, and a fresh
 * batch of 16 random numbers is generated.
 */
class RandomSource {
	function __construct($seed) {
		$this->raw = $seed;
		$this->_init();
	}
	
	function get() {
		$rs = $this->pieces[$this->cursor];
		$this->cursor++;
		if ($this->cursor > count($this->pieces)) $this->_init();
		return $rs;
	}
	
	private function _init() {
		$this->raw = hash('sha512', $this->raw);
		$this->explode();
		$this->cursor = 0;
	}
	
	/**
	 * Split the raw hash into usable chunks of 32-bit numbers.
	 */
	function explode() {
		$pieces = str_split($this->raw, 8); // 32-bits is 4 bytes, which is 8 hexits
		$final = array();
		foreach($pieces as $piece) {
			$final[] = hexdec($piece);
		}
		$this->pieces = $pieces;
	}
}