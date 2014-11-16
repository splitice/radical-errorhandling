<?php
namespace Radical\Core\ErrorHandling\Errors\Internal;

use Radical\Core\ErrorHandling\IErrorException;

class SerializableErrorException implements IErrorException {
	protected $heading;
	protected $fatal = false;
	protected $trace_output;
	protected $message;
	protected $class;
	protected $debugMessage;
	
	function __construct(ErrorException $ex){
		$this->heading = $ex->getHeading();
		$this->fatal = $ex->isFatal();
		$this->message = $ex->getMessage();

		if(method_exists($ex,'getDebugMessage')){
			$this->debugMessage = $ex->getDebugMessage();
		}
		
		if(method_exists($ex,'getTraceOutput')){
			$this->trace_output = $ex->getTraceOutput();
		}else{
			$this->trace_output = $ex->getTraceAsString();
		}
		$this->class = get_class($ex);
	}
	
	function getClass(){
		return $this->class;
	}

	/**
	 * @return the $header
	 */
	public function getHeading() {
		return $this->heading;
	}
	
	function isFatal(){
		return $this->fatal;
	}
	
	function getMessage(){
		return $this->message;
	}
	
	function getDebugMessage(){
		return $this->debugMessage == null ? $this->message : $this->debugMessage;
	}
	
	function getTraceOutput(){
		return $this->trace_output;
	}
	
	function serialize(){
		return serialize($this);
	}
}