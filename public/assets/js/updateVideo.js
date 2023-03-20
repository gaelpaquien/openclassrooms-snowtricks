window.addEventListener('load', () => {
    const updateButtons = document.querySelectorAll('.update-trick-video-modal');
    const modalVideo = document.getElementById('modal-video');
    const trickId = document.getElementById('trick_video_form_trickId');
    const oldVideo = document.getElementById('trick_video_form_oldVideo');
    const confirmModal = document.getElementById('confirm-modal-update-video');
    const confirmYesButton = document.getElementById('confirm-yes-update-video');
    const confirmNoButton = document.getElementById('confirm-no-update-video');
    const mobileMedia = document.getElementById('mobileMediasModal');

    updateButtons.forEach(button => {
        button.addEventListener('click', event => {
            mobileMedia.style.setProperty("display", "none", "important");
            const url = button.getAttribute('data-video-url');
            modalVideo.src = url;
            oldVideo.value = url;
            trickId.value = button.getAttribute('data-trick-id');
            confirmModal.style.setProperty("display", "block", "important");
        });
    });

    confirmNoButton.addEventListener('click', () => {
        confirmModal.style.setProperty("display", "none", "important");
    });

    confirmYesButton.addEventListener('click', () => {
        confirmModal.style.setProperty("display", "none", "important");
    });
});
