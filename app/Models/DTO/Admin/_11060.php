<?php

namespace App\Models\DTO\Admin;

use App\Models\DAO\Admin\_11060DAO;

/**
 * _11060 - Admin
 */
class _11060
{
	/**
	 * • 
	 */
	public static function getChecList($dados) {
		return _11060DAO::getChecList($dados);
	}
    
    /**
	 * listar 
	 */
	public static function listar($dados) {
		return _11060DAO::listar($dados);
	}
    
    /**
	 * store 
	 */
	public static function store($dados) {
		return _11060DAO::store($dados);
	}
    
    /**
	 * show 
	 */
	public static function show($id) {
		return _11060DAO::show($id);
	}
    
    /**
	 * update 
	 */
	public static function update($dados,$id) {
		return _11060DAO::update($dados,$id);
	}
    
    /**
	 * destroy 
	 */
	public static function destroy($dados) {
		return _11060DAO::destroy($dados);
	}

}