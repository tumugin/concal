<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * App\Models\CastAttend
 *
 * @property int $id
 * @property int $cast_id
 * @property int $store_id
 * @property \Illuminate\Support\Carbon $start_time
 * @property \Illuminate\Support\Carbon $end_time
 * @property string $attend_info
 * @property int $added_by_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Cast $cast
 * @property-read \App\Models\Store $store
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend query()
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend whereAddedByUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend whereAttendInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend whereCastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend whereStoreId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CastAttend whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CastAttend extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
        'start_time',
        'end_time',
    ];

    public function getAdminAttributes(): array
    {
        return collect($this->getAttributes())
            ->only([
                'id',
                'cast_id',
                'store_id',
                'attend_info',
                'added_by_user_id',
            ])
            ->merge([
                'start_time' => Carbon::make($this->start_time)->toIso8601String(),
                'end_time' => Carbon::make($this->end_time)->toIso8601String(),
            ])
            ->mapWithKeys(fn($value, string $key) => [
                Str::camel($key) => $value
            ])
            ->all();
    }

    public function getUserAttributes(): array
    {
        return collect($this->getAttributes())
            ->only([
                'id',
                'cast_id',
                'store_id',
                'attend_info',
            ])
            ->merge([
                'start_time' => Carbon::make($this->start_time)->toIso8601String(),
                'end_time' => Carbon::make($this->end_time)->toIso8601String(),
            ])
            ->mapWithKeys(fn($value, string $key) => [
                Str::camel($key) => $value
            ])
            ->all();
    }

    /**
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * @return BelongsTo
     */
    public function cast(): BelongsTo
    {
        return $this->belongsTo(Cast::class);
    }
}
