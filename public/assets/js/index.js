// Scroll to tricks section in the homepage
function scrollToTricks() {
    const element = document.getElementById("tricks");
    element.scrollIntoView({ behavior: "smooth" });
}

// WIP: Delete trick confirmation modal
document.addEventListener("DOMContentLoaded", function(event) {
    const deleteButtons = document.querySelectorAll('.card-btn-delete');
    console.log(deleteButtons);
    deleteButtons.forEach((deleteButton) => {
        this.addEventListener('click', (event) => {
            event.preventDefault();
            console.log('test');
        }
    )});
  });
