<?php

namespace App\Http\Controllers;
use App\Word;
use App\Game;

use Illuminate\Http\Request;

class WordController extends Controller
{
    public function setUuid() {
        $uuid = uniqid();
        return $uuid;
    }
    public function setWord(Request $request) {
        $word = Word::inRandomOrder()->limit(1)->value('word'); //get a random word
        $uuid = $request->input('uuid'); //word that has been guessed

        // echo 'TEST: ' . $uuid . ', ' . 'word: ' . $word;

        Game::create(array('uuid' => $uuid, 'word' => $word)); //there is no word selected, add it to the db
        return response('Word has been chosen, nothing to see here.', 200);
    }
    public function guessWord(Request $request) {
        $word = $request->input('word'); //word that has been guessed
        if($word) {
            $currentWord = Game::find(1)->value('word');

            echo 'Given word: ' . $word . "\n";
            echo 'Current word: ' . $currentWord . "\n";

            $arr1 = str_split($currentWord); //the current word of the game split up in the array
            $arr2 = str_split($word); //the word that the player has given split up in the array
            $arr3 = array(); //array with the correct values and positions
            echo 'Correct: ' . "\n";
            for ($i = 0; $i < count($arr1); $i++) { 
                if($arr1[$i] == $arr2[$i]) {
                    array_push($arr3, [$i, $arr1[$i]]);
                    //echo 'idx: ' . $i . ', value: ' . $arr1[$i] . "\n";
                } 
            }
            print_r($arr3);

            $arr4 = array();
            echo 'Almost: ' . "\n";
            foreach (str_split($word) as $letter) {
                $position = strpos ($currentWord, $letter);
                if($position !== false && !in_array([$position, $letter], $arr3)) {
                    array_push($arr4, [$position, $letter]);
                    // echo 'Letter: ' . $letter;
                    // echo ' in pos: ' . $position . "\n";
                }
            }
            print_r($arr4);

            foreach (str_split($word) as $letter) {

            } 
        }
    }
    public function deleteGame(Request $request) { //delete a game after it has been played
        $gameId = Game::where('id', $request->input('id'))->first();

        $game = subHeader::find($gameId);
        return $game->delete();
    }
    // public function getWord() {
    //     $word = Game::find(1);
    //     return $word;
    // }
}
