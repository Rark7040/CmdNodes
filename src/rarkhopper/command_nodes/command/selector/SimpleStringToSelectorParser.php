<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\Server;
use rarkhopper\command_nodes\command\selector\filter\IFilter;
use rarkhopper\command_nodes\command\selector\filter\IStringToFilterParser;
use rarkhopper\command_nodes\exception\SelectorException;
use function explode;
use function preg_match;
use function str_contains;
use function trim;

final class SimpleStringToSelectorParser implements IStringToSelectorParser{
	private const SELECTOR_PREFIX = '@';
	private const REGEX_SELECTOR_ID = '/^@[^(\[\])]*/';
	private const REGEX_SELECTOR_FILTERS = '/\[[^[\]]*\]/';

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
		$filters = $this->getFilters($arg);

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
	private function getFilters(string $arg) : ?array{
		$validators = [];
		$strFilters = $this->getStringFilters($arg);

		if($strFilters === null) return null;
		foreach($strFilters as $strFilter){
			$validator = $this->filterParser->getFilter($strFilter);

			if($validator === null) return null;
			$validators[] = $validator;
		}
		return $validators;
	}

	/**
	 * @return array<string>|null
	 */
	private function getStringFilters(string $arg) : ?array{
		if(preg_match(self::REGEX_SELECTOR_FILTERS, $arg, $matches) === false){
			throw new SelectorException('invalid regular expression ' . self::REGEX_SELECTOR_FILTERS);
		}
		$match = $matches[0] ?? null;

		if($match === null){
			return str_contains($arg, '[')? null: [];
		}
		return explode(',', trim($match, '[]'));
	}
}
