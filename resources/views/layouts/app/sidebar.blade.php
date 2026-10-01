<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <livewire:team-switcher />

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <!-- Dashboard link -->
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                    {{-- categories link --}}
                    <flux:sidebar.item icon="home" :href="route('admin.categories.index')" :current="request()->routeIs('admin.categories.index')" wire:navigate>
                        {{ __('Categories') }}
                    </flux:sidebar.item>
                    {{-- blogs link--}}
                    <flux:sidebar.item icon="book-open" :href="route('admin.blogs.index')" :current="request()->routeIs('admin.categories.index')" wire:navigate>
                        {{ __('Blogs') }}
                    </flux:sidebar.item>
                    {{-- partners link --}}
                    <flux:sidebar.item icon="user-plus" :href="route('admin.partners.index')" :current="request()->routeIs('admin.partners.index')" wire:navigate>
                        {{ __('Partners') }}
                    </flux:sidebar.item>
                    {{-- programs link--}}
                    <flux:sidebar.item icon="play" :href="route('admin.programs.index')" :current="request()->routeIs('admin.programs.index')" wire:navigate>
                        {{ __('Programs') }}
                    </flux:sidebar.item>
                    {{-- tags link--}}
                    <flux:sidebar.item icon="tag" :href="route('admin.tags.index')" :current="request()->routeIs('admin.tags.index')" wire:navigate>
                        {{ __('Tags') }}
                    </flux:sidebar.item>

                    {{-- events link--}}
                    <flux:sidebar.item icon="calendar" :href="route('admin.events.index')" :current="request()->routeIs('admin.events.index')" wire:navigate>
                        {{ __('Events') }}
                    </flux:sidebar.item>    
                    {{-- publications link--}}
                    <flux:sidebar.item icon="book-open" :href="route('admin.publications.index')" :current="request()->routeIs('admin.publications.index')" wire:navigate>
                        {{ __('Publications') }}
                    </flux:sidebar.item>  
                    {{-- staff link--}}
                    <flux:sidebar.item icon="users" :href="route('admin.staff.index')" :current="request()->routeIs('admin.staff.index')" wire:navigate>
                        {{ __('Staff') }}
                    </flux:sidebar.item>  
                    {{-- testimonials link--}}
                    <flux:sidebar.item icon="chat-bubble-left" :href="route('admin.testmonials.index')" :current="request()->routeIs('admin.testimonials.index')" wire:navigate>
                        {{ __('Testimonials') }}
                    </flux:sidebar.item>
                    

                    <!-- Leave management links -->
                    @if (auth()->user()->isVolunteer())
                        <flux:sidebar.item icon="calendar-days" :href="route('volunteer.leaves.index')" :current="request()->routeIs('volunteer.leaves.*')" wire:navigate>
                            {{ __('My Leave') }}
                        </flux:sidebar.item>
                    @endif

                    @if (auth()->user()->isStaffMember())
                        <flux:sidebar.item icon="clipboard-document-check" :href="route('admin.leaves.index')" :current="request()->routeIs('admin.leaves.*')" wire:navigate>
                            {{ __('Leave Requests') }}
                        </flux:sidebar.item>
                    @endif

                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
