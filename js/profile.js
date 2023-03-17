$(document).ready(function () {

    console.log("started ");


    $.ajax({
      type: 'POST',
      url: 'http://localhost/Guvi/php/profile.php',
      data: {
        sessionId : localStorage.getItem("sessionId")
        ?localStorage.getItem("sessionId")
        :'empty'
      },
      success: function (response) {
       console.log(response);
       if(!response.includes("session ended"))
       {
          var data = JSON.parse(response);
          console.log(data);
          $('#name').text(data['name']);
          $('#email').text(data['email']);
          $('#age').text(data['age']);
          $('#dob').text(data['dob']);
          $('#contact').text(data['contact']);
       }else{
          window.location.href="./register.html"
       }
        
      },
      error: function (xhr, status, error) {
        alert('Update Failed : ', error);
      },
    });

    
      
      $("#logout").click(function (event) {
        event.preventDefault();
        localStorage.clear();
        window.location.href = './index.html';
        $.ajax({
          type: 'GET',
          url: 'http://localhost/Guvi/php/profile.php',
        });
      });

      $("#update").click(function (event) {
        event.preventDefault();

        var name = $('#name').text();
        var email = $('#email').text();
        var age = $('#age').text();
        var dob = $('#dob').text();
        var contact = $('#contact').text();
        console.log(name + " "+age);
        $.ajax({
          type: 'POST',
          url: 'http://localhost/Guvi/php/profile.php',
          data: {
            name: name,
            email: email,
            password:"123",
            age: age,
            dob: dob,
            contact: contact,
          },
          success: function (response) {
            var data = JSON.parse(response);
            if (data['msg'].includes("Update success")) {
              alert('Updated successful');
              localStorage.setItem('user',response);
            } else {
              alert('Update : ', response);
            }
            
          },
          error: function (xhr, status, error) {
            alert('Update Failed : ', error);
          },
        });

      });
});

