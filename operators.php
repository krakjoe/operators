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
			case OPERATORS_BW_OR: return $this->value | $zval; break;
			case OPERATORS_BW_AND: return $this->value & $zval; break;
			case OPERATORS_BW_XOR: return $this->value ^ $zval; break;

			case OPERATOR_IDENTICAL: return $this->value === $zval; break;
			case OPERATOR_NOT_IDENTICAL: return $this->value !== $zval; break;
			case OPERATOR_IS_EQUAL: return $this->value == $zval; break;
			case OPERATOR_IS_NOT_EQUAL: return $this->value != $zval; break;
	
			/** assign should return zero at the moment */
			case OPERATOR_ASSIGN_ADD:
				$this->value += $zval;
				return 0;
			case OPERATOR_ASSIGN_SUB:
				$this->value -= $zval;
				return 0;
			case OPERATOR_ASSIGN_DIV:
				$this->value /= $zval;
				return 0;
			case OPERATOR_ASSIGN_MOD:
				$this->value %= $zval;
				return 0;
			case OPERATOR_ASSIGN_SL:
				$this->value <<= $zval;
				return 0;
			case OPERATOR_ASSIGN_SR:
				$this->value >>= $zval;
				return 0;
			case OPERATOR_ASSIGN_CONCAT:
				$this->value .= $zval;
				return 0;
			case OPERATOR_ASSIGN_BW_OR:
				$this->value |= $zval;
				return 0;
			case OPERATOR_ASSIGN_BW_AND:
				$this->value &= $zval;
				return 0;
			case OPERATOR_ASSIGN_BW_XOR:
				$this->value ^= $zval;
				return 0;

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


$d += $a;

$d |= $a;

$d <<= $b;

$d++;

var_dump($b, $c, $d, $m);
?>
