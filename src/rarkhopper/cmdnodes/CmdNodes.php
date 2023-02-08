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

	/**
	 * @throws CmdNodesException
	 */
	public function registerOwner(Plugin $owner) : void{
		if($this->isRegistered()) throw new CmdNodesException('already registered owner. given ' . $owner->getName());
		$this->owner = $owner;
		Server::getInstance()->getPluginManager()->registerEvents(new CmdNodesListener(), $owner);
	}

	public function isRegistered() : bool{
		return $this->owner !== null;
	}

	/**
	 * @throws CmdNodesException
	 */
	public function getOwningPlugin() : Plugin{
		return $this->owner ?? throw new CmdNodesException('not yet registered owner');
	}
}
