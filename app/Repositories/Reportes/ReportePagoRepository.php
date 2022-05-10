<?php

namespace App\Repositories\Reportes;

use Illuminate\Support\Facades\DB;
use App\Repositories\Auth\AuthRepository;

class ReportePagoRepository
{

    public $authRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Get All
     *
     * @return collections Array
     */
    public function getClients()
    {
        $query = "SELECT clientes.*, IFNULL( clientes.fecha_egreso, 'Indefinida' ) AS fecha_egreso_validada, CONCAT( clientes.nombre, ' ', clientes.apellido_paterno, ' ', clientes.apellido_materno ) AS nombre_completo_cliente, servicios._id AS servicios_id, servicios.nombre AS servicios_nombre, servicios.costo AS servicios_costo, servicios.descripcion AS servicios_descripcion, servicios.periodicidad AS servicios_periodicidad, (servicios.periodicidad + 0) AS servicios_periodicidad_indice FROM clientes INNER JOIN servicios ON servicios._id = clientes.servicio WHERE clientes.estatus + 0 = 1";
        $clients = $this->getQueries($query);

        return $clients;
    }

    /**
     * Get All
     *
     * @return collections Array
     */
    public function getPaymentHistory($request)
    {
        $fields = [
            $request->input('fecha_ingreso'),
            $request->input('fecha_egreso_validada'),
            $request->input('servicios_periodicidad_indice'),
            $request->input('_id'),
            $request->input('servicios_nombre'),
            $request->input('servicios_periodicidad'),
            $request->input('servicios_costo')
        ];

        $paymentDates = $this->setPayments($fields);

        return $paymentDates;
    }

    /**
     * 
     */
    private function getQueries($query)
    {
        $data = DB::select($query);

        if (empty($data)) {
            $response["status"] = "info";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Información",
                "content" => "No hay información disponible",
                "type" => "info",
            ];
            return $response;
        }

        $response["status"] = "success";
        $response["data"] = $data;
        $response["message"] = [
            "title" => "Correcto",
            "content" => "Información consultada correctamente",
            "type" => "success",
        ];

        return $response;
    }

    private function setPayments($fields)
    {
        $periodicities = [
            '+ 1 year',
            '+ 1 month',
            '+ 14 days',
            '+ 6 month'
        ];

        $currentDate = date('Y-m-d');
        $fechaIngreso = $fields[0];
        $fechaEgreso = $fields[1] === "Indefinida" ? $currentDate : $fields[1];
        $periodicity = $periodicities[$fields[2] - 1];
        $idClient = $fields[3];
        $pendings = 0;

        for ($i = $fechaIngreso; $i <= $fechaEgreso; $i = date("Y-m-d", strtotime($i . $periodicity))) {
            $query = "SELECT 'Si' AS es_registrado, pagos.*, servicios.nombre AS servicios_nombre FROM pagos INNER JOIN clientes ON clientes._id = pagos.cliente INNER JOIN servicios ON servicios._id = clientes.servicio WHERE pagos.cliente = {$idClient} AND fecha_pago = '{$i}'";
            $paymentDB = json_decode(json_encode(DB::select($query)), true);

            if (empty($paymentDB)) {
                $pendings = $pendings + 1;
            }
        }

        $query = "SELECT {$pendings} AS pendientes, COUNT( _id ) AS pagados, ( SELECT COUNT( _id ) FROM pagos WHERE cliente = {$idClient} AND estatus + 0 = 1 ) AS cancelados FROM pagos WHERE cliente = {$idClient} AND estatus + 0 = 2";
        $paymentHistory = $this->getQueries($query);

        return $paymentHistory;
    }
}
