<?php

namespace App\Http\Controllers\Procesos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Procesos\ConfiguracionRepository;
use App\Repositories\General\ResponseRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ConfiguracionController extends Controller
{
    public $responseRepository;
    public $configuracionRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ResponseRepository $rr, ConfiguracionRepository $cr)
    {
        if (Auth::check()) {
            $this->middleware('configuracion:api', ['except' => [
                'actualizarPreferencias',
            ]]);
        }

        $this->responseRepository = $rr;
        $this->configuracionRepository = $cr;
    }

    /**
     *
     **/
    public function actualizarPreferencias(Request $request)
    {
        try {
            $data = $this->configuracionRepository->actualizarPreferencias($request);

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
