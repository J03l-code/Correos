<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\LinkController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\MediaController;

// ==========================================
// RUTAS PÚBLICAS
// ==========================================
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::get('/acceso/{slug}', [PublicController::class, 'access'])->name('public.access');
Route::post('/acceso/{slug}/verificar', [PublicController::class, 'verifyPassword'])->name('public.verify');
Route::post('/acceso/{slug}/redireccionar', [PublicController::class, 'redirect'])->name('public.redirect');
Route::get('/politica-de-privacidad', [PublicController::class, 'privacy'])->name('public.privacy');
Route::get('/sitemap.xml', [PublicController::class, 'sitemap'])->name('public.sitemap');
Route::get('/robots.txt', [PublicController::class, 'robots'])->name('public.robots');

// ==========================================
// RUTAS DE AUTENTICACIÓN
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/cambiar-contrasena', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/cambiar-contrasena', [AuthController::class, 'changePassword']);
});

// ==========================================
// RUTAS DE ADMINISTRACIÓN
// ==========================================
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard & Stats
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/estadisticas', [AdminController::class, 'statistics'])->name('admin.statistics');
    Route::get('/estadisticas/exportar', [AdminController::class, 'exportCsv'])->name('admin.export');
    Route::get('/registro-actividad', [AdminController::class, 'activityLog'])->name('admin.activity');

    // Secciones CRUD
    Route::post('/sections/{section}/toggle', [SectionController::class, 'toggle'])->name('sections.toggle');
    Route::post('/sections/{section}/duplicate', [SectionController::class, 'duplicate'])->name('sections.duplicate');
    Route::post('/sections/{section}/up', [SectionController::class, 'moveUp'])->name('sections.up');
    Route::post('/sections/{section}/down', [SectionController::class, 'moveDown'])->name('sections.down');
    Route::resource('sections', SectionController::class);

    // Enlaces CRUD
    Route::post('/links/{link}/toggle', [LinkController::class, 'toggle'])->name('links.toggle');
    Route::post('/links/{link}/duplicate', [LinkController::class, 'duplicate'])->name('links.duplicate');
    Route::post('/links/{link}/up', [LinkController::class, 'moveUp'])->name('links.up');
    Route::post('/links/{link}/down', [LinkController::class, 'moveDown'])->name('links.down');
    Route::get('/links/{link}/qr', [LinkController::class, 'showQr'])->name('links.qr');
    Route::resource('links', LinkController::class);

    // Avisos CRUD
    Route::post('/announcements/{announcement}/toggle', [AnnouncementController::class, 'toggle'])->name('announcements.toggle');
    Route::post('/announcements/{announcement}/up', [AnnouncementController::class, 'moveUp'])->name('announcements.up');
    Route::post('/announcements/{announcement}/down', [AnnouncementController::class, 'moveDown'])->name('announcements.down');
    Route::resource('announcements', AnnouncementController::class);

    // Preguntas Frecuentes CRUD
    Route::post('/faqs/{faq}/toggle', [FaqController::class, 'toggle'])->name('faqs.toggle');
    Route::post('/faqs/{faq}/up', [FaqController::class, 'moveUp'])->name('faqs.up');
    Route::post('/faqs/{faq}/down', [FaqController::class, 'moveDown'])->name('faqs.down');
    Route::resource('faqs', FaqController::class);

    // Navegación CRUD
    Route::post('/navigation/{navigation}/toggle', [NavigationController::class, 'toggle'])->name('navigation.toggle');
    Route::post('/navigation/{navigation}/up', [NavigationController::class, 'moveUp'])->name('navigation.up');
    Route::post('/navigation/{navigation}/down', [NavigationController::class, 'moveDown'])->name('navigation.down');
    Route::resource('navigation', NavigationController::class);

    // Gestor de Medios
    Route::get('/medios', [MediaController::class, 'index'])->name('media.index');
    Route::post('/medios', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/medios/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');
    Route::post('/medios/{medium}/alt', [MediaController::class, 'updateAlt'])->name('media.alt');

    // ==========================================
    // RUTAS EXCLUSIVAS DEL SUPERADMINISTRADOR
    // ==========================================
    Route::middleware(['role:superadmin'])->group(function () {
        // Usuarios CRUD
        Route::post('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
        Route::resource('users', UserController::class);

        // Configuración General
        Route::get('/configuracion', [SettingsController::class, 'index'])->name('admin.settings');
        Route::post('/configuracion', [SettingsController::class, 'update']);
        Route::post('/configuracion/colores/restaurar', [SettingsController::class, 'restoreColors'])->name('admin.settings.restore_colors');
        Route::post('/configuracion/redes', [SettingsController::class, 'storeSocialLink'])->name('admin.settings.social');
        Route::delete('/configuracion/redes/{socialLink}', [SettingsController::class, 'destroySocialLink'])->name('admin.settings.social.destroy');

        // Vaciar Estadísticas
        Route::post('/estadisticas/vaciar', [AdminController::class, 'clearStats'])->name('admin.clear_stats');
    });
});
