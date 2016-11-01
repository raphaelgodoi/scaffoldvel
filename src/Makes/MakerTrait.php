<?php

namespace RaphaGodoi\ScaffoldVel\Makes;

use stdClass;
use RaphaGodoi\ScaffoldVel\Makes\MakeMigration;
use Illuminate\Filesystem\Filesystem;
use RaphaGodoi\ScaffoldVel\Commands\ScaffoldMakeCommand;

trait MakerTrait
{

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;
    protected $scaffoldCommandObj;

    /**
     * @param ScaffoldMakeCommand $scaffoldCommand
     * @param Filesystem $files
     */
    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;
    }

    protected function getFilesRecursive($path)
    {
        $files = [];
        $scan = array_diff(scandir($path), ['.', '..']);

        foreach ($scan as $file)
        {
            $file = realpath("$path$file");

            if(is_dir($file))
            {
                $files = array_merge
                (
                    $files,
                    $this->getFilesRecursive($file.DIRECTORY_SEPARATOR)
                );
                continue;
            }

            $files[] = $file;
        }

        return $files;
    }

    /**
     * Get stub path.
     *
     * @param $file_name
     * @param string $path
     * @return string
     */
    protected function getStubPath()
    {
        return substr(__DIR__,0, -5) . 'Stubs/';
    }

    /**
     * Get views stubs.
     *
     * @return array views
     */
    protected function getStubViews($ui)
    {
        $viewsPath = $this->getStubPath()."$ui/";

        $files     = $this->getFilesRecursive($viewsPath);
        $viewFiles = [];

        foreach ($files as $file)
        {
            $viewFiles[str_replace($viewsPath, '', $file)] = $file;
        }

        $rootViewsPath = base_path("scaffold/{$ui}/");

        $root_files = $this->getFilesRecursive($rootViewsPath);

        foreach ($root_files as $file) {
            $viewFiles[str_replace($rootViewsPath, '', $file)] = $file;
        }

        return $viewFiles;
    }

    /**
     * Build file replacing metas in template.
     *
     * @param array $metas
     * @param string &$template
     * @return void
     */
    protected function buildStub(array $metas, &$template)
    {
        foreach($metas as $k => $v)
        {
            $template = str_replace("{{". $k ."}}", $v, $template);
        }

        return $template;
    }

    /**
     * Set the file name
     *
     * @return string
     */
    protected function parseFile($stubName, $stubFile)
    {

        $stubName = str_replace('.stub','',$stubName);
        $fileTree = explode('/',$stubName);

        $fileName = end($fileTree);
        $fileType = substr($fileName,0, strpos($fileName,'.'));
        $fileExt  = '.'.substr($fileName, strrpos($fileName, '.') + 1);

        array_pop($fileTree);
        $filePath = implode('/', $fileTree);

        $return = new stdClass();
        $return->path = $filePath;

        $stubFile = $this->getFile($stubFile);

        $return->name = $this->buildNameFor($fileType, $fileExt);
        $return->stub = $this->buildStubFor($fileType, $stubFile);

        return $return;
    }

    /**
     * Build stubs based on type
     *
     * @return string
     */
    protected function buildStubFor($type, $stub)
    {
        switch ($type) {
            case 'migration':
                $migration = new MakeMigration($this, $this->files);
                return $migration->compileMigrationStub($stub);
            break;

            default:
                return $this->buildStub($this->getMeta(),$stub);
            break;
        }
    }

    /**
     * Build name for type
     *
     * @return string;
     */
    protected function buildNameFor($type, $ext)
    {
        $meta = $this->getMeta();

        switch ($type) {
            case 'controller':
                return $meta['Model'].'Controller.php';
            break;

            case 'migration':
                $name = 'create_'.str_plural(strtolower($meta['name'])).'_table';
                return date('Y_m_d_His').'_'.$name.'.php';
            break;

            case 'model':
                return $meta['Model'];
            break;

            case 'seeder':
                return $meta['Models'].'TableSeeder'.$ext;
            break;

            default:
                return $this->buildStub($meta, $type).$ext;
            break;
        }
    }

    protected function getFile($file)
    {
        return $this->files->get($file);
    }

    protected function existsDirectory($path)
    {
        return !$this->files->isDirectory($path);
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if ( ! $this->files->isDirectory(dirname($path)))
        {
            $this->files->makeDirectory(dirname($path), 0755, true, true);
        }
    }
}
