<?php

namespace Tests\Feature;

use App\Models\Category;
use Database\Seeders\CategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    public function testInsert()
    {
        $category = new Category();
        $category->id = 'GEDGET';
        $category->name = 'Gedget';
        $result = $category->save();

        $this->assertTrue($result);
    }

    public function testInsertMany()
    {
        $categories = [
            ['id' => 'ELEKTRONIK', 'name' => 'Elektronik'],
            ['id' => 'FASHION', 'name' => 'Fashion'],
            ['id' => 'MUSIC', 'name' => 'Music'],
        ];

        $result = Category::query()->insert($categories);
        $this->assertTrue($result);

        $total = Category::query()->count();
        $this->assertEquals(3, $total);
    }

    public function testFind()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find('FOOD');
        $this->assertNotNull($category);
        $this->assertEquals('FOOD', $category->id);
        $this->assertEquals('Food', $category->name);
        $this->assertEquals('Food category', $category->description);
    }

    public function testUpdate()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find('FOOD');
        $category->name = 'Makanan';
        $category->description = 'Kategori makanan';
        $result = $category->save();

        $this->assertTrue($result);

        $category = Category::query()->find('FOOD');
        $this->assertEquals('Makanan', $category->name);
        $this->assertEquals('Kategori makanan', $category->description);
    }

    public function testSelect()
    {
        $categories = [
            ['id' => 'ELEKTRONIK', 'name' => 'Elektronik'],
            ['id' => 'FASHION', 'name' => 'Fashion'],
            ['id' => 'MUSIC', 'name' => 'Music'],
        ];

        Category::query()->insert($categories);

        $categories = Category::query()->where('id', '=', 'ELEKTRONIK')->get();

        $this->assertNotEmpty($categories);
        $this->assertEquals(1, $categories->count());
        $this->assertEquals('ELEKTRONIK', $categories[0]->id);
        $this->assertEquals('Elektronik', $categories[0]->name);

        foreach ($categories as $category) {
            $category->description = 'Updated description';
            $category->save();
        }

        $categories = Category::query()->where('id', '=', 'ELEKTRONIK')->get();
        $this->assertEquals('Updated description', $categories[0]->description);
    }

    public function testUpdateMany()
    {
        $categories = [];

        for ($i = 1; $i <= 10; $i++) {
            $categories[] = [
                'id' => 'CATEGORY-' . $i,
                'name' => 'Category ' . $i,
            ];
        }
        $result = Category::query()->insert($categories);
        $this->assertTrue($result);

        Category::query()->whereNull('description')->update(['description' => 'Updated description']);
        $total = Category::query()->where('description', '=', 'Updated description')->count();
        $this->assertEquals(10, $total);
    }

    public function testDelete()
    {
        $this->seed(CategorySeeder::class);

        $category = Category::query()->find('FOOD');
        $result = $category->delete();

        $this->assertTrue($result);

        $category = Category::query()->find('FOOD');
        $this->assertNull($category);
    }

    public function testDeleteMany()
    {
        $categories = [];

        for ($i = 1; $i <= 10; $i++) {
            $categories[] = [
                'id' => 'CATEGORY-' . $i,
                'name' => 'Category ' . $i,
            ];
        }
        $result = Category::query()->insert($categories);
        $this->assertTrue($result);

        $total = Category::query()->count();
        $this->assertEquals(10, $total);

        Category::query()->where('id', 'like', 'CATEGORY-%')->delete();
        $total = Category::query()->count();
        $this->assertEquals(0, $total);
    }

    public function testCreate()
    {
        $request = [
            'id' => 'HEALTH',
            'name' => 'Health',
            'description' => 'Health category',
        ];

        $category = new Category($request);
        $result = $category->save();

        $this->assertTrue($result);
    }

    public function testCreateMethod()
    {
        $request = [
            'id' => 'HEALTH',
            'name' => 'Health',
            'description' => 'Health category',
        ];

        $category = Category::query()->create($request);

        $this->assertNotNull($category);
        $this->assertEquals('HEALTH', $category->id);
        $this->assertEquals('Health', $category->name);
        $this->assertEquals('Health category', $category->description);
    }

    public function testFill()
    {
        $this->seed(CategorySeeder::class);

        $request = [
            'name' => 'Food Updated',
            'description' => 'Food category updated',
        ];

        $category = Category::query()->find('FOOD');
        $category->fill($request);
        $result = $category->save();

        $this->assertTrue($result);
    }
}
