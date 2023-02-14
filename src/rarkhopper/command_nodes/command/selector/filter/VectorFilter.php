<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use pocketmine\player\Player;
use rarkhopper\command_nodes\command\selector\IOperandsPool;
use rarkhopper\command_nodes\exception\InvalidFilterOperandException;
use function filter_var;
use const FILTER_VALIDATE_FLOAT;

final class VectorFilter extends MultipleOperandsFilter{
	private float $vec;

	public function __construct(string $usedType, private string $strOperand){
		parent::__construct($usedType, $strOperand);

		if(self::isValidOperand($strOperand)) throw new InvalidFilterOperandException($strOperand);
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

	public function pool(IOperandsPool $pool) : void{
		$pool->pool($this->getUsedType(), $this->strOperand);
	}

	public function filterOnCompletion(Player $executor, array $entities, IOperandsPool $pool) : array{
		// TODO: Implement filterOnCompletion() method.
	}
}
