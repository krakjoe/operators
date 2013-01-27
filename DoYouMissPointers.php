<?php
/* pretty pointless ... but cool all the same */
class StringPointer {
	private $string;
	private $position;
	private $end;

	public function __construct(&$string){
		$this->string = $string;
		$this->position = 0;
		$this->end = strlen($string);
	}

	public function __operators($opcode, $zval = null) {
		switch($opcode){
			case OPERATOR_POST_INC:
					$this->position++;
				return 0;
			
			case OPERATOR_PRE_INC:
					++$this->position;
				return 0;
			
			case OPERATOR_POST_DEC:
					$this->position--;
				return 0;
			
			case OPERATOR_PRE_DEC:
					--$this->position;
				return 0;
		}
		return 0;
	}
	
	public function __toString(){
		if ($this->position < $this->end)
			return (string) $this->string[$this->position];
		else return "";
	}
}

$string = "The Cow Jumped Over The Moon";
$pointer = new StringPointer($string);

while(((string)$pointer)) {
	printf("%s", (string)$pointer);
	++$pointer;
}
?>
