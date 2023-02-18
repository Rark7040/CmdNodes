<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use pocketmine\math\Vector3;
use rarkhopper\command_nodes\exception\InvalidFilterOperandException;
use function filter_var;
use const FILTER_VALIDATE_FLOAT;

final class MinRadiusFilter extends SelectorArgumentBase implements IFilter{
	private const TYPE_MIN_RADIUS = 'rm';
	private float $minRadius;

	public function __construct(string $usedType, string $strOperand){
		parent::__construct($usedType, $strOperand);

		if(self::isValidOperand($strOperand)) throw new InvalidFilterOperandException($strOperand);
		$this->minRadius = (float) $strOperand;
	}

	public static function getTypes() : array{
		return [self::TYPE_MIN_RADIUS];
	}

	public static function isValidOperand(string $strOperand) : bool{
		return filter_var($strOperand, FILTER_VALIDATE_FLOAT) !== false;
	}

	public function getOperand() : float{
		return $this->minRadius;
	}

	public function filter(Vector3 $vec3, array $entities) : array{
		$filteredEntities = [];

		foreach($this->sortByDistance($vec3, $entities) as $distance => $entity){
			if($distance < $this->minRadius) continue;
			$filteredEntities[] = $entity;
		}
		return $filteredEntities;
	}
}
