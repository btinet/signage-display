import './bootstrap.js';
import {Carousel} from "./vendor/bootstrap/bootstrap.index.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

const myCarouselElement = document.querySelector('#activities-slide')

new Carousel(myCarouselElement, {
    interval: 10000,
    touch: true
})

