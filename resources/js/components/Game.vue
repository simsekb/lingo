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
                guessedWords: new Array(), //array met de gerade woorden
                userInput: null, //user's input
                playerTurn: 0, //the turn of the player
                playerMaxTurns: 5, //max turns allowed for a game
                playerUuid: null //user uuid generated in the beginning of each game
            }
        },
        methods:{
            newGame: function() { //maak nieuw spel aan met toegewezen uuid
                if(this.playerTurn <= 0) {
                    this.getUuid().then(uuid_response => { //creeër een nieuw uuid
                        this.playerUuid = uuid_response.data; //wijs uuid toe aan de speler

                        axios.post('api/word/set', { uuid: this.playerUuid }).then(newGame_response => { //zet het woord van het spel
                            this.playerTurn = 1;
                            axios.get('api/word/camo/' + this.playerUuid).then(response => { //plaats op de eerste regel het gecamoufleerde woord (geef hint)
                                this.guessedWords.push(response.data.camo); //voeg woord toe aan array
                            });
                        });
                    });
                }
            },
            throwMessage: function(type, text, duration = 1000) { //functie om de notificaties mee te beheren
                this.$notify({
                    group: 'foo',
                    type: type,
                    duration: duration,
                    text: text
                }); 
            },
            getUuid: function() { //haal de uuid van een speler op
                return axios.get('api/word/uuid');
            },
            endGame: function() { //einde van het spel
                document.getElementById("answer-button").disabled = true;
                document.getElementById("answer-input").disabled = true;
            },
            giveAnswer: function () { //speler geeft antwoord, antwoord wordt geprojecteerd op het bord met de logica erachter
                if(!this.userInput) {
                    this.throwMessage("error", "You need to fill something in!");
                }
                if(this.userInput.length == this.guessedWords[0].length) { //check length of first word in list
                    /*
                     * Er wordt een wordt een post request gestuurd naar de backend, daar wordt het request afgehandeld zodat er in de frontend zich geen gevoelige informatie bevind
                     */
                    axios.post('api/word/guess', { word: this.userInput, uuid: this.playerUuid }).then(response => {
                        this.guessedWords.push(this.userInput); //het geraden woord wordt toegevoegd in een array
                        this.playerTurn++; //beurt van de speler dat omhoog gaat na het raden

                        var arrGoods = response.data.good; //array met de response van goed geraden posities
                        var arrAlmosts = response.data.almost; //array met de response van bijna goed geraden posities

                        this.$nextTick(() => { //wacht totdat de elementen eerst ingeladen zijn, neem dan pas actie

                            if(arrGoods.length >= this.guessedWords[0].length && arrAlmosts.length <= 0) { //gebruiker wint het spel na het goed raden van het woord
                                this.throwMessage("success", "You won. Congratulations!", -1);
                                this.endGame();
                            }
                            else if(this.playerTurn > 5) { //gebruiker verliest het spel na 5 beurten het woord niet geraden te hebben
                                this.throwMessage("error", "You have lost the game. Sad!", -1);
                                this.endGame();
                            }

                            if(arrGoods) {
                                for (let i = 0; i < arrGoods.length; i++) {
                                    var tileId = (this.playerTurn-1) + String(arrGoods[i]); //id van de tiles

                                    document.getElementById(tileId).classList.add('correct-letter'); //class wordt toegewezen aan tile dat correct is
                                }
                            }
                            if(arrAlmosts) {
                                for (let i = 0; i < arrAlmosts.length; i++) {
                                    var tileId = (this.playerTurn-1) + String(arrAlmosts[i]); //id van de tiles

                                    document.getElementById(tileId).classList.add('wrong-position'); //class wordt toegewezen aan tile dat bijna correct is
                                }
                            }
                        });
                    });
                }
                else {
                    //de gebruiker krijgt een foutmelding na het invoeren van te weinig characters van het woord
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
        text-transform: lowercase;
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