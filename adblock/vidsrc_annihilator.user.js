// ==UserScript==
// @name         Vidsrc.cc Absolute AdBlocker (MovieList Edition)
// @namespace    http://tampermonkey.net/
// @version      2.1
// @description  Membasmi total iklan, popup, dan redirect di vidsrc.cc khusus penonton MovieList
// @author       Antigravity
// @match        *://*.vidsrc.cc/*
// @match        *://*.vidsrc.me/*
// @match        *://*.vidsrc.xyz/*
// @match        *://*.vidsrc.net/*
// @run-at       document-start
// @grant        none
// ==/UserScript==

(function() {
    'use strict';

    // 1. Matikan window.open (Mencegah Popup)
    window.open = function() { return null; };
    window.alert = function() { return true; };

    // 2. Bersihkan Overlay Transparan (Clickjacker)
    const cleanOverlays = () => {
        const elements = document.querySelectorAll('div, iframe, a');
        elements.forEach(el => {
            const style = window.getComputedStyle(el);
            if (parseInt(style.zIndex) > 100 && (style.position === 'fixed' || style.position === 'absolute')) {
                console.log('Annihilator: Menghapus overlay iklan!');
                el.remove();
            }
        });
    };

    setInterval(cleanOverlays, 500);

    // 3. Matikan Redirect Otomatis
    window.onbeforeunload = function() { return null; };
    
    // 4. Blokir Event Listener Iklan di Body
    const originalAddEventListener = EventTarget.prototype.addEventListener;
    EventTarget.prototype.addEventListener = function(type, listener, options) {
        if ((type === 'click' || type === 'mousedown') && (this === document.body || this === document)) {
            return;
        }
        return originalAddEventListener.apply(this, arguments);
    };
})();
