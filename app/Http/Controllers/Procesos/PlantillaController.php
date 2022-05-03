<?php

namespace App\Http\Controllers\Procesos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Procesos\PlantillaRepository;
use App\Repositories\General\ResponseRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PlantillaController extends Controller
{
    public $responseRepository;
    public $plantillaRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ResponseRepository $rr, PlantillaRepository $ar)
    {
        if (Auth::check()) {
            $this->middleware('plantilla:api', ['except' => [
                'consultar'
            ]]);
        }

        $this->responseRepository = $rr;
        $this->plantillaRepository = $ar;
    }

    /**
     *
     **/
    public function consultar(Request $request)
    {
        try {
            $data = $this->plantillaRepository->consultar();

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
