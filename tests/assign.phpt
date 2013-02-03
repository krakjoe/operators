--TEST--
Test assignment operations
--DESCRIPTION--
This test verifies assignment operators are working
--FILE--
<?php
class Test implements Operators {
    private $value;
	
	public function __construct($value) {
		$this->value = $value;
	}
	
    public function __operators($opcode, $zval = null) {
		switch($opcode) {
			case OPS_MUL:
				if (is_a($zval, __CLASS__)) 
					return new Test($this->value * $zval->value);
				else return new Test($this->value * $zval);
			break;

			case OPS_ASSIGN_ADD:
				if (is_a($zval, __CLASS__))
					$this->value += $zval->value;
				else $this->value += $zval;	
				return 0;
			break;
		}
	}
}
$test = new Test(10);

$a = $test * 5;
var_dump($a);
$b = $a * $a;
var_dump($b);

$b += $a;
var_dump($b);

$a += $b;
var_dump($a, $b);
?>
--EXPECT--
object(Test)#2 (1) {
  ["value":"Test":private]=>
  int(50)
}
object(Test)#3 (1) {
  ["value":"Test":private]=>
  int(2500)
}
object(Test)#3 (1) {
  ["value":"Test":private]=>
  int(2550)
}
object(Test)#2 (1) {
  ["value":"Test":private]=>
  int(2600)
}
object(Test)#3 (1) {
  ["value":"Test":private]=>
  int(2550)
}

