<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\entity\Entity;
use pocketmine\player\Player;
use rarkhopper\command_nodes\command\selector\filter\IFilter;

interface ISelector{
	/**
	 * @param array<IFilter> $validators
	 */
	public function __construct(Player $executor, array $validators);

	public static function getIdentifier() : string;

	public function getExecutor() : Player;

	/**
	 * @return IFilter[]
	 */
	public function getFilters() : array;

	/**
	 * @return array<Entity>
	 */
	public function selectEntities(Player $executor) : array;
}
