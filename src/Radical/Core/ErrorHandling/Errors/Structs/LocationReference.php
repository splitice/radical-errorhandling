<?php
namespace Radical\Core\ErrorHandling\Errors\Structs;

class LocationReference {
	public $file;
	public $line;
	
	function __construct($file,$line){
		$this->file = $file;
		$this->line = $line;
	}
	
	function __toString(){
		return $this->file.'@'.$this->line;
	}
	
	function toShort(){
		return $this->file.'@'.$this->line;
	}
}
