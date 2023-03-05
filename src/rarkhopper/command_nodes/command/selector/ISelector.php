<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\entity\Entity;
use pocketmine\player\Player;
use rarkhopper\command_nodes\command\selector\argument\ISelectorArgument;

interface ISelector{
	/**
	 * @param array<ISelectorArgument> $args
	 */
	public function __construct(array $args);

	public static function getIdentifier() : string;

	/**
	 * @return ISelectorArgument[]
	 */
	public function getArguments() : array;

	/**
	 * @return array<Entity>
	 */
	public function selectEntities(Player $executor) : array;
}
