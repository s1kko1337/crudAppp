    <?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\RegisterController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\MainContentController;
    use App\Http\Controllers\LoginController;
    use Illuminate\Support\Facades\Auth;


    Route::name('user.')->group(function(){
        Route::get('/tables', [ MainContentController::class, 'showTables'])->middleware('auth')->name('tables');
        Route::get('/home', [ MainContentController::class, 'showHome'])->middleware('auth')->name('home');
        Route::get('/profile', [ ProfileController::class, 'show'])->middleware('auth')->name('profile');

        Route::post('/profile/edit-name', [ProfileController::class, 'editName'])->middleware('auth')->name('profile.editName');
        Route::post('/profile/edit-email', [ ProfileController::class, 'editEmail'])->middleware('auth')->name('profile.editEmail');
        //Route::get('/get-updated-users', [ProfileController::class, 'getUpdatedUsers'])->name('admin.get.updated.users');

        Route::get('/user/add', [ProfileController::class, 'add'])->name('add');
        Route::post('/user/add', [ ProfileController::class, 'saveUser'])->name('saveUser');
        
        Route::middleware('auth')->group(function () {
            Route::get('/user/edit/{id}', [ProfileController::class, 'edit'])->name('edit');
            Route::put('/user/update/{id}', [ProfileController::class, 'update'])->name('update');
            Route::delete('/user/delete/{id}', [ProfileController::class, 'destroy'])->name('delete');
        });
        


        Route::get('/', function(){
            if(Auth::check()){
                return redirect(route('user.home'));
            }
            return view('login');
        })->name(name:'login');

        Route::post('/', [LoginController::class, 'login']);
        
        Route::get('/logout',function(){
            Auth::logout();
            return redirect(route('user.login'));
        })->name('logout');

        Route::get('/registration', function(){
            if(Auth::check()){
                return redirect(route('user.home'));
            }
            return view('register');
        })->name('register');

    Route::post('/registration', [ RegisterController::class, 'save']);
    });
