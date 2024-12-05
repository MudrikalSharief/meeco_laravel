document.addEventListener("DOMContentLoaded", () => {
    // Modal elements
    const casualModal = document.getElementById("casual-subscription-modal");
    const regularModal = document.getElementById("regular-subscription-modal");
    const vipModal = document.getElementById("vip-subscription-modal");

    // Open buttons
    const openCasualModalBtn = document.getElementById("open-casual-modal");
    const openRegularModalBtn = document.getElementById("open-regular-modal");
    const openVipModalBtn = document.getElementById("open-vip-modal");

    // Close buttons
    const closeCasualModalBtn = document.getElementById("close-casual-modal-btn");
    const closeRegularModalBtn = document.getElementById("close-regular-modal-btn");
    const closeVipModalBtn = document.getElementById("close-vip-modal-btn");

    // Utility function to show a modal
    const showModal = (modal) => {
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };

    // Utility function to hide a modal
    const hideModal = (modal) => {
        modal.classList.remove("flex");
        modal.classList.add("hidden");
    };

    // Add event listeners for opening modals
    openCasualModalBtn.addEventListener("click", () => showModal(casualModal));
    openRegularModalBtn.addEventListener("click", () => showModal(regularModal));
    openVipModalBtn.addEventListener("click", () => showModal(vipModal));

    // Add event listeners for closing modals
    closeCasualModalBtn.addEventListener("click", () => hideModal(casualModal));
    closeRegularModalBtn.addEventListener("click", () => hideModal(regularModal));
    closeVipModalBtn.addEventListener("click", () => hideModal(vipModal));

    // Close modal when clicking outside of it
    [casualModal, regularModal, vipModal].forEach((modal) => {
        modal.addEventListener("click", (event) => {
            if (event.target === modal) hideModal(modal);
        });
    });
});
    