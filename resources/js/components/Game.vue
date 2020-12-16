<template>
    <div id="game">
        <span class="uuid">{{ this.playerUuid }}</span>
        <span class="game-title">Lin<span class="game-go">go</span></span>
        <div class="game-container" v-if="guessedWords.length">
            <div class="game-row" v-for="(row, r) in playerTurn" :key="r">
                <div class="game-tile" v-for="(tile, t) in guessedWords[r].length" :key="t" :id="r + '' + t">
                    <span class="tile-text">{{ guessedWords[r].charAt(t) }}</span>
                </div>
            </div>
        </div>
        <div class="seperator"></div>
        <div class="input-container" v-if="playerTurn">
            <b-form-input id="answer-input" class="user-input" v-model="userInput" placeholder="answer here" required></b-form-input>
            <b-button id="answer-button" variant="outline-primary" @click="giveAnswer">Guess</b-button>
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
                playerTurn: 0, //the turn of the player
                playerMaxTurns: 5, //max turns allowed for a game
                playerUuid: null
            }
        },
        methods:{
            newGame: function() {
                if(this.playerTurn <= 0) {
                    this.getUuid().then(uuid_response => {
                        this.playerUuid = uuid_response.data;

                        axios.post('api/word/set', { uuid: this.playerUuid }).then(newGame_response => {
                            this.playerTurn = 1;
                            axios.get('api/word/camo/' + this.playerUuid).then(response => {
                                this.guessedWords.push(response.data.camo);
                            });
                        });
                    });
                }
            },
            throwMessage: function(type, text, duration = 1000) {
                this.$notify({
                    group: 'foo',
                    type: type,
                    duration: duration,
                    text: text
                }); 
            },
            getUuid: function() {
                return axios.get('api/word/uuid');
            },
            endGame: function() {
                document.getElementById("answer-button").disabled = true;
                document.getElementById("answer-input").disabled = true;
            },
            giveAnswer: function () {
                if(!this.userInput) {
                    this.throwMessage("error", "You need to fill something in!");
                }
                if(this.userInput.length == this.guessedWords[0].length) { //check length of first word in list
                    axios.post('api/word/guess', { word: this.userInput, uuid: this.playerUuid }).then(response => {
                        this.guessedWords.push(this.userInput);
                        this.playerTurn++;

                        var arrGoods = response.data.good;
                        var arrAlmosts = response.data.almost;

                        console.log('goods: ', arrGoods);
                        console.log('almosts: ', arrAlmosts);

                        this.$nextTick(() => {

                            if(arrGoods.length >= this.guessedWords[0].length && arrAlmosts.length <= 0) {
                                //win
                                this.throwMessage("success", "You won. Congratulations!", -1);
                                this.endGame();
                            }
                            else if(this.playerTurn > 5) {
                                //lost
                                this.throwMessage("error", "You have lost the game. Sad!", -1);
                                this.endGame();
                            }

                            if(arrGoods) {
                                for (let i = 0; i < arrGoods.length; i++) {
                                    var tileId = (this.playerTurn-1) + String(arrGoods[i]);
                                    //console.log('arrGoods: ' + tileId);

                                    //console.log('lekekrjoh: ', document.getElementById(tileId));
                                    document.getElementById(tileId).classList.add('correct-letter');
                                }
                            }
                            if(arrAlmosts) {
                                for (let i = 0; i < arrAlmosts.length; i++) {
                                    var tileId = (this.playerTurn-1) + String(arrAlmosts[i]);
                                    //console.log('arrAlmosts: ' + tileId);

                                    document.getElementById(tileId).classList.add('wrong-position');
                                }
                            }
                        });
                    });
                }
                else {
                    this.throwMessage("error", 'Guessed word needs to be ' + this.guessedWords[0].length + " letters long. Current length: " + this.userInput.length);
                }
            }
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
        position: relative;
    }
    .uuid {
        position: absolute;
        top: 0px;
        right: 10px;
    }
    .game-title {
        font-size: 5rem;
        font-weight: 700;
        margin-bottom: 20px;
        text-align: center;
        font-family: cursive;
    }
    .game-go {
        color: #007bff;
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
        width: 70px;
        height: 70px;
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