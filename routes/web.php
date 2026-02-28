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
use App\Http\Controllers\Backend\BulkImportController;

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
Route::post('/post/{slug}/comment', [PublicPageController::class , 'storeComment'])->name('public.post.comment');
Route::get('/page/{slug}', [PublicPageController::class , 'page'])->name('public.page');
Route::get('/category/{slug}', [PublicPageController::class , 'category'])->name('public.category');
Route::get('/tag/{slug}', [PublicPageController::class , 'tag'])->name('public.tag');
Route::get('/contact', [PublicPageController::class , 'contact'])->name('public.contact');

// Public Admission Routes
Route::get('/admission/form', [\App\Http\Controllers\PublicAdmissionController::class , 'showForm'])->name('public.admission.form');
Route::post('/admission/step1', [\App\Http\Controllers\PublicAdmissionController::class , 'validateAndShowStep2'])->name('admission.step1');
Route::post('/admission/step2', [\App\Http\Controllers\PublicAdmissionController::class , 'validateAndShowStep3'])->name('admission.step2');
Route::post('/admission/step3', [\App\Http\Controllers\PublicAdmissionController::class , 'validateAndShowStep4'])->name('admission.step3');
Route::post('/admission/submit', [\App\Http\Controllers\PublicAdmissionController::class , 'submitForm'])->name('admission.submit');
Route::get('/admission/confirmation/{registrant}', [\App\Http\Controllers\PublicAdmissionController::class , 'showConfirmation'])->name('public.admission.confirmation');
Route::get('/admission/download-pdf/{registrant}', [\App\Http\Controllers\PublicAdmissionController::class , 'downloadFormPDF'])->name('admission.download-pdf');
Route::get('/admission/blank-form', [\App\Http\Controllers\PublicAdmissionController::class , 'downloadBlankForm'])->name('admission.blank-form');
Route::get('/admission/results', [\App\Http\Controllers\PublicAdmissionController::class , 'showResultsLookup'])->name('admission.results-lookup');
Route::post('/admission/check-results', [\App\Http\Controllers\PublicAdmissionController::class , 'checkResults'])->name('admission.check-results');

// Public Admission API Endpoints
Route::get('/api/admission-phases', [\App\Http\Controllers\PublicAdmissionController::class , 'getAdmissionPhases']);
Route::get('/api/majors/{phaseId}', [\App\Http\Controllers\PublicAdmissionController::class , 'getMajorsForPhase']);

// Public Directory Routes
Route::get('/directory/alumni', [\App\Http\Controllers\PublicDirectoryController::class , 'showAlumniDirectory'])->name('public.directory.alumni');
Route::get('/directory/alumni/{student}', [\App\Http\Controllers\PublicDirectoryController::class , 'showAlumniProfile'])->name('public.directory.alumni.profile');
Route::get('/directory/students', [\App\Http\Controllers\PublicDirectoryController::class , 'showStudentDirectory'])->name('public.directory.students');
Route::get('/directory/students/{student}', [\App\Http\Controllers\PublicDirectoryController::class , 'showStudentProfile'])->name('public.directory.student.profile');
Route::get('/directory/employees', [\App\Http\Controllers\PublicDirectoryController::class , 'showEmployeeDirectory'])->name('public.directory.employees');
Route::get('/directory/employees/{employee}', [\App\Http\Controllers\PublicDirectoryController::class , 'showEmployeeProfile'])->name('public.directory.employee.profile');
Route::get('/api/directory/search', [\App\Http\Controllers\PublicDirectoryController::class , 'searchDirectory']);

// Public Search Routes
Route::get('/search', [\App\Http\Controllers\SearchController::class , 'search'])->name('public.search');
Route::get('/api/search/autocomplete', [\App\Http\Controllers\SearchController::class , 'autocomplete']);
Route::get('/api/search/trending', [\App\Http\Controllers\SearchController::class , 'trending']);

// Public Feed Routes (RSS, Atom, JSON)
Route::get('/feed', [\App\Http\Controllers\FeedController::class , 'feedBlog'])->name('public.feed.blog');
Route::get('/feed.xml', [\App\Http\Controllers\FeedController::class , 'feedBlog'])->name('public.feed.blog.xml');
Route::get('/feed.json', [\App\Http\Controllers\FeedController::class , 'feedBlog'])->name('public.feed.blog.json');
Route::get('/feed/atom', [\App\Http\Controllers\FeedController::class , 'feedAtom'])->name('public.feed.atom');

Route::get('/feed/category/{category:category_slug}', [\App\Http\Controllers\FeedController::class , 'feedCategory'])->name('public.feed.category');
Route::get('/feed/category/{category:category_slug}.xml', [\App\Http\Controllers\FeedController::class , 'feedCategory']);
Route::get('/feed/category/{category:category_slug}.json', [\App\Http\Controllers\FeedController::class , 'feedCategory']);

Route::get('/feed/tag/{tag:slug}', [\App\Http\Controllers\FeedController::class , 'feedTag'])->name('public.feed.tag');
Route::get('/feed/tag/{tag:slug}.xml', [\App\Http\Controllers\FeedController::class , 'feedTag']);
Route::get('/feed/tag/{tag:slug}.json', [\App\Http\Controllers\FeedController::class , 'feedTag']);

Route::get('/feed/alumni', [\App\Http\Controllers\FeedController::class , 'feedAlumni'])->name('public.feed.alumni');
Route::get('/feed/alumni.xml', [\App\Http\Controllers\FeedController::class , 'feedAlumni']);
Route::get('/feed/alumni.json', [\App\Http\Controllers\FeedController::class , 'feedAlumni']);

Route::get('/feed/students', [\App\Http\Controllers\FeedController::class , 'feedStudents'])->name('public.feed.students');
Route::get('/feed/students.xml', [\App\Http\Controllers\FeedController::class , 'feedStudents']);
Route::get('/feed/students.json', [\App\Http\Controllers\FeedController::class , 'feedStudents']);

Route::get('/feed/employees', [\App\Http\Controllers\FeedController::class , 'feedEmployees'])->name('public.feed.employees');
Route::get('/feed/employees.xml', [\App\Http\Controllers\FeedController::class , 'feedEmployees']);
Route::get('/feed/employees.json', [\App\Http\Controllers\FeedController::class , 'feedEmployees']);

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

            // PDF Generation Routes for Admissions
            Route::get('registrants/{registrant}/download-pdf', [App\Http\Controllers\Backend\RegistrantController::class , 'downloadPDF'])->name('registrants.download-pdf');
            Route::get('registrants/{registrant}/view-pdf', [App\Http\Controllers\Backend\RegistrantController::class , 'viewPDF'])->name('registrants.view-pdf');
            Route::get('registrants/{registrant}/regenerate-registration-number', [App\Http\Controllers\Backend\RegistrantController::class , 'regenerateRegistrationNumber'])->name('registrants.regenerate-registration-number');
            Route::get('admission/blank-form-pdf', [App\Http\Controllers\Backend\RegistrantController::class , 'downloadBlankForm'])->name('admission.blank-form-pdf');

            // Admission Selection Routes
            Route::resource('selections', App\Http\Controllers\Backend\AdmissionSelectionController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'])->names('selection');
            Route::post('selections/{selection}/execute', [App\Http\Controllers\Backend\AdmissionSelectionController::class , 'executeSelection'])->name('selection.execute');
            Route::post('selections/{selection}/announce-results', [App\Http\Controllers\Backend\AdmissionSelectionController::class , 'announceResults'])->name('selection.announce-results');
            Route::get('selections/{selection}/results', [App\Http\Controllers\Backend\AdmissionSelectionController::class , 'viewResults'])->name('selection.results');
            Route::get('selections/{selection}/export', [App\Http\Controllers\Backend\AdmissionSelectionController::class , 'exportResults'])->name('selection.export');
            Route::post('selections/{selection}/rollback', [App\Http\Controllers\Backend\AdmissionSelectionController::class , 'rollbackSelection'])->name('selection.rollback');
            Route::get('selections/{selection}/registrant-result', [App\Http\Controllers\Backend\AdmissionSelectionController::class , 'getRegistrantResult'])->name('selection.registrant-result');

            // Bulk Import Routes
            Route::get('import/students', [BulkImportController::class , 'showStudentImportForm'])->name('import.students.form');
            Route::post('import/students/preview', [BulkImportController::class , 'previewStudentImport'])->name('import.students.preview');
            Route::post('import/students/process', [BulkImportController::class , 'processStudentImport'])->name('import.students.process');
            Route::get('import/students/{importLog}/results', [BulkImportController::class , 'showStudentResults'])->name('import.students.results');
            Route::get('import/students/{importLog}/download-errors', [BulkImportController::class , 'downloadStudentErrorReport'])->name('import.students.download-errors');
            Route::get('import/students/template', [BulkImportController::class , 'downloadStudentTemplate'])->name('import.students.template');

            Route::get('import/employees', [BulkImportController::class , 'showEmployeeImportForm'])->name('import.employees.form');
            Route::post('import/employees/preview', [BulkImportController::class , 'previewEmployeeImport'])->name('import.employees.preview');
            Route::post('import/employees/process', [BulkImportController::class , 'processEmployeeImport'])->name('import.employees.process');
            Route::get('import/employees/{importLog}/results', [BulkImportController::class , 'showEmployeeResults'])->name('import.employees.results');
            Route::get('import/employees/{importLog}/download-errors', [BulkImportController::class , 'downloadEmployeeErrorReport'])->name('import.employees.download-errors');
            Route::get('import/employees/template', [BulkImportController::class , 'downloadEmployeeTemplate'])->name('import.employees.template');

            Route::get('import/history', [BulkImportController::class , 'showImportHistory'])->name('import.history');
            Route::post('import/{importLog}/rollback', [BulkImportController::class , 'rollbackImport'])->name('import.rollback');

            // Maintenance Routes
            Route::get('backup/database', [App\Http\Controllers\Backend\BackupController::class , 'downloadDatabase'])->name('backup.database');

            // Reporting & Analytics Routes
            Route::get('reports/dashboard', [App\Http\Controllers\Backend\ReportingController::class , 'dashboard'])->name('reports.dashboard');
            Route::get('reports/students', [App\Http\Controllers\Backend\ReportingController::class , 'studentStatistics'])->name('reports.students');
            Route::get('reports/admissions', [App\Http\Controllers\Backend\ReportingController::class , 'admissionAnalytics'])->name('reports.admissions');
            Route::get('reports/employees', [App\Http\Controllers\Backend\ReportingController::class , 'employeeStatistics'])->name('reports.employees');
            Route::get('reports/academic', [App\Http\Controllers\Backend\ReportingController::class , 'academicAnalysis'])->name('reports.academic');
            Route::get('reports/students/export', [App\Http\Controllers\Backend\ReportingController::class , 'exportStudentReport'])->name('reports.students.export');
            Route::get('reports/admissions/export', [App\Http\Controllers\Backend\ReportingController::class , 'exportAdmissionReport'])->name('reports.admissions.export');
            Route::get('reports/employees/export', [App\Http\Controllers\Backend\ReportingController::class , 'exportEmployeeReport'])->name('reports.employees.export');

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
            Route::get('themes', ThemeManager::class)->name('themes');
            Route::get('banners', \App\Livewire\Backend\BannerManager::class)->name('banners.index');

            // Settings (Livewire)
    

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

            // Security Management Routes (Phase 4)
            Route::get('security/ip-bans', [App\Http\Controllers\Backend\SecurityController::class , 'showIPBans'])->name('security.ip-bans');
            Route::patch('security/ip-bans/{ban}/release', [App\Http\Controllers\Backend\SecurityController::class , 'releaseBan'])->name('security.release-ban');
            Route::get('security/ip-bans/{ban}/history', [App\Http\Controllers\Backend\SecurityController::class , 'viewBanHistory'])->name('security.ban-history');
            Route::delete('security/ip-bans/{ban}', [App\Http\Controllers\Backend\SecurityController::class , 'deleteBan'])->name('security.delete-ban');
        }
        );
    });
