<?php

namespace App\Repositories\Procesos;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Auth\AuthRepository;

class MiPerfilRepository
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
    public function modificar($request)
    {
        DB::beginTransaction();

        $respuesta = DB::update(
            'UPDATE persona SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, rfc = ?, curp = ?, sexo = ?, telefono = ?, fecha_nacimiento = ?, correo_electronico = ?, estado_civil = ?, municipio = ? WHERE _id = ?',
            [
                $request->input('nombre'),
                $request->input('apellido_paterno'),
                $request->input('apellido_materno'),
                $request->input('rfc'),
                $request->input('curp'),
                $request->input('sexo'),
                $request->input('telefono'),
                $request->input('fecha_nacimiento'),
                $request->input('correo_electronico'),
                $request->input('estado_civil'),
                $request->input('municipio'),
                $request->input('_id'),
            ]
        );

        if ($respuesta || $respuesta == 0) {
            $user = $this->authRepository->obtenerInformacionUsuario(Auth::guard()->user()->id);

            $response["status"] = "success";
            $response["user"] = $user;
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se modificó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();
        } else {
            $response["status"] = "info";
            $response["user"] = [];
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
    public function cambiar($request)
    {
        DB::beginTransaction();

        $respuesta = DB::update(
            'UPDATE users SET password = ? WHERE id = ?',
            [
                Hash::make($request->input('contrasenia')),
                Auth::guard()->user()->id,
            ]
        );

        if ($respuesta > 0) {
            $response["status"] = "success";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se cambió correctamente la contraseña",
                "type" => "success",
            ];

            DB::commit();
        } else {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "Ocurrió un error al cambiar la contraseña",
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
        $id_user = Auth::guard()->user()->id;

        $query = "SELECT persona.*, persona._id AS id_persona, (persona.sexo + 0) AS indice_sexo, colaborador.*, (colaborador.estatus + 0) AS indice_estatus, colaborador._id AS id_colaborador, estado_civil._id AS id_estado_civil, municipio._id AS id_municipio, estado._id AS id_estado, pais._id AS id_pais, users.email FROM persona INNER JOIN colaborador ON colaborador.persona = persona._id INNER JOIN estado_civil ON estado_civil._id = persona.estado_civil INNER JOIN municipio ON municipio._id = persona.municipio INNER JOIN estado ON estado._id = municipio.estado INNER JOIN pais ON pais._id = estado.pais INNER JOIN users ON users.id = colaborador.user WHERE users.id = {$id_user}";

        $data = DB::select($query)[0];

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
    public function consultarEstadosCiviles()
    {
        $data = DB::select("SELECT * FROM estado_civil WHERE (estatus + 0) = 1");

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
    public function consultarPaises()
    {
        $data = DB::select("SELECT * FROM pais WHERE (estatus + 0) = 1 ORDER BY nombre ASC");

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
    public function consultarEstados($request)
    {
        $pais = $request->input('pais');

        $data = DB::select("SELECT * FROM estado WHERE pais = {$pais} AND (estatus + 0) = 1 ORDER BY nombre ASC");

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
    public function consultarMunicipios($request)
    {
        $estado = $request->input('estado');

        $data = DB::select("SELECT * FROM municipio WHERE estado = {$estado} AND (estatus + 0) = 1 ORDER BY nombre ASC");

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
