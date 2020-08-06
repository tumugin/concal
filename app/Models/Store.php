<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Store
 *
 * @property int $store_id
 * @property string $store_name
 * @property int $store_group_id
 * @property int $store_disabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereStoreDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereStoreGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereStoreName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Store extends Model
{
    protected $primaryKey = 'store_id';
}
