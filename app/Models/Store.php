<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Cast[] $casts
 * @property-read int|null $casts_count
 * @property-read \App\Models\StoreGroup $storeGroup
 * @method static Builder|Store active()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CastAttend[] $castAttends
 * @property-read int|null $cast_attends_count
 */
class Store extends Model
{
    /**
     * 閉店していない営業中の店舗を返す
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('store_disabled', '=', 'false');
    }

    public function getAdminAttributes(): array
    {
        return collect($this->getAttributes())
            ->only([
                'id',
                'store_name',
                'store_group_id',
                'store_disabled',
            ])
            ->merge([
                'store_disabled' => $this->store_disabled === 1,
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

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            foreach ($model->castAttends->get() as $child) {
                $child->delete();
            }
        });
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
     * この店舗への出勤を取得する
     */
    public function castAttends(): HasMany
    {
        return $this->hasMany(CastAttend::class);
    }

    /**
     * 在籍しているキャストを取得する
     */
    public function casts(): BelongsToMany
    {
        return $this->belongsToMany(Cast::class, StoreCast::class);
    }

    /**
     * 所属している店舗のグループを取得する
     */
    public function storeGroup(): BelongsTo
    {
        return $this->belongsTo(StoreGroup::class);
    }
}
