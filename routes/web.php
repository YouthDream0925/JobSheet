<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JobCard\JobCardController;
use App\Http\Controllers\JobCard\TaskController;
use App\Http\Controllers\JobCard\SubTaskController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\ExportController;
use Plank\Mediable\Mediable;
use Spatie\Analytics\Period;

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

Auth::routes(['verify' => true]);
// Auth::routes();

Route::middleware(['auth', 'verified'])->group(function() {
// Route::middleware(['auth'])->group(function() {
    Route::get('/', function () {
        return redirect('home');
    });

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::patch('/fcm-token', [HomeController::class, 'updateToken'])->name('fcmToken');
    Route::post('/send-notification',[HomeController::class,'notification'])->name('notification');

    Route::resource('profile', ProfileController::class);

    Route::group(['middleware' => ['role:SuperAdmin']], function () {
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::get('users-excel', [UserController::class, 'excel'])->name('users.excel');
        Route::get('users-export', [UserController::class, 'export'])->name('users.export');
        Route::post('users-import', [UserController::class, 'import'])->name('users.import');
    });

    Route::resource('jobcards', JobCardController::class);
    Route::post('jobcards/save_comments', [JobCardController::class, 'SaveComments']);
    Route::post('jobcards/delete_comments', [JobCardController::class, 'DeleteComments']);
    Route::post('jobcards/save_comments_header/{id}', [JobCardController::class, 'SaveHeader'])->name('jobcards.comments.header');
    Route::post('jobcards/save_descriptions', [JobCardController::class, 'SaveDescriptions']);
    Route::post('jobcards/delete_descriptions', [JobCardController::class, 'DeleteDescriptions']);
    Route::post('jobcards/save_questions', [JobCardController::class, 'SaveQuestions']);
    Route::post('jobcards/save_answers', [JobCardController::class, 'SaveAnswers']);
    Route::post('jobcards/delete_questions', [JobCardController::class, 'DeleteQuestions']);
    //Export and Import Excel with JobCard
    Route::get('jobcards-excel', [JobCardController::class, 'excel'])->name('jobcards.excel');
    Route::get('jobcards-export', [JobCardController::class, 'export'])->name('jobcards.export');
    Route::post('jobcards-import', [JobCardController::class, 'import'])->name('jobcards.import');
    //Duplicate JobCard
    Route::get('jobcards/duplicate/{id}', [JobCardController::class, 'duplicate'])->name('jobcards.duplicate');

    Route::get('tasks/create/{job_id}', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('tasks/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('tasks/edit/{task_id}', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::post('tasks/update/{task_id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::get('tasks/destroy/{task_id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('tasks/show/{task_id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::post('tasks/save_comments', [TaskController::class, 'SaveComments']);
    Route::post('tasks/save_comments_header/{id}', [TaskController::class, 'SaveHeader'])->name('tasks.comments.header');
    //Export and Import Excel with Task
    Route::get('tasks-excel/{job_id}', [TaskController::class, 'excel'])->name('tasks.excel');
    Route::get('tasks-export/{job_id}', [TaskController::class, 'export'])->name('tasks.export');
    Route::post('tasks-import/{job_id}', [TaskController::class, 'import'])->name('tasks.import');
    //Duplicate Task
    Route::get('tasks/duplicate/{id}', [TaskController::class, 'duplicate'])->name('tasks.duplicate');

    Route::get('subtasks/create/{task_id}', [SubTaskController::class, 'create'])->name('subtasks.create');
    Route::post('subtasks/store', [SubTaskController::class, 'store'])->name('subtasks.store');
    Route::get('subtasks/edit/{task_id}', [SubTaskController::class, 'edit'])->name('subtasks.edit');
    Route::post('subtasks/update/{task_id}', [SubTaskController::class, 'update'])->name('subtasks.update');
    Route::get('subtasks/destroy/{task_id}', [SubTaskController::class, 'destroy'])->name('subtasks.destroy');
    Route::get('subtasks/show/{task_id}', [SubTaskController::class, 'show'])->name('subtasks.show');
    Route::post('subtasks/save_comments', [SubTaskController::class, 'SaveComments']);
    Route::post('subtasks/save_comments_header/{id}', [SubTaskController::class, 'SaveHeader'])->name('subtasks.comments.header');
    //Export and Import Excel with SubTask
    Route::get('subtasks-excel/{task_id}', [SubTaskController::class, 'excel'])->name('subtasks.excel');
    Route::get('subtasks-export/{task_id}', [SubTaskController::class, 'export'])->name('subtasks.export');
    Route::post('subtasks-import/{task_id}', [SubTaskController::class, 'import'])->name('subtasks.import');
    //Duplicate SubTask
    Route::get('subtasks/duplicate/{id}', [SubTaskController::class, 'duplicate'])->name('subtasks.duplicate');

    Route::get('contact', [ContactController::class, 'index'])->name('contact');
    Route::post('contact/send', [ContactController::class, 'send'])->name('contact.send');

    Route::get('messages/{id?}', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/delete/{id}', [MessageController::class, 'delete'])->name('messages.delete');
    Route::post('messages/send', [MessageController::class, 'send'])->name('messages.send');

    Route::get('create-pdf-file/{id}', [PDFController::class, 'job'])->name('exportPDF');
    Route::get('create-pdf-file/task/{id}', [PDFController::class, 'task'])->name('exportPDF.task');
    Route::get('create-pdf-file/subTask/{id}', [PDFController::class, 'subTask'])->name('exportPDF.subTask');

    Route::get('create-excel-file/{id}', [ExcelController::class, 'job'])->name('exportExcel');
    Route::get('create-excel-file/task/{id}', [ExcelController::class, 'task'])->name('exportExcel.task');
    Route::get('create-excel-file/subTask/{id}', [ExcelController::class, 'subTask'])->name('exportExcel.subTask');

    Route::post('notification', [SettingController::class, 'index']);

    Route::get('export/wps', [ExportController::class, 'wps'])->name('export.wps');
    Route::post('export/wps/excute', [ExportController::class, 'wps_excute'])->name('export.wps.excute');
    Route::post('export/wps/user', [ExportController::class, 'wps_excute_by_user'])->name('export.wps.user');
    Route::get('export/tasks', [ExportController::class, 'tasks'])->name('export.tasks');
    Route::post('export/tasks/excute', [ExportController::class, 'tasks_excute'])->name('export.tasks.excute');
    Route::post('export/tasks/user', [ExportController::class, 'tasks_excute_by_user'])->name('export.tasks.user');
    Route::get('export/subtasks', [ExportController::class, 'subTasks'])->name('export.subtasks');
    Route::post('export/subtasks/excute', [ExportController::class, 'subtasks_excute'])->name('export.subtasks.excute');
    Route::post('export/subtasks/user', [ExportController::class, 'subtasks_excute_by_user'])->name('export.subtasks.user');
});

Route::get('storage/avatar/{filename}', function ($filename)
{
    $path = storage_path('app/avatar/' . $filename);

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('storage/sample', function ()
{
    $path = storage_path('app/public/' . 'male.png');

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::get('storage/error', function ()
{
    $path = storage_path('app/public/' . 'error.png');

    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});


Route::get('/greeting/{locale}', function ($locale) {
    if (! in_array($locale, ['en', 'it'])) {
        abort(400);
    }
 
    App::setLocale($locale);
    session()->put('locale', $locale);
 
    return redirect()->back();
    //
});

Route::get('/analytics', function () {

    $analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));

    echo json_encode($analyticsData);
    die();
    
    return view('analytics.index', ['analyticsData' => $analyticsData]);
});

// Route::group(['middleware' => ['auth']], function() {
//     Route::resource('roles', RoleController::class);
//     Route::resource('users', UserController::class);
//     Route::resource('products', ProductController::class);
// });