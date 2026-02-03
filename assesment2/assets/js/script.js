// Get the search input field
const search = document.getElementById("search");

// Get the div where results will be shown
const result = document.getElementById("result");

// Listen for typing in the search box
search.addEventListener("keyup", function () {

    // Send the typed value to search.php using fetch
    fetch("search.php?q=" + this.value)
        // Convert response to text
        .then(res => res.text())
        // Show the returned data inside result div
        .then(data => result.innerHTML = data);

});
