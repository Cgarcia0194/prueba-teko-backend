<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Repositories\General\GeneralRepository;
use App\Repositories\General\ResponseRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
    public $responseRepository;
    public $generalRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ResponseRepository $rr, GeneralRepository $ar)
    {
        if (Auth::check()) {
            $this->middleware('general:api', ['except' => [
                'consultarPlantillas',
                'consultarEstadisticas',
            ]]);
        } else {
            $this->middleware('general:api', ['except' => [
                'consultarPlantillas'
            ]]);
        }

        $this->responseRepository = $rr;
        $this->generalRepository = $ar;
    }

    /**
     *
     **/
    public function consultarPlantillas()
    {
        try {
            $data = $this->generalRepository->consultarPlantillas();

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
    public function consultarEstadisticas()
    {
        try {
            $data = $this->generalRepository->consultarEstadisticas();

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
