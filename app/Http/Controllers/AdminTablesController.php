<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AdminTablesController extends Controller
{
   public function showTables(){
    $user = Auth::user();
    $roleId = $user->roleId;
    $tableNames = [
        'sellers' => 'Продавцы',
        'product' => 'Товары',
        'users' => 'Пользователи',
        'suppliers' => 'Поставщики',
        'supplies' => 'Поставки',
        'storage' => 'Склад',
        'sales' => 'Продажи'
    ];
    if($roleId == 0 || $roleId == 1){
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
        return view('adminTables', ['tables' => $tables, 'tableNames' => $tableNames]);
    }
        return redirect(route('user.home'));
    }
       
    public function editTable($tableName) {
        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
            $editableColumns = [
                'users' => ['id','username','email', 'roleId'],
                'roles' => ['id','name'],
                'product' => ['id_product','name_product','price_product'],
                'sales' => ['id_sale', 'id_saler'],
                'sale_details' => ['id','id_sale', 'id_product', 'quantity','total_price'],
                'sellers' => ['id_saler','name_saler','telephone_saler','total_sells'],
                'suppliers' => ['id_supplier','name_org','name_supplier','email_supplier','telephone_supplier','adress_org'],
                'supplies' => ['id_supply','id_supplier','supply_date','quantity_products','total_price'],
                'supply_detail' => ['id_supply','id_product','quantity'],
                'sellers_registered' => ['id','id_saler'],
                'storage' => ['id_product','quantity_products'],
                'supplies_status' =>['id_supply','is_added']
            ];
    
            $tableId = $this->getTableIdColumn($tableName);
            $tableData = DB::table($tableName)->orderBy($tableId)->get();
    
            return view('adminEditTable', [
                'tableName' => $tableName,
                'columns' => $columns,
                'tableData' => $tableData,
                'tableId' => $tableId,
                'editableColumns' => $editableColumns[$tableName]
            ]);
        } else {
            abort(404);
        }
    }
    
    private function getTableIdColumn($tableName) {
        $editableColumns = [
            'users' => 'id',
            'roles' => 'id',
            'product' => 'id_product',
            'sales' => 'id_sale',
            'sale_details' => 'id',
            'sellers' => 'id_saler',
            'suppliers' => 'id_supplier',
            'supplies' => 'id_supply',
            'supply_detail' => 'id_supply',
            'sellers_registered' => 'id',
            'storage' => 'id_product',
            'supplies_status' =>'id_supply'
        ];
    
        return $editableColumns[$tableName];
    }
    
    
    public function destroy($tableName, $id)
    {
        $editableColumns = [
            'users' => ['id','username','email', 'roleId'],
            'roles' => ['id','name'],
            'product' => ['id_product','name_product','price_product'],
            'sales' => ['id_sale', 'id_saler'],
            'sale_details' => ['id','id_sale', 'id_product', 'quantity','total_price'],
            'sellers' => ['id_saler','name_saler','telephone_saler','total_sells'],
            'suppliers' => ['id_supplier','name_org','name_supplier','email_supplier','telephone_supplier','adress_org'],
            'supplies' => ['id_supply','id_supplier','supply_date','quantity_products','total_price'],
            'supply_detail' => ['id_supply','id_product','quantity'],
            'sellers_registered' => ['id','id_saler'],
            'storage' => ['id_product','quantity_products'],
            'supplies_status' =>['id_supply','is_added']
        ];
        $tableId = $editableColumns[$tableName][0];
        DB::table($tableName)->where($tableId, $id)->delete();
        return redirect()->route('user.admintables.edit', $tableName)->with('success', 'Строка успешно удалена');
    }
    
    public function updateTable(Request $request, $tableName, $id) {
        $editableColumns = [
            'users' => ['id','username','email', 'roleId'],
            'roles' => ['id','name'],
            'product' => ['id_product','name_product','price_product'],
            'sales' => ['id_sale', 'id_saler'],
            'sale_details' => ['id','id_sale', 'id_product', 'quantity','total_price'],
            'sellers' => ['id_saler','name_saler','telephone_saler','total_sells'],
            'suppliers' => ['id_supplier','name_org','name_supplier','email_supplier','telephone_supplier','adress_org'],
            'supplies' => ['id_supply','id_supplier','supply_date','quantity_products','total_price'],
            'supply_detail' => ['id_supply','id_product','quantity'],
            'sellers_registered' => ['id','id_saler'],
            'storage' => ['id_product','quantity_products'],
            'supplies_status' =>['id_supply','is_added']
        ];

        if (!array_key_exists($tableName, $editableColumns)) {
            abort(404);
        }
    
        $updateData = [];
        foreach ($editableColumns[$tableName] as $column) {
            $updateData[$column] = $request->input($column);
        }
        if($tableName != 'roles')
        {
            $updateData['updated_at'] = now();
        }
        $tableId = $editableColumns[$tableName][0];
        DB::table($tableName)
            ->where($tableId, $id)
            ->update($updateData);
    
        return redirect()->route('user.admintables.edit', $tableName)->with('success', 'Данные успешно обновлены');
    }
    
    public function addTable(Request $request, $tableName) {
        $editableColumns = [
            'users' => ['email','username','password', 'roleId'],
            'roles' => ['id','name'],   
            'product' => ['id_product','name_product', 'price_product'],
            'sales' => [],
            'sale_details' => [],
            'sellers' => ['id_saler','name_saler', 'telephone_saler', 'total_sells'],
            'suppliers' => ['name_org', 'name_supplier', 'email_supplier', 'telephone_supplier', 'adress_org'],
            'supplies' => ['id_supplier', 'supply_date', 'quantity_products', 'total_price'],
            'supply_detail' => ['id_supply','id_product','quantity'],
            'sellers_registered' => ['id','id_saler'],
            'storage' => ['id_product','quantity_products'],
            'supplies_status' =>[]
        ];
    
        if (!array_key_exists($tableName, $editableColumns)) {
            abort(404);
        }
    
        $newData = [];
        foreach ($editableColumns[$tableName] as $column) {
            $newData[$column] = $request->input($column);
        }
        if ($tableName != 'roles'){
        $newData['created_at'] = now();
        $newData['updated_at'] = now();
    }
        DB::table($tableName)->insert($newData);
        return redirect()->route('user.admintables.edit', $tableName)->with('success', 'Запись успешно добавлена');
    }
}