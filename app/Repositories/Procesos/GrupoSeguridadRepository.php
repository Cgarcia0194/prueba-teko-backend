<?php

namespace App\Repositories\Procesos;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GrupoSeguridadRepository
{
    /**
     * Add
     *
     * @return collections Array
     */
    public function agregar($request)
    {
        DB::beginTransaction();

        DB::insert(
            'INSERT INTO grupo_seguridad (nombre, descripcion, estatus, responsable_actualizacion, fecha_actualizacion, responsable_registro, fecha_registro) VALUES (?, ?, ?, ?, ?, ?, ?)',
            [
                $request->input('nombre'),
                $request->input('descripcion'),
                1,
                Auth::guard()->user()->id,
                date("Y-m-d H:i:s"),
                Auth::guard()->user()->id,
                date("Y-m-d H:i:s"),
            ]
        );

        $id = DB::getPdo()->lastInsertId();

        if ($id > 0) {
            $response["status"] = "success";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se agregó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();
        } else {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "Ocurrió un error al agregar el registro",
                "type" => "info",
            ];

            DB::rollback();
        }

        return $response;
    }

    /**
     * Add
     *
     * @return collections Array
     */
    public function modificar($request)
    {
        DB::beginTransaction();

        $respuesta = DB::update(
            'UPDATE grupo_seguridad SET nombre = ?, descripcion = ?, responsable_actualizacion = ?, fecha_actualizacion = ? WHERE _id = ?',
            [
                $request->input('nombre'),
                $request->input('descripcion'),
                Auth::guard()->user()->id,
                date("Y-m-d H:i:s"),
                $request->input('_id'),
            ]
        );

        if ($respuesta > 0) {
            $response["status"] = "success";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se modificó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();
        } else {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "Ocurrió un error al modificar el registro",
                "type" => "info",
            ];

            DB::rollback();
        }

        return $response;
    }

    /**
     * Add
     *
     * @return collections Array
     */
    public function desactivar($request)
    {
        DB::beginTransaction();

        $respuesta = DB::update(
            'UPDATE grupo_seguridad SET estatus = ?, responsable_actualizacion = ?, fecha_actualizacion = ? WHERE _id = ?',
            [
                2,
                Auth::guard()->user()->id,
                date("Y-m-d H:i:s"),
                $request->input('_id'),
            ]
        );

        if ($respuesta > 0) {
            $response["status"] = "success";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se desactivó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();
        } else {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "Ocurrió un error al desactivar el registro",
                "type" => "info",
            ];

            DB::rollback();
        }

        return $response;
    }

    /**
     * Add
     *
     * @return collections Array
     */
    public function reactivar($request)
    {
        DB::beginTransaction();

        $respuesta = DB::update(
            'UPDATE grupo_seguridad SET estatus = ?, responsable_actualizacion = ?, fecha_actualizacion = ? WHERE _id = ?',
            [
                1,
                Auth::guard()->user()->id,
                date("Y-m-d H:i:s"),
                $request->input('_id'),
            ]
        );

        if ($respuesta > 0) {
            $response["status"] = "success";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se reactivó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();
        } else {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "Ocurrió un error al reactivar el registro",
                "type" => "info",
            ];

            DB::rollback();
        }

        return $response;
    }

    /**
     * Get All
     *
     * @return collections Array
     */
    public function consultar($request)
    {
        $query = "SELECT grupo_seguridad.*, u1.name AS name1, u1.lastname AS lastname1, u2.name AS name2, u2.lastname AS lastname2 FROM grupo_seguridad INNER JOIN users AS u1 ON grupo_seguridad.responsable_actualizacion = u1.id INNER JOIN users AS u2 ON grupo_seguridad.responsable_registro = u2.id";
        $query .= " WHERE grupo_seguridad.nombre LIKE '%" . $request->input('txtBuscador') . "%'";

        $data = DB::select($query);

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
    public function consultarPlantillas($request)
    {
        $data = $this->consultarPlantillasPadre($request->input('_id'));

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
     * Add
     *
     * @return collections Array
     */
    public function agregarPlantillasAGrupo($request)
    {
        DB::beginTransaction();

        DB::insert(
            'INSERT INTO grupo_seguridad_plantilla (grupo_seguridad, plantilla, fecha_registro) VALUES (?, ?, ?)',
            [
                $request->input('model')["_id"],
                $request->input('model_plantilla')["_id"],
                date("Y-m-d H:i:s"),
            ]
        );

        $id = DB::getPdo()->lastInsertId();

        if ($id > 0) {
            $response["status"] = "success";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se agregó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();
        } else {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "Ocurrió un error al agregar el registro",
                "type" => "info",
            ];

            DB::rollback();
        }

        return $response;
    }

    /**
     * Add
     *
     * @return collections Array
     */
    public function quitarPlantillasAGrupo($request)
    {
        DB::beginTransaction();

        $respuesta = DB::delete(
            'DELETE FROM grupo_seguridad_plantilla WHERE _id = ?',
            [
                $request->input('model_plantilla')["existe_relacion"]
            ]
        );

        if ($respuesta || $respuesta == 0) {
            $response["status"] = "success";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se eliminó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();
        } else {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "Ocurrió un error al eliminar el registro",
                "type" => "info",
            ];

            DB::rollback();
        }

        return $response;
    }

    /**
     * Función que consulta las plantillas abuelo.
     * @return string
     */
    public function consultarPlantillasPadre($idGrupoSeguridad)
    {
        $informacion = [];
        $nodos = DB::select("SELECT *, IFNULL((SELECT _id FROM grupo_seguridad_plantilla AS gsp WHERE gsp.grupo_seguridad = {$idGrupoSeguridad} AND gsp.plantilla = plantilla._id), 0) AS existe_relacion FROM plantilla WHERE padre = 0 ORDER BY orden, name");

        foreach ($nodos as $nodo) {
            if ($nodo->es_menu == "Si") {
                $nodo->children = $this->consultarPlantillasPorPadre($nodo->_id, $idGrupoSeguridad);

                array_push($informacion, $nodo);
            } else {
                array_push($informacion, $nodo);
            }
        }

        return $informacion;
    }

    /**
     * Función que consulta las plantillas padre.
     * @return string
     */
    public function consultarPlantillasPorPadre($idPadre, $idGrupoSeguridad)
    {
        $informacion = [];
        $nodos = DB::select("SELECT *, IFNULL((SELECT _id FROM grupo_seguridad_plantilla AS gsp WHERE gsp.grupo_seguridad = {$idGrupoSeguridad} AND gsp.plantilla = plantilla._id), 0) AS existe_relacion FROM plantilla WHERE padre = {$idPadre} ORDER BY orden, name");;

        if (!is_null($nodos)) {
            foreach ($nodos as $nodo) {
                if ($nodo->es_menu == "Si") {
                    $nodo->children = $this->consultarPlantillasPorPadre($nodo->_id, $idGrupoSeguridad);

                    array_push($informacion, $nodo);
                } else {
                    array_push($informacion, $nodo);
                }
            }
        }

        return $informacion;
    }
}
