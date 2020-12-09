<template>
    <div id="game">
        <span class="game-title">Lingo</span>
        <div class="game-container">
            <div class="game-row" v-for="row in playerTurn" :key="row">
                <div class="game-tile wrong-position" v-for="tile in word.length" :key="tile"><span class="tile-text">.</span></div>
            </div>
        </div>
        <div class="seperator"></div>
        <div class="input-container">
            <b-form-input class="user-input" v-model="userInput" placeholder="answer here"></b-form-input>
            <b-button variant="outline-primary" @click="giveAnswer">Guess</b-button>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Game",
        data: function() {
            return {
                userInput: null, //user's input
                word: "nadir", //the word to be guessed
                playerTurn: null, //the turn of the player
                playerMaxTurns: 5 //max turns allowed for a game
            }
        },
        methods:{
            giveAnswer: function () {
                if(this.userInput) { //check if input has been given
                    console.log(this.userInput);
                    this.$notify({
                        group: 'foo',
                        type: 'error',
                        duration: 1000,
                        text: 'You guessed it wrong!'
                    });
                }
            }
        },
        mounted() {
            this.wordLength = 5; //testing purpose
            this.playerTurn = 1; //testing purpose
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