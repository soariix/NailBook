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
        // Criar usuário admin
        User::create([
            'name' => 'Admin NailBook',
            'email' => 'admin@nailbook.com',
            'password' => bcrypt('senha123')
        ]);

        // Chamar seeder de serviços
        $this->call([
            ServicosSeeder::class,
        ]);
    }
}
