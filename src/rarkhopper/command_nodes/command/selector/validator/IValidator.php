<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector\validator;

use pocketmine\entity\Entity;

interface IValidator{
	public function __construct(string $strOperand);

	/**
	 * @return array<string>
	 */
	public static function getTypes() : array;

	/**
	 * @return scalar
	 */
	public function getOperand() : mixed;

	/**
	 * @param array<Entity> $entities
	 * @return array<Entity>
	 */
	public function validate(array $entities) : array;
}
