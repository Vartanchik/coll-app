<?php

namespace Database\Seeders;

use App\Models\Collection;
use App\Models\Contributor;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Collection::factory(10)->create()->each(
            function ($collection) {
                $contributors = Contributor::factory(5)->make();
                $collection->contributors()->saveMany($contributors);
            }
        );
    }
}
