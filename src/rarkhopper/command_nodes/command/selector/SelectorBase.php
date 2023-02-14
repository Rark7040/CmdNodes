<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\entity\Entity;
use pocketmine\player\Player;
use rarkhopper\command_nodes\command\selector\filter\IFilter;
use rarkhopper\command_nodes\command\selector\filter\IMultipleOperandsFilter;

/**
 * @internal
 */
abstract class SelectorBase implements ISelector{
	/**
	 * @param array<IFilter> $filters
	 */
	final public function __construct(private Player $executor, private array $filters){}

	public function getExecutor() : Player{
		return $this->executor;
	}

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
		$pooledFilters = [];
		$pool = new SimpleOperandsPool();

		foreach($this->filters as $filter){
			if($filter instanceof IMultipleOperandsFilter){
				$filter->pool($pool);
				$pooledFilters[$filter::class] = $filter;
			}
			$entities = $filter->filter($this->executor, $entities);
		}

		foreach($pooledFilters as $pooledFilter){
			$entities = $pooledFilter->filterOnCompletion($this->executor, $entities, $pool);
		}
		return $entities;
	}
}
