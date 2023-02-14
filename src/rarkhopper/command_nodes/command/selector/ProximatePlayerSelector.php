<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use pocketmine\player\Player;
use const PHP_INT_MAX;

final class ProximatePlayerSelector extends SelectorBase{
	public static function getIdentifier() : string{
		return 'p';
	}

	public function selectEntities(Player $executor) : array{
		$targetDistance = PHP_INT_MAX;
		$target = null;

		foreach($this->filterEntities($executor->getWorld()->getEntities()) as $entity){
			if(!$entity instanceof Player) continue;
			$distance = $executor->getPosition()->distance($entity->getPosition());

			if($distance > $targetDistance) continue;
			$targetDistance = $distance;
			$target = $entity;
		}

		if($target === null) return [];
		return [$target];
	}
}
