<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use pocketmine\player\Player;
use rarkhopper\command_nodes\exception\InvalidFilterOperandException;
use function filter_var;
use const FILTER_VALIDATE_FLOAT;

final class RadiusFilter extends FilterBase{
	private float $radius;

	public function __construct(string $usedType, string $strOperand){
		parent::__construct($usedType, $strOperand);

		if(self::isValidOperand($strOperand)) throw new InvalidFilterOperandException($strOperand);
		$this->radius = (float) $strOperand;
	}

	public static function getTypes() : array{
		return ['r'];
	}

	public static function isValidOperand(string $strOperand) : bool{
		return filter_var($strOperand, FILTER_VALIDATE_FLOAT) !== false;
	}

	public function getOperand() : float{
		return $this->radius;
	}

	public function filter(Player $executor, array $entities) : array{
		$filteredEntities = [];

		foreach($this->orderByDistance($executor->getPosition(), $entities) as $distance => $entity){
			if($distance > $this->radius) continue;
			$filteredEntities[] = $entity;
		}
		return $filteredEntities;
	}
}
