<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use rarkhopper\cmdnodes\exception\CmdNodesException;
use rarkhopper\cmdnodes\utils\ICmdNodesCommandMap;
use rarkhopper\cmdnodes\utils\ICommandToDataParser;
use rarkhopper\cmdnodes\utils\SimpleCmdNodesCommandMap;
use rarkhopper\cmdnodes\utils\SimpleCommandToDataParser;

final class CmdNodes implements PluginOwned{
	use SingletonTrait; //virionなのでプラグインごとにインスタンスが生成される

	private ?Plugin $owner = null;
	private ICmdNodesCommandMap $cmdMap;
	private ICommandToDataParser $parser;

	public function __construct(){
		$this->cmdMap = new SimpleCmdNodesCommandMap();
		$this->parser = new SimpleCommandToDataParser();
	}

	/**
	 * @throws CmdNodesException
	 */
	public function registerOwner(Plugin $owner) : void{
		if($this->isRegistered()) throw new CmdNodesException('already registered owner. given ' . $owner->getName());
		$this->owner = $owner;
		Server::getInstance()->getPluginManager()->registerEvents(new SendCommandDataListener(), $owner);
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

	public function getCommandMap() : ICmdNodesCommandMap{
		return $this->cmdMap;
	}

	public function getParser() : ICommandToDataParser{
		return $this->parser;
	}
}
