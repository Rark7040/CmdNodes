<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use pocketmine\player\Player;
use rarkhopper\command_nodes\exception\InvalidValidatorOperandException;

class VectorFilter extends FilterBase{
	private float $vec;

	public function __construct(string $usedType, string $strOperand){
		parent::__construct($usedType, $strOperand);

		if(self::isValidOperand($strOperand)) throw new InvalidValidatorOperandException($strOperand);
		$this->vec = (float) $strOperand;
	}

	public static function getTypes() : array{
		return ['x', 'y', 'z', 'dx', 'dy', 'dz'];
	}

	public static function isValidOperand(string $strOperand) : bool{
		return filter_var($strOperand, FILTER_VALIDATE_FLOAT) !== false;
	}

	public function getOperand() : float{
		return $this->vec;
	}

	public function filter(Player $executor, array $entities) : array{
		$filteredEntities = [];
		$pos = $executor->getPosition();

		foreach($entities as $entity){
			if($entity->getPosition()->distance($pos) > $this->vec) continue;
			$filteredEntities[] = $entity;
		}
		return $filteredEntities;
	}
}