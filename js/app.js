// Function to Open Login Popup
function openLogin() {
    document.getElementById("loginPopup").style.display = "flex";
}

// Function to Close Login Popup
function closeLogin() {
    document.getElementById("loginPopup").style.display = "none";
}

// Close popup when clicking outside the modal
window.onclick = function(event) {
    var popup = document.getElementById("loginPopup");
    if (event.target === popup) {
        popup.style.display = "none";
    }
};
