<?php
namespace Radical\Core\ErrorHandling\Handlers;

use Radical\Core\ErrorHandling\Errors\Internal\ErrorBase;
use Radical\Core\ErrorHandling\Errors\Internal\ErrorException;
use Radical\Core\ErrorHandling\Handler;

abstract class ErrorHandlerBase extends Handler {
	/**
	 * The namespaces for all error trackers.
	 * 
	 * @radical ns-expr
	 * @var string
	 */
	const ERRORS_EXPR = '\\Radical\\Core\\ErrorHandling\\Errors\\*';
	
	/**
	 * Calls the init functions for all the error modules
	 */
	function __construct(){
		//Itterate all error trackers
		foreach(\Radical\Core\Libraries::get(self::ERRORS_EXPR) as $class){
			$class::Init();
		}
		
		parent::__construct();
	}
	
	abstract function error(ErrorBase $error);
	abstract function exception(ErrorException $error);
}