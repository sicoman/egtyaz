<?php

namespace EzuruCustom\Core\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\DB;
use Artisan;
 
class GenerateCommand extends Command
{
    protected $seedersPath = __DIR__.'/../../publishable/database/seeds/';
    protected $migrationsPath = __DIR__.'/../../publishable/database/migrations/';
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'Ezuru:api:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'EzuruCustom package';

    protected function getOptions()
    {
        return [
            ['with-dummy', null, InputOption::VALUE_NONE, 'Install with dummy data', null],
        ];
    }

    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }

        return 'composer';
    }

    public function fire(Filesystem $filesystem)
    {   
        return $this->handle($filesystem);
    }

    protected function beforeInit(){
        
        $schema = app_path('GraphQl/');

        if (!is_dir($schema)) {
            mkdir($schema);
        }

        foreach (["Enums", "Mutation", "Query", "Type"] as $dir) {
            if (!is_dir($schema . "/" . $dir)) {
                mkdir($schema . "/" . $dir);
            }
        }
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
      //   $this->info('');
        
       // $this->call('vendor:publish', ['--provider' => "Spatie\Activitylog\ActivitylogServiceProvider", '--tag' => "migrations"]);

        //Publish only relevant resources on install
        $tags = ['seeds'];

        $this->beforeInit();

        $app = app_path() ; 

        $models = glob($app . '/Models/*.php');

        $replacements = [
            'varchar' => 'Type::string()',
            'string' => 'Type::string()',
            'id' => 'Type::ID()',
            'integer' => 'Type::int()',
            'smallint' => 'Type::int()',
            'bigint' => 'Type::int()',
            'text' => 'Type::string()',
            'timestamp' => 'Type::string()',
            'datetime' => 'Type::string()',
            'time' => 'Type::string()',
            'date' => 'Type::string()',
            'tinyint' => 'Type::boolean()',
            'boolean' => 'Type::boolean()',
            'float' => 'Type::float()',
            'decimal' => 'Type::float()',
            'enum' => '',
            'point' => 'Type::string()'
        ];

        $path = app_path('GraphQl');
        if(!empty($models)){
            foreach($models as $model){
                $resolve = '';
                $model = pathinfo($model);
                $modelName = $model['filename'] ;
                if($modelName == "Basic")
                    continue ;
                
                $modelObj = app("App\Models\\{$modelName}") ;    
                $fields = $modelObj->getTableColumns() ;   
                $filterRows = $filterNames = [];
                $fieldsRows = [];
                foreach($fields as $field){
                  $type =  DB::getSchemaBuilder()->getColumnType($modelObj->getTable(), $field);
                  $fieldsRows[] = "'{$field}' => [
                    'type' => $replacements[$type],
                    {$resolve}    
                ]\n";
                }

                if($modelObj->relatable){
                    $this->appendRelations($fieldsRows, $modelObj->relatable, $filterRows, $filterNames);
                }

                $filterRowsString = implode(',', $filterRows);
                $filterNamesString = json_encode($filterNames);
                $this->createTypeStub($modelName, 'query', $path, 'Query', 'Query',[],$modelObj);
                $this->createTypeStub($modelName, 'mutation', $path, 'Mutation', 'Mutation',[],$modelObj);
                $this->createTypeStub($modelName, 'queryType', $path, 'QueryType', 'Type',[],$modelObj);
                $this->createTypeStub($modelName, 'mutationType', $path, 'MutationType', 'Type',[],$modelObj);
                $this->createTypeStub($modelName, 'type', $path, 'Type', 'Type', ['{$values}' => join(',', $fieldsRows)],$modelObj);
                $this->createTypeStub($modelName, 'input', $path, 'Input', 'Type', ['{$values}' => "explode(',','{$filterRowsString}')",'{$inputSpecific}'=> "{$filterNamesString}"],$modelObj);
                $this->createTypeStub($modelName, 'response', $path, 'Response', 'Type', ['{$isMulti}' => "true"],$modelObj);
                $this->createTypeStub($modelName, 'response', $path, 'SingleResponse', 'Type', ['{$isMulti}' => "false"],$modelObj);
            }
        }

    }

    public function appendRelations(array &$fields = [], $relatable, &$filterRows, &$filterNames){

        foreach($relatable as $type => $related){
            switch($type){
                case 'has-many':
                foreach($related as $key => $relation){
                    $name = last(explode('\\', $relation));
                    $plural = str_plural($name);
                    $fields[] = "'{$plural}' => [
                        'type' => Type::listOf(GraphQL::type('{$name}Type')),
                        'resolve' => function(\$object){
                            return \$object->$key()->get();
                        }
                        
                    ]\n";
                    $filterRows[] = $plural;
                }
                break;
                case 'belongs-to':
                foreach($related as $key => $relation){
                    $name = last(explode('\\', $relation));
                    $plural = str_plural($name);  
                    $fields[] = "'{$name}' => [
                        'type' => GraphQL::type('{$name}Type'),
                        'resolve' => function(\$object){
                            return \$object->$key()->first();
                        }
                        
                    ]\n";
                    $filterRows[] = $name;
                }
                break;
                case 'many-to-many':
                foreach($related as $key => $relation){
                    $name = last(explode('\\', $relation));
                    $plural = str_plural($name);
                    $fields[] = "'{$plural}' => [
                        'type' => Type::listOf(GraphQL::type('{$name}Type')),
                        'resolve' => function(\$object){
                            return \$object->$key()->get();
                        }
                        
                    ]\n";
                    $filterRows[] = $plural;
                    $filterNames[$name] = ['name' => $name, 'plural' => $plural,'type' => '_mtm_'] ;
                }
                break;
            }
        }

    }


    public function createTypeStub($modelName, $stubName, $path, $stubType, $dir, $others = [], $modelObj = null) {

        $queryType = __DIR__ . "/../../stubs/{$stubName}.stub";

        $dismiss = array_merge(['DummyClass', '{$modelName}', '{$name}', '{$description}', '{$response}', '{$single}'], array_keys($others));

        $replacements = array_merge([
            $modelName. $stubType,
            $modelName,
            $modelName,
            $modelName . ' Description',
            $modelName . 'Response',
            $modelName . 'SingleResponse'
                ], array_values($others));
       
        if($stubType == "MutationType"){
            $validationContent = '';
            if($modelObj && $modelObj->rules){
                array_push($dismiss, '{$validationSegment}');
                $validationContent .= ' $validator = Validator::make($'.$modelName.', [';
                $rules = [];
                foreach($modelObj->rules as $rule){
                    $rules[] = "'{$rule}' => 'required'\n";
                }
                $validationContent .= implode(',', $rules);
                $validationContent .= ']);';
                $validationContent  = "{$validationContent}\nif(\$validator->fails()){\n return \$this->resolveErrors(\$validator->errors()->all()); \n}";
                array_push($replacements, $validationContent);
            }else{
                array_push($dismiss, '{$validationSegment}');
                array_push($replacements, '');
            }   
        }     
        
        $content = str_replace($dismiss, $replacements, file_get_contents($queryType));

        file_put_contents($path . "//{$dir}/" . $modelName . "{$stubType}.php", $content);
    }

}
  