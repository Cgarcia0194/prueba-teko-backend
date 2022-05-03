<?php

namespace App\Repositories\Procesos;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Auth\AuthRepository;

class ConfiguracionRepository
{

    public $authRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $ar)
    {
        $this->authRepository = $ar;
    }

    /**
     * Add
     *
     * @return collections Array
     */
    public function actualizarPreferencias($request)
    {
        DB::beginTransaction();

        $respuesta = DB::update(
            'UPDATE configuracion SET color = ?, complemento = ?, modo_oscuro = ?, modo_pantalla_completa = ?, modo_input = ? WHERE user = ?',
            [
                $request->input('color'),
                $request->input('complemento'),
                $request->input('modo_oscuro') ? 1 : 2,
                $request->input('modo_pantalla_completa') ? 1 : 2,
                $request->input('modo_input'),
                Auth::guard()->user()->id,
            ]
        );

        if ($respuesta || $respuesta == 0) {
            $user = $this->authRepository->obtenerInformacionUsuario(Auth::guard()->user()->id);

            $response["status"] = "success";
            $response["user"] = $user;
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se eliminó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();
        } else {
            $response["status"] = "info";
            $response["user"] = [];
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
