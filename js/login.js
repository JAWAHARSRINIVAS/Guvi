$(document).ready(function () {
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
          if (response == "Record found successfully.") {
            alert('Logined successful!');
          } else {
            alert('Login : ' + response);
          }
        },
        error: function (xhr, status, error) {
          alert('Login failed : ' + error);
        },
      });
    });
  });
  