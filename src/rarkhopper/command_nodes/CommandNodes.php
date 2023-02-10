<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes;

use pocketmine\event\EventPriority;
use pocketmine\event\HandlerListManager;
use pocketmine\event\RegisteredListener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\Server;
use rarkhopper\command_nodes\utils\ICmdNodesCommandMap;
use rarkhopper\command_nodes\utils\ICommandDataUpdater;
use rarkhopper\command_nodes\utils\ICommandToDataParser;
use rarkhopper\command_nodes\utils\SimpleCmdNodesCommandMap;
use rarkhopper\command_nodes\utils\SimpleCommandDataUpdater;
use rarkhopper\command_nodes\utils\SimpleCommandToDataParser;
use ReflectionException;

final class CommandNodes implements PluginOwned{
	private ?RegisteredListener $registeredListener = null;

	public function __construct(
		private Plugin $owner,
		private ICmdNodesCommandMap $cmdMap,
		private ICommandToDataParser $parser,
		private ICommandDataUpdater $updater
	){
		$this->cmdMap = new SimpleCmdNodesCommandMap();
		$this->parser = new SimpleCommandToDataParser();
		$this->updater = new SimpleCommandDataUpdater();
	}

	public static function create(Plugin $owner) : CommandNodes{
		return new self(
			$owner,
			new SimpleCmdNodesCommandMap(),
			new SimpleCommandToDataParser(),
			new SimpleCommandDataUpdater()
		);
	}

	/**
	 * @throws ReflectionException
	 */
	public function enableCommandPacketOverwrite() : void{
		$logger = Server::getInstance()->getLogger();

		if($this->registeredListener !== null){
			$logger->warning('listener is already enabled, but trying enable in ' . $this::class);
			return;
		}
		$this->registeredListener = Server::getInstance()->getPluginManager()->registerEvent(
		DataPacketSendEvent::class,
			function(DataPacketSendEvent $ev) : void{
				foreach($ev->getTargets() as $target){
					$player = $target->getPlayer();

					if($player === null) continue;
					foreach($ev->getPackets() as $pk){
						if(!$pk instanceof AvailableCommandsPacket) continue;
						if(!$this->cmdMap->needsUpdate()) return;
						$this->cmdMap->unsetUpdateFlags();
						$this->updater->overwrite(
							$pk,
							$this->parser,
							$player
						);
					}
				}
			},
			EventPriority::NORMAL,
			$this->owner
		);
		$logger->debug('enabled listener in ' . $this::class);
	}

	public function isEnabled() : bool{
		return $this->registeredListener !== null;
	}

	public function disableCommandPacketOverwrite() : void{
		$server = Server::getInstance();
		$logger = $server->getLogger();

		if($this->registeredListener === null){
			$logger->warning('listener is not yet enabled, but trying disable in ' . $this::class);
			return;
		}
		HandlerListManager::global()->unregisterAll($this->registeredListener);
		$logger->debug('disabled listener in ' . $this::class);
	}

	public function getOwningPlugin() : Plugin{
		return $this->owner;
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
