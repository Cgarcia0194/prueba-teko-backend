<?php

namespace App\Http\Controllers\Reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Reportes\ReportePagoRepository;
use App\Repositories\General\ResponseRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ReportePagoController extends Controller
{
    public $responseRepository;
    public $reportePagoRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ResponseRepository $responseRepository, ReportePagoRepository $reportePagoRepository)
    {
        if (Auth::check()) {
            $this->middleware('reporte_pago:api', ['except' => [
                'getClients',
                'getPaymentHistory',
            ]]);
        }

        $this->responseRepository = $responseRepository;
        $this->reportePagoRepository = $reportePagoRepository;
    }

    /**
     *
     **/
    public function getClients(Request $request)
    {
        try {
            $data = $this->reportePagoRepository->getClients($request);

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
            $data = $this->reportePagoRepository->getPaymentHistory($request);

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
