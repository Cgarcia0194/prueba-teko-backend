<?php

namespace App\Http\Controllers\Procesos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Procesos\GrupoSeguridadRepository;
use App\Repositories\General\ResponseRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class GrupoSeguridadController extends Controller
{
    public $responseRepository;
    public $grupoSeguridadRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ResponseRepository $rr, GrupoSeguridadRepository $ar)
    {
        if (Auth::check()) {
            $this->middleware('grupo_seguridad:api', ['except' => [
                'agregar',
                'modificar',
                'desactivar',
                'reactivar',
                'consultar',
                'consultarPlantillas',
                'agregarPlantillasAGrupo',
                'quitarPlantillasAGrupo'
            ]]);
        }

        $this->responseRepository = $rr;
        $this->grupoSeguridadRepository = $ar;
    }

    /**
     *
     **/
    public function agregar(Request $request)
    {
        try {
            $data = $this->grupoSeguridadRepository->agregar($request);

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
    public function modificar(Request $request)
    {
        try {
            $data = $this->grupoSeguridadRepository->modificar($request);

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
    public function desactivar(Request $request)
    {
        try {
            $data = $this->grupoSeguridadRepository->desactivar($request);

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
    public function reactivar(Request $request)
    {
        try {
            $data = $this->grupoSeguridadRepository->reactivar($request);

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
    public function consultar(Request $request)
    {
        try {
            $data = $this->grupoSeguridadRepository->consultar($request);

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
    public function consultarPlantillas(Request $request)
    {
        try {
            $data = $this->grupoSeguridadRepository->consultarPlantillas($request);

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
    public function agregarPlantillasAGrupo(Request $request)
    {
        try {
            $data = $this->grupoSeguridadRepository->agregarPlantillasAGrupo($request);

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
    public function quitarPlantillasAGrupo(Request $request)
    {
        try {
            $data = $this->grupoSeguridadRepository->quitarPlantillasAGrupo($request);

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
