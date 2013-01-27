<?php
class operable {
	private $value;

	public function __construct($value) {
		$this->value = $value;
	}
	
	public function __operators($opcode, $zval = null) {
		switch($opcode) {

			case OPERATORS_ADD: return $this->value + $zval; break;
			case OPERATORS_SUB: return $this->value - $zval; break;
			case OPERATORS_MUL: return $this->value * $zval; break;
			case OPERATORS_DIV: return $this->value / $zval; break;
			case OPERATORS_SL: return $this->value << $zval; break;
			case OPERATORS_SR: return $this->value >> $zval; break;
			case OPERATORS_CONCAT: return $this->value . $zval; break;
			case OPERATORS_BW_OR: return $this->value || $zval; break;
			case OPERATORS_BW_AND: return $this->value & $zval; break;
			case OPERATORS_BW_XOR: return $this->value | $zval; break;

			default: 
				throw new Exception("Unsupported operator");
		}
	}
}

$m = new operable(5);

$a = 5;

$b = $m + $a;

$c = $m / $a;

$d = $b * $m;


var_dump($b, $c, $d, $m);
?>
