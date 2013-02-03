# Operators in PHP

An operable object will be passed an opcode and zval at execution time.
The task of the operable object is to either manipulate it's own data ( assign ) or to return a value to the engine.
Operators can be used on a mix of scalars and objects, the operator method can return an object or scalar to the engine.

## Example
```php
<?php
class Operable implements Operators {
	private $data;

	public function __construct($data) {
		$this->data = $data;
	}

	public function __operators($opcode, $zval = null) {
		switch($opcode) {
			case OPS_ASSIGN_ADD:
				if (is_array($zval)) {
					$this->data = array_merge($this->data, $zval);
				} else if (is_a($zval, __CLASS__)) {
					$this->data = array_merge($this->data, $zval->data);
				} else $this->data[] = $zval;
			break; 
		}
	}
}
$my = new Operable(array(
	"Hello" => "World"
));

$my += array("Best" => "Intentions");

var_dump($my);

$my += "Other Things";

var_dump($my);
?>
```

In the example above, you can see how you might use such functionality to manipulate both internal and user defined types with the help of the Operators interface.

```php
<?php
class Operable implements Operators {
	private $data;

	public function __construct($data) {
		$this->data = $data;
	}	

	public function __operators($opcode, $zval = null) {
		switch ($opcode) {
			case OPS_MUL:
				if (is_a($zval, __CLASS__)) {
					return new Operable($this->data * $zval->data);
				} else return new Operable($this->data * $zval);
			break;

			case OPS_ADD:
				if (is_a($zval, __CLASS__)) {
					return new Operable($this->data + $zval->data);
				} else return new Operable($this->data + $zval);
			break;

			case OPS_DIV:
				if (is_a($zval, __CLASS__)) {
					return new Operable($this->data / $zval->data);
				} else return new Operable($this->data / $zval);
			break;
		}
	}
}

$my = new Operable(10);

var_dump ($my * 10);

var_dump ($my + $my);

var_dump ((100 * $my) / 2);
?>
```

In the second example you can see that you need not return scalar values, which may or may not be useful, who knows ...

### For Fun
This extension was written for fun, unless you have rock solid PHP fu, do not use this extension, it was written for fun as a learning excercise.
