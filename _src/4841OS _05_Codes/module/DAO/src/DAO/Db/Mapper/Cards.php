<?php

namespace DAO\Db\Mapper;

use Zend\Db\Sql\Where,
	DAO\Db\DTO\Cards as CardsDto;

class Cards extends MapperAbstract implements MapperInterface
{
	/**
	 * Delete a specific row.
	 * 
	 * @param int $id
	 */
	public function delete($id) 
	{
		$sql = $this->connection();
		
		// Create a new WHERE clause
		$where = new Where();
		$where->equalTo('id', $id);

		try {
			// Create a statement
			$statement = $sql->prepareStatementForSqlObject(
					$sql->delete()
					    ->where($where)
			);
			
			// Execute the statement
			$result = $statement->execute();
			
			// If there is more than 0 rows deleted return true, otherwise false
			return $result->getAffectedRows() > 0 ? true : false;
		} catch (\Exception $e) {
			// Something went terribly wrong, just ignore it for now ;-)
			// TIP: Don't do this in real life, ignoring exceptions is not cool!
			return false;
		}
	}

	/**
	 * Returns all the records in the database.
	 * 
	 * @return \DAO\Db\DTO\Cards
	 */
	public function getAll()
	{
		// Get the SQL object
		$sql = $this->connection();
		
		// Prepare a select statement and execute it
		$statement = $sql->prepareStatementForSqlObject($sql->select());
		$records = $statement->execute();
		$retval = array();
		
		// Loop through the records and add them to the result array
		foreach ($records as $row) {
			$retval[] = new CardsDto($row['type'], $row['value'], $row['color'], $row['id']);
		}
		
		unset($sql, $statement, $records);
		
		return $retval;
	}

	/**
	 * Inserts a record.
	 * 
	 * @param \DAO\Db\DTO\Cards $data
	 */
	public function insert($data)
	{
		// We can easily insert this as we know the DTO has already taken care of
		// the validation of the values.
		if (get_class($data) !== 'DAO\Db\DTO\Cards') {
			throw new \Exception("Data needs to be of type DAO\Db\DTO\Cards");
		}
		
		$sql = $this->connection();
		
		try {
			$statement = $sql->prepareStatementForSqlObject(
				$sql->insert()
					->values(array(
						'color' => $data->getColor(),
						'type' => $data->getType(),
						'value' => $data->getValue()
				))
			);
			
			$result = $statement->execute();
			
			return $result->getGeneratedValue();
		} catch (\Exception $e) { 
			return false;
		}
	}

	public function load($id) 
	{
		// Get the SQL object
		$sql = $this->connection();
		
		// A fresh WHERE clause
		$where = new Where();
		$where->equalTo('id', $id);
		
		try {
			// Prepare a select statement
			$statement = $sql->prepareStatementForSqlObject(
					$sql->select()
						->where($where)
			);
		
			// Execute the statement
			$record = $statement->execute()
					            ->current();
			
			return new CardsDto($record['type'], $record['value'], $record['color'], $record['id']);
		} catch (\Exception $e) {
			return false;
		}
	}

	public function update($data) 
	{
		// We can easily insert this as we know the DTO has already taken care of
		// the validation of the values.
		if (get_class($data) !== 'DAO\Db\DTO\Cards') {
			throw new \Exception("Data needs to be of type DAO\Db\DTO\Cards");
		}
		
		if ($data->getId() === null) {
			throw new \Exception("Can't update anything if we don't have a card id!");
		}
		
		$sql = $this->connection();
		
		try {
			// Create the WHERE clause
			$where = new Where();
			$where->equalTo('id', $data->getId());

			// Create the update class
			$update = $sql->update();
			$update->where($where);
			$update->set(array(
					'color' => $data->getColor(),
					'type' => $data->getType(),
					'value' => $data->getValue()
			));
			
			// Create the statement
			$statement = $sql->prepareStatementForSqlObject($update);
			
			// Execute the statement
			$result = $statement->execute();
			
			// If more than 0 rows were updated return true, otherwise false
			return $result->getAffectedRows() > 0 ? true : false;
		} catch (\Exception $e) { 
			return false;
		}
	}
}