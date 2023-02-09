<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\utils;

use pocketmine\command\Command;
use pocketmine\lang\Translatable;
use pocketmine\network\mcpe\protocol\types\command\CommandData;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\player\Player;
use pocketmine\Server;
use rarkhopper\command_nodes\command\CommandBase;
use function array_values;
use function count;
use function in_array;
use function strtolower;
use function ucfirst;

final class SimpleCommandToDataParser implements ICommandToDataParser{
	public function parse(Command $cmd, Player $receiver) : CommandData{
		return new CommandData(
			strtolower($cmd->getLabel()),
			$this->getStringDescription($cmd),
			0,
			0,
			$this->getEnum($cmd),
			$this->getOverloads($cmd, $receiver)
		);
	}

	private function getEnum(Command $cmd) : ?CommandEnum{
		$aliases = $cmd->getAliases();

		if(count($aliases) < 1) return null;
		$label = strtolower($cmd->getLabel());

		if(!in_array($label, $aliases, true)){
			$aliases[] = $label;
		}
		return new CommandEnum(
			ucfirst($label) . 'Aliases',
			array_values($aliases)
		);
	}

	private function getStringDescription(Command $cmd) : string{
		$description = $cmd->getDescription();
		$lang = Server::getInstance()->getLanguage();
		return $description instanceof Translatable?
			$lang->translate($description):
			$lang->translateString($description);
	}

	/**
	 * @return CommandParameter[][]
	 */
	private function getOverloads(Command $cmd, Player $receiver) : array{
		if(!$cmd instanceof CommandBase) return [];
		return $cmd->getOverloads($receiver);
	}
}
