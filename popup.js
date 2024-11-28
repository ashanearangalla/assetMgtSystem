document.addEventListener('DOMContentLoaded', () => {
    const addButton = document.getElementById("add-btn");
    const overlay = document.getElementById("popup-overlay");
    const closePopupButton = document.getElementById("close-popup");
    const popup = document.getElementById("popup-box");
    const confirmButton = document.getElementById("confirm-add");

    const disableScrolling = () => {
        document.body.style.overflow = "hidden";
    };

    // Function to enable scrolling
    const enableScrolling = () => {
        document.body.style.overflow = "auto";
    };

    // Show popup
    addButton.addEventListener("click", (e) => {
        e.preventDefault();
        overlay.style.display = "flex";
        popup.style.display = "block";
        disableScrolling();
    });

    // Hide popup
    closePopupButton.addEventListener("click", () => {
        overlay.style.display = "none";
        popup.style.display = "none";
        enableScrolling();
    });

    overlay.addEventListener("click", () => {
        overlay.style.display = "none";
        popup.style.display = "none";
        enableScrolling();
    })

    // Handle confirm button
    confirmButton.addEventListener("click", () => {
        alert("Item added successfully!");
        overlay.style.display = "none";
        popup.style.display = "none";
        enableScrolling();
    });
});