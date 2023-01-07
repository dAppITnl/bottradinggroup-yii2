<?php

use yii\db\Migration;

class m211020_122500_Pricelist_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_pricelist_index",
            "description" => "backend/pricelist/index"
        ],
        "view" => [
            "name" => "backend_pricelist_view",
            "description" => "backend/pricelist/view"
        ],
        "create" => [
            "name" => "backend_pricelist_create",
            "description" => "backend/pricelist/create"
        ],
        "update" => [
            "name" => "backend_pricelist_update",
            "description" => "backend/pricelist/update"
        ],
        "delete" => [
            "name" => "backend_pricelist_delete",
            "description" => "backend/pricelist/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendPricelistFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendPricelistView" => [
            "index",
            "view"
        ],
        "BackendPricelistEdit" => [
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
