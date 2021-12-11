import Form from "./form";

export default class Core {

    status = 0;
    form

    constructor(status = 0) {
        this.status = status;
        this.form = new Form();
    }

    findBy(selector)
    {
        return document.querySelectorAll(selector)
    }

    findOneBy(selector)
    {
        return document.querySelector(selector)
    }

    setAttributes(elements,qualifiedName, value = null)
    {
        Array.prototype.slice.call(elements)
            .forEach(function (element) {
                if(value !== null)
                {
                    element.setAttribute(qualifiedName,value)
                } else {
                    element.removeAttribute(qualifiedName)
                }

            })
        return null
    }

    setAttribute(element,qualifiedName, value = null)
    {
        if(value)
        {
            element.setAttribute(qualifiedName,value)
        } else {
            element.removeAttribute(qualifiedName)
        }
        return null
    }

    getAttribute(element,qualifiedName)
    {
        return element.getAttribute(qualifiedName)
    }

    setClass(element,value,remove = false)
    {
        if(remove)
        {
            element.classList.remove(value)
        } else {
            element.classList.add(value)
        }
        return null
    }

    addMultipleEventListener(element, events, handler)
    {
        events.forEach(e => element.addEventListener(e, handler))
        return null
    }

    setDisplay(selector, value) {
        document.querySelector(selector).style.display = value;
    }

}