<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\validator;

use pocketmine\Server;
use rarkhopper\command_nodes\exception\SelectorException;
use function explode;

/**
 * @internal
 */
class SimpleStringToValidatorParser implements IStringToValidatorParser{
	/** @var array<string, class-string<IValidator>> */
	private array $validators = [];

	public function __construct(){
		$this->setDefaults();
	}

	private function setDefaults() : void{

	}

	public function register(array $types, string $validatorClass, bool $override = false) : IStringToValidatorParser{
		$logger = Server::getInstance()->getLogger();

		foreach($types as $type){
			if(isset($this->selectors[$type])){
				if(!$override) throw new SelectorException('cannot override already registered selector. but given ' . $type . ':' . $validatorClass);
				$logger->debug('selector ' . $type . ' was overriding to ' . $validatorClass);
			}
			$this->validators[$type] = $validatorClass;
		}
		return $this;
	}

	public function getValidator(string $strValidator) : ?IValidator{
		[$type, $operand] = explode('=', $strValidator, 2);

		if($type === "" || $operand === "") return null;
		$validatorClass = $this->validators[$type] ?? null;

		if($validatorClass === null) return null;
		return new $validatorClass($operand);
	}
}
