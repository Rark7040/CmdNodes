<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\entity\Entity;
use pocketmine\player\Player;
use rarkhopper\command_nodes\command\selector\argument\IArgument;

interface ISelector{
	/**
	 * @param array<IArgument> $args
	 */
	public function __construct(Player $executor, array $args);

	public static function getIdentifier() : string;

	public function getExecutor() : Player;

	/**
	 * @return IArgument[]
	 */
	public function getArguments() : array;

	/**
	 * @return array<Entity>
	 */
	public function selectEntities(Player $executor) : array;
}
