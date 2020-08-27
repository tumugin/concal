<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

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
    public function getAdminAttributes(): array
    {
        return collect($this->getAttributes())
            ->only([
                'store_group_id',
                'group_name',
            ])
            ->mapWithKeys(fn($value, string $key) => [
                Str::camel($key) => $value
            ])
            ->all();
    }

    public function getUserAttributes(): array
    {
        return $this->getAdminAttributes();
    }

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
