<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\validator;

use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use rarkhopper\command_nodes\exception\InvalidExecutorException;
use rarkhopper\command_nodes\exception\InvalidValidatorOperandException;

interface IValidator{

	/**
	 * @throws InvalidValidatorOperandException
	 */
	public function __construct(string $strOperand);

	/**
	 * @return array<string>
	 */
	public static function getTypes() : array;

	public static function isValidOperand(string $strOperand) : bool;

	/**
	 * @return scalar
	 */
	public function getOperand() : mixed;

	/**
	 * @param array<Entity> $entities
	 * @return array<Entity>
	 * @throws InvalidExecutorException
	 */
	public function validate(CommandSender $executor, array $entities) : array;
}
