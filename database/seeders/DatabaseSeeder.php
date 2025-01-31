<?php

namespace Database\Seeders;

use App\Models\EventStore;
use App\Models\Order;
use App\Models\Orderitem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        Order::factory(10)->create();

        Product::factory(10)->create();

        Orderitem::factory(50)->create();

        Payment::factory(50)->create();

        EventStore::factory(50)->create();
    }
}
