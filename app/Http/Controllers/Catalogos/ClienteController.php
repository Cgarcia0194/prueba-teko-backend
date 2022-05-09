<?php

namespace App\Http\Controllers\Catalogos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Catalogos\ClienteRepository;
use App\Repositories\General\ResponseRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller{
    public $responseRepository;
    public $clienteRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ResponseRepository $responseRepository, ClienteRepository $clienteRepository){
        if (Auth::check()) {
            $this->middleware('cliente:api', ['except' => [
                'create',
                'update',
                'deactivateReactivate',
                'getClients',
                'getServices',
            ]]);
        }

        $this->responseRepository = $responseRepository;
        $this->clienteRepository = $clienteRepository;
    }

    /**
     *
     **/
    public function create(Request $request){
        try {
            $data = $this->clienteRepository->create($request);

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
    public function update(Request $request){
        try {
            $data = $this->clienteRepository->update($request);

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
    public function deactivateReactivate(Request $request){
        try {
            $data = $this->clienteRepository->deactivateReactivate($request);

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
    public function getClients(Request $request){
        try {
            $data = $this->clienteRepository->getClients($request);

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
    public function getServices(Request $request){
        try {
            $data = $this->clienteRepository->getServices($request);

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