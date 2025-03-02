// No sidebar toggle is needed now, so this function can be removed or kept empty
function toggleSidebar() {
    // No functionality needed anymore for sidebar
}

function toggleFields() {
    const documentType = document.getElementById('document_type').value;
    const invoiceNumberGroup = document.getElementById('invoice_number_group');
    const validityPeriodGroup = document.getElementById('validity_period_group');

    if (documentType === 'invoice') {
        invoiceNumberGroup.style.display = 'block';
        validityPeriodGroup.style.display = 'none';
    } else {
        invoiceNumberGroup.style.display = 'none';
        validityPeriodGroup.style.display = 'block';
    }
}
// Initialize fields based on the selected document type
toggleFields();

function formatNIKNPWP(event) {
    let input = event.target;
    let value = input.value.replace(/\D/g, ''); // Hapus karakter selain angka
    let formattedValue = '';

    // Format sesuai pola (XXXX XXXX XXXX XXXX)
    for (let i = 0; i < value.length; i++) {
        if (i === 4 || i === 8 || i === 12) {
            formattedValue += ' ';
        }
        formattedValue += value[i];
    }

    input.value = formattedValue.trim();
}

function getRomanMonth(monthIndex) {
    const romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
    return romanMonths[monthIndex - 1]; // Konversi bulan ke angka Romawi (1 -> I, 2 -> II, dst)
}

// Fungsi untuk memformat No Invoice
function formatInvoice(event) {
            let input = event.target;
            let value = input.value.replace(/\D/g, ''); // Hapus karakter selain angka

            // Array Bulan dalam angka Romawi
            const romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

            // Mengambil dua digit pertama untuk kode XX
            let prefix = value.substring(0, 2);
            // Mengambil bulan (harus dua digit)
            let month = romanMonths[parseInt(value.substring(2, 4)) - 1] || '';
            // Mengambil tiga digit berikutnya untuk SSS
            // let sss = value.substring(4, 7);
            // Mengambil empat digit berikutnya untuk tahun YYYY
            let year = value.substring(7, 11);

            // Gabungkan bagian-bagian menjadi format yang diinginkan
            let formattedValue = `${prefix}/${month}/SSS/INV/${year}`;

            // Menyaring input agar tidak melebihi format yang diinginkan
            input.value = formattedValue;
        }

        document.getElementById('add_item').addEventListener('click', function() {
            // Create a new row for the table
            var table = document.getElementById('items_table').getElementsByTagName('tbody')[0];
            var newRow = table.insertRow();
    
            // Create cells for the new row
            var cell1 = newRow.insertCell(0); // No
            var cell2 = newRow.insertCell(1); // Item
            var cell3 = newRow.insertCell(2); // Qty
            var cell4 = newRow.insertCell(3); // Harga
            var cell5 = newRow.insertCell(4); // Disc
            var cell6 = newRow.insertCell(5); // Net
            var cell7 = newRow.insertCell(6); // Total
    
            // Set the content of each cell
            var rowIndex = table.rows.length;
            cell1.innerHTML = rowIndex; // No (Row index)
    
            // Item input
            cell2.innerHTML = '<input type="text" name="item[]" class="form-control" required>';
    
            // Quantity input
            cell3.innerHTML = '<input type="number" name="qty[]" class="form-control" required oninput="calculateNet(this)">';
            
            // Harga input
            cell4.innerHTML = '<input type="number" name="harga[]" class="form-control" required oninput="calculateNet(this)">';
    
            // Discount input
            cell5.innerHTML = '<input type="number" name="disc[]" class="form-control" value="0" required oninput="calculateNet(this)">';
    
            // Net value (calculated)
            cell6.innerHTML = '<input type="number" name="net[]" class="form-control" readonly>';
    
            // Total value (calculated)
            cell7.innerHTML = '<input type="number" name="total[]" class="form-control" readonly>';
    
            // Update total after item added
            updateTotal();
        });
    
        function calculateNet(element) {
            var row = element.closest('tr');
            var qty = row.querySelector('[name="qty[]"]').value;
            var harga = row.querySelector('[name="harga[]"]').value;
            var disc = row.querySelector('[name="disc[]"]').value;
    
            // Calculate Net and Total
            var net = (qty * harga) - (qty * harga * (disc / 100));
            var total = net; // Assuming no additional calculation for now
    
            // Set the values to the Net and Total fields
            row.querySelector('[name="net[]"]').value = net.toFixed(2);
            row.querySelector('[name="total[]"]').value = total.toFixed(2);
    
            // Update the overall total amount
            updateTotal();
        }
    
        function updateTotal() {
            var totalAmount = 0;
            var totalElements = document.querySelectorAll('[name="total[]"]');
    
            totalElements.forEach(function(totalElement) {
                totalAmount += parseFloat(totalElement.value) || 0;
            });
    
            // Set the total amount
            document.getElementById('amount').value = totalAmount.toFixed(2);
        }

        document.getElementById('save_item').addEventListener('click', function() {
            // Get values from the modal form inputs
            var itemName = document.getElementById('item_name').value;
            var qty = document.getElementById('item_qty').value;
            var price = document.getElementById('item_price').value;
            var discount = document.getElementById('item_discount').value;
        
            if (!itemName || !qty || !price) {
              alert("Please fill in all fields");
              return; // Exit if fields are empty
            }
        
            // Create new row for the items table
            var tableBody = document.getElementById('items_table_body');
            var newRow = tableBody.insertRow();
        
            // Add cells for the new item
            var cell1 = newRow.insertCell(0); // No
            var cell2 = newRow.insertCell(1); // Item
            var cell3 = newRow.insertCell(2); // Qty
            var cell4 = newRow.insertCell(3); // Harga
            var cell5 = newRow.insertCell(4); // Disc
            var cell6 = newRow.insertCell(5); // Net
            var cell7 = newRow.insertCell(6); // Total
        
            // Insert the data into each cell
            var rowIndex = tableBody.rows.length;
            cell1.innerHTML = rowIndex; // No (Row index)
            cell2.innerHTML = itemName; // Item Name
            cell3.innerHTML = qty; // Quantity
            cell4.innerHTML = price; // Price
            cell5.innerHTML = discount; // Discount
            var net = (qty * price) - (qty * price * (discount / 100)); // Calculate Net Price
            var total = net; // Assuming no additional calculations
            cell6.innerHTML = net.toFixed(2); // Net Price
            cell7.innerHTML = total.toFixed(2); // Total Price
        
            // Close the modal
            $('#addItemModal').modal('hide');
        
            // Clear the input fields in the modal
            document.getElementById('item_name').value = '';
            document.getElementById('item_qty').value = '';
            document.getElementById('item_price').value = '';
            document.getElementById('item_discount').value = '0';
        
            // Update total amount
            updateTotal();
          });
        
          // Function to update the total amount
          // Function to update Subtotal, Pajak (Tax), and Grand Total
        function updateTotal() {
            var subtotal = 0;
            var totalElements = document.querySelectorAll('#items_table_body .table td:nth-child(7)'); // Total cells of each item
        
            totalElements.forEach(function(totalElement) {
                subtotal += parseFloat(totalElement.innerHTML) || 0;
            });
        
            // Update the Subtotal field
            document.getElementById('subtotal').value = subtotal.toFixed(2);
        
            // Calculate Pajak (Tax)
            var pajak = subtotal * 0.11; // 11% tax
            document.getElementById('pajak').value = pajak.toFixed(2);
        
            // Get Ongkir (Shipping)
            var ongkir = parseFloat(document.getElementById('ongkir').value) || 0;
        
            // Calculate Grand Total
            var grandTotal = subtotal + pajak + ongkir;
            document.getElementById('grand_total').value = grandTotal.toFixed(2);
        }
        
        // Call updateTotal() whenever an item is added
        document.getElementById('save_item').addEventListener('click', function() {
            // ... (existing code to save the item)
        
            // After saving the item, update the totals
            updateTotal();
        });
        
        // Optionally, call updateTotal() when the shipping cost (Ongkir) is changed
        document.getElementById('ongkir').addEventListener('input', function() {
            updateTotal();
        });

        function formatQuantity() {
            let input = document.getElementById("item_qty");
            let value = input.value.replace(/\D/g, ""); // Menghapus karakter selain angka
            input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ","); // Menambahkan pemisah ribuan
          }