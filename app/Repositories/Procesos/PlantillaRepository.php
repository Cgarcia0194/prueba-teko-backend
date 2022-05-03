<?php

namespace App\Repositories\Procesos;

use Illuminate\Support\Facades\DB;

class PlantillaRepository
{

    /**
     * Get All
     *
     * @return collections Array
     */
    public function consultar()
    {
        $data = $this->consultarPlantillasPadre();;

        if (!empty($data)) {
            $response["status"] = "success";
            $response["data"] = $data;
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Información consultada correctamente",
                "type" => "success",
            ];
        } else {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "No hay información disponible",
                "type" => "info",
            ];
        }

        return $response;
    }

    /**
     * Función que consulta las plantillas padre.
     * @return string
     */
    public function consultarPlantillasPadre()
    {
        $informacion = [];
        $nodos = DB::select("SELECT plantilla.*, plantilla.tipo_plantilla + 0 AS tipo_plantilla_indice, users.name AS name_user, users.lastname FROM plantilla INNER JOIN users ON plantilla.responsable = users.id WHERE padre = 0 ORDER BY orden, name");

        foreach ($nodos as $nodo) {
            if ($nodo->es_menu == "Si") {
                $nodo->children = $this->consultarPlantillasPorPadre($nodo->_id);

                array_push($informacion, $nodo);
            } else {
                array_push($informacion, $nodo);
            }
        }

        return $informacion;
    }

    /**
     * Función que consulta las plantillas por padre.
     * @return string
     */
    public function consultarPlantillasPorPadre($idPadre)
    {
        $informacion = [];
        $nodos = DB::select("SELECT plantilla.*, plantilla.tipo_plantilla + 0 AS tipo_plantilla_indice, users.name AS name_user, users.lastname FROM plantilla INNER JOIN users ON plantilla.responsable = users.id WHERE padre = {$idPadre} ORDER BY orden, name");;

        if (!is_null($nodos)) {
            foreach ($nodos as $nodo) {
                if ($nodo->es_menu == "Si") {
                    $nodo->children = $this->consultarPlantillasPorPadre($nodo->_id);

                    array_push($informacion, $nodo);
                } else {
                    array_push($informacion, $nodo);
                }
            }
        }

        return $informacion;
    }
}
