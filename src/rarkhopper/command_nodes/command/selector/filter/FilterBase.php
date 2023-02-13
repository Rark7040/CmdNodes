<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use rarkhopper\command_nodes\exception\InvalidValidatorOperandException;

abstract class FilterBase implements IFilter{
	/**
	 * @throws InvalidValidatorOperandException
	 */
	public function __construct(private string $usedType, private string $strOperand){}

	final public function getUsedType() : string{
		return $this->usedType;
	}

	final function getRawOperand() : string{
		return $this->strOperand;
	}
}
