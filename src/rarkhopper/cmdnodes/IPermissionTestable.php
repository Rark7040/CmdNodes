<?php

declare(strict_types = 1);
namespace rarkhopper\cmdnodes;

use pocketmine\command\CommandSender;

interface IPermissionTestable{
	public function setPermission(?string $permission) : void;

	public function testPermission(CommandSender $target, ?string $permission = null) : bool;
}
