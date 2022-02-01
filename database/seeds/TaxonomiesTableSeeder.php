<?php

use Illuminate\Database\Seeder;

class TaxonomiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Taxonomy::class, 200 )->create();
    }
}
