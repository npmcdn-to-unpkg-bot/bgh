<?php include 'expertos-session.php'; ?>
<?php

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


    //provincia
    $field = get_field_object("field_54e73c5721eb7");
    if($field){
        foreach( $field['choices'] as $k => $v ){
            $provincia[$k] = $v;
        }
    }

    $message = "";
    if(isset($_REQUEST['message'])){
        $message = $_REQUEST['message'];
    }

    switch ($action) {

        case 'insert':

            $title = $_REQUEST['title']; // nombre de la accion
            $content = "-";

            $args = array(
                'post_type' => 'airexperts',
                'post_status' => 'publish', //publish
                'post_title'    => htmlentities($title),
                'post_content'  => htmlentities($content),
                'post_category' => array(),
                // 'post_author'   => 999,
                'tags_input' => htmlentities('imported,accion')
            );
            $id = wp_insert_post($args);

            if($id){

                $message = "Sus registro fue insertado correctamente.";

                foreach ($_POST as $field => $value){
                    if (strpos($field,'field_') !== false) {
                        update_field($field, $value, $id);
                    }
                }

                // foreach ($_FILES as $field => $array) {
                //     if(!empty($_FILES[$field]['name'])){
                //         $file_id = insert_attachment($field,$id);
                //         // $fileurl = wp_get_attachment_url($file_id);
                //         update_field($field, $file_id, $id);
                //     }
                // }


            }


            break;

        case 'update':

            $message = "Registro actualizado.";

            foreach ($_POST as $field => $value){
                if (strpos($field,'field_') !== false) {
                    update_field($field, $value, $id);
                }
            }

            // foreach ($_FILES as $field => $array) {
            //     if(!empty($_FILES[$field]['name'])){
            //         $file_id = insert_attachment($field,$id);
            //         // $fileurl = wp_get_attachment_url($file_id);
            //         update_field($field, $file_id, $id);
            //     }
            // }


            break;

        case 'delete':

            $message = "Registro eliminado.";

            wp_delete_post($id);

            break;


        default:

            break;
    }







    $items = [];
    $query = new WP_Query(array('post_type' => 'airexperts', 'posts_per_page' => -1));
    foreach ($query->posts as $post) {

        $post->title =  $post->post_title;
        // $post->title = get_field('title', $post->ID);
        $post->locality = get_field('locality', $post->ID);
        $post->province = get_field('province', $post->ID);
        $post->telephone = get_field('telephone', $post->ID);
        $post->email = get_field('email', $post->ID);
        $post->address = get_field('address', $post->ID);
        $post->user = get_field('user', $post->ID);
        $post->zip = get_field('zip', $post->ID);

        if(strtolower($post->user)==strtolower($user->login)){
            array_push($items,$post);
        }


    }

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

                        <h2>Mi Perfil<br>
                        <strong>Editar puntos de venta</strong>
                            <a href="#" class="boton-right">Nuevo Punto de Venta </a>
                        </h2>

                        <p class="perfil-disclamer">Esta información sólo se verá reflejada en el listado “Red de Distribución” de la sección Climatización para Profesionales de www.bgh.com.ar. Cualquier cambio aquí, no impacta en ningún otro sistema de la compañía.</p>

                        <?php
                        if(isset($message) && $message!=''){
                            ?>
                            <div id="perfil_message"><?=$message?></div>
                            <?php
                        }
                        ?>

                        <?php
                        foreach ($items as $item) {
                            ?>
                            <form class="form-perfil" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="field_56e1e1f63799c" value="<?=$user->login?>" />
                                <input type="hidden" name="id" value="<?=$item->ID?>" />
                                <input type="hidden" name="action" value="update" />
                                <div class="">
                                    <div class="field"><span>Nombre:</span><input type="text" class="required" name="title" placeholder="Nombre" value="<?=$item->title?>"></div>
                                    <div class="field"><span>Email:</span><input type="text"  name="field_54e73c6f21eb8" placeholder="Email" value="<?=$item->email?>" class="required"></div>
                                    <div class="field"><span>Teléfono:</span><input type="text"  name="field_54e73c481f471" placeholder="Teléfono" value="<?=$item->telephone?>"></div>
                                    <div class="field"><span>Dirección:</span><input type="text" name="field_54e9da8942218" placeholder="Dirección" value="<?=$item->address?>"></div>
                                    <div class="field"><span>Localidad:</span><input type="text"  name="field_54e73c481f469" placeholder="Localidad" value="<?=$item->locality?>"></div>
                                    <div class="field"><span>Provincia:</span>
                                    <!-- <input type="text" class="required" name="field_54e73c5721eb7" placeholder="Provincia" value="<?=$item->province?>"> -->

                                        <select name="field_54e73c5721eb7" onchange="this.style.color='#565656'" class="required" >
                                            <option value="" selected>Provincia</option>
                                            <?php
                                            $field = get_field_object("field_54e73c5721eb7");
                                            if($field){
                                                foreach( $field['choices'] as $k => $v ){
                                                    $selected='';
                                                    if($item->province==$k) $selected='selected';
                                                    echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
                                                }
                                            }
                                            ?>
                                        </select>

                                    </div>
                                    <div class="field"><span>Código Postal:</span><input type="text" name="field_54e9da326412c" placeholder="Código Postal" value="<?=$item->zip?>"></div>
                                    <div class="field">
                                        <input type="button" class="btn-edit" value="editar"/>
                                        <input type="button" class="btn-update" value="guardar"/>
                                        <input type="button" class="btn-cancel" value="cancelar"/>
                                        <input type="button" class="btn-delete" value="Eliminar punto de venta"/>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </form>
                            <?php
                        }
                        ?>

                        <form id="form_new" class="form-perfil" method="POST" enctype="multipart/form-data">
                            <h2 style="padding-top: 0px; padding-bottom: 0px; margin-top: 0px; margin-bottom: 10px">Agregar Punto de Venta</h2>
                            <input type="hidden" name="field_56e1e1f63799c" value="<?=$user->login?>" />
                            <input type="hidden" name="action" value="insert" />
                            <div class="">
                                <div class="field"><span>Nombre:</span><input type="text" class="required" name="title" placeholder="Nombre" ></div>
                                <div class="field"><span>Email:</span><input type="text"  name="field_54e73c6f21eb8" placeholder="Email" class="required"></div>
                                <div class="field"><span>Teléfono:</span><input type="text"  name="field_54e73c481f471" placeholder="Teléfono" ></div>
                                <div class="field"><span>Dirección:</span><input type="text" name="field_54e9da8942218" placeholder="Dirección" ></div>
                                <div class="field"><span>Localidad:</span><input type="text" name="field_54e73c481f469" placeholder="Localidad" ></div>
                                <div class="field"><span>Provincia:</span>
                                    <select name="field_54e73c5721eb7" onchange="this.style.color='#565656'" class="required" >
                                        <option value="" selected>Provincia</option>
                                        <?php
                                        $field = get_field_object("field_54e73c5721eb7");
                                        if($field){
                                            foreach( $field['choices'] as $k => $v ){
                                                $selected='';
                                                //if($item->province==$k) $selected='selected';
                                                echo '<option value="' . $k . '" ' . $selected . '>' . $v . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="field"><span>Código Postal:</span><input type="text" name="field_54e9da326412c" placeholder="Código Postal" ></div>
                                <div class="field">
                                    <input type="button" class="btn-update" value="Aceptar"/>
                                    <input type="button" class="btn-new-cancel" value="Cancelar"/>
                                    </div>
                                <div class="clear"></div>
                            </div>
                        </form>

                        <!-- <p style="float:left; font-size: 1.5em;line-height: 1.5em;"><strong>Empresa:</strong> <?=$user->empresa?><strong>Usuario:</strong> <?=$user->login?></p> -->

                    </div>
                    <div class="clear"></div>
                </div>
            </section>

        </div>
    </div>
<?php get_footer(); ?>
</body>


<script>

var sending=false;


$(document).ready(function(e) {

    $('mi-perfil').addClass('active');


    // $(".form-perfil .field").addClass("disabled");
    // $(".form-perfil .field input").prop("disabled", true);
    // $(".form-perfil .field select").prop("disabled", true);
    // // $(".form-perfil .field button").prop("disabled", true);

    // $(".form-perfil .btn-cancel, .form-perfil .btn-update, .form-perfil .btn-delete").hide();





    $('.btn-edit').on('click',function(){

        var form = $(this).closest('form');

        form.find(".field").removeClass("disabled");
        form.find(".field input[type='text']").prop("disabled", false);
        form.find(".field select").prop("disabled", false);

        form.find(".btn-edit").hide();
        form.find(".btn-cancel, .btn-update, .btn-delete").show();

    });

    $('.btn-cancel').on('click',function(){

        var form = $(this).closest('form');

        form.find(".field").addClass("disabled");
        form.find(".field input[type='text']").prop("disabled", true);
        form.find(".field select").prop("disabled", true);

        form.find(".btn-cancel, .btn-update, .btn-delete").hide();
        form.find(".btn-edit").show();


    });

    $('.btn-new').on('click',function(){
        $('.form-perfil').hide();
        $('#form_new').show();
    });

    $('.btn-new-cancel').on('click',function(){
        $('.form-perfil').show();
        $('#form_new').hide();
    });



    $('#form_new').hide();
    $('.btn-cancel').trigger('click');





    $('.btn-update').on('click',function(){
        $(this).closest('form').submit();
    });


    $('.btn-delete').on('click',function(){
        $(this).closest('form').find('input[name="action"]').attr('value','delete');
        $(this).closest('form').submit();
    });

    $(".form-perfil").submit(function(e) {

        var form = $(this);

        if(sending) return false;
        sending=true;


        form.find('.error').removeClass('error');
        form.find('.message-validation').remove();

        var validForm = true;
        form.find('input:not([type="hidden"]), textarea, select').each(function(){

            if( ($(this).val() == null || $(this).val().trim() =='') && $(this).hasClass('required')){
                $(this).focus();
                $(this).addClass('error');
                $(this).parent().addClass('error');
                validForm = false;
                sending=false;
                return false;
            }
        })

        if(validForm){
            console.log('Enviando formulario..');
            form.submit();
        }
        else{
            console.log('Error en formulario');
            $("html, body").animate({ scrollTop: form.offset().top - 120 }, "slow");
            form.prepend('<div class="message-validation">Por favor, complete todos los campos requeridos.</div><div></div>'); // agrego div final para hacer par por odd css
            e.preventDefault();
        }


    });




});
</script>