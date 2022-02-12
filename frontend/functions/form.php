<?php

/*
 * Генератор форм.
 */

function drevo_form($arg) {

//print_r($arg);
	// Если форма отправлена
	if (isset($_POST['rid'])) {
		//Если эти поля заполнены значит спам рбота. Ибо эти поля не видимые
		if ($_POST['email'] != '') {
			echo 'spam...';
			exit;
		}
		$blogname = get_option('blogname');
		$admin_email = get_option('admin_email');
		//echo $admin_email;//adita-elabuga@mail.ru
		//mail($admin_email,'1','2');
		//exit;
		$sitename = $_SERVER['HTTP_HOST'];
		$message = '<h2>' . $sitename . ' - Новое сообщение [' . $_POST['rid'] . ']</h2>
			 <p>время: ' . date('d-n-Y H:i:s') . '</p>
			  <table>';
		foreach ($_POST['f'] as $fid => $value) {
			$message .= "
			" . ( ($c = !$c) ? '<tr>' : '<tr style="background-color: #f8f8f8;">' ) . "
			<td style='padding: 10px; border: #e9e9e9 1px solid;'><b>" . $arg['fid'][$fid]['title'] . "</b></td>
			<td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
		</tr>
		";
		}
		$message.='</table>';
		/*
		 * Отправляем уведомление на почту администратора сайта
		 */
		$headers .= "From: <webmaster@коллегия-юристов.рф>\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html;charset=utf-8 \r\n";
		//$admin_email = 'deen812@mail.ru';
		mail(
			$admin_email, "Новое сообщение [" . $_POST['rid'] . "]", $message, $headers
		);

		ob_clean();
		/*
		 * Возвращаем ответ.
		 */
		if ($_POST['ajax']) {
			$_POST['f'][14] = date('d-n-Y H:i:s');
			if (Drevo_insertData($_POST['rid'], $_POST['f'], 1) > 0) {
				echo '->ok';
			} else {
				echo '->error';
			}
			die();
		}

		//ob_start();
		//print_r($_POST);
		//echo ob_get_contents(); 
		//ob_end_clean(); 
	}

	wp_register_style('drevo_form_css', plugins_url('../css/drevo_form.css', __FILE__));
	wp_enqueue_style('drevo_form_css');
	wp_register_script('drevo_form_js', plugins_url('../js/drevo_form.js', __FILE__));
	wp_enqueue_script('drevo_form_js');

	//0 верся - это простая форма. С базовым шаблоном.
	if ($arg['return_as_arr'] == 1) {
		$arr = array();
		$arg['echo'] = false;
		drevo_default_form($arg, $arr);
		return $arr;
	}
	if ($arg['type'] == 0) {
		if ($arg['echo'] == false) {
			ob_start();
			drevo_default_form($arg);
			$ReturnString = ob_get_contents();
			ob_end_clean();
			return $ReturnString;
		} else {
			drevo_default_form($arg);
		}
	}
}

/*
 * Простая форма.
 */

function drevo_default_form($arg, &$arrForm = array()) {
	if ($arg['show_agree'] == null) {
		$arg['show_agree'] = true;
	}
	//Если вызыва как всплывающее окно
	if ($arg['popup'] == true) {
		echo '<script>
		 $(document).ready(
		    function(){
		        $(".' . $arg['js_call_class'] . '").click(function(){
				console.log($(this).data("event_title"));
				drevo_form_show(' . $arg['form_id'] . ' ,$(this).data("event_title") )})
			}
		)
		</script>';
		echo '<div  style="display:none" id="df_layer_' . $arg['form_id'] . '" class="drevo-form-layer" onclick="drev_form_hide(' . $arg['form_id'] . ')"></div>';
		echo '<div style="display:none" id="df_container_' . $arg['form_id'] . '" class="drevo-form-container">
	                 <a data-id="' . $arg['form_id'] . '" class="drevo-form-close" onclick="drev_form_hide(' . $arg['form_id'] . ')">закрыть окно</a>';
	}

	$val = '';
	$val.= '
	      <div class="drevo-form-success drevo-form-success' . $arg['form_id'] . '"><span>' . ($arg['success'] == '' ? 'Ваше сообщение отправлено!' : $arg['success']) . '</span></div>
	      <div class="drevo-form-error drevo-form-error' . $arg['form_id'] . '"><span>' . ($arg['error'] == '' ? 'Ой, Ошибка... Позвоните по указанным номерам на сайте.' : $arg['error']) . '</span></div>
	      <div class="drevo-form-form drevo-form-form' . $arg['form_id'] . '">
	      ' . ($arg['title'] != '' ? '<div class="drevo-form-title">' . $arg['title'] . '</div>' : '') . '
       ';
	$val.= '<form action=""  method="post" class="drevo-form ' . $arg['form_class'] . '" data-id="' . $arg['form_id'] . '" id="drevo-form-' . $arg['form_id'] . '">
	      <input type="hidden" name="rid" value="' . $arg['rid'] . '">
	      
             <input type="hidden" name="f[' . $arg['event_title'] . ']" class="event_title" value="">
	      <input type="email" name="email" value="" style="display:none">
	      
	      <input type="hidden" name="ajax" value="' . $arg['ajax'] . '">';
	if ($arg['return_as_arr'] == 1) {
		$arrForm['form_header'] = $val;
	} else
		echo $val;
	$val = '';

	foreach ($arg['fid'] as $fid => $field) {
		$val = '';
		$label = '';
		if ($field['show_lable'] == true) {
			if ($field['type'] != 'hidden') {
				$label = '<label>' . $field['title'] . '</lable>';
			}
		}
		if ($field['type'] == "textarea") {
			$fieldT = ' <textarea ' . (isset($field['required']) ? 'required' : '') . '  id="" class="' . $field['class'] . '" name="f[' . $fid . ']" placeholder="' . $field['placeholder'] . '"></textarea>';
		} elseif ($field['type'] == "select") {
			$fieldT = ' 
				<select 
				    ' . (isset($field['required']) ? 'required' : '') . '  
			            id=""
				    class="' . $field['class'] . '" 
				    name="f[' . $fid . ']" 
				 >';
			if (is_array($field['options'])) {
				foreach ($field['options'] as $opt) {
					$fieldT.= '<option value="' . $opt['value'] . '" ' . $opt['attr'] . '>' . $opt['title'] . '</option>';
				}
			}
			$fieldT.= '  </select>';
		} else {
			$fieldT = ' <input 
			value="' . $field['value'] . '" 
			' . (isset($field['required']) ? 'required' : '') . ' 
			type="' . $field['type'] . '" 
			id="input' . $fid . '" 
			class="' . $field['class'] . '" 
			name="f[' . $fid . ']" 
			placeholder="' . $field['placeholder'] . '">';
		}

		if ($arg['return_as_arr'] == 1) {
			$arrForm['form_fields'][$fid] = array($label, $fieldT);
		} else {
			echo $label;
			echo $fieldT;
		}
	}
	//event_title
	$agree = '
	<div id="agree">
	<input type="checkbox" name="agree" id="agreecb' . $arg['form_id'] . '" required checked="">
	<a href="' . $arg['form_agree_href'] . '" target="_blank">Согласен на обработку персональных данных</a>
	</div>';
	$form_submit = '<input type="submit" value="' . ($arg['submit_text'] != '' ? $arg['submit_text'] : 'Отправить') . '" ' . ($arg['submit_attributes'] != '' ? $arg['submit_attributes'] : '') . '  class="btn btn-primary ' . ($arg['submit_class'] != '' ? $arg['submit_class'] : '') . '">';
	$form_footer = '
		 
         </form>
	      
	      </div>';
	if ($arg['return_as_arr'] == 1) {
		$arrForm['form_agree'] = $agree;
		$arrForm['form_submit'] = $form_submit;
		$arrForm['form_footer'] = $form_footer;
	} else {
		//show_agree
		if ($arg['show_agree'] != false)
			echo $agree;
		echo $form_submit;
		echo $form_footer;
	}
	$val = '';
	if ($arg['popup'] == true) {
		echo '</div>';
	}
}
