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
    public function set_uuid() {

        $response = $this->get('api/word/uuid')->assertStatus(200);
    }

    /** @test */
    public function set_word() {

        $uuid = (string) Str::uuid();

        $response = $this->post('api/word/set', [ 'uuid' => $uuid])->assertStatus(200);
    }

    /** @test */
    public function guess_word() {

        $game = Game::first();

        $response = $this->post('api/word/guess', ['uuid' => $game->uuid, 'word' => $game->word])->assertStatus(200);
    }

    /** @test */
    public function camouflage_word() {

        $uuid = Game::first()->uuid;

        $response = $this->get('api/word/camo/' . $uuid)->assertStatus(200);
    }

    /** @test */
    public function get_length() {

        $uuid = Game::first()->uuid;

        $response = $this->get('api/word/length/' . $uuid)->assertStatus(200);
    }
}
