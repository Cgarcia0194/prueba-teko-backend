<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthRepository
{

    public function register(array $data)
    {
        $data = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ];
        $user = User::create($data);
        return $user;
    }

    public function obtenerInformacionUsuario($id_user)
    {
        $user = DB::select("SELECT id AS id_user, username, name, lastname, email FROM users WHERE id = {$id_user}")[0];

        $user->f_perfil = DB::select("SELECT persona.f_perfil FROM users INNER JOIN colaborador ON users.id = colaborador.`user` INNER JOIN persona ON persona._id = colaborador.persona WHERE users.id = {$id_user}")[0]->f_perfil;
        $user->f_portada = DB::select("SELECT persona.f_portada FROM users INNER JOIN colaborador ON users.id = colaborador.`user` INNER JOIN persona ON persona._id = colaborador.persona WHERE users.id = {$id_user}")[0]->f_portada;

        $file_keys = ['f_perfil', 'f_portada'];;
        foreach ($file_keys as $key) {
            if (!is_null($user->{$key})) {
                $archivo = $this->get_file($user->{$key});

                $user->{$key} = !empty($archivo) ? [
                    'id_archivo' => $archivo->id_archivo,
                    'estatus' => $archivo->estatus,
                    'url' => $this->get_file_url($archivo),
                    'subio' => $this->get_autor_archivo($archivo),
                    'autorizo' => $this->get_autorizacion_archivo($archivo),
                    'nombre' => $archivo->nombre
                ] : [
                    'id_archivo' => false,
                    'estatus' => 'Pendiente',
                    'url' => '#',
                    'subio' => '',
                    'autorizo' => '',
                    'nombre' => ''
                ];
            } else {
                $user->{$key} = [
                    'id_archivo' => false,
                    'estatus' => 'Pendiente',
                    'url' => '#',
                    'subio' => '',
                    'autorizo' => '',
                    'nombre' => ''
                ];
            }
        }

        $user->aplicacion = DB::select("SELECT nombre FROM aplicacion WHERE _id = 1")[0];
        $user->configuracion = DB::select("SELECT color, complemento, modo_oscuro, modo_pantalla_completa, modo_input FROM configuracion WHERE user = {$id_user}")[0];

        return $user;
    }

    public function get_file($id_archivo)
    {
        $file = DB::select("SELECT * FROM archivo WHERE id_archivo = '{$id_archivo}'")[0];

        return !empty($file) ? $file : false;
    }

    public function get_file_url($archivo)
    {
        return asset('api/storage/users/' . $archivo->path);
    }

    function get_autor_archivo($archivo)
    {
        return $this->get_user_fullname($archivo->id_usuario);
    }

    function get_autorizacion_archivo($archivo)
    {
        if ($archivo->estatus == 'Pendiente' || empty($archivo->id_autoriza)) {
            return '';
        } else {
            return $this->get_user_fullname($archivo->id_autoriza);
        }
    }

    function get_user_fullname($id_user)
    {
        return DB::select("SELECT CONCAT(name, ' ', lastname) AS nombre FROM users WHERE id = {$id_user}")[0]->nombre;
    }
}
