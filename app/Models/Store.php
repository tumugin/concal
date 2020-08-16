<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /**
     * キャストをこの店舗に在籍させる
     *
     * @param Cast $cast
     */
    public function enrollCast(Cast $cast): void
    {
        $store_cast = new StoreCast();
        $store_cast->cast_id = $cast->cast_id;
        $store_cast->store_id = $this->store_id;
        $store_cast->save();
    }

    /**
     * 在籍しているキャストを取得する
     */
    public function getBelongingCasts(): BelongsToMany
    {
        return $this->belongsToMany(Cast::class, StoreCast::class);
    }

    /**
     * 店舗を閉店/開店させる
     *
     * @param bool $is_store_closed 店舗が閉店しているかどうか
     */
    public function setStoreClosed(bool $is_store_closed): void
    {
        $this->store_disabled = $is_store_closed;
        $this->save();
    }

    /**
     * 所属している店舗のグループを取得する
     *
     * @return StoreGroup|HasOne
     */
    public function getBelongingStoreGroup(): HasOne
    {
        return $this->hasOne(StoreGroup::class);
    }

    /**
     * 店舗を新しく作る
     *
     * @param string $store_name 店舗名
     * @param StoreGroup $store_group 店舗グループ
     * @return Store
     */
    public static function createStore(string $store_name, StoreGroup $store_group): Store
    {
        $store = new Store();
        $store->store_name = $store_name;
        $store->store_group_id = $store_group->store_group_id;
        $store->store_disabled = 0;
        $store->save();

        return $store;
    }
}
