<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Exists;

class MainContentController extends Controller
{
   public function showTables(){
    $user = Auth::user();
    $roleId = $user->roleId;
    if($roleId == 0 || $roleId == 1){
        $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
        return view('tables', ['tables' => $tables]);
    }
        return redirect(route('user.home'));
    }
    public function showHome(Request $request){
        $sellers = DB::table('sellers')->orderBy('id_saler')->get();
        $sellerIds = $request->query('seller_ids', []);
        $salesData = [];
        $chartLabels = [];
        $chartData = []; 
        $pieLabels = [];
        $pieValues = [];
        $lineChartDatasets = [];
        $productColors = []; // Инициализируем массив цветов для товаров
    
        if (!empty($sellerIds)) {
            $salesData = DB::table('sales')
                ->whereIn('id_saler', $sellerIds)
                ->join('product', 'sales.id_product', '=', 'product.id_product')
                ->select('sales.*', 'product.name_product')
                ->get();
            
            $chartLabels = $salesData->pluck('sale_date')->unique()->toArray();
    
            // Суммарная цена продаж по дням для lineChart1
            $chartData = $salesData->groupBy('sale_date')->map(function($sales) {
                return $sales->sum('total_price');
            })->values()->toArray();
    
            if (count($sellerIds) == 1) {
                // Если выбран один продавец, показываем его данные для pieChart
                $sellerId = $sellerIds[0];
    
                $pieLabels = $salesData->pluck('name_product')->unique()->toArray();
                $pieValues = $salesData->where('id_saler', $sellerId)->groupBy('name_product')->map(function($sales) {
                    return $sales->sum('total_price');
                })->values()->toArray();
    
                $lineChartDatasets = [
                    [
                        'label' => $sellers->where('id_saler', $sellerId)->first()->name_saler,
                        'data' => $salesData->where('id_saler', $sellerId)->groupBy('sale_date')->map(function($sales) {
                            return $sales->sum('total_price');
                        })->values()->toArray(),
                        'fill' => false,
                        'borderColor' => 'rgb(75, 192, 192)',
                        'tension' => 0.1
                    ]
                ];
            } else {
                // Если выбрано несколько продавцов, показываем сводные данные для pieChart и lineChart2
                $pieLabels = $salesData->pluck('name_product')->unique()->toArray();
                $pieValues = $salesData->groupBy('name_product')->map(function($sales) {
                    return $sales->sum('total_price');
                })->values()->toArray();
    
                // Определим массив цветов для продуктов
                $colors = [
                    'rgb(255, 99, 132)',
                    'rgb(54, 162, 235)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)',
                    'rgb(199, 199, 199)',
                    'rgb(83, 102, 255)'
                ];
    
                // Генерируем массив цветов для продуктов
                foreach ($pieLabels as $index => $product) {
                    $productColors[$product] = $colors[$index % count($colors)];
                }
    
                $lineChartDatasets = $salesData->groupBy('id_saler')->map(function($sales, $sellerId) use ($sellers, $productColors) {
                    $seller = $sellers->where('id_saler', $sellerId)->first();
                    return [
                        'label' => $seller->name_saler,
                        'data' => $sales->groupBy('sale_date')->map(function($sales) {
                            return $sales->sum('total_price');
                        })->values()->toArray(),
                        'fill' => false,
                        'borderColor' => $productColors[$sales->first()->name_product] ?? 'rgb(0, 0, 0)',
                        'tension' => 0.1
                    ];
                })->values()->toArray();
            }
        }
    
        return view('home', [
            'sellers' => $sellers,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'pieLabels' => $pieLabels,
            'pieValues' => $pieValues,
            'lineChartDatasets' => $lineChartDatasets,
            'productColors' => $productColors // Передаем массив цветов в представление
        ]);
    }
        
    public function editTable($tableName) {
        if (Schema::hasTable($tableName)) {
            $columns = Schema::getColumnListing($tableName);
            $editableColumns = [
                'users' => ['id','username','email', 'roleId'],
                'roles' => ['id','name'],
                'premises' => ['id_room','level_room','name_room','adress_room','capacity_room'],
                'product' => ['id_product','name_product','price_product'],
                'sales' => ['id_sale', 'id_saler','id_product','sale_date','quantity','total_price'],
                'sellers' => ['id_saler','name_saler','telephone_saler','total_sells'],
                'suppliers' => ['id_supplier','name_org','name_supplier','email_supplier','telephone_supplier','adress_org'],
                'supplies' => ['id_supply','id_supplier','supply_date','quantity_products','total_price'],
                'supply_detail' => ['id_supply','id_product','quantity']
            ];
    
            $tableId = $this->getTableIdColumn($tableName);
            $tableData = DB::table($tableName)->orderBy($tableId)->get();
    
            return view('editTable', [
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
            'premises' => 'id_room',
            'product' => 'id_product',
            'sales' => 'id_sale',
            'sellers' => 'id_saler',
            'suppliers' => 'id_supplier',
            'supplies' => 'id_supply',
            'supply_detail' => 'id_supply'
        ];
    
        return $editableColumns[$tableName];
    }
    
    
    public function destroy($tableName, $id)
    {
        $editableColumns = [
            'users' => ['id','username','email', 'roleId'],
            'roles' => ['id','name'],
            'premises' => ['id_room','level_room','name_room','adress_room','capacity_room'],
            'product' => ['id_product','name_product','price_product'],
            'sales' => ['id_sale', 'id_saler','id_product','sale_date','quantity','total_price'],
            'sellers' => ['id_saler','name_saler','telephone_saler','total_sells'],
            'suppliers' => ['id_supplier','name_org','name_supplier','email_supplier','telephone_supplier','adress_org'],
            'supplies' => ['id_supply','id_supplier','supply_date','quantity_products','total_price'],
            'supply_detail' => ['id_supply','id_product','quantity']
        ];
        $tableId = $editableColumns[$tableName][0];
        DB::table($tableName)->where($tableId, $id)->delete();
        return redirect()->route('user.tables.edit', $tableName)->with('success', 'Строка успешно удалена');
    }
    
    //TODO
    public function updateTable(Request $request, $tableName, $id) {
        $editableColumns = [
            'users' => ['id','username','email', 'roleId'],
            'roles' => ['id','name'],
            'premises' => ['id_room','level_room','name_room','adress_room','capacity_room'],
            'product' => ['id_product','name_product','price_product'],
            'sales' => ['id_sale', 'id_saler','id_product','sale_date','quantity','total_price'],
            'sellers' => ['id_saler','name_saler','telephone_saler','total_sells'],
            'suppliers' => ['id_supplier','name_org','name_supplier','email_supplier','telephone_supplier','adress_org'],
            'supplies' => ['id_supply','id_supplier','supply_date','quantity_products','total_price'],
            'supply_detail' => ['id_supply','id_product','quantity']
        ];

        if (!array_key_exists($tableName, $editableColumns)) {
            abort(404);
        }
    
        $updateData = [];
        foreach ($editableColumns[$tableName] as $column) {
            $updateData[$column] = $request->input($column);
        }
        $updateData['updated_at'] = now();
        $tableId = $editableColumns[$tableName][0];
        // Обновляем запись в базе данных
        DB::table($tableName)
            ->where($tableId, $id)
            ->update($updateData);
    
        return redirect()->route('user.tables.edit', $tableName)->with('success', 'Данные успешно обновлены');
    }
    
    public function addTable(Request $request, $tableName) {
        $editableColumns = [
            'users' => ['email','username','password', 'roleId'],
            'roles' => ['id','name'],   
            'premises' => ['id_room', 'level_room', 'name_room', 'adress_room', 'capacity_room'],
            'product' => ['id_product','name_product', 'price_product'],
            'sales' => ['id_saler', 'id_product', 'sale_date', 'quantity', 'total_price'],
            'sellers' => ['id_saler','name_saler', 'telephone_saler', 'total_sells'],
            'suppliers' => ['name_org', 'name_supplier', 'email_supplier', 'telephone_supplier', 'adress_org'],
            'supplies' => ['id_supplier', 'supply_date', 'quantity_products', 'total_price'],
            'supply_detail' => ['id_supply','id_product','quantity']
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
        return redirect()->route('user.tables.edit', $tableName)->with('success', 'Запись успешно добавлена');
    }
     

    public function showSales(){
        if(Auth::user()->roleId == 2){
            $editableColumns = [
                'premises' => ['id_room','level_room','name_room','adress_room','capacity_room'],
                'product' => ['id_product','name_product','price_product'],
                'sales' => ['id_sale', 'id_saler','id_product','sale_date','quantity','total_price'],
                'sellers' => ['id_saler','name_saler','telephone_saler','total_sells'],
                'suppliers' => ['id_supplier','name_org','name_supplier','email_supplier','telephone_supplier','adress_org'],
                'supplies' => ['id_supply','id_supplier','supply_date','quantity_products','total_price'],
                'supply_detail' => ['id_supply','id_product','quantity']
            ];
            $tables = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
            return view('sales');
        }
            return redirect(route('user.home'));
    }
}
