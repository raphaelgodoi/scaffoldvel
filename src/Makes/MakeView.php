<?php

namespace RaphaGodoi\ScaffoldVel\Makes;

use Illuminate\Filesystem\Filesystem;
use RaphaGodoi\ScaffoldVel\Commands\ScaffoldMakeCommand;
use RaphaGodoi\ScaffoldVel\Migrations\SchemaParser;
use RaphaGodoi\ScaffoldVel\Migrations\SyntaxBuilder;

class MakeView
{
    use MakerTrait;

    /**
     * Store scaffold command.
     *
     * @var ScaffoldMakeCommand
     */
    protected $scaffoldCommandObj;

    /**
     * Store property of model
     *
     * @var array
     */
    protected $schemaArray = [];

    /**
     * Create a new instance.
     *
     * @param ScaffoldMakeCommand $scaffoldCommand
     * @param Filesystem $files
     * @param sting $viewName
     * @return void
     */
    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;
    }

    /**
     * Get all property of model
     *
     * @return void
     */
    protected function getSchemaArray()
    {
        // ToDo - schema is required?
        if($this->scaffoldCommandObj->option('schema') != null)
        {
            if ($schema = $this->scaffoldCommandObj->option('schema'))
            {
                return (new SchemaParser)->parse($schema);
            }
        }

        return [];
    }

    /**
     * Start make view.
     *
     * @return void
     */
    public function compileViewStub($stub)
    {
        $meta = $this->scaffoldCommandObj->getMeta();
        $fields = $this->getFields($meta['ui']);
        $stub = str_replace('{{fields}}', $fields, $stub);
        $stub = $this->buildStub($meta, $stub);
        return $stub;
    }

    protected function getFields($ui)
    {
        $stubsFields = $this->getStubFields($ui);
        $schemas = $this->getSchemaArray();
        $metas = $this->scaffoldCommandObj->getMeta();

        $stubs = [];

        foreach ($schemas as $schema)
        {
            $variablesFromField = $this->getVariablesFromField($schema);
            $stubsFieldsAllow = array_keys($stubsFields);
            $fieldType = $variablesFromField['field.type'];

            if(!in_array($fieldType, $stubsFieldsAllow))
            {
                $fieldType = 'default';
            }

            $stub = $stubsFields[$fieldType];

            $stub = $this->buildStub($variablesFromField, $stub);
            $stub = $this->buildStub($metas, $stub);

            $stubs[] = $stub;
        }

        return join('        ', $stubs);
    }

    private function getVariablesFromField($options)
    {
        $data = [];

        $data['field.name'] = $options['name'];
        $data['field.Name'] = ucwords($options['name']);
        $data['field.type'] = @$options['type'];
        $data['field.required'] = @$options['options']['required'];
        $data['field.value.default'] = @$options['options']['default'];

        return $data;
    }
}
