<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {

        Role::truncate();

        Role::create([
            'nombre' => "Administrador",
            'descripcion' => "Acceso a todas las opciones",
        ]);

        Role::create([
            'nombre' => "Moderador",
            'descripcion' => "Sin acceso a usuarios",
        ]);

        Role::create([
            'nombre' => "Editor",
            'descripcion' => "Opciones limitadas de edición",
        ]);

    }
}
