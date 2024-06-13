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

    static _targets = [ "date", "time" ,"schedule"]


    connect() {}

    timeTargetConnected(element) {
        let date;
        function currentTime() {
            date = new Date();
            element.innerText = date.toLocaleString("de-DE", { timeZone: "Europe/Berlin", timeStyle: "short"});
        }
        currentTime();
        setInterval(currentTime,1000);
    }

    dateTargetConnected(element) {
        let date;
        function currentTime() {
            date = new Date();
            element.innerText = date.toLocaleString("de-DE", { timeZone: "Europe/Berlin", day:"2-digit",month:"2-digit",year:"numeric"});
        }
        currentTime();
        setInterval(currentTime,1000*60);
    }

    scheduleTargetConnected(element) {
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                element.innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "ajax_info.txt", true);
        xhttp.send();
    }

}
