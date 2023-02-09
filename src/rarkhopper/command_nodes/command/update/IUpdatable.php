<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\update;

interface IUpdatable{
	public function setUpdate(bool $update = true) : void;
	public function hasUpdate() : bool;
}