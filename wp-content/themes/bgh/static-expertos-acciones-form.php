<?php include 'expertos-session.php'; ?>
<?php

    function insert_attachment($file_handler, $post_id){
        if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();
        require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        require_once(ABSPATH . "wp-admin" . '/includes/file.php');
        require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        $attach_id = media_handle_upload( $file_handler, $post_id );
        return $attach_id;
    }


	function removeP($str){
		if($str[0]=='<'){
			return substr(substr($str, 3), 0, -5);
		}
		else{
			return $str;
		}
	}


    if(isset($_REQUEST['action'])){
    	$action = $_REQUEST['action'];
    }
    else{
    	$action = '';
    }

	if(isset($_REQUEST['id'])){
    	$id = intval($_REQUEST['id']);
    }
    else{
    	$id = 0;
    }


  	// estado de accion
    // $field = get_field_object("field_568d5d8014cf3");
    // if($field){
    //     foreach( $field['choices'] as $k => $v ){
    //         $accion_estado[$k] = $v;
    //     }
    // }

    $accion_estado['pendent'] = 'Pendiente de aprobación';
    $accion_estado['preapproved'] = 'Pre Aprobada';
    $accion_estado['approved'] = 'Aprobada';
    $accion_estado['rejected'] = 'Rechazada';
    $accion_estado['actionobserved'] = 'Acción Observada';
    $accion_estado['executionobserved'] = 'Ejecución Observada';

    $accion_estado_descripcion['pendent'] = 'Su solicitud aún no fue revisada por BGH';
    $accion_estado_descripcion['preapproved'] = 'Se encuentra habilitado para proceder con la ejecución';
    $accion_estado_descripcion['approved'] = 'Se hace efectiva la nota de crédito';
    $accion_estado_descripcion['rejected'] = 'La acción NO cumple con los lineamientos y/o manual de marca';
    $accion_estado_descripcion['actionobserved'] = 'Deberá enviar información/documentación complementaria';
    $accion_estado_descripcion['executionobserved'] = 'Deberá enviar información/documentación complementaria';



    // tipo de accion
    $field = get_field_object("field_568d5c79c869f");
    if($field){
        foreach( $field['choices'] as $k => $v ){
            $accion_tipo[$k] = $v;
        }
    }


    $message = "";
    if(isset($_REQUEST['message'])){
    	$message = $_REQUEST['message'];
    }

	switch ($action) {

		case 'insert':

	        $title = $_REQUEST['field_568d5c99c86a0']; // nombre de la accion
	        $content = "-";

	        $args = array(
	            'post_type' => 'expertos-accion',
	            'post_status' => 'publish', //publish
	            'post_title'    => htmlentities($title),
	            'post_content'  => htmlentities($content),
	            'post_category' => array(),
	            // 'post_author'   => 999,
	            'tags_input' => htmlentities('imported,accion')
	        );
	        $id = wp_insert_post($args);

	        if($id){

	            $message = "Su acción fue enviada. El número de acción es  <strong>" . $id . "</strong>";

	            update_field('field_568d5d8014cf3', 'pendent', $id); // fuerzo la accion a pendiente

	            foreach ($_POST as $field => $value){
	                if (strpos($field,'field_') !== false) {
	                    update_field($field, $value, $id);
	                }
	            }

	            foreach ($_FILES as $field => $array) {
	                if(!empty($_FILES[$field]['name'])){
	                    $file_id = insert_attachment($field,$id);
	                    // $fileurl = wp_get_attachment_url($file_id);
	                    update_field($field, $file_id, $id);
	                }
	            }



				showForm($user,$id,$message,$accion_estado,$accion_tipo,$accion_estado_descripcion);


  				// OBTENGO LOS DATOS DEL POST GUARDADO PARA ENVIAR MAIL

				$post = new stdClass();
				$post->ID = $id;
				$post->email = get_field('accion_email', $post->ID);
				$post->nombre = get_field('accion_nombre', $post->ID);
				$post->empresa = get_field('accion_empresa', $post->ID);
				$post->nombreyapellido = get_field('accion_nombreyapellido', $post->ID);
				// $post->observaciones = get_field('accion_observaciones', $post->ID); DEBERIAMOS ENVIAR LAS HISTORIAL OBSERVACIONES AL MAIL
				$post->estado = 'pendent'; //get_field('accion_estado', $post->ID);
	        	$post->tipo = get_field('accion_tipo', $post->ID);

				$subject = 'BGH Climatización Profesional - Acción creada: ' .$post->nombre;

				$body = "<b>Acción:</b> " . $post->nombre;
				$body .= "<br><b>Nombre:</b> " . $post->nombreyapellido;
				$body .= "<br><b>Empresa:</b> " . $post->empresa;
				$body .= "<br><b>Tipo:</b> " . $accion_tipo[$post->tipo];
				// $body .= "<br><b>Estado:</b> <span style='color:blue'>" . $accion_estado[$post->estado] . "</span>";
				// $body .= "<br><a href='". get_edit_post_link( $post->ID ) . "'>Ver acción</a>\n\n";

				// wp_mail('rodrigo.butta@egoargentina.com', $subject, $body );

				$fields = array(
				   'to' => 'dash.egoagency@gmail.com,marketingaac@bgh.com.ar,showroomaac@gmail.com',
				   'subject' => $subject,
				   'body' => $body
				);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, site_url() . '/service/mail.php');
				curl_setopt($ch, CURLOPT_POST, count($fields));
				curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
				$result = curl_exec($ch);
				curl_close($ch);


		        // header('Location: /expertos-acciones-form/?id=' . $id . urlencode('&message=la acción fue enviada'));

	        }


			break;

		case 'update':

			$message = "Su acción fue actualizada.";
            // echo "Accion actualizada id " . $id;

            foreach ($_POST as $field => $value){
                if (strpos($field,'field_') !== false) {
                    update_field($field, $value, $id);
                }
            }

            foreach ($_FILES as $field => $array) {
                if(!empty($_FILES[$field]['name'])){
                    $file_id = insert_attachment($field,$id);
                    // $fileurl = wp_get_attachment_url($file_id);
                    update_field($field, $file_id, $id);
                }
        	}

        	showForm($user,$id,$message,$accion_estado,$accion_tipo,$accion_estado_descripcion);


			// OBTENGO LOS DATOS DEL POST GUARDADO PARA ENVIAR MAIL

			$post = new stdClass();
			$post->ID = $id;
			$post->email = get_field('accion_email', $post->ID);
			$post->nombre = get_field('accion_nombre', $post->ID);
			$post->empresa = get_field('accion_empresa', $post->ID);
			$post->nombreyapellido = get_field('accion_nombreyapellido', $post->ID);
			// $post->observaciones = get_field('accion_observaciones', $post->ID); DEBERIAMOS ENVIAR LAS HISTORIAL OBSERVACIONES AL MAIL
			$post->estado = get_field('accion_estado', $post->ID);
        	$post->tipo = get_field('accion_tipo', $post->ID);

        	$subject = 'BGH Climatización Profesional - Acción actualizada: ' .$post->nombre;

			$body = "<b>Acción:</b> " . $post->nombre;
			$body .= "<br><b>Nombre:</b> " . $post->nombreyapellido;
			$body .= "<br><b>Empresa:</b> " . $post->empresa;
			$body .= "<br><b>Tipo:</b> " . $accion_tipo[$post->tipo];
			// $body .= "<br><b>Estado:</b> <span style='color:blue'>" . $accion_estado[$post->estado] . "</span>";
			// $body .= "<br><a href='". get_edit_post_link( $post->ID ) . "'>Ver acción</a>\n\n";

			// echo get_edit_post_link( $post->ID );

			// wp_mail('rodrigo.butta@egoargentina.com', $subject, $body );

			$fields = array(
			   'to' => 'dash.egoagency@gmail.com,Sabrina.Policano@bgh.com.ar',
			   'subject' => $subject,
			   'body' => $body
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, site_url() . '/service/mail.php');
			curl_setopt($ch, CURLOPT_POST, count($fields));
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
			$result = curl_exec($ch);
			curl_close($ch);

			break;

		default:
			showForm($user,$id,$message,$accion_estado,$accion_tipo,$accion_estado_descripcion);
			break;
	}



	function showForm($user,$id=0,$message='',$accion_estado,$accion_tipo,$accion_estado_descripcion){

		if($id!=0){

			$post = get_posts(array(
	            'numberposts'   => 1,
	            'post_type'     => 'expertos-accion',
	            'post__in' => array($id)
	        ));

	        $post = (object)$post[0];

	        $post->id = $post->ID;
            $post->name = $post->post_name;
            $post->title = $post->post_title;

            $post->nombreyapellido = get_field('accion_nombreyapellido', $post->ID);
            $post->empresa = get_field('accion_empresa', $post->ID);
            $post->email = get_field('accion_email', $post->ID);
            $post->telefono = get_field('accion_telefono', $post->ID);
            $post->nombre = get_field('accion_nombre', $post->ID);
            $post->importe = get_field('accion_importe', $post->ID);
            // $post->fecha = get_field('accion_fecha', $post->ID);
            $post->observaciones = removeP(get_field('accion_observaciones', $post->ID));
			$post->importe = get_field('accion_importe', $post->ID);
			$post->tipo = get_field('accion_tipo', $post->ID);
            $post->especifique = get_field('accion_tipo_otro', $post->ID);
            // $post->asistentes = get_field('accion_asistentes', $post->ID);
			$post->telefono = get_field('accion_telefono', $post->ID);

			// $post->estado = get_field('accion_estado', $post->ID);
			$post->historial = get_field('accion_historial', $post->ID);
			$post->historial_final = sizeof($post->historial)-1;
			$post->estado = $post->historial[$post->historial_final]["accion_historial_estado"];
			$post->historial_observacion = $post->historial[$post->historial_final]["accion_historial_texto"];

			// si es alta no tiene bitacora, entonces dejo pendiente por default
			if(!isset($post->estado)){
				$post->estado='pendent';
			}


			$post->file_diseno = (object)get_field('accion_file_diseno', $post->ID);
			$post->file_presupuesto_1 = (object)get_field('accion_file_presupuesto_1', $post->ID);
			$post->file_presupuesto_2 = (object)get_field('accion_file_presupuesto_2', $post->ID);
			$post->file_presupuesto_3 = (object)get_field('accion_file_presupuesto_3', $post->ID);
			$post->file_foto = (object)get_field('accion_file_foto', $post->ID);
			$post->file_factura = (object)get_field('accion_file_factura', $post->ID);

            $post->usuario = get_field('accion_usuario', $post->ID);
            $post->userid = $post->usuario->ID;


            $post->reconocido = get_field('accion_reconocido', $post->ID);
			$post->observaciones_adicionales = get_field('accion_observaciones_adicionales', $post->ID);

			$post->expertos_accion_capacitacion_tema = get_field('expertos_accion_capacitacion_tema', $post->ID);
			$post->expertos_accion_capacitacion_asistentes = get_field('expertos_accion_capacitacion_asistentes', $post->ID);
			$post->expertos_accion_capacitacion_lugar = get_field('expertos_accion_capacitacion_lugar', $post->ID);
			$post->expertos_accion_capacitacion_fecha = get_field('expertos_accion_capacitacion_fecha', $post->ID);
			$post->expertos_accion_exposicion_lugar = get_field('expertos_accion_exposicion_lugar', $post->ID);
			$post->expertos_accion_exposicion_fecha = get_field('expertos_accion_exposicion_fecha', $post->ID);
			$post->expertos_accion_exposicion_nombre = get_field('expertos_accion_exposicion_nombre', $post->ID);
			$post->expertos_accion_carteleria_tipo = get_field('expertos_accion_carteleria_tipo', $post->ID);
			$post->expertos_accion_carteleria_cantidad = get_field('expertos_accion_carteleria_cantidad', $post->ID);
			$post->expertos_accion_carteleria_dimension = get_field('expertos_accion_carteleria_dimension', $post->ID);
			$post->expertos_accion_ploteo_marca = get_field('expertos_accion_ploteo_marca', $post->ID);
			$post->expertos_accion_ploteo_modelo = get_field('expertos_accion_ploteo_modelo', $post->ID);
			$post->expertos_accion_exhibidores_equipos = get_field('expertos_accion_exhibidores_equipos', $post->ID);
			$post->expertos_accion_exhibidores_cantidad = get_field('expertos_accion_exhibidores_cantidad', $post->ID);
			$post->expertos_accion_medios_tipo_medio = get_field('expertos_accion_medios_tipo_medio', $post->ID);
			$post->expertos_accion_medios_nombre = get_field('expertos_accion_medios_nombre', $post->ID);
			$post->expertos_accion_medios_tipo_pauta = get_field('expertos_accion_medios_tipo_pauta', $post->ID);
			$post->expertos_accion_medios_periodo = get_field('expertos_accion_medios_periodo', $post->ID);

			$post->expertos_accion_equipos_nombre_1 = get_field('expertos_accion_equipos_nombre_1', $post->ID);
			$post->expertos_accion_equipos_nombre_2 = get_field('expertos_accion_equipos_nombre_2', $post->ID);
			$post->expertos_accion_equipos_nombre_3 = get_field('expertos_accion_equipos_nombre_3', $post->ID);
			$post->expertos_accion_equipos_nombre_4 = get_field('expertos_accion_equipos_nombre_4', $post->ID);
			$post->expertos_accion_equipos_nombre_5 = get_field('expertos_accion_equipos_nombre_5', $post->ID);
			$post->expertos_accion_equipos_cantidad_1 = get_field('expertos_accion_equipos_cantidad_1', $post->ID);
			$post->expertos_accion_equipos_cantidad_2 = get_field('expertos_accion_equipos_cantidad_2', $post->ID);
			$post->expertos_accion_equipos_cantidad_3 = get_field('expertos_accion_equipos_cantidad_3', $post->ID);
			$post->expertos_accion_equipos_cantidad_4 = get_field('expertos_accion_equipos_cantidad_4', $post->ID);
			$post->expertos_accion_equipos_cantidad_5 = get_field('expertos_accion_equipos_cantidad_5', $post->ID);

			// var_dump($post->expertos_accion_equipos_nombre_1);

		}
		else{

			$post = new stdClass();
			$post->nombreyapellido = $user->nombre;
			$post->empresa = $user->empresa;
			$post->email = $user->email;
			$post->telefono = $user->telefono;
			$post->userid = $user->id;


		}


		if( !isset($post->estado) ){
			$disabledclass="";
		}
		else{

			if( isset($post->estado) && ($post->estado == 'pendent' || $post->estado == 'actionobserved') ){
				$disabledclass="";
			}
			else{
				$disabledclass="disabled";
			}

		}



 		// var_dump($post->historial);

		?>


		<?php get_header(); ?>
		<body <?php body_class(); ?>
		<?php require 'menu.php'; ?>
		    <div class="full-row" style="margin-top:100px">
		        <div class="content-wrapper">

		            <section id="expertos">
		               <div class="wrap">
		                    <?php require 'expertos-sidebar.php'; ?>
		                    <div class="main">
								<h2>Acciones de marketing<br>
								<?php
								if($id==0){
									?>
									<strong>Nueva Acción </strong>
									<?php
								}
								else{
									?>
									<strong>Editar Acción </strong>
									<?php
								}
								?>
								<a href="/expertos-acciones" class="boton-right">Volver</a></h2>

								<div id="acciones_lista_wrapper">
									<ul id="acciones_lista">
										<?php
										foreach ($post->historial as $item) {
											?>
											<li>
												<div class="accion-fecha"><?=$item["accion_historial_fecha"]?></div>
												<div class="accion-estado tooltip"><?=$accion_estado[$item["accion_historial_estado"]]?></div>
												<div class="accion-observacion"><?=$item["accion_historial_texto"]?></div>
											</li>
											<?php
										}
										?>

									</ul>
								</div>

					            <div class="wp-videos" id="frm_wrapper">

									<?php
									if($id!=0){

									//	if(isset($post->historial_observacion) && $post->historial_observacion!=''){
											?>
											<!-- <div class="top-message"><?=$post->historial_observacion?></div> -->
											<?php
									//	}
									//	if(isset($post->importe) && $post->importe!=''){
											?>
											<!-- <div class="top-message">Importe solicitado: <strong style="color: #79a70a">AR$<?=$post->importe?></strong></div> -->
											<?php
									//	}
									//	if(isset($post->reconocido) && $post->reconocido!=''){
											?>
											<!-- <div class="top-message">Importe reconocido: <strong style="color: #79a70a">AR$<?=$post->reconocido?></strong></div> -->
											<?php
									//	}
										if(isset($post->observaciones_adicionales) && $post->observaciones_adicionales!=''){
											?>
											<div class="top-message"><?=$post->observaciones_adicionales?></div>
											<?php
										}
										if(isset($message) && $message!=''){
											?>
											<div class="top-message top-message-ok"><?=$message?></div>
											<?php
										}

									}
									?>
									<p style="font-size: 12px; padding-left: 5px; color: #07b152;">(*) Campos obligatorios</p>
					                <div class="wp-box-video" style="display:block">
					                    <div class="form-acciones">
					                    	<form id="frm_accion" method="POST" enctype="multipart/form-data">
							            		<input type="hidden" name="field_569fa9caf97d6" value="<?=$post->userid?>" />
									            <input type="hidden" name="id" value="<?=$id?>" />

												<fieldset class="<?=$disabledclass?>">
									                <input type="text" class="required" name="field_568d5c36c869b" placeholder="Nombre y Apellido (*)" value="<?=$post->nombreyapellido?>">
									                <input type="text" class="required" name="field_568d5c45c869c" placeholder="Empresa (*)" value="<?=$post->empresa?>">
									                <input type="email" class="required" name="field_568d5c51c869d" placeholder="E-mail (*)" value="<?=$post->email?>">
									                <input type="text" class="required" name="field_568d5c5ac869e" placeholder="Teléfono (*)" value="<?=$post->telefono?>">
									            </fieldset>

									            <fieldset class="<?=$disabledclass?>">


									                <div class="expertos-acciones-label">Nombre de la acción:</div><input type="text" name="field_568d5c99c86a0" placeholder="Nombre de la acción (*)" value="<?=$post->nombre?>" class="required" style="width: 485px;">
									                <div class="expertos-acciones-label">Tipo de acción:</div>
									            	<div class="drop"  style="width: 520px;">
					                                	<!-- <h4>Tipo de acción a realizar</h4> -->
					                                    <div class="select tipo" style="width: 520px;">
					                                    <select name="field_568d5c79c869f" onchange="this.style.color='#565656'" class="required" id="accion_tipo">
					                                    	<option value="" selected disabled>Tipo de Acción (*)</option>
					                                    	<?php
										                    $field = get_field_object("field_568d5c79c869f");
										                    if($field){
										                        foreach( $field['choices'] as $k => $v ){
										                        	$selected='';
										                        	if($post->tipo==$k) $selected='selected';
										                            echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
										                        }
										                    }
										                    ?>
										                    <option value="otro">Otro..</option>
					                                    </select>
					                                    </div>
					                                </div>
					                                <div class="expertos-acciones-label tipo-custom">Especifique:</div>
					                                <input type="text" name="field_56a77f2bf77f4" placeholder="" value="<?=$post->especifique?>" id="tipo_custom" class="tipo-custom">


					                                <input class="expertos-accion-extended accion-capacitacion required" type="text" name="field_570fb1307e976" placeholder="Tema (*)" value="<?=$post->expertos_accion_capacitacion_tema?>"  >
					                                <input class="expertos-accion-extended accion-capacitacion required" type="text" name="field_570fb1827e977" placeholder="Cantidad de Asistentes (*)" value="<?=$post->expertos_accion_capacitacion_asistentes?>"  >
					                                <input class="expertos-accion-extended accion-capacitacion required" type="text" name="field_570fb19b7e978" placeholder="Lugar (*)" value="<?=$post->expertos_accion_capacitacion_lugar?>"  >
					                                <input class="expertos-accion-extended accion-capacitacion required" type="text" name="field_570fb1b77e979" placeholder="Fecha (*)" value="<?=$post->expertos_accion_capacitacion_fecha?>"  >


													<input class="expertos-accion-extended accion-exposicion required" type="text" name="field_570fb1f07e97b" placeholder="Lugar (*)" value="<?=$post->expertos_accion_exposicion_lugar?>"  >
													<input class="expertos-accion-extended accion-exposicion required" type="text" name="field_570fb21a7e97c" placeholder="Fecha (*)" value="<?=$post->expertos_accion_exposicion_fecha?>"  >
													<input class="expertos-accion-extended accion-exposicion required" type="text" name="field_570fb3ec61937" placeholder="Nombre de la exposición (*)" value="<?=$post->expertos_accion_exposicion_nombre?>"  >


					                                <div class="expertos-accion-extended accion-carteleria drop">
					                                    <div class="select" style="width: 300px;">
					                                    <select name="field_570fb42d61939" onchange="this.style.color='#565656'" class="required">
					                                    	<option value="" selected disabled>Tipo (*)</option>
					                                       <?php
									                        $field = get_field_object("field_570fb42d61939");
									                        if($field){
									                            foreach( $field['choices'] as $k => $v ){
									                            	$selected='';
										                        	if($post->expertos_accion_carteleria_tipo==$k) $selected='selected';
									                                echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
									                            }
									                        }
									                        ?>
					                                    </select>
					                                    </div>
					                                </div>

					                                <div class="expertos-acciones-label expertos-accion-extended accion-carteleria">Cantidad Total de Piezas (*)</div>
					                                <input class="expertos-accion-extended accion-carteleria required" type="text" name="field_570fb45f6193a" placeholder="Cantidad Total de Piezas (*)" value="<?=$post->expertos_accion_carteleria_cantidad?>"  >
													<textarea class="expertos-accion-extended accion-carteleria required" name="field_570fb4a16193b" placeholder="Dimensión de cada pieza (*)" ><?=$post->expertos_accion_carteleria_dimension?></textarea>


													<input class="expertos-accion-extended accion-ploteo required" type="text" name="field_570fb4df6193d" placeholder="Marca (*)" value="<?=$post->expertos_accion_ploteo_marca?>"  >
													<input class="expertos-accion-extended accion-ploteo required" type="text" name="field_570fb4f96193e" placeholder="Modelo (*)" value="<?=$post->expertos_accion_ploteo_modelo?>"  >

													<div class="expertos-acciones-label expertos-accion-extended accion-exhibidores">Equipos a exhibir (*)</div>
													<textarea class="expertos-accion-extended accion-exhibidores required" name="field_570fb51761940" placeholder="Equipos a exhibir (*)" ><?=$post->expertos_accion_exhibidores_equipos?></textarea>

													<div class="expertos-acciones-label expertos-accion-extended accion-exhibidores">Cantidad total de exhibidores (*)</div>
													<input class="expertos-accion-extended accion-exhibidores required" type="text" name="field_570fb54061941" placeholder="Cantidad total de exhibidores (*)" value="<?=$post->expertos_accion_exhibidores_cantidad?>"  >


													<div class="expertos-accion-extended accion-medios drop">
					                                    <div class="select" style="width: 300px;">
					                                    <select name="field_570fb57461943" onchange="this.style.color='#565656'" class="required">
					                                    	<option value="" selected disabled>Tipo de Medio (*)</option>
					                                       <?php
									                        $field = get_field_object("field_570fb57461943");
									                        if($field){
									                            foreach( $field['choices'] as $k => $v ){
									                            	$selected='';
										                        	if($post->expertos_accion_medios_tipo_medio==$k) $selected='selected';
									                                echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
									                            }
									                        }
									                        ?>
					                                    </select>
					                                    </div>
					                                </div>
					                                <input class="expertos-accion-extended accion-medios required" type="text" name="field_570fb5ac61944" placeholder="Nombre del medio (*)" value="<?=$post->expertos_accion_medios_nombre?>"  >
													<div class="expertos-accion-extended accion-medios drop">
					                                    <div class="select" style="width: 300px;">
					                                    <select name="field_570fb5c261945" onchange="this.style.color='#565656'" class="required">
					                                    	<option value="" selected disabled>Tipo de Pauta (*)</option>
					                                       <?php
									                        $field = get_field_object("field_570fb5c261945");
									                        if($field){
									                            foreach( $field['choices'] as $k => $v ){
									                            	$selected='';
										                        	if($post->expertos_accion_medios_tipo_pauta==$k) $selected='selected';
									                                echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
									                            }
									                        }
									                        ?>
					                                    </select>
					                                    </div>
					                                </div>
					                                <input class="expertos-accion-extended accion-medios required" type="text" name="field_570fb5fb61946" placeholder="Período (*)" value="<?=$post->expertos_accion_medios_periodo?>"  >

													<div class="expertos-accion-extended accion-equipos">
														<div class="expertos-accion-inlinehelp">
															<p>¿Cuál es la política de los equipos en exhibición?</p>
															<ul>
																<li>20% dto sobre precio y descuento habitual (sujeto a crédito)</li>
																<li>Plazo de pago 0-180 días</li>
																<li>Se permite retirar 1 equipo por modelo por año por local de venta</li>
																<li>Sujeto a stock</li>
															</ul>
														</div>
													</div>
												<!-- 	<div class="expertos-accion-extended accion-equipos expertos-acciones-label">Equipo:</div>
													<input class="expertos-accion-extended accion-equipos required" type="text" name="field_574363a15214e" placeholder="Equipo (*)" value="<?=$post->expertos_accion_equipos_nombre_1?>"  style="width:72%">
					                                <input class="expertos-accion-extended accion-equipos required" type="text" name="field_574363d152153" placeholder="Cantidad (*)" value="<?=$post->expertos_accion_equipos_cantidad_1?>"   style="width:13%">
													<div class="expertos-accion-extended accion-equipos expertos-acciones-label">Equipo:</div>
													<input class="expertos-accion-extended accion-equipos " type="text" name="field_574363a85214f" placeholder="Otro equipo" value="<?=$post->expertos_accion_equipos_nombre_2?>"  style="width:72%">
					                                <input class="expertos-accion-extended accion-equipos " type="text" name="field_574363da52154" placeholder="Cantidad" value="<?=$post->expertos_accion_equipos_cantidad_2?>"   style="width:13%">
													<div class="expertos-accion-extended accion-equipos expertos-acciones-label">Equipo:</div>
													<input class="expertos-accion-extended accion-equipos " type="text" name="field_574363b252150" placeholder="Otro equipo" value="<?=$post->expertos_accion_equipos_nombre_3?>"  style="width:72%">
					                                <input class="expertos-accion-extended accion-equipos " type="text" name="field_574363e352155" placeholder="Cantidad" value="<?=$post->expertos_accion_equipos_cantidad_3?>"   style="width:13%">
													<div class="expertos-accion-extended accion-equipos expertos-acciones-label">Equipo:</div>
													<input class="expertos-accion-extended accion-equipos " type="text" name="field_574363ba52151" placeholder="Otro equipo" value="<?=$post->expertos_accion_equipos_nombre_4?>"  style="width:72%">
					                                <input class="expertos-accion-extended accion-equipos " type="text" name="field_574363ea52156" placeholder="Cantidad" value="<?=$post->expertos_accion_equipos_cantidad_4?>"   style="width:13%">
													<div class="expertos-accion-extended accion-equipos expertos-acciones-label">Equipo:</div>
													<input class="expertos-accion-extended accion-equipos " type="text" name="field_574363c352152" placeholder="Otro equipo" value="<?=$post->expertos_accion_equipos_nombre_5?>"  style="width:72%">
					                                <input class="expertos-accion-extended accion-equipos " type="text" name="field_574363f252157" placeholder="Cantidad" value="<?=$post->expertos_accion_equipos_cantidad_5?>"   style="width:13%">
-->

													<div class="expertos-accion-extended accion-equipos expertos-acciones-label">Equipo:</div>
													<input class="expertos-accion-extended accion-equipos required" type="text" name="field_574362d9101cb" placeholder="Equipo (*)" value="<?=$post->expertos_accion_equipos_nombre_1?>"  style="width:72%">
					                                <input class="expertos-accion-extended accion-equipos required" type="text" name="field_57436326101d0" placeholder="Cantidad (*)" value="<?=$post->expertos_accion_equipos_cantidad_1?>"   style="width:13%">
													<div class="expertos-accion-extended accion-equipos expertos-acciones-label">Equipo:</div>
													<input class="expertos-accion-extended accion-equipos " type="text" name="field_574362f9101cc" placeholder="Otro equipo" value="<?=$post->expertos_accion_equipos_nombre_2?>"  style="width:72%">
					                                <input class="expertos-accion-extended accion-equipos " type="text" name="field_57436335101d1" placeholder="Cantidad" value="<?=$post->expertos_accion_equipos_cantidad_2?>"   style="width:13%">
													<div class="expertos-accion-extended accion-equipos expertos-acciones-label">Equipo:</div>
													<input class="expertos-accion-extended accion-equipos " type="text" name="field_57436301101cd" placeholder="Otro equipo" value="<?=$post->expertos_accion_equipos_nombre_3?>"  style="width:72%">
					                                <input class="expertos-accion-extended accion-equipos " type="text" name="field_5743633f101d2" placeholder="Cantidad" value="<?=$post->expertos_accion_equipos_cantidad_3?>"   style="width:13%">
													<div class="expertos-accion-extended accion-equipos expertos-acciones-label">Equipo:</div>
													<input class="expertos-accion-extended accion-equipos " type="text" name="field_57436310101ce" placeholder="Otro equipo" value="<?=$post->expertos_accion_equipos_nombre_4?>"  style="width:72%">
					                                <input class="expertos-accion-extended accion-equipos " type="text" name="field_57436347101d3" placeholder="Cantidad" value="<?=$post->expertos_accion_equipos_cantidad_4?>"   style="width:13%">
													<div class="expertos-accion-extended accion-equipos expertos-acciones-label">Equipo:</div>
													<input class="expertos-accion-extended accion-equipos " type="text" name="field_57436318101cf" placeholder="Otro equipo" value="<?=$post->expertos_accion_equipos_nombre_5?>"  style="width:72%">
					                                <input class="expertos-accion-extended accion-equipos " type="text" name="field_57436351101d4" placeholder="Cantidad" value="<?=$post->expertos_accion_equipos_cantidad_5?>"   style="width:13%">



					                                <!-- sigue llendo la fecha o es custom de acuerdo al tipo? -->
					                                <!-- <input type="text" name="field_568d5ccec86a2" placeholder="Fecha de la acción (dd/mm/aaaa)" value="<?=$post->fecha?>" class="required" > -->
					                                <div class="expertos-acciones-label ">Observaciones generales (*)</div>
					                                <textarea name="field_568d5cd8c86a3" placeholder="Observaciones (*)" class="required" ><?=$post->observaciones?></textarea>
					                                <div class="expertos-acciones-label field-importe">Importe estimado (AR$):</div><input type="text" name="field_568d5cb8c86a1" placeholder="Importe estimado (*)" value="<?=$post->importe?>" class="required field-importe"  title="Importe aproximado (AR$) de la acción en base a los presupuestos. Sujeto a aprobación y/o alteración por parte de BGH">
									            </fieldset>


												<?php
												if(!isset($post->estado)||$post->estado=='pendent'||$post->estado=='actionobserved'){
												?>

													<fieldset>
														<div class="adjuntos">
										                <?php
										                if($post->file_diseno->scalar){
										                	?>
										                	<div class="file-row field-diseno">
										                		<span class="file-label">Diseño (*)</span>
																<a class="uploaded-file" href="<?=$post->file_diseno->scalar?>" target="_blank"><?=basename($post->file_diseno->scalar)?></a>
											                	<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_568e70a1f4247" class="required alreadyvalid">
							                                    </div>
							                                </div>
										                	<?php
										                }
										                else{
										                	?>
										                	<div class="file-row field-diseno">
										                		<span class="file-label">Diseño (*)</span>
																<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_568e70a1f4247" class="required">
							                                    </div>
							                                </div>
										                	<?php
										                }

										                if($post->file_presupuesto_1->scalar){
										                	?>
										                	<div class="file-row field-presupuesto">
										                		<span class="file-label">Presupuesto 1 (*)</span>
											                	<a class="uploaded-file" href="<?=$post->file_presupuesto_1->scalar?>" target="_blank"><?=basename($post->file_presupuesto_1->scalar)?></a>
											                	<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_568d5d5314cf2" class="required alreadyvalid">
							                                    </div>
							                                </div>
										                	<?php
										                }
										                else{
										                	?>
															<div class="file-row field-presupuesto">
																<span class="file-label">Presupuesto 1 (*)</span>
																<div class="files" >
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_568d5d5314cf2" class="required">
							                                    </div>
							                                </div>
										                	<?php
										                }

										                if($post->file_presupuesto_2->scalar){
										                	?>
										                	<div class="file-row field-presupuesto">
										                		<span class="file-label">Presupuesto 2</span>
											                	<a class="uploaded-file" href="<?=$post->file_presupuesto_2->scalar?>" target="_blank"><?=basename($post->file_presupuesto_2->scalar)?></a>
											                	<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_569f96add5a5e">
							                                    </div>
							                                </div>
										                	<?php
										                }
										                else{
										                	?>
										                	<div class="file-row field-presupuesto">
										                		<span class="file-label">Presupuesto 2</span>
																<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_569f96add5a5e">
							                                    </div>
							                                </div>
										                	<?php
										                }

										                if($post->file_presupuesto_3->scalar){
										                	?>
										                	<div class="file-row field-presupuesto">
										                		<span class="file-label">Presupuesto 3</span>
											                	<a class="uploaded-file" href="<?=$post->file_presupuesto_3->scalar?>" target="_blank"><?=basename($post->file_presupuesto_3->scalar)?></a>
											                	<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_569f96bcd5a5f">
							                                    </div>
							                                </div>
										                	<?php
										                }
										                else{
										                	?>
										                	<div class="file-row field-presupuesto">
										                		<span class="file-label">Presupuesto 3</span>
																<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_569f96bcd5a5f">
							                                    </div>
							                                </div>
										                	<?php
										                }
										                ?>

										                </div>
													</fieldset>


												<?php
												}
												else{
												?>

													<fieldset class="disabled">
									                	<div class="file-row field-diseno">
									                		<span class="file-label">Diseño</span>
									                		<?php
									                		if($post->file_diseno->scalar){
									                			?>
										                		<a class="uploaded-file" href="<?=$post->file_diseno->scalar?>" target="_blank"><?=basename($post->file_diseno->scalar)?></a>
										                		<?php
										                	}
										                	?>
						                                </div>
									                	<div class="file-row field-presupuesto">
									                		<span class="file-label">Presupuesto 1</span>
									                		<?php
									                		if($post->file_presupuesto_1->scalar){
									                			?>
										                		<a class="uploaded-file" href="<?=$post->file_presupuesto_1->scalar?>" target="_blank"><?=basename($post->file_presupuesto_1->scalar)?></a>
										                		<?php
										                	}
										                	?>
						                                </div>
									                	<div class="file-row field-presupuesto">
									                		<span class="file-label">Presupuesto 2</span>
									                		<?php
									                		if($post->file_presupuesto_2->scalar){
									                			?>
										                		<a class="uploaded-file" href="<?=$post->file_presupuesto_2->scalar?>" target="_blank"><?=basename($post->file_presupuesto_2->scalar)?></a>
										                		<?php
										                	}
										                	?>
						                                </div>
									                	<div class="file-row field-presupuesto">
									                		<span class="file-label">Presupuesto 3</span>
									                		<?php
									                		if($post->file_presupuesto_3->scalar){
									                			?>
										                		<a class="uploaded-file" href="<?=$post->file_presupuesto_3->scalar?>" target="_blank"><?=basename($post->file_presupuesto_3->scalar)?></a>
										                		<?php
										                	}
										                	?>
						                                </div>
													</fieldset>

									            <?php
												}

												// FOTO FACTURA

												if(isset($post->estado) && ($post->estado=='approved'||$post->estado=='rejected') ){
												?>

													<fieldset class="disabled">
														<div class="adjuntos">
														<?php
										                if($post->file_foto->scalar){
										                	?>
										                	<div class="file-row field-foto">
										                		<span class="file-label">Foto</span>
											                	<a class="uploaded-file" href="<?=$post->file_foto->scalar?>" target="_blank"><?=basename($post->file_foto->scalar)?></a>
							                                </div>
										                	<?php
										                }

										                if($post->file_factura->scalar){
										                	?>
										                	<div class="file-row field-factura">
										                		<span class="file-label">Factura</span>
											                	<a class="uploaded-file" href="<?=$post->file_factura->scalar?>" target="_blank"><?=basename($post->file_factura->scalar)?></a>
							                                </div>
										                	<?php
										                }


										                ?>
										                </div>
										            </fieldset>

									            <?php
												}
												else{
												// else(isset($post->estado)&& ($post->estado=='preapproved'||$post->estado=='executionobserved')){
												?>

													<fieldset>
														<div class="adjuntos">
														<?php
										                if($post->file_foto->scalar){
										                	?>
										                	<div class="file-row field-foto">
										                		<span class="file-label">Foto</span>
											                	<a class="uploaded-file" href="<?=$post->file_foto->scalar?>" target="_blank"><?=basename($post->file_foto->scalar)?></a>
											                	<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_568d7a1500191">
							                                    </div>
							                                    <span class="file-label"><small>De ser necesaario, puede subir un ZIP con varios archivos</small></span>
							                                </div>
										                	<?php
										                }
										                else{
										                	?>
										                	<div class="file-row field-foto">
										                		<span class="file-label">Foto</span>
																<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_568d7a1500191">
							                                    </div>
							                                    <span class="file-label"><small>De ser necesaario, puede subir un ZIP con varios archivos</small></span>
							                                </div>
										                	<?php
										                }

										                if($post->file_factura->scalar){
										                	?>
										                	<div class="file-row field-factura">
										                		<span class="file-label">Factura</span>
											                	<a class="uploaded-file" href="<?=$post->file_factura->scalar?>" target="_blank"><?=basename($post->file_factura->scalar)?></a>
											                	<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_568d7a2400192">
							                                    </div>
							                                    <span class="file-label"><small>De ser necesaario, puede subir un ZIP con varios archivos</small></span>
							                                </div>
										                	<?php
										                }
										                else{
										                	?>
										                	<div class="file-row field-factura">
										                		<span class="file-label">Factura</span>
																<div class="files">
							                                    	<span>Seleccionar Archivo</span>
							                                    	<input type="file" name="field_568d7a2400192">
							                                    </div>
							                                    <span class="file-label"><small>De ser necesaario, puede subir un ZIP con varios archivos</small></span>
							                                </div>
										                	<?php
										                }

										                ?>
										                </div>
										            </fieldset>


												<?php
												}


												if($id==0){
													?>
													<fieldset>
														<input type="hidden" name="action" value="insert" />
														<div class="clear check">
							                            	<input id="tyc" type="checkbox" name="tyc" checked value="1"><label>Acepto los <a href="#">términos y condiciones.</a></label>
							                            </div>
							                        </fieldset>
						                            <fieldset>
										                <input type="submit" value="SOLICITAR APROBACIÓN" />
										            </fieldset>
													<?php
												}
												else{

													?>
													<input type="hidden" name="action" value="update" />
													<fieldset>
														<?php
											            if(isset($post->estado)&& $post->estado!='rejected'){
															?>
											                <input type="submit" value="ACTUALIZAR ACCIóN" />
											                <?php
											            }
														?>
										                <!-- <input type="button" id="btn_historial" value="HISTORIAL" style="background-color:#fff;color:#444"/> -->
										            </fieldset>

										            <?php
										            if(isset($post->estado)&& $post->estado!='rejected'){
														?>
											            <input id="tyc" type="checkbox" name="tyc" checked value="1" style="display:none">
														<?php
													}

												}
												?>

					                        </form>
					                    </div>
					                </div>
					            </div>





							</div>
		                    <div class="clear"></div>
		                </div>
		            </section>

		        </div>
		    </div>
		<?php get_footer(); ?>
		</body>

	<?php

	}



?>

<script>


	var sending=false;


	$(document).ready(function(e) {

		$('.ico-disk-list.bt-acciones').addClass('active');
		$('li.bt-acciones').addClass('actv');


		var tipo = $('#accion_tipo').val();
		$('.expertos-accion-extended').hide();
		$('.tipo-custom').hide();
		// $('.tipo-custom').css({'visibility':'hidden'});
		$('.expertos-accion-extended.accion-' + tipo).show();
		if(tipo=='otro'){
			$('.tipo-custom').show(); //css({'visibility':'visible'});
		}
		$('.field-diseno').show();
		if(tipo=='capacitacion' || tipo=='exposicion' || tipo=='equipos'){
			$('.field-diseno').hide();
		}
		$('.field-presupuesto').show();
		if(tipo=='equipos'){
			$('.field-presupuesto').hide();
		}
		$('.field-foto').show();
		// if(tipo=='equipos'){
		// 	$('.field-foto').hide();
		// }
		$('.field-factura').show();
		// if(tipo=='equipos'){
		// 	$('.field-factura').hide();
		// }
		$('.field-importe').show();
		if(tipo=='equipos'){
			$('.field-importe').hide();
		}

		// $('#btn_historial').on('click', function(){
		// 	$('#acciones_lista_wrapper').slideToggle();
		// 	$("html, body").animate({ scrollTop: $('#btn_historial').offset().top - 120}, 500);
		// });


		setTimeout(function() {
			$('#acciones_lista_wrapper').slideToggle();
			$("html, body").animate({ scrollTop: $('#btn_historial').offset().top - 120}, 500);
		}, 1000);


		$('.files input').on('change', function(){
			$upfile = $(this).val();
			$file = $upfile.split("\\");
			$file = $file.pop();
			$ext = $file.split(".");
			$ext = $ext.pop();
			$(this).parent().find('span').html($file);
		});


		$('#accion_tipo').on('change',function(){
			var tipo = $(this).val();
			$('.expertos-accion-extended').hide();
			$('.expertos-accion-extended.accion-' + tipo).show();

			$('.tipo-custom').hide();//.css({'visibility':'hidden'});
			if(tipo=='otro'){
				$('.tipo-custom').show();
				// $('.tipo-custom').css({'visibility':'visible'});
			}
			$('.field-diseno').show();
			if(tipo=='capacitacion' || tipo=='exposicion' || tipo=='equipos'){
				$('.field-diseno').hide();
			}
			$('.field-presupuesto').show();
			if(tipo=='equipos'){
				$('.field-presupuesto').hide();
			}
			$('.field-foto').show();
			// if(tipo=='equipos'){
			// 	$('.field-foto').hide();
			// }
			$('.field-factura').show();
			// if(tipo=='equipos'){
			// 	$('.field-factura').hide();
			// }
			$('.field-importe').show();
			if(tipo=='equipos'){
				$('.field-importe').hide();
			}

		});



		$("#frm_accion").submit(function(e) {


			if(sending) return false;
			sending=true;


			$('#frm_wrapper .error').removeClass('error');
			$('#frm_wrapper .message-validation').remove();

			var form = $(this);
			var validForm = true;
			form.find('input:not([type="hidden"]), textarea, select').each(function(){

				// console.log($(this).val());

				if( ($(this).val() == null || $(this).val().trim() =='') && $(this).hasClass('required') && $(this).is(":visible") && !($(this).hasClass('alreadyvalid'))  ){
					$(this).focus();
					$(this).addClass('error');
					$(this).parent().addClass('error');
					console.log('error de validación:' + $(this).attr('name') + ' valor: "' + $(this).val() + '"' );
					validForm = false;
					sending=false;
					return false;
				}
			})


			if(validForm){

				if($('#tyc').is(':checked')){

					$('.top-message').remove();
					console.log('Enviando formulario..');
					$("html, body").animate({ scrollTop: $('#frm_wrapper').offset().top - 100}, 500);
					form.submit();
					$('#frm_wrapper').prepend('<div class="top-message top-message-notify">Enviando formulario..</div>');
				}
				else{
					console.log('Error en formulario 2');

					$('.top-message').remove();
					$('#frm_wrapper').prepend('<div class="top-message top-message-error">Debe aceptar los términos y condiciones.</div>');
					// $("html, body").animate({ scrollTop: 0 }, "slow");
					$("html, body").animate({ scrollTop: $('#frm_wrapper').offset().top - 100}, 500);
					validForm = false;
					sending=false;
					e.preventDefault();
				}

			}
			else{
				console.log('Error en formulario');
				// $("html, body").animate({ scrollTop: 0 }, "slow");
				$("html, body").animate({ scrollTop: $('#frm_wrapper').offset().top - 100}, 500);

				$('.top-message').remove();
				$('#frm_wrapper').prepend('<div class="top-message top-message-error">Por favor, complete todos los campos requeridos.</div>');
				e.preventDefault();
			}


		});





	});

</script>