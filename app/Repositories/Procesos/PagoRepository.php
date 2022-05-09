<?php

namespace App\Repositories\Procesos;

use Illuminate\Support\Facades\DB;
use App\Repositories\Auth\AuthRepository;

class PagoRepository
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
     * 
     */
    public function create($request)
    {
        $query = 'INSERT INTO pagos (total, recargo, fecha_pago, cliente, estatus, fecha_registro) 
        VALUES(?, ?, ?, ?, ?, ?)';

        $fields = [
            $request->input('total'),
            $request->input('recargo'),
            $request->input('fecha_pago'),
            $request->input('cliente'),
            2,
            date("Y-m-d H:i:s"),
        ];

        $newService = $this->createQueries($query, $fields);

        return $newService;
    }

    /**
     * 
     */
    public function cancelPayment($request)
    {
        $query = "UPDATE pagos SET estatus = ? WHERE _id = ?";

        $fields = [
            $request->input('canceladoPagado'),
            $request->input('_id'),
        ];

        $response = $this->updateQueries($query, $fields);

        return $response;
    }

    /**
     * Get All
     *
     * @return collections Array
     */
    public function getClients()
    {
        $query = "SELECT clientes.*, IFNULL( clientes.fecha_egreso, 'Indefinida' ) AS fecha_egreso_validada, CONCAT( clientes.nombre, ' ', clientes.apellido_paterno, ' ', clientes.apellido_materno ) AS nombre_completo_cliente, servicios._id AS servicios_id, servicios.nombre AS servicios_nombre, servicios.costo AS servicios_costo, servicios.descripcion AS servicios_descripcion, servicios.periodicidad AS servicios_periodicidad, (servicios.periodicidad + 0) AS servicios_periodicidad_indice FROM clientes INNER JOIN servicios ON servicios._id = clientes.servicio";
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

    /**
     * 
     */
    private function updateQueries($query, $fields)
    {
        DB::beginTransaction();

        $respuesta = DB::update($query, $fields);

        if ($respuesta || $respuesta == 0) {

            $response["status"] = "success";
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se modificó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();

            return $response;
        }

        $response["status"] = "info";
        $response["user"] = [];
        $response["message"] = [
            "title" => "Información",
            "content" => "Ocurrió un error al modificar el registro",
            "type" => "info",
        ];
        DB::rollback();

        return $response;
    }

    /**
     * 
     */
    private function createQueries($query, $fields)
    {
        DB::beginTransaction();

        DB::insert($query, $fields);

        $id = DB::getPdo()->lastInsertId();

        if ($id > 0) {
            $response["id"] = $id;
            $response["status"] = "success";
            $response["data"] = [];
            $response["message"] = [
                "title" => "Correcto",
                "content" => "Se agregó correctamente el registro",
                "type" => "success",
            ];

            DB::commit();
            return $response;
        }

        $response["status"] = "info";
        $response["data"] = [];
        $response["message"] = [
            "title" => "Información",
            "content" => "Ocurrió un error al agregar el registro",
            "type" => "info",
        ];

        DB::rollback();
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
        $serviciosNombre = $fields[4];
        $serviciosPeriodicidad = $fields[5];
        $serviciosCosto = $fields[6];
        $paymentDates = [];

        for ($i = $fechaIngreso; $i <= $fechaEgreso; $i = date("Y-m-d", strtotime($i . $periodicity))) {
            $query = "SELECT 'Si' AS es_registrado, pagos.*, servicios.nombre AS servicios_nombre FROM pagos INNER JOIN clientes ON clientes._id = pagos.cliente INNER JOIN servicios ON servicios._id = clientes.servicio WHERE pagos.cliente = {$idClient} AND fecha_pago = '{$i}'";
            $paymentDB = json_decode(json_encode(DB::select($query)), true);

            if (empty($paymentDB)) {
                $paymentDate["_id"] = 0;
                $paymentDate["es_registrado"] = 'No';
                $paymentDate["total"] = 0;
                $paymentDate["recargo"] = 0;
                $paymentDate["fecha_pago"] = $i;
                $paymentDate["cliente"] = $idClient;
                $paymentDate["estatus"] = "Por pagar";
                $paymentDate["servicioNombre"] = $serviciosNombre;
                $paymentDate["servicioPeriodicidad"] = $serviciosPeriodicidad;
                $paymentDate["servicioCosto"] = $serviciosCosto;
                $paymentDate["fecha_registro"] = date('Y-m-d');
            } else {
                $paymentDate["_id"] = $paymentDB[0]['_id'];
                $paymentDate["es_registrado"] = $paymentDB[0]['es_registrado'];
                $paymentDate["total"] = $paymentDB[0]['total'];
                $paymentDate["recargo"] = $paymentDB[0]['recargo'];
                $paymentDate["fecha_pago"] = $paymentDB[0]['fecha_pago'];
                $paymentDate["cliente"] = $paymentDB[0]['cliente'];
                $paymentDate["estatus"] = $paymentDB[0]['estatus'];
                $paymentDate["servicioNombre"] = $serviciosNombre;
                $paymentDate["servicioPeriodicidad"] = $serviciosPeriodicidad;
                $paymentDate["servicioCosto"] = $serviciosCosto;
                $paymentDate["fecha_registro"] = $paymentDB[0]['fecha_registro'];
            }

            array_push($paymentDates, $paymentDate);
        }

        if (is_null($paymentDates) || empty($paymentDates)) {
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
        $response["data"] = $paymentDates;
        $response["message"] = [
            "title" => "Correcto",
            "content" => "Información consultada correctamente",
            "type" => "success",
        ];

        return $response;
    }
}
