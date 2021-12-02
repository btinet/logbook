( function (){
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })

    // Fetch all elements with class name ".disabled"
    let $buttons = document.querySelectorAll('.disabled')

    // Loop over them and prevent submission
    Array.prototype.slice.call($buttons)
        .forEach(function ($button) {
            $button.setAttribute("disabled","disabled");
        })
})();