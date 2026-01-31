<?php

namespace App\Models;

use App\Models\AiMessage;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $conversation_id
 */
class AiConversation extends Model
{
	use HasUuids;

	protected $primaryKey = 'conversation_id';

    protected $fillable = [
		'id',
		'model',
		'handoff_date',
	];

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany<AiMessage, $this>
	 */
	public function messages(): HasMany
	{
		return $this->hasMany(AiMessage::class, 'conversation_id', 'conversation_id');
	}
}
