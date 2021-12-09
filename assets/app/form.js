export default class Form {

    constructor() {

    }

    validate(forms)
    {
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
    }

    submitOn(forms,event = 'click')
    {
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener(event, function (event) {
                    this.form.submit();
                }, false)
            })
    }

}
