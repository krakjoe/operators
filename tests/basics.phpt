--TEST--
Test basic operators
--DESCRIPTION--
This test verifies basic operators are working
--FILE--
<?php
class Test implements Operators {
    private $value;
	
	public function __construct($value) {
		$this->value = $value;
	}
	
    public function __operators($opcode, $zval = null) {
		switch($opcode) {
			case OPS_ADD: return $this->value + $zval;
			case OPS_SUB: return $this->value - $zval;
			case OPS_MUL: return $this->value * $zval;
			case OPS_DIV: return $this->value / $zval;
			case OPS_SL: return $this->value << $zval;
			case OPS_SR: return $this->value >> $zval;
			case OPS_BW_OR: return $this->value | $zval;
			case OPS_BW_AND: return $this->value & $zval;
			case OPS_BW_XOR: return $this->value ^ $zval;

			case OPS_IDENTICAL: return $this->value === $zval; break;
			case OPS_NOT_IDENTICAL: return $this->value !== $zval; break;
			case OPS_IS_EQUAL: return $this->value == $zval; break;
			case OPS_IS_NOT_EQUAL: return $this->value != $zval; break;
		}
	}
}

$false = new Test(0);
$true = new Test(1);
$even = new Test(2);
$odd = new Test(3);
$ten = new Test(10);

$strings = array(
	new Test("first"),
	new Test("second")
);

var_dump($even + 3);
var_dump($ten - 5);
var_dump($ten * 10);
var_dump(($ten * 10) / 5);
var_dump($ten << 2);
var_dump($ten >> 2);
var_dump($true | 2);
var_dump($even & 2);
var_dump($even ^ 1);
var_dump($even === 2);
var_dump($even !== 1);
var_dump($even == 2);
var_dump($even != 1);
?>
--EXPECT--
int(5)
int(5)
int(100)
int(20)
int(40)
int(2)
int(3)
int(2)
int(3)
bool(true)
bool(true)
bool(true)
bool(true)

