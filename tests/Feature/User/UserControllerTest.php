<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\User\CreateUser;
use Tests\Feature\User\GetUser;
use Tests\Feature\User\GetUsers;
use Tests\Feature\User\DeleteUser;
use App\Classes\ApiDocs;
use App\Models\User;
class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    

    protected $api_docs;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->createApplication();
        $this->api_docs = new ApiDocs(get_class($this));
    }
    protected function setUp(): void
    {
        parent::setUp();

        $this->testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'phone' => '9876543210',
        ]);
    }
    protected function tearDown(): void{
        $this->testUser->forceDelete();

        parent::tearDown();
    }

    use CreateUser;
    use GetUser;
    use DeleteUser;
    use GetUsers;

    /** @test */
    public function check_setup_and_teardown()
    {
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }
}
