<?php

use yii\db\Migration;

class m211111_231200_Symbol_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_symbol_index",
            "description" => "backend/symbol/index"
        ],
        "view" => [
            "name" => "backend_symbol_view",
            "description" => "backend/symbol/view"
        ],
        "create" => [
            "name" => "backend_symbol_create",
            "description" => "backend/symbol/create"
        ],
        "update" => [
            "name" => "backend_symbol_update",
            "description" => "backend/symbol/update"
        ],
        "delete" => [
            "name" => "backend_symbol_delete",
            "description" => "backend/symbol/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendSymbolFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendSymbolView" => [
            "index",
            "view"
        ],
        "BackendSymbolEdit" => [
            "update",
            "create",
            "delete"
        ]
    ];
    
    public function up()
    {
        
        $permisions = [];
        $auth = \Yii::$app->authManager;

        /**
         * create permisions for each controller action
         */
        foreach ($this->permisions as $action => $permission) {
            $permisions[$action] = $auth->createPermission($permission['name']);
            $permisions[$action]->description = $permission['description'];
            $auth->add($permisions[$action]);
        }

        /**
         *  create roles
         */
        foreach ($this->roles as $roleName => $actions) {
            $role = $auth->createRole($roleName);
            $auth->add($role);

            /**
             *  to role assign permissions
             */
            foreach ($actions as $action) {
                $auth->addChild($role, $permisions[$action]);
            }
        }
    }

    public function down() {
        $auth = Yii::$app->authManager;

        foreach ($this->roles as $roleName => $actions) {
            $role = $auth->createRole($roleName);
            $auth->remove($role);
        }

        foreach ($this->permisions as $permission) {
            $authItem = $auth->createPermission($permission['name']);
            $auth->remove($authItem);
        }
    }
}
