<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\General\GeneralController;
use App\Http\Controllers\Procesos\GrupoSeguridadController;
use App\Http\Controllers\Procesos\PlantillaController;
use App\Http\Controllers\Procesos\PermisosUsuarioController;
use App\Http\Controllers\Procesos\ConfiguracionController;
use App\Http\Controllers\Procesos\MiPerfilController;
use App\Http\Controllers\Catalogos\ColaboradorController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api'
], function ($router) {

    /**
     * Authentication Module
     */
    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
    });

    /**
     * General Module
     */
    Route::group(['prefix' => 'general'], function () {
        Route::post('consultar-plantillas', [GeneralController::class, 'consultarPlantillas']);
        Route::post('consultar-estadisticas', [GeneralController::class, 'consultarEstadisticas']);
        Route::post('consultar-proyectos-por-modelo-de-calidad', [GeneralController::class, 'consultarProyectosPorModeloDeCalidad']);
        Route::post('consultar-proyectos-por-tipo', [GeneralController::class, 'consultarProyectosPorTipo']);
        Route::post('consultar-proyectos-por-tamanio', [GeneralController::class, 'consultarProyectosPorTamanio']);
    });

    /**
     * Plantillas Module
     */
    Route::group(['prefix' => 'plantilla'], function () {
        Route::post('consultar', [PlantillaController::class, 'consultar']);
    });

    /**
     * Grupo de seguridad Module
     */
    Route::group(['prefix' => 'grupo_seguridad'], function () {
        Route::post('agregar', [GrupoSeguridadController::class, 'agregar']);
        Route::post('modificar', [GrupoSeguridadController::class, 'modificar']);
        Route::post('desactivar', [GrupoSeguridadController::class, 'desactivar']);
        Route::post('reactivar', [GrupoSeguridadController::class, 'reactivar']);
        Route::post('consultar', [GrupoSeguridadController::class, 'consultar']);
        Route::post('consultar-plantillas', [GrupoSeguridadController::class, 'consultarPlantillas']);
        Route::post('agregar-plantilla-a-grupo', [GrupoSeguridadController::class, 'agregarPlantillasAGrupo']);
        Route::post('quitar-plantilla-a-grupo', [GrupoSeguridadController::class, 'quitarPlantillasAGrupo']);
    });

    /**
     * Permisos usuario Module
     */
    Route::group(['prefix' => 'permisos_usuario'], function () {
        Route::post('agregar', [PermisosUsuarioController::class, 'agregar']);
        Route::post('eliminar', [PermisosUsuarioController::class, 'eliminar']);
        Route::post('consultar-grupos-seguridad', [PermisosUsuarioController::class, 'consultarGruposSeguridad']);
        Route::post('consultar-usuarios-con-permiso', [PermisosUsuarioController::class, 'consultarUsuariosConPermiso']);
        Route::post('consultar-usuarios-sin-permiso', [PermisosUsuarioController::class, 'consultarUsuariosSinPermiso']);
    });

    /**
     * Configuración Module
     */
    Route::group(['prefix' => 'configuracion'], function () {
        Route::post('actualizar-preferencias', [ConfiguracionController::class, 'actualizarPreferencias']);
    });

    /**
     * Mi Perfil Module
     */
    Route::group(['prefix' => 'mi_perfil'], function () {
        Route::post('modificar', [MiPerfilController::class, 'modificar']);
        Route::post('cambiar', [MiPerfilController::class, 'cambiar']);
        Route::post('consultar', [MiPerfilController::class, 'consultar']);
        Route::post('consultar-estados-civiles', [MiPerfilController::class, 'consultarEstadosCiviles']);
        Route::post('consultar-paises', [MiPerfilController::class, 'consultarPaises']);
        Route::post('consultar-estados', [MiPerfilController::class, 'consultarEstados']);
        Route::post('consultar-municipios', [MiPerfilController::class, 'consultarMunicipios']);
    });

    /**
     * Colaborador Module
     */
    Route::group(['prefix' => 'colaborador'], function () {
        Route::post('agregar', [ColaboradorController::class, 'agregar']);
        Route::post('modificar', [ColaboradorController::class, 'modificar']);
        Route::post('desactivar-reactivar', [ColaboradorController::class, 'desactivarReactivar']);
        Route::post('reactivar', [ColaboradorController::class, 'reactivar']);
        Route::post('consultar', [ColaboradorController::class, 'consultar']);
        Route::post('consultar-estados-civiles', [ColaboradorController::class, 'consultarEstadosCiviles']);
        Route::post('consultar-paises', [ColaboradorController::class, 'consultarPaises']);
        Route::post('consultar-estados', [ColaboradorController::class, 'consultarEstados']);
        Route::post('consultar-municipios', [ColaboradorController::class, 'consultarMunicipios']);
        Route::post('consultar-roles', [ColaboradorController::class, 'consultarRoles']);
        Route::post('consultar-relevancias', [ColaboradorController::class, 'consultarRelevancias']);
    });

    /**
     * Assets Module
     */
    Route::get('storage/users/{filename}', function ($filename) {
        $path = storage_path('/images/' . $filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    });
});