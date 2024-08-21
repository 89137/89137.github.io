function logout() {
    fetch('src/components/logout.php', {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'index.php'; // Redirect to index.php after successful logout
        } else {
            console.error('Logout failed');
        }
    })
    .catch(error => console.error('Error:', error));
}
