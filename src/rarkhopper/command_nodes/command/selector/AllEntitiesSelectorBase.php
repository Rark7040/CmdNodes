<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\player\Player;
use pocketmine\Server;
use function array_merge;

final class AllEntitiesSelectorBase extends SelectorBase{
	public static function getIdentifier() : string{
		return 'e';
	}

	public function selectEntities(Player $executor) : array{
		$entities = [];

		foreach(Server::getInstance()->getWorldManager()->getWorlds() as $world){
			$entities = array_merge($entities, $world->getEntities());
		}
		return $entities;
	}
}
