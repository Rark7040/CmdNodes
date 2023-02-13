<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\Server;
use rarkhopper\command_nodes\command\selector\validator\IStringToValidatorParser;
use rarkhopper\command_nodes\command\selector\validator\IValidator;
use rarkhopper\command_nodes\exception\SelectorException;
use function explode;
use function preg_match;
use function str_contains;
use function trim;

final class SimpleStringToSelectorParser implements IStringToSelectorParser{
	private const SELECTOR_PREFIX = '@';
	private const REGEX_SELECTOR_ID = '/^@[^(\[\])]*/';
	private const REGEX_SELECTOR_VALIDATOR = '/\[[^[\]]*\]/';

	/** @var array<string, class-string<ISelector>> */
	private array $selectors = [];

	public function __construct(private IStringToValidatorParser $validatorParser){
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
		$validators = $this->getValidators($arg);

		if($validators === null) return null;
		return new $selectorClass($validators); //TODO: parse args
	}

	private function getIdentifierByArgument(string $arg) : ?string{
		if(preg_match(self::REGEX_SELECTOR_ID, $arg, $matches) === false){
			throw new SelectorException('invalid regular expression ' . self::REGEX_SELECTOR_ID);
		}
		return $matches[0] ?? null;
	}

	/**
	 * @return array<IValidator>|null
	 */
	private function getValidators(string $arg) : ?array{
		$validators = [];
		$stringValidators = $this->getStringValidators($arg);

		if($stringValidators === null) return null;
		foreach($stringValidators as $stringValidator){
			$validator = $this->validatorParser->getValidator($stringValidator);

			if($validator === null) return null;
			$validators[] = $validator;
		}
		return $validators;
	}

	/**
	 * @return array<string>|null
	 */
	private function getStringValidators(string $arg) : ?array{
		if(preg_match(self::REGEX_SELECTOR_VALIDATOR, $arg, $matches) === false){
			throw new SelectorException('invalid regular expression ' . self::REGEX_SELECTOR_VALIDATOR);
		}
		$match = $matches[0] ?? null;

		if($match === null){
			return str_contains($arg, '[')? null: [];
		}
		return explode(',', trim($match, '[]'));
	}
}
