<?php

namespace App\Interfaces;

interface PermissionServiceInterface
{
    public function getRoles();

    public function createRole(array $data);

    public function getRoleByName(string $name);

    public function getRoleById(int $id);

    public function updateRole(int $id, array $data);

    public function deleteRole(int $id);

    public function getPermissions();

    public function createPermission(array $data);

    public function getPermissionByName(string $name);

    public function getPermissionById(int $id);

    public function updatePermission(int $id, array $data);

    public function deletePermission(int $id);
}
