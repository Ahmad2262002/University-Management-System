<?php 
session_start();
  include ('connection.php');
 
  foreach (array_keys($_SESSION) as $user) {
    unset($_SESSION[$user]);
}

  
  header("Location:index.php");
?>
<script>
  var timerElement = document.getElementById('timer');
  var timerId;

  function logout() {
    clearInterval(timerId); 
    localStorage.removeItem('loginTime'); 
  }

  logout();
</script>
