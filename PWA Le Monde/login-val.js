$(function() {
    $("form[name='register']").validate({
        rules: {
            korisnicko_ime: {
                required: true,
                maxlength: 12,
                minlength: 3,
            },
            lozinka: {
                required: true,
                minlength: 3
            },
            lozinka2: {
                required: true,
                equalTo: "#lozinka"
            }
        },
        messages: {
            korisnicko_ime: {
                required: "Korisničko ime nesmije bit prazno",
                minlength: "Korisničko ime nesmije biti manje od 3"
            },
            lozinka: {
                required: "Potrebno je upisati lozinku",
                maxlength: "Lozinka nesmije biti dulja od 12",
                minlength: "Lozinka nesmije biti manja od 3"
            },
            lozinka2: {
                required: "Potrebno je ponoviti lozinku",
                equalTo: "Lozinke trebaju biti iste"
            }
        },
  
       
        submitHandler: function(form) {
            form.submit();
        }
    });
  });