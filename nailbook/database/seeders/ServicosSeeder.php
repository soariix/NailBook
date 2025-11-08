<?php

namespace Database\Seeders;

use App\Models\Servico;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicos = [
            [
                'nome' => 'Manicure',
                'descricao' => 'Cuidados com as unhas das mãos',
                'preco' => 30.00,
                'duracao' => 60
            ],
            [
                'nome' => 'Pedicure',
                'descricao' => 'Cuidados com as unhas dos pés',
                'preco' => 35.00,
                'duracao' => 60
            ],
            [
                'nome' => 'Manicure + Pedicure',
                'descricao' => 'Combo completo de mãos e pés',
                'preco' => 60.00,
                'duracao' => 120
            ],
            [
                'nome' => 'Esmaltação em Gel',
                'descricao' => 'Esmaltação profissional em gel de longa duração',
                'preco' => 50.00,
                'duracao' => 90
            ],
            [
                'nome' => 'Unhas em Gel',
                'descricao' => 'Alongamento e fortalecimento com gel',
                'preco' => 80.00,
                'duracao' => 120
            ],
            [
                'nome' => 'Unhas em Fibra',
                'descricao' => 'Alongamento e fortalecimento com fibra de vidro',
                'preco' => 90.00,
                'duracao' => 150
            ],
            [
                'nome' => 'Alongamento',
                'descricao' => 'Alongamento de unhas profissional',
                'preco' => 100.00,
                'duracao' => 180
            ],
            [
                'nome' => 'Blindagem',
                'descricao' => 'Tratamento de fortalecimento das unhas',
                'preco' => 45.00,
                'duracao' => 60
            ],
            [
                'nome' => 'Spa dos Pés',
                'descricao' => 'Tratamento relaxante e hidratante para os pés',
                'preco' => 70.00,
                'duracao' => 90
            ],
            [
                'nome' => 'Design de Unhas',
                'descricao' => 'Arte e decoração personalizada nas unhas',
                'preco' => 40.00,
                'duracao' => 60
            ]
        ];

        foreach ($servicos as $servico) {
            Servico::create($servico);
        }

        $this->command->info('Serviços cadastrados com sucesso!');
    }
}
