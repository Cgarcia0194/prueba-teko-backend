<?php

namespace App\Repositories\Catalogos;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Auth\AuthRepository;

class ClienteRepository{

    public $authRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $authRepository){
        $this->authRepository = $authRepository;
    }

    /**
     * 
     */
    public function create($request){

        $query = 'INSERT INTO clientes (nombre, apellido_paterno, apellido_materno, genero, fecha_ingreso, fecha_egreso, servicio, estatus, fecha_registro) 
        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $fields = [
            $request->input('nombre'),
            $request->input('apellido_paterno'),
            $request->input('apellido_materno'),
            $request->input('genero'),
            $request->input('fecha_ingreso'),
            $request->input('fecha_egreso'),
            $request->input('servicio'),
            1,
            date("Y-m-d H:i:s"),
        ];

        $cliente = $this->createQueries($query, $fields);

        return $cliente;
    }

    /**
     * Add
     *
     * @return collections Array
     */
    public function update($request){
        $query = 'UPDATE clientes SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, genero = ?, fecha_ingreso = ?, fecha_egreso = ?, servicio = ? WHERE _id = ?';

        $fields = [
            $request->input('nombre'),
            $request->input('apellido_paterno'),
            $request->input('apellido_materno'),
            $request->input('genero'),
            $request->input('fecha_ingreso'),
            $request->input('fecha_egreso'),
            $request->input('servicio'),
            $request->input('_id')
        ];

        $client = $this->updateQueries($query, $fields);

        return $client;
    }
    
    /**
     * 
     */
    public function deactivateReactivate($request){
        $query = "UPDATE clientes SET estatus = ? WHERE _id = ?";

        $fields = [
            $request->input('desactivarReactivar'),
            $request->input('_id'),
        ];

        $respuesta = $this->updateQueries($query, $fields);

        return $respuesta;
    }

    /**
     * Get All
     *
     * @return collections Array
     */
    public function getClients(){
        $query = "SELECT clientes.*, IFNULL( clientes.fecha_egreso, 'Indefinida' ) AS fecha_egreso_validada, CONCAT( clientes.nombre, ' ', clientes.apellido_paterno, ' ', clientes.apellido_materno ) AS nombre_completo_cliente, ( clientes.genero + 0 ) AS genero_indice, ( clientes.estatus + 0 ) AS estatus_indice, servicios._id AS servicios_id, servicios.nombre AS servicios_nombre, servicios.costo AS servicios_costo, servicios.descripcion AS servicios_descripcion, servicios.periodicidad AS servicios_periodicidad, COUNT(pagos._id) AS pagos_cliente FROM clientes INNER JOIN servicios ON servicios._id = clientes.servicio LEFT JOIN pagos ON pagos.cliente = clientes._id GROUP BY clientes._id";
        $clients = $this->getQueries($query);

        return $clients;
    }
    
    /**
     * 
     */
    public function getServices(){
        $query = "SELECT *, (estatus + 0) AS estatus_indice FROM servicios WHERE (estatus + 0) = 1";
        $services = $this->getQueries($query);

        return $services;
    }

    /**
     * 
     */
    private function getQueries($query){
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
    private function updateQueries($query, $fields){
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
    private function createQueries($query, $fields){
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