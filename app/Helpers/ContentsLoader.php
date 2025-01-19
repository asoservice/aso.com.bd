<?php 
namespace App\Helpers;

use Exception;
use stdClass;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Validation\ValidationException;

final class ContentsLoader
{
    public $data;
    public $model;
    private $dataTable;
    private HttpRequest $request;
    public string|int $id = 0;
    private string $fields = '';
    public bool $isData = false;
    public array $storableData = [];
    private array $unlikableFiles = [];
    public array $validateInputs = [];
    private bool $dataTableMake = true;
    private string $baseAssetPath = 'storage/media';
    private array $with = ['status' => 'undefined', 'message' => []];
    public array $dataTableRaws = ['rawColumns'=> [], 'columns'=> [], 'labels' => []];
    private array $views = ['index' => '', 'create' => '', 'show' => '', 'edit' => ''];
    private array $routes = ['index' => '', 'create' => '', 'show' => '', 'edit' => '', 'store' => '', 'update' => '', 'destroy' => ''];

    private string $routeSuffix = 'backend.';
    private string $routeResource = '';
    private array $storedFilePaths = [];

    public function addAssetPath(string $path, string $suffix = 'storage/media/'){
        $this->baseAssetPath = $suffix . $path;
        return $this;
    }

    public function initDataTable(bool $make = true){
        $this->dataTableMake = $make;
        $this->dataTable = DataTables::of($this->model::query()->latest('id'));
        
        return $this;
    }

    public function addActionColum(string $label = 'Actions', string $name = 'action', string $key = 'id', bool $isEditable = true, bool $isDeletable = true, $isVisible = false){
        $this->dataTableRaws['labels'][] = $label;
        $this->dataTableRaws['rawColumns'][] = $name;
        $this->dataTableRaws['columns'][] = ['data'=> $name, 'orderable'=> false, 'searchable'=> false];

        $this->dataTable->addColumn($name, function ($row) use($isEditable, $isDeletable, $isVisible, $key) {
            $editUrl = $this->getRoute('edit', $row->id);
            $deleteUrl = $this->getRoute('destroy', $row->id);
 
            $editable = $isEditable ? 
                "<a href='javascript:void(0)' class='edit-icon edit-btn' edit-btn='{$editUrl}' id='edit-{$row->{$key}}'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit'><path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path><path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path></svg>
                </a>"
            : '';

            $deletable = $isDeletable ? 
                "<a href='javascript:void(0)' delete-btn='{$deleteUrl}' class='delete-svg delete-btn' id='delete-{$row->{$key}}'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-trash-2 remove-icon delete-confirmation'><polyline points='3 6 5 6 21 6'></polyline><path d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'></path><line x1='10' y1='11' x2='10' y2='17'></line><line x1='14' y1='11' x2='14' y2='17'></line></svg>
                    </a>"
            : '';

            $visible = $isVisible ?
                "<a href='javascript:void(0)' class='lock-icon visible-btn' id='visible-{$row->{$key}}'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-lock'><rect x='3' y='11' width='18' height='11' rx='2' ry='2'></rect><path d='M7 11V7a5 5 0 0 1 10 0v4'></path></svg>
                    </a>"
            : '';

            return "<div class='action-div'>{$editable} {$visible} {$deletable}</div>";
        });

        return $this;
    }

    public function addColumn(string $label, string $name, null|string|callable $key = null, bool $orderable = true, bool $searchable = true){
        $newKey = (is_null($key) && !is_callable($key)) ? $name : $key;
        
        $this->dataTableRaws['labels'][] = $label;
        $this->dataTableRaws['rawColumns'][] = $name;
        $this->dataTableRaws['columns'][] = ['data'=> $name, 'name'=> $newKey, 'orderable'=> $orderable, 'searchable'=> $searchable];

        $this->dataTable->addColumn($name, function ($row) use($newKey, $key) {
            if(is_callable($key)){
                return $key($row);
            } else {
                return $row->{$newKey};
            }
        });

        return $this;
    }

    public function addImageColumn(string $label, string $name = 'image', ?string $key = null, int $width = 100){
        $newKey = is_null($key) ? $name : $key;

        $this->dataTableRaws['labels'][] = $label;
        $this->dataTableRaws['rawColumns'][] = $name;
        $this->dataTableRaws['columns'][] = ['data'=> $name, 'name'=> $newKey, 'orderable'=> false, 'searchable'=> false];

        $this->dataTable->addColumn($name, function ($row) use($newKey, $width) {
            if(file_exists($row->{$newKey})){
                $path = asset($row->{$newKey});
                return "<div class='image-list-detail'>
                        <div class='position-relative'>
                            <img src='{$path}' alt='User Image' class='image-list-item' width='{$width}'>
                            <div class='close-icon'> <i data-feather='x'></i> </div>
                        </div>
                    </div>";
            }

            return 'Image Not Found.';
        });

        return $this;
    }

    public function addCheckBoxColumn(string $label = '<input type="checkbox" class="form-check-input" id="select-all">', string $name = 'checkbox', string $key = 'id'){
        $this->dataTableRaws['labels'][] = $label;
        $this->dataTableRaws['rawColumns'][] = $name;
        $this->dataTableRaws['columns'][] = ['data'=> $name, 'name'=> $name, 'orderable'=> false, 'searchable'=> false];

        $this->dataTable->addColumn($name, function ($row) use($key) {
            return "<div class='form-check'>
                        <input type='checkbox' name='row' class='rowClass form-check-input' value='{$row->{$key}}' id='rowId{$row->{$key}}'>
                    </div>";
        });

        return $this;
    }

    public function addStatusColumn(string $label = 'Status', string $name = 'status', ?string $key = 'status'){
        $newKey = is_null($key) ? $name : $key;

        $this->dataTableRaws['labels'][] = $label;
        $this->dataTableRaws['rawColumns'][] = $name;
        $this->dataTableRaws['columns'][] = ['data'=> $name, 'name'=> $name, 'orderable'=> false, 'searchable'=> false];

        $this->dataTable->addColumn($name, function ($row) use($newKey) {
            $isChecked = ($row->{$newKey} == 1 || $row->{$newKey} == 'active') ? 'checked' : '';
            $url = $this->getRoute('edit', $row->id);

            return "<div class='editor-space justify-content-center'>
                        <label class='switch {$newKey}-switch table-switch'>
                            <input class='form-check-input' type='checkbox' name='{$newKey}' id='{$newKey}-{$row->id}' value='{$row->{$newKey}}' {$isChecked} switch-key='{$newKey}' table-switch='{$url}'>
                            <span class='switch-state'></span>
                        </label>
                    </div>";
        });

        return $this;
    }

    public function addSelectColumn(callable|array $options, string $label = 'Status', string $key = 'status', bool $multiple = false, string $placeholder = 'Select One'){
        $this->dataTableRaws['labels'][] = $label;
        $this->dataTableRaws['rawColumns'][] = $key;
        $this->dataTableRaws['columns'][] = ['data'=> $key, 'name'=> $key, 'orderable'=> false, 'searchable'=> false];
        
        $isMultiple = $multiple ? ['nameKey'=> "{$key}[]", 'multiple'=> 'multiple'] : ['nameKey'=> $key, 'multiple'=> ''];

        $makeOption = function (?string $newValue) use ($options){
            $htmlOption = "";

            if(is_array($options)){
                foreach($options as $optKey => $optVal){
                    $isSelected = $optKey == $newValue ? 'selected' : '';
                    $htmlOption .= "<option value='{$optKey}' {$isSelected}>{$optVal}</option>";
                }
            } else {
                $getObjectOptions = new stdClass();
                $getObjectOptions->key = 'id';
                $getObjectOptions->value = 'title';
                $getOptions = $options($getObjectOptions);

                foreach($getOptions->data as $opt){
                    $isSelected = $opt->{$getOptions->key} == $newValue ? 'selected' : '';
                    $htmlOption .= "<option value='{$opt->{$getOptions->key}}' {$isSelected}>{$opt->{$getOptions->value}}</option>";
                    
                }
            }

            return $htmlOption;
        };


        $this->dataTable->addColumn($key, function ($row) use($key, $isMultiple, $makeOption, $placeholder) {
            $value = isset($row->{$key}) ? $row->{$key} : null;
            $url = $this->getRoute('edit', $row->id); 
                
            return "<div class='form-group row'>
                    <select class='form-control table-select' table-select='{$url}' table-select-key='{$key}' id='{$key}-{$row->id}' search='true'
                        name='{$isMultiple['nameKey']}' data-placeholder='{$placeholder}' {$isMultiple['multiple']}>
                        <option hidden disabled>{$placeholder}</option>
                        {$makeOption($value)}
                    </select>
                </div>";
        });

        return $this;
    }

    public function renderDataTable(){
        if (request()->ajax()) {
            return $this->dataTable->rawColumns($this->dataTableRaws['rawColumns'])->make($this->dataTableMake);
        }
        
        return view($this->views['index'], ['routes'=> $this->routes, 'columns' => $this->dataTableRaws['columns'], 'labels' => $this->dataTableRaws['labels']]);
    }

    public function addViews(string $viewFolder = 'contents', $viewFileTypes = ['index', 'create', 'show', 'edit', 'form'])
    {
        foreach ($viewFileTypes as $fileType) {
            $this->views[$fileType] = "{$viewFolder}.{$fileType}";
        }

        return $this;
    }

    public function findAndSetData(string|int $value = 0, string|callable $field = 'id'){
        if(!is_string($field)){
            $this->data = $field($this->model);
            return $this;
        }
        
        $this->id = $value;

        if($field == 'id'){
            $this->data = $this->model::find($this->id);
        } else {
            $this->data = $this->model::where($field, $this->id)->latest('id')->first();
        }

        if(!$this->data) $this->addMessage($this->id, "Model Data not found with [{$field}: {$this->id}]");

        return $this;
    } 

    public function routeName(
        string $name, 
        string $suffix = 'backend.', 
        array $resources = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'], 
        array $requiresId = ['show', 'edit', 'update', 'destroy']
    ) {
        $this->routeSuffix = $suffix;
        $this->routeResource = $name;

        foreach ($resources as $resource) {
            $routeName = "{$this->routeSuffix}{$name}.{$resource}";
            if (Route::has($routeName)) {
                $this->routes[$resource] = in_array($resource, $requiresId, true)
                    ? route($routeName, $this->id)
                    : route($routeName);
            } else {
                $this->addMessage($resource, $routeName);
            }
        }
        return $this;
    }

    private function getRoute(string $name, $params = []){
        $route = "{$this->routeSuffix}{$this->routeResource}.{$name}";
        if(Route::has($route)) return route($route, $params);
        
        $this->addMessage($name, "Undefined route name [{$route}]");
        return '/undefined/';
    }
    
    protected function addMessage(string $key, string $message): void
    {
        $this->with['status'] = 'multiple';
        $this->with['message'][] = ['type'=> $key, 'message' => $message];
    }    

    public function setModel($model = null){
        if(is_null($model)){
            $this->addMessage('model', 'Model not found');
            return $this;
        }

        $this->model = $model;
        return $this;
    }

    private function makeNote(bool $required, string $note){
        if(empty($note) && $required) return "<span> * </span>";
        if(!empty($note) && $required) return "<span> * {$note} </span>";
        if(!empty($note) && !$required) return "<span>{$note} </span>";

        return "";
    } 

    private function makeInputField(string $type, string $key, string $placeholder, string $required, ?string $value) {
        if($type == 'summery') return "<textarea class='form-control description-ckeditor' id='{$key}' rows='4' name='{$key}' cols='50' placeholder='{$placeholder}' {$required}>{$value}</textarea>";
        if($type == 'description') return "<textarea class='form-control description-ckeditor' id='{$key}' rows='4' name='{$key}' cols='50' placeholder='{$placeholder}' {$required}>{$value}</textarea>";
        if($type == 'normal') return "<textarea class='form-control normal-ckeditor' id='{$key}' rows='4' name='{$key}' cols='50' placeholder='{$placeholder}' {$required}>{$value}</textarea>";

        return "<input class='form-control' type='{$type}' id='{$key}' name='{$key}' value='{$value}' placeholder='{$placeholder}' {$required}>";
    }

    public function addInput(string $title, string $key, string $type = 'text', null|bool|string $value = null, string $placeholder = 'Write here...', bool $required = false, string $note = ''){
        if($value === true){
            $newValue = isset($this->data->{$key}) ? $this->data->{$key} : '';
        } else {
            $newValue  = old($key) ?? $value;
        }
        $isRequired = $required ? 'required' : '';

        $newNote = $this->makeNote($required, $note);
        $field = $this->makeInputField($type, $key, $placeholder, $isRequired, $newValue);
 
        $this->fields .= "
            <div class='form-group row'>
                <label class='col-md-2' for='{$key}'>{$title} {$newNote}</label>
                <div class='col-md-10'>
                    {$field}
                </div>
            </div>
        ";
        return $this;
    }

    public function addFileInput(string $title, string $key, null|bool|string $value = null, bool $multiple = false, bool $required = false, string $note = ''){
        $newValue  = "<div class='image-list mt-3 d-none preview-image' id='preview-image-{$key}'>
                        <div class='image-list-detail'>
                            <div class='position-relative'>
                                <img src='' alt='User Image' class='image-list-item'>
                                <div class='close-icon'> <i data-feather='x'></i> </div>
                            </div>
                        </div>
                    </div>";

        $isRequired = $required ? 'required' : '';
        $isMultiple = $multiple ? ['nameKey'=> "{$key}[]", 'multiple'=> 'multiple'] : ['nameKey'=> $key, 'multiple'=> ''];

        if($multiple){
            if($value === true){
                $files = json_decode(isset($this->data->{$key}) ? $this->data->{$key} : '[]') ?? [];
            } else {
                $files = is_array($value) ? $value : json_decode($value);
            }   
                
            $newValue = "<div class='image-list mt-3 preview-image' id='preview-image-{$key}'> <div class='image-list-detail'>";

            foreach ($files as $file) {
                if(file_exists($file)){
                    $path = asset($file);
                    $newValue .= "
                        <div class='position-relative'>
                            <img src='{$file}' alt='User Image' class='image-list-item'>
                            <div class='close-icon'> <i data-feather='x'></i> </div>
                        </div>
                    ";
                }
            }
            $newValue .= "</div> </div>";
        } else {
            if($value === true){
                $file = isset($this->data->{$key}) ? $this->data->{$key} : '';
            } else {
                $file = $value;
            }

            if(file_exists($file)){
                $path = asset($file);
                $newValue = "
                    <div class='image-list mt-3 preview-image' id='preview-image-{$key}'>
                        <div class='image-list-detail'>
                            <div class='position-relative'>
                                <img src='{$path}' alt='User Image' class='image-list-item'>
                                <div class='close-icon'> <i data-feather='x'></i> </div>
                            </div>
                        </div>
                    </div>
                ";
            }
        }

        $newNote = $this->makeNote($required, $note);

        $this->fields .= "
            <div class='form-group row'>
                <label class='col-md-2' for='{$key}'>{$title} {$newNote}</label>
                <div class='col-md-10'>
                    <input class='form-control preview-file-input' preview-file-input='preview-image-{$key}' type='file' id='{$key}' name='{$isMultiple['nameKey']}' {$isRequired} {$isMultiple['multiple']} />
                    
                    {$newValue}
                </div>
            </div>
        ";
        return $this;
    }

    public function addSwitchInput(string $title, string $key = 'status', bool|string $value = false, bool $required = false, string $note = ''){
        $stack = ['on', '1', 'true', true, 1, 'yes', 'y', 'active'];

        if($value === true && isset($this->data->{$key})){
            $status = $this->data->{$key};
            $checked = in_array($status, $stack, true);
        } else {
            $checked  = in_array((old($key) ?? $value), $stack, true);
        }
        
        $isChecked = $checked ? 'checked' : '';
        $isRequired = $required ? 'required' : '';

        $newNote = $this->makeNote($required, $note);

        $newValue = $checked ? 1 : 0;

        $this->fields .= "
            <div class='form-group row'>
                <label class='col-md-2' for='{$key}'>{$title} {$newNote}</label>
                <div class='col-md-10'>
                    <div class='editor-space'>
                        <label class='switch'>
                            <input class='form-control' type='hidden' name='{$key}' value='{$newValue}'>
                            <input class='form-check-input' type='checkbox' name='{$key}' id='{$key}' value='{$newValue}' {$isRequired} {$isChecked}>
                            <span class='switch-state'></span>
                        </label>
                    </div>
                </div>
            </div>
        ";

        return $this;
    }
    
    public function addSelectInput(string $title, string $key, callable|array $options, null|bool|string $value = null, bool $required = false, string $note = '', bool $multiple = false, string $placeholder = 'Select One'){
        if($value === true){
            $newValue = isset($this->data->{$key}) ? $this->data->{$key} : '';
        } else {
            $newValue  = old($key) ?? $value;
        }

        $isRequired = $required ? 'required' : '';
        $newNote = $this->makeNote($required, $note);
        $isMultiple = $multiple ? ['nameKey'=> "{$key}[]", 'multiple'=> 'multiple'] : ['nameKey'=> $key, 'multiple'=> ''];

        $makeOption = function () use ($options, $newValue){
            $htmlOption = "";

            if(is_array($options)){
                foreach($options as $optKey => $optVal){
                    $isSelected = $optKey == $newValue ? 'selected' : '';
                    $htmlOption .= "<option value='{$optKey}' {$isSelected}>{$optVal}</option>";
                }
            } else {
                $getObjectOptions = new stdClass();
                $getObjectOptions->key = 'id';
                $getObjectOptions->value = 'title';
                $getOptions = $options($getObjectOptions);

                foreach($getOptions->data as $opt){
                    $isSelected = $opt->{$getOptions->key} == $newValue ? 'selected' : '';
                    $htmlOption .= "<option value='{$opt->{$getOptions->key}}' {$isSelected}>{$opt->{$getOptions->value}}</option>";
                    
                }
            }

            return $htmlOption;
        };

        $opts = $makeOption();
        $this->fields .= "
            <div class='form-group row'>
                <label class='col-md-2' for='{$key}'>{$title} {$newNote}</label>
                <div class='col-md-10 error-div select-dropdown'>
                    <select {$isRequired} class='custom-select-2 form-control' id='{$key}' search='true'
                        name='{$isMultiple['nameKey']}' data-placeholder='{$placeholder}' {$isMultiple['multiple']}>
                        <option></option>
                        {$opts}
                    </select>
                    <!-- <span class='invalid-feedback d-block' role='alert' id='{$key}-alert'> <strong></strong></span> -->
                </div>
            </div>
        ";

        return $this;
    }

    public function addRequest(HttpRequest $request){
        $this->request = $request;
        return $this;
    }

    private function getRequestData(string $key, string $type = 'input'){
        $data = 0;
        if ($type == 'input') {
            $data = $this->request->input($key);
        } else if ($type == 'query') {
            $data = $this->request->query($key);
        } else if (method_exists($this->request, $type)) {
            $data = $this->request->{$type}($key);
        }

        return $data;
    }

    public function addData(string $tableKey, ?string $inputName = null, ?string $required = null, NULL|string|int|bool $value = null, string $type = 'input', ?callable $customizeInput = null)
    {
        $name = $inputName ?? $tableKey;
        $input = $this->getRequestData($name, $type);
        $defaultValue = is_string($value) ? $value : $input;

        if ($required) {
            $this->validateInputs[$name] = $required;
        }

        $this->storableData[$tableKey] = is_callable($customizeInput) ? $customizeInput($defaultValue) : $defaultValue;
        return $this;
    }

    public function addFile(string $tableKey, ?string $inputName = null, ?string $required = null, string $module = 'images', bool|string $value = false){
        $oldFile = null;
        $name = $inputName ?? $tableKey;
        $path = $this->baseAssetPath . '/' . $module . '/';

        $this->request->validate([$name => $required]);
        if($this->request->hasFile($name)){
            if($value === true && isset($this->data->{$tableKey})){
                $oldFile = $this->data->{$tableKey};
            }

            // if($required) $this->validateInputs[$name] = $required;
            $this->storableData[$tableKey] = $this->storeFile($this->request->file($name), $path, $oldFile);
            return $this;
        }
        
        return $this;
    }

    public function addMultipleFiles(string $tableKey, ?string $inputName = null, ?string $required = null, string $module = 'images', bool|string $value = false, bool $encodeAsJson = true) {
        $filePaths = [];
        $name = $inputName ?? $tableKey;
        $path = $this->baseAssetPath . $module . '/';
    
        // Check if files exist in the request
        $this->request->validate([$name => $required]);
        if ($this->request->hasFile($name)) {
            $files = $this->request->file($name);
    
            // Unlink old files if $value is true and previous data exists
            if ($value && isset($this->data->{$tableKey})) {
                $values = is_string($value) ? $value : $this->data->{$tableKey};
                $oldFiles = $encodeAsJson ? json_decode($values, true) : explode(',', $values);
                if (is_array($oldFiles)) {
                    foreach ($oldFiles as $oldFile) {
                        $oldFilePath = public_path($oldFile);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                } else {
                    $this->addMessage('error', "Files not found with [{$tableKey}]");
                }
            }
    
            if ($required) {
                $this->validateInputs[$name] = $required;
            }
    
            // Process each file
            foreach ($files as $file) {
                $filePaths[] = $this->storeFile($file, $path);
            }
    
            $encodedPaths = $encodeAsJson ? json_encode($filePaths) : implode(',', $filePaths);
            $this->storableData[$tableKey] = $encodedPaths;
        } else {
            $this->addMessage('error', "Files not found with [{$name}]");
        }
    
        return $this;
    }

    private function storeFile($file, string $path, ?string $oldFile = null)
    {
        $createPath = public_path($path);
        if (!File::isDirectory($createPath)) File::makeDirectory($createPath, 0777, true, true);

        $ext = $file->getClientOriginalExtension();
        $filename = Carbon::now()->toDateString() . '___' . Str::random() . '.' . $ext;
        $file->move($createPath, $filename);

        if ($oldFile && file_exists($oldFile)) unlink($oldFile);

        $pathname = $path . $filename;
        $this->storedFilePaths[] = $pathname;
        return $pathname;
    } 

    public function addCreator(string $tableKey = 'created_by', ?int $authId = null)
    {
        $id = is_null($authId) ? Auth::id() : $authId;
        $this->storableData[$tableKey] = $id;

        return $this;
    }

    public function addSlug(string $inputName, string $tableKey = 'slug', string $type = 'input')
    {
        $string = $this->getRequestData($inputName, $type);
        $slug = Str::slug($string);

        if ($this->model::where($tableKey, $slug)->exists()) {
            $counter = 1;

            while ($this->model::where($tableKey, "{$slug}-{$counter}")->exists()) {
                $counter++;
            }

            $slug = "{$slug}-{$counter}";
        }

        $this->storableData[$tableKey] = $slug;
        return $this;
    }

    public function render(string $name = 'index', ?string $id = null){
        if(in_array($name, array_keys($this->views))){
            return view($this->views[$name], ['fields' => $this->fields]);
        }

        $this->with['status'] = 'error';
        $this->with['message']['view'] = "View [{$name}] not found";
        return redirect()->back()->with($this->with['status'], $this->with['message']);
    }

    public function createPage(){
        return view($this->views['create'], ['fields' => $this->fields, 'routes'=> $this->routes, 'with'=> $this->with]);
    }

    public function editPage(){
        return view($this->views['edit'], ['fields' => $this->fields, 'routes'=> $this->routes, 'with'=> $this->with]);
    }

    public function formOnly(string $action = 'update', string $id = null, ?string $method = null){
        $route = $this->getRoute($action, $id);
        return view($this->views['form'], ['fields' => $this->fields, 'routes'=> $this->routes, 'action'=> $route, 'onlyForm'=> true, 'method'=> $method]);
    }

    private function rollbackFiles(){
        foreach ($this->storedFilePaths as $path) {
            if(file_exists($path)) unlink($path);
        }

        return $this;
    }

    public function storeData(bool $encoded = false)
    {
        try {
            $this->request->validate($this->validateInputs);
            $crateModel = $this->model::create($this->storableData); // Data creation

            // JSON response for API/ajax handling
            if ($encoded) {
                $this->addMessage('success', 'Data Created Successfully!');
                return response()->json([
                    'status' => 'success',
                    'message' => $this->with['message'],
                    'result' => $crateModel,
                ]);
            }

            // Redirect for standard form submission
            $this->addMessage('success', 'Data Created Successfully!');
            return redirect($this->routes['index'])->with($this->with['status'], $this->with['message']);
        } catch (ValidationException $e) {
            $this->rollbackFiles();
            // Handle validation errors
            if ($encoded) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Failed!',
                    'errors' => $e->errors(),
                ], 422);
            }

            // Redirect with validation errors for standard form submission
            return redirect()->back()->with('errors',$e->errors())->withInput();
        } catch (Exception $e) {
            $this->rollbackFiles();
            // Handle unexpected exceptions
            if ($encoded) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An unexpected error occurred.',
                    'error' => $e->getMessage(),
                ], 500);
            }
    
            return redirect($this->routes['index'])->with('error', 'An unexpected error occurred.')->withInput();
        }
    }

    public function updateData(bool $isCreator = false, string $creatorKey = 'created_by', bool $encoded = false)
    {
        try {
            // make an update initiative function for reuse!
            $update = function () use ($encoded) {
                $this->request->validate($this->validateInputs);

                // find the data and update this using model
                $updatedData = $this->data->update($this->storableData);

                if ($encoded) {
                    $this->addMessage('success', 'Data Updated Successfully!');
                    return response()->json([
                        'status' => 'success',
                        'message' => $this->with['message'],
                        'result' => $updatedData,
                    ]);
                }

                $this->addMessage('success', 'Data Updated Successfully!');
                return redirect($this->routes['index'])->with($this->with['status'], $this->with['message']);
            };

            if ($isCreator && isset($this->model->{$creatorKey})) {
                if ($this->model->{$creatorKey} == Auth::id() || Helpers::hasRole('admin')) {
                    return $update();
                }
                
                if($encoded){
                    $this->addMessage('error', 'You are not authorized to update this data!');
                    return response()->json(['status' => 'error', 'message' => $this->with['message']]);
                }

                $this->addMessage('error', 'You are not authorized to update this data!');
                return redirect()->route($this->routes['index'])->with($this->with['status'], $this->with['message']);
            }
        } catch (ValidationException $e) {
            $this->rollbackFiles();
            // Handle validation errors
            if ($encoded) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation Failed!',
                    'errors' => $e->errors(),
                ], 422);
            }

            // Redirect with validation errors for standard form submission
            return redirect()->back()->with('errors',$e->errors())->withInput();
        } catch (Exception $e) {
            $this->rollbackFiles();
            // Handle unexpected exceptions
            if ($encoded) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An unexpected error occurred.',
                    'error' => $e->getMessage(),
                ], 500);
            }
    
            return redirect($this->routes['index'])->with('error', 'An unexpected error occurred.')->withInput();
        }
        return $update();
    }

    public function updateStatus(string $key = 'status', ?string $value = null, bool $isCreator = false, string $creatorKey = 'created_by', $encoded = false)
    {
        $update = function () use ($key, $encoded, $value) {
            if(is_bool($this->data->{$key})){
                $this->data->{$key} = !$this->data->{$key};
            } else if(is_string($value)){
                $this->data->{$key} = $value;
            } else {
                $this->data->{$key} = $this->data->{$key} == 'active' ? 'inactive' : 'active';
            }
            
            if($this->data->save()){
                if($encoded){
                    $this->addMessage('success', 'State Updated Successfully!');
                    return response()->json(['status' => 'success', 'message' => $this->with['message']]);
                } else {
                    $this->addMessage('success', 'State Updated Successfully!');
                    return redirect()->route($this->routes['index'])->with($this->with['status'], $this->with['message']);
                }
            }
            
            if($encoded){
                $this->addMessage('error', 'Failed to updated state!');
                return response()->json(['status' => 'success', 'message' => $this->with['message']]);
            } else {
                $this->addMessage('error', 'Failed to updated state!');
                return redirect()->route($this->routes['index'])->with($this->with['status'], $this->with['message']);
            }
        };

        if ($isCreator && isset($this->model->{$creatorKey})) {
            if ($this->data->{$creatorKey} == Auth::id() || Helpers::hasRole('admin')) {
                return $update();
            }
            
            if($encoded){
                $this->addMessage('error', 'You are not authorized to update this data!');
                return response()->json(['status' => 'error', 'message' => $this->with['message']]);
            }

            $this->addMessage('error', 'You are not authorized to update this data!');
            return redirect()->route($this->routes['index'])->with($this->with['status'], $this->with['message']);
        }

        return $update();
    }

    public function addDestroyingFile(string $tableKey, bool $encoded = false)
    {
        if ($encoded && isset($this->data->{$tableKey})) {
            if ($encoded_files = $this->data->{$tableKey}) {
                foreach (json_decode($encoded_files, true) as $file) {
                    $this->unlikableFiles[] = $file;
                }
            }

            return $this;
        }

        if (isset($this->model_data->{$tableKey})) {
            $this->unlikableFiles[] = $this->data->{$tableKey};
        }

        return $this;
    }

    private function destroyingFiles()
    {
        foreach ($this->unlikableFiles as $file) {
            if (file_exists($file)) unlink($file);
        }

        return $this;
    }

    public function destroyData(bool $fileDeletable = false, bool $isCreator = false, string $creatorKey = 'created_by', bool $encoded = false)
    {
        $delete = function () use ($fileDeletable, $encoded) {
            if ($fileDeletable) {
                $this->destroyingFiles();
            }

            $this->data->delete();
            if ($encoded) {
                $this->addMessage('success', 'Data Deleted Successfully!');
                return response()->json(['status' => 'success', 'message' => $this->with['message']]);
            }

            $this->addMessage('success', 'Data Deleted Successfully!');
            return redirect()->route($this->routes['index'])->with($this->with['status'], $this->with['message']);
        };

        if ($isCreator && isset($this->model_data->{$creatorKey})) {
            if ($this->data->{$creatorKey} == Auth::id() ||  Helpers::hasRole('admin')) {
                return $delete();
            }
           
            if($encoded){
                $this->addMessage('error', 'You are not authorized to delete this data!');
                return response()->json(['status' => 'error', 'message' => $this->with['message']]);
            }

            $this->addMessage('error', 'You are not authorized to delete this data!');
            return redirect()->route($this->routes['index'])->with($this->with['status'], $this->with['message']);
        }

        return $delete();
    }

    public function sendMessage(){
        session()->put('multiple',  $this->with['message']);

        return $this;
    }

    public function __destruct(){
        $this->sendMessage();
    }
}



