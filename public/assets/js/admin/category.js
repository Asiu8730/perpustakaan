
function openAddModal() {
    document.getElementById("addModal").style.display = "block";
}
function closeAddModal() {
    document.getElementById("addModal").style.display = "none";
}
function openEditModal(id, name) {
    document.getElementById("edit_id").value = id;
    document.getElementById("edit_name").value = name;
    document.getElementById("editModal").style.display = "block";
}
function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}
window.onclick = function(event) {
    let addModal = document.getElementById("addModal");
    let editModal = document.getElementById("editModal");
    if (event.target == addModal) addModal.style.display = "none";
    if (event.target == editModal) editModal.style.display = "none";
}
