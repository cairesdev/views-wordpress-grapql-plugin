<?php
/*
Plugin Name: GraphQL and Post Views
Description: Exibe o número de visualizações do post no GraphQL e registra views via REST API pelo slug.
Version: 1.2
Author: cairesdev
*/

add_action('graphql_register_types', function () {
  register_graphql_field('Post', 'views', [
    'type' => 'Int',
    'description' => 'Número de visualizações do post',
    'resolve' => function ($post) {
      return (int) get_post_meta($post->ID, 'views', true);
    },
  ]);
});

add_action('rest_api_init', function () {
  register_rest_route('views/v1', '/hit/(?P<slug>[a-zA-Z0-9-]+)', [
    'methods' => 'POST',
    'callback' => function ($request) {
      $slug = sanitize_text_field($request['slug']);
      $post = get_page_by_path($slug, OBJECT, 'post');
      if (!$post || $post->post_status !== 'publish') {
        return new WP_Error('invalid_post', 'Post inválido ou não publicado.', ['status' => 400]);
      }
      $views = (int) get_post_meta($post->ID, 'views', true);
      $views++;
      update_post_meta($post->ID, 'views', $views);
      return ['postId' => $post->ID, 'slug' => $slug, 'views' => $views];
    },
    'permission_callback' => '__return_true', 
  ]);
});
