<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use pocketmine\math\Vector3;
use rarkhopper\command_nodes\command\selector\IOperandsPool;
use rarkhopper\command_nodes\exception\InvalidFilterOperandException;
use function filter_var;
use const FILTER_VALIDATE_FLOAT;

final class VectorArgument extends ArgumentBase implements IVectorArgument{
	private const TYPE_X = 'x';
	private const TYPE_Y = 'y';
	private const TYPE_Z = 'z';
	private float $vec;

	public function __construct(string $usedType, private string $strOperand){
		parent::__construct($usedType, $strOperand);

		if(self::isValidOperand($strOperand)) throw new InvalidFilterOperandException($strOperand);
		$this->vec = (float) $strOperand;
	}

	public static function getTypes() : array{
		return [
			self::TYPE_X,
			self::TYPE_Y,
			self::TYPE_Z
		];
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

	public function getVector3(Vector3 $vec3, IOperandsPool $pool) : Vector3{
		return new Vector3(
			$pool->getFloat(self::TYPE_X) ?? $vec3->x,
			$pool->getFloat(self::TYPE_Y) ?? $vec3->y,
			$pool->getFloat(self::TYPE_Z) ?? $vec3->z
		);
	}
}
