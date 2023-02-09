<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use rarkhopper\command_nodes\exception\CmdNodesException;
use rarkhopper\command_nodes\utils\ICmdNodesCommandMap;
use rarkhopper\command_nodes\utils\ICommandDataUpdater;
use rarkhopper\command_nodes\utils\ICommandToDataParser;
use rarkhopper\command_nodes\utils\SimpleCmdNodesCommandMap;
use rarkhopper\command_nodes\utils\SimpleCommandDataUpdater;
use rarkhopper\command_nodes\utils\SimpleCommandToDataParser;

final class CommandNodes implements PluginOwned{
	use SingletonTrait; //virionなのでプラグインごとにインスタンスが生成される

	private ?Plugin $owner = null;
	private ICmdNodesCommandMap $cmdMap;
	private ICommandToDataParser $parser;
	private ICommandDataUpdater $updater;

	public function __construct(){
		$this->cmdMap = new SimpleCmdNodesCommandMap();
		$this->parser = new SimpleCommandToDataParser();
		$this->updater = new SimpleCommandDataUpdater();
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

	public function getUpdater() : ICommandDataUpdater{
		return $this->updater;
	}
}
