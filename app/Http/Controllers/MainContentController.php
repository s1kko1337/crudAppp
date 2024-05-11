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
        return view('tables', ['tables' => $tables]);
    }
    
    public function showHome(){
        return view('home');
    }

    public function editTable($tableName) {
        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
            if($tableName=='users'){
            $editableColumns = ['username', 'email', 'roleId'];
            }
            $tableData = DB::table($tableName)->get(); 
            $viewName = 'edit' . ucfirst($tableName);
            return view($viewName, ['tableName' => $tableName, 'columns' => $columns, 'tableData' => $tableData, 'editableColumns' => $editableColumns]);
        } else {
            abort(404);
        }
    }
    

    public function destroy($tableName, $id)
    {
        DB::table($tableName)->where('id', $id)->delete();
        return redirect()->route('user.tables.edit', $tableName)->with('success', 'Строка успешно удалена');
    }
    
    //TODO
    public function updateTable(Request $request, $tableName, $id) {
        if($tableName == 'users') {
        
        $validateFields = $request->validate([
                'username' => 'required|min:8',
                'email' => 'required|email',
                'roleId' => 'required'
            ]);     
        DB::table('users')
            ->where('id', $id)
            ->update([
            'email' => $validateFields['email'],
            'username' => $validateFields['username'],
            'roleId' => $validateFields['roleId']
        ]); 
            return redirect()->route('user.tables.edit', $tableName)->with('success', 'Пользователь успешно обновлен');
        }
        if($tableName == 'premises')
        {
            
        } 
        if($tableName == 'sellers')
        {
            
        } 
        if($tableName == 'sales')
        {
            
        } 
        if($tableName == 'product')
        {
            
        } 
        if($tableName == 'suppliers')
        {
            
        } 
        if($tableName == 'supplies')
        {
            
        } 
        if($tableName == 'roles')
        {
            
        } 
        if($tableName == 'supply_detail')
        {
            
        } 

    }
    
    
    
}
