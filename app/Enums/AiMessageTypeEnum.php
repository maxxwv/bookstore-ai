<?php

namespace App\Enums;

enum AiMessageTypeEnum: int
{
	case human = 0;
	case ai = 1;
	case system = 2;
	case tool = 3;
	case function = 4;

	public function messageType(): string
	{
		return match($this){
			self::human => 'UserMessage',
			self::ai => 'AssistantMessage',
			self::system => 'SystemMessage',
			self::tool => 'ToolResultMessage',
			default => 'UnknownMessage',
		};
	}
}
