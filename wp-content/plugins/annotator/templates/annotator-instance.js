
jQuery(function ($) {
  var element= $('{{annotator_content}}');

  if (element) {
    element.annotator()
           .annotator('setupPlugins', null, {
               Auth:
              {
                  tokenUrl: '/wp-json/annotator/v1/api/token',
                  nonce: '{{nonce}}'
              },
               Store: {
                   prefix: '{{annotator_store_location}}',
               annotationData: {uri: '{{uri}}'},
               loadFromSearch: {uri: '{{uri}}', limit: 200}
             },
               Filter: {
                   addAnnotationFilter: false, // Turn off default annotation filter

               }
           });
  } else {
    throw new Error("OkfnAnnotator: Unable to find a DOM element for selector '{{annotator_content}}'; cannot instantiate the Annotator");
  }

});
