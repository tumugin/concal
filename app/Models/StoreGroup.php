<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\StoreGroup
 *
 * @property int $id
 * @property string $group_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\StoreGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreGroup extends Model
{
    /**
     * 店舗グループを作成する
     *
     * @param string $group_name 店舗グループ名
     */
    public function addStoreGroup(string $group_name)
    {
        $store_group = new StoreGroup();
        $store_group->group_name = $group_name;
        $store_group->save();
    }
}
