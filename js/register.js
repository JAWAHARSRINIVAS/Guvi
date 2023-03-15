$(document).ready(function () {
  $('#register-form').submit(function (event) {
    event.preventDefault();
    var name = $('#username').val();
    var email = $('#email').val();
    var password = $('#password').val();

    var age = $('#age').val();
    var dob = $('#dob').val();
    var contact = $('#contact').val();

    console.log(name + email + password + age + dob + contact);

    $.ajax({
      type: 'POST',
      url: 'http://localhost/Guvi/php/register.php',
      data: {
        name: name,
        email: email,
        password: password,
        age: age,
        dob: dob,
        contact: contact,
      },
      headers: {
        'Access-Control-Allow-Origin': '*',
      },
      success: function (response) {
        if (response == 'New record created successfully.') {
          alert('Register successful!');
        } else {
          alert('Register failed: ' + response);
        }
      },
      error: function (xhr, status, error) {
        alert('Register failed : ' + error);
      },
    });
  });
});
