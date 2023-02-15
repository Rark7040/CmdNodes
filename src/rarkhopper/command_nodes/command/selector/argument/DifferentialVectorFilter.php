<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use rarkhopper\command_nodes\command\selector\IOperandsPool;
use rarkhopper\command_nodes\exception\InvalidFilterOperandException;
use function filter_var;
use function max;
use function min;
use const FILTER_VALIDATE_FLOAT;

final class DifferentialVectorFilter extends ArgumentBase implements IMultipleArgumentFilter{
	private const TYPE_DIFFERENTIAL_X = 'dx';
	private const TYPE_DIFFERENTIAL_Y = 'dy';
	private const TYPE_DIFFERENTIAL_Z = 'dz';
	private float $vec;

	public function __construct(string $usedType, private string $strOperand){
		parent::__construct($usedType, $strOperand);

		if(self::isValidOperand($strOperand)) throw new InvalidFilterOperandException($strOperand);
		$this->vec = (float) $strOperand;
	}

	public static function getTypes() : array{
		return [
			self::TYPE_DIFFERENTIAL_X,
			self::TYPE_DIFFERENTIAL_Y,
			self::TYPE_DIFFERENTIAL_Z
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

	public function filterOnCompletion(Vector3 $vec3, array $entities, IOperandsPool $pool) : array{
		$aabb = $this->getAxisAlignedBB($vec3, $this->getInputtedVector($vec3, $pool));
		$inBoundsEntities = [];

		foreach($entities as $entity){
			if(!$aabb->isVectorInside($entity->getPosition())) continue;
			$inBoundsEntities[] = $entity;
		}
		return $inBoundsEntities;
	}

	private function getInputtedVector(Vector3 $vec3, IOperandsPool $pool) : Vector3{
		return new Vector3(
			$pool->getFloat(self::TYPE_DIFFERENTIAL_X) ?? $vec3->x,
			$pool->getFloat(self::TYPE_DIFFERENTIAL_Y) ?? $vec3->y,
			$pool->getFloat(self::TYPE_DIFFERENTIAL_Z) ?? $vec3->z
		);
	}

	private function getAxisAlignedBB(Vector3 $vecA, Vector3 $vecB) : AxisAlignedBB{
		return new AxisAlignedBB(
			min($vecA->x, $vecB->x),
			min($vecA->y, $vecB->y),
			min($vecA->z, $vecB->z),
			max($vecA->x, $vecB->x),
			max($vecA->y, $vecB->y),
			max($vecA->z, $vecB->z)
		);
	}
}
