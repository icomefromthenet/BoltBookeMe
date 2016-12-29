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
    }
    
    
    
  };
  


  
  

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





