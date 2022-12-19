<?php
  require_once("index.html");
//   require_once("mistserver.php");
  
//   $mist = new MistServer(); //for localhost, if MistServer is running on the same machine as your webserver

//   /**
//     Wrapper function for MistServer::send() that checks for and echos errors
//     @param $data Associative array containing the data that is sent to MistServer; defaults to empty
//   */
//   function getData($data = Array()) {
//     global $mist;
    
//     $response = $mist->send($data);
//     if (isset($response["error"])) {
//       echo "<span class=\"error\">Error: ".$response["error"]."</span>";
//       return false;
//     }
//     return $response;
//   }
  
//   //echos a list of the protocols that are currently configured on this MistServer instance
//   function getCurrentProtocols() {
//     if ($data = getData(Array("config" => true))) {
//       if ((isset($data["config"])) && (isset($data["config"]["protocols"]))) {
        
//         //loop over the configured protocols and add the connector type to an output array
//         $protocols = $data["config"]["protocols"];
//         $output = Array();
//         foreach ($protocols as $p) {
//           $output[] = $p["connector"];
//         }
        
//         //echo output as a string
//         if (count($output)) {
//           echo implode(", ",$output);
//         }
//         else {
//           echo "None.";
//         }
//       }
//       else {
//         echo "<span class=\"error\">Error: Couldn't find protocols in the config data</span>";
//       }
//     }
//   }
  
//   //echos the last error message, if any exist within the 100 most recent log entries
//   function getLastErrorLog() {
//     if ($data = getData(Array("log" => true))) {
//       if (isset($data["log"])) {
//         $output = false;
        
//         //loop over the log entries, starting from the most recent one
//         for ($n = count($data["log"])-1; $n >=0; $n--) {
//           $entry = $data["log"][$n];
          
//           //check the message type
//           if (($entry[1] == "FAIL") || ($entry[1] == "ERROR")) {
//             $output = "[".date("H:i",$entry[0])."] ".$entry[2];
//             break;
//           }
//         }
        
//         if ($output) {
//           echo $output;
//         }
//         else {
//           echo "None.";
//         }
//       }
//       else {
//         echo "<span class=\"error\">Error: Couldn't find logs in the config data</span>";
//       }
//     }
//   }
  
//   function addStream($name,$options = Array()) {
//     if ($reply = getData(Array("addstream" => Array($name => $options)))) {
//       if ((isset($reply["streams"])) && (isset($reply["streams"][$name]))) {
//         echo "Stream ".$name." saved.";
//         return $reply["streams"][$name];
//       }
//       else {
//         echo "<span class=\"error\">Error: Failed to configure stream ".$name."</span>";
//         return false;
//       }
//     }
//   }
  
//   function deleteStream($name) {
//     if ($reply = getData(Array("deletestream" => Array($name)))) {
//       if ((isset($reply["streams"])) && (!isset($reply["streams"][$name]))) {
//         echo "Stream ".$name." deleted.";
//         return true;
//       }
//       else {
//         echo "<span class=\"error\">Error: Failed to delete stream ".$name."</span>";
//         return false;
//       }
//     }
//   }
  
// ?>
<!-- // <style>
//   .error {
//     color: red;
//   }
// </style>
// <h3>Protocols currently enabled</h3>
// <div>
//   <?php getCurrentProtocols(); ?>
// </div>
// <h3>Recent error</h3>
// <div>
//   <?php getLastErrorLog(); ?>
// </div>
// <h3>Stream configuration</h3>
// <div> -->
//   <?php
//     $saved = addStream("bunny",Array(
//      // "source" => "/home/petya/work/mywebsite/video_lib/local-laundry.webm"
//      "source" => "/home/petya/work/mywebsite/video_lib/bunny.mp4"
//     ));
//   ?>
//   <pre>
//     <?php echo json_encode($saved); ?>
//   </pre>
//   <?php
//     //deleteStream("locallaundry");
//   ?>
// </div>