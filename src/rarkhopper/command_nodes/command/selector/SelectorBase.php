<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use rarkhopper\command_nodes\command\selector\argument\IArgument;
use rarkhopper\command_nodes\command\selector\argument\IFilter;
use rarkhopper\command_nodes\command\selector\argument\IMultipleArgumentFilter;
use rarkhopper\command_nodes\command\selector\argument\IVectorArgument;
use function end;

/**
 * @internal
 */
abstract class SelectorBase implements ISelector{
	/** @var array<IVectorArgument> */
	private array $vecArgs = [];
	/** @var array<IMultipleArgumentFilter> */
	private array $multipleOperandsArgs = [];
	/** @var array<IFilter> */
	private array $filters = [];

	/**
	 * @param array<IArgument> $args
	 */
	final public function __construct(private Player $executor, private array $args){
		$this->allocateArguments();
	}

	public function getExecutor() : Player{
		return $this->executor;
	}

	/**
	 * @return IArgument[]
	 */
	public function getArguments() : array{
		return $this->args;
	}

	private function allocateArguments() : void{
		foreach($this->args as $arg){
			if($arg instanceof IVectorArgument){
				$this->vecArgs[] = $arg;
			}
			if($arg instanceof IMultipleArgumentFilter){
				$this->multipleOperandsArgs[] = $arg;
			}
			if($arg instanceof IFilter){
				$this->filters[] = $arg;
			}
		}
	}

	/**
	 * @param array<Entity> $entities
	 * @return array<Entity>
	 */
	final protected function filterEntities(array $entities) : array{
		$vec3 = $this->getSelectorVector();
		$operandsPool = new SimpleOperandsPool();
		$pooledArgs = [];

		foreach($this->multipleOperandsArgs as $multipleOperandsArg){
			$multipleOperandsArg->pool($operandsPool);
			$pooledArgs[$multipleOperandsArg::class] = $multipleOperandsArg;
		}
		foreach($pooledArgs as $pooledArg){
			$entities = $pooledArg->filterOnCompletion($vec3, $entities, $operandsPool);
		}
		foreach($this->filters as $filter){
			$filter->filter($vec3, $entities);
		}
		return $entities;
	}

	private function getSelectorVector() : Vector3{
		$vectorPool = new SimpleOperandsPool();
		$vec3 = $this->executor->getPosition()->asVector3();

		foreach($this->vecArgs as $vecArg){
			$vecArg->pool($vectorPool);

			if($vecArg === end($this->vecArgs)){
				$vec3 = $vecArg->getVector3($vec3, $vectorPool);
			}
		}
		return $vec3;
	}
}
