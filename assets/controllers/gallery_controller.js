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


    connect() {

        let gallery = new Flickity(this.element, {
            imagesLoaded: true,
            prevNextButtons: false,
            pageDots: false,
            wrapAround: true,
            percentPosition: false,
            resize: false,
            autoPlay: 1000*5
        });

        function init(element){
            element.resize();
            element.reposition();
        }

        init(gallery);
        setInterval(init,1000*10,gallery);

    }

}
