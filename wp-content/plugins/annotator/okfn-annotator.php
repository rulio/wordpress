<?php

/**
 * @package OkfnAnnotator
 * @author Andrea Fiore
 * @author Nick Stenning
 *
 * Main plugin controller
 *
 */


/*
Plugin Name: Annotator
Plugin URI: https://github.com/okfn/annotator-wordpress
Description: Adds inline annotations to Wordpress using the amazing <a href="http://annotateit.org">Annotator</a> widget (by the Open Knowledge Foundation).
Version: 0.4
Author: Open Knowledge Foundation
Author URI: http://okfn.org/projects/annotator/
License: GPLv2 or later
*/

foreach(array(
  'lib/wp-pluggable',
  'vendor/Mustache',
  'lib/okfn-utils',
  'lib/okfn-base',
  'lib/okfn-annot-settings',
  'lib/okfn-annot-content-policy',
  'lib/okfn-annot-injector',
  'lib/okfn-annot-factory',
            'lib/jwt'
) as $lib) require_once("${lib}.php");
use \Firebase\JWT\JWT;

$settings = new OkfnAnnotSettings();

if (!is_admin()) {
  $factory  = new OkfnAnnotFactory($settings);
  $content_policy  = new OkfnAnnotContentPolicy($settings);
  $injector = new OkfnAnnotInjector($factory, $content_policy);
  $injector->inject();
}
function annotator_token(){

  $user = wp_get_current_user();
  $settings = new OkfnAnnotSettings();
  $payload = array();
  $payload['consumerKey'] = 'b8b5e94d4ad441ae9ff0aa7ce5d06ee2';
  $payload['userId']      = $user->user_login;
  $payload['issuedAt']    = date('c');
  $payload['ttl']         = 84600;

  if($user->exists()){
    $encoded =  JWT::encode($payload, $settings->get_option('annotator_token_key'));
    echo $encoded;
  }
  exit;
}
function annotator_base(){
  $out = array(
      'name'    => "Wordpress Annotator Store API (PHP)",
      'version' => '1.0.0',
      'author'  => 'rulio'
  );
  return $out;

}
function annotator_annotations_get(){
    $out = array();
    $c = array();
    foreach($c as $post) {
      $post['id'] = (string) $post['_id'];
      unset($post['_id']);
      $out[] = $post;
    }

    return $out;
}

function annotator_annotations_post(){

  return $_SERVER;
}

function annotator_get_annotation(){
  //by id

}
function annotator_update_annotation(){
  //by id

}
function annotator_delete_annotation(){
  //by id

}


add_action( 'rest_api_init', function () {
  register_rest_route( 'annotator/v1', '/api/token', array(
      'methods' => 'GET',
      'callback' => 'annotator_token',
  ) );
  register_rest_route( 'annotator/v1', '/api', array(
      'methods' => 'GET',
      'callback' => 'annotator_base',
  ) );
  register_rest_route( 'annotator/v1', '/api/annotations', array(
      'methods' => 'GET',
      'callback' => 'annotator_annotations_get',
  ) );
  register_rest_route( 'annotator/v1', '/api/annotations', array(
      'methods' => 'POST',
      'callback' => 'annotator_annotations_post',
  ) );

  register_rest_route( 'annotator/v1', '/api/annotations/[id]', array(
      'methods' => 'GET',
      'callback' => 'annotator_get_annotation',
  ) );
  register_rest_route( 'annotator/v1', '/api/annotations/[id]', array(
      'methods' => 'PUT',
      'callback' => 'annotator_update_annotation',
  ) );
  register_rest_route( 'annotator/v1', '/api/annotations/[id]', array(
      'methods' => 'DELETE',
      'callback' => 'annotator_delete_annotation',
  ) );



} );

?>
