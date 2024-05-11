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
            $editableColumns = [
                'users' => ['username', 'email', 'roleId'],
                'roles' => ['id','name'],
                'premises' => ['id_room','level_room','name_room','adress_room','capacity_room'],
                'product' => ['id_product','name_product','price_product'],
                'sales' => ['id_sale', 'id_saler','id_product','sale_date','quantity','total_price'],
                'sellers' => ['id_saler','name_seller','telephone_saler','total_sells'],
                'suppliers' => ['id_supplier','name_org','name_supplier','email_supplier','telephone_supplier','adress_org'],
                'supplies' => ['id_supply','id_supplier','supply_date','quantity_products','total_price'],
                'supply_detail' => ['id_supply','id_product','quantity']
            ];
            //if($tableName=='users'){
            //$editableColumns = ['username', 'email', 'roleId'];
            //}
            $tableData = DB::table($tableName)->get(); 
            //$viewName = 'edit' . ucfirst($tableName);
            return view('editTable'/*$viewName*/, ['tableName' => $tableName, 'columns' => $columns, 'tableData' => $tableData, 'editableColumns' => $editableColumns[$tableName]]);
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
        $editableColumns = [
            'users' => ['email', 'username', 'roleId'],
            'roles' => ['name'],
            'premises' => ['id_room', 'level_room', 'name_room', 'adress_room', 'capacity_room'],
            'product' => ['name_product', 'price_product'],
            'sales' => ['id_saler', 'id_product', 'sale_date', 'quantity', 'total_price'],
            'sellers' => ['name_seller', 'telephone_saler', 'total_sells'],
            'suppliers' => ['name_org', 'name_supplier', 'email_supplier', 'telephone_supplier', 'adress_org'],
            'supplies' => ['id_supplier', 'supply_date', 'quantity_products', 'total_price'],
            'supply_detail' => ['id_product', 'quantity']
        ];

        if (!array_key_exists($tableName, $editableColumns)) {
            abort(404);
        }
    
        $updateData = [];
        foreach ($editableColumns[$tableName] as $column) {
            $updateData[$column] = $request->input($column);
        }
    
        // Обновляем запись в базе данных
        DB::table($tableName)
            ->where('id', $id)
            ->update($updateData);
    
        return redirect()->route('user.tables.edit', $tableName)->with('success', 'Данные успешно обновлены');
    }
    
    
    
    
}
