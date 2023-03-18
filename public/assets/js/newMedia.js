// Modal to confirm trick deleted
window.addEventListener('load', () => {
    const updateButtons = document.querySelectorAll('.update-trick-modal');
    const modalImage = document.getElementById('modal-image');
    const trickId = document.getElementById('trick_image_form_trickId');
    const currentImage = document.getElementById('trick_image_form_currentImage');
    const confirmModal = document.getElementById('confirm-modal-update');
    const confirmYesButton = document.getElementById('confirm-yes-update');
    const confirmNoButton = document.getElementById('confirm-no-update');

    updateButtons.forEach(button => {
        button.addEventListener('click', event => {
            const image = button.getAttribute('data-image-name');
            modalImage.src = '/assets/uploads/tricks/mini/300x300-' + image;
            currentImage.value = image;
            trickId.value = button.getAttribute('data-trick-id');
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
