<?php

namespace Database\Seeders;

use App\Models\persona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class registrosSinApodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $personas = persona::where('apodo', null)->get();

        foreach ($personas as $persona) {
            $persona->apodo = 'Sin apodo';
            $persona->save();
        }
    }
}
