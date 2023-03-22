// Get all the links with the data-delete attribute
let links = document.querySelectorAll("[data-delete]");

for (let link of links) {
    link.addEventListener("click", function (e) {
        e.preventDefault();

        // Get the closest image block
        let mediaBlock = link.closest(".media-block");

        // Show the confirmation modal
        let confirmModal = document.getElementById("confirm-modal");
        let confirmMessage = document.getElementById("confirm-message");
        let confirmYes = document.getElementById("confirm-yes");
        let confirmNo = document.getElementById("confirm-no");

        confirmMessage.innerText = link.getAttribute("data-confirm-message");
        confirmModal.style.setProperty("display", "block", "important");

        // Listen to confirmation modal events
        confirmYes.addEventListener("click", function (e) {
            e.preventDefault();
            confirmModal.style.setProperty("display", "none", "important");

            // Send an AJAX request
            fetch(link.getAttribute("data-url"), {
                method: "DELETE",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ "_token": link.dataset.token })
            }).then(function (response) {
                return response.json();
            }).then(function (data) {
                if (data.success) {
                    location.href = link.getAttribute("data-redirect");
                } else {
                    alert(data.error);
                }
            }).catch(function (error) {
                alert(error);
            });
        });

        confirmNo.addEventListener("click", function (e) {
            e.preventDefault();
            confirmModal.style.setProperty("display", "none", "important");
        });
    });
}
