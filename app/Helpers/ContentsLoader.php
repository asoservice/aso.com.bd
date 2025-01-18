<?php 
namespace App\Helpers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use stdClass;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

final class ContentsLoader
{
    public $data;
    public $model;
    private $dataTable;
    private Request $request;
    public string|int $id = 0;
    private string $fields = '';
    public bool $isData = false;
    private array $storableData = [];
    private array $unlikableFiles = [];
    private array $validateInputs = [];
    private bool $dataTableMake = true;
    private string $baseAssetPath = 'uploads/media/';
    private array $with = ['status' => 'undefined', 'message' => []];
    public array $dataTableRaws = ['rawColumns'=> [], 'columns'=> [], 'labels' => []];
    private array $views = ['index' => '', 'create' => '', 'show' => '', 'edit' => ''];
    private array $routes = ['index' => '', 'create' => '', 'show' => '', 'edit' => '', 'store' => '', 'update' => '', 'destroy' => ''];

    public function addAssetPath(string $path, string $suffix = 'uploads/media/'){
        $this->baseAssetPath = $suffix . $path;
        return $this;
    }

    public function initDataTable(bool $make = true){
        $this->dataTableMake = $make;
        $this->dataTable = DataTables::of($this->model::query());
        
        return $this;
    }

    public function addActionColum(string $label = 'Actions', string $name = 'action', string $key = 'id', bool $isEditable = true, bool $isDeletable = true, $isVisible = false){
        $this->dataTableRaws['labels'][] = $label;
        $this->dataTableRaws['rawColumns'][] = $name;
        $this->dataTableRaws['columns'][] = ['data'=> $name, 'orderable'=> false, 'searchable'=> false];

        $this->dataTable->addColumn($name, function ($row) use($isEditable, $isDeletable, $isVisible, $key) {
            $editable  = $isEditable ? 
                "<a href='{$this->routes['edit']}' class='edit-icon edit-btn' id='edit-{$row->{$key}}'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-edit'><path d='M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7'></path><path d='M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z'></path></svg>
                </a>"
            : '';

            $deletable = $isDeletable ? 
                "<a href='#confirmationModal{$row->id}' data-bs-toggle='modal' class='delete-svg delete-btn' id='delete-{$row->{$key}}'>
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

    public function renderDataTable(){
        if (request()->ajax()) {
            return $this->dataTable->rawColumns($this->dataTableRaws['rawColumns'])->make($this->dataTableMake);
        }

        return view($this->views['index'], ['routes'=> $this->routes, 'columns' => $this->dataTableRaws['columns'], 'labels' => $this->dataTableRaws['labels']]);
    }

    public function addViews(string $viewFolder, $viewFileTypes = ['index', 'create', 'show', 'edit', 'form'])
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
        foreach ($resources as $resource) {
            $routeName = "{$suffix}{$name}.{$resource}";
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
    
    protected function addMessage(string $key, string $message, string $type = 'multiple'): void
    {
        $this->with['status'] = $type;
        $this->with['message'][$key] = $message;
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
        if($value === true){
            $checked = in_array(isset($this->data->{$key}) ? $this->data->{$key} : '', ['on', '1', 'true', true, 1, 'yes', 'y']);
        } else {
            $checked  = in_array((old($key) ?? $value), ['on', '1', 'true', true, 1, 'yes', 'y']);
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
                            <input class='form-control' type='hidden' name='{$key}' value={$newValue}>
                            <input class='form-check-input' type='checkbox' name='{$key}' id='{$key}' value='{$newValue}' {$isRequired} {$isChecked}>
                            <span class='switch-state'></span>
                        </label>
                    </div>
                </div>
            </div>
        ";

        return $this;
    }

    
    public function addSelectInput(string $title, string $key, callable|array $options, ?string $value = null, bool $required = false, string $note = '', bool $multiple = false){
        $newValue  = old($key) ?? $value;
        $isRequired = $required ? 'required' : '';

        $newNote = $this->makeNote($required, $note);
        
        $isMultiple = $multiple ? ['nameKey'=> "{$key}[]", 'multiple'=> 'multiple'] : ['nameKey'=> $key, 'multiple'=> ''];

        $makeOption = function () use ($options, $newValue, $title){
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


        $this->fields .= "
            <div class='form-group row'>
                <label class='col-md-2' for='{$key}'>{$title} {$newNote}</label>
                <div class='col-md-10 error-div select-dropdown'>
                    <select id='blog_categories' {$isRequired} class='custom-select-2 form-control' id='{$key}' search='true'
                        name='{$isMultiple['nameKey']}' data-placeholder='{$title}' {$isMultiple['multiple']}>
                        <option></option>
                        {$makeOption()}
                    </select>
                    <!-- <span class='invalid-feedback d-block' role='alert' id='{$key}-alert'> <strong></strong></span> -->
                </div>
            </div>
        ";

        return $this;
    }

    public function addRequest(Request $request){
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

    public function addData(string $tableKey, ?string $inputName = null, ?string $required = null, NULL|string|int|bool $value = null, string $type = 'input')
    {
        $name = $inputName ?? $tableKey;
        $input = $this->getRequestData($name, $type);
        $defaultValue = $value ?? $input;

        if ($required) {
            $this->validateInputs[$name] = $required;
        }

        $this->storableData[$tableKey] = $defaultValue;
        return $this;
    }

    public function addFile(string $tableKey, ?string $inputName = null, ?string $required = null, string $module = 'images', bool|string $value = false){
        $oldFile = null;
        $name = $inputName ?? $tableKey;
        $path = $this->baseAssetPath . $module . '/';

        if($this->request->hasFile($name)){
            if($value === true && isset($this->data->{$tableKey})){
                $oldFile = $this->data->{$tableKey};
            }

            if($required) $this->validateInputs[$name] = $required;
            $this->storableData[$tableKey] = $this->storeFile($this->request->file($name), $path, $oldFile);
        }
        
        return $this;
    }

    public function addMultipleFiles(string $tableKey, ?string $inputName = null, ?string $required = null, string $module = 'images', bool|string $value = false, bool $encodeAsJson = true) {
        $filePaths = [];
        $name = $inputName ?? $tableKey;
        $path = $this->baseAssetPath . $module . '/';
    
        // Check if files exist in the request
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

    public function storeFile($file, string $path, ?string $oldFile = null)
    {
        $create_path = public_path($path);

        if (!File::isDirectory($create_path)) File::makeDirectory($create_path, 0777, true, true);

        $ext = $file->getClientOriginalExtension();
        $file_name = Carbon::now()->toDateString() . '___' . Str::random() . '.' . $ext;
        $file->move($create_path, $file_name);

        if ($oldFile && file_exists($oldFile)) unlink($oldFile);

        return $path . $file_name;
    } 

    public function addCreator(string $tableKey = 'created_by', ?int $auth_id = null)
    {
        $id = is_null($auth_id) ? Auth::id() : $auth_id;
        $this->storableData[$tableKey] = $id;

        return $this;
    }

    public function addSlug(string $inputName, string $tableKey = 'slug', string $type = 'input')
    {
        $string = $this->getRequestData($inputName, $type);
        $slug = Str::slug($string);

        if ($this->model::where('slug', $slug)->exists()) {
            $counter = 1;

            while ($this->model::where('slug', $slug . '-' . $counter)->exists()) {
                $counter++;
            }

            $slug = $slug . '-' . $counter;
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

    public function formOnly(string $action = 'update'){
        $route = in_array($action, array_keys($this->routes)) ? $this->routes[$action] : null;
        return view($this->views['form'], ['fields' => $this->fields, 'routes'=> $this->routes, 'action'=> $route, 'onlyForm'=> true]);
    }

    public function storeData(bool $encoded = false)
    {
        $create = function () use ($encoded) {
            $this->request->validate($this->validateInputs);
            $crateModel = $this->model::create($this->storableData);

            // make a json response returning data for ajax/api request handling.
            if ($encoded) {
                $this->addMessage('success', 'Data Created Successfully!');
                return response()->json([
                    'status' => 'success',
                    'message' => $this->with['message'],
                    'result' => $crateModel,
                ]);
            }

            $this->addMessage('success', 'Data Created Successfully!');
            return redirect($this->routes['index'])->with($this->with['status'], $this->with['message']);
        };

        return $create();
    }

    public function updateData(bool $isCreator = false, string $creatorKey = 'created_by', bool $encoded = false)
    {
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

        return $update();
    }

    public function updateStatus(string $tableKey = 'status', bool $isCreator = true, string $creatorKey = 'created_by', $encoded = false)
    {
        $update = function () use ($tableKey, $encoded) {
            $key = request('name') ?? $tableKey;
            $this->data->{$key} = !$this->data->{$key};
            $this->data->save();

            if($encoded){
                $this->addMessage('success', 'Data Updated Successfully!');
                return response()->json(['status' => 'success', 'message' => $this->with['message']]);
            } else {
                $this->addMessage('success', 'Data Updated Successfully!');
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

