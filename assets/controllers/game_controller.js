import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {

    static targets = ["snake"];


    static values = { refreshInterval: Number }


    connect() {
        this.movable = true;
    }

    startGame() {
        this.startRefreshing()
    }

    startRefreshing() {
        game = document.getElementById('game');
        this.score = game.dataset.score;  
        this.interval = setInterval(() => {
            this.turn()
            // clearInterval(this);
            // this.startRefreshing();
        }, 400)
    }

    turn(event) {
        fetch("/turn")
            .then(response => response.text())
            .then(html => {
                this.element.innerHTML = html;
            })
        this.movable = true;
    }

    move(event) {
        const directions = {
            ArrowUp: 'T',
            ArrowDown: 'B',
            ArrowLeft: 'L',
            ArrowRight: 'R',
        }
        if (this.movable) {
            fetch("/direction/" + directions[event.key])
            this.movable = false;
        }
    }

}