<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webmozart\Assert\Assert;

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
    public static function addStoreGroup(string $group_name): StoreGroup
    {
        Assert::stringNotEmpty($group_name);

        $store_group = new StoreGroup();
        $store_group->group_name = $group_name;
        $store_group->save();

        return $store_group;
    }

    /**
     * 店舗グループ情報を更新する
     *
     * @param string $group_name 店舗グループ名
     */
    public function updateStoreInfo(string $group_name): void
    {
        Assert::stringNotEmpty($group_name);

        $this->group_name = $group_name;
        $this->save();
    }

    /**
     * 店舗グループを削除する
     *
     * 所属している店舗が無い時のみ削除が行える
     */
    public function deleteStoreGroup(): void
    {
        Assert::eq(Store::whereStoreGroupId($this->id)->count(), 0);
        $this->delete();
    }
}
