<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WordTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function setUuid() {
        $response = $this->action('GET', 'WordController@setUuid'); //get because nothing is being pulled from the database
        return $response;
    }

    public function getLength() {
        $response = $this->action('GET', 'WordController@getLength', ['uuid' => '']);
        return $response;
    }

    public function camouflagedWord() {
        $response = $this->action('GET', 'WordController@camouflageWord', ['uuid' => '']);
        return $response;
    }
}
