<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\utils\SingletonTrait;
use rarkhopper\command_nodes\exception\SelectorException;
use function explode;
use function preg_match;
use function trim;

final class SelectorsMap{
	use SingletonTrait;

	private const SELECTOR_PREFIX = '@';
	private const REGEX_SELECTOR_ID = '/^@[^(\[\])]*/';
	private const REGEX_SELECTOR_VALIDATOR = '/\[[^[\]]*\]/';

	/** @var array<string, class-string<ISelector>> */
	private array $selectors = [];

	public function __construct(){
		$this->register(AllEntitiesSelector::getIdentifier(), AllEntitiesSelector::class);
		$this->register(AllPlayersSelector::getIdentifier(), AllPlayersSelector::class);
		$this->register(ProximatePlayerSelector::getIdentifier(), ProximatePlayerSelector::class);
		$this->register(RandomPlayerSelector::getIdentifier(), RandomPlayerSelector::class);
		$this->register(SelfSelector::getIdentifier(), SelfSelector::class);
	}

	/**
	 * @param class-string<ISelector> $selectorClass 登録をするセレクターの文字列クラス
	 */
	private function register(string $prefix, string $selectorClass) : void{
		$this->selectors[self::SELECTOR_PREFIX . $prefix] = $selectorClass;
	}

	public function getSelector(string $arg) : ?ISelector{
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
