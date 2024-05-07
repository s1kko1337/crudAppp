<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function home()
    {
        return view('home');
    }
    
    public function login()
    {
        return view('login');
    }

    public function check(Request $request)
    {
        $data = DB::table('testtable')->get();
        if (count($data) == 0) {
            return back()->withErrors($data)->withInput();
        }
            
        //dd($data);
        $valid = $request->validate([
            'email' => 'required|min:4|max:40',
            'password' => 'required|min:6|max:24'
        ]);

        if ($valid) {
            return redirect('/home');
        } else {
            return back()->withErrors($valid)->withInput();
        }
    }

    public function tables()
    {
        return view('tables');
    }
}
