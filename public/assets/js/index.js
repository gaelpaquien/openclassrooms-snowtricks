// Scroll to tricks section in the homepage
function scrollToTricks() {
    const element = document.getElementById("tricks");
    element.scrollIntoView({ behavior: "smooth" });
}

// Modal to confirm trick deleted
window.addEventListener('load', () => {
    const deleteButtons = document.querySelectorAll('.card-btn-delete');
    const confirmModal = document.getElementById('confirm-modal');
    const confirmMessage = document.getElementById('confirm-message');
    const confirmYesButton = document.getElementById('confirm-yes');
    const confirmNoButton = document.getElementById('confirm-no');
    let url = null;

    deleteButtons.forEach(button => {
        button.addEventListener('click', event => {
            const message = button.getAttribute('data-confirm-message');
            url = button.getAttribute('data-url');
            confirmMessage.textContent = message;
            confirmModal.style.setProperty("display", "block", "important");
        });
    });

    confirmNoButton.addEventListener('click', () => {
        confirmModal.style.setProperty("display", "none", "important");
    });

    confirmYesButton.addEventListener('click', () => {
        confirmModal.style.setProperty("display", "none", "important");
        location.href = url;
    });
});
