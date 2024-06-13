import { Controller } from '@hotwired/stimulus';

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * time_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {

    static values = {
        url: String
    }

    connect() {
        console.log("LOS BVG");
        const xhr = new XMLHttpRequest();
        xhr.open("GET", this.urlValue,true);
        xhr.send();
        xhr.onload = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                this.element.innerHTML = xhr.responseText;
            } else {
                console.log(`Error: ${xhr.status}`);
            }
        };
    }

}
