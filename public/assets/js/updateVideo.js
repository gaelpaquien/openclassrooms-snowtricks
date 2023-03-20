window.addEventListener('load', () => {
    const updateButtons = document.querySelectorAll('.update-trick-video-modal');
    const modalVideo = document.getElementById('modal-video');
    const trickId = document.getElementById('trick_video_form_trickId');
    const oldVideo = document.getElementById('trick_video_form_oldVideo');
    const confirmModal = document.getElementById('confirm-modal-update-video');
    const confirmYesButton = document.getElementById('confirm-yes-update-video');
    const confirmNoButton = document.getElementById('confirm-no-update-video');
    const mobileMedia = document.getElementById('mobileMediasModal');
    let url = null;

    updateButtons.forEach(button => {
        button.addEventListener('click', event => {
            // Set URL to redirect to if user clicks "No"
            url = button.getAttribute('data-url');
            // Disable mobile media modal
            mobileMedia.style.setProperty("display", "none", "important");
            // Get video URL
            const videoURL = button.getAttribute('data-video-url');
            // Set video source
            modalVideo.src = videoURL;
            // Set values for hidden fields
            oldVideo.value = videoURL;
            trickId.value = button.getAttribute('data-trick-id');
            // Display modal
            confirmModal.style.setProperty("display", "block", "important");
        });
    });

    confirmNoButton.addEventListener('click', () => {
        confirmModal.style.setProperty("display", "none", "important");
        location.href = url;
    });

    confirmYesButton.addEventListener('click', () => {
        confirmModal.style.setProperty("display", "none", "important");
    });
});
