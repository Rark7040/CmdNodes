<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\validator;

use pocketmine\command\CommandSender;
use rarkhopper\command_nodes\exception\InvalidValidatorOperandException;
use function array_slice;
use function count;
use function filter_var;
use const FILTER_VALIDATE_INT;

final class CountValidator implements IValidator{
	private int $maxCnt;

	public function __construct(string $strOperand){
		if(self::isValidOperand($strOperand)) throw new InvalidValidatorOperandException($strOperand);
		$this->maxCnt = (int) $strOperand;
	}

	public static function getTypes() : array{
		return ['c'];
	}

	public static function isValidOperand(string $strOperand) : bool{
		if(filter_var($strOperand, FILTER_VALIDATE_INT) === false) return false;
		return (int) $strOperand > 0;
	}

	public function getOperand() : int{
		return $this->maxCnt;
	}

	public function validate(CommandSender $executor, array $entities) : array{
		if(count($entities) <= $this->maxCnt) return $entities;
		return array_slice($entities, 0, $this->maxCnt);
	}
}