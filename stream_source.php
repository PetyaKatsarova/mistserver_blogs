
<!-- stream name (string); payload -->
<?PHP
  
  //a little stand-in for a video database
  function get_library() {
    // $video_directory = "/mnt/data/streaming/vids/";
    $video_directory = "/home/petya/work/mywebsite/video_lib/";
    
    //list of files
    $library = Array();
    $files = scandir($video_directory);
    foreach ($files as $index => $file) {
      
      //skip if this is not a file
      if (in_array($file,Array("..","."))) {
        continue;
      }
      
      //retrieve extension
      $extension = explode(".",$file);
      $extension = $extension[count($extension)-1];
      //validate extension, expand these as wanted
      if (!in_array($extension,Array("flv","mp4"))) {
        continue;
      }
      
      $library[] = $video_directory.$file;
    }
    
    return $library;
  }
  
  //confirm the page is being requested by MistServer's STREAM_SOURCE trigger
  if ($_SERVER["HTTP_X_TRIGGER"] != "STREAM_SOURCE") {
    http_response_code(405);
    error_log("Unsupported trigger type.");
    echo("Unsupported trigger type.");
    return;
  }
  
  //retrieve the stream name
  //retrieve the post body
  $post_body = file_get_contents("php://input");
  //convert to an array
  $post_body = explode("\n",$post_body);
  $stream = $post_body[0];
  
  //if the stream is "random", or "random" plus a wildcard token
  if (($stream == "random") || (substr($stream,0,7)) == "random+") {
    /*
      Note: As the stream source trigger is only called when the stream is not yet active,
            a new random file won't be selected until the stream has timed out.
            A way around this is to add a random wildcard token to the streamname as the video is being embedded.
    */
    
    //select a random video from the library array
    $library = get_library();
    $random_video_id = array_rand($library);
    
    //return the path
    echo $library[$random_video_id];
    return;
  }
  
  if ((substr($stream,0,8)) == "library+") {
    $video_id = substr($stream,8); //strip the "library+" part
    
    $library = get_library();
    
    echo $library[$video_id];
    return;
  }
  
  http_response_code(405);
  error_log("Something went wrong.");
  return;
  
  //Note: Be careful not to send a newline after the response.
