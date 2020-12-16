<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * App\Models\StoreGroup
 *
 * @property int $id
 * @property string $group_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Store[] $stores
 * @property-read int|null $stores_count
 * @method static \Illuminate\Database\Eloquent\Builder|StoreGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|StoreGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreGroup whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StoreGroup whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class StoreGroup extends Model
{
    protected $guarded = ['id'];

    public function getAdminAttributes(): array
    {
        return collect($this->getAttributes())
            ->only([
                'id',
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

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($model) {
            foreach ($model->stores as $child) {
                $child->delete();
            }
        });
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }
}
