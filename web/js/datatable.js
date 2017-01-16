/**
 * Main mixin Custom Callback use in DataTable Plugin
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
    * Bookme.datatable mixin container.
    *
    * @private
    * @type {Object}
    */
    var datatable = {};
    
    /**
    * Bookme.datatable.render mixin container.
    *
    * @private
    * @type {Object}
    */
    var render = {};
    
    /**
    * Bookme.datatable.button mixin container.
    *
    * @private
    * @type {Object}
    */
    var button = {};
    
    /**
    * Bookme.datatable.links mixin container.
    *
    * @private
    * @type {Object}
    */
    var links  = {};
    
    
     /**
     * Initializes the mixin.
     *
     * @static
     * @function init
     * @memberof bookme.datatable
     */
    datatable.init = function () {
        
        var DataTable = $.fn.dataTable;
         
        /* Set the defaults for DataTables initialisation */
        $.extend( true, DataTable.defaults, {
        	dom:
        		"<'row'<'col-sm-6'B><'col-sm-6'f>>" +
        		"<'row'<'col-sm-12'tr>>" +
        		"<'row'<'col-sm-5'i><'col-sm-7'p>>",
        	renderer: 'bootstrap'
        } );
         
        $.extend( true, DataTable.Buttons.defaults, {
         	dom: {
         		container: {
         			className: 'dt-buttons btn-group'
         		},
         		button: {
         			className: 'btn'
         		},
         		collection: {
         			tag: 'ul',
         			className: 'dt-button-collection dropdown-menu',
         			button: {
         				tag: 'li',
         				className: 'dt-button'
         			},
         			buttonLiner: {
         				tag: 'a',
         				className: ''
         			}
         		}
         	}
        });
        
        
        
    };
    
    /**
    * Bind Datatable Config to a plugin instance
    *
    * @function bind
    * @memberof bookme.datatable
    * @param {jQuery} $el       - The element as jquery selector 
    * @param {object} oConfig   - The column datatype
 
    */ 
    datatable.bind = function($el, oConfig) {
        // bind are overriden the first so call it again
        datatable.init();
        
        $el.DataTable(oConfig);
        
    }; 
    
    /**
    * Append minute unit to a timeslor
    *
    * @function timeslotLength
    * @memberof bookme.datatable.render
    * @param {mixed}  data   - The data from the server for this column 
    * @param {string} type   - The column datatype
    * @param {object} row    - The row config for this datatable 
    */  
    render.timeslotLength = function ( data, type, row ) {
        if(type === "display") {
            data = data + ' Minutes';    
        }
        
        return data;
    };
    
    /**
    * Remove time part of date string so rules appear for whole day
    *
    * @function ruleDates
    * @memberof bookme.datatable.render
    * @param {mixed}  data   - The data from the server for this column 
    * @param {string} type   - The column datatype
    * @param {object} row    - The row config for this datatable 
    */  
    render.ruleDates = function ( data, type, row ) {
        if(type === "display") {
          data = data.replace('00:00:00','');
        }
       
        return data;
    };
    
    /**
    * Appends AM/PM to timeslot starting time which defaults to 12 hour
    *
    * @function slotTime
    * @memberof bookme.datatable.render
    * @param {mixed}  data   - The data from the server for this column 
    * @param {string} type   - The column datatype
    * @param {object} row    - The row config for this datatable 
    */ 
    render.slotTime = function ( data, type, row ) {
        if(type === "display") {
          data = moment().startOf('day').add(data,'minutes').format('hh:mm:ss A');
        }
       
        return data;
    }
    
    // Apply Mixin to Datatable Container
    datatable.render = render;
  
    /**
    * Button callback for Rule Table Delete Action
    *
    * @function onRuleDelete
    * @memberof bookme.datatable.button
    */ 
    button.onRuleDelete  = function ( e, dt, node, config ) {
       
    
    };
    
    /**
    * Button add new schedule Member Action
    *
    * @function onMemberAdd
    * @memberof bookme.datatable.button
    */ 
    button.onMemberAdd = function ( e, dt, node, config ) {
        var sLink = datatable.links.findLink('bookme-worker-create-basic',config.crudLinks);
        
        if(!sLink) {
          alert('Link unknown unable to redirect to new member');
        } else {
          
          window.location = sLink;
            
        }
        
    };
    
    /**
    * Button callback for Schedule Member Edit Table Delete Action
    *
    * @function onMemberEdit
    * @memberof bookme.datatable.button
    */ 
    button.onMemberEdit = function ( e, dt, node, config ) {
        var data = dt.row().data();
        
        if(data) {
          var sLink = datatable.links.findLink('bookme-worker-edit-basic',data.links);
          
          if(!sLink) {
            alert('Link unknown unable to redirect to edit member');
          } else {
            
            window.location = sLink;
          }
        } else {
          alert('Please select a row to edit');
        }

    };

    // Apply Button mixin to datatabe container
    datatable.button = button;
  
  
    /**
    * Helper to find links with a given name inside
    * button config or ajax data.
    *
    * expects strut like
    * links : [
    *   { rel:sRouteName, link: sFullRoute },
    *   { rel:sRouteName, link: sFullRoute },
    *   { rel:sRouteName, link: sFullRoute },
    * ]
    * 
    * @function findLink
    * @memberof bookme.datatable.links
    * @param {string}   sLinkName   - value of the rel property
    * @param {array}    aLinks      - a collection of link objects
    */ 
    links.findLink = function(sLinkName, aLinks) {
      var sLinkHref = '';
      
      for(var i = 0; i < aLinks.length; i++) {
        if(aLinks[i].rel == sLinkName) {
           sLinkHref = aLinks[i].link;
           break;
        }
      }
      
      return sLinkHref;
    };
    
    // Apply Button mixin to datatabe container
    datatable.links = links;
  

    
    // Apply mixin container
    bookme.datatable = datatable;

   
})(bookme, window, $, _ ,moment);