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
use rarkhopper\command_nodes\command\ICmdNodesCommandMap;
use rarkhopper\command_nodes\command\selector\IStringToSelectorParser;
use rarkhopper\command_nodes\command\selector\SimpleStringToSelectorParser;
use rarkhopper\command_nodes\command\selector\validator\SimpleStringToValidatorParser;
use rarkhopper\command_nodes\command\SimpleCmdNodesCommandMap;
use rarkhopper\command_nodes\utils\ICommandToNetworkDataParser;
use rarkhopper\command_nodes\utils\INetworkCommandDataUpdater;
use rarkhopper\command_nodes\utils\SimpleCommandToNetworkDataParser;
use rarkhopper\command_nodes\utils\SimpleNetworkCommandDataUpdater;
use ReflectionException;

final class CommandNodes implements PluginOwned{
	private ?RegisteredListener $registeredListener = null;
	private ICmdNodesCommandMap $cmdMap;
	private ICommandToNetworkDataParser $parser;
	private INetworkCommandDataUpdater $updater;
	private IStringToSelectorParser $selectorParser;

	/**
	 * @param Plugin                           $owner   呼び出し元のプラグイン
	 * @param ICmdNodesCommandMap|null         $cmdMap  nullが指定された場合、{@see SimpleCmdNodesCommandMap}が代入されます
	 * @param ICommandToNetworkDataParser|null $parser  nullが指定された場合、{@see SimpleCommandToNetworkDataParser}が代入されます
	 * @param INetworkCommandDataUpdater|null  $updater nullが指定された場合、{@see SimpleNetworkCommandDataUpdater}が代入されます
	 */
	public function __construct(
		private Plugin $owner,
		?ICmdNodesCommandMap $cmdMap = null,
		?ICommandToNetworkDataParser $parser = null,
		?INetworkCommandDataUpdater $updater = null,
		?IStringToSelectorParser $selectorParser = null
	){
		$this->cmdMap = $cmdMap ?? new SimpleCmdNodesCommandMap();
		$this->parser = $parser ?? new SimpleCommandToNetworkDataParser();
		$this->updater = $updater ?? new SimpleNetworkCommandDataUpdater();
		$this->selectorParser = $selectorParser ?? new SimpleStringToSelectorParser(
			new SimpleStringToValidatorParser()
		);
	}

	/**
	 * {@see AvailableCommandsPacket}を上書きをするハンドラーを有効化します
	 * 有効化されなかった場合、コマンドの引数の補完がされません
	 *
	 * @throws ReflectionException
	 */
	public function enable() : void{
		$logger = Server::getInstance()->getLogger();

		if($this->registeredListener !== null){
			$logger->warning('listener is already enabled, but trying enable in ' . self::class);
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
		$logger->debug('enabled listener in ' . self::class);
	}

	/**
	 * {@see AvailableCommandsPacket}を上書きをするハンドラーを無効化します
	 */
	public function disable() : void{
		$server = Server::getInstance();
		$logger = $server->getLogger();

		if($this->registeredListener === null){
			$logger->warning('listener is not yet enabled, but trying disable in ' . $this::class);
			return;
		}
		HandlerListManager::global()->unregisterAll($this->registeredListener);
		$logger->debug('disabled listener in ' . $this::class);
	}

	/**
	 * @return bool {@see AvailableCommandsPacket}を上書きをするハンドラーが有効であるか否か
	 */
	public function isEnabled() : bool{
		return $this->registeredListener !== null;
	}

	public function getOwningPlugin() : Plugin{
		return $this->owner;
	}

	public function getCommandMap() : ICmdNodesCommandMap{
		return $this->cmdMap;
	}

	public function getParser() : ICommandToNetworkDataParser{
		return $this->parser;
	}

	public function getUpdater() : INetworkCommandDataUpdater{
		return $this->updater;
	}

	public function getSelectorParser() : IStringToSelectorParser{
		return $this->selectorParser;
	}
}
