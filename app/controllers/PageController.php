<?php

class PageController extends BaseController {
	
	public function index() {
		Response::getInstance()->setTemplate("index");
	}
	
	public function about() {
		Response::getInstance()
			->setTemplate("about");
	}
	
	public function terms() {
		Response::getInstance()
			->setTemplate("index");
	}
	
	public function contact() {
		Response::getInstance()
			->setTemplate("index");		
	}
}
