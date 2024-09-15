document.addEventListener("DOMContentLoaded", () => {
  let sidebar = document.querySelector(".sidebar");
  let closeBtn = document.querySelector("#btn");
  let searchBtn = document.querySelector(".bx-search");

  // Toggle sidebar open/close
  closeBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    menuBtnChange();
  });

  // Optionally handle sidebar toggle with search button
  searchBtn.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    menuBtnChange();
  });

  // Function to change sidebar button icon
  function menuBtnChange() {
    if (sidebar.classList.contains("open")) {
      closeBtn.classList.replace("bx-menu", "bx-menu-alt-right");
    } else {
      closeBtn.classList.replace("bx-menu-alt-right", "bx-menu");
    }
  }
  
  // Optionally handle sidebar opening when the page loads (if needed)
  // Remove the comment to enable this feature
  // sidebar.classList.add("open");
});
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
      window.location.href = 'admin_logout.php';
  });

  window.addEventListener('click', function(event) {
      if (event.target == modal) {
          modal.style.display = "none";
      }
  });
});