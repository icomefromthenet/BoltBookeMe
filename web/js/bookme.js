(function(serverside, global, $, _, JSON) {


  var bookme = {};
  
 
  // DataTable Column Renders 
  
  bookme.datatable = {};
  bookme.datatable.render = {};
  
  
  bookme.datatable.render = {
    timeslotLength : function ( data, type, row ) {
        if(type === "display") {
            data = data + ' Minutes';    
        }
        
        return data;
    },
    ruleDates : function ( data, type, row ) {
        if(type === "display") {
          data = data.replace('00:00:00','');
        }
       
        return data;
    },
    slotTime : function ( data, type, row ) {
        if(type === "display") {
          data = moment().startOf('day').add(data,'minutes').format('hh:mm:ss A');
        }
       
        return data;
    }
    
    
  };
  
  
  bookme.datatable.button = {
    onRuleDelete : function ( e, dt, node, config ) {
        alert( 'Activated!' );
        //this.disable(); // disable button
    },
    onMemberAdd :  function ( e, dt, node, config ) {
        var data = dt.row().data();
        var sLink = bookme.links.findLink('bookme-worker-create-basic',config.crudLinks);
        
        if(!sLink) {
          alert('Link unknown unable to redirect to new member');
        } else {
          
          window.location = sLink;
            
        }
        
    },
    onMemberEdit :  function ( e, dt, node, config ) {
      var data = dt.row().data();

    }
    
  };
  
  
  bookme.links = {
    findLink: function(sLinkName, aLinks) {
      var sLinkHref = '';
      
      for(var i = 0; i < aLinks.length; i++) {
        if(aLinks[i].rel == sLinkName) {
           sLinkHref = aLinks[i].link;
           break;
        }
      }
      
      return sLinkHref;
    }
    
  }

  

// Expose the getFormValue method to the global object
// (other methods exposed as jQuery functions)
if (!global.bookme) {
  global.bookme = bookme;
  global.console.log('bookme module loaded');
}

})((typeof exports !== 'undefined'),
  ((typeof exports !== 'undefined') ? exports : window),
  ((typeof jQuery !== 'undefined') ? jQuery : { fn: {} }),
  ((typeof _ !== 'undefined') ? _ : null),
  JSON);





