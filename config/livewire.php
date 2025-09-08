<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Class Namespace
    |--------------------------------------------------------------------------
    |
    | This value sets the root namespace for Livewire component classes in
    | your application. This value affects component auto-discovery and
    | any Livewire file helper commands, like `artisan make:livewire`.
    |
    | After changing this item, run: `php artisan livewire:discover`.
    |
    */

    'class_namespace' => 'App\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | View Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path for Livewire component views. This affects
    | file manipulation helper commands like `artisan make:livewire`.
    |
    */

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | The default layout view that will be used when rendering a component via
    | Route::get('/some-endpoint', SomeComponent::class);. In this case the
    | the view returned by SomeComponent will be wrapped in "layouts.app"
    |
    */

    'layout' => 'components.layouts.app',

    /*
    |--------------------------------------------------------------------------
    | Lazy Loading Placeholder
    |--------------------------------------------------------------------------
    |
    | Livewire allows you to lazy load components that would otherwise slow down
    | the initial page load. Every component that has a `wire:loading` directive
    | will be replaced by this placeholder until the component is fully loaded.
    |
    */

    'lazy_placeholder' => null,

    /*
    |--------------------------------------------------------------------------
    | Temporary File Uploads Endpoint Configuration
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing uploads in a temporary directory
    | before the file is validated and stored permanently. All file uploads
    | are directed to a global endpoint for temporary storage. The config
    | items below are used for customizing the endpoint.
    |
    */

    'temporary_file_upload' => [
        'disk' => null,        // Example: 'local', 's3'              Default: 'default'
        'rules' => null,       // Example: ['file', 'mimes:jpg,png']  Default: ['required', 'file', 'max:12288'] (12MB)
        'directory' => null,   // Example: 'tmp'                      Default  'livewire-tmp'
        'middleware' => null,  // Example: 'throttle:5,1'             Default: 'throttle:60,1'
        'preview_mimes' => [   // Supported file types for temporary pre-signed file URLs.
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5, // Max time (in minutes) before an upload is invalidated.
    ],

    /*
    |--------------------------------------------------------------------------
    | Render On Redirect
    |--------------------------------------------------------------------------
    |
    | This value sets the Livewire rendering behavior when a redirect occurs
    | using the redirect() helper. When set to false, Livewire will not
    | render the component. This helps prevent certain issues where
    | the component was being rendered before the redirect.
    |
    */

    'render_on_redirect' => false,

    /*
    |--------------------------------------------------------------------------
    | Eloquent Model Binding
    |--------------------------------------------------------------------------
    |
    | Previous versions of Livewire allowed binding to Eloquent model
    | properties directly. This is no longer supported. This value
    | sets the behavior when a model binding is attempted.
    |
    */

    'legacy_model_binding' => false,

    /*
    |--------------------------------------------------------------------------
    | Auto-inject Frontend Assets
    |--------------------------------------------------------------------------
    |
    | By default, Livewire automatically injects its JavaScript and CSS
    | into the page. If you want to disable this behavior and manually
    | inject the assets yourself, set this to false.
    |
    */

    'inject_assets' => true,

    /*
    |--------------------------------------------------------------------------
    | Navigate (SPA mode)
    |--------------------------------------------------------------------------
    |
    | By default, Livewire includes a "Navigate" page component that adds
    | single-page-application-like navigation between pages. Set this to
    | false to disable this feature.
    |
    */

    'navigate' => [
        'show_progress_bar' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | HTML Encoding
    |--------------------------------------------------------------------------
    |
    | Livewire will safely encode HTML in component properties. This
    | prevents XSS attacks. If you want to disable this behavior,
    | set this to false.
    |
    */

    'html_encoding' => true,

    /*
    |--------------------------------------------------------------------------
    | Pretty URLs
    |--------------------------------------------------------------------------
    |
    | By default, Livewire will use "pretty" URLs for pagination and other
    | Livewire features. Set this to false to disable this behavior.
    |
    */

    'pretty_urls' => true,

    /*
    |--------------------------------------------------------------------------
    | Back Button Cache
    |--------------------------------------------------------------------------
    |
    | By default, Livewire will cache the component state when the user
    | navigates away from the page and restore it when they return.
    | Set this to false to disable this behavior.
    |
    */

    'back_button_cache' => false,

    /*
    |--------------------------------------------------------------------------
    | Immutable Component Data
    |--------------------------------------------------------------------------
    |
    | By default, Livewire will make component data immutable. This
    | prevents accidental mutations. Set this to false to disable
    | this behavior.
    |
    */

    'immutable_data' => true,

    /*
    |--------------------------------------------------------------------------
    | Component Auto-discovery
    |--------------------------------------------------------------------------
    |
    | By default, Livewire will automatically discover components in
    | your application. Set this to false to disable this behavior.
    |
    */

    'auto_discover' => true,

    /*
    |--------------------------------------------------------------------------
    | Component Namespace
    |--------------------------------------------------------------------------
    |
    | This value sets the root namespace for Livewire component classes in
    | your application. This value affects component auto-discovery and
    | any Livewire file helper commands, like `artisan make:livewire`.
    |
    | After changing this item, run: `php artisan livewire:discover`.
    |
    */

    'component_namespace' => 'App\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | Component View Path
    |--------------------------------------------------------------------------
    |
    | This value sets the path for Livewire component views. This affects
    | file manipulation helper commands like `artisan make:livewire`.
    |
    */

    'component_view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Component Layout
    |--------------------------------------------------------------------------
    |
    | The default layout view that will be used when rendering a component via
    | Route::get('/some-endpoint', SomeComponent::class);. In this case the
    | the view returned by SomeComponent will be wrapped in "layouts.app"
    |
    */

    'component_layout' => 'components.layouts.app',

    /*
    |--------------------------------------------------------------------------
    | Component Lazy Loading Placeholder
    |--------------------------------------------------------------------------
    |
    | Livewire allows you to lazy load components that would otherwise slow down
    | the initial page load. Every component that has a `wire:loading` directive
    | will be replaced by this placeholder until the component is fully loaded.
    |
    */

    'component_lazy_placeholder' => null,

    /*
    |--------------------------------------------------------------------------
    | Component Temporary File Uploads Endpoint Configuration
    |--------------------------------------------------------------------------
    |
    | Livewire handles file uploads by storing uploads in a temporary directory
    | before the file is validated and stored permanently. All file uploads
    | are directed to a global endpoint for temporary storage. The config
    | items below are used for customizing the endpoint.
    |
    */

    'component_temporary_file_upload' => [
        'disk' => null,        // Example: 'local', 's3'              Default: 'default'
        'rules' => null,       // Example: ['file', 'mimes:jpg,png']  Default: ['required', 'file', 'max:12288'] (12MB)
        'directory' => null,   // Example: 'tmp'                      Default  'livewire-tmp'
        'middleware' => null,  // Example: 'throttle:5,1'             Default: 'throttle:60,1'
        'preview_mimes' => [   // Supported file types for temporary pre-signed file URLs.
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5, // Max time (in minutes) before an upload is invalidated.
    ],

    /*
    |--------------------------------------------------------------------------
    | Component Render On Redirect
    |--------------------------------------------------------------------------
    |
    | This value sets the Livewire rendering behavior when a redirect occurs
    | using the redirect() helper. When set to false, Livewire will not
    | render the component. This helps prevent certain issues where
    | the component was being rendered before the redirect.
    |
    */

    'component_render_on_redirect' => false,

    /*
    |--------------------------------------------------------------------------
    | Component Eloquent Model Binding
    |--------------------------------------------------------------------------
    |
    | Previous versions of Livewire allowed binding to Eloquent model
    | properties directly. This is no longer supported. This value
    | sets the behavior when a model binding is attempted.
    |
    */

    'component_legacy_model_binding' => false,

    /*
    |--------------------------------------------------------------------------
    | Component Auto-inject Frontend Assets
    |--------------------------------------------------------------------------
    |
    | By default, Livewire automatically injects its JavaScript and CSS
    | into the page. If you want to disable this behavior and manually
    | inject the assets yourself, set this to false.
    |
    */

    'component_inject_assets' => true,

    /*
    |--------------------------------------------------------------------------
    | Component Navigate (SPA mode)
    |--------------------------------------------------------------------------
    |
    | By default, Livewire includes a "Navigate" page component that adds
    | single-page-application-like navigation between pages. Set this to
    | false to disable this feature.
    |
    */

    'component_navigate' => [
        'show_progress_bar' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Component HTML Encoding
    |--------------------------------------------------------------------------
    |
    | Livewire will safely encode HTML in component properties. This
    | prevents XSS attacks. If you want to disable this behavior,
    | set this to false.
    |
    */

    'component_html_encoding' => true,

    /*
    |--------------------------------------------------------------------------
    | Component Pretty URLs
    |--------------------------------------------------------------------------
    |
    | By default, Livewire will use "pretty" URLs for pagination and other
    | Livewire features. Set this to false to disable this behavior.
    |
    */

    'component_pretty_urls' => true,

    /*
    |--------------------------------------------------------------------------
    | Component Back Button Cache
    |--------------------------------------------------------------------------
    |
    | By default, Livewire will cache the component state when the user
    | navigates away from the page and returns. Set this to false to disable
    | this behavior.
    |
    */

    'component_back_button_cache' => false,

    /*
    |--------------------------------------------------------------------------
    | Component Immutable Component Data
    |--------------------------------------------------------------------------
    |
    | By default, Livewire will make component data immutable. This
    | prevents accidental mutations. Set this to false to disable
    | this behavior.
    |
    */

    'component_immutable_data' => true,

    /*
    |--------------------------------------------------------------------------
    | Component Auto-discovery
    |--------------------------------------------------------------------------
    |
    | By default, Livewire will automatically discover components in
    | your application. Set this to false to disable this behavior.
    |
    */

    'component_auto_discover' => true,
];
