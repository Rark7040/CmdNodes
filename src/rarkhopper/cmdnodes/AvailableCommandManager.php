<?php

declare(strict_types=1);

namespace rarkhopper\cmdnodes;

use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use rarkhopper\cmdnodes\command\CommandBase;
use rarkhopper\cmdnodes\command\SubCommandBase;
use function count;
use function in_array;
use function strtolower;
use function ucfirst;

class AvailableCommandManager {
	use SingletonTrait;

	/** @var array<CommandDataCache> */
	private array $commandDataCaches = [];

	public function __construct() {
		$this->loadCommands();
	}

	/**
	 * @return array<CommandData>
	 */
	public function getCommandData(Player $player) : array {
		$commandData = [];
		foreach ($this->commandDataCaches as $commandDataCache) {
			if ($commandDataCache->getCommand()->testPermissionSilent($player)) {
				$commandData[] = $commandDataCache->getCommandData($player);
			}
		}
		return $commandData;
	}

	public function loadCommands() : void {
		foreach (Server::getInstance()->getCommandMap()->getCommands() as $command) {
			$commandName = strtolower($command->getName());
			$description = $command->getDescription();

			if ($description instanceof Translatable) {
				$description = Server::getInstance()->getLanguage()->translate($description);

			} else {
				$description = Server::getInstance()->getLanguage()->translateString($description);
			}
			$commandEnum = null;
			$aliases = $command->getAliases();
			if (count($aliases) > 0) {
				if (!in_array($commandName, $aliases, true)) {
					$aliases[] = $commandName;
				}
				$commandEnum = new CommandEnum(ucfirst($commandName) . ' Alias', $aliases);
			}
			$this->commandDataCaches[$commandName] = new CommandDataCache($command, new CommandData($commandName, $description, 0, 0, $commandEnum, []));
		}
	}
}
