// categories.js

const categories = [
    { id: "Pipa HDPE", name: "Pipa HDPE" },
    { id: "Fitting HDPE", name: "Fitting HDPE" },
    // Tambah kategori lain di sini
];

// Fungsi untuk memuat kategori ke dalam dropdown
function loadCategories() {
    const categorySelect = document.getElementById("productCategory");

    categories.forEach(category => {
        const option = document.createElement("option");
        option.value = category.id;
        option.textContent = category.name;
        categorySelect.appendChild(option);
    });
}
