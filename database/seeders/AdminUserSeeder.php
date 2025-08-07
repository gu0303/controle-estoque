<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuário administrador padrão
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@controleestoque.com',
            'password' => Hash::make('admin123'),
            'role' => 'administrador',
            'email_verified_at' => now(),
        ]);

        // Criar usuário gerente de estoque
        User::create([
            'name' => 'Gerente de Estoque',
            'email' => 'gerente@controleestoque.com',
            'password' => Hash::make('gerente123'),
            'role' => 'gerente_estoque',
            'email_verified_at' => now(),
        ]);

        // Criar usuário operador
        User::create([
            'name' => 'Operador',
            'email' => 'operador@controleestoque.com',
            'password' => Hash::make('operador123'),
            'role' => 'operador',
            'email_verified_at' => now(),
        ]);
    }
}
