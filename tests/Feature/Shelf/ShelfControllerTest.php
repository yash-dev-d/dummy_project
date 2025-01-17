<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\Feature\Shelf\CreateShelf;
use Tests\Feature\Shelf\GetShelf;
use Tests\Feature\Shelf\AssignShelf;
use Tests\Feature\Shelf\GetShelves;
use App\Classes\ApiDocs;

class ShelfControllerTest extends TestCase
{
    use RefreshDatabase;
    

    protected $api_docs;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->createApplication();
        $this->api_docs = new ApiDocs(get_class($this));
    }

    protected function tearDown(): void{
        parent::tearDown();
      }

    use CreateShelf;
    use GetShelf;
    use AssignShelf;
    use GetShelves;

}

