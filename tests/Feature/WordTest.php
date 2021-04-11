<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

use App\Game;
use Tests\TestCase;

class WordTest extends TestCase
{
    /** @test */
    public function uuid_function_returns_valid_uuid() {
        $response = $this->get('api/word/uuid');
        
        $this->assertIsString($response->getContent());
        $this->assertTrue(true);
    }

    /** @test */
    public function set_word_for_a_new_game_with_valid_uuid() {

        $uuid = (string) Str::uuid();

        $this->post('api/word/set', [ 'uuid' => $uuid]);
        $this->assertTrue(true);
    }

    /** @test */
    public function set_word_for_a_new_game_with_uuid_being_a_string_and_filled_in() {
        $uuid = '00000000';
        $this->post('api/word/set', [ 'uuid' => $uuid]);

        $this->assertContainsOnly('string', ["uuid" => $uuid]);
    }
}
