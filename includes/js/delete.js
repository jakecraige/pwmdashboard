function confirmDelete(id) {
        delUrl = window.location + "?mode=delete&id=" + id;
        if(confirm("Are you sure you want to delete this?")) {
                window.location = delUrl;
        }
}