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
            $tableData = DB::table($tableName)->get(); 
            return view('editTable', ['tableName' => $tableName, 'columns' => $columns, 'tableData' => $tableData]);
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
        // Получаем данные текущей строки по id
        $currentRow = DB::table($tableName)->where('id', $id)->first();
    
        // Перебираем данные из запроса и обновляем только отличающиеся поля
        $data = $request->except('_token', '_method');
        $updatedData = array_diff_assoc($data, (array)$currentRow);
        return redirect()->back()->with('error', $updatedData);
    
        if (!empty($updatedData)) {
            // Обновляем только отличающиеся поля
            DB::table($tableName)->where('id', $id)->update($updatedData);
            return redirect()->back()->with('success', 'Данные успешно обновлены');
        } else {
            return redirect()->back()->with('info', 'Данные не изменены');
        }
    }
    
    
    
}
