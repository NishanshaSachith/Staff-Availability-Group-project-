function showLogin() {
    document.getElementById("loginForm").classList.remove("hidden");
    document.getElementById("registerForm").classList.add("hidden");
    document.getElementById("adminLoginForm").classList.add("hidden");
    document.getElementById("loginTab").classList.add("active");
    document.getElementById("registerTab").classList.remove("active");
    document.getElementById("adminLoginTab").classList.remove("active");
}

function showRegister() {
    document.getElementById("loginForm").classList.add("hidden");
    document.getElementById("registerForm").classList.remove("hidden");
    document.getElementById("adminLoginForm").classList.add("hidden");
    document.getElementById("registerTab").classList.add("active");
    document.getElementById("loginTab").classList.remove("active");
    document.getElementById("adminLoginTab").classList.remove("active");
}

function showAdminLogin() {
    document.getElementById("loginForm").classList.add("hidden");
    document.getElementById("registerForm").classList.add("hidden");
    document.getElementById("adminLoginForm").classList.remove("hidden");
    document.getElementById("adminLoginTab").classList.add("active");
    document.getElementById("loginTab").classList.remove("active");
    document.getElementById("registerTab").classList.remove("active");
}

// Handle the display of the position input based on selected role
document.getElementById("register-role").addEventListener("change", function() {
    const positionGroup = document.getElementById("position-group");
    if (this.value === "staff") {
        positionGroup.style.display = "block";
    } else {
        positionGroup.style.display = "none";
    }
});
