<?php
    
    //MistServer PHP API
    $host = "http://petya:8080";
    $mist = new MistServer(null, null ,[$host]);

    class MistServer {
    
    protected $auth = Array();
    public $data = Array();
    
    /**
      The constructor saves the credentials and hostname
      @param $username The username used to log in to the MistServer instance
      @param $password The password used to log in to the MistServer instance
      @param $host     Url to the MistServer API; defaults to "http://localhost:4242/api"
    */
    public function __construct(
      $username = "",
      $password = false,
      $host = "http://localhost:4242/api"
    ) {
      
      $this->user = Array(
        "host"     => $host,
        "username" => $username,
        "password" => ($password ? md5($password) : "")
      );
    }
    
    /**
      Sends $data to MistServer and returns the response
      Automatically logs in if required
      On error, an associative array containing an "error" field with a message is returned
      
      @param $data Associative array containing the data that is sent to MistServer; defaults to empty
    */
    public function send(array $data = Array()) {
      $sendData = $data;
      
      //append the authorize field to the data that will be sent to MistServer
      $sendData["authorize"] = Array(
        "username" => $this->user["username"],
        "password" => ""
      );
      if (isset($this->user["authstring"])) {
        $sendData["authorize"]["password"] = md5($this->user["password"].$this->user["authstring"]);
      }
      
      //enables minimal mode: doesn't send logs and such unless requested
      if (!isset($sendData["minimal"])) {
        $sendData["minimal"] = true;
      }
      else if (!$sendData["minimal"]) {
        //still allow overrides (Mist doesn't care about the value of minimal, it only checks if the key is set
        unset($sendData["minimal"]);
      }
      
      //prepare and execute a CURL HTTP POST
      $postData = http_build_query(Array("command" => json_encode($sendData)));
      
      $ch = curl_init();
      curl_setopt($ch,CURLOPT_URL,$this->user["host"]);
      curl_setopt($ch,CURLOPT_POST,1);
      curl_setopt($ch,CURLOPT_POSTFIELDS,$postData);
      curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
      
      $response = curl_exec($ch);
      
      //handle CURL errors
      if ($response === false) {
        $output = Array("error" => curl_error($ch));
        curl_close($ch);
        return $output;
      }
      
      //close the connection and decode the response JSON
      curl_close($ch);
      $response = json_decode($response,true);
      
      if (($response == NULL) || (!isset($response["authorize"])) || (!isset($response["authorize"]["status"]))) {
        return Array("error" => "Failed to decode response");
      }
      
      switch ($response["authorize"]["status"]) {
        case "OK":
          //everything is as expected, save and return the response object
          unset($response["authorize"]);
          $this->data = $response;
          return $response;
          break;
        case "CHALL":
          if ((isset($this->user["authstring"])) && ($this->user["authstring"] == $response["authorize"]["challenge"])) {
            return Array("error" => "Incorrect credentials.");
          }
          
          //save the authstring and send the request again
          $this->user["authstring"] = $response["authorize"]["challenge"];
          return $this->send($data);
          break;
        case "NOACC":
          return Array("error" => "Please create an account with which to access MistServer before using this PHP API.");
          break;
        default:
          return Array("error" => "MistServer response type not implemented");
      }
    }
  }
?>