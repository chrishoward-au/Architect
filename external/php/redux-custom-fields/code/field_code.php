<?php
class ReduxFramework_code extends ReduxFramework {

    /**
     * Field Constructor.
     *
     * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
     *
     * @since ReduxFramework 1.0.0
    */
    function __construct( $field = array(), $value ='', $parent ) {
    
        //parent::__construct( $parent->sections, $parent->args );
        $this->parent = $parent;
        $this->field = $field;
        $this->value = $value;
    
    }

    /**
     * Field Render Function.
     *
     * Takes the vars and outputs the HTML for the field in the settings
     *
     * @since ReduxFramework 1.0.0
    */
    function render() {


    		echo '<input type="text" id="' . $this->field['id'] . '-text" name="' . $this->field['name'] . '" readonly value="' . esc_attr($this->value) . '" class="regular-text ' . $this->field['class'] . '" />';
        if (isset($this->field['code'])) {
          echo '<div class="redux-code-block">'.$this->field['code'].'</div>';
        }

    
        
        
    
    }
}
