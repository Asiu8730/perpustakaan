
/* Modal Tambah */
function openAddModal() {
    document.getElementById("addModal").style.display = "block";
}
function closeAddModal() {
    document.getElementById("addModal").style.display = "none";
}

/* Modal Edit */
function openEditModal(id, title, author, publisher, category, publish_date, stock) {
    console.log("Edit modal terbuka", id, title, category); // debug

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_title").value = title;
    document.getElementById("edit_author").value = author;
    document.getElementById("edit_publisher").value = publisher;
    document.getElementById("edit_category").value = category;
    document.getElementById("edit_publish_date").value = publish_date;
    document.getElementById("edit_stock").value = stock;

    document.getElementById("editModal").style.display = "block";
}

function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

/* Tutup modal kalau klik luar */
window.onclick = function(event) {
    let addModal = document.getElementById("addModal");
    let editModal = document.getElementById("editModal");
    if (event.target == addModal) addModal.style.display = "none";
    if (event.target == editModal) editModal.style.display = "none";
}

