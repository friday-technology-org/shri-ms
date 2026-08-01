<aside class="bg-white row-span-2 border-r border-neutral relative flex flex-col justify-between p-[25px] dark:bg-dark-neutral-bg dark:border-dark-neutral-border"> 
    <div class="absolute p-2 border-neutral right-0 border bg-white rounded-full cursor-pointer duration-300 translate-x-1/2 hover:opacity-75 dark:bg-dark-neutral-bg dark:border-dark-neutral-border" id="sidebar-btn"><img src="{{ asset('assets/images/icons/icon-arrow-left.svg') }}" alt="left chevron icon"></div>
    <div><a class="mb-10" href="{{ route('cms.dashboard') }}"> <img class="logo-maximize" src="{{ asset('assets/images/icons/icon-logo.svg') }}" alt="Frox logo"><img class="logo-minimize ml-[10px]" src="{{ asset('assets/images/icons/icon-favicon.svg') }}" alt="Frox logo"></a>
        <div class="pt-[106px] lg:pt-[35px] pb-[18px]">
            
            <!-- Dashboard -->
            <div class="sidemenu-item rounded-xl relative">
                <a href="{{ route('cms.dashboard') }}" class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] hover:bg-gray-50 dark:hover:bg-gray-dark-100 rounded-xl">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-favorite-chart.svg') }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Dashboard</span>
                    </div>
                </a>
            </div>

            @hasrole('Administrator')
            <!-- Users -->
            <div class="sidemenu-item rounded-xl relative">
                <a href="{{ route('cms.users.index') }}" class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] hover:bg-gray-50 dark:hover:bg-gray-dark-100 rounded-xl">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-user-square.svg') }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Users</span>
                    </div>
                </a>
            </div>
            @endhasrole

            @hasanyrole('Administrator|Editor|Contributor|Author')
            <!-- Posts -->
            <div class="sidemenu-item rounded-xl relative">
                <input class="sr-only peer" type="checkbox" value="posts" name="sidemenu" id="posts">
                <label class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] focus:outline-none peer-checked:border-transparent" for="posts">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-cms.svg') }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Posts</span>
                    </div>
                </label><img class="absolute right-2 transition-all duration-150 caret-icon pointer-events-none peer-checked:rotate-180 top-[22px]" src="{{ asset('assets/images/icons/icon-arrow-down.svg') }}" alt="caret icon">
                <div class="hidden peer-checked:block">
                    <ul class="text-gray-300 child-menu z-10 pl-[53px]">
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.posts.index') }}">All Posts</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.posts.create') }}">Add New</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="#">Categories</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="#">Tags</a></li>
                    </ul>
                </div>
            </div>
            @endhasanyrole

            @hasanyrole('Administrator|Editor')
            <!-- Pages -->
            <div class="sidemenu-item rounded-xl relative">
                <input class="sr-only peer" type="checkbox" value="pages" name="sidemenu" id="pages">
                <label class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] focus:outline-none peer-checked:border-transparent" for="pages">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-file.svg') }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Pages</span>
                    </div>
                </label><img class="absolute right-2 transition-all duration-150 caret-icon pointer-events-none peer-checked:rotate-180 top-[22px]" src="{{ asset('assets/images/icons/icon-arrow-down.svg') }}" alt="caret icon">
                <div class="hidden peer-checked:block">
                    <ul class="text-gray-300 child-menu z-10 pl-[53px]">
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.pages.index') }}">All Pages</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.pages.create') }}">Add New</a></li>
                    </ul>
                </div>
            </div>
            @endhasanyrole

            @hasanyrole('Administrator|Editor')
            <!-- Media Library -->
            <div class="sidemenu-item rounded-xl relative">
                <a href="{{ route('cms.media.index') }}" class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] hover:bg-gray-50 dark:hover:bg-gray-dark-100 rounded-xl">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-gallery.svg') }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Media</span>
                    </div>
                </a>
            </div>
            @endhasanyrole

            @hasanyrole('Administrator|Editor')
            <!-- Comments -->
            <div class="sidemenu-item rounded-xl relative">
                <a href="{{ route('cms.comments.index') }}" class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] hover:bg-gray-50 dark:hover:bg-gray-dark-100 rounded-xl">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-messages.svg') }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Comments</span>
                    </div>
                </a>
            </div>
            @endhasanyrole

            @hasrole('Administrator')
            <!-- Plugins -->
            <div class="sidemenu-item rounded-xl relative">
                <a href="{{ route('cms.plugins.index') }}" class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] hover:bg-gray-50 dark:hover:bg-gray-dark-100 rounded-xl">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-element-3.svg') }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Plugins</span>
                    </div>
                </a>
            </div>
            @endhasrole
            @hasanyrole('Administrator|Editor')
            @php
                $cpts = \Cms\Core\Models\PostType::all();
            @endphp
            @foreach($cpts as $cpt)
            <div class="sidemenu-item rounded-xl relative">
                <input class="sr-only peer" type="checkbox" value="{{ $cpt->name }}" name="sidemenu" id="cpt_{{ $cpt->name }}">
                <label class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] focus:outline-none peer-checked:border-transparent" for="cpt_{{ $cpt->name }}">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/' . ($cpt->icon ?: 'icon-file.svg')) }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">{{ $cpt->plural_label }}</span>
                    </div>
                </label><img class="absolute right-2 transition-all duration-150 caret-icon pointer-events-none peer-checked:rotate-180 top-[22px]" src="{{ asset('assets/images/icons/icon-arrow-down.svg') }}" alt="caret icon">
                <div class="hidden peer-checked:block">
                    <ul class="text-gray-300 child-menu z-10 pl-[53px]">
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.content.index', $cpt->name) }}">All {{ $cpt->plural_label }}</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.content.create', $cpt->name) }}">Add New</a></li>
                        @php
                            $cptTaxonomies = \Cms\Core\Models\Taxonomy::whereJsonContains('post_types', $cpt->name)->get();
                        @endphp
                        @foreach($cptTaxonomies as $tax)
                            <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.terms.index', $tax->id) }}">{{ $tax->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
            @endhasanyrole

            @hasrole('Administrator')
            <!-- Appearance -->
            <div class="sidemenu-item rounded-xl relative">
                <input class="sr-only peer" type="checkbox" value="appearance" name="sidemenu" id="appearance">
                <label class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] focus:outline-none peer-checked:border-transparent" for="appearance">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-brush.svg') }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Appearance</span>
                    </div>
                </label><img class="absolute right-2 transition-all duration-150 caret-icon pointer-events-none peer-checked:rotate-180 top-[22px]" src="{{ asset('assets/images/icons/icon-arrow-down.svg') }}" alt="caret icon">
                <div class="hidden peer-checked:block">
                    <ul class="text-gray-300 child-menu z-10 pl-[53px]">
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.themes.index') }}">Themes</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.customizer.edit') }}">Customizer</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.menus.index') }}">Menus</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.widgets.index') }}">Widgets</a></li>
                    </ul>
                </div>
            </div>
            @endhasrole

            @hasrole('Administrator')
            <!-- Settings / Architecture -->
            <div class="sidemenu-item rounded-xl relative">
                <input class="sr-only peer" type="checkbox" value="settings" name="sidemenu" id="settings">
                <label class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] focus:outline-none peer-checked:border-transparent" for="settings">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-setting.svg') }}" alt="side menu icon" onerror="this.src='{{ asset('assets/images/icons/icon-category.svg') }}'">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Architecture</span>
                    </div>
                </label><img class="absolute right-2 transition-all duration-150 caret-icon pointer-events-none peer-checked:rotate-180 top-[22px]" src="{{ asset('assets/images/icons/icon-arrow-down.svg') }}" alt="caret icon">
                <div class="hidden peer-checked:block">
                    <ul class="text-gray-300 child-menu z-10 pl-[53px]">
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.post-types.index') }}">Post Types</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.taxonomies.index') }}">Taxonomies</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.field-groups.index') }}">Custom Fields</a></li>
                    </ul>
                </div>
            </div>
            @endhasrole

            @hasrole('Administrator')
            <!-- Settings -->
            <div class="sidemenu-item rounded-xl relative">
                <input class="sr-only peer" type="checkbox" value="settings_panel" name="sidemenu" id="settings_panel">
                <label class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] focus:outline-none peer-checked:border-transparent" for="settings_panel">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-setting-2.svg') }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Settings</span>
                    </div>
                </label><img class="absolute right-2 transition-all duration-150 caret-icon pointer-events-none peer-checked:rotate-180 top-[22px]" src="{{ asset('assets/images/icons/icon-arrow-down.svg') }}" alt="caret icon">
                <div class="hidden peer-checked:block">
                    <ul class="text-gray-300 child-menu z-10 pl-[53px]">
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.settings.index') }}">General Settings</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.translations.index') }}">Translations</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.network.index') }}">Multisite Network</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.updates.index') }}">Core Updates</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.api-tokens.index') }}">API Tokens</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.settings.logs404') }}">404 Monitor</a></li>
                    </ul>
                </div>
            </div>
            @endhasrole

            @hasrole('Administrator')
            <!-- Tools -->
            <div class="sidemenu-item rounded-xl relative">
                <input class="sr-only peer" type="checkbox" value="tools_panel" name="sidemenu" id="tools_panel">
                <label class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] focus:outline-none peer-checked:border-transparent" for="tools_panel">
                    <div class="flex items-center gap-[10px]">
                        <img src="{{ asset('assets/images/icons/icon-briefcase.svg') }}" alt="side menu icon">
                        <span class="text-normal font-semibold text-gray-500 sidemenu-title dark:text-gray-dark-500">Tools</span>
                    </div>
                </label><img class="absolute right-2 transition-all duration-150 caret-icon pointer-events-none peer-checked:rotate-180 top-[22px]" src="{{ asset('assets/images/icons/icon-arrow-down.svg') }}" alt="caret icon">
                <div class="hidden peer-checked:block">
                    <ul class="text-gray-300 child-menu z-10 pl-[53px]">
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.tools.site-health') }}">Site Health</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.tools.backups.index') }}">Backups</a></li>
                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal" href="{{ route('cms.tools.activity-logs') }}">Activity Log</a></li>
                    </ul>
                </div>
            </div>
            @endhasrole

            @php
                $themeMenus = config('cms.admin_menus', []);
            @endphp
            @if(count($themeMenus) > 0)
                <!-- Theme Pages -->
                @foreach($themeMenus as $menu)
                    @php
                        $isActive = request()->routeIs('cms.admin.theme.' . $menu['slug']) || 
                                    collect($menu['submenus'])->contains(fn($sub) => request()->routeIs('cms.admin.theme.' . $sub['slug']));
                    @endphp
                    @hasrole($menu['role'])
                    <div class="sidemenu-item rounded-xl relative {{ $isActive ? 'bg-color-brands' : '' }}">
                        @if(count($menu['submenus']) > 0)
                            <input class="sr-only peer" type="checkbox" value="{{ $menu['slug'] }}" name="sidemenu" id="theme_{{ $menu['slug'] }}">
                            <label class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] focus:outline-none peer-checked:border-transparent" for="theme_{{ $menu['slug'] }}">
                                <div class="flex items-center gap-[10px]">
                                    @php
                                        $activeTheme = \Cms\Core\Models\Option::get('active_theme', 'default');
                                        $themeIconPath = base_path('cms-content/themes/' . $activeTheme . '/assets/images/icons/' . ($menu['icon'] ?? 'icon-setting-2.svg'));
                                    @endphp
                                    @if(str_contains($menu['icon'] ?? 'icon-setting-2.svg', '.'))
                                        @if(file_exists($themeIconPath))
                                            <span class="theme-svg-icon flex items-center justify-center w-[24px] h-[24px] {{ $isActive ? 'brightness-0 invert' : '' }}">
                                                {!! file_get_contents($themeIconPath) !!}
                                            </span>
                                        @else
                                            <img src="{{ asset('assets/images/icons/' . ($menu['icon'] ?? 'icon-setting-2.svg')) }}" class="{{ $isActive ? 'brightness-0 invert' : '' }}" alt="side menu icon">
                                        @endif
                                    @else
                                        <i class="{{ $menu['icon'] }} text-[24px] w-[24px] text-center {{ $isActive ? 'text-white' : 'text-gray-500 dark:text-gray-dark-500' }}"></i>
                                    @endif
                                    <span class="text-normal font-semibold sidemenu-title {{ $isActive ? 'text-white' : 'text-gray-500 dark:text-gray-dark-500' }}">{{ $menu['title'] }}</span>
                                </div>
                            </label><img class="absolute right-2 transition-all duration-150 caret-icon pointer-events-none peer-checked:rotate-180 top-[22px]" src="{{ asset('assets/images/icons/icon-arrow-down.svg') }}" alt="caret icon">
                            <div class="hidden peer-checked:block">
                                <ul class="text-gray-300 child-menu z-10 pl-[53px]">
                                    <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal {{ request()->routeIs('cms.admin.theme.' . $menu['slug']) ? 'text-white font-bold' : '' }}" href="{{ route('cms.admin.theme.' . $menu['slug']) }}">{{ $menu['title'] }}</a></li>
                                    @foreach($menu['submenus'] as $submenu)
                                        @hasrole($submenu['role'])
                                        <li class="pb-2 transition-opacity duration-150 hover:opacity-75"><a class="text-normal {{ request()->routeIs('cms.admin.theme.' . $submenu['slug']) ? 'text-white font-bold' : '' }}" href="{{ route('cms.admin.theme.' . $submenu['slug']) }}">{{ $submenu['title'] }}</a></li>
                                        @endhasrole
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('cms.admin.theme.' . $menu['slug']) }}" class="flex items-center justify-between w-full cursor-pointer py-[17px] px-[21px] hover:bg-gray-50 dark:hover:bg-gray-dark-100 rounded-xl">
                                <div class="flex items-center gap-[10px]">
                                    @php
                                        $activeTheme = \Cms\Core\Models\Option::get('active_theme', 'default');
                                        $themeIconPath = base_path('cms-content/themes/' . $activeTheme . '/assets/images/icons/' . ($menu['icon'] ?? 'icon-setting-2.svg'));
                                    @endphp
                                    @if(str_contains($menu['icon'] ?? 'icon-setting-2.svg', '.'))
                                        @if(file_exists($themeIconPath))
                                            <span class="theme-svg-icon flex items-center justify-center w-[24px] h-[24px] {{ $isActive ? 'brightness-0 invert' : '' }}">
                                                {!! file_get_contents($themeIconPath) !!}
                                            </span>
                                        @else
                                            <img src="{{ asset('assets/images/icons/' . ($menu['icon'] ?? 'icon-setting-2.svg')) }}" class="{{ $isActive ? 'brightness-0 invert' : '' }}" alt="side menu icon">
                                        @endif
                                    @else
                                        <i class="{{ $menu['icon'] }} text-[24px] w-[24px] text-center {{ $isActive ? 'text-white' : 'text-gray-500 dark:text-gray-dark-500' }}"></i>
                                    @endif
                                    <span class="text-normal font-semibold sidemenu-title {{ $isActive ? 'text-white' : 'text-gray-500 dark:text-gray-dark-500' }}">{{ $menu['title'] }}</span>
                                </div>
                            </a>
                        @endif
                    </div>
                    @endhasrole
                @endforeach
            @endif

        </div>
    </div>
    <div class="rounded-xl bg-neutral pt-4 flex items-center gap-5 mt-5 sidebar-control pr-[18px] pb-[13px] pl-[19px] dark:bg-dark-neutral-border">
        <div class="flex items-center gap-3"><i class="moon-icon" id="theme-toggle-dark-icon"><img class="cursor-pointer" src="{{ asset('assets/images/icons/icon-moon.svg') }}" alt="moon icon"><img class="cursor-pointer" src="{{ asset('assets/images/icons/icon-moon-active.svg') }}" alt="moon icon"></i>
        <label class="flex items-center cursor-pointer" for="theme-toggle" id="toggle-theme-btn"> 
            <div class="relative"> 
            <input class="sr-only peer" type="checkbox" name="" id="theme-toggle">
            <div class="block rounded-full w-[48px] h-[16px] bg-gray-300 peer-checked:bg-[#B2A7FF]"></div>
            <div class="dot dotS absolute rounded-full transition h-[24px] w-[24px] top-[-4px] left-[-4px] bg-[#B2A7FF] peer-checked:bg-color-brands"></div>
            </div>
        </label><i class="sun-icon" id="theme-toggle-light-icon"><img class="cursor-pointer" src="{{ asset('assets/images/icons/icon-sun.svg') }}" alt="sun icon"><img class="cursor-pointer" src="{{ asset('assets/images/icons/icon-sun-active.svg') }}" alt="sun icon"></i>
        </div>
        <div class="bg-neutral-bg w-[2px] h-[30px] dark:bg-dark-neutral-bg"></div>
        <div> <img class="cursor-pointer" id="sidebar-expand" src="{{ asset('assets/images/icons/icon-maximize-3.svg') }}" alt="expand icon"></div>
    </div>
</aside>