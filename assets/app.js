import {setAttributes, validateForms, selectAll} from "./app/core";

let buttons = selectAll('.disabled')
let forms = selectAll('.needs-validation')
setAttributes(buttons,'disabled','disabled')
validateForms(forms)
