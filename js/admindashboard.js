 
 const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            content.classList.toggle('shrink');
        });


    // let formToSubmit = null;

    // function confirmDelete(event) {
    //     event.preventDefault();
    //     formToSubmit = event.target;
    //     document.getElementById('modal-section').style.display = 'flex';
    //     return false;
    // }

    // function confirmDeletion() {
    //     if (formToSubmit) {
    //         formToSubmit.submit();
    //     }
    //     closeModal();
    // }

    // function closeModal() {
    //     document.getElementById('modal-section').style.display = 'none';
    //     formToSubmit = null;
    // }


document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-input-student-section');
    const clearButton = document.getElementById('clear-btn-student-section');
    const tableBody = document.getElementById('student-table-body-student-section');
    const originalTableContent = tableBody.innerHTML;
    searchInput.addEventListener('input', () => {
        clearButton.style.display = searchInput.value ? 'inline' : 'none';
        if (!searchInput.value.trim()) {
            tableBody.innerHTML = originalTableContent; 
        }
    });
 clearButton.addEventListener('click', () => {
        searchInput.value = ''; 
        clearButton.style.display = 'none'; 
        tableBody.innerHTML = originalTableContent;
        searchInput.focus(); 
    });
});

$('#search-input-student-section').on('keyup', function () {
    var query = $(this).val().trim();

    if (query) {
        $.ajax({
            url: 'searchStudent.php',
            method: 'GET',
            data: { query: query },
            success: function (response) {
                $('#student-table-body-student-section').html(response);
            },
            error: function () {
                console.error('Error fetching search results.');
            }
        });
    } else {
        const tableBody = document.getElementById('student-table-body-student-section');
        tableBody.innerHTML = originalTableContent;
    }
});


        function closeModal() {
    const modals = document.querySelectorAll('.modal-section');
    modals.forEach(modal => modal.style.display = 'none');
    formToSubmit = null;
}

        
    let formToSubmit = null;

    function confirmDelete(event) {
        event.preventDefault();
        formToSubmit = event.target;
        document.getElementById('modal-section').style.display = 'flex';
        return false;
    }

    function confirmDeletion() {
        if (formToSubmit) {
            formToSubmit.submit();
        }
        closeModal();
    }

    function closeModal() {
        document.getElementById('modal-section').style.display = 'none';
        formToSubmit = null;
    }
        function openModal(student) {
        showSection('edit-student');
        document.getElementById('edit-student-id').value = student.id;
        document.getElementById('edit-firstname').value = student.firstname;
        document.getElementById('edit-middlename').value = student.middlename || "";
        document.getElementById('edit-lastname').value = student.lastname;
        document.getElementById('edit-age').value = student.age;
        document.getElementById('edit-gender').value = student.gender;
        document.getElementById('edit-phone').value = student.phone;
        document.getElementById('edit-address').value = student.address;
    }
        function showSection(section) {
            const sections = document.querySelectorAll(".section-content");
            const links = document.querySelectorAll(".sidebar ul li a");
            sections.forEach(sec => sec.style.display = "none");
            links.forEach(link => link.classList.remove("active"));
            document.getElementById(section).style.display = "block";
            const activeLink = document.querySelector(`.sidebar ul li a[data-section="${section}"]`);
            if (activeLink) activeLink.classList.add("active");
        }
        window.onload = function () {
            showSection('dashboard'); 
        }
