<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use pocketmine\math\Vector3;
use rarkhopper\command_nodes\exception\InvalidFilterOperandException;
use function array_slice;
use function count;
use function filter_var;
use const FILTER_VALIDATE_INT;

final class CountFilter extends FilterBase{
	private int $maxCnt;

	public function __construct(string $usedType, string $strOperand){
		parent::__construct($usedType, $strOperand);

		if(self::isValidOperand($strOperand)) throw new InvalidFilterOperandException($strOperand);
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

	public function filter(Vector3 $vec3, array $entities) : array{
		if(count($entities) <= $this->maxCnt) return $entities;
		return array_slice($this->orderByDistance($vec3, $entities), 0, $this->maxCnt);
	}
}
