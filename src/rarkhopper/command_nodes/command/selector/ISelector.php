<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use rarkhopper\command_nodes\command\selector\validator\IValidator;

interface ISelector{
	/**
	 * @param array<IValidator> $validators
	 */
	public function __construct(array $validators);

	public static function getIdentifier() : string;

	/**
	 * @return IValidator[]
	 */
	public function getValidators() : array;

	/**
	 * @return array<Entity>
	 */
	public function selectEntities(?CommandSender $executor) : array;
}
