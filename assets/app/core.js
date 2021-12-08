
export function setAttributes (elements,qualifiedName, value)
{
    Array.prototype.slice.call(elements)
        .forEach(function (element) {
            element.setAttribute(qualifiedName,value);
        })
}

export function validateForms(forms)
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

export {selectAll} from "./select.js"

