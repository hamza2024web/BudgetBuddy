<?php

use App\Http\Controllers\AnomaliesController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BalanceController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DependenceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RecurringExpenseController;
use App\Http\Controllers\SummaryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/register',[AuthController::class,'register']);

Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tags',TagController::class);
    Route::get('expenses/anomalies',[AnomaliesController::class,'anomalies']);
    Route::apiResource('expenses',DependenceController::class);
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/user',[UserController::class,'show']);
    Route::apiResource('groups',GroupController::class);
});

Route::prefix('groups/{id}/expenses')->group(function () {
    Route::get('/', [ExpenseController::class, 'index']); 
    Route::post('/', [ExpenseController::class, 'store']); 
    Route::delete('{expenseId}', [ExpenseController::class, 'destroy']); 
});

Route::middleware('auth:sanctum')->group(function (){
    Route::post('/budgets',[BudgetController::class,'storeBudget']);
    Route::put('/budgets/{id}',[BudgetController::class,'Expense']);
    Route::delete('/budgets/{id}',[BudgetController::class,'destroy']);
    Route::get('/alert',[BudgetController::class,'alerts']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/recurring-expenses', [RecurringExpenseController::class, 'store']);
    Route::get('/recurring-expenses', [RecurringExpenseController::class, 'index']);
    Route::delete('/recurring-expenses/{id}', [RecurringExpenseController::class, 'destroy']);
});
Route::get('groups/{id}/balances',[BalanceController::class,'balance']);
Route::get('reports/summary',[SummaryController::class,'financialSummary']);
Route::get('reports/custom/start={YYYY-MM-DD}&end={YYYY-MM-DD}',[SummaryController::class,'customFinancial']);
Route::post('/groups/{id}/settle',[GroupController::class,'settle']);
Route::get('/groups/{id}/history',[GroupController::class,'history']);