<?php
namespace App\Data;
require_once DIR.'App/Data/conexao.php';

use App\Data\DB as DB;

use \Error;
use \Exception;

Class CRUD extends DB{

	public static function beginTransaction() {
		parent::Conectar()->beginTransaction();
	}

	public static function commit() {
		parent::Conectar()->commit();
	}
	
    public static function Query(string $sql, null|array $values=null) :void {
		
		if(isset($values[1]) && is_array($values[1])) {
			$firstPos = strpos($sql, '(?');
			$lastPos = strrpos($sql, '?)')+2;
			$amountCaracters = $lastPos-$firstPos;
			$value = substr($sql, $firstPos, $amountCaracters);

			for($i=1; $i < count($values); $i++) {
				$sql .= ",$value";
			} 
		}

    	$stmt = parent::Conectar()->prepare($sql);

		if($values):
			if(isset($values[0]) && is_array($values[0])) {
				$key = 0;
				for($i=0; $i < count($values); $i++) {
					foreach($values[$i] as $value) {
						$key++;
						$stmt->bindValue($key, $value);
					}
				}
			} else {
				foreach ($values as $key => $value) {
					if(isset($values[0])) $key++;

					$stmt->bindValue($key, $value);   
            	}
			}
		endif;

		try {
    		$stmt->execute();
		} catch(Exception $e) {
			//var_dump($e);
			throw new Error(message: $stmt->errorInfo()[2], code: $stmt->errorInfo()[1]);
		}	 
	}
	
	public static function Consult(string $sql, null|array $values=null, string $returnType = 'auto' ) :array|object|null {
		
		$stmt = parent::Conectar()->prepare($sql);
		if($values):
            foreach ($values as $key => $value) {
				if(isset($values[0])) $key++;
                $stmt->bindValue($key, $value);        
            }
		endif;
		try{
    		$stmt->execute();
			$rowCount = $stmt->rowCount();
			if(($rowCount > 1 or $returnType == "array") && $rowCount >= 1):
				while ($result = $stmt->fetchObject(__CLASS__)) {
					if($results[] = $result);
				};
				return $results;	
			elseif($rowCount == 1 && ($returnType == "auto" or $returnType == "object")):
				return $stmt->fetchObject(__CLASS__);
			else:
				return null;
			endif;
		} catch (Exception $e) {
			throw new Error(message: $stmt->errorInfo()[2], code: $stmt->errorInfo()[1]);
		}
		
	}
}