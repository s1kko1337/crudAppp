<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show(){
        return view('profile');
    }

    public function getRole(){
        if(Auth::check()){
            $user = Auth::user();
            $roleId = $user->roleId;
            
            $role = DB::table('roles')
                ->select('name')
                ->where('id', $roleId)
                ->first();
        }
        else {
            return redirect(route('user.login'));
        }
        return $role->name; 
    }

    public function editName(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
        ]);

        $userId = Auth::id();

        DB::table('users')
            ->where('id', $userId)
            ->update(['username' => $request->username]);

        return redirect()->back()->with('success', 'Имя пользователя успешно обновлено.');
    }

    public function editEmail(Request $request)
    {
    $request->validate([
        'email' => 'required|email',
    ]);

    $userId = Auth::id(); 

    DB::table('users')
        ->where('id', $userId)
        ->update(['email' => $request->email]);

        return redirect()->back()->with('success', 'Электронная почта пользователя успешно обновлена.');
    }

}
