<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use rarkhopper\command_nodes\exception\InvalidExecutorException;
use rarkhopper\command_nodes\exception\InvalidValidatorOperandException;
use function filter_var;
use const FILTER_VALIDATE_FLOAT;

final class MinRadiusFilter  extends FilterBase{
	private float $minRadius;

	public function __construct(string $usedType, string $strOperand){
		parent::__construct($usedType, $strOperand);

		if(self::isValidOperand($strOperand)) throw new InvalidValidatorOperandException($strOperand);
		$this->minRadius = (float) $strOperand;
	}

	public static function getTypes() : array{
		return ['rm'];
	}

	public static function isValidOperand(string $strOperand) : bool{
		return filter_var($strOperand, FILTER_VALIDATE_FLOAT) !== false;
	}

	public function getOperand() : float{
		return $this->minRadius;
	}

	public function filter(CommandSender $executor, array $entities) : array{
		$filteredEntities = [];

		if(!$executor instanceof Player) throw new InvalidExecutorException($executor, Player::class);
		$pos = $executor->getPosition();

		foreach($entities as  $entity){
			if($entity->getPosition()->distance($pos) < $this->minRadius) continue;
			$filteredEntities[] = $entity;
		}
		return $filteredEntities;
	}
}
