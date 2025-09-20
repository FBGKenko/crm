<?php

namespace App\Exports;

use App\Models\persona;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class personasExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return persona::with(['identificacion', 'relacionDomicilio.domicilio', 'telefonos', 'correos'])->get();
    }

     public function headings(): array
    {
        return [
            'ID',
            'Fecha de Registro',
            'Folio',
            'Origen',
            'Estatus',
            'Apodo',
            'Nombre',
            'Apellido Paterno',
            'Apellido Materno',
            'Sexo',
            'Fecha de Nacimiento',
            'Edad',
            'Telefonos',
            'Correos',
            'Calle Principal',
            'Entre calle',
            'Y calle',
            'Número Exterior',
            'Número Interior',
            'Código Postal',
            'Colonia',
            'Ciudad',
            'Municipio',
            'Estado',
            'Referencias',
            'CURP',
            'RFC',
            'Lugar de Nacimiento',
            'Clave Electoral',
            'Sección',
            'Afiliado',
            'Simpatizante',
            'Relación',
            'Cliente',
            'Promotor',
            'Colaborador',
            'Etiquetas',
            'Observaciones',
        ];
    }

     public function map($persona): array
    {
        $domicilio = $persona->relacionDomicilio->where('tipo', 'PRINCIPAL')->first()->domicilio;
        return [
            $persona->id,
            $persona->fecha_registro,
            $persona->folio,
            $persona->origen,
            $persona->estatus,
            $persona->apodo,
            $persona->nombres,
            $persona->apellido_paterno,
            $persona->apellido_materno,
            $persona->genero,
            $persona->fecha_nacimiento,
            $persona->rangoEdad,
            $persona->telefonos->pluck('telefono')->implode(', '),
            $persona->correos->pluck('correo')->implode(', '),
            $domicilio->calle1 ?? '',
            $domicilio->calle2 ?? '',
            $domicilio->calle3 ?? '',
            $domicilio->numero_exterior ?? '',
            $domicilio->numero_interior ?? '',
            $domicilio->codigo_postal ?? '',
            $domicilio->colonia->nombre ?? '',
            '',
            '',
            '',
            $domicilio->referencias ?? '',
            $persona->identificacion->curp ?? '',
            $persona->identificacion->rfc ?? '',
            $persona->identificacion->lugarNacimiento ?? '',
            $persona->identificacion->clave_elector ?? '',
            $persona->identificacion->seccion_id ?? '',
            $persona->afiliado ? 'Sí' : 'No',
            $persona->simpatizante ? 'Sí' : 'No',
            $persona->programa,
            $persona->cliente ? 'Sí' : 'No',
            $persona->promotor ? 'Sí' : 'No',
            $persona->colaborador ? 'Sí' : 'No',
            $persona->etiquetas,
            $persona->observaciones,
        ];
    }
}
