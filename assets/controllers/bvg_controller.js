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
        url: String,
        offset: Number
    }

    connect() {

        function getData(url, element,offset){
            const xhr = new XMLHttpRequest();
            let nowDate = Date.now();
            function addMinutes(date, minutes) {
                return new Date(date + minutes*60000);
            }
            if(offset > 0) {
                nowDate = addMinutes(nowDate,offset);
                url = url + "&when=" + nowDate.toISOString();

            }

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

        getData(this.urlValue,this.element,this.offsetValue);
        setInterval(getData,1000*60*2,this.urlValue,this.element,this.offsetValue);
    }

}
