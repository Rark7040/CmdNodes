<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use rarkhopper\command_nodes\command\selector\validator\IValidator;

/**
 * @internal
 */
abstract class BaseSelector implements ISelector{
	/**
	 * @param array<IValidator> $validators
	 */
	final public function __construct(private array $validators){}

	/**
	 * @return IValidator[]
	 */
	public function getValidators() : array{
		return $this->validators;
	}
}
