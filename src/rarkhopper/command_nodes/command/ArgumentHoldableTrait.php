<?php

declare(strict_types=1);

namespace rarkhopper\command_nodes\command;

use rarkhopper\command_nodes\command\params\ICommandParameter;

/**
 * @implements IArgumentHoldable<mixed>
 */
trait ArgumentHoldableTrait{

	/**
	 * @param array<string>            $args
	 * @param array<ICommandParameter> $params
	 * @return array<string, mixed>
	 */
	private function parseParameters(array $args, array $params) : array{
		$results = [];

		for($i = 0; isset($params[$i]); ++$i){
			$arg = $args[$i] ?? null;
			$param = $params[$i];

			if($arg === null){
				if(!$param->isOptional()){
					return []; //TODO: throw exception
				}
				return $results;
			}
			$results[$param->getLabel()] = $param->parseArgument($arg); //TODO: result type
		}
		return $results;
	}
}
