<?php

namespace Store;

use App\Models\Store;
use App\Models\StoreGroup;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreGroupTest extends TestCase
{
    use RefreshDatabase;

    public function testAddStoreGroup(): void
    {
        $group_name = 'アフィリア魔法王国';
        StoreGroup::addStoreGroup($group_name);
        $this->assertDatabaseHas('store_groups', [
            'group_name' => $group_name,
        ]);
    }

    public function testUpdateStoreGroupInfo(): void
    {
        $group = factory(StoreGroup::class)->create();
        $new_group_name = 'メイドカフェめいどりーみん';
        $group->updateStoreInfo($new_group_name);
        $this->assertDatabaseHas('store_groups', [
            'group_name' => $new_group_name,
        ]);
    }

    public function testDeleteStoreGroup(): void
    {
        $group = factory(StoreGroup::class)->create();
        // 何も所属店舗がないグループは削除できる
        $group->deleteStoreGroup();
        $this->assertDatabaseCount('store_groups', 0);
    }

    public function testCanNotDeleteStoreGroupWithStore(): void
    {
        $this->expectException('InvalidArgumentException');
        $group = factory(StoreGroup::class)->create();
        Store::createStore(
            '王立アフィリアグランドロッジ',
            $group
        );
        // 所属店舗があるグループは削除できない
        $group->deleteStoreGroup();
    }
}
