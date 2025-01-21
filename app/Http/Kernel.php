use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'guest' => RedirectIfAuthenticated::class,
    ];
}
