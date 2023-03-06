<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_getting_items(): void
    {
        Product::factory()->amazon()->count(3)->create();
        Product::factory()->example()->count(4)->create();
        Product::factory()->steam()->count(1)->create();

        $response = $this->getJson('/api/products');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('products')->etc();
            $json->has('products.0', function (AssertableJson $json) {
                $json
                    ->whereType('id', 'integer')
                    ->whereType('name', 'string')
                    ->whereType('url', 'string')
                    ->whereType('price', 'string')
                    ->whereType('description', 'string');
            });
        });
    }

    public function test_getting_single_item(): void
    {
        $attributes = [
            'name' => 'Test item',
            'price' => 12300.45,
            'url' => 'https://example.store/876446446',
            'description' => 'Test description',
        ];

        $item = Product::factory()->create($attributes);

        $response = $this->getJson('/api/products/'.$item->id);

        $response->assertStatus(200);

        $responseItem = $response->json()['product'];

        $this->assertSame($item->id, $responseItem['id']);
        $this->assertSame($attributes['name'], $responseItem['name']);
        $this->assertSame('12 300.45', $responseItem['price']);
        $this->assertSame($attributes['url'], $responseItem['url']);
        $this->assertSame($attributes['description'], $responseItem['description']);
    }

    public function test_creating_new_item_with_valid_data(): void
    {
        $response = $this->postJson('/api/products', [
            'name' => 'New item',
            'price' => 12345,
            'url' => 'https://store.example.com/my-product',
            'description' => 'Test **item** description',
        ]);

        $this->assertSame('New item', $response->json()['product']['name']);

        $this->assertDatabaseHas(Product::class, [
            'name' => 'New item',
            'price' => 12345,
            'url' => 'https://store.example.com/my-product',
            'description' => "<p>Test <strong>item</strong> description</p>\n",
        ]);
    }

    public function test_creating_new_item_with_invalid_data(): void
    {
        $response = $this->postJson('/api/products', [
            'name' => 'New item',
            'price' => 'string',
            'url' => 'invalid url',
            'description' => 'Test item description',
        ]);

        $response->assertStatus(422);
    }

    public function test_updating_item_with_valid_data(): void
    {
        $item = Product::factory()->create();

        $response = $this->putJson('/api/products/ '.$item->id, [
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'https://store.example.com/my-other-product',
            'description' => 'Test _item_ description',
        ]);

        $this->assertSame('Updated title', $response->json()['product']['name']);
        $this->assertSame(
            '<p>Test <em>item</em> description</p>',
            $response->json()['product']['description']
        );

        $this->assertDatabaseHas(Product::class, [
            'id' => $item->id,
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'https://store.example.com/my-other-product',
            'description' => "<p>Test <em>item</em> description</p>\n",
        ]);
    }

    public function test_updating_item_with_invalid_data(): void
    {
        $item = Product::factory()->create();

        $response = $this->putJson('/api/products/ '.$item->id, [
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'invalid url',
            'description' => 'Test item description',
        ]);

        $response->assertStatus(422);
    }
}
