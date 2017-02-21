<?php

/*
 * Dynamically generates the JavaScript code snippet needed for instantiating
 * the Annotator.
 *
 *
 */

class OkfnAnnotFactory extends OkfnBase {
  private $uri; //current wordpress page
  private $annotator_content;
  private $template_vars;
  private $settings;

  /*
   * Public:
   *
   * Class constructor
   *
   * settings - an instance of OkfnAnnotSettings.
   *
   */

  function __construct($settings) {
    $this->settings = $settings;
    $this->annotator_content = $settings->get_option('annotator_content');

    //todo: add load from search
    $this->uri = $this->get_current_uri();
  }


  /*
   *
   * Fetches the URI of the currently visited blog page
   *
   * returns a uri string
   *
   */

  private function get_current_uri(){
    return OkfnUtils::current_url();
  }

  /*
   * Wrapper method calling the Mustache template engine.
   *
   * (Needed mostly for testing and mocking...)
   *
   *
   *
   */

  function render_template($template_vars, $template='annotator-instance.js') {
    $template = OkfnUtils::get_template($template);
    $mustache = new Mustache;
    return $mustache->render($template,$template_vars);
  }


  private function prepare_variables(){
    $user_name = 'guest';
    if(wp_get_current_user()){
      $user = wp_get_current_user();
      $user_name = $user->user_login;
    }
    $template_vars = array(
      'annotator_content' => $this->annotator_content,
      'uri' => $this->uri,
      'user_name'=> $user_name,
      'annotator_store_location' => WP_ANNOTATOR_STORE,
      'nonce'=>wp_create_nonce('wp_rest')
    );

    return $template_vars;
  }

  /*
   * Generates a code snippet containing a customised Javascript instantiation
   * code for the Annotator.
   *
   * Returns a string or null if no instance is meant to be created.
   *
   */
  function create_snippet() {
    return $this->render_template($this->prepare_variables());
  }
}
?>
