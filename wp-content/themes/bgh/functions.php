<?php
	error_reporting(E_ALL^E_NOTICE^E_DEPRECATED^E_WARNING^E_STRICT);
	ob_start();




	define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
	define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);



	# Redirect to https
/*
if (!isset($_SERVER['https']) || $_SERVER['https'] == '') {
		$redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		header("HTTP:/1.1 301 Moved Permanently");
		header("Location: $redirect");
	}
*/

	// REB 000 FUNCION PARA ESTANDARIZAR NOMBRES CAPITALIZE EN PHP

		function ucname($string) {
		    $string =ucwords(strtolower($string));

		    foreach (array('-', '\'') as $delimiter) {
		      if (strpos($string, $delimiter)!==false) {
		        $string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
		      }
		    }
		    return $string;
		}

	// FIN REB 000



	// REB 444 ESTILOS CUSTOM DEL ADMIN

		function registerCustomAdminCss(){
			$src = get_template_directory_uri() . "/css/custom-admin.css";
			$handle = "customAdminCss";
			wp_register_script($handle, $src);
			wp_enqueue_style($handle, $src, array(), false, false);
		}
		add_action('admin_head', 'registerCustomAdminCss');

	// FIN REB 444


	// REB 555 Personalizar Admin login

		function custom_login_stylesheet() {
		    wp_enqueue_style( 'custom-login', get_template_directory_uri() . '/css/custom-login.css' );
		    wp_enqueue_script( 'custom-login', get_template_directory_uri() . '/js/custom-login.js' );
		}
		add_action('login_enqueue_scripts', 'custom_login_stylesheet');

		function custom_login_logo_url() {
		    return get_site_url();
		}
		add_filter('login_headerurl', 'custom_login_logo_url');

		function custom_login_logo_url_title() {
		    return 'Volver al sitio de BGH';
		}
		add_filter('login_headertitle', 'custom_login_logo_url_title');

	// FIN REB 555


	// REB 888 Defaults Label/Value para mostrar en listas (TODO: Pasar a table)

		function get_agents_categories($k = '') {
			$list = get_field('parameters-agents-categories', 'option');
			// var_dump($list);
			if($k!=''){
				$ix = array_search($k, array_column($list, 'name'));
				return $list[$ix];
			}
			else{
				return $list;
			}
		}
		add_filter('agent_categories', 'get_agents_categories');

    // FIN REB 888


    // REB 999 Defaults Label/Value de provincias (TODO: Pasar a table)

		function get_provinces($k = '') {
			$list = get_field('parameters-provinces', 'option');
			if($k!=''){
				$ix = array_search($k, array_column($list, 'name'));
				return $list[$ix];
			}
			else{
				return $list;
			}
		}
		add_filter('provinces', 'get_provinces');

    // FIN REB 999





	# General
	# ----------------------------------
	// Sessions
	add_action('init', function() {
		if(!session_id())
			session_start();
	}, 1);

	// Load script
	add_action('wp_enqueue_scripts', function() {
		wp_enqueue_script('jquery');
	});

	// Blog title
	add_filter('wp_title', function() {
		return esc_attr(get_bloginfo('name'));
	});

	$current_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

	# Post types
	# ----------------------------------
	$menu_position = -20;
	function add_post_type($name, $plural, $singular, $icon, $supports, $taxonomies) {
		global $menu_position;
		add_action('init', function() use ($name, $plural, $singular, $icon, $supports, $taxonomies) {

			global $menu_position;
			$plural_lowercase = strtolower($plural);
			$singular_lowercase = strtolower($singular);
			// Register taxonomy

			if (array_search('category', $taxonomies) !== false) {
				register_taxonomy(
					$name.'_categories',
					$name,
				array(
					'hierarchical' => true,
					'label' => 'Categorías de '.$singular,
					'query_var' => true,
					'rewrite' => true
				));
				// Inherit actions
				add_action($name.'_categories_add_form_fields', function() {
					do_action('category_add_form_fields');
				});
				add_action($name.'_categories_edit_form_fields', function($term) {
					do_action('category_edit_form_fields', $term);
				});
				add_action('create_'.$name.'_categories', function($term_id) {
					do_action('edited_category', $term_id);
				}, 10, 2);
				add_action('edited_'.$name.'_categories', function($term_id) {
					do_action('edited_category', $term_id);
				}, 10, 2);
				// Remove category from taxonomy
				if(($key = array_search('category', $taxonomies)) !== false)
					unset($taxonomies[$key]);
			}
			// Register post type
			$labels = array(
				'name' => _x($plural, 'post type general name'),
				'singular_name' => _x($singular, 'post type singular name'),
				'add_new' => _x('Añadir nuevo', $singular_lowercase),
				'add_new_item' => __('Añadir nuevo '.$singular_lowercase),
				'edit_item' => __('Editar '.$singular_lowercase),
				'new_item' => __('Nuevo '.$singular_lowercase),
				'all_items' => __('Todos los '.$plural_lowercase),
				'view_item' => __('Ver '.$singular_lowercase),
				'search_items' => __('Buscar '.$plural_lowercase),
				'not_found' => __('No se encontraron '.$plural_lowercase),
				'not_found_in_trash' => __('No hay '.$plural_lowercase.' en papelera'),
				'parent_item_colon' => '',
				'menu_name' => $plural
			);
			if (substr($singular_lowercase, -3) != 'ima')
			if (substr($singular_lowercase, -1) == 'a' || $singular_lowercase == 'novedad' || substr($singular_lowercase, -3) == 'rse') {
				$labels['new_item'] = __('Nueva '.$singular_lowercase);
				$labels['add_new'] = _x('Añadir nueva', $singular_lowercase);
				$labels['add_new_item'] = __('Añadir nueva '.$singular_lowercase);
				$labels['all_items'] = __('Todas las '.$plural_lowercase);
			}
			$args = array(
				'labels' => $labels,
				'description' => 'Contiene '.$plural_lowercase.' y datos específicos de los mismos',
				'public' => true,
				'menu_position' => $menu_position,
				'supports' => $supports,
				'taxonomies' => $taxonomies,
				'has_archive' => true,
				'menu_icon'	=> $icon,
				'rewrite' => array('slug' => $singular_lowercase, 'with_front'=>false)
			);
			register_post_type($name, $args);
		});
	}

	add_post_type('donde-comprar', 'Donde Comprar', 'Donde Comprar', 'dashicons-fontawesome-building-o', array('title'), array());
	add_post_type('agents', 'Agentes Celulares', 'Agente Celulares', 'dashicons-fontawesome-suitcase', array('title'), array());
	add_post_type('airexperts', 'Expertos en clima', 'Experto en clima', 'dashicons-fontawesome-users', array('title'), array());
	add_post_type('airpro', 'Climatización para profesionales', 'Climatización para profesionales', 'dashicons-fontawesome-sun-o', array('title', 'editor', 'thumbnail', 'excerpt'), array('post_tag', 'category'));
	add_post_type('rse', 'Entradas de RSE', 'Entrada de RSE', 'dashicons-fontawesome-building-o', array('title', 'editor'), array('category'));
	add_post_type('news', 'Novedades', 'Novedad', 'dashicons-fontawesome-newspaper-o', array('title', 'editor'), array('category'));
	add_post_type('choose-kitchen', 'Como elegir tu cocina', 'Como elegir tu cocina', 'dashicons-fontawesome-fire', array('title', 'editor'), array());
	add_post_type('tip', 'Tips', 'Tip', 'dashicons-fontawesome-lightbulb-o', array('title', 'editor'), array());
	add_post_type('instructor', 'Instructoras', 'Instructora', 'dashicons-fontawesome-graduation-cap', array('title'), array());
	add_post_type('recipe', 'Recetas', 'Receta', 'dashicons-fontawesome-cutlery', array('title', 'editor', 'thumbnail'), array('post_tag', 'category'));
	add_post_type('brand', 'Fabricantes', 'Fabricante', 'dashicons-store', array('title'), array());
	add_post_type('product', 'Productos', 'Producto', 'dashicons-cart', array('title', 'editor', 'thumbnail', 'excerpt'), array('post_tag', 'category'));


	add_post_type('expertos-contacto', 'Expertos Contacto', 'Expertos Contacto', 'dashicons-cart', array('title'), array());
	add_post_type('expertos-faq', 'Expertos Preguntas Frecuentes', 'Expertos Pregunta Frecuente', 'dashicons-cart', array('title'), array('category'));
	add_post_type('expertos-accion', 'Expertos Acciones Marketing', 'Expertos Accion Marketing', 'dashicons-cart', array('title'), array());
	add_post_type('expertos-material', 'Expertos Materiales Marketing', 'Expertos Material Marketing', 'dashicons-cart', array('title', 'excerpt'), array('post_tag','category'));
	add_post_type('expertos-soporte', 'Expertos Soporte', 'Expertos Soporte', 'dashicons-cart', array('title'), array('category'));
	add_post_type('expertos-nota', 'Expertos Notas', 'Expertos Nota', 'dashicons-cart', array('title'), array('category'));
	add_post_type('expertos-curso', 'Expertos Cursos', 'Expertos Curso', 'dashicons-cart', array('title'), array('category'));
	add_post_type('expertos-video', 'Expertos Videos', 'Expertos Video', 'dashicons-cart', array('title', 'excerpt'), array('category'));
	add_post_type('expertos-usuario', 'Expertos Usuarios', 'Expertos Usuario', 'dashicons-cart', array('title'), array());



	$post_type_any = array('product', 'recipe', 'news', 'rse', 'airpro', 'mates', 'expertos-usuario', 'expertos-video', 'expertos-curso', 'expertos-nota', 'expertos-soporte', 'expertos-material', 'expertos-accion', 'expertos-faq');

	# Backend
	# ----------------------------------
	function array_where($array, $key, $value) {
		$results = array();
		if (is_array($array)) {
			if (isset($array[$key]) && $array[$key] == $value)
				$results[] = $array;
			foreach ($array as $subarray)
				$results = array_merge($results, array_where($subarray, $key, $value));
    	}
    	return $results;
	}



	add_filter( 'wp_mail_from', 'my_mail_from' );
	function my_mail_from( $email ){
	    return "noreply@bgh.com.ar";
	}

	add_filter( 'wp_mail_from_name', 'my_mail_from_name' );
	function my_mail_from_name( $name ){
	    return "BGH";
	}

	add_filter( 'wp_mail_content_type', 'wpdocs_set_html_mail_content_type' );
	function wpdocs_set_html_mail_content_type() {
	    return 'text/html';
	}


	add_action( 'save_post', 'send_email' );
	function send_email( $post_id ) {

		if ( !wp_is_post_revision($post_id) && get_post_type($post_id) == 'expertos-accion') {

			$post = new stdClass();
			$post->ID = $post_id;
			$post->email = get_field('accion_email', $post->ID);
			$post->empresa = get_field('accion_empresa', $post->ID);
			$post->nombre = get_field('accion_nombre', $post->ID);
			$post->nombreyapellido = get_field('accion_nombreyapellido', $post->ID);

        	$post->tipo = get_field('accion_tipo', $post->ID);

		    // tipo de accion
		    $field = get_field_object("field_568d5c79c869f");
		    if($field){
		        foreach( $field['choices'] as $k => $v ){
		            $accion_tipo[$k] = $v;
		        }
		    }

			$post->historial = get_field('accion_historial', $post->ID);

			// si es el alta, no tiene estado, no hace nada
		    if(sizeof($post->historial)>0){

				$post->historial_final = sizeof($post->historial)-1;
				$post->estado = $post->historial[$post->historial_final]["accion_historial_estado"];
				$post->historial_observacion = $post->historial[$post->historial_final]["accion_historial_texto"];

				$to = $post->email;

				$accion_estado['pendent'] = 'Pendiente de Aprobación';
				$accion_estado['preapproved'] = 'Pre Aprobada';
				$accion_estado['approved'] = 'Aprobada';
				$accion_estado['rejected'] = 'Rechazada';
				$accion_estado['actionobserved'] = 'Observada';
				$accion_estado['executionobserved'] = 'Observada (en su ejecución)';

				$subject = 'Tu acción está ' . $accion_estado[$post->estado];

	            $mailbody = "<html><body>";
	            $mailbody .= "<p>Estimado/a " . $post->nombreyapellido . ":</p>";
	            $mailbody .= "<p>Tu acción <strong>" . $post->nombre . "</strong> fue revisada y pasada al estado <b><span style='color:blue'>" . $accion_estado[$post->estado] . "</span></b> con las siguientes observaciones: &nbsp;<i><span style='color:#777'>\"" . $post->historial_observacion . "\"</span></i>";
				$mailbody .= "<br><br>Para ver la acción hacé click <a href='".getBaseUrl()."/expertos-acciones-form?id=". $post->ID. "'>acá</a>\n\n";

				if($post->estado=='preapproved'){
					$mailbody .= "<br><i>Recuerde que tendrá hasta 60 (sesenta) días luego de pre-aprobada la acción en la primera etapa, para completar esta segunda etapa. En caso de que esta etapa no se complete una vez cumplido el plazo, la acción quedará automáticamente anulada</i>\n";
				}

	            $mailbody .= "<p><br>BGH Climatización Profesional</p>";
	            $mailbody .= "</body></html>";

	            $fields = array(
				   'to' => $post->email,
				   'subject' => $subject,
				   'body' => $mailbody
				);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, site_url() . '/service/mail.php');
				curl_setopt($ch, CURLOPT_POST, count($fields));
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
				$result = curl_exec($ch);
				curl_close($ch);

		    }


		}

		if ( get_post_type($post_id) == 'airexperts' ) {

			$post = get_post( $post_id );

			// $to = 'marketingaac@bgh.com.ar,dash.egoagency@gmail.com';

			$subject = 'El perfil de ' . $post->post_title . ' fue modificado.';

            // $headers = "From: BGH Climatización Profesional <" . strip_tags("marketingaac@bgh.com.ar") . ">\r\n";
            // $headers .= "MIME-Version: 1.0\r\n";
            // $headers .= "Content-Type: text/html; charset=utf-8\r\n";

            $mailbody = "<html><body>";
            $mailbody .= "<p>El usuario " . $post->post_title . " modificó su perfil</p>";
			$mailbody .= "<br><br>Para ver el perfil hacé click <a href='".getBaseUrl()."/wp-admin/post.php?post=". $post->ID. "&action=edit'>acá</a>\n\n";
            $mailbody .= "<p><br>Debes estar logueado como admin</p>";
            $mailbody .= "</body></html>";


        	$fields = array(
			   'to' => 'dash.egoagency@gmail.com,marketingaac@bgh.com.ar',
			   'subject' => $subject,
			   'body' => $mailbody
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, site_url() . '/service/mail.php');
			curl_setopt($ch, CURLOPT_POST, count($fields));
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
			$result = curl_exec($ch);
			curl_close($ch);


			// wp_mail($to, $subject, $mailbody, $headers);
		}

	}



	// *** START ADMIN
	if (is_admin()) {

		//require 'admin/plugins/category-tags/category-tags.php';
		//require 'admin/media-tags.php';
		require 'admin/disable-comments.php';
		add_action('admin_enqueue_scripts', function() {
			// CSS
			wp_enqueue_style('style-admin', get_template_directory_uri() . '/admin/styles.css');
			wp_enqueue_style('style-codemirror', get_template_directory_uri() . '/admin/codemirror/codemirror.css');
			wp_enqueue_style('style-codemirror_showhint', get_template_directory_uri() . '/admin/codemirror/addon/hint/show-hint.css');
			wp_enqueue_style('style-handsontable', get_template_directory_uri() . '/admin/handsontable/handsontable.full.css');
			wp_enqueue_style('style-fontawesome', get_template_directory_uri() . '/admin/font-awesome/font-awesome.css');
			// JS
			wp_enqueue_script('script-admin', get_template_directory_uri() . '/admin/script.js');
			wp_enqueue_script('script-codemirror', get_template_directory_uri(). '/admin/codemirror/codemirror.js');
			wp_enqueue_script('script-codemirror_hint', get_template_directory_uri(). '/admin/codemirror/addon/hint/show-hint.js');
			wp_enqueue_script('script-codemirror_hint', get_template_directory_uri(). '/admin/codemirror/addon/hint/xml-hint.js');
			wp_enqueue_script('script-codemirror_hint', get_template_directory_uri(). '/admin/codemirror/addon/hint/html-hint.js');
			wp_enqueue_script('script-codemirror_xml', get_template_directory_uri(). '/admin/codemirror/mode/xml/xml.js');
			wp_enqueue_script('script-codemirror_javascript', get_template_directory_uri(). '/admin/codemirror/mode/javascript/javascript.js');
			wp_enqueue_script('script-codemirror_css', get_template_directory_uri(). '/admin/codemirror/mode/css/css.js');
			wp_enqueue_script('script-codemirror_htmlmixed', get_template_directory_uri(). '/admin/codemirror/mode/htmlmixed/htmlmixed.js');
			wp_enqueue_script('script-handsontable', get_template_directory_uri(). '/admin/handsontable/handsontable.full.min.js');
		});

		// Configuration pages
		acf_add_options_page(array(
			'page_title' => 'Configurar BGH',
			'menu_title' => 'Configurar BGH',
			'menu_slug' => 'bgh-settings',
			//'capability' => 'edit_posts',
			'redirect' => true
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Home del sitio',
			'menu_title' => 'Home del sitio',
			'menu_slug' => 'bgh-settings-home',
			'parent_slug' => 'bgh-settings',
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Menu principal',
			'menu_title' => 'Menu principal',
			'menu_slug' => 'bgh-settings-menu-main',
			'parent_slug' => 'bgh-settings',
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Cabeceras de productos',
			'menu_title' => 'Cabeceras de productos',
			'menu_slug' => 'bgh-settings-product-header',
			'parent_slug' => 'bgh-settings',
		));

		acf_add_options_sub_page(array(
			'page_title' => 'Anuncios de productos',
			'menu_title' => 'Anuncios de productos',
			'menu_slug' => 'bgh-settings-product-ads',
			'parent_slug' => 'bgh-settings',
		));


		acf_add_options_sub_page(array(
			'page_title' => 'Parametros',
			'menu_title' => 'Parametros',
			'menu_slug' => 'bgh-parameters',
			'parent_slug' => 'bgh-settings',
		));

		// REB MOBILE

		acf_add_options_sub_page(array(
			'page_title' => 'Home Mobile',
			'menu_title' => 'Home Mobile',
			'menu_slug' => 'bgh-settings-home-mobile',
			'parent_slug' => 'bgh-settings',
		));

		// FIN REB MOBILE

		acf_add_options_page(array(
			'page_title' => 'Expertos en clima',
			'menu_title' => 'Expertos en clima',
			'menu_slug' => 'bgh-expertos-settings'
			//'capability' => 'edit_posts',
			// 'redirect' => true
		));



		// REB 000: ROL DEL USUARIO LOGUEADO

			function current_role_name() {
	 			$current_user = wp_get_current_user();
				$role = $current_user->roles[0];
				return $role;
			}

		// FIN REB 000


		// REB 111: Agregar columna de categoria y filtro al listado de productos

			// agregar columna de categoria
			function custom_columns_head($defaults) {
			    $defaults['product_categories_column'] = 'Categorias';
			    return $defaults;
			}
			add_filter('manage_posts_columns', 'custom_columns_head');

			function custom_columns_content($column_name, $post_ID) {
			    if ($column_name == 'product_categories_column') {
			    	$categories = wp_get_post_terms($post_ID, 'product_categories', array('fields' => 'ids'));
			    	$categories_str = "";
			    	foreach ($categories as $cat) {
			    		$tmp = get_term_by('id', $cat, 'product_categories');
						$categories_str = $categories_str . $tmp->name . ", ";
					}
					echo rtrim($categories_str,', ');
			    }
			}
			add_action('manage_posts_custom_column', 'custom_columns_content', 10, 2);

			//http://justintadlock.com/archives/2011/06/27/custom-columns-for-custom-post-types
			// hacer la columna de categoria ordenable
			function product_sortable_columns( $columns ) {
				$columns['product_categories_column'] = 'product_categories_column';
				return $columns;
			}
			add_filter( 'manage_edit-product_sortable_columns', 'product_sortable_columns' );

			// agregar filtro de categoria solo para administradores
			function product_add_taxonocustom_filters() {
				global $typenow;

				$taxonomies = array('product_categories');

				if( $typenow == 'product' ){

					foreach ($taxonomies as $tax_slug) {
						$tax_obj = get_taxonomy($tax_slug);
						$tax_name = $tax_obj->labels->name;
						$terms = get_terms($tax_slug);
						if(count($terms) > 0) {
							echo "<select name='$tax_slug' id='$tax_slug' class='postform' style='max-width:300px'>";
							echo "<option value=''>Mostrar todas las $tax_name</option>";
							foreach ($terms as $term) {
								echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' . $term->name .' (' . $term->count .')</option>';
							}
							echo "</select>";
						}
					}

				}

			}
			if(current_role_name()=="administrator"){
				add_action( 'restrict_manage_posts', 'product_add_taxonocustom_filters' );
			}

		// FIN REB 111


		// REB 222: Personalizar columnas de agentes

			function admin_agent_list_columns( $defaults ) {
			    $defaults['province'] = 'Provincia';
			    $defaults['locality'] = 'Localidad';
			    $defaults['product'] = 'Producto';
			    return $defaults;
			}
			add_filter('manage_agents_posts_columns', 'admin_agent_list_columns');

			function manage_agents_table_content( $column_name, $post_id ) {

				if ($column_name == 'province') {
					$tmp = get_post_meta($post_id,'province',true);

					echo $tmp;

					// echo get_provinces($tmp)["value"];
				}
				if ($column_name == 'locality') {
					echo get_post_meta($post_id,'locality',true);
				}
				if ($column_name == 'product') {

					$tmp = get_post_meta($post_id,'product',true);

					echo $tmp[0];
					// echo $tmp;// [0] // ver si va el 0 cuando es multi producto. Seguir manejando como array
					// echo get_agents_categories($tmp[0])["value"];
				}

			}
			add_action( 'manage_agents_posts_custom_column', 'manage_agents_table_content', 10, 2 );

		// FIN REB 222


		// REB 2b: Personalizar columnas de Donde Comprar

			function admin_dondecomprar_list_columns( $defaults ) {
			    $defaults['province'] = 'Provincia';
			    $defaults['locality'] = 'Localidad';
			    $defaults['sucursal'] = 'Sucursal';
			    $defaults['address'] = 'Dirección';
			    return $defaults;
			}
			add_filter('manage_donde-comprar_posts_columns', 'admin_dondecomprar_list_columns');

			function manage_dondecomprar_table_content( $column_name, $post_id ) {

				$fields = get_fields($post_id);

				if ($column_name == 'province') {
					$tmp = get_post_meta($post_id,'province',true);
					echo get_provinces($tmp)["value"];
				}
				if ($column_name == 'locality') {
					echo get_post_meta($post_id,'locality',true);
				}
				if ($column_name == 'sucursal') {
					echo get_post_meta($post_id,'empresa',true);
				}
				if ($column_name == 'address') {
					echo get_post_meta($post_id,'address',true);
				}

			}
			add_action( 'manage_donde-comprar_posts_custom_column', 'manage_dondecomprar_table_content', 10, 2 );

		// FIN REB 2b


		// REB 333: FILTRAR INTERNAMENTE LISTADOS Y FORMS EN BASE A ROLE CAPABILITIES

			// filtrar listados
			function add_post_format_filter_to_posts($query){
			    global $post_type, $pagenow;

			    if($pagenow == 'edit.php' && $post_type == 'product' && current_role_name()!="administrator"){ // LIMTAR CATEGORIAS DE PRODUCTOS

			    	$current_user = wp_get_current_user();

					$arr_caps = [];
			    	foreach ($current_user->allcaps as $name => $value) {
			    		$arr_name = explode("_", $name,2);
			    		if($arr_name[0]== 'product'){
			    			array_push($arr_caps,$arr_name[1]);
			    		}
					}

	                $query->query_vars['tax_query'] = array(
	                    array(
	                        'taxonomy' =>  'product_categories',
	                        'field' => 'slug',
	                        'terms' => $arr_caps //array('cocina','sss')
	                    )
	                );

			    }

			    if($pagenow == 'edit.php' && $post_type ==  'agents'){ // LIMITAR PRODUCTO DE AGENTES

			    	$current_user = wp_get_current_user();
					$meta_query = $query->get('meta_query');
					$meta_query_filters = [];
					$meta_query_tmp = [];
					$meta_query_tmp['relation'] = 'OR';

					/////---- SE COMENTAN ESTOS DOS BLOQUES PORQUE AHORA SOLO SE VEN CELULARES, MAS ALLA DE LOS PERMISOS

					// permiso custom
					// if(!array_key_exists("agent_all", $current_user->allcaps)) { // si tiene agent_all es el usu institucional, entonces muestro todo
				 	// 	foreach ($current_user->allcaps as $name => $value) {
				 	//		$arr_name = explode("_", $name,2); // limito a dos porque puede venir agent_tv_video y el param seria tv_video
				 	// 		if($arr_name[0]== 'agent'){
					// 			$tmp = [];
					// 			$tmp['relation'] = 'product';
					// 			$tmp['value'] = $arr_name[1];
					// 			$tmp['compare'] = 'LIKE';
					// 			array_push($meta_query_tmp,$tmp);
				 	// 		}
					// 	}
					// 	array_push($meta_query_filters,$meta_query_tmp);
					// 	// si no insterto el filtro, entonces mantiene el query como venia
					// 	$query->set('meta_query',$meta_query_filters);
					// }
					//

					/////----

		    			$tmp = [];
						$tmp['relation'] = 'product';
						$tmp['value'] = 'celulares';
						$tmp['compare'] = 'LIKE';
						array_push($meta_query_tmp,$tmp);
						array_push($meta_query_filters,$meta_query_tmp);

						$query->set('meta_query',$meta_query_filters);

					/////----


			    }

			}
			add_action('pre_get_posts', 'add_post_format_filter_to_posts');


			// filtrar forms
			function acf_load_product_field_choices( $field ) {

				$current_user = wp_get_current_user();

				$field['choices'] = array();

				if(current_role_name()!="administrator" && !array_key_exists("agent_all", $current_user->allcaps)){

					// SE COMENTAN ESTOS DOS BLOQUES PORQUE AHORA SOLO SE CARGAN CELULARES, MAS ALLA DE LOS PERMISOS
			  		// foreach ($current_user->allcaps as $name => $value) {
			  		// 	$arr_name = explode("_", $name,2); // limito a dos porque puede venir agent_tv_video y el param seria tv_video
			  		//  if($arr_name[0]== 'agent'){
					// 		$field['choices'][ $arr_name[1] ] = get_agents_categories($arr_name[1]);
			  		//   }
					// }

					$tmp["celulares"] = "Celulares";
					$field['choices'] = $tmp;

				}
				else{

					// SE COMENTAN ESTOS DOS BLOQUES PORQUE AHORA SOLO SE CARGAN CELULARES, MAS ALLA DE LOS PERMISOS
					// $tmp = [];
					// foreach (get_agents_categories() as $item) {
					// 	$tmp[$item["name"]] = $item["value"];
					// }

					$tmp["celulares"] = "Celulares";
					$field['choices'] = $tmp;

				}

				return $field;
			}
			add_filter('acf/load_field/name=product', 'acf_load_product_field_choices'); // TODO: product es el FIELD, se podria poner un nombre de field mas especifico tipo agents_product, pero cambiaria valores actuales

		// FIN REB 333


		// REB 911

			// filtrar forms
			function acf_load_province_field_choices( $field ) {

				$field['choices'] = array();

				$provinces = get_provinces();
		    	foreach ($provinces as $item) {
					$field['choices'][ $item['name'] ] = $item['value'];
				}

				return $field;
			}
			add_filter('acf/load_field/name=province', 'acf_load_province_field_choices'); // TODO: product es el FIELD, se podria poner un nombre de field mas especifico tipo agents_product, pero cambiaria valores actuales

		// FIN REB 911


	}
	// *** END ADMIN


	# Frontend
	# ----------------------------------
	if (!is_admin()) {

		add_filter( 'get_origin', 'fn_get_origin' );
		function fn_get_origin() {

			session_start();

			$server_name =  $_SERVER['SERVER_NAME'];
			$server_name_parts = explode(".", $server_name);
			$server_name_origin = $server_name_parts[count($server_name_parts)-1];

			$server_name_origin = strtolower($server_name_origin);

			if(!isset($server_name_origin) || $server_name_origin == ''){
				$origin = 'ar';
			}
			else{
				$origin = $server_name_origin;
			}

			if($origin == 'cl'){
				$origin = 'ch';
			}

			// echo $server_name_origin;

			// if(isset($_GET['origin'])){
			// 	$origin = $_GET['origin'];
			// 	$_SESSION['origin'] = $origin;
			// }
			// else if(isset($_SESSION['origin'])){
			// 	$origin = $_SESSION['origin'];
			// }
			// else{
			// 	$origin = 'ar';
			// }


			//TESTINGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGGG
			// $origin = 'ar';

		    return $origin;
		}


		# Hide stuff
		# ----------------------------------
		// Clean head
		// remove_action('wp_head','rsd_link');
		// remove_action('wp_head','wlwmanifest_link');
		// remove_action('wp_head','index_rel_link');
		// remove_action('wp_head','wp_generator');

		// Remove admin bar
		// add_filter('show_admin_bar', function() { return false; });

		// Remove edit post link
		add_filter('edit_post_link', function() { return false; });

		# Scripts
		# ----------------------------------
		// Add admin additional styles/scripts
		add_action('wp_enqueue_scripts', function() {
			wp_enqueue_style('style-reset', get_template_directory_uri() . '/css/html5reset.css');
			wp_enqueue_style('style-fonts', get_template_directory_uri() . '/css/fonts/fonts.css');
			wp_enqueue_style('style-custom', get_template_directory_uri() . '/css/custom.css', null, rand());
			wp_enqueue_style('style-catalog', get_template_directory_uri() . '/css/catalog.css', null, rand());
			wp_enqueue_style('style-fonts', 'http://fonts.googleapis.com/css?family=Asap:400,700');
			//wp_enqueue_script('lib-lodash', get_template_directory_uri() . '/js/lib/lodash.js');
			//wp_enqueue_script('application', get_template_directory_uri() . '/js/application.js?' . rand(0, 100));
		});

		# Rewrite
		# ----------------------------------
		add_action('parse_request', function($wp) {
			global $post_type_any;


			$wp->request = str_replace("%20","-",$wp->request);


			$parts = explode('/', $wp->request);

			// Remove parts
			if (end($parts) == 'hisense'){
				unset($parts[count($parts) - 1]);
			}

			$element = end($parts);


			// var_dump($parts);

			if ($element) {

				// Is brand
				$brands = get_posts(array('post_type' => 'brand'));
				$brand_selected = null;
				foreach ($brands as $brand) {
					if ($element == $brand->post_name && $parts[count($parts) - 2] != $element) {
						$brand_selected = $brand;
						$brand_selected->logo = (object)get_field('logo', $brand->ID);
						$brand_selected->logo = $brand_selected->logo->url;
						$element = $parts[count($parts) - 2];
					}
				}

				// Is single
				$post = get_posts(array('name' => $element, 'post_type' => $post_type_any));
				if (count($post) > 0) {
					$post = $post[0];
					$post_type = get_post_type_object($post->post_type);

					$wp->query_vars = array(
						'page' => '',
						'product' => $element,
						'post_type' => $post->post_type,
						'name' => $element
					);
					$wp->request = $post_type->rewrite['slug'] . '/' . $element;
					$wp->matched_rule = $post_type->rewrite['slug'] . '/([^/]+)(/[0-9]+)?/?$';
					$wp->matched_query = $post->post_type . '=' . $post_type->rewrite['slug'] . '&page=';
				} else {





					// Is Category
					$taxonomies = get_taxonomies(array('public' => true, '_builtin' => false), 'names', 'and');
					if ($taxonomies){

						// echo "#$###";
						// var_dump($taxonomy);
						// echo "#$###";

						foreach ($taxonomies as $taxonomy) {
							foreach (get_terms($taxonomy, array('hide_empty' => false)) as $term) {

								// var_dump($term->slug);

								if ($term->slug == $element) {

									// REB
									$taxonocustom_list = get_terms($taxonomy);
									$taxonocustom_term = $term;
									$taxonomy_term = $term;
									$taxonomy_list = get_terms($taxonomy);

									$file = 'category-' . str_replace('_categories', '', $taxonocustom_term->taxonomy) . '.php';
									if (!file_exists(get_template_directory() . '/' . $file)){
										echo "<h1>Error</h1>File missing: " . $file;
									}
									else {
										require 'category.php';
										require $file;
									}

									die;

								}

							}
						}
					}

					// Is static
					$files = scandir(get_template_directory());
					foreach ($files as $file) {
						if (strpos($file, 'static-') === 0){
							if ($file == 'static-' . $element . '.php') {
								require get_template_directory() . '/' . $file;

								die;

							}
						}
					}

				}

			}
		});

	}



function getBaseUrl(){

    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['SERVER_NAME']; //$_SERVER['HTTP_HOST'];

}



add_action('cron_expertos', 'cron_expertos_function');

function cron_expertos_function() {

	$now = date('Y-m-d H:i:s');

	$logfile = TEMPLATEPATH . '/cron_log.txt';

	$filename = 'static-expertos-cursos-cron.php';

	$logged = file_put_contents($logfile, "\n" . $now . "> " . "RUN " . $filename , FILE_APPEND);

	include 'static-expertos-cursos-cron.php';

}

