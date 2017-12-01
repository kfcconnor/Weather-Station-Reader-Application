<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

# Database config variables (for your MySQL database)
 
$config['displayErrorDetails'] = true;
$config['db']['host']   = "eu-cdbr-azure-west-d.cloudapp.net";
$config['db']['user']   = "b7c2a3d2fe2772";
$config['db']['pass']   = "6fe33472";
$config['db']['dbname'] = "connorthompsondb";
 
// bind the database settings to the app (your service) configuration
$app = new \Slim\App(["settings" => $config]);
$container = $app->getContainer();
 
# Database container function, you will simply call this as 'db' in your REST methods
 
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'],
        $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

# GET endpoint to return all data for selected sensor in the sensor table
$app->get('/sensors/getsensordata', function (Request $request, Response $response) {
 
	$flag = 0;
   // Get the sensorid as passed in the calling URL
   // Actually gets all parameters that are specified in the URL
   $allGetVars = $request->getQueryParams();
   foreach($allGetVars as $key => $param){
   $sensor_time = $allGetVars['timestamp'];
   $sensor_id = $allGetVars['sensorid'];
   $sensor_type = $allGetVars['sensortype'];
   $sensor_value = $allGetVars['value'];
}
    
   // Create database query to select all sensor data for the selected sensor
   // Use our db connection from app configuration
   $db = $this->db;
 
   // Determines what paramaters have been entered and chooses the right sql statement
   if(!empty($sensor_id)){
	   if(!empty($sensor_type)){
		   if(!empty($sensor_value)){
			   if(!empty($sensor_time)){
				   // All params
				   $stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_id = :sensorid and sensor_type = :sensortype and value = :sensorvalue and time_stamp :sensortime ORDER BY time_stamp desc');
				   $stmt->bindValue(':sensorid', $sensor_id);
				   $stmt->bindValue(':sensortype', $sensor_type);
				   $stmt->bindValue(':sensorvalue', $sensor_value);
				   $stmt->bindValue(':sensortime', $sensor_time);
			   } else {
				   
				   $stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_id = :sensorid and sensor_type = :sensortype and value = :sensorvalue ORDER BY time_stamp desc');
				   $stmt->bindValue(':sensorid', $sensor_id);
				   $stmt->bindValue(':sensortype', $sensor_type);
				   $stmt->bindValue(':sensorvalue', $sensor_value);
			   }
		   } elseif(!empty($sensor_time)) {
			    $stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_id = :sensorid and sensor_type = :sensortype and time_stamp = :sensortime ORDER BY time_stamp desc');
				$stmt->bindValue(':sensorid', $sensor_id);
				$stmt->bindValue(':sensortype', $sensor_type);
				$stmt->bindValue(':sensortime', $sensor_time);
		   }else {
			   $stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_id = :sensorid and sensor_type = :sensortype ORDER BY time_stamp desc');
				$stmt->bindValue(':sensorid', $sensor_id);
				$stmt->bindValue(':sensortype', $sensor_type);
		   }
	   }elseif(!empty($sensor_value)){ 
			$stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_id = :sensorid and value = :sensorvalue ORDER BY time_stamp desc');
			$stmt->bindValue(':sensorid', $sensor_id);
			$stmt->bindValue(':sensortype', $sensor_type);
		}elseif(!empty($sensor_time)) {
			$stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_id = :sensorid time_stamp = :sensortime ORDER BY time_stamp desc');
			$stmt->bindValue(':sensorid', $sensor_id);
			$stmt->bindValue(':sensortime', $sensor_time);
	   } else {
			$stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_id = :sensorid');
			$stmt->bindValue(':sensorid', $sensor_id);
	   }
   } elseif(!empty($sensor_type)) {
	   if (!empty($sensor_value)){
			if(!empty($sensor_time)){
				$stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_type = :sensortype and value = :sensorvalue and time_stamp = sensortime ORDER BY time_stamp desc');
				$stmt->bindValue(':sensortype', $sensor_type);
				$stmt->bindValue(':sensorvalue', $sensor_value);
				$stmt->bindValue(':sensortime', $sensor_time);
			} else {
				$stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_type = :sensortype and value = :sensorvalue ORDER BY time_stamp desc');
				$stmt->bindValue(':sensortype', $sensor_type);
				$stmt->bindValue(':sensorvalue', $sensor_value);
			}
	   }elseif(!empty($sensor_time)){
		    $stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_type = :sensortype and time_stamp = sensortime ORDER BY time_stamp desc');
			$stmt->bindValue(':sensortype', $sensor_type);
			$stmt->bindValue(':sensortime', $sensor_time);
	   }else{
			$stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_type = :sensortype ORDER BY time_stamp desc');
			$stmt->bindValue(':sensortype', $sensor_type);
	   }
   } elseif(!empty($sensor_value)) {
	   if(!empty($sensor_time)){
		   $stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where value = :sensorvalue and time_stamp = sensortime ORDER BY time_stamp desc');
				$stmt->bindValue(':sensorvalue', $sensor_value);
				$stmt->bindValue(':sensortime', $sensor_time);
	   } else {
		$stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where sensor_value = :sensorvalue ORDER BY time_stamp desc');
		$stmt->bindValue(':sensorvalue', $sensor_value);
		}
   } elseif(!empty($sensor_time)){
		$stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors where time_stamp = :sensortime ORDER BY time_stamp desc');
		$stmt->bindValue(':sensortime', $sensor_time);
   }else {
	   $stmt = $db->prepare('SELECT time_stamp,sensor_id, sensor_type, value from sensors ORDER BY time_stamp desc');
   }
   
   // Parameterise query for security puposes

   // Run query
   $stmt->execute();
   // Save query today using PDO object
   $sensordata = $stmt->fetchAll(PDO::FETCH_OBJ);
   // Close database connection
   $db = null;
   // return sensor data for given sensorid to calling client as JSON data
   $newResponse = $response->withHeader('Content-type', 'application/json');
   $newResponse = $response->withHeader('Access-Control-Allow-Origin', '*');
   $newResponse = $response->withJson($sensordata);
   return $newResponse;
});

$app->get('/access/getaccessdata', function (Request $request, Response $response) {
 
	$flag = 0;
   // Get the sensorid as passed in the calling URL
   // Actually gets all parameters that are specified in the URL
   $allGetVars = $request->getQueryParams();
   foreach($allGetVars as $key => $param){
   $access_time = $allGetVars['timestamp'];
}
    
   // Create database query to select all sensor data for the selected sensor
   // Use our db connection from app configuration
   $db = $this->db;
	
   if(!empty($access_time)){
		$stmt = $db->prepare('SELECT time_stamp, Longitude, Latitude from access where access_time = :timestamp ORDER BY time_stamp desc');
		$stmt->bindValue(':timestamp', $access_time);
   } else {
	   $stmt = $db->prepare('SELECT time_stamp, Longitude, Latitude from access ORDER BY time_stamp desc');
   }
   
   // Run query
   $stmt->execute();
   // Save query today using PDO object
   $accessdata = $stmt->fetchAll(PDO::FETCH_OBJ);
   // Close database connection
   $db = null;
   // return sensor data for given sensorid to calling client as JSON data
   $newResponse = $response->withHeader('Content-type', 'application/json');
   $newResponse = $response->withHeader('Access-Control-Allow-Origin', '*');
   $newResponse = $response->withJson($accessdata);
   return $newResponse;
});

// below is a GET endpoint URI that accepts a parameter called name
$app->get('/hello/{name}', function (Request $request, Response $response) {
    $name = $request->getAttribute('name');
   $data = array('name' => $name, 'age' => 40);
   $newResponse = $response->withJson($data);
    return $newResponse;
});

# POST endpoint to post specific sensor data to the sensors database table
$app->post('/sensors/postsensordata', function (Request $request, Response $response) {
 
    // Get the sensorid, sensortype, and sensor value from parameters in the URL from calling client
    $allGetVars = $request->getQueryParams();
    foreach($allGetVars as $key => $param){
    $sensor_id = $allGetVars['sensorid'];
    $sensor_type = $allGetVars['sensortype'];
    $sensor_value = $allGetVars['value'];
}
 
   // Create database query to insert row of sensor data
   // Use our db connection from app configuration
   $db = $this->db;
 
   // // SQL INSERT statement query to save data to sensors table
   $stmt = $db->prepare('INSERT into sensors (sensor_id, sensor_type, value) VALUES (:sensorid, :sensortype, :sensorvalue)');
 
   // Parameterise query for security puposes
   $stmt->bindValue(':sensorid', $sensor_id);
   $stmt->bindValue(':sensortype', $sensor_type);
   $stmt->bindValue(':sensorvalue', $sensor_value);;
   $stmt->execute();
 
   // Get id (auto incremented) of  newly inserted row of sensor data
   $lastid = $db->lastInsertId();
   $db = null;
 
   // Return id of inserted row to calling client in JSON
   $newResponse = $response->withHeader('Content-type', 'application/json');
   $newResponse = $response->withJson($lastid);
   return $newResponse;
});

$app->post('/access/postaccessdata', function (Request $request, Response $response) {
 
    // Get the sensorid, sensortype, and sensor value from parameters in the URL from calling client
    $allGetVars = $request->getQueryParams();
    foreach($allGetVars as $key => $param){
    $access_longitude = $allGetVars['longitude'];
	$access_latitude = $allGetVars['latitude'];
}
 
   // Create database query to insert row of sensor data
   // Use our db connection from app configuration
   $db = $this->db;
 
   // // SQL INSERT statement query to save data to sensors table
   $stmt = $db->prepare('INSERT into access (Longitude, Latitude) VALUES (:longitude, :latitude)');
 
   // Parameterise query for security puposes
   $stmt->bindValue(':longitude', $access_longitude);
   $stmt->bindValue(':latitude', $access_latitude);
   $stmt->execute();
 
   // Get id (auto incremented) of  newly inserted row of sensor data
   $lastid = $db->lastInsertId();
   $db = null;
 
   // Return id of inserted row to calling client in JSON
   $newResponse = $response->withHeader('Content-type', 'application/json');
   $newResponse = $response->withJson($lastid);
   return $newResponse;
});

$app->delete('/sensors/deletesensordata', function (Request $request, Response $response) {
 
    // Get the sensorid, sensortype, and sensor value from parameters in the URL from calling client
    $allGetVars = $request->getQueryParams();
    foreach($allGetVars as $key => $param){
    $sensor_id = $allGetVars['sensorid'];
    $sensor_type = $allGetVars['sensortype'];
    $sensor_value = $allGetVars['value'];
}
 
   // Create database query to insert row of sensor data
   // Use our db connection from app configuration
   $db = $this->db;
 
   // // SQL INSERT statement query to save data to sensors table
   $stmt = $db->prepare('DELETE FROM sensors WHERE `sensor_id`= :sensorid AND `sensor_type`= :sensortype AND `value`= :sensorvalue');
 
   // Parameterise query for security puposes
   $stmt->bindValue(':sensorid', $sensor_id);
   $stmt->bindValue(':sensortype', $sensor_type);
   $stmt->bindValue(':value', $sensor_value);;
   $stmt->execute();
   
   $newResponse = 1;
   return $newResponse;
});


$app->run();

?>