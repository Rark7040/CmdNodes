<?php

declare(strict_types = 1);
namespace rarkhopper\command_nodes\command;

use pocketmine\command\CommandSender;

interface IPermissionTestable{

	/**
	 * @param string|null $permission 設定するパーミッション
	 */
	public function setPermission(?string $permission) : void;

	/**
	 * @param CommandSender $target     パーミッションの検証をされる対象
	 * @param string|null   $permission 検証に使用するパーミッションであり、null又は空文字列が指定された場合は常に検証結果は真になります
	 * @return bool 検証結果であり、偽であった場合は対象はパーミッションを持っておらず、真であった場合はパーミッションを持っている
	 */
	public function testPermission(CommandSender $target, ?string $permission = null) : bool;
}
