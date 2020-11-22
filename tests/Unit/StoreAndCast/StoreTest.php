<?php

namespace Tests\Unit\StoreAndCast;

use App\Models\Cast;
use App\Models\Store;
use App\Models\StoreGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function testEnrollCast(): void
    {
        $store = factory(Store::class)->create();
        $cast = factory(Cast::class)->create();
        $store->enrollCast($cast);
        $this->assertDatabaseHas('store_casts', [
            'cast_id' => $cast->id,
            'store_id' => $store->id,
        ]);
    }

    public function testGetBelongingCasts(): void
    {
        $store = factory(Store::class)->create();
        $cast = factory(Cast::class)->create();
        $store->enrollCast($cast);
        $belonging_casts = $store->casts();
        $this->assertEquals(1, $belonging_casts->count());
        $this->assertEquals(
            $cast->getAttributes(),
            $belonging_casts->first()->getAttributes()
        );
    }

    public function testGetBelongingStoreGroup(): void
    {
        $store_group = factory(StoreGroup::class)->create();
        $store = factory(Store::class)->create();
        $store->store_group_id = $store_group->id;
        $store->save();
        $this->assertEquals(
            $store_group->getAttributes(),
            $store->storeGroup()->first()->getAttributes(),
        );
    }
}
