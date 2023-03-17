// Get all the links with the data-delete attribute
let links = document.querySelectorAll("[data-delete]");

for (let link of links) {
    link.addEventListener("click", function (e) {
        e.preventDefault();

        // Get the closest image block
        let mediaBlock = link.closest(".media-block");

        // Confirm the deletion
        if (confirm(`${link.getAttribute("data-confirm-message")} ?`)) {
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
                    if (data.success) {
                        mediaBlock.remove();
                        location.reload();
                    }

                    else 
                        alert(data.error);
                })
            ).catch(e => alert(e));
        }
    });
}

