document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form')

    form.addEventListener('submit', (e) => {
        e.preventDefault()
        
        const cnpj = document.querySelector('#cnpj-input').value
        const xmls = document.querySelector('#xml-input').files

        if (!cnpj || cnpj.length < 12) {document.querySelector('#cnpj-error').innerText = 'O campo é obrigatorio e precisa ter pelo menos 12 digitos'} 
        else {document.querySelector("#cnpj-error").innerText = ""}

        if (!xmls) {document.querySelector("#xml-error").innerText = 'O campo é obrigatorio'} 
        else {document.querySelector("#xml-error").innerText = ""}

        if (document.querySelector("#cnpj-error").innerText != "" || document.querySelector("#xml-error").innerText != "") return

        const formData = new FormData()

        for (let i = 0; i < xmls.length; i++)
            formData.append(`xml_${i}`, xmls[i])
        formData.append('cnpj', cnpj)

        fetch('/generate-query', {
            method: 'POST',
            body: formData,
        })
        .then(data => data.text())
        .then(data => {
            console.log(data)
            alert('Queries geradas com sucesso')
        })
    })
})