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

function insertPermissions(){
    $routes = [
        'faqs' => createRouteName('faqs'),
        'faq-categories' => createRouteName('faq-categories'),
    ];
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


final class SyncDatabase {
    private ?string $importSqlPath = null;
    private string $sqlPath;
    private array $exportDBConfig;
    private array $importDBConfig;
    private CapsuleManager $importDB;
    private CapsuleManager $exportDB;
    public array $messages = [];
    private bool $isBooted = false;

    public function __construct(array $exportDBConfig = [], array $importDBConfig = []) {
        $this->exportDBConfig = empty($exportDBConfig) ? [
            'driver'    => env('EXPORT_DB_DRIVER', 'mysql'),
            'host'      => env('EXPORT_DB_HOST', 'localhost'),
            'database'  => env('EXPORT_DB_DATABASE', 'aso_2'),
            'username'  => env('EXPORT_DB_USERNAME', 'root'),
            'password'  => env('EXPORT_DB_PASSWORD', ''),
            'charset'   => env('EXPORT_DB_CHARSET', 'utf8'),
            'collation' => env('EXPORT_DB_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('EXPORT_DB_PREFIX', ''),
        ] : $exportDBConfig;

        $this->importDBConfig = empty($importDBConfig) ? [
            'driver'    => env('EXPORT_DB_DRIVER', 'mysql'),
            'host'      => env('EXPORT_DB_HOST', 'localhost'),
            'database'  => env('EXPORT_DB_DATABASE', 'aso_3'),
            'username'  => env('EXPORT_DB_USERNAME', 'root'),
            'password'  => env('EXPORT_DB_PASSWORD', ''),
            'charset'   => env('EXPORT_DB_CHARSET', 'utf8'),
            'collation' => env('EXPORT_DB_COLLATION', 'utf8_unicode_ci'),
            'prefix'    => env('EXPORT_DB_PREFIX', ''),
        ] : $importDBConfig;

        $importSqlPath = __DIR__ . '/json-and-sql/demo_aso.sql';

        if(file_exists($importSqlPath)){
            $this->importSqlPath = $importSqlPath;
        }

        $this->sqlPath = __DIR__ . '/json-and-sql/db.sql';
        if (!file_exists(dirname($this->sqlPath))) {
            mkdir(dirname($this->sqlPath), 0777, true); // Ensure directory exists
        }
    }

    public function addImportSqlFile(string $filePath, bool $isBaseDir = true){
        if($isBaseDir){
            $this->importSqlPath =  __DIR__ . $filePath;
        } else {
            $this->importSqlPath = $filePath;
        }

        if(!file_exists($this->importSqlPath)){
            $this->importSqlPath = null;
        }
        return $this;
    }

    public function bootImportDB(){
        $this->importDB = new CapsuleManager();
        $this->importDB->addConnection($this->importDBConfig);
        $this->importDB->setAsGlobal();
        $this->importDB->bootEloquent();

        return $this;
    }

    public function bootExportDB(){
        $this->exportDB = new CapsuleManager();
        $this->exportDB->addConnection($this->exportDBConfig);
        $this->exportDB->setAsGlobal();
        $this->exportDB->bootEloquent();

        return $this;
    }

    public function boot() {
        $this->bootExportDB();
        $this->bootImportDB();
        $this->isBooted = true;

        return $this;
    }

    public function getConfigs(){
        $importSqlPath = $this->importSqlPath;
        $exportDBConfig = $this->exportDBConfig;
        $importDBConfig = $this->importDBConfig;
        $sqlPath = $this->sqlPath;
        $messages = $this->messages;
        $isBooted = $this->isBooted;

        return compact('exportDBConfig', 'importDBConfig', 'importSqlPath', 'sqlPath', 'messages', 'isBooted');
    }

    private function connect(string $dbType){
        $configs = new stdClass;
        if($dbType == 'export'){
            if (!$this->exportDB) {
                $this->bootExportDB();
            }

            $configs->db = $this->exportDB;
            $configs->dbName = $this->exportDBConfig['database'];
        } else if($dbType == 'import'){
            if (!$this->importDB) {
                $this->bootImportDB();
            }

            $configs->db = $this->importDB;
            $configs->dbName = $this->importDBConfig['database'];
        } else {
            $configs->db = null;
            $configs->dbName = null;
            $this->messages[] = "Failed Configure Database";
        }

        return $configs;
    }

    private function syncDBFromFile(string $dbType){
        $config = $this->connect($dbType);

        if($this->importSqlPath && $config->db){
            try {
                $sql = file_get_contents($this->importSqlPath);
    
                // Execute the SQL dump in the target database
                $config->db->getConnection()->unprepared($sql);
    
                $this->messages[] = "Import completed successfully from [{$this->importSqlPath}] to [{$config->dbName}]";
                return $this;
            } catch (Exception $e) {
                $this->messages[] = "Error during import: " . $e->getMessage();
                return $this;
            }
        }

        $this->messages[] = "Failed to open [{$this->importSqlPath}]";
        return $this;
    }

    public function syncExportDBFromFile(){
        return $this->syncDBFromFile('export');
    }

    public function syncImportDBFromFile(){
        return $this->syncDBFromFile('import');
    }

    private function saveSqlFile(array $dbConfig){
        $host = $dbConfig['host'] ?? 'localhost';
        $database = $dbConfig['database'] ?? '';
        $username = $dbConfig['username'] ?? '';
        $password = $dbConfig['password'] ?? '';

        if (empty($database)) {
            $this->messages[] = 'Database name is missing in the configuration.';
            return $this;
        }

        $command = sprintf(
            'mysqldump --host=%s --user=%s --password=%s %s > %s',
            escapeshellarg($host),
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($database),
            escapeshellarg($this->sqlPath)
        );

        $output = null;
        $returnVar = null;

        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->messages[] = "Database exported successfully to [{$this->sqlPath}]";
            return $this;
        }

        $this->messages[] = "Failed to export the database. Command: [{$command}]";
        return $this;
    }

    public function saveSqlFileFromExportDB(){
        return $this->saveSqlFile($this->exportDBConfig);
    }

    public function saveSqlFileFromImportDB(){
        return $this->saveSqlFile($this->importDBConfig);
    }

    private function dumpSqlFile(string $dbType) {
        $config = $this->connect($dbType);

        if(!file_exists($this->sqlPath)){
            $this->messages[] = "Failed to open [{$this->sqlPath}]";
            return $this;
        }

        try {
            $sql = file_get_contents($this->sqlPath);

            // Execute the SQL dump in the target database
            $config->db->getConnection()->unprepared($sql);

            $this->messages[] = "Import completed successfully from [{$this->sqlPath}] to [{$config->dbName}]";
            return $this;
        } catch (Exception $e) {
            $this->messages[] = "Error during import: " . $e->getMessage();
            return $this;
        }
    }

    public function dumpSqlFromImportDB(){
        return $this->dumpSqlFile('import');
    }

    public function dumpSqlFromExportDB(){
        return $this->dumpSqlFile('export');
    }

    private function dropTables(string $dbType){
        $config = $this->connect($dbType);
        
        if($config->db && $config->dbName){
            try {
                $config->db->getConnection()->statement('SET FOREIGN_KEY_CHECKS = 0');
                $tables = $config->db->getConnection()->select("SHOW TABLES");
                $tableKey = "Tables_in_{$config->dbName}";
    
                foreach ($tables as $table) {
                    $tableName = $table->$tableKey;
                    $config->db->getConnection()->statement("DROP TABLE `$tableName`");
                }
    
                $this->messages[] = "All tables dropped successfully from [{$config->dbName}].";
                return $this;
            } catch (\Exception $e) {
                $this->messages[] = "Failed to drop tables: " . $e->getMessage();
                return $this;
            }
        }

        $this->messages[] = "Failed To Drop Tables";
        return $this;
    }

    public function dropTablesFromExportDB(){
        return $this->dropTables('export');
    }

    public function dropTablesFromImportDB(){
        return $this->dropTables('import');
    }

    public function merge(){
        if(!$this->isBooted) $this->boot();

        $this->saveSqlFileFromExportDB();
        $this->dropTablesFromImportDB();
        $this->dumpSqlFromImportDB();

        $message = "Successfully database exported from [{$this->exportDBConfig['database']}] and dumped into [{$this->importDBConfig['database']}], (with dropped all tables before dumping).";

        $this->messages[] = $message;
        return $this;
    }
}

$manager = new SyncDatabase(exportDBConfig: [
    'driver'    => 'mysql', // 'mysql',
    'host'      => 'localhost', // '139.162.8.132',
    'database'  => 'aso_3', // 'hridoy_dev',
    'username'  => 'root', // 'hridoy_dev',
    'password'  => '', // 'h8Id0Y$deVA60',
    'charset'   => 'utf8', // 'utf8',
    'collation' => 'utf8_unicode_ci', // 'utf8_unicode_ci',
    'prefix'    => '', 
], importDBConfig: [
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'apz_sheitech',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

echo "\nOperation in progress...\n";
// $manager->boot()->merge();
    // ->dropTablesFromImportDB();
    // ->addImportSqlFile('/json-and-sql/demo_aso.sql')
    // ->syncExportDBFromFile();

// $manager->boot();
// $manager->dropTablesFromImportDB();
// $manager->dropTablesFromExportDB();

// $manager->boot()->syncExportDBFromFile();
// echo json_encode($manager->getConfigs(), 128);



// $status = 'inactive';
// $stack = ['on', '1', 'true', true, 1, 'yes', 'y', 'active'];
// $checked = in_array($status, $stack, true);
// echo "\n\n". json_encode(compact('checked', 'status', 'stack'), JSON_PRETTY_PRINT) ."\n\n";

// import sheitech db into [apz_sheitech] as import db usng File
// $manager->addImportSqlFile('/htdocs/sheitech/sheitech-db.sql', false);
// $manager->boot();
// $manager->syncImportDBFromFile();


// Messages
$messages = json_encode($manager->messages, JSON_PRETTY_PRINT);
echo "\n\n{$messages}\n\n";