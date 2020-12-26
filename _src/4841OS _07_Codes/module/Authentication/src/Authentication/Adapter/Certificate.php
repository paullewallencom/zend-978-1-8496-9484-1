<?php

namespace Authentication\Adapter;

use Zend\Authentication\Adapter\AdapterInterface;

class Certificate implements AdapterInterface
{
	const AUTH_FAIL_INV_CERT = 0;
	const AUTH_FAIL_NO_HTTPS = 1;
	const AUTH_FAIL_PARSE_CERT = 2;
	const AUTH_FAIL_EXP_CERT = 3;
	const AUTH_FAIL_NOT_ALL_FIELDS = 4;
	const AUTH_FAIL_NO_DB_ADAPTER = 5;
	const AUTH_FAIL_SQL_ERR = 6;
	const AUTH_FAIL_NO_USER = 7;
	
	private $certificate;
	private $error = -1;
	private $identity;
	
	/**
	 * @var \Zend\Db\Adapter\Adapter
	 */
	private $dbAdapter;
	
	/**
	 * Sets the Db adapter.
	 *	
	 * @param \Zend\Db\Adapter\Adapter $db
	 */
	public function setDbAdapter(\Zend\Db\Adapter\Adapter $db) {
		$this->dbAdapter = $db;
	}
	
	/**
	 * Returns the Db adapter.
	 * 
	 * @return \Zend\Db\Adapter\Adapter
	 */
	private function getDbAdapter()
	{
		return $this->dbAdapter;
	}
	
	/**
	 * Retrieves the identity of the user.
	 * 
	 * @return array
	 */
	public function getIdentity() 
	{
		return $this->identity;
	}
	
	/**
	 * Gets the latest error message back.
	 * 
	 * @return string
	 */
	public function getErrorMessage() 
	{
		switch ($this->error) {
			case self::AUTH_FAIL_SQL_ERR:
				$retval = "SQL error occurred while checking for the user.";
				break;
			case self::AUTH_FAIL_INV_CERT:
				$retval = "Certificate provided is invalid.";
				break;
			case self::AUTH_FAIL_PARSE_CERT:
				$retval = "Certificate provided couldn't be parsed.";
				break;
			case self::AUTH_FAIL_EXP_CERT:
				$retval = "Certificate has expired.";
				break;
			case self::AUTH_FAIL_NO_DB_ADAPTER:
				$retval = "No Database adapter set.";
				break;
			case self::AUTH_FAIL_NOT_ALL_FIELDS:
				$retval = "Not all the fields required are available.";
				break;
			case self::AUTH_FAIL_NO_USER:
				$retval = "The user could not be found.";
				break;
			case self::AUTH_FAIL_NO_HTTPS:
				$retval = "Connection is not secure.";
				break;
			case -1:
				$retval = "No error occurred.";
				break;
			default:
				$retval = "Unknown error occurred.";
				break;
		}
		
		// Reset the error
		$this->error = -1;
		
		return $retval;
	}
	
	/**
	 * Sets an error.
	 * 
	 * @param int $error
	 */
	private function setError($error) 
	{
		$this->error = $error;
	}
	
	/**
	 * Returns the client certificate.
	 * 
	 * @return string
	 */
	private function getCertificate()
	{
		if ($this->certificate !== null) {
			return $this->certificate;
		} else if (isset($_SERVER['SSL_CLIENT_CERT']) === true) {
			$this->certificate = $this->setCertificate($_SERVER['SSL_CLIENT_CERT']);
			return $this->certificate;
		} else {
			return false;
		}
	}
	
	/**
	 * Sets (and parses) a certificate, returns false if the certificate couldn't
	 * be parsed.
	 * 
	 * @param string $certificateContent
	 * @return boolean
	 */
	public function setCertificate($certificateContent) 
	{
		$certificate = openssl_x509_parse($certificateContent);
		
		if ($certificate !== false) {
			$this->certificate = $certificate;
			
			return true;
		} else {
			$this->setError(self::AUTH_FAIL_PARSE_CERT);
			return false;
		}
	}
	
	/**
	 * Checks if the current certificate is valid or not.
	 * 
	 * @return boolean
	 */
	private function isCertificateValid()
	{
		$certificate = $this->getCertificate();
		
		if ($certificate !== false) {
			// Check if the valid from and to fields are set, because if they are
			// not, we won't be able to check if the certificate is valid or not
			if (isset($certificate['validFrom_time_t']) === true 
					&& isset($certificate['validTo_time_t']) === true) {
				// Check if the from time is smaller than our current time and
				// the to time is bigger than the current time
				if (time() >= $certificate['validFrom_time_t'] 
						&& time() < $certificate['validTo_time_t']) {
					$retval = true;
				}
			}
		}
		
		unset($certificate);
		
		return isset($retval) ? $retval : false;
	}
	
	
	/**
	 * Returns true if the current connection is through HTTPS.
	 * 
	 * @return boolean
	 */
	private function isHTTPS()
	{
		return isset($_SERVER['HTTPS']) ? true : false;
	}
	
	/**
	 * Checks if all our fields (issuer, issuer[O], issuer[CN], 
	 * issuer[emailAddress], serialNumber) are in the certificate.
	 * 
	 * @return boolean
	 */
	private function checkRequiredFields()
	{
		$certificate = $this->getCertificate();
		
		if ($certificate !== false) {
			// We want to check if the following fields (and subfields) are in
			// the certificate
			$required = array(
				'issuer' => array('O', 'CN', 'emailAddress'), 
				'serialNumber' => null
			);
			
			// Loop through the primary fields
			foreach ($required as $field=>$value) {
				if (in_array($field, $certificate) === true) {
					// The primary field is in there, check if there are any
					// secondary fields we need to check
					if (is_array($value) === true && is_array($certificate[$field]) === true) {
						// Loop through the secondary fields
						foreach ($value as $key) {
							if (in_array($key, array_keys($certificate[$field])) === false) {
								return false;
							}
						}
					}
				} else {
					return false;
				}
			}
			
			// If we reach this point, we are always ok to go
			$retval = true;
			
			unset($required);
		}
		
		unset($certificate);
		
		return isset($retval) ? $retval : false;
	}
	
	/**
	 * Tries to authenticate the user through the certificate.
	 * 
	 * @return boolean
	 */
	public function authenticate() 
	{
		$continue = true;
		
		if ($this->getDbAdapter() !== null) {
			// Check if we are on a secure connection
			if ($this->isHTTPS() === true) {
				// Check if the certificate is valid
				if ($this->getCertificate() !== false) {
					// Check if the fields we require are available
					if ($this->checkRequiredFields() === true) {
						// Check if the certificate isn't expired
						if ($this->isCertificateValid() === false) {
							$this->setError(self::AUTH_FAIL_EXP_CERT);
							$continue = false;
						}
					} else {
						$this->setError(self::AUTH_FAIL_NOT_ALL_FIELDS);
						$continue = false;
					}
				} else {
					$this->setError(self::AUTH_FAIL_INV_CERT);
					$continue = false;
				}
			} else {
				$this->setError(self::AUTH_FAIL_NO_HTTPS);
				$continue = false;
			}
		} else {
			$this->setError(self::AUTH_FAIL_NO_DB_ADAPTER);
			$continue = false;
		}
		
		if ($continue === true) {
			// Now we are going to check with the database if the email address
			// is in there
			$statement = $this->getDbAdapter()->createStatement(
					"SELECT * FROM users WHERE email = :email"
			);
			
			try {
				$result = $statement->execute(array(
					'email' => $this->getCertificateVariable('emailAddress')
				));
				
				// Check if we have one result
				if ($result->count() === 1) {
					// One result found, put it in the identity kit
					$this->identity = $result->current();
					
					// Because we are super-cool add some of our certificate 
					// variables as well
					$this->identity['serialNumber'] = $this->getCertificateVariable('serialNumber');
					$this->identity['organization'] = $this->getCertificateVariable('O');
					$this->identity['commonName'] = $this->getCertificateVariable('CN');

					// We successfully found our user
					$retval = true;
				} else {
					$this->setError(self::AUTH_FAIL_NO_USER);
				}
			} catch (\Exception $e) {
				$this->setError(self::AUTH_FAIL_SQL_ERR);
				error_log($e->getMessage());
			}
		}
		
		// Return the retval is we have one, otherwise just fail
		return isset($retval) ? $retval : false;
	}	
	
	/**
	 * Retrieves a variable from the certificate, returns null if not found.
	 * 
	 * @param string $variable
	 * @return string
	 */
	private function getCertificateVariable($variable)
	{
		if (is_array($this->certificate) === true && isset($this->certificate[$variable]) === true) {
			return $this->certificate[$variable];
		} else if (is_array($this->certificate) === true && isset($this->certificate['issuer'][$variable]) === true) {
			return $this->certificate['issuer'][$variable];
		} else {
			return null;
		}
	}
}