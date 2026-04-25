document.addEventListener('DOMContentLoaded', () => {
 
document.querySelector('#products-link').addEventListener('click', function(event) {
        event.preventDefault();
        loadPage('product.php');
    });

    
document.querySelector('#category-link').addEventListener('click', function(event) {
        event.preventDefault();
        loadPage('category.php');

});


document.querySelector('#orders-link').addEventListener('click', function(event) {
    event.preventDefault();
    loadPage('order.php');

});

document.querySelector('#customers-link').addEventListener('click', function(event) {
    event.preventDefault();
    loadPage('view-customers.php');

});

document.querySelector('#reviews-link').addEventListener('click', function(event) {
    event.preventDefault();
    loadPage('review.php');

});

document.querySelector('#reports-link').addEventListener('click', function(event) {
    event.preventDefault();
    loadPage('reports.php');

});





});

function loadPage(page) {
    $.ajax({
        url: page,
        type: 'GET',
        success: function(response) {
            $('#main-content').html(response);
        },
        error: function(xhr, status, error) {
            console.error("Error loading page: ", error);
        }
    });
}


