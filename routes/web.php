<?php

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

use App\Http\Controllers\CustomFieldController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FileTypeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PrestationController;
use App\Http\Controllers\ImportController;





use App\Http\Controllers\QualityTypeController;
use App\Http\Controllers\QualityController;
use App\Http\Controllers\QualityLogController;


Route::get('/', [HomeController::class,'welcome'])->name('home');

Route::get('/check-limits', function() {
    return [
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'memory_limit' => ini_get('memory_limit')
    ];
});


Route::get('config', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('cache:forget spatie.permission.cache');
});

Auth::routes();

    Route::group(['prefix' => 'admin', 'middleware' => ['auth','check_block']], function () {
        Route::get('/home', [HomeController::class,'index'])->name('admin.dashboard');
        Route::match(['get','post'],'/profile', [HomeController::class,'profile'])->name('profile.manage');
        Route::group(['prefix' => 'advanced'], function () {
            Route::resource('settings', SettingController::class);
            Route::resource('custom-fields', CustomFieldController::class, ['names' => 'customFields']);
            Route::resource('file-types', FileTypeController::class, ['names' => 'fileTypes']);
        });

        
        Route::resource('qualities', QualityController::class);


        


        /* ********* */

        
        Route::get('/prestations/journalier', [PrestationController::class, 'dailyForm'])->name('prestations.daily.form');
        Route::post('/prestations/journalier', [PrestationController::class, 'dailyStore'])->name('prestations.daily.store');
        //Route::get('/prestations/journal', [PrestationController::class, 'journal'])->name('prestations.journal');
        Route::get('/prestations/journal', [PrestationController::class, 'pivotJournal'])->name('prestations.pivot');


        Route::get('/prestations/log', [PrestationController::class, 'logForm'])->name('prestations.log.form');
        Route::post('/prestations/log', [PrestationController::class, 'saveLog'])->name('prestations.log.store');
        Route::get('/prestations/types', [PrestationController::class, 'types'])->name('prestations.types');
        Route::get('/prestations', [PrestationController::class, 'pivotJournal'])->name('prestations.pivot'); 
        //Route::get('/prestations', [PrestationController::class, 'index'])->name('prestations.datatable');
        
        Route::post('/prestations/store', [PrestationController::class, 'store'])->name('prestations.store');
        Route::post('/prestations', [PrestationController::class, 'show'])->name('prestations.show');
        Route::get('/prestations/create', [PrestationController::class, 'create'])->name('prestations.create');
        Route::get('/prestations/{id}/edit', [PrestationController::class, 'edit'])->name('prestations.edit');
        Route::put('/prestations/{id}', [PrestationController::class, 'update'])->name('prestations.update');
        Route::delete('/prestations/{id}', [PrestationController::class, 'destroy'])->name('prestations.destroy');

     



        Route::get('/import', [ImportController::class, 'showImportForm'])->name('import.form');
        Route::post('/import/preview', [ImportController::class, 'previewImport'])->name('import.preview');
        Route::post('/import/execute', [ImportController::class, 'executeImport'])->name('import.execute');  


    /* ********* */
    
    
    Route::resource('users', UserController::class);
    Route::get('/users-block/{user}',[UserController::class,'blockUnblock'])->name('users.blockUnblock');
    Route::resource('tags', TagController::class);

    Route::resource('documents', DocumentController::class);
    Route::post('document-verify/{id}',[DocumentController::class,'verify'])->name('documents.verify');
    Route::post('document-store-permission/{id}',[DocumentController::class,'storePermission'])->name('documents.store-permission');
    Route::post('document-delete-permission/{document_id}/{user_id}',[DocumentController::class,'deletePermission'])->name('documents.delete-permission');
    Route::group(['prefix' => '/files-upload', 'as' => 'documents.files.'], function () {
        Route::get('/{id}', [DocumentController::class,'showUploadFilesUi'])->name('create');
        Route::post('/{id}', [DocumentController::class,'storeFiles'])->name('store');
        Route::delete('/{id}', [DocumentController::class,'deleteFile'])->name('destroy');
    });

    Route::get('/_files/{dir?}/{file?}',[HomeController::class,'showFile'])->name('files.showfile');
    Route::get('/_zip/{id}/{dir?}',[HomeController::class,'downloadZip'])->name('files.downloadZip');
    Route::post('/_pdf',[HomeController::class,'downloadPdf'])->name('files.downloadPdf');
});
