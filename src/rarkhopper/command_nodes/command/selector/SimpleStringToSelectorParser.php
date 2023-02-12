<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\Server;
use rarkhopper\command_nodes\exception\SelectorException;
use function explode;
use function preg_match;
use function trim;

/**
 * @internal
 */
final class SimpleStringToSelectorParser implements IStringToSelectorParser{
	private const SELECTOR_PREFIX = '@';
	private const REGEX_SELECTOR_ID = '/^@[^(\[\])]*/';
	private const REGEX_SELECTOR_VALIDATOR = '/\[[^[\]]*\]/';

	/** @var array<string, class-string<ISelector>> */
	private array $selectors = [];

	public function __construct(){
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
			if($override) throw new SelectorException('cannot override already registered selector. but given ' . $prefixedId . ':' . $selectorClass);
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
		return new $selectorClass(); //TODO: parse args
	}

	private function getIdentifierByArgument(string $arg) : ?string{
		if(preg_match(self::REGEX_SELECTOR_ID, $arg, $matches) === false){
			throw new SelectorException('invalid regular expression ' . self::REGEX_SELECTOR_ID);
		}
		return $matches[0] ?? null;
	}

	/**
	 * @return array<string>|null
	 */
	private function getStringValidators(string $arg) : ?array{
		if(preg_match(self::REGEX_SELECTOR_VALIDATOR, $arg, $matches) === false){
			throw new SelectorException('invalid regular expression ' . self::REGEX_SELECTOR_VALIDATOR);
		}
		$match = $matches[0] ?? null;

		if($match === null) return null;
		return explode(',', trim($match, '[]'));
	}
}
