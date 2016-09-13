<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
log_message('info','EN EL SERVER REST en el GET CATEGORIES ANTES DE CARGAR LA LIBRERIA');
require_once BASEPATH."tastyigniter/libraries/REST_Controller.php";
log_message('info','EN EL SERVER REST en el GET CATEGORIES DEPUES DE CARGAR LA LIBRERIA');
//require_once BASEPATH.'libraries/REST_Controller.php';

class Cat extends REST_Controller {

	public function __construct()
	{
                log_message('info','En el constructor ');
		parent::__construct();                
		//cargamos el modelo cuando se llama al constructor
                log_message('info','cargamos el modelo cuando se llama al constructor ');
		$this->load->model('Categories_model'); // load the menus model
	}
	
	//esta función sirve para obtener info (pedir datos al servidor) 
	public function index_get()
	{
                
                log_message('info','EN EL SERVER REST en el GET CATEGORIES ');
                log_message('info','QUERY A LA BD ');
		$categories = $this->Categories_model->getCategories();
                log_message('info','SE VALIDA VALOR ');
		if (! is_null($categories)) 
		{
                    log_message('info','SE MUESTRAN CATEGORIAS ');
			$this->response(array("response"=>$categories), 200);
		}
		else
		{
                    log_message('info','NO HAY CATEGORIAS ');
			$this->response(array("error"=>"No hay Categorias"), 404);
		}
	}


	//para buscar por un criterio determinado
	public function find_get($id)
	{
                
                log_message('info','EN EL SERVER REST en el GET CATEGORIES by ID');
		if (! $id) 
		{
			$this->response(NULL, 400);
		}

		$category = $this->Categories_model->getCategory($id);

		if (! is_null($category)) 
		{
			$this->response(array("response" => $category), 200);
		}
		else
		{
			$this->response(array("error" => "No se encuentra la categoria"), 404);
		}
	}

}
/* End of file pois.php */
/* Location: ./application/controllers/pois.php */