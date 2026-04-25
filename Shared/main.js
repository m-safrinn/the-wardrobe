function openNav() {
    document.getElementById("mySidenav").style.width = "550px";
    document.getElementById("overlay").classList.add("active");
    document.body.classList.add("no-scroll"); // Disable scrolling
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("overlay").classList.remove("active");
    document.body.classList.remove("no-scroll"); // Enable scrolling
}

document.getElementById('cartIcon').addEventListener('click', openNav);
document.getElementById('startShoppingBtn').addEventListener('click', closeNav);

// Close nav when clicking on the overlay
document.getElementsByName('overlay').addEventListener('click', closeNav);

function toggleFilter() {
    const filterContainer = document.getElementById('filters');
    filterContainer.classList.toggle('open');
}
