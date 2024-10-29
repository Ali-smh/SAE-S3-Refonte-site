const header = document.querySelector("header")
const footer =  document.querySelector("footer")
const head = document.head

console.log(header)

function loadHeader(){
    fetch("/src/partials/html/header.html")
        .then(response => response.text())
        .then(data => {
            header.innerHTML = data
            let link = document.createElement("link")
            link.rel = "stylesheet"
            link.href = "/src/partials/css/header.css"
            head.appendChild(link)
        })
}

function loadFooter(){
    fetch("/src/partials/html/footer.html")
        .then(response => response.text())
        .then(data => {
            footer.innerHTML = data
            let link = document.createElement("link")
            link.rel = "stylesheet"
            link.href = "/src/partials/css/footer.css"
            head.appendChild(link)
        })
}

loadHeader()
loadFooter()