<?

require SYSTEM.'/base-controller.php';
require SYSTEM.'/base-model.php';
require SYSTEM.'/base-class.php';

/***********************/
/*  GENERIC AUTOLODER  */
/***********************/

function generic_class_loader($name,$prefix, $folder) {
	try{
		$file_name = str_replace ( '_', '-' , strtolower($name));
	    $path = $folder . '/' . $file_name . '.php';
		if(is_file($path)) {

			// include class file
			include_once $path;

			// check if the class exists in file
			if(!class_exists($name, false)) throw new Exception("Class $name is not defined in $path", 1);			

			// check if the class extends Base
			if(!in_array('Base_'.$prefix.'_Interface',class_implements($name))) throw new Exception("$name must implement Base_$prefix", 1);

		} 
		else {
			throw new Exception("$path not found", 1);	
		}
	}
	catch (Exception $e){
		error_log($e->getMessage());
		return false;
	}
	return true;
}

// Simple_Example_Controller
function generic_autoloader($name) {
	if(preg_match('/_Controller$/',$name)) {
		return generic_class_loader($name,'Controller',CONTROLLERS);	
	}
	else if(preg_match('/_Model$/',$name)){
		return generic_class_loader($name,'Model',MODELS);
	}
	else if(preg_match('/_Class$/',$name)){
		return generic_class_loader($name,'Class',CLASSES);
	}
}

spl_autoload_register('generic_autoloader',true);
