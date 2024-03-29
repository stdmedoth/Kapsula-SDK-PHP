<?php

namespace Kapsula;

use Kapsula\Request;

Class Element {

	public function __construct($route){
		$this->route = $route;
		$this->request = new Request($route);
	}

	protected $id;

	public $request;
	public $route;
	public $objects;
	public $data_obj;

	public $current_page = 1;
	public $last_page;

	public function post(){
		$response = $this->request->post($this->to_json());
		return json_decode($response);
	}

	public function get( $id = null){

		$obj = substr($this->route, 0, -1);
		if($id){
			$this->objects = $this->request->get( $id )->{"$this->data_obj"};
			foreach ($this->objects as $key => $value) {
				$this->{$key} = $value;
			}
		}else{
			$this->objects = $this->request->get(FALSE, $this->current_page);
			if($this->objects && $this->objects->last_page){
				$this->last_page = $this->objects->last_page;
				$this->current_page++;
			}

		}

		return $this->objects;
	}

	public function put( $data ){

		if( !$data ){
			return null;
		}
		//$payload = json_encode($data);
		return json_decode($this->request->put($this->id, $data));

	}


   	public function to_json(){
		$objectVars = get_object_vars($this);

		// Filter out null values
		$filteredVars = array_filter($objectVars, function ($value) {
			if ((gettype($value)=="string") && (strlen($value)<=0)){
				return FALSE;
			}

			return ($value !== NULL);
		});
        return json_encode($filteredVars);
    }



}
