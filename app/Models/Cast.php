<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Cast
 *
 * @property int $cast_id
 * @property string $cast_name
 * @property string $cast_short_name
 * @property string $cast_twitter_id
 * @property string $cast_description
 * @property string $cast_color
 * @property int $cast_disabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastDisabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastShortName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCastTwitterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Cast whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Cast extends Model
{
    protected $primaryKey = 'cast_id';
}
