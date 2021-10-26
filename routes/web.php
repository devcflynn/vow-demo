<?php
use App\Models\User;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CredentialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', function() {
    return view('auth.login');
})->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::get('logout', [AuthController::class, 'logout']);

Route::get('register', function() {
    return view('auth.register');
})->name('register.form');

Route::post('register', [AuthController::class, 'register'])->name('register.submit');

Route::middleware(['auth'])->group(function () {

    Route::get('admin', function() {
        if(auth()->user()->id !== 1) {
            return redirect()->to('credentials')->withErrors('You cannot access that area');
        }
        $users = User::all();
        $users->load('credentials');
        return view('admin')->with(compact('users'));
    })->name('admin');

    Route::get('impersonate/{user_id}', function($user_id) {
        Auth::loginUsingId($user_id);
        return redirect('credentials')->with('success','You are now logged in as user '.$user_id);
    });

    Route::get('users/remove/{user_id}', function($user_id) {
        $user = User::findOrFail($user_id);
        $user->delete(); 
        return response()->json(['success' => 'User Removed!']);
    });

    Route::get('users/list', function() {
        $users = User::all();
        $users->load('credentials');
        return response()->json($users);
    });



    Route::resource('credentials', CredentialController::class);

    /* Files/Media Routes */
        Route::prefix('filepond')->group(function () {
            Route::get('restore', function (Request $request) {
                $file = storage_path(sprintf('app/tmp/filepond/%s', $request->get('restore')));

                return response()->file($file);
            })->name('filepond.restore');

            Route::delete('revert', function (Request $request) {
                app('filesystem')->delete('tmp/filepond/' . $request->getContent());

                return response('', 200)->header('Content-Type', 'text/plain');
            })->name('filepond.revert');

            Route::post('process', function (Request $request) {
                $path = $request->file('filepond')->store('tmp/filepond/' . $request->get('path'));

                return response($request->get('path') . '/' . basename($path), 200)
                    ->header('Content-Type', 'text/plain');
            })->name('filepond.process');

            Route::get('files', function (Request $request) {
                $files = glob(storage_path('app/tmp/filepond/' . $request->get('path') . '/*'));
                if($files) {
                    return response()
                        ->json(array_map(function ($file) use ($request) {
                            return $request->get('path') . '/' . basename($file);
                        }, $files));
                }
                return response()
                        ->json([], 200);
            })->name('filepond.files');
        });

});



