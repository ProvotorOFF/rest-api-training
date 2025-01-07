<?

use App\Http\Controllers\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'name' => 'v1.'], function() {
    Route::controller(AuthController::class)->prefix('auth')->name('auth.')->group(function() {
        Route::post('login', 'store')->name('login');
    });    
});