<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GameController;
use App\Http\Middleware\CheckStudentLogin;
use App\Http\Middleware\CheckAdminLogin;
use App\Http\Middleware\CheckParentLogin;

// Route home
Route::get('/', function () {
    return view('home');
})->name('home');

// ==================== ADMIN ROUTES ====================
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');
    
    Route::middleware(['CheckAdminLogin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');
        
        // Games management
        Route::get('/games', [AdminController::class, 'games'])->name('admin.games');
        Route::get('/games/create', [AdminController::class, 'createGame'])->name('admin.games.create');
        Route::post('/games', [AdminController::class, 'storeGame'])->name('admin.games.store');
        Route::get('/games/{id}/edit', [AdminController::class, 'editGame'])->name('admin.games.edit');
        Route::put('/games/{id}', [AdminController::class, 'updateGame'])->name('admin.games.update');
        Route::delete('/games/{id}', [AdminController::class, 'deleteGame'])->name('admin.games.delete');
        
        // Questions management
        Route::get('/games/{gameId}/questions', [AdminController::class, 'questions'])->name('admin.questions');
        Route::get('/games/{gameId}/questions/create', [AdminController::class, 'createQuestion'])->name('admin.questions.create');
        Route::post('/questions', [AdminController::class, 'storeQuestion'])->name('admin.questions.store');
        Route::get('/questions/{id}/edit', [AdminController::class, 'editQuestion'])->name('admin.questions.edit');
        Route::put('/questions/{id}', [AdminController::class, 'updateQuestion'])->name('admin.questions.update');
        Route::delete('/questions/{id}', [AdminController::class, 'deleteQuestion'])->name('admin.questions.delete');
        
        // Parents management
        Route::get('/parents', [AdminController::class, 'parents'])->name('admin.parents');
        Route::get('/parents/create', [AdminController::class, 'createParent'])->name('admin.parents.create');
        Route::post('/parents', [AdminController::class, 'storeParent'])->name('admin.parents.store');
        Route::get('/parents/{id}/edit', [AdminController::class, 'editParent'])->name('admin.parents.edit');
        Route::put('/parents/{id}', [AdminController::class, 'updateParent'])->name('admin.parents.update');
        Route::delete('/parents/{id}', [AdminController::class, 'deleteParent'])->name('admin.parents.delete');
        
        // Students management
        Route::get('/students', [AdminController::class, 'students'])->name('admin.students');
        Route::get('/students/create', [AdminController::class, 'createStudent'])->name('admin.students.create');
        Route::post('/students', [AdminController::class, 'storeStudent'])->name('admin.students.store');
        Route::get('/students/{id}/edit', [AdminController::class, 'editStudent'])->name('admin.students.edit');
        Route::put('/students/{id}', [AdminController::class, 'updateStudent'])->name('admin.students.update');
        Route::delete('/students/{id}', [AdminController::class, 'deleteStudent'])->name('admin.students.delete');
        
        // Posters management
        Route::get('/posters', [AdminController::class, 'posters'])->name('admin.posters');
        Route::get('/posters/create', [AdminController::class, 'createPoster'])->name('admin.posters.create');
        Route::post('/posters', [AdminController::class, 'storePoster'])->name('admin.posters.store');
        Route::get('/posters/{id}/edit', [AdminController::class, 'editPoster'])->name('admin.posters.edit');
        Route::put('/posters/{id}', [AdminController::class, 'updatePoster'])->name('admin.posters.update');
        Route::delete('/posters/{id}', [AdminController::class, 'deletePoster'])->name('admin.posters.delete');
    });
});

// ==================== STUDENT ROUTES ====================
// Route login student
Route::post('/student/login', [StudentController::class, 'login'])->name('student.login');
Route::get('/student/logout', [StudentController::class, 'logout'])->name('student.logout');

// Route start game - bisa diakses sebelum login (akan redirect ke home jika belum login)
Route::post('/games/{slug}/start', [GameController::class, 'start'])->name('games.start');

// Route games - HARUS LOGIN DULU
Route::middleware(['CheckStudentLogin'])->group(function () {
    Route::get('/games', [GameController::class, 'index'])->name('games.index');
    Route::get('/games/all', [GameController::class, 'all'])->name('games.all');
    Route::get('/games/{slug}', [GameController::class, 'show'])->name('games.show');
    Route::get('/session/{id}/question', [GameController::class, 'getQuestion'])->name('games.question');
    Route::post('/session/{id}/answer', [GameController::class, 'submitAnswer'])->name('games.answer');
    Route::get('/session/{id}/finish', [GameController::class, 'finish'])->name('games.finish');
    Route::post('/session/{id}/retry', [GameController::class, 'retry'])->name('games.retry');
});

// ==================== PARENT ROUTES ====================
Route::get('/parent/login', [ParentController::class, 'showLoginForm'])->name('parent.login');
Route::post('/parent/login', [ParentController::class, 'login'])->name('parent.login.post');

Route::middleware(['CheckParentLogin'])->group(function () {
    Route::get('/parent/dashboard', [ParentController::class, 'dashboard'])->name('parent.dashboard');
    Route::get('/parent/logout', [ParentController::class, 'logout'])->name('parent.logout');
});

// ==================== PUBLIC POSTER ROUTE ====================
Route::get('/posters', [App\Http\Controllers\PosterController::class, 'index'])->name('posters.index');