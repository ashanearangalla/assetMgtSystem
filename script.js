document.addEventListener('DOMContentLoaded', () => {
    const addButton = document.getElementById("add-btn");
    const overlay = document.getElementById("popup-overlay");
    const closePopupButton = document.getElementById("close-popup");
    const confirmButton = document.getElementById("confirm-add");

    // Show popup
    addButton.addEventListener("click", (e) => {
        e.preventDefault();
        overlay.style.display = "flex";
    });

    // Hide popup
    closePopupButton.addEventListener("click", () => {
        overlay.style.display = "none";
    });

    overlay.addEventListener("click", () => {
        overlay.style.display = "none";
    })

    // Handle confirm button
    confirmButton.addEventListener("click", () => {
        alert("Item added successfully!");
        overlay.style.display = "none";
    });
});