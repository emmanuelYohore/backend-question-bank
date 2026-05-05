<?php

namespace Database\Seeders;

use App\Models\BankItem;
use Illuminate\Database\Seeder;

class BankItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BankItem::factory()->count(50)->create();
    }
}
