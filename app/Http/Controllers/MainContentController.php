<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
    public function showHome(Request $request) {
        $user = Auth::user();
        $roleId = $user->roleId;
        $sellers = DB::table('sellers')->orderBy('id_saler')->get();
        $sellerIds = $request->query('seller_ids', []);
        $salesData = collect();
        $chartLabels = [];
        $chartData = [];
        $pieLabels = [];
        $pieValues = [];
        $lineChartDatasets = [];
        $productColors = [];
    
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
    
        if ($roleId == 2) {
            $sellerRecord = DB::table('sellers_registered')->select('id_saler')->where('id', $user->id)->first();
            $sellerId = $sellerRecord ? $sellerRecord->id_saler : null;
    
            if ($sellerId) {
                $salesData = DB::table('sale_details')
                    ->join('sales', 'sale_details.id_sale', '=', 'sales.id_sale')
                    ->join('product', 'sale_details.id_product', '=', 'product.id_product')
                    ->where('sales.id_saler', $sellerId)
                    ->select('sales.sale_date', 'product.name_product', 'sale_details.quantity', 'sale_details.total_price')
                    ->get();
    
                $chartLabels = $salesData->pluck('sale_date')->unique()->values()->toArray();
                $chartData = $salesData->groupBy('sale_date')->map(function($sales) {
                    return $sales->sum('total_price');
                })->values()->toArray();
    
                $pieLabels = $salesData->pluck('name_product')->unique()->values()->toArray();
                $pieValues = $salesData->groupBy('name_product')->map(function($sales) {
                    return $sales->sum('total_price');
                })->values()->toArray();
    
                foreach ($pieLabels as $index => $product) {
                    $productColors[$product] = $colors[$index % count($colors)];
                }
    
                $lineChartDatasets = [
                    [
                        'label' => $sellers->where('id_saler', $sellerId)->first()->name_saler,
                        'data' => $salesData->groupBy('sale_date')->map(function($sales) {
                            return $sales->sum('total_price');
                        })->values()->toArray(),
                        'fill' => false,
                        'borderColor' => 'rgb(75, 192, 192)',
                        'tension' => 0.4
                    ]
                ];
            }
        }
    
        if (!empty($sellerIds)) {
            $salesData = DB::table('sale_details')
                ->join('sales', 'sale_details.id_sale', '=', 'sales.id_sale')
                ->join('product', 'sale_details.id_product', '=', 'product.id_product')
                ->whereIn('sales.id_saler', $sellerIds)
                ->select('sales.sale_date', 'product.name_product', 'sale_details.quantity', 'sale_details.total_price', 'sales.id_saler')
                ->get();
    
            $chartLabels = $salesData->pluck('sale_date')->unique()->values()->toArray();
            $chartData = $salesData->groupBy('sale_date')->map(function($sales) {
                return $sales->sum('total_price');
            })->values()->toArray();
    
            $pieLabels = $salesData->pluck('name_product')->unique()->values()->toArray();
            $pieValues = $salesData->groupBy('name_product')->map(function($sales) {
                return $sales->sum('total_price');
            })->values()->toArray();
    
            foreach ($pieLabels as $index => $product) {
                $productColors[$product] = $colors[$index % count($colors)];
            }
    
            $lineChartDatasets = $salesData->groupBy('id_saler')->map(function($sales, $sellerId) use ($sellers, $productColors) {
                $seller = $sellers->where('id_saler', $sellerId)->first();
                return [
                    'label' => $seller->name_saler,
                    'data' => $sales->groupBy('sale_date')->mapWithKeys(function($sales, $date) {
                        return [$date => $sales->sum('total_price')];
                    })->toArray(),
                    'fill' => false,
                    'borderColor' => 'rgb(75, 192, 192)',
                    'tension' => 0.4
                ];
            })->values()->toArray();
        }
    
        return view('home', [
            'sellers' => $sellers,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'pieLabels' => $pieLabels,
            'pieValues' => $pieValues,
            'lineChartDatasets' => $lineChartDatasets,
            'productColors' => $productColors,
            'showCharts' => !empty($sellerIds) || $roleId == 2
        ]);
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
                'storage' => ['id_product','quantity_products']
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
            'product' => 'id_product',
            'sales' => 'id_sale',
            'sale_details' => 'id',
            'sellers' => 'id_saler',
            'suppliers' => 'id_supplier',
            'supplies' => 'id_supply',
            'supply_detail' => 'id_supply',
            'sellers_registered' => 'id',
            'storage' => 'id_product'
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
            'storage' => ['id_product','quantity_products']
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
            'product' => ['id_product','name_product','price_product'],
            'sales' => ['id_sale', 'id_saler'],
            'sale_details' => ['id','id_sale', 'id_product', 'quantity','total_price'],
            'sellers' => ['id_saler','name_saler','telephone_saler','total_sells'],
            'suppliers' => ['id_supplier','name_org','name_supplier','email_supplier','telephone_supplier','adress_org'],
            'supplies' => ['id_supply','id_supplier','supply_date','quantity_products','total_price'],
            'supply_detail' => ['id_supply','id_product','quantity'],
            'sellers_registered' => ['id','id_saler'],
            'storage' => ['id_product','quantity_products']
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
        DB::table($tableName)
            ->where($tableId, $id)
            ->update($updateData);
    
        return redirect()->route('user.tables.edit', $tableName)->with('success', 'Данные успешно обновлены');
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
            'storage' => ['id_product','quantity_products']
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
     

    public function showSales()
    {
        if(Auth::user()->roleId == 2) {
            $products = DB::table('storage')
                          ->join('product', 'storage.id_product', '=', 'product.id_product')
                          ->select('product.id_product', 'product.name_product', 'storage.quantity_products')
                             ->get();

            return view('sales', compact('products'));
        }
        return redirect(route('user.home'));
    }

    public function storeSales(Request $request)
{
    $request->validate([
        'products' => 'required|array',
        'products.*.product_id' => 'required|exists:product,id_product',
        'products.*.quantity' => 'required|integer|min:1',
        'sale_date' => 'required|date'
    ]);

    $sellerRecord = DB::table('sellers_registered')->select('id_saler')->where('id', Auth::user()->id)->first();
    $sellerId = $sellerRecord ? $sellerRecord->id_saler : null;

    if (!$sellerId) {
        return back()->withErrors(['seller' => 'Продавец не найден.']);
    }

    $errors = [];
    $totalPrices = [];

    // Создаем уникальный идентификатор продажи
    $saleId = Str::uuid();

    foreach ($request->products as $index => $productRequest) {
        $product = DB::table('storage')
                     ->join('product', 'storage.id_product', '=', 'product.id_product')
                     ->where('storage.id_product', $productRequest['product_id'])
                     ->select('storage.quantity_products', 'product.name_product', 'product.price_product')
                     ->first();

        if ($product->quantity_products < $productRequest['quantity']) {
            $errors["products.$index.quantity"] = 'Недостаточное количество товаров на складе для ' . $product->name_product;
            continue;
        }

        $totalPrice = $product->price_product * $productRequest['quantity'];
        $totalPrices[] = [
            'id_sale' => $saleId,
            'id_product' => $productRequest['product_id'],
            'quantity' => $productRequest['quantity'],
            'total_price' => $totalPrice,
            'created_at' => now(),
            'updated_at' => now()
        ];

        DB::table('storage')->where('id_product', $productRequest['product_id'])->decrement('quantity_products', $productRequest['quantity']);
    }

    if (!empty($errors)) {
        return back()->withErrors($errors)->withInput();
    }

    DB::table('sales')->insert([
        'id_sale' => $saleId,
        'id_saler' => $sellerId,
        'sale_date' => $request->sale_date,
        'created_at' => now(),
        'updated_at' => now()
    ]);

    DB::table('sale_details')->insert($totalPrices);

    DB::table('sellers')->where('id_saler', $sellerId)->increment('total_sells');

    return redirect()->route('user.sales')->with('success', 'Товары успешно проданы');
}

}
