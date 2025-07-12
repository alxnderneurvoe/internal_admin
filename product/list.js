// categories.js

const categories = [
    { id: "", name: "Semua" },
    { id: "Aksesoris Perangkat Jaringan", name: "Aksesoris Perangkat Jaringan" },
    { id: "Alat Peraga Edukatif", name: "Alat Peraga Edukatif" },
    { id: "Buku Pendidikan", name: "Buku Pendidikan" },
    { id: "Furnitur Kantor/Sekolah", name: "Furnitur Kantor/Sekolah" },
    { id: "Laptop/PC/AiO", name: "Laptop/PC/AiO" },
    { id: "Laptop", name: "Laptop" },
    { id: "Meja dan Kursi Guru", name: "Meja dan Kursi Guru" },
    { id: "Meja dan Kursi Siswa", name: "Meja dan Kursi Siswa" },
    { id: "Meja dan Kursi Paud", name: "Meja dan Kursi Paud" },
    { id: "Mesin Welding Pipe", name: "Mesin Welding Pipe" },
    { id: "Pipa dan Fitting HDPE", name: "Pipa dan Fitting HDPE" },
    { id: "Pipa dan Fitting Limbah", name: "Pipa dan Fitting Limbah" },
    { id: "Pipa dan Fitting PPR", name: "Pipa dan Fitting PPR" },
    { id: "Pipa dan Fitting PVC", name: "Pipa dan Fitting PVC" }
];

const unit = [
    { id: "", name: "Pilih Satuan" },
    { id: "Pcs", name: "Pcs" },
    { id: "Meter", name: "Meter" },
    { id: "Unit", name: "Unit" },
    { id: "Paket", name: "Paket" },
    { id: "Roll-50m", name: "Roll-50m" },
    { id: "Roll-100m", name: "Roll-100m" },

];

function populateCategories() {
    const selectElement = document.getElementById('category-select');
    selectElement.innerHTML = '';

    categories.forEach(category => {
        const option = document.createElement('option');
        option.value = category.id;
        option.textContent = category.name;

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('category') === category.id) {
            option.selected = true;
        }

        selectElement.appendChild(option);
    });
}

function loadCategories() {
    fetch('get_categories.php')
        .then(response => response.json())
        .then(data => {
            const categorySelects = document.querySelectorAll('#category-select, #productCategory');
            categorySelects.forEach(select => {
                select.innerHTML = '<option value="">Select Category</option>';
                data.forEach(cat => {
                    const option = document.createElement('option');
                    option.value = cat;
                    option.textContent = cat;
                    select.appendChild(option);
                });
            });
        })
        .catch(error => console.error('Failed to load categories:', error));
}


function loadUnit() {
    const unitSelect = document.getElementById("productUnit");

    unit.forEach(unit => {
        const option = document.createElement("option");
        option.value = unit.id;
        option.textContent = unit.name;
        unitSelect.appendChild(option);
    });
}
