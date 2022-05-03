<?php

namespace App\Repositories\Catalogos;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Auth\AuthRepository;

class ColaboradorRepository{

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
    public function agregar($request){
        $query = 'INSERT INTO persona (nombre, apellido_paterno, apellido_materno, rfc, curp, sexo, telefono, fecha_nacimiento, correo_electronico, estado_civil, municipio, fecha_registro) 
        VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';

        $fields = [
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
            date("Y-m-d H:i:s"),
        ];

        $persona = $this->createQueries($query, $fields);

        if($persona['status'] == 'success'){
            $query = 'INSERT INTO colaborador (telefono_oficina, ext, horario_oficina, user, rol, relevancia, persona, estatus, fecha_registro) 
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
    
            $fields = [
                $request->input('telefono_oficina'),
                $request->input('ext'),
                $request->input('horario_oficina'),
                $request->input('id_user'),
                $request->input('rol'),
                $request->input('relevancia'),
                $persona['id'],
                1,
                date("Y-m-d H:i:s"),
            ];
    
            $colaborador = $this->createQueries($query, $fields);
        }

        return $colaborador;
    }

    /**
     * Add
     *
     * @return collections Array
     */
    public function modificar($request){
        $user['id'] = 0;
        
        $query = 'UPDATE persona SET nombre = ?, apellido_paterno = ?, apellido_materno = ?, rfc = ?, curp = ?, sexo = ?, telefono = ?, fecha_nacimiento = ?, correo_electronico = ?, estado_civil = ?, municipio = ? WHERE _id = ?';

        $fields = [
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
            $request->input('id_persona')
        ];

        $persona = $this->updateQueries($query, $fields);

        if($request->input('id_user') == 0 && $request->input('email') != null && $request->input('contrasenia') != null){

            $query = 'INSERT INTO users (email, password, username, status, verified, resettable, roles_mask, registered, force_logout) 
            VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)';
    
            $fields = [
                $request->input('email'),
                Hash::make($request->input('contrasenia')),
                $request->input('username'),
                0,
                1,
                1,
                0,
                0,
                0
            ];
    
            $user = $this->createQueries($query, $fields);
        }else if($request->input('id_user') != 0 && $request->input('email') != null && $request->input('contrasenia') != null){//actualizar tabla user
            $query = 'UPDATE users SET email = ?, password = ?, username = ? WHERE id = ?';
    
            $fields = [
                $request->input('email'),
                Hash::make($request->input('contrasenia')),
                $request->input('username'),
                $request->input('id_user')
            ];
    
            $user = $this->updateQueries($query, $fields);
        }

        if($persona['status'] == 'success'){
            $query = 'UPDATE colaborador SET telefono_oficina = ?, ext = ?, horario_oficina = ?, user = ?, rol = ?, relevancia = ? WHERE _id = ?';
    
            $fields = [
                $request->input('telefono_oficina'),
                $request->input('ext'),
                $request->input('horario_oficina'),
                $request->input('id_user') == 0 ? $user['id'] : $request->input('id_user'),
                $request->input('rol'),
                $request->input('relevancia'),
                $request->input('id_colaborador'),
            ];
    
            $colaborador = $this->updateQueries($query, $fields);
        }

        return $colaborador;
    }
    
    /**
     * 
     */
    public function desactivarReactivar($request){
        $query = "UPDATE colaborador SET estatus = ? WHERE _id = ?";

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
    public function consultar(){
        $query = "SELECT persona.*, persona._id AS id_persona, (persona.sexo + 0) AS indice_sexo, colaborador.*, (colaborador.estatus + 0) AS indice_estatus, colaborador._id AS id_colaborador, estado_civil._id AS id_estado_civil, estado_civil.nombre AS nombre_estado_civil, municipio._id AS id_municipio, municipio.nombre AS nombre_municipio, estado._id AS id_estado, estado.nombre AS nombre_estado, pais._id AS id_pais, pais.nombre AS nombre_pais, rol._id AS id_rol, rol.nombre AS nombre_rol, relevancia._id AS id_relevancia, relevancia.nombre AS nombre_relevancia, users.email, users.password, users.username, users.role, users.name, users.lastname FROM persona INNER JOIN colaborador ON colaborador.persona = persona._id INNER JOIN estado_civil ON estado_civil._id = persona.estado_civil INNER JOIN municipio ON municipio._id = persona.municipio INNER JOIN estado ON estado._id = municipio.estado INNER JOIN pais ON pais._id = estado.pais INNER JOIN rol ON rol._id = colaborador.rol INNER JOIN relevancia ON relevancia._id = colaborador.relevancia LEFT JOIN users ON users.id = colaborador.user";
        $colaboradores = $this->getQueries($query);

        return $colaboradores;
    }

    /**
     * 
     */
    public function consultarEstadosCiviles(){
        $query = "SELECT * FROM estado_civil WHERE (estatus + 0) = 1 ORDER BY nombre ASC";
        $estadosCiviles = $this->getQueries($query);

        return $estadosCiviles;
    }
    
    /**
     * 
     */
    public function consultarPaises(){
        $query = "SELECT * FROM pais ORDER BY nombre ASC";
        $paises = $this->getQueries($query);

        return $paises;
    }
    
    /**
     * 
     */
    public function consultarEstados($request){
        $pais = $request->input('pais');

        $query = "SELECT * FROM estado WHERE pais = {$pais} AND (estatus + 0) = 1 ORDER BY nombre ASC";
        $estados = $this->getQueries($query);

        return $estados;
    }
    
    /**
     * 
     */
    public function consultarMunicipios($request){
        $estado = $request->input('estado');

        $query = "SELECT * FROM municipio WHERE estado = {$estado} AND (estatus + 0) = 1 ORDER BY nombre ASC";
        $municipios = $this->getQueries($query);

        return $municipios;
    }

    /**
     * 
     */
    public function consultarRoles(){
        $query = "SELECT * FROM rol WHERE (estatus + 0) = 1 ORDER BY nombre ASC";
        $roles = $this->getQueries($query);

        return $roles;
    }

    /**
     * 
     */
    public function consultarRelevancias(){
        $query = "SELECT * FROM relevancia WHERE (estatus + 0) = 1 ORDER BY nombre ASC";
        $relevancias = $this->getQueries($query);

        return $relevancias;
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