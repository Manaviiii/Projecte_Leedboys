<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FotosSeeder extends Seeder
{
    public function run()
    {
        $carpeta = storage_path('app/fotos');
        $archivos = glob($carpeta . '/traje_*.jpg');

        // Agrupa los archivos por número de traje
        $trajes = [];
        foreach ($archivos as $archivo) {
            $nombre = basename($archivo);
            preg_match('/traje_(\d+)_foto_\d+/', $nombre, $matches);
            if (!isset($matches[1])) continue;

            $numTraje = (int) $matches[1];
            $trajes[$numTraje][] = $archivo;
        }

        foreach ($trajes as $numTraje => $fotos) {
            // Busca el id de item_trajes a partir del item_id
            $idTraje = DB::table('item_trajes')->where('item_id', $numTraje)->value('id');
            if (!$idTraje) continue;

            // Busca el nombre del traje
            $nombreTraje = DB::table('items')->where('id', $numTraje)->value('nombre');

            // Ordena: primero el sin paréntesis (principal), luego los demás
            usort($fotos, function($a, $b) {
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

            echo "✅ Traje $numTraje ($nombreTraje): " . count($fotos) . " fotos insertadas\n";
        }
    }
}