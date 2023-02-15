<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\Server;
use rarkhopper\command_nodes\command\selector\argument\IFilter;
use rarkhopper\command_nodes\command\selector\argument\IStringToFilterParser;
use rarkhopper\command_nodes\exception\SelectorException;
use function explode;
use function preg_match;
use function str_contains;
use function trim;

final class SimpleStringToSelectorParser implements IStringToSelectorParser{
	private const SELECTOR_PREFIX = '@';
	private const REGEX_SELECTOR_ID = '/^@[^(\[\])]*/';
	private const REGEX_SELECTOR_ARGUMENTS = '/\[[^[\]]*\]/';

	/** @var array<string, class-string<ISelector>> */
	private array $selectors = [];

	public function __construct(private IStringToFilterParser $filterParser){
		$this->setDefaults();
	}

	private function setDefaults() : void{
		$this->register(AllEntitiesSelector::getIdentifier(), AllEntitiesSelector::class)
			->register(AllPlayersSelector::getIdentifier(), AllPlayersSelector::class)
			->register(ProximatePlayerSelector::getIdentifier(), ProximatePlayerSelector::class)
			->register(RandomPlayerSelector::getIdentifier(), RandomPlayerSelector::class)
			->register(SelfSelector::getIdentifier(), SelfSelector::class);
	}

	public function register(string $id, string $selectorClass, bool $override = false) : IStringToSelectorParser{
		$logger = Server::getInstance()->getLogger();
		$prefixedId = self::SELECTOR_PREFIX . $id;

		if(isset($this->selectors[$prefixedId])){
			if(!$override) throw new SelectorException('cannot override already registered selector. but given ' . $prefixedId . ':' . $selectorClass);
			$logger->debug('selector ' . $prefixedId . ' was overriding to ' . $selectorClass);
		}
		$this->selectors[$prefixedId] = $selectorClass;
		return $this;
	}

	public function parse(string $arg) : ?ISelector{
		$selectorId = $this->getIdentifierByArgument($arg);

		if($selectorId === null) return null;
		$selectorClass = $this->selectors[$selectorId] ?? null;

		if($selectorClass === null) return null;
		$filters = $this->getSelectorArguments($arg);

		if($filters === null) return null;
		return new $selectorClass($filters);
	}

	private function getIdentifierByArgument(string $arg) : ?string{
		if(preg_match(self::REGEX_SELECTOR_ID, $arg, $matches) === false){
			throw new SelectorException('invalid regular expression ' . self::REGEX_SELECTOR_ID);
		}
		return $matches[0] ?? null;
	}

	/**
	 * @return array<IFilter>|null
	 */
	private function getSelectorArguments(string $arg) : ?array{
		$selectorArgs = [];
		$strSelectorArgs = $this->getStringSelectorArguments($arg);

		if($strSelectorArgs === null) return null;
		foreach($strSelectorArgs as $strSelectorArg){
			$selectorArg = $this->filterParser->getFilter($strSelectorArg);

			if($selectorArg === null) return null;
			$selectorArgs[] = $selectorArg;
		}
		return $selectorArgs;
	}

	/**
	 * @return array<string>|null
	 */
	private function getStringSelectorArguments(string $arg) : ?array{
		if(preg_match(self::REGEX_SELECTOR_ARGUMENTS, $arg, $matches) === false){
			throw new SelectorException('invalid regular expression ' . self::REGEX_SELECTOR_ARGUMENTS);
		}
		$match = $matches[0] ?? null;

		if($match === null){
			return str_contains($arg, '[')? null: [];
		}
		return explode(',', trim($match, '[]'));
	}
}
