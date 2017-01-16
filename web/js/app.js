/**
 * Main mixin for the BookMe Application
 * 
 * @mixin
 * @namespace bookme.app
 *
 * @param {Object} bookme - The BookMe module.
 * @param {Object} window - The browser window.
 * @param {Object} $      - jQuery.
 * @param {Object} _      - underscore js library
 * @param {Object} moment - moment.js.
 */
(function (bookme, window, $, _ ,moment) {
    'use strict';


    
    /**
    * Bookme.app mixin container.
    *
    * @private
    * @type {Object}
    */
    var app = {};

    
    
     /**
     * Initializes globals.
     *
     * @private
     * @function initGlobal
     * @memberof bookme.app
     */
    function initGlobal() {
        
       
      
      
    }
  
   

    /**
     * Initializes and then starts the Bolt module.
     * Is automatically executed on jQueries ``$(document).ready()``.
     *
     * @function run
     * @memberof bookme.app
     */
    app.run = function () {
        
        initGlobal();
        
        
        bookme.datatable.init();
    };




    /*
    * Start when ready.
    */
    $(document).ready(app.run);
    
    // Apply mixin container
    bookme.app = app;

   
})(bookme, window, $, _ ,moment);

