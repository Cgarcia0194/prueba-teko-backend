<?php

namespace App\Repositories\General;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GeneralRepository
{

    /**
     * Get All
     *
     * @return collections Array
     */
    public function consultarPlantillas()
    {
        $data = $this->consultarPlantillasDeAcceso(Auth::check() ? Auth::guard()->user()->id : 0);
        $items = $this->consultarPlantillasBuscador(Auth::check() ? Auth::guard()->user()->id : 0);

        if (!empty($data)) {
            $response["status"] = "success";
            $response["data"] = $data;
            $response["items"] = $items;
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Información consultada correctamente",
                "type" => "success",
            ];
        } else {
            $response["status"] = "info";
            $response["data"] = [];
            $response["items"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "No hay información disponible",
                "type" => "info",
            ];
        }

        return $response;
    }

    /**
     * Get All
     *
     * @return collections Array
     */
    public function consultarEstadisticas()
    {
        $data = DB::select("SELECT estatus AS name, ( SELECT COUNT( p._id ) FROM proyecto AS p WHERE p.estatus = proyecto.estatus ) AS data FROM proyecto GROUP BY proyecto.estatus");

        foreach ($data as $info) {
            $info->data = array($info->data);
        }

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
     * Get All
     *
     * @return collections Array
     */
    public function consultarProyectosPorModeloDeCalidad()
    {
        $data = DB::select("SELECT nombre AS name, (SELECT COUNT(_id) FROM proyecto WHERE proyecto.modelo_calidad = modelo_calidad._id) AS data FROM modelo_calidad");

        foreach ($data as $info) {
            $info->data = array($info->data);
        }

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
     * Get All
     *
     * @return collections Array
     */
    public function consultarProyectosPorTipo()
    {
        $data = DB::select("SELECT ( SELECT COUNT( _id ) FROM proyecto WHERE (tipo + 0) = 1 ) AS total_nuevo, ( SELECT COUNT( _id ) FROM proyecto WHERE (tipo + 0) = 2 ) AS total_adaptacion, ( SELECT COUNT( _id ) FROM proyecto WHERE (tipo + 0) = 3 ) AS total_mantenimiento")[0];

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
     * Get All
     *
     * @return collections Array
     */
    public function consultarProyectosPorTamanio()
    {
        $data = DB::select("SELECT (SELECT COUNT(_id) FROM proyecto WHERE (tamanio + 0 = 1)) AS total_chico, (SELECT COUNT(_id) FROM proyecto WHERE (tamanio + 0 = 2)) AS total_mediano, (SELECT COUNT(_id) FROM proyecto WHERE tamanio + 0 = 3) AS total_grande")[0];

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
     * Función que consulta las plantillas abuelo.
     * @return string
     */
    function consultarPlantillasNivel0($id)
    {
        $query = "SELECT DISTINCT p3.* FROM plantilla AS p1 INNER JOIN plantilla AS p2 ON p1.padre = p2._id INNER JOIN plantilla AS p3 ON p2.padre = p3._id INNER JOIN grupo_seguridad_plantilla AS gsp ON p1._id = gsp.plantilla WHERE gsp.grupo_seguridad IN ( SELECT grupo_seguridad FROM grupo_seguridad_user WHERE USER = {$id} ) AND p3.estatus = 1 UNION SELECT DISTINCT plantilla.* FROM plantilla INNER JOIN grupo_seguridad_plantilla ON plantilla._id = grupo_seguridad_plantilla.plantilla WHERE grupo_seguridad_plantilla.grupo_seguridad IN ( SELECT grupo_seguridad FROM grupo_seguridad_user WHERE USER = {$id} ) AND plantilla.estatus = 1 AND plantilla.padre = 0";

        return $query;
    }

    /**
     * Función que consulta las plantillas padre.
     * @return string
     */
    function consultarPlantillasNivel1($id, $idAbuelo)
    {
        $query = "SELECT DISTINCT p2.* FROM plantilla AS p1 INNER JOIN plantilla AS p2 ON p1.padre = p2._id INNER JOIN grupo_seguridad_plantilla AS gsp ON p1._id = gsp.plantilla WHERE gsp.grupo_seguridad IN ( SELECT grupo_seguridad FROM grupo_seguridad_user WHERE USER = {$id} ) AND p2.estatus = 1 AND p2.padre = {$idAbuelo} UNION SELECT DISTINCT plantilla.* FROM plantilla INNER JOIN grupo_seguridad_plantilla ON plantilla._id = grupo_seguridad_plantilla.plantilla WHERE grupo_seguridad_plantilla.grupo_seguridad IN ( SELECT grupo_seguridad FROM grupo_seguridad_user WHERE USER = {$id} ) AND plantilla.estatus = 1 AND plantilla.padre = {$idAbuelo}";

        return $query;
    }

    /**
     * Función que consulta las plantillas hijo.
     * @return string
     */
    function consultarPlantillasNivel2($id, $idPadre)
    {
        $query = "SELECT DISTINCT p1.* FROM plantilla AS p1 INNER JOIN grupo_seguridad_plantilla AS gsp ON p1._id = gsp.plantilla WHERE gsp.grupo_seguridad IN ( SELECT grupo_seguridad FROM grupo_seguridad_user WHERE user = {$id} ) AND p1.estatus = 1 AND p1.padre = {$idPadre} ORDER BY p1.orden, p1.name";

        return $query;
    }

    /**
     * Función que consulta las plantillas.
     * @return string
     */
    function consultarPlantillasDeAcceso($id)
    {
        $nivel0 = DB::select($this->consultarPlantillasNivel0($id));

        if (!empty($nivel0)) {
            foreach ($nivel0 as $n0) {
                $nivel1 = DB::select($this->consultarPlantillasNivel1($id, $n0->_id));

                if (!empty($nivel1)) {
                    foreach ($nivel1 as $n1) {
                        $nivel2 = DB::select($this->consultarPlantillasNivel2($id, $n1->_id));

                        $n1->children = $nivel2;
                    }
                }

                $n0->children = $nivel1;
            }
        }

        return $nivel0;
    }

    /**
     * Función que consulta las plantillas para el buscador.
     * @return string
     */
    function consultarPlantillasBuscador($id)
    {
        $data = DB::select("SELECT DISTINCT p1.* FROM plantilla AS p1 INNER JOIN grupo_seguridad_plantilla AS gsp ON p1._id = gsp.plantilla WHERE gsp.grupo_seguridad IN ( SELECT grupo_seguridad FROM grupo_seguridad_user WHERE user = {$id} ) AND p1.estatus = 1 ORDER BY p1.name");

        return $data;
    }
}
