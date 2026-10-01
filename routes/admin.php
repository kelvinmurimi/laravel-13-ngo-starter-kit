<?php


use Illuminate\Support\Facades\Route;

/// Admin Routes categories
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::livewire('categories', 'category.index')->name('admin.categories.index');
    Route::livewire('categories/create', 'category.create')->name('admin.categories.create');
    Route::livewire('categories/{category}', 'category.edit')->name('admin.categories.edit');
});
/// Admin Routes blog
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::livewire('blog', 'blog.index')->name('admin.blogs.index');
    Route::livewire('blog/create', 'blog.create')->name('admin.blogs.create');
    Route::livewire('blog/{blog}', 'blog.edit')->name('admin.blogs.edit');
});

/// Admin Routes partners
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::livewire('partners', 'partners.index')->name('admin.partners.index');
    Route::livewire('partners/create', 'partners.create')->name('admin.partners.create');
    Route::livewire('partners/{partner}', 'partners.edit')->name('admin.partners.edit');
});
/// Admin Routes programs
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::livewire('programs', 'programs.index')->name('admin.programs.index');
    Route::livewire('programs/create', 'programs.create')->name('admin.programs.create');
    Route::livewire('programs/{program}', 'programs.edit')->name('admin.programs.edit');
});
/* admin routes for tags */
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::livewire('tags', 'tags.index')->name('admin.tags.index');
    Route::livewire('tags/create', 'tags.create')->name('admin.tags.create');
    Route::livewire('tags/{tag}', 'tags.edit')->name('admin.tags.edit');
});

/* admin routes for Events */
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::livewire('events', 'events.index')->name('admin.events.index');
    Route::livewire('events/create', 'events.create')->name('admin.events.create');
    Route::livewire('events/{event}', 'events.edit')->name('admin.events.edit');
});

/* admin routes for  publications  */
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::livewire('publications', 'publications.index')->name('admin.publications.index');
    Route::livewire('publications/create', 'publications.create')->name('admin.publications.create');
    Route::livewire('publications/{publication}', 'publications.edit')->name('admin.publications.edit');
});

/* admin routes for staff */
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::livewire('staff', 'staff.index')->name('admin.staff.index');
    Route::livewire('staff/create', 'staff.create')->name('admin.staff.create');
    Route::livewire('staff/{staff}', 'staff.edit')->name('admin.staff.edit');
});

/* admin routes for testmonials */
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::livewire('testmonials', 'testmonials.index')->name('admin.testmonials.index');
    Route::livewire('testmonials/create', 'testmonials.create')->name('admin.testmonials.create');
    Route::livewire('testmonials/{testmonial}', 'testmonials.edit')->name('admin.testmonials.edit');
});

/* admin routes for leave requests (access enforced by LeaveRequestPolicy) */
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::get('leaves', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'index'])->name('leaves.index');
    Route::get('leaves/{leave}', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'show'])->name('leaves.show');
    Route::patch('leaves/{leave}/approve', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'approve'])->name('leaves.approve');
    Route::patch('leaves/{leave}/reject', [\App\Http\Controllers\Admin\LeaveRequestController::class, 'reject'])->name('leaves.reject');
});

/* admin routes for volunteer management */
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::livewire('volunteers', 'volunteers.index')->name('admin.volunteers.index');
    Route::livewire('volunteers/hours', 'volunteers.hours')->name('admin.volunteers.hours');
});
