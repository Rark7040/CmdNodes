<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use pocketmine\Server;
use rarkhopper\command_nodes\exception\SelectorException;
use function explode;
use function str_contains;

/**
 * @internal
 */
final class SimpleStringToArgumentParser implements IStringToFilterParser{
	/** @var array<string, class-string<IFilter>> */
	private array $filters = [];

	public function __construct(){
		$this->setDefaults();
	}

	private function setDefaults() : void{
		$this->register(CountFilter::getTypes(), CountFilter::class);
	}

	public function register(array $types, string $filterClass, bool $override = false) : IStringToFilterParser{
		$logger = Server::getInstance()->getLogger();

		foreach($types as $type){
			if(isset($this->selectors[$type])){
				if(!$override) throw new SelectorException('cannot override already registered selector. but given ' . $type . ':' . $filterClass);
				$logger->debug('selector ' . $type . ' was overriding to ' . $filterClass);
			}
			$this->filters[$type] = $filterClass;
		}
		return $this;
	}

	public function getFilter(string $strArg) : ?IFilter{
		if(!str_contains($strArg, '=')) return null;
		[$type, $operand] = explode('=', $strArg, 2);

		if($type === "" || $operand === "") return null;
		$filterClass = $this->filters[$type] ?? null;

		if($filterClass === null) return null;
		return new $filterClass($type, $operand);
	}
}
