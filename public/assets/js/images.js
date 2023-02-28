// Get all the links with the data-delete attribute
let links = document.querySelectorAll("[data-delete]");

for (let link of links) {
    link.addEventListener("click", function (e) {
        // Prevent the default behavior
        e.preventDefault();

        // Confirm the deletion
        if (confirm("Voulez-vous supprimer cette image ?")) {
            // Send an AJAX request
            fetch(this.getAttribute("href"), {
                method: "DELETE",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ "_token": this.dataset.token })
            }).then(
                // If the request is successful
                response => response.json().then(data => {
                    if (data.success)
                        this.parentElement.remove();
                    else
                        alert(data.error);
                })
            ).catch(e => alert(e));
        }
    });
}