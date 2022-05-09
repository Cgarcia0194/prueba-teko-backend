<?php

namespace App\Http\Controllers\Catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Catalogos\ServicioRepository;
use App\Repositories\General\ResponseRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ServicioController extends Controller
{
    public $responseRepository;
    public $servicioRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ResponseRepository $responseRepository, ServicioRepository $servicioRepository)
    {
        if (Auth::check()) {
            $this->middleware('servicios:api', ['except' => [
                'create',
                'update',
                'deactivateReactivate',
                'getServices',
            ]]);
        }

        $this->responseRepository = $responseRepository;
        $this->servicioRepository = $servicioRepository;
    }

    /**
     *
     **/
    public function create(Request $request)
    {
        try {
            $data = $this->servicioRepository->create($request);

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
    public function update(Request $request)
    {
        try {
            $data = $this->servicioRepository->update($request);

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
    public function deactivateReactivate(Request $request)
    {
        try {
            $data = $this->servicioRepository->deactivateReactivate($request);

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
    public function getServices(Request $request)
    {
        try {
            $data = $this->servicioRepository->getServices($request);

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
