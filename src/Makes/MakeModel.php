<?php

namespace RaphaGodoi\ScaffoldVel\Makes;

use Illuminate\Filesystem\Filesystem;
use RaphaGodoi\ScaffoldVel\Commands\ScaffoldMakeCommand;
use RaphaGodoi\ScaffoldVel\Migrations\SchemaParser;
use RaphaGodoi\ScaffoldVel\Migrations\SyntaxBuilder;

class MakeModel
{
    use MakerTrait;

    /**
     * Create a new instance.
     *
     * @param ScaffoldMakeCommand $scaffoldCommand
     * @param Filesystem $files
     * @return void
     */
    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;
    }

    /**
     * Compile the migration stub.
     *
     * @return string
     */
    public function compileModelStub($stub)
    {
        $this->buildStub($this->scaffoldCommandObj->getMeta(), $stub);
        $this->buildFillable($stub);

        return $stub;
    }

    /**
     * Build stub replacing the variable template.
     *
     * @return string
     */
    protected function buildFillable(&$stub)
    {
        $schemaArray = [];

        $schema = $this->scaffoldCommandObj->getMeta()['schema'];

        if ($schema)
        {
            $items = (new SchemaParser)->parse($schema);
            foreach($items as $item)
            {
                $schemaArray[] = "'{$item['name']}'";
            }

            $schemaArray = join(", ", $schemaArray);
        }

        $stub = str_replace('{{fillable}}', $schemaArray, $stub);

        return $this;
    }
}
