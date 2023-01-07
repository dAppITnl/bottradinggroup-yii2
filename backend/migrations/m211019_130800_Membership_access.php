<?php

use yii\db\Migration;

class m211019_130800_Membership_access extends Migration
{
    /**
     * @var array controller all actions
     */
    public $permisions = [
        "index" => [
            "name" => "backend_membership_index",
            "description" => "backend/membership/index"
        ],
        "view" => [
            "name" => "backend_membership_view",
            "description" => "backend/membership/view"
        ],
        "create" => [
            "name" => "backend_membership_create",
            "description" => "backend/membership/create"
        ],
        "update" => [
            "name" => "backend_membership_update",
            "description" => "backend/membership/update"
        ],
        "delete" => [
            "name" => "backend_membership_delete",
            "description" => "backend/membership/delete"
        ]
    ];
    
    /**
     * @var array roles and maping to actions/permisions
     */
    public $roles = [
        "BackendMembershipFull" => [
            "index",
            "view",
            "create",
            "update",
            "delete"
        ],
        "BackendMembershipView" => [
            "index",
            "view"
        ],
        "BackendMembershipEdit" => [
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
