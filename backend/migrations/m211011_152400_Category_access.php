<?php

use yii\db\Migration;

class m211011_152400_Category_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_category_index",
            "description" => "backend/category/index"
        ],
        "view" => [
            "name" => "backend_category_view",
            "description" => "backend/category/view"
        ],
        "create" => [
            "name" => "backend_category_create",
            "description" => "backend/category/create"
        ],
        "update" => [
            "name" => "backend_category_update",
            "description" => "backend/category/update"
        ],
        "delete" => [
            "name" => "backend_category_delete",
            "description" => "backend/category/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendCategoryFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendCategoryView" => [
            "index",
            "view"
        ],
        "BackendCategoryEdit" => [
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
