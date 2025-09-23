<?php
// Definición de rutas
use App\Controllers\HomeController;
use App\Controllers\AdminPanelController;
use App\Controllers\SignInController;
use App\Controllers\FaqController;
use App\Controllers\ComplaintsAdminController;
use App\Controllers\ReportsAdminController;
use App\Controllers\SignUpController;
use App\Controllers\SuggestionsAdminController;
use App\Controllers\ProceduresController;
use App\Controllers\MyComplaintsController;
use App\Controllers\BuzonController;
use App\Controllers\PostulateController;
use App\Controllers\ReportarMascotasController;
use App\Controllers\MascotasExtraviadasController;
use App\Controllers\ComplaintsReportsController;
use App\Controllers\ComplaintsSuccessController;
use App\Controllers\AnimalAdminPanelController;
use App\Controllers\NotificationAdminController;
use App\Controllers\DenunciaAnimalController;
use App\Controllers\DenunciaAnimalPublicController;
use App\Controllers\HeaderController;
use App\Controllers\AdminSliderController;
use App\Controllers\SubdepartamentController;
use App\Controllers\LogoutController;
use App\Controllers\HeaderAdminController;
use App\Controllers\AdminPostulationsController;
use App\Controllers\CambiarEstadoMascotaController;
use App\Controllers\ProcesarBuzonController;
use App\Controllers\CreateNotificationController;
use App\Controllers\DeleteCvController;
use App\Controllers\LoadProceduresController;
use App\Controllers\DeleteNotificationController;
use App\Controllers\LoadFaqController;
use App\Controllers\GetChatController;
use App\Controllers\GetSubdepartamentController;
use App\Controllers\SaveSuggestionController;
use App\Controllers\FaqAdminController;
use App\Controllers\ProcessComplaintsController;
use App\Controllers\ReportarMascotasAdminController;

return [
    'GET' => [
    '/denuncia-animal-admin' => [DenunciaAnimalController::class, 'index'],
        '/' => [HomeController::class, 'index'],
        '/home' => [HomeController::class, 'index'],
        '/admin' => [AdminPanelController::class, 'index'],
        '/login' => [SignInController::class, 'showForm'],
        '/faq' => [FaqController::class, 'index'],
        '/complaints-admin' => [ComplaintsAdminController::class, 'index'],
        '/reports-admin' => [ReportsAdminController::class, 'index'],
        '/register' => [SignUpController::class, 'showForm'],
        '/suggestions-admin' => [SuggestionsAdminController::class, 'index'],
        '/procedures' => [ProceduresController::class, 'index'],
        '/my-complaints' => [MyComplaintsController::class, 'index'],
        '/buzon' => [BuzonController::class, 'index'],
        '/postulate' => [PostulateController::class, 'index'],
        '/reportar-mascotas' => [ReportarMascotasController::class, 'index'],
        '/mascotas-extraviadas' => [MascotasExtraviadasController::class, 'index'],
        '/complaints-reports' => [ComplaintsReportsController::class, 'index'],
        '/complaints-success' => [ComplaintsSuccessController::class, 'index'],
        '/animal-admin-panel' => [AnimalAdminPanelController::class, 'index'],
        '/notification-admin' => [NotificationAdminController::class, 'index'],
    '/denuncia-animal' => [DenunciaAnimalPublicController::class, 'index'],
        '/header' => [HeaderController::class, 'index'],
        '/view-cv' => [App\Controllers\ViewCvController::class, 'index'],
    '/admin-slider' => [AdminSliderController::class, 'index'],
    '/admin-slider-delete' => [AdminSliderDeleteController::class, 'index'],
        '/subdepartament' => [SubdepartamentController::class, 'index'],
        '/logout' => [LogoutController::class, 'index'],
        '/header-admin' => [HeaderAdminController::class, 'index'],
        '/admin-postulations' => [AdminPostulationsController::class, 'index'],
        '/cambiar-estado-mascota' => [CambiarEstadoMascotaController::class, 'index'],
        '/procesar-buzon' => [ProcesarBuzonController::class, 'index'],
        '/create-notification' => [CreateNotificationController::class, 'index'],
        '/delete-cv' => [DeleteCvController::class, 'index'],
        '/load-procedures' => [LoadProceduresController::class, 'index'],
        '/delete-notification' => [DeleteNotificationController::class, 'index'],
        '/load-faq' => [LoadFaqController::class, 'index'],
        '/get-chat' => [GetChatController::class, 'index'],
        '/get-subdepartament' => [GetSubdepartamentController::class, 'index'],
        '/save-suggestion' => [SaveSuggestionController::class, 'index'],
        '/faq-admin' => [FaqAdminController::class, 'index'],
        '/process-complaints' => [ProcessComplaintsController::class, 'index'],
        '/reportar-mascotas-admin' => [ReportarMascotasAdminController::class, 'index'],
        '/admin-panel' => [AdminPanelController::class, 'index'],
        '/sign-up' => [SignUpController::class, 'index'],
        '/suggestions_admin' => [SuggestionsAdminController::class, 'index'],
    ],
    'POST' => [
    '/denuncia-animal-admin' => [DenunciaAnimalController::class, 'index'],
    '/denuncia-animal' => [DenunciaAnimalPublicController::class, 'index'],
        '/login' => [SignInController::class, 'login'],
        '/register' => [SignUpController::class, 'register'],
        '/sign-up' => [SignUpController::class, 'index'],
        '/process-complaints' => [ProcessComplaintsController::class, 'index'],
        '/my-complaints' => [MyComplaintsController::class, 'index'],
        '/buzon/procesar' => [BuzonController::class, 'procesar'],
        '/suggestions_admin/eliminar' => [SuggestionsAdminController::class, 'eliminar'],
        '/delete-cv' => [DeleteCvController::class, 'index'],
        '/postulate/process' => [PostulateController::class, 'process'],
        '/reportar-mascota' => [ReportarMascotasController::class, 'store'],
        '/cambiar-estado-mascota' => [CambiarEstadoMascotaController::class, 'index'],
    ],
];
