<?php
require_once('lib/websockets.php');
use application\core\Controller;
use application\model\Chat;

class echoServer extends WebSocketServer {
  //protected $maxBufferSize = 1048576; //1MB... overkill for an echo server, but potentially plausible for other applications.

  protected function process ($user, $message) {
    $this->send($user,$message);
  }
  
  protected function connected ($user) {
    $chat_model = new Chat();
    $dialog = $chat_model->getDialog($user->from, $user->to);
    
    $jsn = json_encode($dialog, JSON_UNESCAPED_UNICODE);
    $this->send($user,$jsn);
  }
  
  protected function closed ($user) {
    // Do nothing: This is where cleanup would go, in case the user had any sort of
    // open files or other objects associated with them.  This runs after the socket 
    // has been closed, so there is no need to clean up the socket itself here.
  }
}

class serverController extends Controller {
  public function actionResponce(){  

    $server = new echoServer("127.0.0.1","8889");
    try {
      $server->run();
    }
    catch (Exception $e) {
      $server->stdout($e->getMessage());
    }


  }
}

