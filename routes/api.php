<?php

use App\Http\Controllers\API\Admin\AdminBlogsController;
use App\Http\Controllers\API\Admin\AdminCompanyController;
use App\Http\Controllers\API\Admin\UsersController;
use App\Http\Controllers\API\Customer\BlogsController;
use App\Http\Controllers\API\Customer\CompanyDetailsController;
use App\Http\Controllers\API\Customer\ProjectsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Admin\AdminProjectController;

// Handle preflight OPTIONS requests
Route::options('{any}', function () {
    return response('', 200);
})->where('any', '.*');


//Routes for Customer UI
Route::prefix('customer')->middleware(['throttle:'.env('API_RATE_LIMIT', 60).',1'])->group(function(){
    Route::get('company-details', [CompanyDetailsController::class, 'getCompanyDetails']);

    Route::get('blogs', [BlogsController::class, 'getAllBlogs']);
    Route::get('blogs/{id}', [BlogsController::class, 'getBlogDetails']);

    Route::get('project-types', [ProjectsController::class, 'getAllProjectTypes']);
    Route::get('projects', [ProjectsController::class, 'getAllProjects']);
    Route::get('projects/{id}', [ProjectsController::class, 'getProjectDetails']);
});

//Routes for Admin Panel
Route::prefix('admin')->middleware(['throttle:'.env('ADMIN_RATE_LIMIT', 120).',1'])->group(function(){
    // Admin Authentication
    Route::post('register', [UsersController::class, 'register']);
    Route::post('login', [UsersController::class, 'login']);
    
    // Protected Routes (require authentication)
    Route::group(['middleware' => ['auth:api']], function () {

        // User's Profile and Account Management
        Route::get('profile', [UsersController::class, 'profile']);
        Route::put('edit-profile', [UsersController::class, 'editProfile']);
        Route::post('change-password', [UsersController::class, 'changePassword']);
        Route::get('logout', [UsersController::class, 'logout']);
        Route::post('refresh', [UsersController::class, 'refresh']);

        //Manage All Users Accounts
        Route::get('users', [UsersController::class, 'getAllUsers']);
        Route::get('users/{id}', [UsersController::class, 'getOtherUserDetails']);
        Route::put('users/{id}', [UsersController::class, 'editOtherUserDetails']);
        Route::post('users/{id}/change-password', [UsersController::class, 'editOtherUserPassword']);
        Route::delete('users/{id}', [UsersController::class, 'deleteUser']);
        
        //Manage All Blogs
        Route::get('blogs', [AdminBlogsController::class, 'getAllBlogs']);
        Route::get('blogs/{id}', [AdminBlogsController::class, 'getBlog']);
        Route::post('blogs', [AdminBlogsController::class, 'createBlog']);
        Route::put('blogs/{id}', [AdminBlogsController::class, 'editBlog']);
        Route::delete('blogs/{id}', [AdminBlogsController::class, 'deleteBlog']);
        Route::post('blogs/{id}/images', [AdminBlogsController::class, 'addImagesToBlog']);
        Route::delete('blog-images/{id}', [AdminBlogsController::class, 'deleteBlogImage']);
        

        //Manage All Projects
        Route::get('projects', [AdminProjectController::class, 'getAllProjects']);
        Route::get('projects/{id}', [AdminProjectController::class, 'getProject']);
        Route::post('projects', [AdminProjectController::class, 'createProject']);
        Route::put('projects/{id}/details', [AdminProjectController::class, 'editProjectDetails']);
        Route::post('projects/{id}/new-thumbnail', [AdminProjectController::class, 'replaceProjectThumbnail']);
        Route::post('projects/{id}/features', [AdminProjectController::class, 'createProjectFeatures']);
        Route::put('project-features/{id}/details', [AdminProjectController::class, 'editProjectFeatures']);
        Route::delete('projects/{id}', [AdminProjectController::class, 'deleteProject']);
        Route::delete('project-features/{id}', [AdminProjectController::class, 'deleteProjectFeature']);

        
        //Manage All Project Types
        Route::get('project-types', [AdminProjectController::class, 'getAllProjectTypes']);
        Route::get('project-types/{id}', [AdminProjectController::class, 'getProjectType']);
        Route::post('project-types', [AdminProjectController::class, 'createProjectType']);
        Route::put('project-types/{id}', [AdminProjectController::class, 'editProjectType']);
        Route::delete('project-types/{id}', [AdminProjectController::class, 'deleteProjectType']);

        //Manage Project Features Images
        Route::post('project-features/{id}/images', [AdminProjectController::class, 'addProjectFeaturesImages']);
        Route::delete('project-feature-images/{id}', [AdminProjectController::class, 'deleteProjectFeaturesImages']);

        //Manage Company Details
        Route::get('company', [AdminCompanyController::class, 'getCompanyDetails']);
        Route::post('company', [AdminCompanyController::class, 'addCompanyDetails']);
        Route::put('company', [AdminCompanyController::class, 'editCompanyDetails']);
        Route::post('company/logo', [AdminCompanyController::class, 'replaceCompanyLogo']);

        //Manage Company Social Media
        Route::post('company/social-media', [AdminCompanyController::class, 'addCompanySocialMedia']);
        Route::put('company/social-media/{id}', [AdminCompanyController::class, 'editCompanySocialMedia']);
        Route::delete('company/social-media/{id}', [AdminCompanyController::class, 'deleteCompanySocialMedia']);

        //Manage Company Contacts
        Route::post('company/contacts', [AdminCompanyController::class, 'addCompanyContact']);
        Route::put('company/contacts/{id}', [AdminCompanyController::class, 'editCompanyContact']);
        Route::delete('company/contacts/{id}', [AdminCompanyController::class, 'deleteCompanyContact']);
    });
});