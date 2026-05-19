<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FotosSeeder extends Seeder
{
    public function run()
    {
        $carpeta  = storage_path('app/Fotos');
        $archivos = glob($carpeta . '/traje_*.jpg');

        // Agrupa los archivos por número de traje
        $trajes = [];
        foreach ($archivos as $archivo) {
            $nombre = basename($archivo);
            preg_match('/traje_(\d+)/', $nombre, $matches);
            if (!isset($matches[1])) continue;

            $numTraje = (int) $matches[1];
            $trajes[$numTraje][] = $archivo;
        }

        foreach ($trajes as $numTraje => $fotos) {
            // Los archivos empiezan en 02 pero los item_id empiezan en 1
            // por eso restamos 1
            $itemId = $numTraje - 1;

            $idTraje = DB::table('item_trajes')->where('item_id', $itemId)->value('id');
            if (!$idTraje) {
                echo "⚠️  No se encontró item_traje para item_id=$itemId (traje_$numTraje)\n";
                continue;
            }

            $nombreTraje = DB::table('items')->where('id', $itemId)->value('nombre');

            // Ordena: primero el sin paréntesis (principal), luego los demás
            usort($fotos, function ($a, $b) {
                $aPrincipal = !str_contains(basename($a), '(');
                $bPrincipal = !str_contains(basename($b), '(');
                return $bPrincipal - $aPrincipal;
            });

            foreach ($fotos as $orden => $archivo) {
                $esPrincipal = !str_contains(basename($archivo), '(');

                DB::table('fotos')->insert([
                    'idTraje'    => $idTraje,
                    'principal'  => $esPrincipal,
                    'nombre'     => $nombreTraje,
                    'orden'      => $orden + 1,
                    'imagen'     => file_get_contents($archivo),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            echo "✅ Traje $numTraje → item_id=$itemId ($nombreTraje): " . count($fotos) . " fotos insertadas\n";
        }
    }
}