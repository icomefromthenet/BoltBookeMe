(function(serverside, global, $, _, JSON) {


  var bookme = {};
  
  
  // Json Form Enhancements
  bookme.form = {}; 
  
  
  bookme.form.inlineTemplate = function(inner) {
      return '<div class="<%= cls.groupClass %> jsonform-node jsonform-error-<%= keydash %>' +
      '<%= elt.htmlClass ? " " + elt.htmlClass : "" %>' +
      '<%= (node.required && node.formElement && (node.formElement.type !== "checkbox") ? " jsonform-required" : "") %>' +
      '<%= (node.isReadOnly() ? " jsonform-readonly" : "") %>' +
      '<%= (node.disabled ? " jsonform-disabled" : "") %>' +
      '" data-jsonform-type="<%= node.formElement.type %>">' +
     
          /* Moving Label Inside Control Group */
          '<% if (node.title && !elt.notitle && elt.inlinetitle !== true) { %>' +
            '<label style="display:inline-block; margin-right:5px;" class="<%= cls.labelClass %>" for="<%= node.id %>"><%= node.title %></label>' +
          '<% } %>' +
        
         
          '<% if (node.prepend || node.append) { %>' +
          '<div class="<%= node.prepend ? cls.prependClass : "" %> ' +
          '<%= node.append ? cls.appendClass : "" %>">' +
         
          '<% if (node.prepend && node.prepend.indexOf("<button ") >= 0) { %>' +
         
            '<% if (cls.buttonAddonClass) { %>' +
              '<span class="<%= cls.buttonAddonClass %>"><%= node.prepend %></span>' +
            '<% } else { %>' +
              '<%= node.prepend %>' +
            '<% } %>' +
         
          '<% } %>' +
         
          '<% if (node.prepend && node.prepend.indexOf("<button ") < 0) { %>' +
            '<span class="<%= cls.addonClass %>"><%= node.prepend %></span>' +
          '<% } %>' +
        
        '<% } %>' +

          '<% if (node.description) { %>' +
          '<span class="help-block jsonform-description"><%= node.description %></span>' +
          '<% } %>' +

       
        inner +
       
       
        '<% if (node.append && node.append.indexOf("<button ") >= 0) { %>' +
       
          '<% if (cls.buttonAddonClass) { %>' +
            '<span class="<%= cls.buttonAddonClass %>"><%= node.append %></span>' +
          '<% } else { %>' +
            '<%= node.append %>' +
          '<% } %>' +
       
        '<% } %>' +
       
        '<% if (node.append && node.append.indexOf("<button ") < 0) { %>' +
          '<span class="<%= cls.addonClass %>"><%= node.append %></span>' +
        '<% } %>' +
       
        '<% if (node.prepend || node.append) { %>' +
          '</div>' +
        '<% } %>' +
       
        '<span class="help-block jsonform-errortext" style="display:none;"></span>' +
      '</div>';
           
    };
      
  // Custom Date Field that uses Jquery UI Date Control
  bookme.form.jDate = {
    'template': 
    '<div class="input-group">' +
      '<span class="input-group-btn">' +
          '<button type="button"  name="jdate_calbtn"  class="btn btn-tertiary">' +
                '<i class="fa fa-calendar"></i> '+
           '</button>'+
         '</span>' +
        '<input type="jdate" ' +
        'class="<%= fieldHtmlClass %> <%= cls.textualInputClass %> datepicker" ' +
        'name="<%= node.name %>" value="<%= escape(value) %>" id="<%= id %>"' +
        '<%= (node.disabled? " disabled" : "")%>' +
        '<%= (node.isReadOnly() ? " readonly=\'readonly\'" : "") %>' +
        '<%= (node.schemaElement && node.schemaElement.maxLength ? " maxlength=\'" + node.schemaElement.maxLength + "\'" : "") %>' +
        '<%= (node.required ? " required=\'required\'" : "") %>' +
        '<%= (node.placeholder? " placeholder=" + \'"\' + escape(node.placeholder) + \'"\' : "")%>' +
        ' />' +
      '</div>',
    'fieldtemplate': true,
    'inputfield': true,
     'onInsert': function(evt, node) {
           
       var $input = $(node.el).find('input[type="jdate"]');
       
       console.log($(node.el));
       
       var opt = {
         dateFormat: "dd-mm-yy"
         
       };
       
        $input.datepicker(opt);
    }
    
  };
  
  // Custom Date Time that uses Jquery UI Date Control
  bookme.form.jDateTime = {
    'template': 
    '<div class="input-group">' +
      '<span class="input-group-btn">' +
          '<button type="button" name="jdate_calbtn" class="btn btn-tertiary">' +
                '<i class="fa fa-calendar"></i> '+
           '</button>'+
         '</span>' +
        '<input type="input" ' +
        'class="<%= fieldHtmlClass %> <%= cls.textualInputClass %> datepicker" ' +
        'name="<%= node.name %>_date" value="" id="<%= id %>_date"' +
        '<%= (node.disabled? " disabled" : "")%>' +
        '<%= (node.isReadOnly() ? " readonly=\'readonly\'" : "") %>' +
        '<%= (node.schemaElement && node.schemaElement.maxLength ? " maxlength=\'" + node.schemaElement.maxLength + "\'" : "") %>' +
        '<%= (node.required ? " required=\'required\'" : "") %>' +
        '<%= (node.placeholder? " placeholder=" + \'"\' + escape(node.placeholder) + \'"\' : "")%>' +
        ' />' +
        
        '<input type="input" ' +
        'class="<%= fieldHtmlClass %> <%= cls.textualInputClass %>" style="width: 93px;" ' +
        'name="<%= node.name %>_time" value="" id="<%= id %>_time"' +
        '<%= (node.disabled? " disabled" : "")%>' +
        '<%= (node.isReadOnly() ? " readonly=\'readonly\'" : "") %>' +
        '<%= (node.schemaElement && node.schemaElement.maxLength ? " maxlength=\'" + node.schemaElement.maxLength + "\'" : "") %>' +
        '<%= (node.required ? " required=\'required\'" : "") %>' +
        '<%= (node.placeholder? " placeholder=" + \'"\' + escape(node.placeholder) + \'"\' : "")%>' +
        ' />' +
        '<input type="hidden" name="<%= node.name %> value="<%= escape(value) %>" id="<%= id %>" />' +
      '</div>',
    'fieldtemplate': true,
    'inputfield': true,
    
     'onInsert': function(evt, node) {
           
      // Start the Datepicker     
           
       var $input = $(node.el).find(':input[name$="_date"]');
       
       var opt = {
         dateFormat: "dd-mm-yy"
         
       };
       
        $input.datepicker(opt);
        
       //bind date picker to button
       $(node.el).find(':input[name="jdate_calbtn"]').click(function(event){
         $input.datepicker('show');
         
       });
       
    },
    
    'onChange': function (event, elt) {
    
      // merge the date and the time element
      console.log(event,elt);
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





