<?php

declare(strict_types=1);

namespace App\Tests\Unit\SubRegion\Action\Update;

use App\SubRegion\Action\Update\UpdateSubRegionActionRequest;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class UpdateSubRegionActionRequestTest extends TestCase
{
    public function testValidInstantiation(): void
    {
        $id = Uuid::uuid4();
        $regionTitle = 'Europe';
        $title = 'Eastern Europe';

        $actual = new UpdateSubRegionActionRequest();
        $actual->setId($id->toString());
        $actual->title = $title;
        $actual->regionTitle = $regionTitle;

        $this->assertEquals($id, $actual->id);
        $this->assertEquals($title, $actual->title);
        $this->assertEquals($regionTitle, $actual->regionTitle);
    }
}
