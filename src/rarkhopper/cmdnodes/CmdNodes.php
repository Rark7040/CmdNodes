<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use rarkhopper\cmdnodes\exception\CmdNodesException;

final class CmdNodes implements PluginOwned{
	use SingletonTrait;

	private ?Plugin $owner = null;
	private CmdNodesCommandMap $cmdMap;

	public function __construct(){
		$this->cmdMap = new CmdNodesCommandMap();
	}

	public function getCommandMap() : CmdNodesCommandMap{
		return $this->cmdMap;
	}

	/**
	 * @throws CmdNodesException
	 */
	public function registerOwner(Plugin $owner) : void{
		if($this->alreadyRegistered()) throw new CmdNodesException('already registered owner. given ' . $owner->getName());
		$this->owner = $owner;
	}

	public function alreadyRegistered() : bool{
		return $this->owner !== null;
	}

	/**
	 * @throws CmdNodesException
	 */
	public function getOwningPlugin() : Plugin{
		return $this->owner ?? throw new CmdNodesException('not yet registered owner');
	}

	public function unregisterOwner() : void{
		if(!$this->alreadyRegistered()){
			Server::getInstance()->getLogger()->warning('not yet registered owner. but was unregister');
		}
		$this->cmdMap->clearCommands();
		$this->owner = null;
	}
}
