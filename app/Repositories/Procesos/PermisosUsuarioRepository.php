<?php

namespace App\Repositories\Procesos;

use Illuminate\Support\Facades\DB;

class PermisosUsuarioRepository
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
            'INSERT INTO grupo_seguridad_user (grupo_seguridad, user, fecha_registro) VALUES (?, ?, ?)',
            [
                $request->input('_idGrupoSeguridad'),
                $request->input('_idUsuario'),
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
    public function eliminar($request)
    {
        DB::beginTransaction();

        $respuesta = DB::delete(
            'DELETE FROM grupo_seguridad_user WHERE _id = ?',
            [
                $request->input('_id')
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
     * Get All
     *
     * @return collections Array
     */
    public function consultarGruposSeguridad()
    {
        $data = DB::select("SELECT *, estatus + 0 AS estatus_indice FROM grupo_seguridad ORDER BY _id");

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
    public function consultarUsuariosConPermiso($request)
    {
        $data = DB::select("SELECT users.*, grupo_seguridad.nombre, grupo_seguridad_user._id AS grupo_seguridad_user_id FROM grupo_seguridad_user INNER JOIN grupo_seguridad ON grupo_seguridad._id = grupo_seguridad_user.grupo_seguridad INNER JOIN users ON users.id = grupo_seguridad_user.user WHERE grupo_seguridad._id = {$request->input('cmbGrupoSeguridad')}");

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
    public function consultarUsuariosSinPermiso($request)
    {
        $data = DB::select("SELECT * FROM users WHERE users.id NOT IN (SELECT users.id FROM grupo_seguridad_user INNER JOIN grupo_seguridad ON grupo_seguridad._id = grupo_seguridad_user.grupo_seguridad LEFT JOIN users ON users.id = grupo_seguridad_user.`user` WHERE grupo_seguridad._id = {$request->input('cmbGrupoSeguridad')})");

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
}
