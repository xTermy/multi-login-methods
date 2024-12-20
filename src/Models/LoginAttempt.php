<?php

namespace StormCode\MultiLoginMethods\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Config;

class LoginAttempt extends Model
{
    protected $table = 'login_attempts'; // Powiązanie z tabelą
    protected $fillable = [
        'user_id',
        'tries',
        'method',
        'code',
        'ip',
    ];
    protected function casts()
    {
        return [
            'user_id' => 'string',
            'tries' => 'integer',
            'method' => 'string',
            'code' => 'string',
            'ip' => 'string',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Config::string('multiLoginMethods.auth_model', App\Models\User::class));
    }

    public function toToken(): string
    {
        return encrypt(['attempt_id' => $this->id, 'user_id' => $this->user_id, 'method' => $this->method]);
    }
}
