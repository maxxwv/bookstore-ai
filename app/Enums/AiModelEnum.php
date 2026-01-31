<?php

namespace App\Enums;

enum AiModelEnum: int
{
    // openai
	case Gpt4oMini = 0;
	case Gpt5Mini = 1;
	// gemini
	case Gemini25FlashLite = 3;

	public function modelName(): string
	{
		return match($this){
			self::Gpt4oMini => 'gpt-4o-mini',
			self::Gpt5Mini => 'gpt-5-mini',
			self::Gemini25FlashLite => 'gemini-2.5-flash-lite',
		};
	}
}
