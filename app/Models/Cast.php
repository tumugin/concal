<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

/**
 * App\Models\Cast
 *
 * @property int $id
 * @property string $cast_name
 * @property string|null $cast_short_name
 * @property string|null $cast_twitter_id
 * @property string $cast_description
 * @property string|null $cast_color
 * @property int $cast_disabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CastAttend[] $castAttends
 * @property-read int|null $cast_attends_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\StoreCast[] $storeCasts
 * @property-read int|null $store_casts_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastTwitterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Store[] $stores
 * @property-read int|null $stores_count
 * @method static Builder|Cast active()
 */
class Cast extends Model
{
    const CAST_COLOR_REGEX = '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/';

    /**
     * 現役のキャストを返す
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('cast_disabled', '=', false);
    }

    public function getAdminAttributes(): array
    {
        return collect($this->getAttributes())
            ->only([
                'id',
                'cast_name',
                'cast_short_name',
                'cast_twitter_id',
                'cast_description',
                'cast_color',
                'cast_disabled',
            ])
            ->merge([
                'cast_disabled' => $this->cast_disabled === 1,
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

    public function castAttends(): HasMany
    {
        return $this->hasMany(CastAttend::class);
    }

    public function storeCasts(): HasMany
    {
        return $this->hasMany(StoreCast::class);
    }

    public function stores(): BelongsToMany
    {
        return $this->belongsToMany(Store::class, StoreCast::class);
    }
}
