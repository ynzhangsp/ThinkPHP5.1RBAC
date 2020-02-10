jQuery.fn.jcOnPageFilter = function(settings) {
                    settings = jQuery.extend({
                    animateHideNShow: false,
                    focusOnLoad:false,
                    highlightColor:'yellow',
                    textColorForHighlights:'#000000',
                    caseSensitive:false,
                    hideNegatives:false,
                    parentLookupClass:'jcorgFilterTextParent',
                    childBlockClass:'jcorgFilterTextChild'
                }, settings);
                jQuery.expr[':'].icontains = function(obj, index, meta) {                    
                    return jQuery(obj).text().toUpperCase().indexOf(meta[3].toUpperCase()) >= 0;                
                }; 
                if(settings.focusOnLoad) {
                  jQuery(this).focus();
                }
                var rex = /(<span.+?>)(.+?)(<\/span>)/g;
                var rexAtt = "g";
                if(!settings.caseSensitive) {
                   rex = /(<span.+?>)(.+?)(<\/span>)/gi;
                   rexAtt = "gi";
                }
                                    
                return this.each(function() {
                    jQuery(this).keyup(function(e) {
                      if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                        return false;
                      }
                      else {
                            var textToFilter = jQuery(this).val();
                            if (textToFilter.length > 0) {
                                if(settings.hideNegatives) {
                                  if(settings.animateHideNShow) {
                                    jQuery('.'+settings.parentLookupClass).stop(true, true).fadeOut('slow');
                                  }
                                  else {
                                    jQuery('.'+settings.parentLookupClass).stop(true, true).hide();
                                  }
                                }
                                var _cs = "icontains";
                                if(settings.caseSensitive) {
                                  _cs = "contains";
                                }
                                jQuery.each(jQuery('.'+settings.childBlockClass),function(i,obj) {
                                  jQuery(obj).html(jQuery(obj).html().replace(new RegExp(rex), "$2"));  
                                });
                                      

                                jQuery.each(jQuery('.'+settings.childBlockClass+":"+_cs+"(" + textToFilter + ")"),function(i,obj) {
                                    if(settings.hideNegatives) {
                                      if(settings.animateHideNShow) {
                                        jQuery(obj).parent().stop(true, true).fadeIn('slow');
                                      }
                                      else {
                                        jQuery(obj).parent().stop(true, true).show();
                                      }
                                      
                                    }
                                    var newhtml = jQuery(obj).html();
                                    jQuery(obj).html(newhtml.replace(
                                                                      new RegExp(textToFilter, rexAtt), 
                                                                      function(match) {
                                                                        return ["<span style='background:"+settings.highlightColor+";color:"+settings.textColorForHighlights+"'>", match, "</span>"].join("");
                                                                      }));                                    
                                  
                                });
                                
                            } else {
                              jQuery.each(jQuery('.'+settings.childBlockClass),function(i,obj) {
                                    var html = jQuery(obj).html().replace(new RegExp(rex), "$2");
                                    jQuery(obj).html(html);  
                              });
                              if(settings.hideNegatives) {
                                if(settings.animateHideNShow) {
                                    jQuery('.'+settings.parentLookupClass).stop(true, true).fadeIn('slow');
                                  }
                                  else {
                                    jQuery('.'+settings.parentLookupClass).stop(true, true).show();
                                  }
                              }
                            }
                      }
                    });
                  });
        };  