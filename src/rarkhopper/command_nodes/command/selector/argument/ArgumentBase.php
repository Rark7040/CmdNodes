<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\argument;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use rarkhopper\command_nodes\exception\InvalidFilterOperandException;
use function array_multisort;
use const SORT_DESC;
use const SORT_NUMERIC;

abstract class ArgumentBase implements IArgument{
	/**
	 * @throws InvalidFilterOperandException
	 */
	public function __construct(private string $usedType, private string $strOperand){}

	final public function getUsedType() : string{
		return $this->usedType;
	}

	final function getRawOperand() : string{
		return $this->strOperand;
	}

	/**
	 * @param array<Entity> $entities
	 * @return array<int, Entity>
	 */
	final protected function sortByDistance(Vector3 $vec3, array $entities) : array{
		$orderedEntities = [];

		foreach($entities as $entity){
			$orderedEntities[$entity->getPosition()->distance($vec3)] = $entity;
		}
		array_multisort($orderedEntities, SORT_NUMERIC, SORT_DESC);
		return $orderedEntities;
	}
}
