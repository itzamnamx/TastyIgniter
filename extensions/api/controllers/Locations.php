<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH."tastyigniter/libraries/REST_Controller.php";

class Locations extends REST_Controller {

	public function __construct()
	{
                //log_message('info','En el constructor ');
		parent::__construct();                
		//cargamos el modelo cuando se llama al constructor
		$this->load->model('Locations_model'); // load the menus model
	}
	
	//esta función sirve para obtener info (pedir datos al servidor) 
	public function index_get()
	{
                
		$locations = $this->Locations_model->getLocations();
		if (! is_null($locations)) 
		{                    
			$this->response(array("response"=>$locations), 200);
		}
		else
		{
			$this->response(array("error"=>"No hay Ubicaciones"), 404);
		}
	}


	//para buscar por un criterio determinado
	public function find_get($id)
	{
		if (! $id) 
		{
			$this->response(NULL, 400);
		}

		$location = $this->Locations_model->getLocation($id);

		if (! is_null($location)) 
		{                    
			$this->response(array("response" => $location), 200);
		}
		else
		{
			$this->response(array("error" => "No se encuentra la ubicacion"), 404);
		}
	}

}
/* End of file pois.php */
/* Location: ./application/controllers/pois.php */