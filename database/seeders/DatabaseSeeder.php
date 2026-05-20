<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
		SectorSeeder::class,
		AsientoSeeder::class,
		UserSeeder::class,
		EventoSeeder::class,
		PrecioSeeder::class,
	]);


	$this->command->info('');
	$this->command->info('Base de datos poblada correctamente');


    }
}
	
