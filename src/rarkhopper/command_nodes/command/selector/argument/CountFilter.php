<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use pocketmine\math\Vector3;
use rarkhopper\command_nodes\exception\InvalidFilterOperandException;
use function abs;
use function array_slice;
use function count;
use function filter_var;
use const FILTER_VALIDATE_INT;
//TODO: type=!1
final class CountFilter extends ArgumentBase implements IFilter{
	private const TYPE_COUNT = 'c';
	private int $maxCnt;

	public function __construct(string $usedType, string $strOperand){
		parent::__construct($usedType, $strOperand);

		if(self::isValidOperand($strOperand)) throw new InvalidFilterOperandException($strOperand);
		$this->maxCnt = (int) $strOperand;
	}

	public static function getTypes() : array{
		return [self::TYPE_COUNT];
	}

	public static function isValidOperand(string $strOperand) : bool{
		return filter_var($strOperand, FILTER_VALIDATE_INT) !== false;
	}

	public function getOperand() : int{
		return $this->maxCnt;
	}

	public function filter(Vector3 $vec3, array $entities) : array{
		if(count($entities) <= abs($this->maxCnt)) return $entities;
		$sortedEntities = $this->sortByDistance($vec3, $entities);
		return $this->maxCnt < 0?
			array_slice($sortedEntities, $this->maxCnt):
			array_slice($sortedEntities, 0, $this->maxCnt);
	}
}
