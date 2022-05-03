<?php

namespace App\Http\Controllers\Procesos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Procesos\PermisosUsuarioRepository;
use App\Repositories\General\ResponseRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PermisosUsuarioController extends Controller
{
    public $responseRepository;
    public $permisosUsuarioRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ResponseRepository $rr, PermisosUsuarioRepository $ar)
    {
        if (Auth::check()) {
            $this->middleware('permisos_usuario:api', ['except' => [
                'agregar',
                'eliminar',
                'consultarGruposSeguridad',
                'consultarUsuariosConPermiso',
                'consultarUsuariosSinPermiso'
            ]]);
        }

        $this->responseRepository = $rr;
        $this->permisosUsuarioRepository = $ar;
    }

    /**
     *
     **/
    public function agregar(Request $request)
    {
        try {
            $data = $this->permisosUsuarioRepository->agregar($request);

            return $this->responseRepository->Response($data);
        } catch (\Exception $e) {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Excepción",
                "content" => $e->getMessage(),
                "type" => "exception",
            ];

            return $this->responseRepository->Response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     **/
    public function eliminar(Request $request)
    {
        try {
            $data = $this->permisosUsuarioRepository->eliminar($request);

            return $this->responseRepository->Response($data);
        } catch (\Exception $e) {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Excepción",
                "content" => $e->getMessage(),
                "type" => "exception",
            ];

            return $this->responseRepository->Response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     **/
    public function consultarGruposSeguridad()
    {
        try {
            $data = $this->permisosUsuarioRepository->consultarGruposSeguridad();

            return $this->responseRepository->Response($data);
        } catch (\Exception $e) {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Excepción",
                "content" => $e->getMessage(),
                "type" => "exception",
            ];

            return $this->responseRepository->Response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     **/
    public function consultarUsuariosConPermiso(Request $request)
    {
        try {
            $data = $this->permisosUsuarioRepository->consultarUsuariosConPermiso($request);

            return $this->responseRepository->Response($data);
        } catch (\Exception $e) {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Excepción",
                "content" => $e->getMessage(),
                "type" => "exception",
            ];

            return $this->responseRepository->Response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     *
     **/
    public function consultarUsuariosSinPermiso(Request $request)
    {
        try {
            $data = $this->permisosUsuarioRepository->consultarUsuariosSinPermiso($request);

            return $this->responseRepository->Response($data);
        } catch (\Exception $e) {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Excepción",
                "content" => $e->getMessage(),
                "type" => "exception",
            ];

            return $this->responseRepository->Response($response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
