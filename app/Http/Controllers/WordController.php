<?php

namespace App\Http\Controllers;

use app\Word;
use app\Game;

use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function setUuid() {
        $uuid = uniqid();
        return $uuid;
    }
    public function setWord(Request $request) {
        $word = Word::whereRaw('LENGTH(word) <= ?', [8])->inRandomOrder()->first(); //get a random word
        $uuid = $request->input('uuid'); //word that has been guessed

        // echo 'TEST: ' . $uuid . ', ' . 'word: ' . $word;

        Game::create(array('uuid' => $uuid, 'word' => $word->word)); //add word to the db
        return $word->word;
    }
    public function guessWord(Request $request) {
        $word = $request->input('word'); //word that has been guessed
        $uuid = $request->input('uuid'); //uuid

        if($word) {
            $game = Game::where('uuid', $uuid)->first();
            $currentWord = $game->word;

            // echo 'Given word: ' . $word . "\n";
            // echo 'Current word: ' . $currentWord . "\n";

            $arr1 = str_split($currentWord); //the current word of the game split up in the array
            $arr2 = str_split($word); //the word that the player has given split up in the array

            $arr3 = array(); //array with the correct values and positions

            //echo 'Correct: ' . "\n";
            for ($i = 0; $i < count($arr1); $i++) {
                if($arr1[$i] == $arr2[$i]) {
                    array_push($arr3, $i);
                    //echo 'idx: ' . $i . ', value: ' . $arr1[$i] . "\n";
                } 
            }
            //print_r($arr3);
            $arr4 = array();

            //echo 'Almost: ' . "\n";
            foreach (str_split($word) as $position => $letter) {
                // echo 'test: ' . $position . "\n";

                if(strpos($currentWord, $letter) && !in_array($position, $arr3)) {
                    array_push($arr4, $position);
                }
            }
            //print_r($arr4);

            // foreach (str_split($word) as $letter) {

            // } 
        }
        return response()->json([
            'good' => $arr3,
            'almost' => $arr4
        ]);
    }
    public function camouflageWord($uuid) {
        $game = Game::where('uuid', $uuid)->first();
        $word = $game->word;

        $camo = '';
        for ($i = 2; $i < strlen($word); $i++) {
            $camo .= '.';
        }

        $camouflagedWord = substr_replace($word, $camo, 2);
    
        return response()->json([
            'camo' => $camouflagedWord
        ]);
    }
    public function getLength($uuid) {
        $game = Game::where('uuid', $uuid)->first();

        return response()->json([
            'size' => strlen($game->word)
        ]);
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
