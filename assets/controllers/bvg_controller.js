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
        function getData(url, element){
            const xhr = new XMLHttpRequest();
            xhr.open("GET", url,true);
            xhr.send();
            xhr.onload = () => {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    element.innerHTML = xhr.responseText;
                } else {
                    console.log(`Error: ${xhr.status}`);
                }
            };
        }
        getData(this.urlValue,this.element);
        setInterval(getData,1000*60*2,this.urlValue,this.element);
    }

}
