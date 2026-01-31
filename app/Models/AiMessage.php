<?php

namespace App\Models;

use App\Casts\MessageContentCast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiMessage extends Model
{
    protected $fillable = [
		'conversation_id',
		'direction',
		'message_content',
		'message_type',
	];

	protected $casts = [
		'message_content' => MessageContentCast::class,
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<AiConversation, $this>
	 */
	public function conversation(): BelongsTo
	{
		return $this->belongsTo(AiConversation::class, 'conversation_id', 'conversation_id');
	}
}
