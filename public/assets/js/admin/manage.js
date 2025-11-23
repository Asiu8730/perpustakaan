function openAddModal() { 
    document.getElementById("addModal").style.display = "block"; 
}
function closeAddModal() { 
    document.getElementById("addModal").style.display = "none"; 
}

function openEditModal(id, title, author, publisher, category, publish_date, description, status, stock) {

    document.getElementById("edit_id").value = id;
    document.getElementById("edit_title").value = title;
    document.getElementById("edit_author").value = author;
    document.getElementById("edit_publisher").value = publisher;

    document.getElementById("edit_category").value = category;
    document.getElementById("edit_publish_date").value = publish_date;

    document.getElementById("edit_description").value = description;

    document.getElementById("edit_status").value = status;
    document.getElementById("edit_stock").value = stock;

    document.getElementById("editModal").style.display = "block";
}

function closeEditModal() { 
    document.getElementById("editModal").style.display = "none"; 
}

document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById("edit_id").value = btn.dataset.id;
        document.getElementById("edit_title").value = btn.dataset.title;
        document.getElementById("edit_author").value = btn.dataset.author;
        document.getElementById("edit_publisher").value = btn.dataset.publisher;
        document.getElementById("edit_category").value = btn.dataset.category;
        document.getElementById("edit_publish_date").value = btn.dataset.publishDate;
        document.getElementById("edit_description").value = btn.dataset.description;
        document.getElementById("edit_status").value = btn.dataset.status;
        document.getElementById("edit_stock").value = btn.dataset.stock;

        document.getElementById("editModal").style.display = "block";
    });
});


