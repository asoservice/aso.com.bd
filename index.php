<?php
require __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as CapsuleManager;
use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ );
$dotenv->load();

// Function to get database configuration from environment variables
function getDbConfigFromEnv() {
    return [
        'driver'    => env('DB_DRIVER', 'mysql'),
        'host'      => env('DB_HOST', 'localhost'),
        'database'  => env('DB_DATABASE', 'aso'),
        'username'  => env('DB_USERNAME', 'root'),
        'password'  => env('DB_PASSWORD', ''),
        'charset'   => env('DB_CHARSET', 'utf8'),
        'collation' => env('DB_COLLATION', 'utf8_unicode_ci'),
        'prefix'    => env('DB_PREFIX', ''),
    ];
}

// Usage
$config = getDbConfigFromEnv();
$capsule = new CapsuleManager;
$capsule->addConnection($config);
$capsule->setAsGlobal();
$capsule->bootEloquent();

function createRouteName(string $resource, string $prefix = 'backend', array $actions = ['index', 'create', 'edit', 'store', 'update', 'destroy']){
    // $routeNames = array_map(fn ($item)=> ["{$item}"=> "{$prefix}.{$resource}.{$item}"], $actions);
    $routeNames = [];
    foreach ($actions as $action) {
        $routeNames[$action] = "{$prefix}.{$resource}.{$action}";
    }
    return json_encode($routeNames);
}

function bootModules(array $modulesData) {
    global $capsule;
    $messages = [];

    foreach ($modulesData as $module => $moduleData) {
        if($capsule->table('modules')->where('name', $module)->exists()) {
            $messages[] = "Resource `{$module}` already exists or module name must be unique!";
        } else {
            $capsule->table('modules')->insert([ 
                'name' => $module, 
                'actions' => $moduleData,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $messages[] = "Resource `{$module}` created successfully!";
        }
    }

    return $messages;
}


$routes = [
    'faqs' => createRouteName('faqs'),
    'faq-categories' => createRouteName('faq-categories'),
];

function insertPermissions(array $routes){
    global $capsule;
    $permissions = [];
    foreach ($routes as $route) {
        foreach (json_decode($route, true) as $routeName){
            // $permissions[] = $routeName;
            if($capsule->table('permissions')->where('name', $routeName)->exists()) {
                $permissions[] = "Permission `{$routeName}` already exists or permission name must be unique!";
            } else {
                $capsule->table('permissions')->insert([ 
                    'name' => $routeName,
                    'guard_name' => 'web', 
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $permissions[] = "Permission `{$routeName}` created successfully!";
            }
        }
    }

    return $permissions;
}

// $message = $capsule->table('modules')->get();
// echo "\n\n" . json_encode($message, 128) . "\n\n";

// $role = $capsule->table('roles')->where('name', 'admin')->first();
// echo "\n\n" . json_encode(insertPermissions($routes), 128) . "\n\n";
// echo "\n\n" . json_encode($routes, 128) . "\n\n";
// echo "\n\n" . json_encode(bootModules($routes), 128) . "\n\n";
// echo "\n\n" . json_encode($config, 128) . "\n\n";



