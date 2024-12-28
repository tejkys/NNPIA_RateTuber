<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{

    public function test_access_admin_portal_without_login()
    {
        $response = $this->get(route('admin.index'));
        $response->assertSessionHasErrors(['Auth' => 'Authorization required!']);
        $response->assertStatus(302);
    }
    public function test_access_admin_portal_with_login_without_privileges()
    {
        $user = new User([
            'name'=>'test1',
            'password'=>bcrypt('test1'),
            'email' => 'admin@tejkys.eu',
            'ip' => '161.97.116.84'
        ]);
        $user->role = Role::where('name', 'user')->first();
        $response = $this->actingAs($user)->get(route('admin.index'));
        $response->assertStatus(302);
    }
    public function test_access_admin_portal_with_login_with_privileges()
    {
        $user = new User([
            'name'=>'test1',
            'password'=>bcrypt('test1'),
            'email' => 'admin@tejkys.eu',
            'ip' => '161.97.116.84'
        ]);
        $user->role = Role::where('name', 'admin')->first();
        $response = $this->actingAs($user)->get(route('admin.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin');
    }
}
