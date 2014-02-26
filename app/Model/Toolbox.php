<?php

/**
 * Classe qualifiée de "boîte à outils" contenant uniquement des méthodes statiques 
 */
class Toolbox {

    /**
     * Retourne une valeur tirée aléatoirement dans le tableau passé en paramètre
     * @param array $array 
     * @return mixed
     */
    public static function arrayRand($array) {
        if (empty($array)) {
            return null;
        }
        return $array[array_rand($array)];
    }

    /**
     * Retourne vrai ou faux de manière aléatoire. Par défaut a autant de chance de retourner vrai que faux.
     * @param int $proba Probabilité sur 100 de retourner vrai. Par défaut, vaut 50 (1 chance sur 2)
     * @return boolean 
     */
    public static function test($proba = 50) {
        $rand = mt_rand(1, 100);
        if ($rand <= $proba) {
            return true;
        }
        return false;
    }

    /**
     * Renvoie une suite logique de chiffre de la longueur passée en paramètre
     * @param int $number Longuerur de la suite (défaut 3)
     * @return string Suite de chiffre logique
     */
    public static function logicalSequence($number = 3) {
        $possibilities = array(
            'increasing',
            'decreasing',
            'egal'
        );
        $startNumber = rand(0, 9);
        $numberActual = $startNumber;
        $sequence = '';
        switch (Toolbox::arrayRand($possibilities)) {
            case 'increasing':
                for ($i = 0; $i < $number; $i++) {
                    $sequence .= $numberActual;
                    $numberActual++;
                    if ($numberActual > 9) {
                        $numberActual = 0;
                    }
                }
                break;
            case 'decreasing':
                for ($i = 0; $i < $number; $i++) {
                    $sequence .= $numberActual;
                    $numberActual--;
                    if ($numberActual < 0) {
                        $numberActual = 9;
                    }
                }
                break;
            default:
                for ($i = 0; $i < $number; $i++) {
                    $sequence .= $startNumber;
                }
                break;
        }
        return $sequence;
    }

    /**
     * Converti un nombre d'unités (année, mois,semaine heure,minute)  en nombre de secondes
     * @param string $unit Unité à convertir (year,month,week,dau,hour,min)
     * @param int $time Quantité d'unité à convertir
     * @return int Nombre de secondes correspondantes
     */
    public static function convertTimeToSeconds($unit, $time = 1) {
        switch ($unit) {
            case 'sec':
                return $time;
            case 'min':
                return $time * 60;
            case 'hour':
                return $time * 60 * 60;
            case 'day':
                return $time * 60 * 60 * 24;
            case 'week':
                return $time * 60 * 60 * 24 * 7;
            case 'month':
                return $time * 60 * 60 * 24 * 30;
            case 'year':
                return $time * 60 * 60 * 24 * 365;
            default :
                return false;
        }
    }

}
