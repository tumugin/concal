<?php

namespace Tests\Unit\StoreAndCast;

use App\Models\Cast;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CastTest extends TestCase
{
    use RefreshDatabase;

    public function testAddCast(): void
    {
        $data_array = [
            'cast_name' => 'アム・S・ミカエル',
            'cast_short_name' => 'アム',
            'cast_twitter_id' => 'amu_afilia',
            'cast_description' => 'アムらしく頑張っていくので仲良くしてください♡下界のアニメが大好きです！',
            'cast_color' => '#FEEEED',
        ];
        Cast::addCast($data_array);
        $this->assertDatabaseHas('casts', $data_array);
    }

    public function testDeleteCast(): void
    {
        $cast = factory(Cast::class)->create();
        $this->assertDatabaseCount('casts', 1);
        $cast->deleteCast();
        $this->assertDatabaseCount('casts', 0);
    }

    public function testGraduateCast(): void
    {
        $cast = factory(Cast::class)->create();
        $cast->graduateCast();
        $this->assertDatabaseCount('casts', 1);
        $this->assertDatabaseHas('casts', [
            'cast_disabled' => 1,
        ]);
    }

    public function testEnrollToStore(): void
    {
        $store = factory(Store::class)->create();
        $cast = factory(Cast::class)->create();
        $cast->enrollToStore($store);
        $this->assertDatabaseHas('store_casts', [
            'store_id' => $store->id,
            'cast_id' => $cast->id,
        ]);
    }

    public function testAddAttendance(): void
    {
        $cast = factory(Cast::class)->create();
        $store = factory(Store::class)->create();
        $user = factory(User::class)->create();
        $start_time = Carbon::parse('2020/08/20 17:00');
        $end_time = Carbon::parse('2020/08/20 23:00');
        $attend_info = 'カウンター屋さん';
        $cast->addAttendance(
            $store,
            $user,
            $start_time,
            $end_time,
            $attend_info
        );
        $this->assertDatabaseHas('cast_attends', [
            'cast_id' => $cast->id,
            'store_id' => $store->id,
            'start_time' => $start_time->toDateTimeString(),
            'end_time' => $end_time->toDateTimeString(),
            'added_by_user_id' => $user->id,
            'attend_info' => $attend_info,
        ]);
    }

    public function testUpdateAttendance(): void
    {
        $cast = factory(Cast::class)->create();
        $added_attendance = $cast->addAttendance(
            factory(Store::class)->create(),
            factory(User::class)->create(),
            Carbon::parse('2020/08/20 17:00'),
            Carbon::parse('2020/08/20 23:00'),
            'test'
        );
        $store = factory(Store::class)->create();
        $user = factory(User::class)->create();
        $start_time = Carbon::parse('2020/08/30 17:00');
        $end_time = Carbon::parse('2020/08/30 23:00');
        $attend_info = 'カウンター屋さん';
        $added_attendance->updateAttendance(
            $store->id,
            $user->id,
            $start_time,
            $end_time,
            $attend_info
        );
        $this->assertDatabaseHas('cast_attends', [
            'cast_id' => $cast->id,
            'store_id' => $store->id,
            'start_time' => $start_time->toDateTimeString(),
            'end_time' => $end_time->toDateTimeString(),
            'added_by_user_id' => $user->id,
            'attend_info' => $attend_info,
        ]);
    }

    public function testUpdateCast(): void
    {
        $cast = factory(Cast::class)->create();
        $cast_info = [
            'cast_name' => 'ジェニファー・オブザ・ワールド',
            'cast_short_name' => 'ジェニファー',
            'cast_twitter_id' => 'jennifer_afilia',
            'cast_description' => 'ジェニファー・オブザ・ワールドです♪',
            'cast_color' => '#F69896',
        ];
        $cast->updateCast($cast_info);
        $this->assertDatabaseHas('casts', $cast_info);
    }
}
