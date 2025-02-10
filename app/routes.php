use App\Controllers\AuthController;

// Home route
$router->get('/', [AuthController::class, 'index']);