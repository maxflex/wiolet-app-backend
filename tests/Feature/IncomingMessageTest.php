<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Message;
use App\Events\{IncomingMessage, FuckYou};

class IncomingMessageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIncomingMessage()
    {
        $message = Message::first();
        event(new IncomingMessage($message));
        $this->assertTrue(true);
    }
}
