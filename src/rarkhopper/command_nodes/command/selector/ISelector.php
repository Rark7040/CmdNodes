<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use rarkhopper\command_nodes\command\selector\filter\IFilter;

interface ISelector{
	/**
	 * @param array<IFilter> $validators
	 */
	public function __construct(array $validators);

	public static function getIdentifier() : string;

	/**
	 * @return IFilter[]
	 */
	public function getFilters() : array;

	/**
	 * @return array<Entity>
	 */
	public function selectEntities(?CommandSender $executor) : array;
}
