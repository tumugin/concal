<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

/**
 * App\Models\Store
 *
 * @property int $id
 * @property string $store_name
 * @property int $store_group_id
 * @property int $store_disabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereStoreDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereStoreGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereStoreName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Store whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Store extends Model
{
    public function getAdminAttributes(): array
    {
        return collect($this->getAttributes())
            ->only([
                'id',
                'store_name',
                'store_group_id',
                'store_disabled',
            ])
            ->mergeRecursive([
                'cast_disbled' => $this->store_disabled === 1,
            ])
            ->mapWithKeys(fn($value, string $key) => [
                Str::camel($key) => $value
            ])
            ->all();
    }

    public function getUserAttributes(): array
    {
        // 隠す必要のある属性がないのでそのまま返す
        return $this->getAdminAttributes();
    }

    /**
     * キャストをこの店舗に在籍させる
     *
     * @param Cast $cast
     */
    public function enrollCast(Cast $cast): void
    {
        $store_cast = new StoreCast();
        $store_cast->cast_id = $cast->id;
        $store_cast->store_id = $this->id;
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
     * @return StoreGroup
     */
    public function getBelongingStoreGroup(): StoreGroup
    {
        return StoreGroup::whereId($this->store_group_id)->firstOrFail();
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
        Assert::stringNotEmpty($store_name);

        $store = new Store();
        $store->store_name = $store_name;
        $store->store_group_id = $store_group->id;
        $store->store_disabled = 0;
        $store->save();

        return $store;
    }

    /**
     * 店舗情報を更新する
     *
     * @param string $store_name
     * @param StoreGroup $store_group
     */
    public function updateStore(string $store_name, StoreGroup $store_group): void
    {
        Assert::stringNotEmpty($store_name);

        $this->store_name = $store_name;
        $this->store_group_id = $store_group->id;
        $this->save();
    }
}
