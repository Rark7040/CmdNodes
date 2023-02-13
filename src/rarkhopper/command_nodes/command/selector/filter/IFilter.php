<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\filter;

use pocketmine\entity\Entity;
use pocketmine\player\Player;
use rarkhopper\command_nodes\exception\InvalidExecutorException;
use rarkhopper\command_nodes\exception\InvalidValidatorOperandException;

interface IFilter{

	/**
	 * @throws InvalidValidatorOperandException
	 */
	public function __construct(string $usedType, string $strOperand);

	/**
	 * @return array<string>
	 */
	public static function getTypes() : array;

	public static function isValidOperand(string $strOperand) : bool;

	public function getUsedType() : string;

	public function getRawOperand() : string;

	/**
	 * @return scalar
	 */
	public function getOperand() : mixed;

	/**
	 * @param array<Entity> $entities
	 * @return array<Entity>
	 * @throws InvalidExecutorException
	 */
	public function filter(Player $executor, array $entities) : array;
}
