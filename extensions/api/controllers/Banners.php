<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once BASEPATH."tastyigniter/libraries/REST_Controller.php";

class Banners extends REST_Controller {

	public function __construct()
	{
                //log_message('info','En el constructor ');
		parent::__construct();                
		//cargamos el modelo cuando se llama al constructor
		$this->load->model('Banners_model'); // load the banners model
	}
	
	//esta función sirve para obtener info (pedir datos al servidor) 
	public function index_get()
	{
                
		$banners = $this->Banners_model->findBanners();
		if (! is_null($banners)) 
		{                    
			$this->response(array("total" => count($banners),"result"=>$banners), 200);
		}
		else
		{
			$this->response(array("error"=>"No hay anuncios"), 404);
		}
	}


	//para buscar por un criterio determinado
	public function find_get($id)
	{
		if (! $id) 
		{
			$this->response(NULL, 400);
		}

		$banner = $this->Banners_model->getBanner($id);

		if (! is_null($banner)) 
		{                    
			$this->response(array("result" => $banner), 200);
		}
		else
		{
			$this->response(array("error" => "No se encuentra el anuncio"), 404);
		}
	}       
        

}
/* End of file pois.php */
/* Location: ./application/controllers/pois.php */