<?php

use App\Role;
use App\Permission;
use Illuminate\Database\Seeder;

class {{Model}}TableSeeder extends Seeder
{
    public function run()
    {
        /**
         * Permissions
         */
        $permissions = [
            ['group' => '{{model}}', 'name' => '{{model}}', 'display_name' => 'Ver {{models}}'],
            ['group' => '{{model}}', 'name' => '{{model}}_create', 'display_name' => 'Criar {{models}}'],
            ['group' => '{{model}}', 'name' => '{{model}}_update', 'display_name' => 'Atualizar {{models}}' ],
            ['group' => '{{model}}', 'name' => '{{model}}_delete', 'display_name' => 'Remover {{models}}' ]
        ];

        foreach($permissions as $key=>$val){
            $permission               = new Permission();
            $permission->name         = $val['name'];
            $permission->group        = $val['group'];
            $permission->display_name = $val['display_name'];
            $permission->description  = $val['display_name'];
            $permission->save();
        }

    }
}
