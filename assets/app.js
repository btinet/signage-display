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
const scheduleCarousel = document.querySelector('#schedule-slide')

new Carousel(shoutOutCarousel, {
    interval: 30000,
    touch: true
})

new Carousel(mainCarousel, {
    interval: 30000,
    touch: true
})

new Carousel(scheduleCarousel, {
    interval: 20000,
    touch: true
})
// x

