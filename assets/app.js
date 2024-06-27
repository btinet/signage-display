import './bootstrap.js';
import {Carousel} from "./vendor/bootstrap/bootstrap.index.js";
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */


const shoutOutCarousel = document.querySelector('#shout-out-slide')
const mainCarousel = document.querySelector('#main-slide')

new Carousel(shoutOutCarousel, {
    interval: 30000,
    touch: true
})

new Carousel(mainCarousel, {
    interval: 60000,
    touch: true
})

