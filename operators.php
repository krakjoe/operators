<?php
class operable implements Operators {
	private $value;

	public function __construct($value) {
		$this->value = $value;
	}
	
	public function __operators($opcode, $zval = null) {
		switch($opcode) {
			case OPS_ADD:
				if (is_a($zval, __CLASS__)) 
					return new operable($this->value + $zval->value); 
				else return new operable($this->value + $zval);
			break;

			case OPS_SUB: 
				if (is_a($zval, __CLASS__)) 
					return new operable($this->value - $zval->value); 
				else return new operable($this->value - $zval); 
			break;

			case OPS_MUL:  
				if (is_a($zval, __CLASS__))
					return new operable($this->value * $zval->value);
				else return new operable($this->value * $zval);
			break;

			case OPS_DIV: 
				if (is_a($zval, __CLASS__))
					return new operable($this->value / $zval->value);
				else return new operable($this->value / $zval);
			break;
			
			case OPS_SL:
				if (is_a($zval, __CLASS__))   
					return new operable($this->value << $zval->value);
				else return new operable($this->value << $zval);
			break;

			case OPS_SR:
				if (is_a($zval, __CLASS__)) 
					return new operable($this->value >> $zval->value);
				else return new operable($this->value >> $zval);
			break;

			case OPS_BW_OR:
				if (is_a($zval, __CLASS__))
					return new operable($this->value | $zval->value);
				else return new operable($this->value | $zval);
			break;

			case OPS_BW_AND: 
				if (is_a($zval, __CLASS__))
					return new operable($this->value & $zval->value);
				else return new operable($this->value & $zval);
			break;
			
			case OPS_BW_XOR: 
				if (is_a($zval, __CLASS__))
					return new operable($this->value ^ $zval->value);
				else return new operable($this->value ^ $zval);
			break;

			case OPS_IDENTICAL:
				if (is_a($zval, __CLASS__))
					return new operable($this->value === $zval->value);
				else return new operable($this->value === $zval);
			break;
			
			case OPS_NOT_IDENTICAL:
				if (is_a($zval, __CLASS__))
					return new operable($this->value !== $zval->value);
				else return new operable($this->value !== $zval);
			break;
			
			case OPS_IS_EQUAL:
				if (is_a($zval, __CLASS__))
					return new operable($this->value == $zval->value);
				else return new operable($this->value == $zval);
			break;

			case OPS_IS_NOT_EQUAL:
				if (is_a($zval, __CLASS__))
					return new operable($this->value != $zval->value);
				else return new operable($this->value != $zval);
			break;
	
			case OPS_ASSIGN_ADD:
				if (is_a($zval, __CLASS__))
					$this->value += $zval->value;
				else $this->value += $zval;
			break;

			case OPS_ASSIGN_SUB:
				if (is_a($zval, __CLASS__))
					$this->value -= $zval->value;
				else $this->value -= $zval;
			break;
				
			case OPS_ASSIGN_DIV:
				if (is_a($zval, __CLASS__))
					$this->value /= $zval->value;
				else $this->value /= $zval;
			break;

			case OPS_ASSIGN_MOD:
				if (is_a($zval, __CLASS__))
					$this->value %= $zval->value;
				else $this->value %= $zval;
			break;

			case OPS_ASSIGN_SL:
				if (is_a($zval, __CLASS__))
					$this->value <<= $zval->value;
				else $this->value <<= $zval;
			break;

			case OPS_ASSIGN_SR:
				if (is_a($zval, __CLASS__))
					$this->value >>= $zval->value;
				else $this->value >>= $zval;
			break;

			case OPS_ASSIGN_BW_OR:
				if (is_a($zval, __CLASS__))
					$this->value |= $zval->value;
				else $this->value |= $zval;
			break;

			case OPS_ASSIGN_BW_AND:
				if (is_a($zval, __CLASS__))
					$this->value &= $zval->value;
				else $this->value &= $zval;
			break;
			
			case OPS_ASSIGN_BW_XOR:
				if (is_a($zval, __CLASS__))
					$this->value ^= $zval->value;	
				else $this->value ^= $zval;
			break;

			default: 
				throw new Exception("Unsupported operator {$opcode}");
		}
	}
}

$m = new operable(5);

$a = 5;
$b = new operable(10);

$d = $b * $m;

var_dump($d);
$d += $a;

var_dump($d);

var_dump(($d * 10) / 2);
?>
