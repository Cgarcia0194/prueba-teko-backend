<?php
class eeee
{

    /**
     * 
     * @param type $fechaIngreso
     * @param type $fechaEgreso
     * @param type $modalidad
     */
    function calcularRangosEntreFechas($fechaIngreso, $fechaEgreso, $modalidad)
    {
        $rows = [];
        $dias = "+ 1 days";

        switch ($modalidad) {
            /**
             * Caso para cuando son semanas
             */
            case 1:
                $dias = "+ 7 days";
                break;
                /**
                 * Caso para cuando son meses
                 */
            case 2:
                $dias = "+ 30 days";
                break;
                /**
                 * Caso para cuando son semestres
                 */
            case 3:
                $dias = "+ 180 days";
                break;
                /**
                 * Caso para cuando son anualidades
                 */
            case 4:
                $dias = "+ 365 days";
                break;
            default:
                $dias = "+ 1 days";
                break;
        }

        for ($i = $fechaIngreso; $i <= $fechaEgreso; $i = date("Y-m-d", strtotime($i . $dias))) {
            $fecha["fecha_cargo"] = $i;
            array_push($rows, $fecha);
        }

        return $rows;
    }
}
