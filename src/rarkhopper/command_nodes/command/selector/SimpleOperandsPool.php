<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command\selector;

use function filter_var;
use const FILTER_VALIDATE_BOOLEAN;
use const FILTER_VALIDATE_FLOAT;
use const FILTER_VALIDATE_INT;

final class SimpleOperandsPool implements IOperandsPool{

	/** @var array<string, string> */
	private array $pool = [];

	public function pool(string $key, string $strOperand) : void{
		$this->pool[$key] = $strOperand;
	}

	public function getInt(string $key) : ?int{
		$op = $this->pool[$key] ?? null;

		if($op === null) return null;
		if(filter_var($op, FILTER_VALIDATE_INT) === false) return null;
		return (int) $op;
	}

	public function getFloat(string $key) : ?float{
		$op = $this->pool[$key] ?? null;

		if($op === null) return null;
		if(filter_var($op, FILTER_VALIDATE_FLOAT) === false) return null;
		return (float) $op;
	}

	public function getString(string $key) : ?string{
		return $this->pool[$key] ?? null;
	}

	public function getBool(string $key) : ?bool{
		$op = $this->pool[$key] ?? null;

		if($op === null) return null;
		if(filter_var($op, FILTER_VALIDATE_BOOLEAN) === false) return null;
		return (bool) $op;
	}
}
