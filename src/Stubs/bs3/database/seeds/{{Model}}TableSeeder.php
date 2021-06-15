<?php

use App\Role;
use App\Permission;
use Illuminate\Database\Seeder;

class {{Model}}TableSeeder extends Seeder
{
    public function run()
    {

        const ADMIN_LABEL      = 'admin';
        const MANAGER_LABEL    = 'manager';
        const USER_LABEL       = 'user';
        const PROFESSIONAL     = 'professional';
        
        /**
         * Permissions
         */
        $permissions = [
            ['grant' => [self::ADMIN_LABEL, self::MANAGER_LABEL, self::USER_LABEL], 'group' => '{{model}}', 'name' => '{{model}}', 'display_name' => 'Ver {{models}}'],
            ['grant' => [self::ADMIN_LABEL, self::MANAGER_LABEL,], 'group' => '{{model}}', 'name' => '{{model}}_create', 'display_name' => 'Criar {{models}}'],
            ['grant' => [self::ADMIN_LABEL, self::MANAGER_LABEL,], 'group' => '{{model}}', 'name' => '{{model}}_update', 'display_name' => 'Atualizar {{models}}' ],
            ['grant' => [self::ADMIN_LABEL, self::MANAGER_LABEL,], 'group' => '{{model}}', 'name' => '{{model}}_delete', 'display_name' => 'Remover {{models}}' ],
            ['grant' => [self::ADMIN_LABEL, self::MANAGER_LABEL,], 'group' => '{{model}}', 'name' => '{{model}}_module', 'display_name' => 'Ver {{models}}' ],
        ];

        foreach($permissions as $key=>$val){
            $permission = Permission::where('name',$val['name'])->first();
            if(!$permission){
                $permission               = new Permission();
                $permission->name         = $val['name'];
                $permission->group        = $val['group'];
                $permission->display_name = $val['display_name'];
                $permission->description  = $val['description'] ?? $val['display_name'];
                $permission->save();
            }
            foreach($val['grant'] as $grant){
                ${$grant}->attachPermission($permission);
            }
        }

    }
}
