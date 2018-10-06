<?php

namespace Tests\Feature\Api;

use App\Item;
use Tests\TestCase;

class ItemFeatureTest extends TestCase
{
    public function testsItemsAreCreatedCorrectly()
    {
        $payload = [
            'name' => 'Create Item',
            'key' => 'createkey',
        ];

        $this->json('POST', '/api/v1/item', $payload)
            ->assertStatus(200);
    }

    /** @test */
    public function testItemsAreListedCorrectly()
    {
        $response = $this->get('api/v1/item');

        $response->assertStatus(200);
    }

    public function testItemIsListedCorrectly()
    {
        $item = factory(Item::class)->create([
            'name' => 'Show Item',
            'key' => 'showkey',
        ]);

        $this->get('/api/v1/item/' . $item->id)
            ->assertStatus(200);

    }

    public function testsItemsAreUpdatedCorrectly()
    {
        $item = factory(Item::class)->create([
            'name' => 'Update Item',
            'key' => 'updatekey',
        ]);

        $payload = [
            'name' => 'Update Item '.$item->id,
            'key' => 'updatekey'.$item->id,
        ];

        $this->json('PUT', '/api/v1/item/' . $item->id, $payload)
            ->assertStatus(200);
    }

    public function testsItemsAreDeletedCorrectly()
    {
        $item = factory(Item::class)->create([
            'name' => 'Some Item',
            'key' => 'somekey',
        ]);

        $this->json('DELETE', '/api/v1/item/' . $item->id)
            ->assertStatus(200);
    }
}