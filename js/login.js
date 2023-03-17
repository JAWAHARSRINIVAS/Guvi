$(document).ready(function () {
  
  $.ajax({
    type: 'GET',
    url: 'http://localhost/Guvi/php/register.php',
    headers: {
      'Access-Control-Allow-Origin': '*',
    },
    success: function (response) {
       console.log(response);
       if(response.includes("end session"))
       {
        localStorage.clear();
       }
       if(response.includes("session is live"))
       {
          window.location.href="./profile.html";
       }
    },
    
  });

    $('#login-form').submit(function (event) {
      event.preventDefault();
  
      var email = $('#email').val();
      var password = $('#password').val();

      console.log(email + "  "+password);

      

      $.ajax({
        type: 'POST',
        url: 'http://localhost/Guvi/php/login.php',
        data: {
          email: email,
          password: password,
        },
        headers: {
          'Access-Control-Allow-Origin': '*',
        },
        success: function (response) {
          console.log(response);
          var data = JSON.parse( response );
          console.log(data);
          if (data["msg"] == "success") {
            alert('Logined successful!');
            localStorage.setItem('sessionId',data['sessionId']);
            window.location.href = './profile.html';
          } else {
            alert('Login : ' + data['msg']);
          }
          
        },
        error: function (xhr, status, error) {
          alert('Login failed : ' + error);
        },
      });
    });
  });
  

