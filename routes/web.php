<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\UserGroupController;
use App\Http\Controllers\Backend\UserPrivilegeController;
use App\Http\Controllers\Backend\StudentController;
use App\Http\Controllers\Backend\EmployeeController;
use App\Http\Controllers\Backend\AcademicYearController;
use App\Http\Controllers\Backend\MajorController;
use App\Http\Controllers\Backend\ClassGroupController;

// Livewire Components
use App\Livewire\Backend\PostManager;
use App\Livewire\Backend\OptionManager;
use App\Livewire\Backend\MessageManager;
use App\Livewire\Backend\PostCommentManager;
use App\Livewire\Backend\OpeningSpeech;
use App\Livewire\Backend\SettingsManager;
use App\Livewire\Backend\RegistrantFiltered;
use App\Livewire\Backend\MenuManager;
use App\Livewire\Backend\ThemeManager;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- */

// Public Frontend Routes
Route::get('/', [PublicPageController::class , 'home'])->name('home');
Route::get('/post/{slug}', [PublicPageController::class , 'post'])->name('public.post');
Route::get('/page/{slug}', [PublicPageController::class , 'page'])->name('public.page');
Route::get('/category/{slug}', [PublicPageController::class , 'category'])->name('public.category');
Route::get('/tag/{slug}', [PublicPageController::class , 'tag'])->name('public.tag');
Route::get('/contact', [PublicPageController::class , 'contact'])->name('public.contact');

Route::get('/login', [AuthController::class , 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class , 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class , 'logout'])->name('logout')->middleware('auth');

Route::get('/dashboard', [DashboardController::class , 'index'])->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class , 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class , 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class , 'changePassword'])->name('profile.password');
    Route::post('/profile/password', [ProfileController::class , 'updatePassword'])->name('profile.password.update');

    Route::get('/settings/{group}', [SettingController::class , 'index'])->name('settings.index');
    Route::post('/settings/{group}', [SettingController::class , 'update'])->name('settings.update');

    // Users Management Routes (backend namespace/prefix)
    Route::prefix('backend')->name('backend.')->group(function () {
            // Academic Management Routes
            Route::resource('academic_years', AcademicYearController::class)->except(['create', 'show', 'edit']);
            Route::resource('majors', MajorController::class)->except(['create', 'show', 'edit']);
            Route::resource('class_groups', ClassGroupController::class)->except(['create', 'show', 'edit']);
            Route::resource('alumni', App\Http\Controllers\Backend\AlumniController::class)->only(['index', 'update']);
            Route::resource('academic_students', App\Http\Controllers\Backend\AcademicStudentController::class)->except(['create', 'show', 'edit']);

            // Modules Routes
            Route::resource('academic_employees', App\Http\Controllers\Backend\AcademicEmployeeController::class)->except(['create', 'show', 'edit']);

            // CMS / Blog Routes (Livewire)
            Route::get('posts-live', PostManager::class)->name('posts_live');
            Route::get('messages', MessageManager::class)->name('messages');
            Route::get('post-comments', PostCommentManager::class)->name('post_comments_live');
            Route::get('opening-speech', OpeningSpeech::class)->name('opening_speech');

            // CMS / Blog Routes (Traditional)
            Route::resource('posts', App\Http\Controllers\Backend\PostController::class)->except(['create', 'show', 'edit']);
            Route::resource('pages', App\Http\Controllers\Backend\PageController::class)->except(['create', 'show', 'edit']);
            Route::resource('post_categories', App\Http\Controllers\Backend\PostCategoryController::class)->except(['create', 'show', 'edit']);
            Route::resource('tags', App\Http\Controllers\Backend\TagController::class)->except(['create', 'show', 'edit']);
            Route::resource('image_sliders', App\Http\Controllers\Backend\ImageSliderController::class)->except(['create', 'show', 'edit']);
            Route::resource('links', App\Http\Controllers\Backend\LinkController::class)->except(['create', 'show', 'edit']);
            Route::resource('banners', App\Http\Controllers\Backend\BannerController::class)->except(['create', 'show', 'edit']);
            Route::resource('questions', App\Http\Controllers\Backend\QuestionController::class)->except(['create', 'show', 'edit']);
            Route::resource('answers', App\Http\Controllers\Backend\AnswerController::class)->except(['create', 'show', 'edit']);
            Route::resource('quotes', App\Http\Controllers\Backend\QuoteController::class)->except(['create', 'show', 'edit']);
            Route::resource('albums', App\Http\Controllers\Backend\AlbumController::class)->except(['create', 'show', 'edit']);
            Route::resource('photos', App\Http\Controllers\Backend\PhotoController::class)->only(['index', 'store', 'destroy']);
            Route::resource('videos', App\Http\Controllers\Backend\VideoController::class)->except(['create', 'show', 'edit']);
            Route::resource('file_categories', App\Http\Controllers\Backend\FileCategoryController::class)->except(['create', 'show', 'edit']);
            Route::resource('files', App\Http\Controllers\Backend\FileController::class)->except(['create', 'show', 'edit']);
            Route::resource('subscribers', App\Http\Controllers\Backend\SubscriberController::class)->only(['index', 'destroy']);

            // Admission / PPDB Routes (Traditional + Livewire)
            Route::resource('admission_phases', App\Http\Controllers\Backend\AdmissionPhaseController::class)->except(['create', 'show', 'edit']);
            Route::resource('admission_quotas', App\Http\Controllers\Backend\AdmissionQuotaController::class)->except(['create', 'show', 'edit']);
            Route::resource('registrants', App\Http\Controllers\Backend\RegistrantController::class)->except(['create', 'show', 'edit']);
            Route::get('registrants-approved', RegistrantFiltered::class)->name('registrants_approved');
            Route::get('registrants-unapproved', RegistrantFiltered::class)->name('registrants_unapproved');

            // Academic References (Livewire - Options table)
            Route::get('transportations', OptionManager::class)->name('transportations');
            Route::get('monthly-incomes', OptionManager::class)->name('monthly_incomes');
            Route::get('residences', OptionManager::class)->name('residences');
            Route::get('student-status', OptionManager::class)->name('student_status');
            Route::get('admission-types', OptionManager::class)->name('admission_types');
            Route::get('employments', OptionManager::class)->name('employments');

            // Appearance (Livewire)
            Route::get('menus', MenuManager::class)->name('menus');
            Route::get('themes', ThemeManager::class)->name('themes');

            // Settings (Livewire)
            Route::get('settings-discussion', SettingsManager::class)->name('settings_discussion');
            Route::get('settings-media', SettingsManager::class)->name('settings_media');
            Route::get('settings-writing', SettingsManager::class)->name('settings_writing');
            Route::get('settings-reading', SettingsManager::class)->name('settings_reading');

            // Reference / Options Routes (Traditional)
            Route::resource('educations', App\Http\Controllers\Backend\EducationController::class)->except(['create', 'show', 'edit']);
            Route::resource('special_needs', App\Http\Controllers\Backend\SpecialNeedController::class)->except(['create', 'show', 'edit']);
            Route::resource('employment_statuses', App\Http\Controllers\Backend\EmploymentStatusController::class)->except(['create', 'show', 'edit']);
            Route::resource('employment_types', App\Http\Controllers\Backend\EmploymentTypeController::class)->except(['create', 'show', 'edit']);
            Route::resource('institution_lifters', App\Http\Controllers\Backend\InstitutionLifterController::class)->except(['create', 'show', 'edit']);
            Route::resource('laboratory_skills', App\Http\Controllers\Backend\LaboratorySkillController::class)->except(['create', 'show', 'edit']);
            Route::resource('ranks', App\Http\Controllers\Backend\RankController::class)->except(['create', 'show', 'edit']);
            Route::resource('salary_sources', App\Http\Controllers\Backend\SalarySourceController::class)->except(['create', 'show', 'edit']);

            // Users Management Routes
            Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
            Route::resource('user_groups', UserGroupController::class)->except(['create', 'show', 'edit']);
            Route::resource('user_privileges', UserPrivilegeController::class)->only(['index', 'store', 'destroy']);

            Route::resource('user_students', StudentController::class)->only(['index', 'update']);
            Route::resource('user_employees', EmployeeController::class)->only(['index', 'update']);

            // Self-Service Portals
            Route::get('student_profile', [App\Http\Controllers\Backend\StudentProfileController::class , 'index'])->name('student_profile.index');
            Route::put('student_profile', [App\Http\Controllers\Backend\StudentProfileController::class , 'update'])->name('student_profile.update');

            Route::get('employee_profile', [App\Http\Controllers\Backend\EmployeeProfileController::class , 'index'])->name('employee_profile.index');
            Route::put('employee_profile', [App\Http\Controllers\Backend\EmployeeProfileController::class , 'update'])->name('employee_profile.update');

            Route::resource('achievements', App\Http\Controllers\Backend\AchievementController::class)->only(['index', 'store', 'update', 'destroy']);
            Route::resource('scholarships', App\Http\Controllers\Backend\ScholarshipController::class)->only(['index', 'store', 'update', 'destroy']);
        }
        );
    });
