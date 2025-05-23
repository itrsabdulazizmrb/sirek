/**
 * DataTables initialization script
 * This script initializes DataTables with export functionality for all tables with class 'datatable'
 */
document.addEventListener('DOMContentLoaded', function() {
  // Indonesian language for DataTables
  const indonesianLanguage = {
    "emptyTable": "Tidak ada data yang tersedia pada tabel ini",
    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
    "infoFiltered": "(disaring dari _MAX_ entri keseluruhan)",
    "infoPostFix": "",
    "thousands": ".",
    "lengthMenu": "Tampilkan _MENU_ entri",
    "loadingRecords": "Memuat...",
    "processing": "Memproses...",
    "search": "Cari:",
    "zeroRecords": "Tidak ditemukan data yang sesuai",
    "paginate": {
      "first": "Pertama",
      "last": "Terakhir",
      "next": "Selanjutnya",
      "previous": "Sebelumnya"
    },
    "aria": {
      "sortAscending": ": aktifkan untuk mengurutkan kolom ke atas",
      "sortDescending": ": aktifkan untuk mengurutkan kolom ke bawah"
    },
    "buttons": {
      "copy": "Salin",
      "copyTitle": "Salin ke Clipboard",
      "copySuccess": {
        "_": "Menyalin %d baris ke clipboard",
        "1": "Menyalin 1 baris ke clipboard"
      },
      "print": "Cetak",
      "excel": "Excel",
      "pdf": "PDF",
      "csv": "CSV",
      "colvis": "Visibilitas Kolom"
    }
  };

  // Initialize all tables with class 'datatable'
  const tables = document.querySelectorAll('table.table');
  
  tables.forEach(function(table) {
    // Add datatable class to all tables
    table.classList.add('datatable');
    
    // Skip tables that don't have a thead element
    if (!table.querySelector('thead')) {
      return;
    }
    
    try {
      // Initialize DataTable with export buttons
      const dataTable = $(table).DataTable({
        language: indonesianLanguage,
        responsive: true,
        dom: 'Bfrtip',
        lengthMenu: [
          [10, 25, 50, -1],
          ['10 baris', '25 baris', '50 baris', 'Tampilkan semua']
        ],
        buttons: [
          {
            extend: 'copy',
            className: 'btn btn-sm btn-outline-primary'
          },
          {
            extend: 'csv',
            className: 'btn btn-sm btn-outline-primary'
          },
          {
            extend: 'excel',
            className: 'btn btn-sm btn-outline-primary'
          },
          {
            extend: 'pdf',
            className: 'btn btn-sm btn-outline-primary'
          },
          {
            extend: 'print',
            className: 'btn btn-sm btn-outline-primary'
          },
          {
            extend: 'colvis',
            className: 'btn btn-sm btn-outline-primary'
          }
        ],
        // Preserve existing dropdown functionality by excluding the last column (action column)
        columnDefs: [
          {
            targets: -1,
            orderable: false,
            searchable: false
          }
        ],
        // Custom styling for DataTables elements
        initComplete: function() {
          // Style the buttons container
          $('.dt-buttons').addClass('mb-3');
          
          // Add spacing between buttons
          $('.dt-button').addClass('me-2');
          
          // Style the search input
          $('.dataTables_filter input').addClass('form-control form-control-sm');
          $('.dataTables_filter input').attr('placeholder', 'Cari...');
          
          // Style the length menu
          $('.dataTables_length select').addClass('form-select form-select-sm');
        }
      });
    } catch (error) {
      console.error('Error initializing DataTable:', error);
    }
  });
});
