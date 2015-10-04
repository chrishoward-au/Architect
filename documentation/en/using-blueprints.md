There are seven methods to displaying a Blueprint. In order of required technical know-how (easiest first), these are:

-   **Headway Block** : If you are using Headway, simply place the Architect block, and then select the Blueprint to display.

-   **Widget** : Select Architect widget on WP Admin widgets screen

-   **Shortcode** : Place your cursor in the body content where you want the Blueprint to appear, and select it from the dropdown at the top of the editor. Alternatively, add *[architect blueprint=“blueprint” ids=“overrides”]* in your content at the point you want the Blueprint to appear.

-   **Architect Builder**: The Architect Builder appears on the WP Page Editor screen. It provides a simple drag and drop interface for arrange Blueprints. You do have to change the page *Template* in *Page Attributes* box to *Architect Builder*  

-   **Actions editor** : Enter the action name, Blueprint and pages to appear on

-   **Action call** : Add _new showBlueprint(’action’, ’blueprint’, ’pageids’);_ to your functions.php. It should be in a function added to init action, and should be conditional on class_exists showBlueprint

-   **Template tag** : Add *pzarchitect(’blueprint’);* to your template at the point you want the Blueprint to appear


*blueprint* = the shortname of the Blueprint to display.

*overrides* = a comma separated list of the media ids to display instead. Very useful for easily converting a WordPress gallery shortcode. eg [gallery ids=“1,2,3,5”] change to [architect blueprint=“mygallery” ids=“1,2,3,4,5”]

*action* = the name of the action hook where you want the Blueprint to appear

*pageids* = a comma separated list of names or numeric ids of the pages to display the Blueprint.

**NOTE**: In Architect Options, you can also select any Blueprint with Galleries as the content source to override the WordPress Gallery shortcode layout.


