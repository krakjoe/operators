<?php
class operable {
	private $value;

	public function __construct($value) {
		$this->value = $value;
	}
	
	public function __operators($opcode, $zval = null) {
		switch($opcode) {
			case OPERATORS_ADD: return new operable($this->value + $zval); break;
			case OPERATORS_SUB: return new operable($this->value - $zval); break;
			case OPERATORS_MUL: return new operable($this->value * $zval); break;
			case OPERATORS_DIV: return new operable($this->value / $zval); break;
			case OPERATORS_SL: return new operable($this->value << $zval); break;
			case OPERATORS_SR: return new operable($this->value >> $zval); break;
			case OPERATORS_CONCAT: return new operable($this->value . $zval); break;
			case OPERATORS_BW_OR: return new operable($this->value | $zval); break;
			case OPERATORS_BW_AND: return new operable($this->value & $zval); break;
			case OPERATORS_BW_XOR: return new operable($this->value ^ $zval); break;

			case OPERATOR_IDENTICAL: return new operable($this->value === $zval); break;
			case OPERATOR_NOT_IDENTICAL: return new operable($this->value !== $zval); break;
			case OPERATOR_IS_EQUAL: return new operable($this->value == $zval); break;
			case OPERATOR_IS_NOT_EQUAL: return new operable($this->value != $zval); break;
	
			/** assign should return zero at the moment */
			case OPERATOR_ASSIGN_ADD:
				if (is_a($zval, __CLASS__))
					$this->value += $zval->value;
				return 0;
			case OPERATOR_ASSIGN_SUB:
				if (is_a($zval, __CLASS__))
					$this->value -= $zval->value;
				return 0;
			case OPERATOR_ASSIGN_DIV:
				if (is_a($zval, __CLASS__))
					$this->value /= $zval->value;
				return 0;
			case OPERATOR_ASSIGN_MOD:
				if (is_a($zval, __CLASS__))
					$this->value %= $zval->value;
				return 0;
			case OPERATOR_ASSIGN_SL:
				if (is_a($zval, __CLASS__))
					$this->value <<= $zval->value;
				return 0;
			case OPERATOR_ASSIGN_SR:
				if (is_a($zval, __CLASS__))
					$this->value >>= $zval->value;
				return 0;
			case OPERATOR_ASSIGN_CONCAT:
				if (is_a($zval, __CLASS__))
					$this->value .= $zval->value;
				return 0;
			case OPERATOR_ASSIGN_BW_OR:
				if (is_a($zval, __CLASS__))
					$this->value |= $zval->value;
				return 0;
			case OPERATOR_ASSIGN_BW_AND:
				if (is_a($zval, __CLASS__))
					$this->value &= $zval->value;
				return 0;
			
			case OPERATOR_ASSIGN_BW_XOR:
				if (is_a($zval, __CLASS__))
					$this->value ^= $zval->value;
				return 0;

			case OPERATOR_PRE_INC:
					++$this->value;
				return 0;

			case OPERATOR_POST_INC:
					$this->value++;
				return 0;

			case OPERATOR_PRE_DEC: 
					--$this->value; 
				return 0;
			
			case OPERATOR_POST_DEC: 
					$this->value--; 
				return 0;

			default: 
				throw new Exception("Unsupported operator {$opcode}");
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

$m += $a;

var_dump($b, $c, $d, $m);
?>
