<?php

namespace Tests\Unit\Cast;

use App\Models\Cast;
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
}
