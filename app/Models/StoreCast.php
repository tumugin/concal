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
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCast query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCast whereCastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCast whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreCast whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreCast extends Model
{
    protected $primaryKey = ['store_id', 'cast_id'];
    public $incrementing = false;
}
