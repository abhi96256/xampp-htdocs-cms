<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'A2P Realtech Pvt. Ltd.',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => 'A2P Realtech',
    'logo_img' => "/imgs/logo.png",
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => false,

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => true,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary signin_btn',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4 bg-black',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-dark navbar-dark bg-black',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => true,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-dark',
    'sidebar_scrollbar_auto_hide' => 'n',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-dark',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => '/',
    'logout_url' => 'account/signout',
    'login_url' => 'account/signin',
    'register_url' => false,
    'password_reset_url' => false,
    'password_email_url' => false,
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type' => 'navbar-search',
            'text' => 'search',
            'topnav_right' => true,
        ],
        [
            'type' => 'navbar-notifcation',
            'text' => 'Notification',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items:
        // [
        //     'type' => 'sidebar-menu-search',
        //     'text' => 'search',
        // ],
        [
            'text' => 'dashboard',
            'url' => '/',
            'icon' => 'fas fa-fw fa-chart-line',
            // 'label'       => 4,
            // 'label_color' => 'success',
        ],
        [
            'text' => 'Attendance',
            'url' => 'attendance',
            'icon' => 'fas fa-fw fa-user-clock',
        ],
        [
            'text' => 'DPR',
            'url' => 'dpr*',
            'icon' => 'fas fa-fw fa-calendar-check',
            'submenu' => [
                [
                    'text' => 'All DPR Reports',
                    'url' => 'dpr',
                    'icon' => 'fas fa-fw fa-list',
                ],
                [
                    'text' => 'Fill Daily DPR',
                    'url' => 'dpr/create',
                    'icon' => 'fas fa-fw fa-plus-square',
                ],
            ],
        ],
        [
            'text' => 'leads',
            'url' => 'enquiries/*',
            'icon' => 'fas fa-fw fa-question-circle',
            'can' => 'enquiry_access',
            'submenu' => [
                [
                    'text' => 'All Leads',
                    'url' => 'enquiries',
                    'icon' => 'fas fa-fw fa-list',
                    'can' => 'enquiry_show',
                ],
                [
                    'text' => 'Add Lead',
                    'url' => 'enquiries/create',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'enquiry_create',
                ],
            ],
        ],
        [
            'text' => 'Site Visits',
            'url'  => 'monthly-visits',
            'icon' => 'fas fa-fw fa-map-marker-alt',
            'can'  => 'site_visits access',
        ],
        [
            'text' => 'Happy Client',
            'url' => 'clients/*',
            'icon' => 'fas fa-fw fa-users',
            'can' => 'client_access',
            'submenu' => [
                [
                    'text' => 'All Happy Clients',
                    'url' => 'clients',
                    'icon' => 'fas fa-fw fa-list',
                    'can' => 'client_show',
                ],
                [
                    'text' => 'Add Happy Client',
                    'url' => 'clients/create',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'client_create',
                ],
            ],
        ],
        [
            'text' => 'Projects',
            'url' => 'projects/*',
            'icon' => 'fas fa-fw fa-project-diagram',
            'can' => 'project_access',
            'submenu' => [
                [
                    'text' => 'all_projects',
                    'url' => 'projects',
                    'icon' => 'fas fa-fw fa-list',
                    'can' => 'project_show',
                ],
                [
                    'text' => 'add_project',
                    'url' => 'projects/create',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'project_create',
                ],
            ],
        ],
        [
            'text' => 'dues',
            'url' => 'dues/*',
            'icon' => 'fas fa-fw fa-cash-register',
            'can' => 'dues_access',
            'submenu' => [
                [
                    'text' => 'all_dues',
                    'url' => 'dues',
                    'icon' => 'fas fa-fw fa-list',
                    'can' => 'dues_show',
                ],
                [
                    'text' => 'add_due',
                    'url' => 'dues/create',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'dues_create',
                ],
            ],
        ],
        [
            'text' => 'Wallet',
            'url' => 'wallet/*',
            'icon' => 'fas fa-fw fa-wallet',
            'can' => 'wallet_access',
            'submenu' => [
                [
                    'text' => 'List',
                    'url' => 'wallet',
                    'icon' => 'fas fa-fw fa-list',
                    'can' => 'wallet_show',
                ],
                [
                    'text' => 'Add Amount',
                    'url' => 'wallet/create',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'wallet_create',
                ],
            ],
        ],
        [
            'text' => 'expenses',
            'url' => 'expenses/*',
            'icon' => 'fas fa-fw fa-money-bill',
            'can' => 'expenses_access',
            'submenu' => [
                [
                    'text' => 'all_expenses',
                    'url' => 'expenses',
                    'icon' => 'fas fa-fw fa-list',
                    'can' => 'expenses_show',
                ],
                [
                    'text' => 'add_expense',
                    'url' => 'expenses/create',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'expenses_create',
                ],
            ],
        ],
        [
            'text' => 'User Verification',
            'url'  => 'agreements',
            'icon' => 'fas fa-fw fa-file-signature',
            'can'  => 'user_management_access',
        ],
        [
            'header' => 'administartion',
        ],
        [
            'text' => 'Office Payment',
            'url' => 'office_payment',
            'icon' => 'fas fa-fw fa-money-check-alt',
            'can' => 'office_payment_access',
        ],
        [
            'text' => 'Activities',
            'url' => 'activities',
            'icon' => 'fas fa-fw fa-receipt',
            'can' => 'report_access',

        ],
        [
            'text' => 'reports',
            'url' => 'reports',
            'icon' => 'fas fa-fw fa-receipt',
            'can' => 'report_access',
            'submenu' => [
                [
                    'text' => 'Users Report',
                    'url' => 'reports',
                    'icon' => 'fas fa-fw fa-list',
                    'can' => 'report_access',
                ],
                [
                    'text' => 'Sell Reports',
                    'url' => 'reports/sell',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'report_access',
                ],
                [
                    'text' => 'Pending Leads',
                    'url' => 'reports/pending',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'report_access',
                ],
                [
                    'text' => 'Incentive Reports',
                    'url' => 'reports/incentive',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'report_access',
                ],
                [
                    'text' => 'Company Incentive Reports',
                    'url' => 'reports/cincentive',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'report_access',
                ],
                [
                    'text' => 'Leads Transfer Reports',
                    'url' => 'reports/transfer',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'report_access',
                ],
                [
                    'text' => 'Expenses Reports',
                    'url' => 'reports/expenses',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => 'report_access',
                ],
            ],
        ],
        [
            'text' => 'transactions',
            'url' => 'transactions',
            'icon' => 'fas fa-fw fa-file-invoice',
            'can' => 'transaction_access',
        ],
        [
            'text' => 'Invoices',
            'url' => 'invoices',
            'icon' => 'fas fa-fw fa-file-invoice',
            'can' => 'transaction_access',
        ],
        [
            'text' => 'Roles & Permissions',
            'url' => '#',
            'icon' => 'fas fa-fw fa-user-shield',
            'can' => 'role_access',
            'submenu' => [
                [
                    'text' => 'All Roles',
                    'url' => 'roles_permissions',
                    'icon' => 'fas fa-fw fa-list',
                    'can' => 'role_access',
                ],
                [
                    'text' => 'Team Management',
                    'url' => 'teams',
                    'icon' => 'fas fa-fw fa-users-cog',
                    'can' => 'role_access',
                ],
            ],
        ],
        [
            'text' => 'users',
            'url' => 'useraccount/*',
            'icon' => 'fas fa-fw fa-users-cog',
            'can' => 'user_management_access',
            'submenu' => [
                [
                    'text' => 'all_users',
                    'url' => 'useraccount',
                    'icon' => 'fas fa-fw fa-list',
                    'can' => '',
                ],
                [
                    'text' => 'add_user',
                    'url' => 'useraccount/create',
                    'icon' => 'fas fa-fw fa-plus-square',
                    'can' => '',
                ],
            ],
        ],
        ['header' => 'account_settings'],
        [
            'text' => 'Notification',
            'url' => 'myaccount/notification',
            'icon' => 'fas fa-fw fa-user-shield',
        ],
        [
            'text' => 'my_account',
            'url' => 'myaccount',
            'icon' => 'fas fa-fw fa-user-shield',
        ],
        [
            'text' => 'Logout',
            'url' => 'account/signout',
            'icon' => 'fas fa-fw fa-sign-out-alt',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Base' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => 'css/base.css?v=10.5',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => 'js/sidebar_state.js?v=3.3',
                ],
            ],
        ],
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    */

    'livewire' => false,
];
