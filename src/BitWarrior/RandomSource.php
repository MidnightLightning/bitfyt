<?php
namespace BitWarrior;

/**
 * A source of random numbers, that come from a deterministic source
 *
 * Given an input string/number/foo as seed, using SHA-512, a 512-bit number is created
 * Used in 32-bit chunks (default), that number can provide 16 random numbers between 0 and 4,294,967,295
 * If more than 16 numbers are required, the original SHA-512 output is hashed again, and a fresh
 * batch of 16 random numbers is generated.
 */
class RandomSource {
	private $key;
	protected $out_size;
	protected $raw;
	protected $cursor;
	
	/**
	 * Set up the generator
	 * @param string $key The secret key for the HMAC function
	 * @param string $seed The seed for the HMAC function
	 * @param int $out The number of hexits for each output. Needs to evenly divide 128 (512 bits is 64 bytes is 128 hexits). Defaults to 8 (32-bit number; 4 bytes)
	 */
	function __construct($key, $seed, $out = 8) {
		$this->key = $key;
		$this->raw = $seed;
		$this->out = $out;
		$this->_init();
	}
	
	/**
	 * Get the next number in the random set
	 *
	 * The range of the output is determined by the output block size (set at construction):
	 *
	 * Out size | Number of outputs per round | Max output value
	 * 128 | 1 | 13.407807e153
	 * 64  | 2 | 115.792089e75
	 * 32  | 4 | 340.282366e36
	 * 16  | 8 | 18,446,744,073,709,551,615
	 * 8   | 16 |             4,294,967,295
	 * 4   | 32 |                    65,535
	 * 2   | 64 |                       255
	 * 1   | 128 |                       15
	 *
	 * @return string Random number, output as a string, because it may be quite large
	 */
	function get() {
		$rs = $this->pieces[$this->cursor++];
		if ($this->cursor >= count($this->pieces)) $this->_init(); // If we've run out of numbers, create some more
		return $rs;
	}
	
	/**
	 * Get a random number within a given range
	 *
	 * Return a number between $min and $max rather than between zero and the max of the current block size
	 * @param int $min
	 * @param int $max
	 * @return string Random number, output as a fraction represented as a string, because it may be quite large
	 */
	function getRange($min, $max) {
		$r = $this->get();
		bcscale(10); // Allow fractions
		$max_value = bcsub(bcpow(2, $this->out_size*4, 0), '1', 0);
		$range = bcsub($max, $min);
		if (bccomp($range, $max_value) == 1) throw new InvalidArgumentException("Can't get a number in the range {$min} to {$max} since that range ({$range}) is greater than the current output range ({$max_value})");
		$frac = bcdiv($r, $max_value);
		return bcadd($min, bcmul($frac, $range));
	}
	
	function peek() {
		return $this->pieces[$this->cursor];
	}
	
	function getState() {
		return array(
			'cursor' => $this->cursor,
			'raw' => $this->raw,
			'pieces' => $this->pieces,
		);
	}
	
	private function _init() {
		$this->raw = hash_hmac('sha512', $this->raw, $this->key);
		$this->cursor = 0;
		
		// Split the hash into usable chunks
		$pieces = str_split($this->raw, $this->out);
		$final = array();
		foreach($pieces as $piece) {
			$final[] = $this->_bigHexDec($piece);
		}
		$this->pieces = $final;
	}
	
	private function _bigHexDec($hex) {
		if ($hex == '') return '0';
		$dec = '0';
		$len = strlen($hex);
		bcscale(0); // dealing with integers only
		for ($i=0; $i<$len; $i++) {
			$hex_value = hexdec($hex[$i]);
			$place_value = bcpow('16', strval($len - $i-1));
			$dec = bcadd($dec, bcmul($hex_value, $place_value));
		}
		return $dec;
	}
}