/**
 * Main mixin List.js Plugin
 * 
 * @mixin
 * @namespace bookme.datatable
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
    * Bookme.list mixin container.
    *
    * @private
    * @type {Object}
    */
    var list = {};
    
    
    
     /**
     * Initializes the mixin.
     * 
     * Looks div with a class of .bm-list
     *
     * @static
     * @function init
     * @memberof bookme.list
     */
    list.init = function () {
        
        $('div.bm-list').each(function(){
          
          
          
        });
        
        
        
    };
    
    /**
    * Bind Datatable Config to a plugin instance
    *
    * @function bind
    * @memberof bookme.list
    * @param {jQuery} $el       - The element as jquery selector 
    * @param {object} oConfig   - The column datatype
 
    */ 
    list.bind = function($el, oConfig) {
        
        
        
    }; 
    
   
  

    
    // Apply mixin container
    bookme.list = list;

   
})(bookme, window, $, _ ,moment);