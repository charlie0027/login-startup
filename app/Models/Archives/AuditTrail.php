<?php

namespace App\Models\Archives;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class AuditTrail extends Model
{
    // use SoftDeletes;
    use HasFactory, Notifiable;

    protected $guarded = [];

    protected $casts = [
        'from_values' => 'array',
        'to_values'   => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function insertAuditTrail($action, $entity_type, $entity_id, $from, $to)
    {
        $changed_values = $to->getDirty();
        $original_values = $from->getOriginal();

        $from_values = [];
        $to_values   = [];

        foreach ($changed_values as $key => $newValue) {
            $from_values[$key] = array_key_exists($key, $original_values) ? $original_values[$key] : null;
            $to_values[$key]   = $newValue;
        }

        self::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entity_type,
            'entity_id' => $entity_id,
            'from_values' => $from_values,
            'to_values' => $to_values,
            'ip_address' => request()->ip(),
            'user_agent' => request()->header('User-Agent'),
        ]);
    }
}
