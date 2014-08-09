CSS Classes
-----------

    div: pzarc-blueprint pzarc-blueprint_{blueprint shortname) nav-{navigation type}

      h2:  pzarc-page-title

      div:  pzarc-sections pzarc-sections_{blueprint shortname} pzarc-is_{architect type}

        section:  pzarc-section pzarc-section_{section number} pzarc-section-using-panel_{panel shortname}

          div:  pzarc-panel pzarc-panel_{panel shortname} pzarc-panel-no_{panel_number} {odd/even}-blueprint-panel {odd/even}-section-panel

            figure: entry-bgimage pzarc-bg-image  fill scale
              a: lightbox
                img:

            article: {If Headway block-type-content}  post-{post ID} post type-{} status-{post status} format-{post format} hentry category-{category}  pzarc-components

              h1:  entry-title

              div: entry-customfieldgroup entry-customfieldgroup-{custom fieldgroup number}
                div: entry-customfield entry-customfield-{custom field name}
                  p:
                    img: pzarc-presuff-image prefix-image
                    img: pzarc-presuff-image suffix-image

              div:  entry-meta entry-meta{meta field number}
                span: entry-date
                  a:
                    time: entry-date
                span: by-line
                  span: author vcard

              figure: entry-thumbnail
                a:
                  img:

              div: entry-excerpt
                figure: entry-thumbnail in-content-thumb
                  img:
                  span: caption
                a: readmore moretag

              div: entry-content
                figure: entry-thumbnail in-content-thumb
                  img:
                  span: caption

