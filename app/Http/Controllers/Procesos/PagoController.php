<?php

namespace App\Http\Controllers\Procesos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Procesos\PagoRepository;
use App\Repositories\General\ResponseRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    public $responseRepository;
    public $pagoRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ResponseRepository $responseRepository, PagoRepository $pagoRepository)
    {
        if (Auth::check()) {
            $this->middleware('pago:api', ['except' => [
                'create',
                'cancelPayment',
                'getClients',
                'getPaymentHistory',
            ]]);
        }

        $this->responseRepository = $responseRepository;
        $this->pagoRepository = $pagoRepository;
    }

    /**
     *
     **/
    public function create(Request $request)
    {
        try {
            $data = $this->pagoRepository->create($request);

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
    public function cancelPayment(Request $request)
    {
        try {
            $data = $this->pagoRepository->cancelPayment($request);

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
    public function getClients(Request $request)
    {
        try {
            $data = $this->pagoRepository->getClients($request);

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
    public function getPaymentHistory(Request $request)
    {
        try {
            $data = $this->pagoRepository->getPaymentHistory($request);

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
