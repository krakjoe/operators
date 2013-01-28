<?php
class operable {
	private $value;

	public function __construct($value) {
		$this->value = $value;
	}
	
	public function __operators($opcode, $zval = null) {
		switch($opcode) {
			case OPS_ADD: return new operable($this->value + $zval); break;
			case OPS_SUB: return new operable($this->value - $zval); break;
			case OPS_MUL: return new operable($this->value * $zval); break;
			case OPS_DIV: return new operable($this->value / $zval); break;
			case OPS_SL: return new operable($this->value << $zval); break;
			case OPS_SR: return new operable($this->value >> $zval); break;
			case OPS_CONCAT: return new operable($this->value . $zval); break;
			case OPS_BW_OR: return new operable($this->value | $zval); break;
			case OPS_BW_AND: return new operable($this->value & $zval); break;
			case OPS_BW_XOR: return new operable($this->value ^ $zval); break;

			case OPS_IDENTICAL: return new operable($this->value === $zval); break;
			case OPS_NOT_IDENTICAL: return new operable($this->value !== $zval); break;
			case OPS_IS_EQUAL: return new operable($this->value == $zval); break;
			case OPS_IS_NOT_EQUAL: return new operable($this->value != $zval); break;
	
			/** assign should return zero at the moment */
			case OPS_ASSIGN_ADD:
				if (is_a($zval, __CLASS__))
					$this->value += $zval->value;
				return 0;
			case OPS_ASSIGN_SUB:
				if (is_a($zval, __CLASS__))
					$this->value -= $zval->value;
				return 0;
			case OPS_ASSIGN_DIV:
				if (is_a($zval, __CLASS__))
					$this->value /= $zval->value;
				return 0;
			case OPS_ASSIGN_MOD:
				if (is_a($zval, __CLASS__))
					$this->value %= $zval->value;
				return 0;
			case OPS_ASSIGN_SL:
				if (is_a($zval, __CLASS__))
					$this->value <<= $zval->value;
				return 0;
			case OPS_ASSIGN_SR:
				if (is_a($zval, __CLASS__))
					$this->value >>= $zval->value;
				return 0;
			case OPS_ASSIGN_CONCAT:
				if (is_a($zval, __CLASS__))
					$this->value .= $zval->value;
				return 0;
			case OPS_ASSIGN_BW_OR:
				if (is_a($zval, __CLASS__))
					$this->value |= $zval->value;
				return 0;
			case OPS_ASSIGN_BW_AND:
				if (is_a($zval, __CLASS__))
					$this->value &= $zval->value;
				return 0;
			
			case OPS_ASSIGN_BW_XOR:
				if (is_a($zval, __CLASS__))
					$this->value ^= $zval->value;
				return 0;

			case OPS_PRE_INC:
					++$this->value;
				return 0;

			case OPS_POST_INC:
					$this->value++;
				return 0;

			case OPS_PRE_DEC: 
					--$this->value; 
				return 0;
			
			case OPS_POST_DEC: 
					$this->value--; 
				return 0;

			default: 
				throw new Exception("Unsupported operator {$opcode}");
		}
	}
}

$m = new operable(5);

$a = 5;
$b = new operable(10);
 
$c = $m / $a;

$d = $b * $m;



var_dump($b, $c, $d, $m);
?>
