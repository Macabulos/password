document.addEventListener('DOMContentLoaded', function() {
    const logoutBtn = document.getElementById('logout-btn');
    const modal = document.getElementById('myModal');
    const closeBtn = document.querySelector('.close');
    const confirmLogoutBtn = document.getElementById('confirm-logout');
    const cancelBtn = document.querySelector('.cancel-btn');

    logoutBtn.addEventListener('click', function(event) {
        event.preventDefault();
        modal.style.display = "block";
    });

    closeBtn.addEventListener('click', function() {
        modal.style.display = "none";
    });

    cancelBtn.addEventListener('click', function() {
        modal.style.display = "none";
    });

    confirmLogoutBtn.addEventListener('click', function() {
        window.location.href = 'logout.php';
    });

    window.addEventListener('click', function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });
});