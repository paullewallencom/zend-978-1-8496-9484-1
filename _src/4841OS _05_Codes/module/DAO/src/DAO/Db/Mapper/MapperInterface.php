<?php

namespace DAO\Db\Mapper;

interface MapperInterface
{
	/**
	 * Inserts data.
	 */
	public function insert($data);
	
	/**
	 * Updates data.
	 */
	public function update($data);
	
	/**
	 * Deletes data.
	 */
	public function delete($id);
	
	/**
	 * Loads a specific record.
	 */
	public function load($id);
	
	/**
	 * Gets all the records in the table.
	 */
	public function getAll();
}