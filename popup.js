function showForgotPasswordPopup() {
	document.getElementById("forgot-password-popup").style.display = "block";
	}
function hideForgotPasswordPopup() {
    document.getElementById("forgot-password-popup").style.display = "none";
}
document.getElementById("forgot-password-link").addEventListener("click", showForgotPasswordPopup);
document.getElementById("forgot-password-submit").addEventListener("click", hideForgotPasswordPopup);
