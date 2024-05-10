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
    

    public function destroy($id,$tableName)
    {
    
        DB::table($tableName)->where('id', $id)->delete();
    
        return redirect()->route('user.tables.edit', $tableName)->with('success', 'Строка успешно удалена');
    }
    

   // MainContentController.php

public function updateTable(Request $request, $tableName, $id) {
    $data = $request->only(['column1', 'column2',]);

    // Обновляем данные в базе данных
    DB::table($tableName)->where('id', $id)->update($data);

    // Перенаправляем пользователя обратно на страницу редактирования таблицы
    return redirect()->route('user.tables.edit', $tableName)->with('success', 'Данные успешно обновлены');
}

    
}
