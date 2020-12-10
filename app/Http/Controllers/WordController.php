<?php

namespace App\Http\Controllers;
use App\Word;
use App\Game;

use Illuminate\Http\Request;

class WordController extends Controller
{
    public function setWord() {
        $word = Word::inRandomOrder()->limit(1)->value('word'); //get a random word

        $wordRow = Game::find(1);
        if($wordRow) {
            $wordRow->word = $word;
            $wordRow->save();  
        }
        else {
            Game::create(array('word' => $word));
        }
        return response('Word has been chosen, nothing to see here.', 200);
    }
    public function getWord() {
        $word = Game::find(1);
        return $word;
    }
}
