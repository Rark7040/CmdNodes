<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\entity\Entity;
use rarkhopper\command_nodes\command\selector\filter\IFilter;

/**
 * @internal
 */
abstract class BaseSelector implements ISelector{
	/**
	 * @param array<IFilter> $filters
	 */
	final public function __construct(private array $filters){}

	/**
	 * @return IFilter[]
	 */
	public function getFilters() : array{
		return $this->filters;
	}

	/**
	 * @param array<Entity> $entities
	 * @return array<Entity>
	 */
	final protected function filterEntities(array $entities) : array{

		return [];
	}
}
