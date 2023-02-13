<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\validator;

use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use rarkhopper\command_nodes\exception\InvalidExecutorException;
use rarkhopper\command_nodes\exception\InvalidValidatorOperandException;
use function filter_var;
use const FILTER_VALIDATE_FLOAT;

final class RadiusValidator implements IValidator{
	private float $radius;

	public function __construct(string $strOperand){
		if(self::isValidOperand($strOperand)) throw new InvalidValidatorOperandException($strOperand);
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

	public function validate(CommandSender $executor, array $entities) : array{
		$filteredEntities = [];

		if(!$executor instanceof Player) throw new InvalidExecutorException($executor, Player::class);
		$pos = $executor->getPosition();

		foreach($entities as  $entity){
			if($entity->getPosition()->distance($pos) > $this->radius) continue;
			$filteredEntities[] = $entity;
		}
		return $filteredEntities;
	}
}
