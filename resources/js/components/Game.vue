<template>
    <div id="game">
        <span class="game-title">Lingo</span>
        <div class="game-container">
            <div class="game-row" v-for="row in playerTurn" :key="row">
                <div class="game-tile wrong-position" v-for="tile in word" :key="tile"><span class="tile-text">.</span></div>
            </div>
        </div>
        <div class="seperator"></div>
        <div class="input-container" v-if="playerTurn">
            <b-form-input class="user-input" v-model="userInput" placeholder="answer here"></b-form-input>
            <b-button variant="outline-primary" @click="giveAnswer">Guess</b-button>
        </div>
        <div v-else>
            <b-button variant="outline-primary" @click="newGame">Start Game</b-button>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Game",
        data: function() {
            return {
                guessedWords: new Array(),
                userInput: null, //user's input
                word: null, //the word to be guessed
                playerTurn: 0, //the turn of the player
                playerMaxTurns: 5 //max turns allowed for a game
            }
        },
        methods:{
            newGame: function() {
                if(this.playerTurn <= 0) {
                    axios.post('api/word/set').then(response => {
                        this.playerTurn = 1;
                    });
                }
            },
            giveAnswer: function () {
                if(this.userInput) { //check if input has been given
                    //this.guessedWords.push(this.userInput);
                    axios.get('api/word/get').then(response => {
                        alert(response.data.word);
                        //this.wordLength = this.word.length;
                    });

                    //console.log(this.userInput);
                    // this.$notify({
                    //     group: 'foo',
                    //     type: 'error',
                    //     duration: 1000,
                    //     text: 'You guessed it wrong!'
                    // });
                }
            }
        },
        mounted() {
        }
    }
</script>

<style scoped>
    #game {
        width: 100vw;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }
    .game-title {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-align: center;
    }
    .game-container {
    }
    .game-row {
        display: flex;
    }
    .game-row .game-tile:not(:last-child) {
        margin-right: 15px;
    }
    .game-row:not(:last-child) {
        margin-bottom: 15px;
    }
    .game-tile {
        width: 100px;
        height: 100px;
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0px 1px 3px #8e8e8e;
        border-radius: 5px;
        background-color: #007bff;
        text-align: center;
    }
    .seperator {
        border-bottom: 1px solid #80808061;
        width: 500px;
        margin: 20px;
    }
    .input-container {
        width: 300px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .tile-text {
        color: white;
        text-transform: uppercase;
        font-size: 3rem;
        font-weight: 700;
    }
    .user-input {
        width: 200px;
    }
    .correct-letter {
        background-color: #dc3545;
    }
    .wrong-position span {
        background-color: #ffc107;
        width: 65px;
        height: 65px;
        border-radius: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-content: center;
    }
</style>