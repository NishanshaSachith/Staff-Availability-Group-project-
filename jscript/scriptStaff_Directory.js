document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const categorySelect = document.getElementById('categorySelect');
    const staffProfiles = document.querySelectorAll('.staff-profile');

    // Function to filter staff profiles
    function filterStaff() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedCategory = categorySelect.value.toLowerCase(); // Convert to lowercase

        staffProfiles.forEach(profile => {
            const name = profile.querySelector('h3').textContent.toLowerCase();
            const position = profile.querySelector('p').textContent.toLowerCase();
            const category = profile.getAttribute('data-category').toLowerCase(); // Convert to lowercase

            const matchesSearch = name.includes(searchTerm) || position.includes(searchTerm);
            const matchesCategory = selectedCategory === '' || category === selectedCategory;

            // Show or hide the profile based on the filters
            profile.style.display = matchesSearch && matchesCategory ? 'block' : 'none';
        });
    }

    // Event listeners for the search input and category select
    searchInput.addEventListener('input', filterStaff);
    categorySelect.addEventListener('change', () => {
        searchInput.value = ''; // Clear search input
        filterStaff(); // Apply filter
    });
});
