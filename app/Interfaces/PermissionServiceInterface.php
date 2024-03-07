<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface PermissionServiceInterface
{
    public function getRoles(Request $request);

    public function createRole(array $data);

    public function updateRole(array $data, int $id);

    public function deleteRole(int $id);

    public function getPermissions(Request $request);

    public function createPermission(array $data);

    public function updatePermission(Request $request, int $id);

    public function deletePermission(int $id);
}
