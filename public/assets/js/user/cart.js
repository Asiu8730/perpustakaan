document.addEventListener("DOMContentLoaded", () => {
  const addToCartBtn = document.getElementById("addToCartBtn");

  if (addToCartBtn) {
    addToCartBtn.addEventListener("click", async () => {
      const bookId = addToCartBtn.getAttribute("data-id");

      try {
        // ✅ Path disesuaikan karena semua aksi dilakukan dari dashboard_user.php di folder /public
        const response = await fetch(
          `dashboard_user.php?page=cart&action=add&id=${bookId}`,
          { headers: { "X-Requested-With": "XMLHttpRequest" } }
        );

        const result = await response.json();

        // ✅ Gunakan showNotification dari notification.js
        if (typeof showNotification === "function") {
          showNotification(result.message);
        } else {
          alert(result.message);
        }
      } catch (error) {
        console.error("Error:", error);
        showNotification("Terjadi kesalahan. Coba lagi.");
      }
    });
  }
});
