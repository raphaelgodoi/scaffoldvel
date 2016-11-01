<?php

namespace RaphaGodoi\ScaffoldVel\Makes;

use Illuminate\Filesystem\Filesystem;
use RaphaGodoi\ScaffoldVel\Commands\ScaffoldMakeCommand;
use RaphaGodoi\ScaffoldVel\Migrations\SchemaParser;
use RaphaGodoi\ScaffoldVel\Migrations\SyntaxBuilder;

class MakeMigration
{
    use MakerTrait;

    /**
     * Store scaffold command.
     *
     * @var ScaffoldMakeCommand
     */
    protected $scaffoldCommandObj;

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
    public function compileMigrationStub($stub)
    {
        $this->replaceSchema($stub);
        $this->buildStub($this->scaffoldCommandObj->getMeta(), $stub);

        return $stub;
    }

    /**
     * Replace the schema for the stub.
     *
     * @param  string $stub
     * @param string $type
     * @return $this
     */
    protected function replaceSchema(&$stub)
    {
        if ($schema = $this->scaffoldCommandObj->getMeta()['schema'])
        {
            $schema = (new SchemaParser)->parse($schema);
        }

        $schema = (new SyntaxBuilder)->create($schema, $this->scaffoldCommandObj->getMeta());
        $stub = str_replace(['{{schema_up}}', '{{schema_down}}'], $schema, $stub);

        return $this;
    }
}
