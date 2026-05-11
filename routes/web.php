<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {

        Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
        Route::get('/totalRefresh/{which}', [App\Http\Controllers\HomeController::class, 'totalRefresh'])->name('totalRefresh');
        Route::get('/safeBalanceRefresh', [App\Http\Controllers\HomeController::class, 'safeBalanceRefresh'])->name('safeBalanceRefresh');






        Route::get('/stores', [App\Http\Controllers\StoreController::class, 'index'])->name('stores');
        Route::get('/stores-show/{id}', [App\Http\Controllers\StoreController::class, 'show'])->name('stores-show');
        Route::post('/stores-store', [App\Http\Controllers\StoreController::class, 'store'])->name('stores-store');
        Route::get('/stores-delete/{id}', [App\Http\Controllers\StoreController::class, 'destroy'])->name('stores-delete');
        Route::get('/store_balance/{id}', [App\Http\Controllers\StoreController::class, 'store_balance'])->name('store_balance');




        Route::get('/safes', [App\Http\Controllers\SafeController::class, 'index'])->name('safes');
        Route::get('/safes-show/{id}',  [App\Http\Controllers\SafeController::class, 'show'])->name('safes-show');
        Route::post('/safes-store', [App\Http\Controllers\SafeController::class, 'store'])->name('safes-store');
        Route::get('/safes-delete/{id}', [App\Http\Controllers\SafeController::class, 'destroy'])->name('safes-delete');

        Route::post('/balance-update', [App\Http\Controllers\SafeBalanceController::class, 'store'])->name('balance-update');
        Route::get('/balance-show/{safe_id}', [App\Http\Controllers\SafeBalanceController::class, 'show'])->name('balance-show');



        Route::get('/balance_transactions/{isAll?}',  [App\Http\Controllers\SafeBalanceExchangeController::class, 'index'])->name( 'balance_transactions');
        Route::post('/balance_transactions_store',  [App\Http\Controllers\SafeBalanceExchangeController::class, 'store'])->name( 'balance_transactions_store');
        Route::get('/balance_transactions_show/{id}',  [App\Http\Controllers\SafeBalanceExchangeController::class, 'show'])->name('balance_transactions_show');
        Route::get('/get_transaction_code',  [App\Http\Controllers\SafeBalanceExchangeController::class, 'getcode'])->name('get_transaction_code');




        Route::get('/expenses_types', [App\Http\Controllers\RecipitTypeController::class, 'index'])->name('expenses_types');
        Route::get('/expenses_types-show/{id}', [App\Http\Controllers\RecipitTypeController::class, 'show'])->name('expenses_types-show');
        Route::post('/expenses_types-store', [App\Http\Controllers\RecipitTypeController::class, 'store'])->name('expenses_types-store');
        Route::get('/expenses_types-delete/{id}', [App\Http\Controllers\RecipitTypeController::class, 'destroy'])->name('expenses_types-delete');


        Route::get('/suppliers/{type}', [App\Http\Controllers\ClientController::class, 'index'])->name('suppliers');
        Route::get('/suppliers-show/{id}', [App\Http\Controllers\ClientController::class, 'show'])->name('suppliers-show');
        Route::post('/suppliers-store', [App\Http\Controllers\ClientController::class, 'store'])->name('suppliers-store');
        Route::get('/suppliers-delete/{id}', [App\Http\Controllers\ClientController::class, 'destroy'])->name('suppliers-delete');
        Route::get('/supplier-account-show/{id}', [App\Http\Controllers\ClientController::class, 'showBalance'])->name('supplier-account-show');
        Route::post('/supplier-balance-update', [App\Http\Controllers\ClientController::class, 'updateBalance'])->name('supplier-balance-update');
        Route::get('/suppliers-show/{id}', [App\Http\Controllers\ClientController::class, 'show'])->name('suppliers-show');
        Route::get('/supplier-order', [App\Http\Controllers\ClientController::class, 'getOrder'])->name('supplier-order');


        Route::get('/insuranceBalances', [App\Http\Controllers\SupplierInsuranceBalanceController::class, 'index'])->name('insuranceBalances');
        Route::get('/insuranceBalances-create', [App\Http\Controllers\SupplierInsuranceBalanceController::class, 'create'])->name('insuranceBalances-create');
        Route::get('/insuranceBalances-show/{id}', [App\Http\Controllers\SupplierInsuranceBalanceController::class, 'show'])->name('insuranceBalances-show');
        Route::post('/insuranceBalances-store', [App\Http\Controllers\SupplierInsuranceBalanceController::class, 'store'])->name('insuranceBalances-store');
        Route::get('/insuranceBalances-edit/{id}', [App\Http\Controllers\SupplierInsuranceBalanceController::class, 'edit'])->name('insuranceBalances-edit');


        Route::get('/items', [App\Http\Controllers\ItemsController::class, 'index'])->name('items');
        Route::get('/items-show/{id}', [App\Http\Controllers\ItemsController::class, 'show'])->name('items-show');
        Route::post('/items-store', [App\Http\Controllers\ItemsController::class, 'store'])->name('items-store');
        Route::get('/items-delete/{id}', [App\Http\Controllers\ItemsController::class, 'destroy'])->name('items-delete');
        Route::get('/item-select/{id}/{store}/{meal}', [App\Http\Controllers\ItemsController::class, 'itemSelect'])->name('item-select');
        Route::get('/item-pair-select/{fItem}/{tItem}/{store}', [App\Http\Controllers\ItemsController::class, 'itemPairSelect'])->name('item-select');
                Route::get('/item-select-W-M/{id}/{store}', [App\Http\Controllers\ItemsController::class, 'itemSelectWithoutMeal'])->name('item-select');


        Route::get('/items-quantity', [App\Http\Controllers\StoreQuantityController::class, 'index'])->name('items-quantity');
        Route::get('/items-quantity-show/{id}', [App\Http\Controllers\StoreQuantityController::class, 'show'])->name('items-quantity-show');
        Route::post('/items-quantity-store', [App\Http\Controllers\StoreQuantityController::class, 'store'])->name('items-quantity-store');



        Route::get('/cars', [App\Http\Controllers\CarsController::class, 'index'])->name('cars');
        Route::get('/cars-show/{id}', [App\Http\Controllers\CarsController::class, 'show'])->name('cars-show');
        Route::post('/cars-store', [App\Http\Controllers\CarsController::class, 'store'])->name('cars-store');
        Route::get('/cars-delete/{id}', [App\Http\Controllers\CarsController::class, 'destroy'])->name('cars-delete');

        Route::get('/car_meals/{supplier_id}/{startDate}', [App\Http\Controllers\CarMealController::class, 'index'])->name('car_meals');
        Route::get('/getWeakMealsForCars/{month}/{year}/{day}', [App\Http\Controllers\CarMealController::class, 'getWeakMealsForCars'])->name('getWeakMealsForCars');
        Route::get('/car_meal_create/{wid}', [App\Http\Controllers\CarMealController::class, 'create'])->name('car_meal_create');
        Route::post('/postCarMeal', [App\Http\Controllers\WeaklyMilkMealController::class, 'postCarMeal'])->name('postCarMeal');



        Route::get('/weakly_meals', [App\Http\Controllers\WeaklyMilkMealController::class, 'index'])->name('weakly_meals');
        Route::get('/weakly_meals-show/{id}/{post}', [App\Http\Controllers\WeaklyMilkMealController::class, 'show'])->name('weakly_meals-show');
        Route::post('/weakly_meals-store', [App\Http\Controllers\WeaklyMilkMealController::class, 'store'])->name('weakly_meals-store');
        Route::get('/weakly_meals-delete/{id}', [App\Http\Controllers\WeaklyMilkMealController::class, 'destroy'])->name('weakly_meals-delete');
        Route::get('/weakly_meals-code', [App\Http\Controllers\WeaklyMilkMealController::class, 'getCode'])->name('weakly_meals-code');
        Route::post('/weakly_meals-carryingOver', [App\Http\Controllers\WeaklyMilkMealController::class, 'carryingOver'])->name('weakly_meals-carryingOver');
        Route::get('/view_weakly_meal/{id}', [App\Http\Controllers\WeaklyMilkMealController::class, 'viewDetails'])->name('view_weakly_meal');
        Route::get('/milk_meals', [App\Http\Controllers\WeaklyMilkMealController::class, 'milk_meals'])->name('milk_meals');
        Route::get('/getWeaklyMeals/{month}/{year}/{day}', [App\Http\Controllers\WeaklyMilkMealController::class, 'getWeaklyMeals'])->name('getWeaklyMeals');
        Route::get('/getWeaklyMeal/{id}/{month}/{year}/{day}', [App\Http\Controllers\WeaklyMilkMealController::class, 'getWeaklyMeal'])->name('getWeaklyMeal');
        Route::get('/posted_milk_meals', [App\Http\Controllers\WeaklyMilkMealController::class, 'posted_milk_meals'])->name('posted_milk_meals');
        Route::get('/weakly_meals-init/{start}/{end}', [App\Http\Controllers\WeaklyMilkMealController::class, 'init'])->name('weakly_meals-init');



        Route::post('/postMeal', [App\Http\Controllers\WeaklyMilkMealController::class, 'postMeal'])->name('postMeal');
        Route::get('/weakMeals/{wid}/{supplier_id?}', [App\Http\Controllers\WeaklyMilkMealController::class, 'weakMeals'])->name('weakMeals');
        Route::get('/mealCarryOver/{wid}', [App\Http\Controllers\WeaklyMilkMealController::class, 'carryingOverWeak'])->name('mealCarryOver');
        Route::get('/supplierMealsCarryOver/{wid}/{supplier_id}', [App\Http\Controllers\WeaklyMilkMealController::class, 'supplierMealsCarryOver'])->name('supplierMealsCarryOver');
        Route::get('/weakMealDetails/{id}/{supplier_id}/{start}', [App\Http\Controllers\WeaklyMilkMealController::class, 'weakMealDetails'])->name('weakMealDetails');
        Route::get('/weakMealDetailsPrint/{id}/{supplier_id}/{start}', [App\Http\Controllers\WeaklyMilkMealController::class, 'weakMealDetailsPrint'])->name('weakMealDetailsPrint');
        Route::get('/weakCarMeals/{wid}/{supplier_id?}', [App\Http\Controllers\WeaklyMilkMealController::class, 'weakCarMeals'])->name('weakCarMeals');
        Route::get('/supplierCarMealsCarryOver/{wid}/{supplier_id}', [App\Http\Controllers\WeaklyMilkMealController::class, 'supplierCarMealsCarryOver'])->name('supplierCarMealsCarryOver');
        Route::get('/weakCarMealDetails/{id}/{supplier_id}/{start}', [App\Http\Controllers\WeaklyMilkMealController::class, 'weakCarMealDetails'])->name('weakCarMealDetails');
        Route::get('/weakCarMealsView/{wid}/{member_id?}', [App\Http\Controllers\WeaklyMilkMealController::class, 'weakCarMealsView'])->name('weakCarMealsView');
        Route::get('/weakCarMealDetailsPrint/{id}/{supplier_id}/{start}', [App\Http\Controllers\WeaklyMilkMealController::class, 'weakCarMealDetailsPrint'])->name('weakCarMealDetailsPrint');





        Route::get('/daily_meals', [App\Http\Controllers\DailyMilkMealController::class, 'index'])->name('daily_meals');
        Route::get('/daily_meals-show/{id}', [App\Http\Controllers\DailyMilkMealController::class, 'show'])->name('daily_meals-show');
        Route::post('/daily_meals-store', [App\Http\Controllers\DailyMilkMealController::class, 'store'])->name('daily_meals-store');
        Route::get('/daily_meals-delete/{id}', [App\Http\Controllers\DailyMilkMealController::class, 'destroy'])->name('daily_meals-delete');
        Route::get('/daily_meals-code/{id}', [App\Http\Controllers\DailyMilkMealController::class, 'getCode'])->name('daily_meals-code');
        Route::get('/bouns-check/{type}/{date}', [App\Http\Controllers\DailyMilkMealController::class, 'bounsCheck'])->name('bouns-check');
        Route::get('/get_daily_meal_by_code/{id}', [App\Http\Controllers\DailyMilkMealController::class, 'getMealByCode'])->name('get_daily_meal_by_code');
        Route::get('/daily_meals_total_milk/{id}', [App\Http\Controllers\DailyMilkMealController::class, 'dailyMealTotalMilk'])->name('daily_meals_total_milk');
        Route::get('/get_daily_meal_by_id/{id}', [App\Http\Controllers\DailyMilkMealController::class, 'getMealByID'])->name('get_daily_meal_by_id');





        Route::get('/cheese-meals/{isAll?}', [App\Http\Controllers\CheeseMealController::class, 'index'])->name('cheese-meals');
        Route::get('/cheese-meals-posted', [App\Http\Controllers\CheeseMealController::class, 'index2'])->name('cheese-meals-posted');

        Route::get('/cheese_meal_create', [App\Http\Controllers\CheeseMealController::class, 'create'])->name('cheese_meal_create');
        Route::get('/cheese_meals-delete/{id}', [App\Http\Controllers\CheeseMealController::class, 'destroy'])->name('cheese_meals-delete');
        Route::post('/cheese_meals-store', [App\Http\Controllers\CheeseMealController::class, 'store'])->name('cheese_meals-store');
        Route::get('/cheese_meals-code', [App\Http\Controllers\CheeseMealController::class, 'getCode'])->name('cheese_meals-code');
        Route::get('/cheese_meal_edit/{id}', [App\Http\Controllers\CheeseMealController::class, 'edit'])->name('cheese_meal_edit');
        Route::get('/post_cheese_meal/{id}', [App\Http\Controllers\CheeseMealController::class, 'post'])->name('post_cheese_meal');
        Route::post('/cheese_meals-update', [App\Http\Controllers\CheeseMealController::class, 'update'])->name('cheese_meals-update');
        Route::get('/cheese_meal_view/{id}', [App\Http\Controllers\CheeseMealController::class, 'view'])->name('cheese_meal_view');
        Route::get('/cheese_meal_step1', [App\Http\Controllers\CheeseMealController::class, 'step1'])->name('cheese_meal_step1');
        Route::get('/getWeakMealsToFactory/{month}/{year}/{day}', [App\Http\Controllers\CheeseMealController::class, 'getWeakMealsToFactory'])->name('getWeakMealsToFactory');
        Route::get('/cheese_meal_step2/{id}', [App\Http\Controllers\CheeseMealController::class, 'step2'])->name('cheese_meal_step2');
        Route::post('/cheese_meals_post', [App\Http\Controllers\CheeseMealController::class, 'storeAjax'])->name('cheese_meals_post');
        Route::post('/cheese_meals_prices_update', [App\Http\Controllers\CheeseMealController::class, 'cheese_meals_prices_update'])->name('cheese_meals_prices_update');
        Route::get('/cheese_meal_step2/{id}', [App\Http\Controllers\CheeseMealController::class, 'step2'])->name('cheese_meal_step2');
        Route::get('/cheese_meals_carry_over/{meal_id}', [App\Http\Controllers\CheeseMealController::class, 'cheese_meals_carry_over'])->name('cheese_meals_carry_over');
        Route::get('/cheese_meal_code_check/{meal_id}/{code}', [App\Http\Controllers\CheeseMealController::class, 'cheese_meal_code_check'])->name('cheese_meal_code_check');



        Route::get('/sales/{isAll?}', [App\Http\Controllers\SalesController::class, 'index'])->name('sales');
        Route::get('/sales_create', [App\Http\Controllers\SalesController::class, 'create'])->name('sales_create');
        Route::post('/sales_store', [App\Http\Controllers\SalesController::class, 'store'])->name('sales_store');
        Route::get('/sales_code', [App\Http\Controllers\SalesController::class, 'getCode'])->name('sales_code');
        Route::get('/sales_edit/{id}', [App\Http\Controllers\SalesController::class, 'edit'])->name('sales_edit');
        Route::get('/sales_delete/{id}', [App\Http\Controllers\SalesController::class, 'destroy'])->name('sales_delete');
        Route::post('/sales_update', [App\Http\Controllers\SalesController::class, 'update'])->name('sales_update');
        Route::get('/sales_post/{id}', [App\Http\Controllers\SalesController::class, 'post'])->name('sales_post');
        Route::get('/sales_view/{id}', [App\Http\Controllers\SalesController::class, 'show'])->name('sales_view');
        Route::get('/getStoreMeals/{id}', [App\Http\Controllers\SalesController::class, 'getStoreMeals'])->name('getStoreMeals');
        Route::get('/getMealItems/{id}/{store}', [App\Http\Controllers\SalesController::class, 'getMealItems'])->name('getMealItems');
        Route::get('/get_store_items/{id}', [App\Http\Controllers\SalesController::class, 'get_store_items'])->name('get_store_items');




        Route::get('/stock_exchange/{isAll?}', [App\Http\Controllers\StockTransactionController::class, 'index'])->name('stock_exchange');
        Route::get('/stock_exchange_create', [App\Http\Controllers\StockTransactionController::class, 'create'])->name('stock_exchange_create');
        Route::post('/stock_exchange_store', [App\Http\Controllers\StockTransactionController::class, 'store'])->name('stock_exchange_store');
        Route::get('/stock_exchange_code', [App\Http\Controllers\StockTransactionController::class, 'getCode'])->name('stock_exchange_code');
        Route::get('/stock_exchange_edit/{id}', [App\Http\Controllers\StockTransactionController::class, 'edit'])->name('stock_exchange_edit');
        Route::get('/stock_exchange_delete/{id}', [App\Http\Controllers\StockTransactionController::class, 'destroy'])->name('stock_exchange_delete');
        Route::post('/stock_exchange_update', [App\Http\Controllers\StockTransactionController::class, 'update'])->name('stock_exchange_update');
        Route::get('/stock_exchange_post/{id}', [App\Http\Controllers\StockTransactionController::class, 'post'])->name('stock_exchange_post');
        Route::get('/stock_exchange_view/{id}', [App\Http\Controllers\StockTransactionController::class, 'view'])->name('stock_exchange_view');


        Route::get('/stock_in/{isAll?}', [App\Http\Controllers\StockTransactionInController::class, 'index'])->name('stock_in');
        Route::get('/stock_in_create/{meal_id}', [App\Http\Controllers\StockTransactionInController::class, 'create'])->name('stock_in_create');
        Route::post('/stock_in_store', [App\Http\Controllers\StockTransactionInController::class, 'store'])->name('stock_in_store');
        Route::get('/stock_in_code', [App\Http\Controllers\StockTransactionInController::class, 'getCode'])->name('stock_in_code');
        Route::get('/stock_in_view/{id}', [App\Http\Controllers\StockTransactionInController::class, 'edit'])->name('stock_in_view');

        Route::get('/item_transform_docs/{isAll?}', [App\Http\Controllers\ItemTransformDocController::class, 'index'])->name('item_transform_docs');
        Route::get('/item_transform_docs_create', [App\Http\Controllers\ItemTransformDocController::class, 'create'])->name('item_transform_docs_create');
        Route::post('/item_transform_docs_store', [App\Http\Controllers\ItemTransformDocController::class, 'store'])->name('item_transform_docs_store');
        Route::get('/item_transform_docs_code', [App\Http\Controllers\ItemTransformDocController::class, 'getCode'])->name('item_transform_docs_code');
        Route::get('/item_transform_docs_view/{id}', [App\Http\Controllers\ItemTransformDocController::class, 'show'])->name('item_transform_docs_view');
        Route::get('/item_transform_docs_edit/{id}', [App\Http\Controllers\ItemTransformDocController::class, 'edit'])->name('item_transform_docs_edit');
        Route::post('/item_transform_docs_update', [App\Http\Controllers\ItemTransformDocController::class, 'update'])->name('item_transform_docs_update');
        Route::get('/item_transform_docs_delete/{id}', [App\Http\Controllers\ItemTransformDocController::class, 'destroy'])->name('item_transform_docs_delete');
        Route::get('/item_transform_docs_post/{id}', [App\Http\Controllers\ItemTransformDocController::class, 'postDoc'])->name('item_transform_docs_post');


        Route::get('/recipits/{isAll?}', [App\Http\Controllers\RecipitController::class, 'index'])->name('recipits');
        Route::post('/store-recipits', [App\Http\Controllers\RecipitController::class, 'store'])->name('store-recipits');
        Route::get('/getRecipit/{id}', [App\Http\Controllers\RecipitController::class, 'show'])->name('getRecipit');
        Route::get('/deleteRecipit/{id}', [App\Http\Controllers\RecipitController::class, 'destroy'])->name('deleteRecipit');
        Route::get('/recipits-getCode', [App\Http\Controllers\RecipitController::class, 'getCode'])->name('recipits-getCode');
        Route::get('/post_recipit_doc/{id}', [App\Http\Controllers\RecipitController::class, 'post'])->name('post_recipit_doc');
        Route::get('/printRecipit/{id}', [App\Http\Controllers\RecipitController::class, 'print'])->name('printRecipit');




        Route::post('/makePayment', [App\Http\Controllers\RecipitController::class, 'makePayment'])->name('makePayment');





        Route::get('/catches/{isAll?}', [App\Http\Controllers\CatchRecipitController::class, 'index'])->name('catches');
        Route::post('/store-catches', [App\Http\Controllers\CatchRecipitController::class, 'store'])->name('store-catches');
        Route::get('/getCatch/{id}', [App\Http\Controllers\CatchRecipitController::class, 'show'])->name('getCatch');
        Route::get('/deleteCatch/{id}', [App\Http\Controllers\CatchRecipitController::class, 'destroy'])->name('deleteCatch');
        Route::get('/catches-getCode', [App\Http\Controllers\CatchRecipitController::class, 'getCode'])->name('catches-getCode');
        Route::get('/post_catch_doc/{id}', [App\Http\Controllers\CatchRecipitController::class, 'post'])->name('post_catch_doc');
        Route::get('/printCatch/{id}', [App\Http\Controllers\CatchRecipitController::class, 'print'])->name('printCatch');




        Route::get('/boxRecipits/{isAll?}', [App\Http\Controllers\BoxRecipitController::class, 'index'])->name('boxRecipits');
        Route::post('/store-boxRecipits', [App\Http\Controllers\BoxRecipitController::class, 'store'])->name('store-boxRecipits');
        Route::get('/getBoxRecipit/{id}', [App\Http\Controllers\BoxRecipitController::class, 'show'])->name('getBoxRecipit');
        Route::get('/deleteBoxRecipit/{id}', [App\Http\Controllers\BoxRecipitController::class, 'destroy'])->name('deleteBoxRecipit');
        Route::get('/boxRecipits-getCode', [App\Http\Controllers\BoxRecipitController::class, 'getCode'])->name('boxRecipits-getCode');
        Route::get('/box_recipit_post/{id}', [App\Http\Controllers\BoxRecipitController::class, 'post'])->name('box_recipit_post');




        Route::get('/loans', [App\Http\Controllers\LoanController::class, 'index'])->name('loans');
        Route::post('/store-loans', [App\Http\Controllers\LoanController::class, 'store'])->name('store-loans');
        Route::get('/geLoan/{id}', [App\Http\Controllers\LoanController::class, 'show'])->name('geLoan');
        Route::get('/deleteLoan/{id}', [App\Http\Controllers\LoanController::class, 'destroy'])->name('deleteLoan');
        Route::get('/loan-getCode', [App\Http\Controllers\LoanController::class, 'getCode'])->name('loan-getCode');
        Route::post('/pay-loan', [App\Http\Controllers\LoanController::class, 'pay'])->name('pay-loan');





        Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('settings');
        Route::post('/store-settings', [App\Http\Controllers\SettingsController::class, 'store'])->name('store-settings');
        Route::get('/getSetting/{id}', [App\Http\Controllers\SettingsController::class, 'show'])->name('getSetting');
        Route::get('/deleteSetting/{id}', [App\Http\Controllers\SettingsController::class, 'destroy'])->name('deleteSetting');


        Route::get('/users', [App\Http\Controllers\HomeController::class, 'users'])->name('users');
        Route::post('/store-user', [App\Http\Controllers\HomeController::class, 'storeUser'])->name('store-user');
        Route::get('/getUser/{id}', [App\Http\Controllers\HomeController::class, 'showUser'])->name('getUser');
        Route::get('/deleteUser/{id}', [App\Http\Controllers\HomeController::class, 'destroyUser'])->name('deleteUser');
        Route::get('/getUserProfile/{id}', [App\Http\Controllers\HomeController::class, 'getUserProfile'])->name('getUserProfile');
        Route::post('/reset-password', [App\Http\Controllers\HomeController::class, 'resetPassword'])->name('reset-password');
        Route::post('/update-supplier', [App\Http\Controllers\HomeController::class, 'updateSupplier'])->name('update-supplier');



        Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles');
        Route::post('/store-role', [App\Http\Controllers\RoleController::class, 'store'])->name('store-role');
        Route::get('/getRole/{id}', [App\Http\Controllers\RoleController::class, 'show'])->name('getRole');
        Route::get('/deleteRole/{id}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('deleteRole');


        Route::get('/auth', [App\Http\Controllers\AuthenticationController::class, 'index'])->name('auth');
        Route::post('/store-auth', [App\Http\Controllers\AuthenticationController::class, 'store'])->name('store-auth');
        Route::get('/getAuth/{id}', [App\Http\Controllers\AuthenticationController::class, 'show'])->name('getAuth');
        Route::get('/deleteAuth/{id}', [App\Http\Controllers\AuthenticationController::class, 'destroy'])->name('deleteAuth');
        Route::get('/auth-create', [App\Http\Controllers\AuthenticationController::class, 'create'])->name('authCreate');
        Route::get('/getRoleAuthForms/{id}', [App\Http\Controllers\AuthenticationController::class, 'getRoleAuthForms'])->name('getRoleAuthForms');


        Route::get('/clientAccountSearch', [App\Http\Controllers\ReportController::class, 'clientAccountSearch'])->name('clientAccountSearch');
        Route::post('/clientAccountReport', [App\Http\Controllers\ReportController::class, 'clientAccountReport'])->name('clientAccountReport');
        Route::post('/clientAccountReportPrint', [App\Http\Controllers\ReportController::class, 'clientAccountReportPrint'])->name('clientAccountReportPrint');


        Route::get('/stockMovementSearch', [App\Http\Controllers\ReportController::class, 'stockMovementSearch'])->name('stockMovementSearch');
        Route::post('/stockMovementReport', [App\Http\Controllers\ReportController::class, 'stockMovementReport'])->name('stockMovementReport');
        Route::post('/printStockMovementReport', [App\Http\Controllers\ReportController::class, 'printStockMovementReport'])->name('printStockMovementReport');


        Route::get('/stockMealMovementSearch', [App\Http\Controllers\ReportController::class, 'stockMealMovementSearch'])->name('stockMealMovementSearch');
        Route::post('/stockMealMovementReport', [App\Http\Controllers\ReportController::class, 'stockMealMovementReport'])->name('stockMealMovementReport');
        Route::post('/printStockMealMovementReport', [App\Http\Controllers\ReportController::class, 'printStockMealMovementReport'])->name('printStockMealMovementReport');



        Route::get('/safeMovementSearch', [App\Http\Controllers\ReportController::class, 'safeMovementSearch'])->name('safeMovementSearch');
        Route::post('/safeMovementReport', [App\Http\Controllers\ReportController::class, 'safeMovementReport'])->name('safeMovementReport');
        Route::post('/safeMovementReportPrint', [App\Http\Controllers\ReportController::class, 'safeMovementReportPrint'])->name('safeMovementReportPrint');


        Route::get('/supplierAccountSettlementSearch', [App\Http\Controllers\ReportController::class, 'supplierAccountSettlementSearch'])->name('supplierAccountSettlementSearch');
        Route::post('/supplierAccountSettlementReport', [App\Http\Controllers\ReportController::class, 'supplierAccountSettlementReport'])->name('supplierAccountSettlementReport');


        Route::get('/dailyMealsSearch', [App\Http\Controllers\ReportController::class, 'dailyMealsSearch'])->name('dailyMealsSearch');
        Route::post('/dailyMealsReport', [App\Http\Controllers\ReportController::class, 'dailyMealsReport'])->name('dailyMealsReport');

        Route::get('/weaklyMealsSearch', [App\Http\Controllers\ReportController::class, 'weaklyMealsSearch'])->name('weaklyMealsSearch');
        Route::post('/weaklyMealsReport', [App\Http\Controllers\ReportController::class, 'weaklyMealsReport'])->name('weaklyMealsReport');





        Route::get('/cheeseMealsSearch', [App\Http\Controllers\ReportController::class, 'cheeseMealsSearch'])->name('cheeseMealsSearch');
        Route::post('/cheeseMealsReport', [App\Http\Controllers\ReportController::class, 'cheeseMealsReport'])->name('cheeseMealsReport');

        Route::get('/vehicleMealsSearch', [App\Http\Controllers\ReportController::class, 'vehicleMealsSearch'])->name('vehicleMealsSearch');
        Route::post('/vehicleMealsReport', [App\Http\Controllers\ReportController::class, 'vehicleMealsReport'])->name('vehicleMealsReport');


        Route::get('/chart-data/growth', [\App\Http\Controllers\HomeController::class, 'getGrowthData']);
        Route::get('/chart/revenue-expense', [\App\Http\Controllers\HomeController::class, 'revenueExpenseChartData']);
        Route::get('/chart/income', [\App\Http\Controllers\HomeController::class, 'incomeChartData']);
        Route::get('/chart/weekly-expenses', [\App\Http\Controllers\HomeController::class, 'weeklyExpensesPercentage']);


        Route::get('/carMembers/{supplier_id}', [App\Http\Controllers\CarMemberController::class, 'index'])->name('carMembers');
        Route::get('/carMembers-show/{id}', [App\Http\Controllers\CarMemberController::class, 'show'])->name('carMembers-show');
        Route::get('/carMembers-delete/{id}', [App\Http\Controllers\CarMemberController::class, 'destroy'])->name('carMembers-delete');
        Route::post('/carMember-store', [App\Http\Controllers\CarMemberController::class, 'store'])->name('carMember-store');
        Route::get('/member-order/{supplier_id}', [App\Http\Controllers\CarMemberController::class, 'getOrder'])->name('member-order');



        Route::get('/employees', [App\Http\Controllers\EmployeeController::class, 'index'])->name('employees');
        Route::post('/store-employee', [App\Http\Controllers\EmployeeController::class, 'store'])->name('store-employee');
        Route::get('/getEmployee/{id}', [App\Http\Controllers\EmployeeController::class, 'show'])->name('getEmployee');
        Route::get('/deleteEmployee/{id}', [App\Http\Controllers\EmployeeController::class, 'destroy'])->name('deleteEmployee');

        Route::get('/attendances', [App\Http\Controllers\AttendanceController::class, 'index'])->name('attendances');
        Route::post('/store-attendance', [App\Http\Controllers\AttendanceController::class, 'store'])->name('store-attendance');
        Route::get('/getAttendance/{start_date}/{end_date}', [App\Http\Controllers\AttendanceController::class, 'show'])->name('getAttendance');
        Route::get('/deleteAttendance/{id}', [App\Http\Controllers\AttendanceController::class, 'destroy'])->name('deleteAttendance');
        Route::post('/post-attendance', [App\Http\Controllers\AttendanceController::class, 'postData'])->name('post-attendance');


        Route::get('/salaries', [App\Http\Controllers\SalaryController::class, 'index'])->name('salaries');
        Route::post('/store-salary', [App\Http\Controllers\SalaryController::class, 'store'])->name('store-salary');
        Route::post('/getSalaries', [App\Http\Controllers\SalaryController::class, 'show'])->name('getSalaries');
        Route::get('/showSalaries/{start}/{end}', [App\Http\Controllers\SalaryController::class, 'show2'])->name('showSalaries');

        Route::get('/deleteSalary/{id}', [App\Http\Controllers\SalaryController::class, 'destroy'])->name('deleteSalary');

        Route::get('/advances/{isAll?}', [App\Http\Controllers\AdvanceController::class, 'index'])->name('advances');
        Route::post('/store-advance', [App\Http\Controllers\AdvanceController::class, 'store'])->name('store-advance');
        Route::get('/getAdvance/{id}', [App\Http\Controllers\AdvanceController::class, 'show'])->name('getAdvance');
        Route::get('/deleteAdvance/{id}', [App\Http\Controllers\AdvanceController::class, 'destroy'])->name('deleteAdvance');





       Route::get('/getSuppliersBeforeBalance/{id}/{start}/{end}', [App\Http\Controllers\WeaklyMilkMealController::class, 'getSuppliersBeforeBalance'])->name('getSuppliersBeforeBalance');





        Route::get('/clientsAccountRebase', [App\Http\Controllers\ClientAccountController::class, 'clientsAccountRebase'])->name('clientsAccountRebase');



        Auth::routes();
    }
);



