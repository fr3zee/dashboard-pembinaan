<?php
namespace Tests\Feature;
use App\Models\Pembinaan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
class AuthenticationAuthorizationTest extends TestCase {
 use RefreshDatabase;
 public function test_valid_login_regenerates_session(): void { $user=User::factory()->create(['email'=>'valid@example.com','password'=>Hash::make('correct-password')]); $old=$this->app['session']->getId(); $response=$this->post('/login',['email'=>$user->email,'password'=>'correct-password']); $response->assertRedirect('/dashboard'); $this->assertAuthenticatedAs($user); $this->assertNotSame($old,$this->app['session']->getId()); }
 public function test_invalid_credentials_and_unknown_email_are_rejected(): void { User::factory()->create(['email'=>'known@example.com','password'=>Hash::make('correct-password')]); $this->post('/login',['email'=>'known@example.com','password'=>'wrong-password'])->assertSessionHasErrors('email'); $this->assertGuest(); $this->post('/login',['email'=>'unknown@example.com','password'=>'wrong-password'])->assertSessionHasErrors('email'); $this->assertGuest(); }
 public function test_logout_invalidates_session_and_blocks_dashboard(): void { $this->actingAs(User::factory()->create()); $this->post('/logout')->assertRedirect('/login'); $this->assertGuest(); $this->get('/dashboard')->assertRedirect('/login'); }
 public function test_registration_is_not_available(): void { $this->get('/register')->assertNotFound(); $this->post('/register')->assertNotFound(); }
 public function test_role_defaults_to_pelaksana(): void { $this->assertSame('pelaksana', User::factory()->create()->role); }
 public function test_role_middleware_enforces_access(): void { $admin=User::factory()->role('admin')->create(); $pelaksana=User::factory()->role('pelaksana')->create(); $monitoring=User::factory()->role('monitoring')->create(); $this->actingAs($admin)->get('/admin-check')->assertOk(); $this->actingAs($pelaksana)->get('/admin-check')->assertForbidden(); $this->actingAs($monitoring)->post('/write-check')->assertForbidden(); $this->actingAs($pelaksana)->post('/write-check')->assertOk(); }
 public function test_policy_enforces_pelaksana_ownership_and_viewer_read_only(): void { $owner=User::factory()->role('pelaksana')->create(); $other=User::factory()->role('pelaksana')->create(); $viewer=User::factory()->role('monitoring')->create(); $owned=new Pembinaan(['created_by'=>$owner->id]); $foreign=new Pembinaan(['created_by'=>$other->id]); $policy=new \App\Policies\PembinaanPolicy; $this->assertTrue($policy->update($owner,$owned)); $this->assertFalse($policy->update($other,$owned)); $this->assertFalse($policy->update($viewer,$owned)); $this->assertFalse($policy->delete($owner,$owned)); $this->assertTrue($policy->delete(User::factory()->role('admin')->create(),$owned)); }
}
