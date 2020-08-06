<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreGroup
 *
 * @property int $store_group_id
 * @property string $group_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup whereStoreGroupId($value)
 * @mixin \Eloquent
 */
class StoreGroup extends Model
{
    protected $primaryKey = 'store_group_id';
}
