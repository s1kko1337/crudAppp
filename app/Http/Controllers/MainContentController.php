<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class MainContentController extends Controller
{
    public function showTables(){
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
        return view('tables', compact('tables'));
    }
    public function showHome(){
        return view('home');
    }
}
