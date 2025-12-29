<?php
namespace App\Services;
use App\Models\face_id;

class FaceRecognitionService
{
    public function identificar(array $descriptor)
    {
        $faces = face_id::where('activo', true)->get();

        $mejorMatch = null;
        $menorDistancia = 1.0;

        foreach ($faces as $face) {

            if (!is_array($face->embedding)) {
                continue;
            }

            foreach ($face->embedding as $storedDescriptor) {

                if (!is_array($storedDescriptor)) {
                    continue;
                }

                if (count($storedDescriptor) !== count($descriptor)) {
                    continue;
                }

                $dist = $this->euclideanDistance($descriptor, $storedDescriptor);

                if ($dist < $menorDistancia) {
                    $menorDistancia = $dist;
                    $mejorMatch = $face;
                }
            }
        }
        if (!$mejorMatch) {
            return null;
        }

        if ($menorDistancia <= 0.6) {
            return [
                'face' => $mejorMatch,
                'distance' => $menorDistancia
            ];
        }

        return null;
    }

    private function euclideanDistance(array $d1, array $d2): float
    {
        $sum = 0;
        foreach ($d1 as $i => $v) {
            $sum += pow($v - $d2[$i], 2);
        }
        return sqrt($sum);
    }
}