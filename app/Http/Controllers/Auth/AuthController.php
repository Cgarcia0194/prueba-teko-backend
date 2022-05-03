<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\General\ResponseRepository;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public $responseRepository;
    public $authRepository;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(ResponseRepository $rr, AuthRepository $ar)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->responseRepository = $rr;
        $this->authRepository = $ar;
    }

    /**
     * @OA\POST(
     *     path="/api/auth/login",
     *     tags={"Authentication"},
     *     summary="Login",
     *     description="Login",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="email", type="string", example="manirujjamanakash@gmail.com"),
     *              @OA\Property(property="password", type="string", example="123456")
     *          ),
     *      ),
     *      @OA\Response(response=200, description="Login" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     * @OA\SecurityScheme(
     *   securityScheme="Bearer",type="apiKey",description="JWT",name="Authorization",in="header",
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if ($token = Auth::guard()->attempt($credentials)) {
                $data =  $this->respondWithToken($token);

                if (!empty($data)) {
                    $response["status"] = "success";
                    $response["data"] = $data;
                    $response["message"] = [
                        "title" => "Correcto",
                        "content" => "Información consultada correctamente",
                        "type" => "success",
                    ];
                } else {
                    $response["status"] = "info";
                    $response["data"] = [];
                    $response["message"] = [
                        "title" => "Información",
                        "content" => "No hay información disponible",
                        "type" => "info",
                    ];
                }

                return $this->responseRepository->Response($response);
            } else {
                $response["status"] = "info";
                $response["data"] = [];
                $response["message"] = [
                    "title" => "Información",
                    "content" => "El intento de acceder falló.",
                    "type" => "info",
                ];

                return $this->responseRepository->Response($response, Response::HTTP_UNAUTHORIZED);
            }
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
     * @OA\POST(
     *     path="/api/auth/register",
     *     tags={"Authentication"},
     *     summary="Register User",
     *     description="Register New User",
     *     @OA\RequestBody(
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string", example="Jhon Doe"),
     *              @OA\Property(property="email", type="string", example="jhondoe@example.com"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *              @OA\Property(property="password_confirmation", type="string", example="123456")
     *          ),
     *      ),
     *      @OA\Response(response=200, description="Register New User Data" ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found")
     * )
     */
    public function register(RegisterRequest $request)
    {
        try {
            $requestData = $request->only('name', 'email', 'password', 'password_confirmation');
            $user = $this->authRepository->register($requestData);

            if ($user) {
                if ($token = Auth::guard()->attempt($requestData)) {
                    $data =  $this->respondWithToken($token);
                    return $this->responseRepository->ResponseSuccess($data, 'User Registered and Logged in Successfully', Response::HTTP_OK);
                }
            }
        } catch (\Exception $e) {
            return $this->responseRepository->ResponseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\GET(
     *     path="/api/auth/me",
     *     tags={"Authentication"},
     *     summary="Authenticated User Profile",
     *     description="Authenticated User Profile",
     *     @OA\Response(response=200, description="Authenticated User Profile" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function me()
    {
        try {
            $data = Auth::guard()->user();

            return $this->responseRepository->ResponseSuccess($data, 'Profile Fetched Successfully !');
        } catch (\Exception $e) {
            return $this->responseRepository->ResponseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api/auth/logout",
     *     tags={"Authentication"},
     *     summary="Logout",
     *     description="Logout",
     *     @OA\Response(response=200, description="Logout" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function logout()
    {
        try {
            Auth::guard()->logout();

            return $this->responseRepository->ResponseSuccess(null, 'Logged out successfully !');
        } catch (\Exception $e) {
            return $this->responseRepository->ResponseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\POST(
     *     path="/api/auth/refresh",
     *     tags={"Authentication"},
     *     summary="Refresh",
     *     description="Refresh",
     *     @OA\Response(response=200, description="Refresh" ),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function refresh()
    {
        try {
            $data = $this->respondWithToken(Auth::guard()->refresh());

            return $this->responseRepository->ResponseSuccess($data, 'Token Refreshed Successfully !');
        } catch (\Exception $e) {
            return $this->responseRepository->ResponseError(null, $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        $data = [[
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard()->factory()->getTTL() * 60 * 24 * 30, // 43200 Minutes = 30 Days
            'user' => $this->authRepository->obtenerInformacionUsuario(Auth::guard()->user()->id)
        ]];
        return $data[0];
    }
}
