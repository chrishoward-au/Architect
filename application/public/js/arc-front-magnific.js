jQuery( document ).ready( function ()
      {
          "use strict";
          var arcSections = jQuery( '.pzarchitect .pzarc-section' );
          var arcRSIDs = '';
          arcSections.each( function ( i )
          {
              arcRSIDs = this.id;
              var arcLightbox = jQuery( 'a.lightbox-' + arcRSIDs );
              if ( arcLightbox.length > 0 )
              {
                  arcLightbox.magnificPopup( {
                      type: 'image',
                      image: {
                          titleSrc: function(item){return getTitle(item);}
                      },
                      gallery: {enabled: true}
                  } );

              }
          } );
          var getTitle = function ( item )
          {
              var imgTitle = item.el.attr( 'title' );
              var imgCaption = item.el.find('img' ).attr('alt');
              var magnificTitle = imgTitle;
              if ('' !== imgCaption && imgTitle !== imgCaption) {
                  magnificTitle  = magnificTitle +'<br><div class="caption">'+imgCaption+'</div>';
              }
              return magnificTitle;
          };
      }
);