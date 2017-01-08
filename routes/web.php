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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// log.activity middleware logs the time of user activity that any inclusive routes are hit
Route::group(['middleware' => ['log.activity']], function() {

    // use profile
    Route::get('/user/profile/@{user}', 'ProfileController@index')->name('user.profile.index');

    // auth routing
    Route::group(['middleware' => ['auth']], function () {

        // general auth routing
        Route::get('/home', 'HomeController@index')->name('home.index');

        Route::group(['prefix' => 'forum'], function() {
            // auth forum routes
            // topics
            Route::get('/topics/create', 'TopicsController@showCreateForm')->name('forum.topics.create.form');
            Route::post('/topics/create', 'TopicsController@create')->name('forum.topics.create.submit');

            // subscriptions
            Route::get('/topics/{topic}/subscription/status', 'SubscriptionsController@getSubscriptionStatus')->name('forum.topics.topic.subscription.status');
            Route::post('/topics/{topic}/subscription', 'SubscriptionsController@handleSubscription')->name('forum.topics.topic.subscription.submit');

            // posts
            Route::post('/topics/{topic}/posts/create', 'PostsController@create')->name('forum.topics.posts.create.submit');
            Route::get('/topics/{topic}/posts/{post}/edit', 'PostsController@edit')->name('forum.topics.topic.posts.post.edit');
            Route::post('/topics/{topic}/posts/{post}/update', 'PostsController@update')->name('forum.topics.topic.posts.post.update');
            Route::delete('/topics/{topic}/posts/{post}/delete', 'PostsController@destroy')->name('forum.topics.topic.posts.post.delete');

            // reports
            Route::post('/topics/{topic}/report', 'TopicsReportController@report')->name('forum.topics.topic.report.report');
            Route::post('/topics/{topic}/posts/{post}/report', 'PostsReportController@report')->name('forum.topics.topic.posts.post.report.report');

            // auth.elevated refers to moderator || admin roles
            Route::group(['middleware' => ['auth.elevated']], function() {
                Route::delete('/topics/{topic}', 'TopicsController@destroy')->name('forum.topics.topic.delete');
            });
        });

        // user routing
        Route::group(['prefix' => 'user'], function() {

            Route::group(['prefix' => 'chat/threads'], function() {
                // user messaging
                Route::get('/', 'MessagesThreadController@index')->name('user.chat.threads.index');
                Route::post('/create', 'MessagesThreadController@create')->name('user.chat.threads.create');

                Route::get('/@{user}/messages', 'MessagesController@index')->name('user.chat.threads.thread.messages.index');
                Route::get('/@{user}/messages/fetch', 'MessagesController@fetchMessages')->name('user.chat.threads.thread.messages.fetch');
                Route::post('/@{user}/messages', 'MessagesController@create')->name('user.chat.threads.thread.messages.create');
            });

            Route::group(['prefix' => 'profile'], function() {
                // user profile
                Route::get('/@{user}/settings', 'ProfileSettingsController@index')->name('user.profile.settings.index');
                Route::post('/@{user}/settings/update/', 'ProfileSettingsController@update')->name('user.profile.settings.update');
            });

        });

        // admin routing
        Route::group(['prefix' => 'admin', 'middleware' => ['auth.admin']], function () {
            // admin dashboard
            Route::get('/dashboard', 'AdministratorDashboardController@index')->name('admin.dashboard.index');
            Route::post('/dashboard/update', 'AdministratorDashboardController@update')->name('admin.dashboard.update');
            Route::post('/dashboard/invite', 'AdministratorDashboardController@invite')->name('admin.dashboard.invite');

            Route::delete('/dashboard/users/{user}', 'AdministratorDashboardController@destroy')->name('admin.dashboard.user.destroy');
        });

        // moderator dashboard, also accessible by admin (auth.elevated)
        Route::group(['prefix' => 'moderator', 'middleware' => ['auth.elevated']], function() {
            Route::get('/dashboard', 'ModeratorDashboardController@index')->name('moderator.dashboard.index');
            Route::delete('/dashboard/reports/{report}', 'ModeratorDashboardController@destroy')->name('moderator.dashboard.reports.report.destroy');
        });

    });

    // public forum routing
    Route::group(['prefix' => 'forum'], function() {
        // view topics and topic posts
        Route::get('/', 'TopicsController@index')->name('forum.topics.index');
        Route::get('/topics/{topic}', 'TopicsController@show')->name('forum.topics.topic.show');

        // check status of content, in relation to reporting
        Route::get('/topics/{topic}/report/status', 'TopicsReportController@status')->name('forum.topics.topic.report.status');
        Route::get('/topics/{topic}/posts/{post}/report/status', 'PostsReportController@status')->name('forum.topics.topic.posts.post.report.status');
    });

});


Auth::routes();

//Route::get('/home', 'HomeController@index');

Route::get('generator_builder', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@builder');

Route::get('field_template', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@fieldTemplate');

Route::post('generator_builder/generate', '\InfyOm\GeneratorBuilder\Controllers\GeneratorBuilderController@generate');

Auth::routes();

Route::get('/home', 'HomeController@index');

Route::get('admincp/modules/activitylogs', ['as'=> 'admincp.modules.activitylogs.index', 'uses' => 'Modules\ActivitylogController@index']);
Route::post('admincp/modules/activitylogs', ['as'=> 'admincp.modules.activitylogs.store', 'uses' => 'Modules\ActivitylogController@store']);
Route::get('admincp/modules/activitylogs/create', ['as'=> 'admincp.modules.activitylogs.create', 'uses' => 'Modules\ActivitylogController@create']);
Route::put('admincp/modules/activitylogs/{activitylogs}', ['as'=> 'admincp.modules.activitylogs.update', 'uses' => 'Modules\ActivitylogController@update']);
Route::patch('admincp/modules/activitylogs/{activitylogs}', ['as'=> 'admincp.modules.activitylogs.update', 'uses' => 'Modules\ActivitylogController@update']);
Route::delete('admincp/modules/activitylogs/{activitylogs}', ['as'=> 'admincp.modules.activitylogs.destroy', 'uses' => 'Modules\ActivitylogController@destroy']);
Route::get('admincp/modules/activitylogs/{activitylogs}', ['as'=> 'admincp.modules.activitylogs.show', 'uses' => 'Modules\ActivitylogController@show']);
Route::get('admincp/modules/activitylogs/{activitylogs}/edit', ['as'=> 'admincp.modules.activitylogs.edit', 'uses' => 'Modules\ActivitylogController@edit']);


Route::get('admincp/modules/backups', ['as'=> 'admincp.modules.backups.index', 'uses' => 'Modules\BackupController@index']);
Route::post('admincp/modules/backups', ['as'=> 'admincp.modules.backups.store', 'uses' => 'Modules\BackupController@store']);
Route::get('admincp/modules/backups/create', ['as'=> 'admincp.modules.backups.create', 'uses' => 'Modules\BackupController@create']);
Route::put('admincp/modules/backups/{backups}', ['as'=> 'admincp.modules.backups.update', 'uses' => 'Modules\BackupController@update']);
Route::patch('admincp/modules/backups/{backups}', ['as'=> 'admincp.modules.backups.update', 'uses' => 'Modules\BackupController@update']);
Route::delete('admincp/modules/backups/{backups}', ['as'=> 'admincp.modules.backups.destroy', 'uses' => 'Modules\BackupController@destroy']);
Route::get('admincp/modules/backups/{backups}', ['as'=> 'admincp.modules.backups.show', 'uses' => 'Modules\BackupController@show']);
Route::get('admincp/modules/backups/{backups}/edit', ['as'=> 'admincp.modules.backups.edit', 'uses' => 'Modules\BackupController@edit']);


Route::get('admincp/modules/contactPeople', ['as'=> 'admincp.modules.contactPeople.index', 'uses' => 'Modules\ContactPersonController@index']);
Route::post('admincp/modules/contactPeople', ['as'=> 'admincp.modules.contactPeople.store', 'uses' => 'Modules\ContactPersonController@store']);
Route::get('admincp/modules/contactPeople/create', ['as'=> 'admincp.modules.contactPeople.create', 'uses' => 'Modules\ContactPersonController@create']);
Route::put('admincp/modules/contactPeople/{contactPeople}', ['as'=> 'admincp.modules.contactPeople.update', 'uses' => 'Modules\ContactPersonController@update']);
Route::patch('admincp/modules/contactPeople/{contactPeople}', ['as'=> 'admincp.modules.contactPeople.update', 'uses' => 'Modules\ContactPersonController@update']);
Route::delete('admincp/modules/contactPeople/{contactPeople}', ['as'=> 'admincp.modules.contactPeople.destroy', 'uses' => 'Modules\ContactPersonController@destroy']);
Route::get('admincp/modules/contactPeople/{contactPeople}', ['as'=> 'admincp.modules.contactPeople.show', 'uses' => 'Modules\ContactPersonController@show']);
Route::get('admincp/modules/contactPeople/{contactPeople}/edit', ['as'=> 'admincp.modules.contactPeople.edit', 'uses' => 'Modules\ContactPersonController@edit']);


Route::get('admincp/modules/departments', ['as'=> 'admincp.modules.departments.index', 'uses' => 'Modules\DepartmentController@index']);
Route::post('admincp/modules/departments', ['as'=> 'admincp.modules.departments.store', 'uses' => 'Modules\DepartmentController@store']);
Route::get('admincp/modules/departments/create', ['as'=> 'admincp.modules.departments.create', 'uses' => 'Modules\DepartmentController@create']);
Route::put('admincp/modules/departments/{departments}', ['as'=> 'admincp.modules.departments.update', 'uses' => 'Modules\DepartmentController@update']);
Route::patch('admincp/modules/departments/{departments}', ['as'=> 'admincp.modules.departments.update', 'uses' => 'Modules\DepartmentController@update']);
Route::delete('admincp/modules/departments/{departments}', ['as'=> 'admincp.modules.departments.destroy', 'uses' => 'Modules\DepartmentController@destroy']);
Route::get('admincp/modules/departments/{departments}', ['as'=> 'admincp.modules.departments.show', 'uses' => 'Modules\DepartmentController@show']);
Route::get('admincp/modules/departments/{departments}/edit', ['as'=> 'admincp.modules.departments.edit', 'uses' => 'Modules\DepartmentController@edit']);


Route::get('admincp/modules/employees', ['as'=> 'admincp.modules.employees.index', 'uses' => 'Modules\EmployeeController@index']);
Route::post('admincp/modules/employees', ['as'=> 'admincp.modules.employees.store', 'uses' => 'Modules\EmployeeController@store']);
Route::get('admincp/modules/employees/create', ['as'=> 'admincp.modules.employees.create', 'uses' => 'Modules\EmployeeController@create']);
Route::put('admincp/modules/employees/{employees}', ['as'=> 'admincp.modules.employees.update', 'uses' => 'Modules\EmployeeController@update']);
Route::patch('admincp/modules/employees/{employees}', ['as'=> 'admincp.modules.employees.update', 'uses' => 'Modules\EmployeeController@update']);
Route::delete('admincp/modules/employees/{employees}', ['as'=> 'admincp.modules.employees.destroy', 'uses' => 'Modules\EmployeeController@destroy']);
Route::get('admincp/modules/employees/{employees}', ['as'=> 'admincp.modules.employees.show', 'uses' => 'Modules\EmployeeController@show']);
Route::get('admincp/modules/employees/{employees}/edit', ['as'=> 'admincp.modules.employees.edit', 'uses' => 'Modules\EmployeeController@edit']);


Route::get('admincp/modules/leads', ['as'=> 'admincp.modules.leads.index', 'uses' => 'Modules\LeadController@index']);
Route::post('admincp/modules/leads', ['as'=> 'admincp.modules.leads.store', 'uses' => 'Modules\LeadController@store']);
Route::get('admincp/modules/leads/create', ['as'=> 'admincp.modules.leads.create', 'uses' => 'Modules\LeadController@create']);
Route::put('admincp/modules/leads/{leads}', ['as'=> 'admincp.modules.leads.update', 'uses' => 'Modules\LeadController@update']);
Route::patch('admincp/modules/leads/{leads}', ['as'=> 'admincp.modules.leads.update', 'uses' => 'Modules\LeadController@update']);
Route::delete('admincp/modules/leads/{leads}', ['as'=> 'admincp.modules.leads.destroy', 'uses' => 'Modules\LeadController@destroy']);
Route::get('admincp/modules/leads/{leads}', ['as'=> 'admincp.modules.leads.show', 'uses' => 'Modules\LeadController@show']);
Route::get('admincp/modules/leads/{leads}/edit', ['as'=> 'admincp.modules.leads.edit', 'uses' => 'Modules\LeadController@edit']);


Route::get('admincp/modules/lkpActivityPriorities', ['as'=> 'admincp.modules.lkpActivityPriorities.index', 'uses' => 'Modules\LkpActivityPriorityController@index']);
Route::post('admincp/modules/lkpActivityPriorities', ['as'=> 'admincp.modules.lkpActivityPriorities.store', 'uses' => 'Modules\LkpActivityPriorityController@store']);
Route::get('admincp/modules/lkpActivityPriorities/create', ['as'=> 'admincp.modules.lkpActivityPriorities.create', 'uses' => 'Modules\LkpActivityPriorityController@create']);
Route::put('admincp/modules/lkpActivityPriorities/{lkpActivityPriorities}', ['as'=> 'admincp.modules.lkpActivityPriorities.update', 'uses' => 'Modules\LkpActivityPriorityController@update']);
Route::patch('admincp/modules/lkpActivityPriorities/{lkpActivityPriorities}', ['as'=> 'admincp.modules.lkpActivityPriorities.update', 'uses' => 'Modules\LkpActivityPriorityController@update']);
Route::delete('admincp/modules/lkpActivityPriorities/{lkpActivityPriorities}', ['as'=> 'admincp.modules.lkpActivityPriorities.destroy', 'uses' => 'Modules\LkpActivityPriorityController@destroy']);
Route::get('admincp/modules/lkpActivityPriorities/{lkpActivityPriorities}', ['as'=> 'admincp.modules.lkpActivityPriorities.show', 'uses' => 'Modules\LkpActivityPriorityController@show']);
Route::get('admincp/modules/lkpActivityPriorities/{lkpActivityPriorities}/edit', ['as'=> 'admincp.modules.lkpActivityPriorities.edit', 'uses' => 'Modules\LkpActivityPriorityController@edit']);


Route::get('admincp/modules/lkpActivityStatuses', ['as'=> 'admincp.modules.lkpActivityStatuses.index', 'uses' => 'Modules\LkpActivityStatusController@index']);
Route::post('admincp/modules/lkpActivityStatuses', ['as'=> 'admincp.modules.lkpActivityStatuses.store', 'uses' => 'Modules\LkpActivityStatusController@store']);
Route::get('admincp/modules/lkpActivityStatuses/create', ['as'=> 'admincp.modules.lkpActivityStatuses.create', 'uses' => 'Modules\LkpActivityStatusController@create']);
Route::put('admincp/modules/lkpActivityStatuses/{lkpActivityStatuses}', ['as'=> 'admincp.modules.lkpActivityStatuses.update', 'uses' => 'Modules\LkpActivityStatusController@update']);
Route::patch('admincp/modules/lkpActivityStatuses/{lkpActivityStatuses}', ['as'=> 'admincp.modules.lkpActivityStatuses.update', 'uses' => 'Modules\LkpActivityStatusController@update']);
Route::delete('admincp/modules/lkpActivityStatuses/{lkpActivityStatuses}', ['as'=> 'admincp.modules.lkpActivityStatuses.destroy', 'uses' => 'Modules\LkpActivityStatusController@destroy']);
Route::get('admincp/modules/lkpActivityStatuses/{lkpActivityStatuses}', ['as'=> 'admincp.modules.lkpActivityStatuses.show', 'uses' => 'Modules\LkpActivityStatusController@show']);
Route::get('admincp/modules/lkpActivityStatuses/{lkpActivityStatuses}/edit', ['as'=> 'admincp.modules.lkpActivityStatuses.edit', 'uses' => 'Modules\LkpActivityStatusController@edit']);


Route::get('admincp/modules/lkpActivityTypes', ['as'=> 'admincp.modules.lkpActivityTypes.index', 'uses' => 'Modules\LkpActivityTypeController@index']);
Route::post('admincp/modules/lkpActivityTypes', ['as'=> 'admincp.modules.lkpActivityTypes.store', 'uses' => 'Modules\LkpActivityTypeController@store']);
Route::get('admincp/modules/lkpActivityTypes/create', ['as'=> 'admincp.modules.lkpActivityTypes.create', 'uses' => 'Modules\LkpActivityTypeController@create']);
Route::put('admincp/modules/lkpActivityTypes/{lkpActivityTypes}', ['as'=> 'admincp.modules.lkpActivityTypes.update', 'uses' => 'Modules\LkpActivityTypeController@update']);
Route::patch('admincp/modules/lkpActivityTypes/{lkpActivityTypes}', ['as'=> 'admincp.modules.lkpActivityTypes.update', 'uses' => 'Modules\LkpActivityTypeController@update']);
Route::delete('admincp/modules/lkpActivityTypes/{lkpActivityTypes}', ['as'=> 'admincp.modules.lkpActivityTypes.destroy', 'uses' => 'Modules\LkpActivityTypeController@destroy']);
Route::get('admincp/modules/lkpActivityTypes/{lkpActivityTypes}', ['as'=> 'admincp.modules.lkpActivityTypes.show', 'uses' => 'Modules\LkpActivityTypeController@show']);
Route::get('admincp/modules/lkpActivityTypes/{lkpActivityTypes}/edit', ['as'=> 'admincp.modules.lkpActivityTypes.edit', 'uses' => 'Modules\LkpActivityTypeController@edit']);


Route::get('admincp/modules/lkpContactTypes', ['as'=> 'admincp.modules.lkpContactTypes.index', 'uses' => 'Modules\LkpContactTypeController@index']);
Route::post('admincp/modules/lkpContactTypes', ['as'=> 'admincp.modules.lkpContactTypes.store', 'uses' => 'Modules\LkpContactTypeController@store']);
Route::get('admincp/modules/lkpContactTypes/create', ['as'=> 'admincp.modules.lkpContactTypes.create', 'uses' => 'Modules\LkpContactTypeController@create']);
Route::put('admincp/modules/lkpContactTypes/{lkpContactTypes}', ['as'=> 'admincp.modules.lkpContactTypes.update', 'uses' => 'Modules\LkpContactTypeController@update']);
Route::patch('admincp/modules/lkpContactTypes/{lkpContactTypes}', ['as'=> 'admincp.modules.lkpContactTypes.update', 'uses' => 'Modules\LkpContactTypeController@update']);
Route::delete('admincp/modules/lkpContactTypes/{lkpContactTypes}', ['as'=> 'admincp.modules.lkpContactTypes.destroy', 'uses' => 'Modules\LkpContactTypeController@destroy']);
Route::get('admincp/modules/lkpContactTypes/{lkpContactTypes}', ['as'=> 'admincp.modules.lkpContactTypes.show', 'uses' => 'Modules\LkpContactTypeController@show']);
Route::get('admincp/modules/lkpContactTypes/{lkpContactTypes}/edit', ['as'=> 'admincp.modules.lkpContactTypes.edit', 'uses' => 'Modules\LkpContactTypeController@edit']);


Route::get('admincp/modules/lkpLeadSources', ['as'=> 'admincp.modules.lkpLeadSources.index', 'uses' => 'Modules\LkpLeadSourceController@index']);
Route::post('admincp/modules/lkpLeadSources', ['as'=> 'admincp.modules.lkpLeadSources.store', 'uses' => 'Modules\LkpLeadSourceController@store']);
Route::get('admincp/modules/lkpLeadSources/create', ['as'=> 'admincp.modules.lkpLeadSources.create', 'uses' => 'Modules\LkpLeadSourceController@create']);
Route::put('admincp/modules/lkpLeadSources/{lkpLeadSources}', ['as'=> 'admincp.modules.lkpLeadSources.update', 'uses' => 'Modules\LkpLeadSourceController@update']);
Route::patch('admincp/modules/lkpLeadSources/{lkpLeadSources}', ['as'=> 'admincp.modules.lkpLeadSources.update', 'uses' => 'Modules\LkpLeadSourceController@update']);
Route::delete('admincp/modules/lkpLeadSources/{lkpLeadSources}', ['as'=> 'admincp.modules.lkpLeadSources.destroy', 'uses' => 'Modules\LkpLeadSourceController@destroy']);
Route::get('admincp/modules/lkpLeadSources/{lkpLeadSources}', ['as'=> 'admincp.modules.lkpLeadSources.show', 'uses' => 'Modules\LkpLeadSourceController@show']);
Route::get('admincp/modules/lkpLeadSources/{lkpLeadSources}/edit', ['as'=> 'admincp.modules.lkpLeadSources.edit', 'uses' => 'Modules\LkpLeadSourceController@edit']);


Route::get('admincp/modules/lkpLeadStatuses', ['as'=> 'admincp.modules.lkpLeadStatuses.index', 'uses' => 'Modules\LkpLeadStatusController@index']);
Route::post('admincp/modules/lkpLeadStatuses', ['as'=> 'admincp.modules.lkpLeadStatuses.store', 'uses' => 'Modules\LkpLeadStatusController@store']);
Route::get('admincp/modules/lkpLeadStatuses/create', ['as'=> 'admincp.modules.lkpLeadStatuses.create', 'uses' => 'Modules\LkpLeadStatusController@create']);
Route::put('admincp/modules/lkpLeadStatuses/{lkpLeadStatuses}', ['as'=> 'admincp.modules.lkpLeadStatuses.update', 'uses' => 'Modules\LkpLeadStatusController@update']);
Route::patch('admincp/modules/lkpLeadStatuses/{lkpLeadStatuses}', ['as'=> 'admincp.modules.lkpLeadStatuses.update', 'uses' => 'Modules\LkpLeadStatusController@update']);
Route::delete('admincp/modules/lkpLeadStatuses/{lkpLeadStatuses}', ['as'=> 'admincp.modules.lkpLeadStatuses.destroy', 'uses' => 'Modules\LkpLeadStatusController@destroy']);
Route::get('admincp/modules/lkpLeadStatuses/{lkpLeadStatuses}', ['as'=> 'admincp.modules.lkpLeadStatuses.show', 'uses' => 'Modules\LkpLeadStatusController@show']);
Route::get('admincp/modules/lkpLeadStatuses/{lkpLeadStatuses}/edit', ['as'=> 'admincp.modules.lkpLeadStatuses.edit', 'uses' => 'Modules\LkpLeadStatusController@edit']);


Route::get('admincp/modules/lkpOpportunityStages', ['as'=> 'admincp.modules.lkpOpportunityStages.index', 'uses' => 'Modules\LkpOpportunityStageController@index']);
Route::post('admincp/modules/lkpOpportunityStages', ['as'=> 'admincp.modules.lkpOpportunityStages.store', 'uses' => 'Modules\LkpOpportunityStageController@store']);
Route::get('admincp/modules/lkpOpportunityStages/create', ['as'=> 'admincp.modules.lkpOpportunityStages.create', 'uses' => 'Modules\LkpOpportunityStageController@create']);
Route::put('admincp/modules/lkpOpportunityStages/{lkpOpportunityStages}', ['as'=> 'admincp.modules.lkpOpportunityStages.update', 'uses' => 'Modules\LkpOpportunityStageController@update']);
Route::patch('admincp/modules/lkpOpportunityStages/{lkpOpportunityStages}', ['as'=> 'admincp.modules.lkpOpportunityStages.update', 'uses' => 'Modules\LkpOpportunityStageController@update']);
Route::delete('admincp/modules/lkpOpportunityStages/{lkpOpportunityStages}', ['as'=> 'admincp.modules.lkpOpportunityStages.destroy', 'uses' => 'Modules\LkpOpportunityStageController@destroy']);
Route::get('admincp/modules/lkpOpportunityStages/{lkpOpportunityStages}', ['as'=> 'admincp.modules.lkpOpportunityStages.show', 'uses' => 'Modules\LkpOpportunityStageController@show']);
Route::get('admincp/modules/lkpOpportunityStages/{lkpOpportunityStages}/edit', ['as'=> 'admincp.modules.lkpOpportunityStages.edit', 'uses' => 'Modules\LkpOpportunityStageController@edit']);


Route::get('admincp/modules/lkpOpportunityTypes', ['as'=> 'admincp.modules.lkpOpportunityTypes.index', 'uses' => 'Modules\LkpOpportunityTypeController@index']);
Route::post('admincp/modules/lkpOpportunityTypes', ['as'=> 'admincp.modules.lkpOpportunityTypes.store', 'uses' => 'Modules\LkpOpportunityTypeController@store']);
Route::get('admincp/modules/lkpOpportunityTypes/create', ['as'=> 'admincp.modules.lkpOpportunityTypes.create', 'uses' => 'Modules\LkpOpportunityTypeController@create']);
Route::put('admincp/modules/lkpOpportunityTypes/{lkpOpportunityTypes}', ['as'=> 'admincp.modules.lkpOpportunityTypes.update', 'uses' => 'Modules\LkpOpportunityTypeController@update']);
Route::patch('admincp/modules/lkpOpportunityTypes/{lkpOpportunityTypes}', ['as'=> 'admincp.modules.lkpOpportunityTypes.update', 'uses' => 'Modules\LkpOpportunityTypeController@update']);
Route::delete('admincp/modules/lkpOpportunityTypes/{lkpOpportunityTypes}', ['as'=> 'admincp.modules.lkpOpportunityTypes.destroy', 'uses' => 'Modules\LkpOpportunityTypeController@destroy']);
Route::get('admincp/modules/lkpOpportunityTypes/{lkpOpportunityTypes}', ['as'=> 'admincp.modules.lkpOpportunityTypes.show', 'uses' => 'Modules\LkpOpportunityTypeController@show']);
Route::get('admincp/modules/lkpOpportunityTypes/{lkpOpportunityTypes}/edit', ['as'=> 'admincp.modules.lkpOpportunityTypes.edit', 'uses' => 'Modules\LkpOpportunityTypeController@edit']);


Route::get('admincp/modules/lkpSalutations', ['as'=> 'admincp.modules.lkpSalutations.index', 'uses' => 'Modules\LkpSalutationController@index']);
Route::post('admincp/modules/lkpSalutations', ['as'=> 'admincp.modules.lkpSalutations.store', 'uses' => 'Modules\LkpSalutationController@store']);
Route::get('admincp/modules/lkpSalutations/create', ['as'=> 'admincp.modules.lkpSalutations.create', 'uses' => 'Modules\LkpSalutationController@create']);
Route::put('admincp/modules/lkpSalutations/{lkpSalutations}', ['as'=> 'admincp.modules.lkpSalutations.update', 'uses' => 'Modules\LkpSalutationController@update']);
Route::patch('admincp/modules/lkpSalutations/{lkpSalutations}', ['as'=> 'admincp.modules.lkpSalutations.update', 'uses' => 'Modules\LkpSalutationController@update']);
Route::delete('admincp/modules/lkpSalutations/{lkpSalutations}', ['as'=> 'admincp.modules.lkpSalutations.destroy', 'uses' => 'Modules\LkpSalutationController@destroy']);
Route::get('admincp/modules/lkpSalutations/{lkpSalutations}', ['as'=> 'admincp.modules.lkpSalutations.show', 'uses' => 'Modules\LkpSalutationController@show']);
Route::get('admincp/modules/lkpSalutations/{lkpSalutations}/edit', ['as'=> 'admincp.modules.lkpSalutations.edit', 'uses' => 'Modules\LkpSalutationController@edit']);


Route::get('admincp/modules/opportunities', ['as'=> 'admincp.modules.opportunities.index', 'uses' => 'Modules\OpportunityController@index']);
Route::post('admincp/modules/opportunities', ['as'=> 'admincp.modules.opportunities.store', 'uses' => 'Modules\OpportunityController@store']);
Route::get('admincp/modules/opportunities/create', ['as'=> 'admincp.modules.opportunities.create', 'uses' => 'Modules\OpportunityController@create']);
Route::put('admincp/modules/opportunities/{opportunities}', ['as'=> 'admincp.modules.opportunities.update', 'uses' => 'Modules\OpportunityController@update']);
Route::patch('admincp/modules/opportunities/{opportunities}', ['as'=> 'admincp.modules.opportunities.update', 'uses' => 'Modules\OpportunityController@update']);
Route::delete('admincp/modules/opportunities/{opportunities}', ['as'=> 'admincp.modules.opportunities.destroy', 'uses' => 'Modules\OpportunityController@destroy']);
Route::get('admincp/modules/opportunities/{opportunities}', ['as'=> 'admincp.modules.opportunities.show', 'uses' => 'Modules\OpportunityController@show']);
Route::get('admincp/modules/opportunities/{opportunities}/edit', ['as'=> 'admincp.modules.opportunities.edit', 'uses' => 'Modules\OpportunityController@edit']);


Route::get('admincp/modules/projects', ['as'=> 'admincp.modules.projects.index', 'uses' => 'Modules\ProjectController@index']);
Route::post('admincp/modules/projects', ['as'=> 'admincp.modules.projects.store', 'uses' => 'Modules\ProjectController@store']);
Route::get('admincp/modules/projects/create', ['as'=> 'admincp.modules.projects.create', 'uses' => 'Modules\ProjectController@create']);
Route::put('admincp/modules/projects/{projects}', ['as'=> 'admincp.modules.projects.update', 'uses' => 'Modules\ProjectController@update']);
Route::patch('admincp/modules/projects/{projects}', ['as'=> 'admincp.modules.projects.update', 'uses' => 'Modules\ProjectController@update']);
Route::delete('admincp/modules/projects/{projects}', ['as'=> 'admincp.modules.projects.destroy', 'uses' => 'Modules\ProjectController@destroy']);
Route::get('admincp/modules/projects/{projects}', ['as'=> 'admincp.modules.projects.show', 'uses' => 'Modules\ProjectController@show']);
Route::get('admincp/modules/projects/{projects}/edit', ['as'=> 'admincp.modules.projects.edit', 'uses' => 'Modules\ProjectController@edit']);


Route::get('admincp/modules/relations', ['as'=> 'admincp.modules.relations.index', 'uses' => 'Modules\RelationController@index']);
Route::post('admincp/modules/relations', ['as'=> 'admincp.modules.relations.store', 'uses' => 'Modules\RelationController@store']);
Route::get('admincp/modules/relations/create', ['as'=> 'admincp.modules.relations.create', 'uses' => 'Modules\RelationController@create']);
Route::put('admincp/modules/relations/{relations}', ['as'=> 'admincp.modules.relations.update', 'uses' => 'Modules\RelationController@update']);
Route::patch('admincp/modules/relations/{relations}', ['as'=> 'admincp.modules.relations.update', 'uses' => 'Modules\RelationController@update']);
Route::delete('admincp/modules/relations/{relations}', ['as'=> 'admincp.modules.relations.destroy', 'uses' => 'Modules\RelationController@destroy']);
Route::get('admincp/modules/relations/{relations}', ['as'=> 'admincp.modules.relations.show', 'uses' => 'Modules\RelationController@show']);
Route::get('admincp/modules/relations/{relations}/edit', ['as'=> 'admincp.modules.relations.edit', 'uses' => 'Modules\RelationController@edit']);


Route::get('admincp/modules/tickets', ['as'=> 'admincp.modules.tickets.index', 'uses' => 'Modules\TicketController@index']);
Route::post('admincp/modules/tickets', ['as'=> 'admincp.modules.tickets.store', 'uses' => 'Modules\TicketController@store']);
Route::get('admincp/modules/tickets/create', ['as'=> 'admincp.modules.tickets.create', 'uses' => 'Modules\TicketController@create']);
Route::put('admincp/modules/tickets/{tickets}', ['as'=> 'admincp.modules.tickets.update', 'uses' => 'Modules\TicketController@update']);
Route::patch('admincp/modules/tickets/{tickets}', ['as'=> 'admincp.modules.tickets.update', 'uses' => 'Modules\TicketController@update']);
Route::delete('admincp/modules/tickets/{tickets}', ['as'=> 'admincp.modules.tickets.destroy', 'uses' => 'Modules\TicketController@destroy']);
Route::get('admincp/modules/tickets/{tickets}', ['as'=> 'admincp.modules.tickets.show', 'uses' => 'Modules\TicketController@show']);
Route::get('admincp/modules/tickets/{tickets}/edit', ['as'=> 'admincp.modules.tickets.edit', 'uses' => 'Modules\TicketController@edit']);


Route::get('admincp/modules/ticketPriorities', ['as'=> 'admincp.modules.ticketPriorities.index', 'uses' => 'Modules\TicketPriorityController@index']);
Route::post('admincp/modules/ticketPriorities', ['as'=> 'admincp.modules.ticketPriorities.store', 'uses' => 'Modules\TicketPriorityController@store']);
Route::get('admincp/modules/ticketPriorities/create', ['as'=> 'admincp.modules.ticketPriorities.create', 'uses' => 'Modules\TicketPriorityController@create']);
Route::put('admincp/modules/ticketPriorities/{ticketPriorities}', ['as'=> 'admincp.modules.ticketPriorities.update', 'uses' => 'Modules\TicketPriorityController@update']);
Route::patch('admincp/modules/ticketPriorities/{ticketPriorities}', ['as'=> 'admincp.modules.ticketPriorities.update', 'uses' => 'Modules\TicketPriorityController@update']);
Route::delete('admincp/modules/ticketPriorities/{ticketPriorities}', ['as'=> 'admincp.modules.ticketPriorities.destroy', 'uses' => 'Modules\TicketPriorityController@destroy']);
Route::get('admincp/modules/ticketPriorities/{ticketPriorities}', ['as'=> 'admincp.modules.ticketPriorities.show', 'uses' => 'Modules\TicketPriorityController@show']);
Route::get('admincp/modules/ticketPriorities/{ticketPriorities}/edit', ['as'=> 'admincp.modules.ticketPriorities.edit', 'uses' => 'Modules\TicketPriorityController@edit']);


Route::get('admincp/modules/ticketSources', ['as'=> 'admincp.modules.ticketSources.index', 'uses' => 'Modules\TicketSourceController@index']);
Route::post('admincp/modules/ticketSources', ['as'=> 'admincp.modules.ticketSources.store', 'uses' => 'Modules\TicketSourceController@store']);
Route::get('admincp/modules/ticketSources/create', ['as'=> 'admincp.modules.ticketSources.create', 'uses' => 'Modules\TicketSourceController@create']);
Route::put('admincp/modules/ticketSources/{ticketSources}', ['as'=> 'admincp.modules.ticketSources.update', 'uses' => 'Modules\TicketSourceController@update']);
Route::patch('admincp/modules/ticketSources/{ticketSources}', ['as'=> 'admincp.modules.ticketSources.update', 'uses' => 'Modules\TicketSourceController@update']);
Route::delete('admincp/modules/ticketSources/{ticketSources}', ['as'=> 'admincp.modules.ticketSources.destroy', 'uses' => 'Modules\TicketSourceController@destroy']);
Route::get('admincp/modules/ticketSources/{ticketSources}', ['as'=> 'admincp.modules.ticketSources.show', 'uses' => 'Modules\TicketSourceController@show']);
Route::get('admincp/modules/ticketSources/{ticketSources}/edit', ['as'=> 'admincp.modules.ticketSources.edit', 'uses' => 'Modules\TicketSourceController@edit']);


Route::get('admincp/modules/ticketStatuses', ['as'=> 'admincp.modules.ticketStatuses.index', 'uses' => 'Modules\TicketStatusController@index']);
Route::post('admincp/modules/ticketStatuses', ['as'=> 'admincp.modules.ticketStatuses.store', 'uses' => 'Modules\TicketStatusController@store']);
Route::get('admincp/modules/ticketStatuses/create', ['as'=> 'admincp.modules.ticketStatuses.create', 'uses' => 'Modules\TicketStatusController@create']);
Route::put('admincp/modules/ticketStatuses/{ticketStatuses}', ['as'=> 'admincp.modules.ticketStatuses.update', 'uses' => 'Modules\TicketStatusController@update']);
Route::patch('admincp/modules/ticketStatuses/{ticketStatuses}', ['as'=> 'admincp.modules.ticketStatuses.update', 'uses' => 'Modules\TicketStatusController@update']);
Route::delete('admincp/modules/ticketStatuses/{ticketStatuses}', ['as'=> 'admincp.modules.ticketStatuses.destroy', 'uses' => 'Modules\TicketStatusController@destroy']);
Route::get('admincp/modules/ticketStatuses/{ticketStatuses}', ['as'=> 'admincp.modules.ticketStatuses.show', 'uses' => 'Modules\TicketStatusController@show']);
Route::get('admincp/modules/ticketStatuses/{ticketStatuses}/edit', ['as'=> 'admincp.modules.ticketStatuses.edit', 'uses' => 'Modules\TicketStatusController@edit']);


Route::get('admincp/modules/ticketThreads', ['as'=> 'admincp.modules.ticketThreads.index', 'uses' => 'Modules\TicketThreadController@index']);
Route::post('admincp/modules/ticketThreads', ['as'=> 'admincp.modules.ticketThreads.store', 'uses' => 'Modules\TicketThreadController@store']);
Route::get('admincp/modules/ticketThreads/create', ['as'=> 'admincp.modules.ticketThreads.create', 'uses' => 'Modules\TicketThreadController@create']);
Route::put('admincp/modules/ticketThreads/{ticketThreads}', ['as'=> 'admincp.modules.ticketThreads.update', 'uses' => 'Modules\TicketThreadController@update']);
Route::patch('admincp/modules/ticketThreads/{ticketThreads}', ['as'=> 'admincp.modules.ticketThreads.update', 'uses' => 'Modules\TicketThreadController@update']);
Route::delete('admincp/modules/ticketThreads/{ticketThreads}', ['as'=> 'admincp.modules.ticketThreads.destroy', 'uses' => 'Modules\TicketThreadController@destroy']);
Route::get('admincp/modules/ticketThreads/{ticketThreads}', ['as'=> 'admincp.modules.ticketThreads.show', 'uses' => 'Modules\TicketThreadController@show']);
Route::get('admincp/modules/ticketThreads/{ticketThreads}/edit', ['as'=> 'admincp.modules.ticketThreads.edit', 'uses' => 'Modules\TicketThreadController@edit']);


Route::get('admincp/modules/ticketTimes', ['as'=> 'admincp.modules.ticketTimes.index', 'uses' => 'Modules\TicketTimeController@index']);
Route::post('admincp/modules/ticketTimes', ['as'=> 'admincp.modules.ticketTimes.store', 'uses' => 'Modules\TicketTimeController@store']);
Route::get('admincp/modules/ticketTimes/create', ['as'=> 'admincp.modules.ticketTimes.create', 'uses' => 'Modules\TicketTimeController@create']);
Route::put('admincp/modules/ticketTimes/{ticketTimes}', ['as'=> 'admincp.modules.ticketTimes.update', 'uses' => 'Modules\TicketTimeController@update']);
Route::patch('admincp/modules/ticketTimes/{ticketTimes}', ['as'=> 'admincp.modules.ticketTimes.update', 'uses' => 'Modules\TicketTimeController@update']);
Route::delete('admincp/modules/ticketTimes/{ticketTimes}', ['as'=> 'admincp.modules.ticketTimes.destroy', 'uses' => 'Modules\TicketTimeController@destroy']);
Route::get('admincp/modules/ticketTimes/{ticketTimes}', ['as'=> 'admincp.modules.ticketTimes.show', 'uses' => 'Modules\TicketTimeController@show']);
Route::get('admincp/modules/ticketTimes/{ticketTimes}/edit', ['as'=> 'admincp.modules.ticketTimes.edit', 'uses' => 'Modules\TicketTimeController@edit']);


Route::get('admincp/modules/invoices', ['as'=> 'admincp.modules.invoices.index', 'uses' => 'Modules\InvoiceController@index']);
Route::post('admincp/modules/invoices', ['as'=> 'admincp.modules.invoices.store', 'uses' => 'Modules\InvoiceController@store']);
Route::get('admincp/modules/invoices/create', ['as'=> 'admincp.modules.invoices.create', 'uses' => 'Modules\InvoiceController@create']);
Route::put('admincp/modules/invoices/{invoices}', ['as'=> 'admincp.modules.invoices.update', 'uses' => 'Modules\InvoiceController@update']);
Route::patch('admincp/modules/invoices/{invoices}', ['as'=> 'admincp.modules.invoices.update', 'uses' => 'Modules\InvoiceController@update']);
Route::delete('admincp/modules/invoices/{invoices}', ['as'=> 'admincp.modules.invoices.destroy', 'uses' => 'Modules\InvoiceController@destroy']);
Route::get('admincp/modules/invoices/{invoices}', ['as'=> 'admincp.modules.invoices.show', 'uses' => 'Modules\InvoiceController@show']);
Route::get('admincp/modules/invoices/{invoices}/edit', ['as'=> 'admincp.modules.invoices.edit', 'uses' => 'Modules\InvoiceController@edit']);


Route::get('admincp/modules/invoiceTicketTimes', ['as'=> 'admincp.modules.invoiceTicketTimes.index', 'uses' => 'Modules\InvoiceTicketTimeController@index']);
Route::post('admincp/modules/invoiceTicketTimes', ['as'=> 'admincp.modules.invoiceTicketTimes.store', 'uses' => 'Modules\InvoiceTicketTimeController@store']);
Route::get('admincp/modules/invoiceTicketTimes/create', ['as'=> 'admincp.modules.invoiceTicketTimes.create', 'uses' => 'Modules\InvoiceTicketTimeController@create']);
Route::put('admincp/modules/invoiceTicketTimes/{invoiceTicketTimes}', ['as'=> 'admincp.modules.invoiceTicketTimes.update', 'uses' => 'Modules\InvoiceTicketTimeController@update']);
Route::patch('admincp/modules/invoiceTicketTimes/{invoiceTicketTimes}', ['as'=> 'admincp.modules.invoiceTicketTimes.update', 'uses' => 'Modules\InvoiceTicketTimeController@update']);
Route::delete('admincp/modules/invoiceTicketTimes/{invoiceTicketTimes}', ['as'=> 'admincp.modules.invoiceTicketTimes.destroy', 'uses' => 'Modules\InvoiceTicketTimeController@destroy']);
Route::get('admincp/modules/invoiceTicketTimes/{invoiceTicketTimes}', ['as'=> 'admincp.modules.invoiceTicketTimes.show', 'uses' => 'Modules\InvoiceTicketTimeController@show']);
Route::get('admincp/modules/invoiceTicketTimes/{invoiceTicketTimes}/edit', ['as'=> 'admincp.modules.invoiceTicketTimes.edit', 'uses' => 'Modules\InvoiceTicketTimeController@edit']);
