<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use PHPUnit\Framework\TestCase;

class TaskEntityTest extends TestCase
{
    public function testCreatedAt(): void
    {
        $task = new Task();
        $date = new \DateTime;
        $task->setCreatedAt($date);
        $testDate = $task->getCreatedAt();

        $this->assertSame($date, $testDate);
    }

    public function testIsDone(): void
    {
        $task = new Task();
        $done = true;
        $task->setIsDone($done);
        $testIsDone = $task->getIsDone();

        $this->assertSame($done, $testIsDone);
    }
}
