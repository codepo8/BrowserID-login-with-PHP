<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<style>
  *{margin:0;padding:0;font-size:15px;font-family:helvetica,arial,sans-serif}
  footer,section,header{display:block;}
  h1{margin:2em}
  button,p{border:none;background:transparent;margin:0 2em;}
</style>
<script src="https://browserid.org/include.js"></script></head>
<body>
  <header><h1>Darn it, I don't know you&hellip;</h1></header>
  <section>
    <button><img src="https://browserid.org/i/sign_in_green.png" alt="sign in with browser ID"></button>
  </section>
<script>
(function(){
  var request,
      but = document.querySelector('button'),
      h1 = document.querySelector('h1');
    
  but.addEventListener('click', function(ev) {

    navigator.id.getVerifiedEmail(function(assertion) {
      if (assertion) {
        verify(assertion);
      } else {
        alert('I still don\'t know you...');
      }
    });

    function verify(assertion) {
      request = new XMLHttpRequest();
      var parameters = 'assert=' + assertion;
      request.open('POST', 'verify.php');
      request.setRequestHeader('If-Modified-Since',
                               'Wed, 05 Apr 2006 00:00:00 GMT');
      request.setRequestHeader('Content-type',
                               'application/x-www-form-urlencoded');
      request.setRequestHeader('Content-length', parameters.length);
      request.setRequestHeader('Connection', 'close');
      request.send(encodeURI(parameters));
    
      request.onreadystatechange = function() {
        if (request.readyState == 4){
          if (request.status && (/200|304/).test(request.status)) {
            response = JSON.parse(request.responseText);
            if(response.status === 'okay') {
              message = 'Well, hi there, '+response.email;
              var p = document.createElement('p');
              p.innerHTML = message;
              but.parentNode.replaceChild(p,but);
              h1.innerHTML = 'Woohoo, I know you!';
            }
          } else{
            alert('couldn\'t log you in. Sad panda now!');
          }
        }
      };
    }

  }, false);
}());
</script>
</body>
</html>