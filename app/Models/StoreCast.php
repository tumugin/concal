<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreCast
 *
 * @property int $store_id
 * @property int $cast_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreCast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreCast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreCast query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreCast whereCastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreCast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreCast whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreCast whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreCast extends Model
{
    protected $primaryKey = ['store_id', 'cast_id'];
    public $incrementing = false;
}
