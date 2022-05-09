<?php

namespace App\Repositories\Catalogos;

use Illuminate\Support\Facades\DB;
use App\Repositories\Auth\AuthRepository;

class ServicioRepository
{

    public $authRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * 
     */
    public function create($request)
    {
        $query = 'INSERT INTO servicios (nombre, costo, descripcion, periodicidad, estatus, fecha_registro) 
        VALUES(?, ?, ?, ?, ?, ?)';

        $fields = [
            $request->input('nombre'),
            $request->input('costo'),
            $request->input('descripcion'),
            $request->input('periodicidad_indice'),
            1,
            date("Y-m-d H:i:s"),
        ];

        $newService = $this->createQueries($query, $fields);

        return $newService;
    }

    /**
     * 
     */
    public function update($request)
    {
        $query = "UPDATE servicios SET nombre = ?, costo = ?, descripcion = ?, periodicidad = ? WHERE _id = ?";

        $fields = [
            $request->input('nombre'),
            $request->input('costo'),
            $request->input('descripcion'),
            $request->input('periodicidad_indice'),
            $request->input('_id'),
        ];

        $response = $this->updateQueries($query, $fields);

        return $response;
    }

    /**
     * 
     */
    public function deactivateReactivate($request)
    {
        $query = "UPDATE servicios SET estatus = ? WHERE _id = ?";

        $fields = [
            $request->input('desactivarReactivar'),
            $request->input('_id'),
        ];

        $response = $this->updateQueries($query, $fields);

        return $response;
    }

    /**
     * Get All
     *
     * @return collections Array
     */
    public function getServices()
    {
        $query = "SELECT *, (periodicidad + 0) AS periodicidad_indice, (estatus + 0) AS estatus_indice FROM servicios";
        $services = $this->getQueries($query);

        return $services;
    }

    /**
     * 
     */
    private function getQueries($query)
    {
        $data = DB::select($query);

        if (empty($data)) {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "No hay información disponible",
                "type" => "info",
            ];
            return $response;
        }

        $response["status"] = "success";
        $response["data"] = $data;
        $response["message"] = [
            "title" => "Correcto",
            "content" => "Información consultada correctamente",
            "type" => "success",
        ];

        return $response;
    }

    /**
     * 
     */
    private function updateQueries($query, $fields)
    {
        DB::beginTransaction();

        $respuesta = DB::update($query, $fields);

        if ($respuesta || $respuesta == 0) {

            $response["status"] = "success";
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se modificó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();

            return $response;
        }

        $response["status"] = "info";
        $response["user"] = [];
        $response["message"] = [
            "title" => "Información",
            "content" => "Ocurrió un error al modificar el registro",
            "type" => "info",
        ];
        DB::rollback();

        return $response;
    }

    /**
     * 
     */
    private function createQueries($query, $fields)
    {
        DB::beginTransaction();

        DB::insert($query, $fields);

        $id = DB::getPdo()->lastInsertId();

        if ($id > 0) {
            $response["id"] = $id;
            $response["status"] = "success";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se agregó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();
            return $response;
        }

        $response["status"] = "info";
        $response["data"] = [];
        $response["message"] = [
            "title" => "Información",
            "content" => "Ocurrió un error al agregar el registro",
            "type" => "info",
        ];

        DB::rollback();
        return $response;
    }
}