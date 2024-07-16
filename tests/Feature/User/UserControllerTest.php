<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\User\CreateUser;
use Tests\Feature\User\GetUser;
use Tests\Feature\User\GetUsers;
use Tests\Feature\User\DeleteUser;
use App\Classes\ApiDocs;

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


    use CreateUser;
    use GetUser;
    use DeleteUser;
    use GetUsers;
}
