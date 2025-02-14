<?php

namespace Database\Seeders;

use App\Models\domicilio;
use App\Models\persona;
use App\Models\personaDomicilio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class fixDomiciliosQueNOExistenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $relacionDomicilio = personaDomicilio::where('tipo', 'UBICACION')->update(['tipo' => 'PRINCIPAL']);
        $personas = persona::doesntHave('relacionDomicilio')->get();

        $datosDomicilio = [
            'calle1' => null,
            'calle2' => null,
            'calle3' => null,
            'numero_exterior' => null,
            'numero_interior' => null,
            'colonia_id' => null,
            'referencia' => null,
        ];
        foreach ($personas as $persona) {
            $domicilio = domicilio::create($datosDomicilio);
            personaDomicilio::create([
                'tipo' => 'PRINCIPAL',
                'persona_id' => $persona->id,
                'domicilio_id' => $domicilio->id,
            ]);
        }

        $totales = persona::doesntHave('relacionDomicilio')->get();
        Log::info('Total de personas sin domicilio: ' . $totales->count());
    }
}
