<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\update;

trait UpdatableTrait{
	private bool $hasUpdate = true;

	public function setUpdate(bool $update = true) : void{
		$this->hasUpdate = $update;
	}

	public function hasUpdate() : bool{
		return $this->hasUpdate;
	}
}