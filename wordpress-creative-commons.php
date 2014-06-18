<?php
/**
 * Plugin Name: Wordpress Creative Commons
 * Plugin Uri: https://github.com/mateus007/wordpress-creative-commons
 * Description: Add creative commons license box in the posts with configurable license per post
 * Version:     1.0.0
 * Author:      Mateus Souza
 */

/**
 * Add meta box in admin
 * @return void
 */
function creativeCommonsPostTypeAdmin(){
	add_meta_box('post', __('Licença CC', 'linha'), 'creativeCommonsMetaBox', 'post', 'normal', 'high');
}
add_action('admin_init', 'creativeCommonsPostTypeAdmin');

/**
 * The meta box options
 * @return void
 */
function creativeCommonsMetaBox(){
	global $post;
	$meta = get_post_custom($post->ID);
	$license = $meta['license'][0];
	?>
	<p>
		<label>
			<input type="radio" name="license" value="NONE" <?php if(!$license OR $license == 'NONE') echo 'checked="checked"' ?> /> Sem licença</label><br/>

		<label>
			<input type="radio" name="license" value="BY" <?php if($license == 'BY') echo 'checked="checked"' ?> />
			<img src="<?php echo plugins_url('images/CC-BY.png', __FILE__ ); ?>" alt="">
			Somente atribuição (BY)</label><br/>

		<label>
			<input type="radio" name="license" value="BY-NC" <?php if($license == 'BY-NC') echo 'checked="checked"' ?> />
			<img src="<?php echo plugins_url('images/CC-BY-NC.png', __FILE__ ); ?>" alt="">
			Atribuição + Uso não comercial (BY-NC)</label><br/>

		<label>
			<input type="radio" name="license" value="BY-ND" <?php if($license == 'BY-ND') echo 'checked="checked"' ?> />
			<img src="<?php echo plugins_url('images/CC-BY-ND.png', __FILE__ ); ?>" alt="">
			Atribuição + Não a obras derivadas (BY-ND)</label><br/>

		<label>
			<input type="radio" name="license" value="BY-SA" <?php if($license == 'BY-SA') echo 'checked="checked"' ?> />
			<img src="<?php echo plugins_url('images/CC-BY-SA.png', __FILE__ ); ?>" alt="">
			Atribuição + Compartilhamento pela mesma licença (BY-SA)</label><br/>

		<label>
			<input type="radio" name="license" value="BY-NC-ND" <?php if($license == 'BY-NC-ND') echo 'checked="checked"' ?> />
			<img src="<?php echo plugins_url('images/CC-BY-NC-ND.png', __FILE__ ); ?>" alt="">
			 Atribuição + Uso não comercial + Não a obras derivadas (BY-NC-ND)</label><br/>

		<label>
			<input type="radio" name="license" value="BY-NC-SA" <?php if($license == 'BY-NC-SA') echo 'checked="checked"' ?> />
			<img src="<?php echo plugins_url('images/CC-BY-NC-SA.png', __FILE__ ); ?>" alt="">
			 Atribuição + Uso não comercial + Compartilhamento pela mesma licença (BY-NC-SA)</label><br/>
	</p>
	<?php
}

/**
 * Save license data
 * @param int $post_id
 * @return string
 */
function saveCreativeCommonsMeta($post_id){

	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}

	if( !current_user_can( 'edit_post', $post_id ) ){
		return;
	}

	if( !isset($_POST['license']) ){
		return FALSE;
	}

	// Update data
	update_post_meta($post_id, 'license', $_POST['license']);

	return $post_id;
}
add_action('save_post', 'saveCreativeCommonsMeta');
add_action('edit_post', 'saveCreativeCommonsMeta');

/**
 * Add creative commons box
 * @param string $content
 * @return string
 */
function creativeCommonAddtoPost($content){
	global $post;

	$licenses = array(
		'BY' => '<a rel="license" href="http://creativecommons.org/licenses/by/4.0/"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by/4.0/88x31.png" /></a><br />$INFOEsta obra está licenciado com uma Licença <a rel="license" href="http://creativecommons.org/licenses/by/4.0/">Creative Commons Atribuição 4.0 Internacional</a>.',
		'BY-NC' => '<a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc/4.0/88x31.png" /></a><br />$INFOEsta obra está licenciado com uma Licença <a rel="license" href="http://creativecommons.org/licenses/by-nc/4.0/">Creative Commons Atribuição-NãoComercial 4.0 Internacional</a>.',
		'BY-ND' => '<a rel="license" href="http://creativecommons.org/licenses/by-nd/4.0/"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nd/4.0/88x31.png" /></a><br />$INFOEsta obra está licenciado com uma Licença <a rel="license" href="http://creativecommons.org/licenses/by-nd/4.0/">Creative Commons Atribuição-SemDerivações 4.0 Internacional</a>.',
		'BY-SA' => '<a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-sa/4.0/88x31.png" /></a><br />$INFOEsta obra está licenciado com uma Licença <a rel="license" href="http://creativecommons.org/licenses/by-sa/4.0/">Creative Commons Atribuição-CompartilhaIgual 4.0 Internacional</a>.',
		'BY-NC-ND' => '<a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-nd/4.0/88x31.png" /></a><br />$INFOEsta obra está licenciado com uma Licença <a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">Creative Commons Atribuição-NãoComercial-SemDerivações 4.0 Internacional</a>.',
		'BY-NC-SA' => '<a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/"><img alt="Licença Creative Commons" style="border-width:0" src="http://i.creativecommons.org/l/by-nc-sa/4.0/88x31.png" /></a><br />$INFOEsta obra está licenciado com uma Licença <a rel="license" href="http://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons Atribuição-NãoComercial-CompartilhaIgual 4.0 Internacional</a>.'
	);

	$meta = get_post_custom($post->ID);
	$license = $meta['license'][0];

	if( array_key_exists($license, $licenses)){

		$info = '';
		$info .= '<em><a href="'. get_permalink($post->ID). '">'. get_the_title($post->ID) .'</a></em>';
		$info .= ' por <em>'. get_the_author_link(). '</em>. ';

		$license = str_replace('$INFO', $info, $licenses[ $license ]);
		$box = '<div style="display: block" class="creative-commons-licence-box">'. $license. '</div>';
		$content .= $box;
	}

	return $content;
}
add_filter('the_content', 'creativeCommonAddtoPost', 250);

// Feeds

// add_action('rdf_ns', 'bccl_add_cc_ns_feed');
// add_action('rdf_header', 'bccl_add_cc_element_feed');
// add_action('rdf_item', 'bccl_add_cc_element_feed_item');

// add_action('rss2_ns', 'bccl_add_cc_ns_feed');
// add_action('rss2_head', 'bccl_add_cc_element_feed');
// add_action('rss2_item', 'bccl_add_cc_element_feed_item');

// add_action('atom_ns', 'bccl_add_cc_ns_feed');
// add_action('atom_head', 'bccl_add_cc_element_feed');
// add_action('atom_entry', 'bccl_add_cc_element_feed_item');
