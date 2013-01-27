<?php
class operable {
	private $value;

	public function __construct($value) {
		$this->value = $value;
	}
	
	public function __operators($opcode, $side, $zval = null) {
		switch($opcode) {
			case OPERATORS_ADD: return $this->value + $zval; break;
			
			default: 
				throw new Exception("Unsupported operator");
		}
	}
}

$m = new operable(5);

$a = 5;

$b = $m + $a;
$c = $m + $a;

var_dump($b, $c, $m);
?>
