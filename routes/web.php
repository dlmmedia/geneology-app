<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

// -----------------------------------------------------------------------------------
// Landing page route (public)
// -----------------------------------------------------------------------------------
Route::get('/', [App\Http\Controllers\Front\PageController::class, 'landing'])->name('landing');

// -----------------------------------------------------------------------------------
// Launch app route - auto-login and redirect to app
// -----------------------------------------------------------------------------------
Route::post('/launch', function () {
    // Auto-login as developer
    $developer = \App\Models\User::where('email', 'developer@genealogy.test')
        ->where('is_developer', true)
        ->first();

    if ($developer) {
        auth()->login($developer, true);
        return redirect()->route('people.search');
    }

    return redirect()->route('landing')->with('error', 'Developer account not found.');
})->name('launch');

// -----------------------------------------------------------------------------------
// Logout override - redirect to landing page
// -----------------------------------------------------------------------------------
Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('landing');
})->name('logout');

// -----------------------------------------------------------------------------------
// App routes - require authentication with auto-login fallback
// -----------------------------------------------------------------------------------
Route::middleware([
    App\Http\Middleware\AutoLoginDeveloper::class,
    'auth:sanctum',
    config('jetstream.auth_session'),
    // 'verified', // Removed - using developer auto-login
])->group(function (): void {
    // App home (redirects from old home)
    Route::controller(App\Http\Controllers\Front\PageController::class)->group(function (): void {
        Route::get('/home', 'home')->name('home');
        Route::get('password-generator', 'passwordGenerator')->name('password.generator');
        Route::get('about', 'about')->name('about');
        Route::get('help', 'help')->name('help');
    });
    // -----------------------------------------------------------------------------------
    // teams
    // -----------------------------------------------------------------------------------
    Route::controller(App\Http\Controllers\Back\TeamController::class)->group(function (): void {
        Route::get('team', 'team')->name('team');
        Route::get('teamlog', 'teamlog')->name('teamlog');
        Route::get('peoplelog', 'peoplelog')->name('peoplelog');

        Route::put('/teams/{team}/transfer-ownership', 'transferOwnership')->name('teams.transfer-ownership');
    });

    // -----------------------------------------------------------------------------------
    // pages
    // -----------------------------------------------------------------------------------
    Route::controller(App\Http\Controllers\Back\PageController::class)->group(function (): void {
        Route::get('test', 'test')->name('test');
    });

    // -----------------------------------------------------------------------------------
    // people
    // -----------------------------------------------------------------------------------
    Route::controller(App\Http\Controllers\Back\PeopleController::class)->group(function (): void {
        Route::get('search', 'search')->name('people.search');
        Route::get('birthdays', 'birthdays')->name('people.birthdays');

        Route::get('people/add', 'add')->name('people.add');
        Route::get('people/{person}', 'show')->name('people.show');
        Route::get('people/{person}/ancestors', 'ancestors')->name('people.ancestors');
        Route::get('people/{person}/descendants', 'descendants')->name('people.descendants');
        Route::get('people/{person}/chart', 'chart')->name('people.chart');
        Route::get('people/{person}/history', 'history')->name('people.history');
        Route::get('people/{person}/datasheet', 'datasheet')->name('people.datasheet');
        Route::get('people/{person}/add-father', 'addFather')->name('people.add-father');
        Route::get('people/{person}/add-mother', 'addMother')->name('people.add-mother');
        Route::get('people/{person}/add-child', 'addChild')->name('people.add-child');
        Route::get('people/{person}/add-partner', 'addPartner')->name('people.add-partner');
        Route::get('people/{person}/edit-contact', 'editContact')->name('people.edit-contact');
        Route::get('people/{person}/edit-death', 'editDeath')->name('people.edit-death');
        Route::get('people/{person}/edit-family', 'editFamily')->name('people.edit-family');
        Route::get('people/{person}/edit-files', 'editFiles')->name('people.edit-files');
        Route::get('people/{person}/edit-photos', 'editPhotos')->name('people.edit-photos');
        Route::get('people/{person}/edit-profile', 'editProfile')->name('people.edit-profile');
        Route::get('people/{person}/{couple}/edit-partner', 'editPartner')->name('people.edit-partner');
    });

    // -----------------------------------------------------------------------------------
    // gedcom
    // -----------------------------------------------------------------------------------
    Route::controller(App\Http\Controllers\Back\GedcomController::class)->prefix('gedcom')->as('gedcom.')->group(function (): void {
        Route::get('exportteam', 'exportteam')->name('exportteam');
        Route::get('importteam', 'importteam')->name('importteam');
    });

    // -----------------------------------------------------------------------------------
    // developer
    // -----------------------------------------------------------------------------------
    Route::middleware(App\Http\Middleware\IsDeveloper::class)->prefix('developer')->as('developer.')->group(function (): void {
        // -----------------------------------------------------------------------------------
        // pages
        // -----------------------------------------------------------------------------------
        Route::controller(App\Http\Controllers\Back\DeveloperController::class)->group(function (): void {
            Route::get('settings', 'settings')->name('settings');

            Route::get('teams', 'teams')->name('teams');
            Route::get('people', 'people')->name('people');
            Route::get('users', 'users')->name('users');

            Route::get('dependencies', 'dependencies')->name('dependencies');
            Route::get('session', 'session')->name('session');

            Route::get('userlog/log', 'userlogLog')->name('userlog.log');
            Route::get('userlog/origin', 'userlogOrigin')->name('userlog.origin');
            Route::get('userlog/originmap', 'userlogOriginMap')->name('userlog.origin-map');
            Route::get('userlog/period', 'userlogPeriod')->name('userlog.period');
        });

        // -----------------------------------------------------------------------------------
        // backups
        // -----------------------------------------------------------------------------------
        Route::get('backups', App\Livewire\Backups\Manage::class)->name('backups');
    });
});

// -----------------------------------------------------------------------------------
// set application language in session
// actual language switching wil be handled by App\Http\Middleware\Localization::class
// -----------------------------------------------------------------------------------
Route::get('language/{locale}', function ($locale) {
    session()->put('locale', $locale);

    return back();
});
