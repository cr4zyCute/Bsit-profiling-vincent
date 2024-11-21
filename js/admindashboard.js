 const hamburger = document.getElementById('hamburger');
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('active');
            content.classList.toggle('shrink');
        });


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


