<?php


use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class AccountTest extends TestCase
{

    public function test_login()
    {
        $user = User::whereName('dummy')->first();
        $response = $this->post(route('account.login'), [
            'email' => 'dummy@dummy.cz',
            'password' => 'dummy123',
        ]);
        $this->assertAuthenticatedAs($user);
    }
    public function test_not_active_account_login()
    {
        $user = User::whereName('dummy')->first();
        $user->active = 0;
        $user->save();
        $response = $this->post(route('account.login'), [
            'email' => 'dummy@dummy.cz',
            'password' => 'dummy123',
        ]);
        $response->assertSessionHasErrors(['Auth'=>'Account is disabled!']);
        $user->active = 1;
        $user->save();
    }
    public function test_registration_validators(){
        $response = $this->post(route('account.create'), [
            'nickname' => 'dummy',
            'email' => 'dummy@dummy.cz',
            'password' => 'dummy',
        ]);
        $response->assertSessionHasErrors([
            'nickname'=>'The nickname has already been taken.',
            'email'=>'The email has already been taken.',
            'password'=>'The password must be at least 6 characters.',
        ]);
    }
}
