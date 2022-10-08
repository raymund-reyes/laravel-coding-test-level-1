<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;
    use UUID;

    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';

    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [
        'updatedAt',
        'deletedAt'
    ];


    public function scopeActive($query) {
        return $query->where('startAt', '<=', Carbon::now())
                ->where('endAt', '>=', Carbon::now());
    }
}
