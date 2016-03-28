<?php
namespace Radical\Core\ErrorHandling\Errors;

use Radical\Core\Server;

class PHPError extends Internal\ErrorBase {
	const HEADER = 'Site Error (PHP)';
    private $errno;
    private $error_text;
    private $error_location;
	
	function __construct($errno, $msg_text, Structs\LocationReference $where){
        $this->errno = $errno;
        $this->error_text = $msg_text;
        $this->error_location = $where;

		//Build Error page
		if(!\Radical\Core\Server::isProduction() || \Radical\Core\Server::isCLI()){
			$message = 'A PHP error occurred at '.$where->toShort().': '.$msg_text;
		}else{
			$message = 'An error has occurred in the script.';
			global $_ADMIN_EMAIL;
			if(isset($_ADMIN_EMAIL)){
				$message .= ' Please report this to an administrator at '.$_ADMIN_EMAIL.'.';
			}
		}

		$fatal = false;
		if($errno == E_CORE_ERROR || $errno == E_ERROR || $errno == E_RECOVERABLE_ERROR || $errno == E_USER_ERROR){
			$fatal = true;
		}
		
		parent::__construct($message,static::HEADER,$fatal);
		

	}
    public function getErrno(){
        return $this->errno;
    }
    public function getErrorText(){
        return $this->error_text;
    }
    public function getErrorLocation(){
        return $this->error_location;
    }
	static function init(){
		ini_set('display_errors','On');
		$handler = array (get_called_class(), 'handler' );
		set_error_handler ( $handler, error_reporting ());
	}
	static function handler($errno, $msg_text, $errfile, $errline) {
		if (! (error_reporting () & $errno)) {
			return true;
		}
		if (!($errno & E_STRICT)) { //E_STRICT, well we would like it but not PEAR
			if(($errno & E_WARNING) && preg_match('/^Declaration of .+ should be compatible with/', $msg_text)){
				return true;
			}
			new static($errno, $msg_text, new Structs\LocationReference($errfile, $errline));
			return Server::isProduction();
		}
	
		return true;
	}
}