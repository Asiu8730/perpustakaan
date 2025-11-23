function openConfirmModal(id, title, borrower, type) {
    document.getElementById("loan_id").value = id;
    document.getElementById("book_title").value = title;
    document.getElementById("borrower_name").value = borrower;
    document.getElementById("action_type").value = type;

    const modal = document.getElementById("confirmModal");
    const returnDateContainer = document.getElementById("returnDateContainer");
    const modalTitle = document.getElementById("modalTitle");
    const returnDateInput = document.getElementById("return_date");

    if (type === "peminjaman") {
        returnDateContainer.style.display = "block";
        modalTitle.textContent = "Konfirmasi Peminjaman Buku";
        returnDateInput.value = ""; 
    } else {
        returnDateContainer.style.display = "none";
        modalTitle.textContent = "Konfirmasi Pengembalian Buku";
    }

    modal.style.display = "block";
}

// ✅ TAMBAH INI
function closeConfirmModal() {
    document.getElementById("confirmModal").style.display = "none";
}
