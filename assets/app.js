import Core from "./app/core";

let App = new Core();

let submitButton = App.findOneBy('#list_delete');
let formInputFields = App.findBy('.checkbox-action');

App.setAttributes(App.findBy('.disabled'),'disabled','disabled')
App.form.validate(App.findBy('.needs-validation'))
App.form.submitOn(App.findBy('.filter_form'),'change')

setListener(formInputFields,validateDeleteForm)

// listen to click on each field and run custom function
function setListener(fields,customFunction, eventActions = ['click'])
{
    Array.prototype.slice.call(fields)
        .forEach(function (field) {
            let $i = 0;
            App.addMultipleEventListener(field,eventActions,customFunction)
        })
}

// validate checkboxes to enable delete button
function validateDeleteForm($i){
    Array.prototype.slice.call(formInputFields)
        .forEach(function (field) {
            if(field.checked === true){
                $i = 1;
            }
        })
    if($i === 1){
        App.setClass(submitButton,'disabled',true)
        App.setAttribute(submitButton,'disabled')
    } else {
        App.setClass(submitButton,'disabled')
        App.setAttribute(submitButton,'disabled','disabled')
    }
}
