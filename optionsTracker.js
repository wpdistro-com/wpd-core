jQuery(document).ready(function() {
  jQuery('#options_form>input').not(':input[type=button], :input[type=submit], :input[type=reset]').each(function() {
          var elem = jQuery(this);
          // Save current value of element
          elem.data('oldVal', elem.val());
          // Look for changes in the value
          elem.bind("propertychange change click keyup input paste", function(event) {
                  // If value has changed...
                  if (elem.data('oldVal') != elem.val()) {
                      // Updated stored value
                      elem.data('changed', true);
                      elem.data('oldVal', elem.val());

                      // Do action
                  }
                  return true;
              }
          );
      }
  );
}

);

function submit_form(element, form) {
  jQuery('#options_form>input').each(function() {
    if (!jQuery(this).data('changed')) {
        jQuery(this).prop("disabled", true);
    }
  });
  jQuery("input[name='formaction']").prop("disabled", false);
  form['formaction'].value = element.name;
  form.submit();
}