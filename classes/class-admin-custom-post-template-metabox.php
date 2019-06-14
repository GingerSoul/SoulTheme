<?php
/**
* Creates the SoulTheme Custom Post Templates metabox and saves the user's chosen templates on post save
*/
class Soul_Theme_Custom_Post_Templates_Metabox {

    /**
    * Constructor.
    */
    public function __construct() {
        if ( is_admin() ) {
        add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
        add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
        }

    }

    /**
    * Meta box initialization.
    */
    public function init_metabox() {
        add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
        add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );
    }

    /**
    * Adds the SoulTheme custom template metabox 
    */
    public function add_metabox() {
        $post_types = array('post', 'page');
        
        if(class_exists('FLBuilderModel')){
            $post_types = FLBuilderModel::get_post_types();
        }
        
        add_meta_box(
            'soul-theme-custom-header-footer',
            __('SoulTheme Custom Templates', 'gsr-soul-theme'),
            array($this, 'render_soul_theme_templates_metabox'),
            $post_types,
            'advanced'
        );
    }

    /**
    * Renders the SoulTheme custom template metabox.
    */
    public function render_soul_theme_templates_metabox( $post ) {
        // Create a nonce for the metabox
        wp_nonce_field( 'gsr_header_footer_nonce-' . $post->ID, 'gsr_hf_nonce' );

        // Obtain all of the current saved Beaver Builder templates
        $templates = get_posts(array('numberposts' => -1, 'post_type' => 'fl-builder-template' ));
        
        // Try getting any saved templates for the post
        $saved_templates = get_post_meta($post->ID, 'gsr-soul-theme-custom-post-templates', true);

        // If there aren't any templates, set up an empty array
        if(empty($saved_templates)){
            $saved_templates = array();
        }

        // Output the custom template metabox HTML
        ?>
        <div id="gsr-soul-theme-custom-post-templates" name="gsr-soul-theme-custom-post-templates">
            <div>
                <label for="gsr-soul-theme-custom-post-templates_header"><span style="min-width: 120px; display: inline-block;"><?php _e('Add Custom Header', 'gsr-soul-theme'); ?></span></label>
                <select id="gsr-soul-theme-custom-post-templates_header" name="gsr-soul-theme-custom-post-templates_header">
                    <option value="null" <?php selected(!isset($saved_templates['_header']) || 'null' === $saved_templates['_header']); ?>><?php _e('Don\'t add custom header', 'gsr-soul-theme'); ?></option>
                    <?php
                        // Output each available header template option
                        foreach($templates as $template){
                            echo '<option value="' . $template->ID . '"' . selected(isset($saved_templates['_header']) && (int) $template->ID === (int) $saved_templates['_header'], true, false) . '>' . get_the_title($template) . '</option>';
                        }
                    ?>
                </select>
            </div>
            <div>
                <label for="gsr-soul-theme-custom-post-templates_footer"><span style="min-width: 120px; display: inline-block;"><?php _e('Add Custom Footer', 'gsr-soul-theme'); ?></span></label>
                <select id="gsr-soul-theme-custom-post-templates_footer" name="gsr-soul-theme-custom-post-templates_footer">
                    <option value="null" <?php selected(!isset($saved_templates['_footer']) || 'null' === $saved_templates['_footer']); ?>><?php _e('Don\'t add custom footer', 'gsr-soul-theme'); ?></option>
                    <?php
                        // Output each available footer template option
                        foreach($templates as $template){
                            echo '<option value="' . $template->ID . '"' . selected(isset($saved_templates['_footer']) && (int) $template->ID === (int) $saved_templates['_footer'], true, false) . '>' . get_the_title($template) . '</option>';
                        }
                    ?>
                </select>
            </div>
        </div>
        <?php
    }

    /**
    * Stores the SoulTheme custom template data on post save
    */
    public function save_metabox( $post_id, $post ) {
        $nonce_name   = isset( $_POST['gsr_hf_nonce'] ) ? $_POST['gsr_hf_nonce'] : '';
        $nonce_action = 'gsr_header_footer_nonce-' . $post_id;

        // Exit the save action if:
        if (!wp_verify_nonce($nonce_name, $nonce_action) || // the nonce isn't valid,
            !current_user_can('edit_post', $post_id) ||     // the user doesn't have permission to edit the post,
            wp_is_post_autosave($post_id) ||                // this is an autosave,
            wp_is_post_revision($post_id) )                 // or if this is a revision
        {
            return;
        }

        // Set up the post template data key
        $template_key = 'gsr-soul-theme-custom-post-templates';

        // Make a list of the available post location templates
        $available_templates = array('_header', '_footer');

        // Get any currently saved post templates
        $post_templates = get_post_meta($post_id, $template_key, true);

        // Check to see if there's any saved templates
        if(empty($post_templates)){
            // if there aren't any, set currently_saved to an empty array
            $post_templates = array();
        }

        // Look over the items from saving the post
        foreach($_POST as $key => $value){
            // if the current item in the loop has gsr post template data
            if(false !== strpos($key, $template_key)){
                // get the specific location we're trying to save
                $template_location = substr($key, strlen($template_key));

                // check to see if the location is in our list of locations to save
                if(in_array($template_location, $available_templates)){

                    // if it is, add the location and its template data to the saved list of templates
                    $post_templates[$template_location] = sanitize_text_field($value);
                }
            }
        }

        // And finally save the template data
        update_post_meta($post_id, $template_key, $post_templates);

    }
}

new Soul_Theme_Custom_Post_Templates_Metabox();
