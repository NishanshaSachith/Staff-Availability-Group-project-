document.querySelectorAll('.dropdown').forEach(dropdown => {
    dropdown.addEventListener('click', function(event) {
        event.preventDefault(); // Prevent default anchor click behavior
        const dropdownContent = this.querySelector('.dropdown-content');
        dropdownContent.classList.toggle('show'); // Toggle dropdown visibility
    });
});

// Close dropdown if clicked outside
window.addEventListener('click', function(e) {
    if (!e.target.matches('.dropdown a')) {
        document.querySelectorAll('.dropdown-content').forEach(dropdownContent => {
            dropdownContent.classList.remove('show'); // Remove 'show' class to hide dropdown
        });
    }
});
