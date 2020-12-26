<?php

/**
 * The SQL's performed in this file are relating to a table which I created in my
 * database. Obviously it would be handy for you if you also can use that, so that
 * is why you can find the script in /data/sample.sql.
 * 
 * Have fun!
 */


namespace Application\Model;

use Zend\Db\Adapter\Adapter,
	Zend\Debug\Debug,
	Zend\Db\Adapter\ParameterContainer,
	Zend\Db\TableGateway\TableGateway,
	Zend\Db\Sql\Insert,
	Zend\Db\Sql\Update,
	Zend\Db\Sql\Where,
	Zend\Db\Sql\Delete,
	Zend\Db\Adapter\Profiler\Profiler,
	Zend\Db\Sql\Sql;

class Sample 
{
	/**
	 * Our database connection.
	 * 
	 * @var \Zend\Db\Adapter\Adapter
	 */
	protected $connection;
	
	private function connectToDatabase()
	{
		if ($this->connect === null) {
			$this->connection = new Adapter(array(
				'driver' => 'PDO_Mysql',
				'database' => 'book',
				'hostname' => 'localhost',
				'username' => 'root',
				'password' => 'd3v3l0p3r',
			));
		}
	}
	
	/**
	 * Does some simple queries.
	 */
	public function simpleQueries()
	{
		Debug::dump('******************* EXECUTING Application\Model\Sample::simpleQueries() *******************');
		
		$this->connectToDatabase();
		
		try {
			// This is the value of the where
			$type = array('number');

			// Build up our query
			$result = $this->connection
						   ->query(
					"SELECT * FROM cards WHERE type = ?", 
					Adapter::QUERY_MODE_PREPARE
			)->execute($type);

			foreach ($result as $res) {
				Debug::dump($res, 'Query 1');
				// Do something with the 36 rows of result
			}

			unset($result, $type);
		} catch (\Exception $e) {
			Debug::dump($e->getMessage());
		}
		
		try{
			// Now lets create a prepared statement
			$statement = $this->connection
							  ->createStatement();

			$statement->setSql("SELECT * FROM cards WHERE type = :type AND color = :color");

			// Create a new parameter container to store our where parameters in
			$container = new ParameterContainer(array('type' => 'picture', 'color' => 'diamond'));
			
			// Set the container to be used in our statement
			$statement->setParameterContainer($container);

			// Prepare the statement for use with the database
			$statement->prepare();
			
			// Now execute the statement and get the resultset
			$result = $statement->execute();

			foreach ($result as $res) {
				Debug::dump($res, 'Query 2');
				// Do something with the 3 rows of result
			}
		} catch (\Exception $e) {
			Debug::dump($e->getMessage());
		}
		
	}
	
	public function advancedQueries()
	{
		Debug::dump('******************* EXECUTING Application\Model\Sample::advancedQueries() *******************');
		
		$this->connectToDatabase();
		
		// Let's make this object for examples later on
		
		// Create a new insert statement
		$insert = new Insert('cards');
		
		// Define the columns in the table, although not required, it is best
		// practice
		$insert->columns(array(
			'id',
			'color',
			'type',
			'value',
		));
		
		// Assign the values we want to insert
		$insert->values(array(
			'color' => 'diamond',
			'type' => 'picture',
			'value' => 'Goblin'
		));
		
		// Create a new table gateway to perform our SQL on
		$tableGateway = new TableGateway('cards', $this->connection);
		
		try {
			Debug::dump($insert->getSqlString(), "Executing statement:");
			$tableGateway->insertWith($insert);
			$result = true;
		} catch (Exception $e) {
			$result = false;
		}
		
		if ($result === true) {
			$primaryKey = $tableGateway->getLastInsertValue();
			Debug::dump($primaryKey, "Inserted with ID:");
			
			// Now let's update our record
			$update = new Update('cards');
			$update->set(array(
				'color' => 'spade',
				'value' => '10',
				'type' => 'number',
			));
			
			// Now create a where statement
			$where = new Where();
			$where->equalTo("id", $primaryKey);
			
			// Set the where in the update statement
			$update->where($where);
			
			// Update the record
			Debug::dump($update->getSqlString(), "Executing statement:");
			$updated = $tableGateway->updateWith($update);
			
			Debug::dump($updated, "Updated records:");
			
			// Delete everything again
			$delete = new Delete('cards');
			
			// We can use the same where statement!
			$delete->where($where);
			
			Debug::dump($delete->getSqlString(), "Executing statement:");
			$deleted = $tableGateway->deleteWith($delete);
			
			Debug::dump($deleted, "Deleted records:");
		}
		
		unset($deleted, $delete, $where, $updated, $update, $insert, $result, 
				$primaryKey, $tableGateway);
		
		/**
		 * We are now going to do the exact same thing again but in another way,
		 * just to show you the different possibilities. This method though has
		 * a DB profiler attached to the queries.
		 */
		
		Debug::dump("******************* ROUND 2: FIGHT! *******************");
		
		// Instantiate a new Sql object
		$sql = new Sql($this->connection);
		
		// Create a new profiler to use
		$profiler = new Profiler();
		
		// Set the profiler to the database connection
		$this->connection
			 ->setProfiler($profiler);
		
		// Create a new insert statement
		$insert = $sql->insert('cards');
		
		// Define the columns in the table, although not required, it is best
		// practice
		$insert->columns(array(
			'id',
			'color',
			'type',
			'value',
		));
		
		// Assign the values we want to insert
		$insert->values(array(
			'color' => 'diamond',
			'type' => 'picture',
			'value' => 'Goblin'
		));
		
		// Create a new table gateway to perform our SQL on
		$statement = $sql->prepareStatementForSqlObject($insert);
		
		try {
			// Execute and get our primary key as a result
			$primaryKey= $statement->execute()
					              ->getGeneratedValue();
			$result = true;
		} catch (Exception $e) {
			Debug::dump($e->getMessage());
			$result = false;
		}
		
		if ($result === true) {
				
			Debug::dump($primaryKey, "Inserted with ID:");
			
			// Now let's update our record
			$update = $sql->update('cards');
			$update->set(array(
				'color' => 'spade',
				'value' => '10',
				'type' => 'number',
			));
			
			// Now create a where statement
			$where = new Where();
			$where->equalTo("id", $primaryKey);
			
			// Set the where in the update statement
			$update->where($where);
			
			// Create the prepared statement for execution
			$statement = $sql->prepareStatementForSqlObject($update);
			
			$updated = $statement->execute();
					
			Debug::dump($updated->getAffectedRows(), "Updated records:");
			
			// Delete everything again
			$delete = $sql->delete('cards');
			
			// We can use the same where statement!
			$delete->where($where);
			
			// Create the prepared statement for execution
			$statement = $sql->prepareStatementForSqlObject($delete);
			
			$deleted = $statement->execute();
					
			Debug::dump($deleted->getAffectedRows(), "Deleted records:");
		}
		
		// Let's output all the profiles
		Debug::dump($this->connection->getProfiler()->getProfiles(), "DB Profiles:");
	}
}