<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::view('/', 'site.home')->name('home');
Route::view('/about', 'site.about')->name('about');
Route::view('/academics', 'site.academics')->name('academics');
Route::view('/facilities', 'site.facilities')->name('facilities');
Route::view('/news', 'site.news')->name('news');
Route::view('/contact', 'site.contact')->name('contact');

Volt::route('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware('auth')->prefix('cms')->name('cms.')->group(function () {
    Volt::route('/', 'pages.cms.dashboard')->name('dashboard');


    Volt::route('/settings', 'pages.cms.settings')->name('settings');
    Volt::route('/pages', 'pages.cms.pages.index')->name('pages.index');
    Volt::route('/pages/{page}/edit', 'pages.cms.pages.edit')->name('pages.edit');
    Volt::route('/announcements', 'pages.cms.announcements.index')->name('announcements.index');
    Volt::route('/announcements/create', 'pages.cms.announcements.form')->name('announcements.create');
    Volt::route('/announcements/{announcement}/edit', 'pages.cms.announcements.form')->name('announcements.edit');
    Volt::route('/programs', 'pages.cms.programs.index')->name('programs.index');
    Volt::route('/programs/create', 'pages.cms.programs.form')->name('programs.create');
    Volt::route('/programs/{program}/edit', 'pages.cms.programs.form')->name('programs.edit');
    Volt::route('/facilities', 'pages.cms.facilities.index')->name('facilities.index');
    Volt::route('/facilities/create', 'pages.cms.facilities.form')->name('facilities.create');
    Volt::route('/facilities/{facility}/edit', 'pages.cms.facilities.form')->name('facilities.edit');
    Volt::route('/gallery', 'pages.cms.gallery.index')->name('gallery.index');
    Volt::route('/gallery/create', 'pages.cms.gallery.form')->name('gallery.create');
    Volt::route('/gallery/{galleryImage}/edit', 'pages.cms.gallery.form')->name('gallery.edit');
    Volt::route('/principles', 'pages.cms.principles.index')->name('principles.index');
    Volt::route('/principles/create', 'pages.cms.principles.form')->name('principles.create');
    Volt::route('/principles/{principle}/edit', 'pages.cms.principles.form')->name('principles.edit');
    Volt::route('/menus', 'pages.cms.menus.index')->name('menus');
    Volt::route('/media', 'pages.cms.media.index')->name('media');
    Volt::route('/submissions', 'pages.cms.submissions.index')->name('submissions.index');
    Volt::route('/submissions/{submission}', 'pages.cms.submissions.show')->name('submissions.show');
});

require __DIR__.'/auth.php';
