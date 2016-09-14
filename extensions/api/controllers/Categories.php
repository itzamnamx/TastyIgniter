<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH."tastyigniter/libraries/REST_Controller.php";

class Categories extends REST_Controller {

	public function __construct()
	{
                log_message('info','En el constructor ');
		parent::__construct();                
		//cargamos el modelo cuando se llama al constructor
		$this->load->model('Categories_model'); // load the menus model
	}
	
	//esta función sirve para obtener info (pedir datos al servidor) 
	public function index_get()
	{
                
		$categories = $this->Categories_model->getCategories();
		if (! is_null($categories)) 
		{                    
			$this->response(array("response"=>$categories), 200);
		}
		else
		{
			$this->response(array("error"=>"No hay Categorias"), 404);
		}
	}


	//para buscar por un criterio determinado
	public function find_get($id)
	{
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