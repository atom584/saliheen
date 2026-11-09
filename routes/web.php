<?php
declare(strict_types=1);

use App\Controllers\Api\Account\AvatarController as ApiAvatarController;
use App\Controllers\Api\Auth\AuthApiController;
use App\Controllers\Api\OAuth\OAuthController;
use App\Controllers\Api\Services\ServiceSelectionController;
use App\Controllers\Pages\Admin\DashboardController as AdminDashboardController;
use App\Controllers\Pages\Admin\ServiceManagementController;
use App\Controllers\Pages\Auth\AdminLoginController;
use App\Controllers\Pages\Auth\ForgotPasswordController;
use App\Controllers\Pages\Auth\LoginController;
use App\Controllers\Pages\Auth\RegisterCompleteController;
use App\Controllers\Pages\Auth\RegisterController;
use App\Controllers\Pages\Auth\ResetPasswordController;
use App\Controllers\Pages\Auth\VerifyController;
use App\Controllers\Pages\Client\DashboardController as ClientDashboardController;
use App\Controllers\Pages\Client\ServiceController;
use App\Controllers\Pages\PublicSite\ExploreServicesController;
use App\Controllers\Pages\PublicSite\SitePagesController;
use App\Core\Router;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\GuestMiddleware;

// Public marketing pages stay DB-free (app: false).
Router::get('/', [SitePagesController::class, 'home'], [], 'home');
Router::get('/about', [SitePagesController::class, 'about'], [], 'about');
Router::get('/services', [SitePagesController::class, 'services'], [], 'services');
Router::get('/team', [SitePagesController::class, 'team'], [], 'team');
Router::get('/contact', [SitePagesController::class, 'contact'], [], 'contact');
Router::get('/explore-services', [ExploreServicesController::class, 'handle'], [], 'explore-services');

// Auth pages: signed-in users are sent to their portal by the group middleware.
Router::group('', [[GuestMiddleware::class, 'redirectIfSignedIn']], static function (): void {
    Router::get('/login', [LoginController::class, 'handle'], [], 'login', true);
    Router::get('/admin-login', [AdminLoginController::class, 'handle'], [], 'admin-login', true);
    Router::get('/register', [RegisterController::class, 'handle'], [], 'register', true);
    Router::get('/register/complete', [RegisterCompleteController::class, 'handle'], [], 'register.complete', true);
    Router::get('/forgot-password', [ForgotPasswordController::class, 'handle'], [], 'forgot-password', true);
    Router::get('/reset-password', [ResetPasswordController::class, 'handle'], [], 'reset-password', true);
    Router::get('/verify', [VerifyController::class, 'handle'], [], 'verify', true);
});

Router::group('/client', [[AuthMiddleware::class, 'requireClient']], static function (): void {
    Router::get('/dashboard', [ClientDashboardController::class, 'handle'], [], 'client.dashboard', true);
    Router::get('/services', [ServiceController::class, 'handle'], [], 'client.services', true);
    Router::get('/services/{slug}', [ServiceController::class, 'handle'], [], 'client.service', true);
});

Router::group('/admin', [[AuthMiddleware::class, 'requireAdmin']], static function (): void {
    Router::get('/dashboard', [AdminDashboardController::class, 'handle'], [], 'admin.dashboard', true);
    Router::get('/services', [ServiceManagementController::class, 'handle'], [], 'admin.services', true);
    Router::post('/services', [ServiceManagementController::class, 'handle'], [[CsrfMiddleware::class, 'require']], '', true);
});

Router::group('/api', [], static function (): void {
    Router::post('/auth/login', [AuthApiController::class, 'login'], [[CsrfMiddleware::class, 'require']], '', true);
    Router::post('/auth/logout', [AuthApiController::class, 'logout'], [[CsrfMiddleware::class, 'require']], '', true);
    Router::post('/auth/register', [AuthApiController::class, 'register'], [[CsrfMiddleware::class, 'require']], '', true);
    Router::post('/auth/forgot-password', [AuthApiController::class, 'forgotPassword'], [[CsrfMiddleware::class, 'require']], '', true);
    Router::post('/auth/reset-password', [AuthApiController::class, 'resetPassword'], [[CsrfMiddleware::class, 'require']], '', true);
    Router::post('/auth/verify', [AuthApiController::class, 'verify'], [[CsrfMiddleware::class, 'require']], '', true);
    Router::post('/auth/resend-verification', [AuthApiController::class, 'resendVerification'], [[CsrfMiddleware::class, 'require']], '', true);
    Router::post('/auth/complete-oauth', [AuthApiController::class, 'completeOAuth'], [[CsrfMiddleware::class, 'require']], '', true);
    Router::post('/services/select', [ServiceSelectionController::class, 'handle'], [[CsrfMiddleware::class, 'require']], '', true);
    Router::post('/account/avatar', [ApiAvatarController::class, 'handle'], [[CsrfMiddleware::class, 'require']], '', true);

    // OAuth is a top-level redirect flow; the provider callbacks keep their
    // registered /api/oauth/{provider}/callback.php path (see Router::dispatch).
    Router::get('/oauth/{provider}/start', [OAuthController::class, 'start'], [], '', true);
    Router::get('/oauth/{provider}/callback', [OAuthController::class, 'callback'], [], '', true);
});
