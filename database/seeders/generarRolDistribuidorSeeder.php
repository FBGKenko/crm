<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class generarRolDistribuidorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisoPedidos = Permission::create(['name' => 'accesoPedidos']);
        $rolDistribuidor = Role::create(['name' => 'DISTRIBUIDOR']);
        $rolDistribuidor->givePermissionTo($permisoPedidos);

        //buscar rol admin y dar permiso permisoPedidos
        $rolAdmin = Role::where('name', 'ADMINISTRADOR')->first();
        if($rolAdmin){
            $rolAdmin->givePermissionTo($permisoPedidos);
        }
    }
}
